<?php

namespace App\Http\Controllers;

use App\Exports\OrdenServicioExport;
use App\Exports\OrdenServicioFullExport;
use App\Http\Requests\Comprobante\SubmitComprobanteRequest;
use App\Http\Requests\OrdenServicio\SubmitOrdenServicioRequest;
use App\Models\Comprobante;
use App\Models\DetalleOrdenServicio;
use App\Models\OrdenServicio;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrdenServicioController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:ver ordenes', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear ordenes', ['only' => ['create', 'store']]);
        $this->middleware('permission:cancelar ordenes', ['only' => ['cancelar', 'updatecancelar', 'cancelaralcredito', 'updatecancelaralcredito']]);
        $this->middleware('permission:recepcionar ordenes', ['only' => ['recepcionar', 'updaterecepcionar']]);
        //$this->middleware('permission:anular ordenes', ['only' => ['anular']]);
    }


    /**
     * Actualiza únicamente 'cotizacion' (sin tocar otros campos).
     */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordenes = OrdenServicio::orderBy('created_at', 'desc')->paginate(100);
        return view('ordenes.index', compact('ordenes'));
    }

    public function formulario()
    {
        return view('ordenes.formulario');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('orden-servicio.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubmitOrdenServicioRequest $request)
    {
        $hoy = Carbon::now('America/Lima');
        $proveedor = Proveedor::updateOrCreate(
            ['ruc' => $request->documento_proveedor],
            [
                'razon_social' => $request->proveedor,
                'telefono' => $request->telefono_proveedor,
                'direccion' => $request->direccion_proveedor,
            ]
        );
        $insert = [
            "proveedor_id" => $proveedor->id,
            "fecha_creacion" => $hoy,
            "fecha_inicio" => $request->fecha_inicio,
            "fecha_fin" => $request->fecha_fin,
            "descripcion" => $request->descripcion,
            "costo_estimado" => $request->costo_estimado,
            "costo_final" => $request->costo_final,
            "codigo" => $this->generarCodigo()
        ];
        try {
            $orden_servicio = DB::transaction(function () use ($insert, $request) {
                $orden_servicio = OrdenServicio::create($insert);
                $detalles = json_decode($request->detalles, true);
                foreach ($detalles["detalles"] as $detalle) {

                    DetalleOrdenServicio::create([
                        "orden_servicio_id" => $orden_servicio->id,
                        "descripcion" => $detalle['descripcion'],
                        "cantidad" => $detalle['cantidad'],
                        "precio_unitario" => $detalle['precio_unitario'],
                        "subtotal" => $detalle['subtotal'],
                    ]);
                }
                return $orden_servicio;
            });

            return redirect()
                ->route('orden-servicio.index')
                ->with('success', 'La orden de servicio se creó correctamente.');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function generarCodigo()
    {
        $count = OrdenServicio::count() + 1; // cuenta todas las órdenes y suma 1
        $codigo = str_pad($count, 3, '0', STR_PAD_LEFT); // rellena con ceros a la izquierda (001, 002, 010, etc.)
        return "OS-" . $codigo;
    }


    public function datatable()
    {
        $model = OrdenServicio::with('proveedor')->orderBy('id', 'desc');

        return DataTables::of($model)
            ->addIndexColumn()
            ->make(true);
    }

    public function exportExcel()
    {
        return Excel::download(new OrdenServicioFullExport, 'ordenes_servicio_completo.xlsx');
    }

    /**
     * Display the specified resource.
     */

    public function show(OrdenServicio $ordenServicio)
    {
        // Carga relaciones necesarias
        $ordenServicio->load('proveedor', 'detalles');

        return view('ordenes.show', compact('ordenServicio'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orden = OrdenServicio::with(['proveedor', 'detalles'])->findOrFail($id);
        return view('ordenes.edit', compact('orden'));
    }

    public function update(SubmitOrdenServicioRequest $request, $id)
    {
        $hoy = Carbon::now('America/Lima');

        // Validación básica (puedes reemplazar con tu FormRequest)
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'costo_estimado' => 'required|numeric|min:0',
            'costo_final' => 'required|numeric|min:0',
            'detalles' => 'required|json',
        ]);

        try {
            $orden_servicio = DB::transaction(function () use ($request, $id, $hoy) {

                $orden = OrdenServicio::findOrFail($id);

                $proveedor = Proveedor::updateOrCreate(
                    ['ruc' => $request->documento_proveedor],
                    [
                        'razon_social' => $request->proveedor,
                        'telefono' => $request->telefono_proveedor,
                        'direccion' => $request->direccion_proveedor,
                    ]
                );

                $orden->update([
                    "proveedor_id" => $proveedor->id,
                    "fecha_inicio" => $request->fecha_inicio,
                    "fecha_fin" => $request->fecha_fin,
                    "descripcion" => $request->descripcion,
                    "costo_estimado" => $request->costo_estimado,
                    "costo_final" => $request->costo_final,
                    "updated_at" => $hoy,
                ]);

                $detalles = json_decode($request->detalles, true);

                // Eliminar los detalles antiguos
                $orden->detalles()->delete();

                // Crear los nuevos detalles
                foreach ($detalles["detalles"] as $detalle) {
                    DetalleOrdenServicio::create([
                        "orden_servicio_id" => $orden->id,
                        "descripcion" => $detalle['descripcion'],
                        "cantidad" => $detalle['cantidad'],
                        "precio_unitario" => $detalle['precio_unitario'],
                        "subtotal" => $detalle['subtotal'],
                    ]);
                }

                return $orden;
            });


            return redirect()
                ->route('orden-servicio.index')
                ->with('success', 'La orden de servicio se actualizó correctamente.');
        } catch (\Exception $e) {
            // Si hay error, lo mostramos (opcional: puedes redirigir con mensaje)
            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al actualizar la orden: ' . $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            // Cambia el estado
            $orden->estado_servicio = 'A'; // o 'ANULADO' según tu convención
            $orden->save();

            return redirect()
                ->route('orden-servicio.index')
                ->with('success', 'La orden de servicio ha sido anulada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('orden-servicio.index')
                ->with('error', 'Ocurrió un error al anular la orden: ' . $e->getMessage());
        }
    }

    public function finalizar($id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            // Cambia el estado
            $orden->estado_servicio = 'F';
            $orden->save();

            return redirect()
                ->route('orden-servicio.index')
                ->with('success', 'La orden de servicio ha sido completada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('orden-servicio.index')
                ->with('error', 'Ocurrió un error al completar la orden: ' . $e->getMessage());
        }
    }

    public function proceso($id)
    {
        try {
            $orden = OrdenServicio::findOrFail($id);

            // Cambia el estado
            $orden->estado_servicio = 'E';
            $orden->save();

            return redirect()
                ->route('orden-servicio.index')
                ->with('success', 'La orden de servicio se está completando.');
        } catch (\Exception $e) {
            return redirect()
                ->route('orden-servicio.index')
                ->with('error', 'Ocurrió un error al editar la orden: ' . $e->getMessage());
        }
    }



    public function print(string $id)
    {

        $orden = OrdenServicio::with('detalles')->findOrFail($id);


        return view('ordenes.ticket', compact('orden'));
    }

    public function cancelar(Request $request, string $id)
    {
        $orden = OrdenServicio::findOrFail($id);
        $comprobante = $orden->comprobantes()->create([
            'total' => $orden->costo_final,
            'tipo_pago' => $request->tipopago,
            'usuario_cancelacion' => Auth::id(),
            'fecha_cancelacion' => $request->fecha_cancelacion,
            'fecha_emision' => $request->fecha_emision,
            'comprobante_correlativo' => $request->comprobante_correlativo,
        ]);
        $orden->update([
            'estado_servicio' => 'C',
        ]);
       

        return redirect()
            ->route('orden-servicio.index')
            ->with('success', 'Comprobante registrado y actividad guardada.');
    }
    public function comprobante(string $id)
    {
        $orden = OrdenServicio::findOrFail($id);
        return view('ordenes.comprobante', compact('orden'));
    }
    public function search(Request $request)
    {
        $search = $request->get('search');

        $ordenes = OrdenServicio::with('proveedor')
            ->when($search, function ($query, $search) {
                $query->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('proveedor', function ($q) use ($search) {
                        $q->where('razon_social', 'like', "%{$search}%")
                            ->orWhere('ruc', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('ordenes.partials._rows', compact('ordenes'));
    }
}
