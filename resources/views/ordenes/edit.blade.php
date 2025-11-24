@extends('admin.layout')

@section('title', 'Editar Orden de Servicio')

@section('content')
    <style>
        .modern-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 1.5rem;
        }

        .modern-card-header {
            color: rgb(0, 0, 0);
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .modern-card-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .modern-input {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            transition: all 0.2s;
        }

        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .modern-input:disabled {
            background-color: #f1f5f9;
            cursor: not-allowed;
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-top: 1rem;
        }

        .modern-table thead th {
            background: #f8fafc;
            padding: 1rem;
            font-weight: 700;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .modern-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .modern-table tbody td {
            padding: 0.75rem;
        }

        .subtotal-display {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border: 2px solid #93c5fd;
            border-radius: 8px;
            padding: 0.625rem;
            text-align: right;
            font-weight: 700;
            color: #1e40af;
            min-height: 42px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-delete:hover {
            background: #b91c1c;
            transform: scale(1.1);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }

        .badge-codigo {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="modern-card">
            <div class="modern-card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Editar Orden de Servicio</h4>
                <span class="badge-codigo">{{ $orden->codigo }}</span>
            </div>
        </div>

        <form id="formEditarOrden" method="POST" action="{{ route('orden-servicio.update', $orden->id) }}">
            @csrf
            @method('PUT')
            <?php use Carbon\Carbon;
            $hoy = Carbon::now('America/Lima'); ?>

            <div class="modern-card">
                <div class="modern-card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">RUC</label>
                            <input type="text" name="documento_proveedor" value="{{ $orden->proveedor->ruc ?? '' }}"
                                class="form-control modern-input">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Razón Social</label>
                            <input type="text" name="proveedor" value="{{ $orden->proveedor->razon_social ?? '' }}"
                                class="form-control modern-input">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono_proveedor" value="{{ $orden->proveedor->telefono ?? '' }}"
                                class="form-control modern-input">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion_proveedor"
                                value="{{ $orden->proveedor->direccion ?? '' }}" class="form-control modern-input">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio"
                                value="{{ optional($orden->fecha_inicio)->format('Y-m-d') }}"
                                class="form-control modern-input" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin"
                                value="{{ optional($orden->fecha_fin)->format('Y-m-d') }}" class="form-control modern-input"
                                required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Costo Estimado</label>
                            <input type="number" step="0.01" name="costo_estimado" value="{{ $orden->costo_estimado }}"
                                class="form-control modern-input" id="costoEstimadoInput" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Costo Final</label>
                            <input type="number" step="0.01" name="costo_final" value="{{ $orden->costo_final }}"
                                class="form-control modern-input" id="costoFinalInput" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control modern-input" rows="3">{{ $orden->descripcion }}</textarea>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="modern-table" id="tablaDetalles">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unitario</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orden->detalles as $detalle)
                                    <tr>
                                        <td><input type="text" class="form-control modern-input descripcion"
                                                value="{{ $detalle->descripcion }}"></td>
                                        <td><input type="number" class="form-control modern-input cantidad text-center"
                                                min="0" step="1" value="{{ $detalle->cantidad }}"></td>
                                        <td><input type="number" class="form-control modern-input precio_unitario text-end"
                                                step="0.01" min="0" value="{{ $detalle->precio_unitario }}"></td>
                                        <td>
                                            <div class="subtotal-display">S/ <span
                                                    class="subtotal-valor">{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn-delete eliminarFila" title="Eliminar">
                                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                    <path fill-rule="evenodd"
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="agregarFila" class="btn btn-success mt-3 d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                        </svg>
                        Agregar Detalle
                    </button>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal:</strong>
                            <span id="subtotalGeneral">0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>IGV (18%):</strong>
                            <span id="igvGeneral">0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <strong>Total General:</strong>
                            <span id="totalGeneral">0.00</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="detalles" id="detalles">
                <div class="text-end mt-4 mb-4">
                    <p class="mb-2">Items: <span id="itemCount">{{ count($orden->detalles) }}</span></p>
                    <button type="submit" class="btn-success-modern">Guardar Cambios</button>
                    <a href="{{ route('orden-servicio.index') }}" class="btn btn-light ms-2 px-4 py-2 rounded-3">
                        Cancelar
                    </a>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function recalcularTotales() {
                let subtotal = 0;
                document.querySelectorAll('#tablaDetalles tbody tr').forEach(fila => {
                    const cantidad = parseFloat(fila.querySelector('.cantidad')?.value) || 0;
                    const precio = parseFloat(fila.querySelector('.precio_unitario')?.value) || 0;
                    const sub = cantidad * precio;
                    fila.querySelector('.subtotal-valor').textContent = sub.toFixed(2);
                    subtotal += sub;
                });
                const igv = subtotal * 0.18;
                const total = subtotal + igv;
                document.getElementById('subtotalGeneral').textContent = subtotal.toFixed(2);
                document.getElementById('igvGeneral').textContent = igv.toFixed(2);
                document.getElementById('totalGeneral').textContent = total.toFixed(2);
                // 🔹 Actualiza también los inputs visibles
                document.getElementById('costoEstimadoInput').value = subtotal.toFixed(2);
                document.getElementById('costoFinalInput').value = total.toFixed(2);
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.eliminarFila')) {
                    e.target.closest('tr').remove();
                    recalcularTotales();
                    actualizarContador();
                }
            });

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('cantidad') || e.target.classList.contains(
                        'precio_unitario')) {
                    recalcularTotales();
                }
            });

            document.getElementById('agregarFila').addEventListener('click', function() {
                const tbody = document.querySelector('#tablaDetalles tbody');
                const fila = document.createElement('tr');
                fila.innerHTML = `
            <td><input type="text" class="form-control modern-input descripcion"></td>
            <td><input type="number" class="form-control modern-input cantidad text-center" min="0" step="1" value="0"></td>
            <td><input type="number" class="form-control modern-input precio_unitario text-end" step="0.01" min="0" value="0.00"></td>
            <td><div class="subtotal-display">S/ <span class="subtotal-valor">0.00</span></div></td>
            <td class="text-center">
                <button type="button" class="btn-delete eliminarFila" title="Eliminar">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z"/>
                    </svg>
                </button>
            </td>`;
                tbody.appendChild(fila);
                actualizarContador();
            });

            function actualizarContador() {
                document.getElementById('itemCount').textContent =
                    document.querySelectorAll('#tablaDetalles tbody tr').length;
            }

            document.getElementById('formEditarOrden').addEventListener('submit', function() {
                let subtotal = 0;
                document.querySelectorAll('#tablaDetalles tbody tr').forEach(fila => {
                    const cantidad = parseFloat(fila.querySelector('.cantidad')?.value) || 0;
                    const precio = parseFloat(fila.querySelector('.precio_unitario')?.value) || 0;
                    subtotal += cantidad * precio;
                });
                const igv = subtotal * 0.18;
                const total = subtotal + igv;
                document.getElementById('costoEstimadoInput').value = subtotal.toFixed(2);
                document.getElementById('costoFinalInput').value = total.toFixed(2);

                const detalles = [];
                document.querySelectorAll('#tablaDetalles tbody tr').forEach(fila => {
                    const descripcion = fila.querySelector('.descripcion').value.trim();
                    const cantidad = fila.querySelector('.cantidad').value || 0;
                    const precio = fila.querySelector('.precio_unitario').value || 0;
                    const subtotal = parseFloat(fila.querySelector('.subtotal-valor')
                        .textContent) || 0;
                    if (descripcion !== '') {
                        detalles.push({
                            descripcion,
                            cantidad,
                            precio_unitario: precio,
                            subtotal
                        });
                    }
                });

                document.getElementById('detalles').value = JSON.stringify({
                    detalles
                });
            });

            // Espera que todo el DOM se cargue y calcula totales iniciales
            setTimeout(recalcularTotales, 100);
        });
    </script>
@endsection
