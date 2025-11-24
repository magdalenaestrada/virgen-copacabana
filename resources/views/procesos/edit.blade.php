@extends('admin.layout')

@section('title', 'Editar Proceso')

@section('content')
    <br>
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0 flex-grow-1">CONSUMO DE REACTIVOS</h6>
                        <button class="btn btn-light btn-sm" id="btnNuevoConsumo">
                            Agregar Consumo
                        </button>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered text-sm" id="tablaConsumos">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Reactivo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proceso->consumosreactivos as $consumo)
                                    <tr data-id="{{ $consumo->id }}">
                                        <td>{{ $consumo->id }}</td>
                                        <td>{{ $consumo->reactivo->nombre ?? '' }}</td>
                                        <td>{{ $consumo->cantidad }}</td>
                                        <td>{{ $consumo->fecha }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm btnEliminarConsumo">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="mb-0 flex-grow-1">DEVOLUCIÓN DE REACTIVOS {{ $proceso->codigo }}</h6>
                        <button class="btn btn-light btn-sm" id="btnNuevaDevolucion">
                            Agregar Devolución
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-sm" id="tablaDevoluciones">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Reactivo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proceso->devolucionesReactivos as $dev)
                                    <tr data-id="{{ $dev->id }}">
                                        <td>{{ $dev->id }}</td>
                                        <td>{{ $dev->reactivo->nombre ?? '' }}</td>
                                        <td>{{ $dev->cantidad }}</td>
                                        <td>{{ $dev->fecha }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm btnEliminarDevolucion">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('procesos.modals.agregar_consumo')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.getElementById('btnNuevoConsumo').addEventListener('click', () => {
                $('#modalConsumo').modal('show'); 
            });

            document.getElementById('btnGuardarConsumo').addEventListener('click', async () => {
                const reactivo_id = document.getElementById('reactivo_id').value;
                const cantidad = document.getElementById('cantidad').value;
                const fecha = document.getElementById('fecha').value;

                if (!reactivo_id || !cantidad || !fecha) {
                    Swal.fire('Campos incompletos', 'Complete todos los campos', 'warning');
                    return;
                }

                const res = await fetch("{{ route('reactivos') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        proceso_id: {{ $proceso->id }},
                        reactivo_id,
                        cantidad,
                        fecha
                    })
                });

                const data = await res.json();

                if (data.success) {
                    Swal.fire('Correcto', 'Consumo agregado', 'success');
                    location.reload();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });


            document.querySelectorAll('.btnEliminarConsumo').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = tr.dataset.id;
                    const confirm = await Swal.fire({
                        title: '¿Eliminar consumo?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar'
                    });
                    if (!confirm.isConfirmed) return;

                    const res = await fetch("{{ route('procesos') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            consumoId: id
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire('Eliminado', 'Consumo eliminado', 'success');
                        tr.remove();
                    }
                });
            });

            document.querySelectorAll('.btnEliminarDevolucion').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = tr.dataset.id;
                    const confirm = await Swal.fire({
                        title: '¿Eliminar devolución?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar'
                    });
                    if (!confirm.isConfirmed) return;

                    const res = await fetch("{{ route('procesos') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            devolucionId: id
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire('Eliminado', 'Devolución eliminada', 'success');
                        tr.remove();
                    }
                });
            });

        });
    </script>
@endpush
