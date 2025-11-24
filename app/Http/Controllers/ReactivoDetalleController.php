<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reactivo;
use App\Models\ReactivoDetalle;
use Illuminate\Http\Request;

class ReactivoDetalleController extends Controller
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
    public function store(Request $request)
    {
        $request->validate([
            'precio' => 'required',
            'limite' => 'required',
            'reactivo_id' => 'required|exists:reactivos,id',
        ]);

        ReactivoDetalle::create([
            'precio' => $request->precio,
            'limite' => $request->limite,
            'reactivo_id' => $request->reactivo_id,
        ]);

        return redirect()->route('reactivos')
            ->with('success', 'Detalles del reactivo añadidos correctamente.');
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
        $reactivo = Reactivo::with('detalle')->findOrFail($id);

        return view('reactivos.edit', compact('reactivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $detalle = ReactivoDetalle::findOrFail($request->reactivoDetalleId);
            $detalle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detalle eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el detalle: ' . $e->getMessage()
            ], 500);
        }
    }
}
