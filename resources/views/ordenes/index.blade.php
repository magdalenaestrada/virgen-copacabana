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
                        <div class="col-md-6">
                            {{ __('ORDENES DE SERVICIO REGISTRADAS') }}
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-sm btn-special" href="{{ route('orden-servicio.formulario') }}">
                                {{ __('CREAR ORDEN DE SERVICIO') }}
                            </a>
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between p-3">
                        <div class="col-md-6 input-container">
                            <input type="text" name="search" id="search" class="input-search form-control"
                                placeholder="Buscar aqu铆...">
                        </div>
                        <a href="{{ route('orden-servicio.export-excel') }}" class="button_export-excel">
                            <span class="button__text">Descargar Excel</span>
                        </a>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover text-center" id="ordenservicio-table">
                            <thead>
                                <tr>
                                    <th>{{ __('CODIGO') }}</th>
                                    <th>{{ __('PROVEEDOR') }}</th>
                                    <th>{{ __('FECHA DE INICIO') }}</th>
                                    <th>{{ __('FECHA DE FIN') }}</th>
                                    <th>{{ __('DESCRIPCION') }}</th>
                                    <th>{{ __('MONTO TOTAL') }}</th>
                                    <th>{{ __('ESTADO') }}</th>
                                    <th>{{ __('ACCI脫N') }}</th>
                                </tr>
                            </thead>

                            <tbody style="font-size:13px">
                                @forelse ($ordenes as $orden)
                                    <tr>
                                        <td>{{ $orden->codigo }}</td>
                                        <td>{{ $orden->proveedor->razon_social ?? '-' }}</td>
                                        <td>{{ optional($orden->fecha_inicio)->format('Y-m-d') ?? '-' }}</td>
                                        <td>{{ optional($orden->fecha_fin)->format('Y-m-d') ?? '-' }}</td>
                                        <td>{{ $orden->descripcion ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <p>{{ $orden->moneda == 'DOLARES' ? '$' : 'S/.' }}</p>
                                                <p>{{ number_format($orden->costo_final, 2) }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $estados = [
                                                    'P' => ['label' => 'Pendiente', 'color' => 'bg-light'],
                                                    'E' => ['label' => 'En proceso', 'color' => 'bg-warning'],
                                                    'F' => ['label' => 'Completado', 'color' => 'bg-success'],
                                                    'A' => ['label' => 'Cancelado', 'color' => 'bg-danger'],
                                                    'C' => ['label' => 'Pagado', 'color' => 'bg-primary'],
                                                ];

                                                $estado = $estados[$orden->estado_servicio] ?? [
                                                    'label' => 'Desconocido',
                                                    'color' => 'bg-secondary',
                                                ];
                                            @endphp

                                            <span class="badge {{ $estado['color'] }}">
                                                {{ $estado['label'] }}
                                            </span>

                                        </td>

                                        <td class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                                data-target="#ModalShow{{ $orden->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if ($orden->estado_servicio !== 'A' && $orden->estado_servicio !== 'C')
                                                @if ($orden->estado_servicio !== 'F')
                                                    <a href="{{ route('orden-servicio.edit', $orden->id) }}"
                                                        class="btn btn-outline-warning btn-sm mx-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if ($orden->estado_servicio !== 'F' && $orden->estado_servicio !== 'E')
                                                    <form action="{{ route('orden-servicio.proceso', $orden->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-light btn-sm">
                                                            <i class="fa-solid fa-gear"></i>INICIAR
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('orden-servicio.comprobante', $orden->id) }}"
                                                    class="btn btn-primary btn-sm mx-1 comprobante-orden-servicio"
                                                    data-id="{{ $orden->id }}">
                                                    PAGAR
                                                </a>
                                            @endif
                                            @if ($orden->estado_servicio === 'E')
                                                <form action="{{ route('orden-servicio.finalizar', $orden->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa-solid fa-gear"></i>COMPLETAR
                                                    </button>
                                                </form>
                                            @endif
                                            <div class="ml-1" style="margin-top: -10px">
                                                <a href="{{ route('orden-servicio.print', $orden->id) }}" class="btnprn"
                                                    style="margin-left: 5px;">
                                                    <div class="printer">
                                                        <div class="paper">

                                                            <svg viewBox="0 0 8 8" class="svg">
                                                                <path fill="#0077FF"
                                                                    d="M6.28951 1.3867C6.91292 0.809799 7.00842 0 7.00842 0C7.00842 0 6.45246 0.602112 5.54326 0.602112C4.82505 0.602112 4.27655 0.596787 4.07703 0.595012L3.99644 0.594302C1.94904 0.594302 0.290039 2.25224 0.290039 4.29715C0.290039 6.34206 1.94975 8 3.99644 8C6.04312 8 7.70284 6.34206 7.70284 4.29715C7.70347 3.73662 7.57647 3.18331 7.33147 2.67916C7.08647 2.17502 6.7299 1.73327 6.2888 1.38741L6.28951 1.3867ZM3.99679 6.532C2.76133 6.532 1.75875 5.53084 1.75875 4.29609C1.75875 3.06133 2.76097 2.06018 3.99679 2.06018C4.06423 2.06014 4.13163 2.06311 4.1988 2.06905L4.2414 2.07367C4.25028 2.07438 4.26057 2.0758 4.27406 2.07651C4.81533 2.1436 5.31342 2.40616 5.67465 2.81479C6.03589 3.22342 6.23536 3.74997 6.23554 4.29538C6.23554 5.53084 5.23439 6.532 3.9975 6.532H3.99679Z">
                                                                </path>
                                                                <path fill="#0055BB"
                                                                    d="M6.756 1.82386C6.19293 2.09 5.58359 2.24445 4.96173 2.27864C4.74513 2.17453 4.51296 2.10653 4.27441 2.07734C4.4718 2.09225 5.16906 2.07947 5.90892 1.66374C6.04642 1.58672 6.1743 1.49364 6.28986 1.38647C6.45751 1.51849 6.61346 1.6647 6.756 1.8235V1.82386Z">
                                                                </path>
                                                            </svg>

                                                        </div>
                                                        <div class="dot"></div>
                                                        <div class="output">
                                                            <div class="paper-out"></div>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div>
                                            @if ($orden->estado_servicio === 'P')
                                                <a href="{{ route('orden-servicio.anular', $orden->id) }}"
                                                    class="btn btn-danger btn-sm mx-1 anular-orden-servicio"
                                                    data-id="{{ $orden->id }}">
                                                    X
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            {{ __('No hay 贸rdenes registradas') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Paginaci贸n -->
                        <nav>
                            <ul class="pagination justify-content-end">
                                {{ $ordenes->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 馃敼 AQUI VAN LOS MODALES (fuera del foreach) --}}
    @foreach ($ordenes as $orden)
        @include('ordenes.modal.show', ['ordenServicio' => $orden])
    @endforeach
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/updateadvice.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.printPage.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btnprn').printPage();

        });
    </script>

    <script>
        // Confirmaci贸n de anulaci贸n
        $(document).on('click', '.anular-orden-servicio', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: '驴Anular esta orden de servicio?',
                text: 'Esta acci贸n no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'S铆, anular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/orden-servicio/${id}/anular`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            Swal.fire('脡xito', 'La orden ha sido anulada.', 'success')
                                .then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error', 'Ocurrió un error al anular la orden.', 'error');
                        }
                    });
                }
            });
        });

        // Buscador en vivo
        $('#search').on('input', function() {
            const value = $(this).val();
            $.ajax({
                url: "{{ route('search.ordenservicio') }}",
                method: 'GET',
                data: {
                    search: value
                },
                success: function(response) {
                    $('#ordenservicio-table tbody').html(response);
                },
                error: function(err) {
                    console.error(err);
                }
            });
        });

        // Notificaciones
        @if (session('success'))
            Swal.fire('脡xito', '{{ session('success') }}', 'success');
        @elseif (session('error'))
            Swal.fire('Error', '{{ session('error') }}', 'error');
        @endif
    </script>
@stop
