@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

@section('content')
    <div class="container">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row card-header d-flex justify-content-between align-items-center">
                        <div class="col-md-6">{{ __('REACTIVOS REGISTRADOS') }}</div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-sm btn-special" style="border: 1px solid black; border-radius: 6px;"
                                data-toggle="modal" data-target="#ModalCreateReactivo">
                                {{ __('CREAR REACTIVO') }}
                            </button>
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between p-3">
                        <div class="col-md-6 input-container">
                            <input type="text" name="search" id="search" class="input-search form-control"
                                placeholder="Buscar aquí...">
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover text-center" id="reactivo-table">
                            <thead>
                                <tr>
                                    <th>{{ __('NOMBRE') }}</th>
                                    <th>{{ __('DETALLES') }}</th>
                                    <th>{{ __('ACCIÓN') }}</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @forelse ($reactivos as $reactivo)
                                    <tr>
                                        <td>{{ $reactivo->productos->nombre_producto }}</td>
                                        <td>{{ $reactivo->detalles->count() }} detalle(s)</td>
                                        <td class="d-flex justify-content-center">
                                            @include('reactivos.modals.show', ['reactivo' => $reactivo])
                                            @include('reactivos.modals.precio', ['reactivo' => $reactivo])

                                            <button class="btn btn-sm btn-outline-danger btn-delete-reactivo"
                                                data-id="{{ $reactivo->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            {{ __('No hay reactivos registrados') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <nav>
                            <ul class="pagination justify-content-end">
                                {{ $reactivos->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Crear Reactivo --}}
        @include('reactivos.modals.create')
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Eliminar detalle
            $(document).on('click', '.btn-eliminar-detalle', function() {
                const reactivoDetalleId = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Este precio será eliminado.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('reactivosdetalles.destroy') }}",
                            type: "POST",
                            data: {
                                reactivoDetalleId: reactivoDetalleId,
                                _token: "{{ csrf_token() }}",
                                _method: "DELETE"
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo eliminar el lote.'
                                });
                            }
                        });
                    }
                });
            });
        });

        $(document).on('click', '.btn-delete-reactivo', function() {
            const reactivoId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este reactivo será eliminado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('reactivos.destroy') }}",
                        type: "POST",
                        data: {
                            reactivoId: reactivoId,
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el reactivo.'
                            });
                        }
                    });
                }
            });
        });
    </script>
@stop
