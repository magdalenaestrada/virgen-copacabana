<?php

namespace App\Http\Controllers;

use App\Models\ConsumoDevolucionReactivo;
use App\Models\PlProgramacion;
use App\Models\Proceso;
use App\Models\ProcesoPeso;
use App\Models\Reactivo;
use App\Models\StockReactivo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class ProcesoController extends Controller

{
    public function index()
    {
        $procesos = Proceso::with(['lote', 'programacion'])
            ->leftJoin('pl_programaciones', 'procesos.id', '=', 'pl_programaciones.proceso_id')
            ->orderBy('pl_programaciones.fecha_inicio', 'desc')
            ->select('procesos.*') // importante para que paginate funcione bien
            ->paginate(100);

        return view('procesos.index', compact('procesos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $estado_programado = PsEstado::where('nombre', 'PROGRAMADO');

        $proceso = Proceso::Create([
            'peso_total' => $request->peso_total,
            'lote_id' => $request->lote_id,
            'circuito' => $request->circuito
        ]);


        $pesosIds = json_decode($request->pesosIds, true);


        foreach ($pesosIds as $pesoId) {

            // $estadopeso = PsEstadoPeso::updateOrCreate(
            //     ['peso_id' => $pesoId],
            //     [
            //         'peso_id' => $request->pesoId,
            //         'estado_id' => $estado_programado->id,
            //     ]
            // );}

            // Manually create entries in the intermediate table or structure
            ProcesoPeso::create([
                'proceso_id' => $proceso->id,
                'peso_id' => $pesoId
            ]);
        }


        $programacion = PlProgramacion::Create([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'proceso_id' => $proceso->id,

        ]);



        return response()->json(['message' => 'Data saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $proceso = Proceso::findOrFail($id);
        $reactivos = Reactivo::all();
        $stock_reactivos = StockReactivo::where("stock", ">", 0)->get();
        if ($proceso->pesosotrabal && $proceso->pesosotrabal->count() > 0) {
            $proceso->peso_total = $proceso->pesos->pluck('Neto')->sum() + $proceso->pesosotrabal->pluck('Neto')->sum();
            $proceso->save();
        }

        if ($proceso->liquidacion) {
            return response()->json(['error' => 'El proceso está liquidado y no se puede editar.'], 403);
        }

        return view('procesos.edit', compact('proceso', 'reactivos', 'stock_reactivos'));
    }

    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $proceso = Proceso::findOrFail($request->procesoId);

        $proceso->programacion->delete();

        ProcesoPeso::where('proceso_id', $proceso->id)->delete();

        foreach ($proceso->consumosreactivos as $consumoreactivo) {
            $consumoreactivo->delete();
        }

        foreach ($proceso->devolucionesReactivos as $devolucionreactivo) {
            $devolucionreactivo->delete();
        }


        foreach ($proceso->pesosotrabal as $peso) {
            $peso->delete();
        }


        $proceso->delete();
    }
    public function agregarConsumo($request, $id)
    {
        $proceso = Proceso::findOrFail($id);
        ConsumoDevolucionReactivo::create([
            "proceso_id" => $id,
            "tipo" => "C",
            "cantidad" => $request->cantidad,
            "reactivo_id" => $request->producto_id,
        ]);

        $hoy = Carbon::now("America/Lima");

        $reactivo = StockReactivo::where("producto_id", $request->producto_id)
            ->where("circuito", $proceso->circuito)
            ->first();

        if ($reactivo) {
            $reactivo->stock -= $request->cantidad;
            $reactivo->fecha_hora = $hoy;
            $reactivo->usuario_id = auth()->id();
            $reactivo->save();
        }
    }
}
