<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PesoOtraBal;
use App\Models\PlProgramacion;
use App\Models\Proceso;
use App\Models\PsEstado;
use Illuminate\Http\Request;

class PesoOtraBalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $id)
    {
        $programacion = PlProgramacion::findOrFail($id);
        $validated = $request->validate(rules: [
            'neto' => 'required',
        ]);

        $peso_otra_bal = PesoOtraBal::create([
            'fechai' => $programacion->fecha_inicio,
            'fechas' => $programacion->fecha_fin,
            'bruto' => $request->bruto,
            'tara' => $request->tara,
            'neto' => $request->neto,
            'placa' => $request->placa,
            'observacion' => $request->observacion,
            'producto' => $request->producto,
            'conductor' => $request->conductor,
            'razon_social_id' => $programacion->proceso->lote->lq_cliente_id,
            'guia' => $request->guia,
            'guiat' => $request->guiat,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'balanza' => $request->balanza,
            'lote_id' => $programacion->proceso->lote_id,
            'proceso_id' => $programacion->proceso_id,
            'programacion_id' => $id,
            'estado_id' => 1,
        ]);
        $proceso = Proceso::findOrFail($programacion->proceso_id);
        $proceso->increment('peso_total', $request->neto);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {


        $pesootrabal = PesoOtraBal::findOrFail($request->pesoId);

        $validated = $request->validate(rules: [
            'neto' => 'required',
        ]);



        $pesootrabal->update([
            'Fechai'       => $request->fechai,
            'Fechas'       => $request->fechas,
            'Bruto'        => $request->bruto,
            'Tara'         => $request->tara,
            'Neto'         => $request->neto,
            'Placa'        => $request->placa,
            'Observacion'  => $request->observacion,
            'Producto'     => $request->producto,
            'Conductor'    => $request->conductor,
            'Razonsocial'  => $request->razonsocial,
            'Guiar'        => $request->guiar,
            'Guiat'        => $request->guiat,
            'Origen'       => $request->origen,
            'Destino'      => $request->destino,
            'Balanza'      => $request->balanza,
        ]);

        // Optionally, return a response or redirect after update
        $pesootrabal->save();
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pesoOtraBal = PesoOtraBal::findOrFail($id);

            if (in_array($pesoOtraBal->estado_id, [3, 4])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un peso en estado procesado o finalizado'
                ], 422);
            }

            $pesoOtraBal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Peso eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el peso: ' . $e->getMessage()
            ], 500);
        }
    }
}
