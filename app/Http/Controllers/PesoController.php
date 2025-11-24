<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peso;
use App\Models\Lote;
use App\Models\PsEstado;
use App\Models\PsEstadoPeso;
use App\Models\PsLotePeso;

class PesoController extends Controller
{
    public function index()
    {
        $peso = Peso::with(['estado', 'lote'])->first();
        $estados = PsEstado::all();
        $lotes = Lote::all();
        $pesos = Peso::with(['estado', 'lote'])
            ->orderBy('nroSalida', 'desc')
            ->paginate(100);
        return view('pesos.index', compact('pesos', 'estados', 'lotes'));
    }

    public function pesosByProgramacion($id)
    {
        $pesos = Peso::with(['estado', 'lote'])
            ->where('programacion_id', $id) // filtra por programación
            ->get(); // devuelve Collection

        return response()->json($pesos);
    }

    public function pesos(Request $request)
    {
        $query = Peso::query();

        if ($request->filled('NroSalida')) {
            $query->where('NroSalida', 'like', $request->NroSalida . '%');
        }
        if ($request->filled('Producto')) {
            $query->where('Producto', 'like', '%' . $request->Producto . '%');
        }
        if ($request->filled('Placa')) {
            $query->where('Placa', 'like', '%' . $request->Placa . '%');
        }
        if ($request->filled('Conductor')) {
            $query->where('Conductor', 'like', '%' . $request->Conductor . '%');
        }
        if ($request->filled('RazonSocial')) {
            $query->where('RazonSocial', 'like', '%' . $request->RazonSocial . '%');
        }
        if ($request->filled('origen')) {
            $query->where('origen', 'like', '%' . $request->origen . '%');
        }
        if ($request->filled('destino')) {
            $query->where('destino', 'like', '%' . $request->destino . '%');
        }
        if ($request->filled('Neto')) {
            $query->where('Neto', $request->Neto);
        }
        if ($request->filled('Observacion')) {
            $query->where('Observacion', 'like', '%' . $request->Observacion . '%');
        }
        if ($request->filled('desde')) {
            $query->whereDate('Fechai', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('Fechas', '<=', $request->hasta);
        }

        if ($request->filled('estado_id')) {
            if ($request->estado_id == 'sin_asignar') {
                $query->whereNull('lq_estado_id');
            } else {
                $query->where('lq_estado_id', $request->estado_id);
            }
        }

        $sumaNetos = $query->sum('Neto');

        $pesos = $query->orderBy('Fechai', 'desc')->paginate(100);

        $estados = PsEstado::all();
        $lotes = Lote::all();

        if ($request->ajax()) {
            return view('pesos.partials.tabla', compact('pesos', 'estados', 'lotes', 'sumaNetos'))->render();
        }

        return view('pesos.index', compact('pesos', 'estados', 'lotes', 'sumaNetos'));
    }
    public function update(Request $request, $id)
    {
        try {
            $peso = Peso::where('NroSalida', $id)->firstOrFail();

            PsEstadoPeso::updateOrCreate(
                ['peso_id' => $peso->NroSalida],
                ['estado_id' => $request->estadoId]
            );

            PsLotePeso::updateOrCreate(
                ['peso_id' => $peso->NroSalida],
                ['lote_id' => $request->loteId]
            );

            return response()->json([
                'success' => true,
                'message' => 'Peso actualizado correctamente',
                'data' => [
                    'peso_id' => $peso->NroSalida,
                    'estado_id' => $request->estadoId,
                    'lote_id' => $request->loteId
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el peso: ' . $e->getMessage()
            ], 500);
        }
    }
}
