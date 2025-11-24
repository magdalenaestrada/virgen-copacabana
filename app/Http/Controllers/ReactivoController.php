<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductosFamilia;
use App\Models\Reactivo;
use Illuminate\Http\Request;
use Inertia\Inertia;



class ReactivoController extends Controller
{
    public function index()
    {
        $reactivos = Reactivo::with(['detalles', 'productos'])
            ->whereRelation('productos', 'productosfamilia_id', 1)
            ->orderBy('created_at')
            ->paginate(10);

        $productos = Producto::where('productosfamilia_id', 1)->get();

        return view('reactivos.index', compact('reactivos', 'productos'));
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
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);

        $reactivo = Reactivo::create([
            'producto_id' => $validated['producto_id'],
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('reactivos')
            ->with('success', 'Reactivo creado exitosamente. Ahora puedes añadir los detalles.');
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
            $reactivo = Reactivo::findOrFail($request->reactivoId);

            // Verificar relaciones dependientes
            if ($reactivo->detalles()->exists()) {
                foreach ($reactivo->detalles as $detalle) {
                    $detalle->delete();
                }
            }

            $reactivo->delete(); // soft delete o borrado normal, según tu modelo

            return response()->json([
                'success' => true,
                'message' => 'Reactivo eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el reactivo: ' . $e->getMessage()
            ], 500);
        }
    }
}
