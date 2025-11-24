<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\LqCliente;
use App\Models\Peso;
use App\Models\PesoOtraBal;
use App\Models\PlProgramacion;
use App\Models\Proceso;
use App\Models\ProcesoPeso;
use App\Models\ProgramacionDetalle;
use App\Models\PsEstadoPeso;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlProgramacionController extends Controller
{
    public function index()
    {
        $lotes = Lote::with('pesos', 'pesos.estado', 'pesos.proceso')->get();
        $clientes = LqCliente::all();
        $programaciones = PlProgramacion::with('proceso.pesos', 'proceso.lote')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'fecha_inicio' => $p->fecha_inicio,
                'fecha_fin' => $p->fecha_fin,
                'lote_id' => $p->proceso->lote->id ?? null,
                'lote_nombre' => $p->proceso->lote->nombre ?? 'N/A',
                'circuito' => $p->proceso->circuito ?? 'N/A',
                'pesos' => $p->proceso->pesos->pluck('NroSalida')->toArray()
            ];
        });

        return view('programaciones.index', compact('lotes', 'programaciones', 'clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lote_id'       => 'required|exists:lotes,id',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'required|date|after:fecha_inicio',
            'pesos'         => 'required|array|min:1',
        ], [
            'lote_id.required'      => 'Debes seleccionar un lote.',
            'fecha_inicio.required' => 'Debes ingresar la fecha de inicio.',
            'fecha_fin.required'    => 'Debes ingresar la fecha de fin.',
            'fecha_fin.after'       => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'pesos.required'        => 'Debes seleccionar al menos un peso.',
        ]);

        try {
            DB::beginTransaction();

            $proceso = Proceso::create([
                'codigo' => $this->GenerarCodigoProceso($request),
                'lote_id' => $request->lote_id,
                'fecha_inicio' => Carbon::parse($request->fecha_inicio),
                'fecha_fin' => Carbon::parse($request->fecha_fin),
                'peso_total' => $request->peso_total,
                'circuito' => $request->circuito,

            ]);

            $programacion = PlProgramacion::create([
                'fecha_inicio' => Carbon::parse($request->fecha_inicio),
                'fecha_fin' => Carbon::parse($request->fecha_fin),
                'proceso_id' => $proceso->id,
            ]);

            foreach ($request->pesos as $pesoId) {
                ProcesoPeso::create([
                    'proceso_id' => $proceso->id,
                    'peso_id' => $pesoId,
                ]);

                $estado_peso = PsEstadoPeso::where('peso_id', $pesoId)->first();
                if ($estado_peso) {
                    $estado_peso->estado_id = 2;
                    $estado_peso->save();
                }

                ProgramacionDetalle::create([
                    'programacion_id' => $programacion->id,
                    'peso_id' => $pesoId,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Programación registrada correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al registrar la programación.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function datatable($id)
    {
        $programacion = PlProgramacion::with([
            'proceso.lote',
            'proceso.pesosOtrasBal.estado',
        ])->findOrFail($id);

        $pesosAsignados = ProgramacionDetalle::with('peso.estado')
            ->where('programacion_id', $id)
            ->get()
            ->map(function ($pp) {
                return [
                    'tipo' => 'balanza', // para distinguirlos
                    'NroSalida' => $pp->peso->NroSalida,
                    'Fechas' => $pp->peso->Fechas,
                    'Bruto' => $pp->peso->Bruto,
                    'Tara' => $pp->peso->Tara,
                    'Neto' => $pp->peso->Neto,
                    'Horai' => $pp->peso->Horai,
                    'Horas' => $pp->peso->Horas,
                    'estado_id' => $pp->peso->estado->id ?? null,
                    'estado_nombre' => $pp->peso->estado->nombre ?? '-',
                ];
            });

        $pesosOtrasBal = PesoOtraBal::with('estado')
            ->where('programacion_id', $id)
            ->get()
            ->map(function ($otra_balanza) {
                return [
                    'tipo' => 'manual', // para distinguirlos
                    'id' => $otra_balanza->id,
                    'fechai' => $otra_balanza->fechai,
                    'fechas' => $otra_balanza->fechas,
                    'bruto' => $otra_balanza->bruto,
                    'tara' => $otra_balanza->tara,
                    'neto' => $otra_balanza->neto,
                    'conductor' => $otra_balanza->conductor,
                    'estado_nombre' => $otra_balanza->estado->nombre ?? null,
                    'estado_id' => $otra_balanza->estado->id ?? null,
                    'balanza' => $otra_balanza->balanza,
                    'producto' => $otra_balanza->producto,
                    'placa' => $otra_balanza->placa,
                ];
            });

        $pesosTotales = $pesosAsignados->merge($pesosOtrasBal);

        return response()->json([
            'programacion' => $programacion,
            'pesos' => $pesosTotales,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lote_id' => 'required|exists:lotes,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        DB::transaction(function () use ($request, $id) {
            $programacion = PlProgramacion::findOrFail($id);
            $proceso = $programacion->proceso;

            ProcesoPeso::where('proceso_id', $proceso->id)->each(function ($pp) {
                if ($pp->peso) {
                    $estadoPeso = PsEstadoPeso::where('peso_id', $pp->peso->NroSalida)->first();
                    if ($estadoPeso) {
                        $estadoPeso->update(['estado_id' => 1]); // vuelve a CANCHA
                    }
                }
                $pp->delete();
            });

            ProgramacionDetalle::where('programacion_id', $programacion->id)->delete();

            $programacion->update([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);

            $proceso->update([
                'lote_id' => $request->lote_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'peso_total' => $request->peso_total,
                'circuito' => $request->circuito,
            ]);

            foreach ($request->pesos as $nro) {
                ProcesoPeso::create([
                    'proceso_id' => $proceso->id,
                    'peso_id' => $nro,
                ]);

                ProgramacionDetalle::create([
                    'programacion_id' => $programacion->id,
                    'peso_id' => $nro,
                ]);

                $estadoPeso = PsEstadoPeso::where('peso_id', $nro)->first();
                if ($estadoPeso) {
                    $estadoPeso->update(['estado_id' => 2]); // pasa a PROCESANDO
                }
            }
        });

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $programacion = PlProgramacion::findOrFail($id);
            $proceso = $programacion->proceso;

            ProcesoPeso::where('proceso_id', $proceso->id)->each(function ($pp) {
                if ($pp->peso) {
                    $estadoPeso = PsEstadoPeso::where('peso_id', $pp->peso->NroSalida)->first();
                    if ($estadoPeso) {
                        $estadoPeso->update(['estado_id' => 1]); // vuelve a CANCHA
                    }
                }
                $pp->delete();
            });

            ProgramacionDetalle::where('programacion_id', $programacion->id)->delete();

            $programacion->delete();

            if ($proceso) {
                $proceso->delete();
            }
        });

        return response()->json(['success' => true, 'deleted' => true]);
    }

    public function GenerarCodigoProceso($request)
    {
        $lote = Lote::findOrFail($request->lote_id);
        $numero = Proceso::where("lote_id", $lote->id)
            ->lockForUpdate()
            ->count();

        $nuevo = $numero + 1;

        return $lote->codigo . '-' . $nuevo;
    }
}
