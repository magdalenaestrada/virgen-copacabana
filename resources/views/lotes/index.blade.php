@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <style>
        .combo {
            position: relative;
        }

        .combo input {
            width: 100%;
        }

        .combo select {
            width: 100%;
            position: absolute;
            top: 100%;
            left: 0;
            display: none;
            z-index: 1000;
            max-height: 150px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row card-header d-flex justify-content-between align-items-center">
                        <div class="col-md-6">{{ __('LOTES REGISTRADOS') }}</div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-sm btn-special" style="border: 1px solid black; border-radius: 6px;"
                                data-toggle="modal" data-target="#ModalCreateLote">
                                {{ __('CREAR LOTE') }}
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
                        <table class="table table-striped table-hover text-center" id="lote-table">
                            <thead>
                                <tr>
                                    <th>{{ __('CODIGO') }}</th>
                                    <th>{{ __('NOMBRE') }}</th>
                                    <th>{{ __('CLIENTE') }}</th>
                                    <th>{{ __('ACCIÓN') }}</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @forelse ($lotes as $lote)
                                    <tr>
                                        <td>{{ $lote->codigo }}</td>
                                        <td>{{ $lote->nombre }}</td>
                                        <td>{{ $lote->cliente->nombre ?? 'NO ASIGNADO' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning btn-editar-lote"
                                                data-id="{{ $lote->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger btn-delete-lote"
                                                data-id="{{ $lote->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            {{ __('No hay lotes registrados') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <nav>
                            <ul class="pagination justify-content-end">
                                {{ $lotes->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Incluye modales --}}
        @include('lotes.modals.create')
        @include('lotes.modals.edit')
    </div>
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-editar-lote', function() {
                const id = $(this).data('id');
                $(`#ModalEditLote${id}`).modal('show');
            });
        });

        $(document).on('click', '.btn-delete-lote', function() {
            const loteId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este lote será eliminado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('lotes.destroy') }}",
                        type: "POST",
                        data: {
                            loteId: loteId,
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


        $('#formLote').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('lotes.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#ModalCreateLote').modal('hide');
                        $('#formLote')[0].reset();
                        window.location.reload();

                    }
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        let mensaje = "";

                        $.each(errores, function(key, value) {
                            mensaje += value[0] + "\n";
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: mensaje
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado.'
                        });
                    }
                }
            });
        });

        $(document).on('submit', 'form[action*="lotes/"]', function(e) {
            e.preventDefault();

            let form = $(this);
            let actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize() + '&_method=PUT',
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Lote actualizado correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    form.closest('.modal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar el lote.'
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            function initCombos(root = document) {
                root.querySelectorAll('.combo').forEach(function(combo) {
                    if (combo.dataset.comboInited) return;
                    combo.dataset.comboInited = '1';

                    const input = combo.querySelector('.clienteInput');
                    const select = combo.querySelector('.clienteSelect');
                    const hidden = combo.querySelector('.clienteHidden');

                    if (!input || !select || !hidden) return;

                    input.addEventListener('input', () => {
                        const filtro = input.value.toLowerCase().trim();
                        let hasMatch = false;

                        [...select.options].forEach(opt => {
                            if (opt.text.toLowerCase().includes(filtro) || opt.value ===
                                "") {
                                opt.style.display = "";
                                hasMatch = true;
                            } else {
                                opt.style.display = "none";
                            }
                        });

                        select.style.display = hasMatch ? 'block' : 'none';
                        if (hasMatch) {
                            for (let i = 0; i < select.options.length; i++) {
                                if (select.options[i].style.display !== 'none') {
                                    select.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    });

                    input.addEventListener('focus', () => {
                        const anyVisible = [...select.options].some(o => o.style.display !==
                            'none');
                        select.style.display = anyVisible ? 'block' : 'none';
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            select.focus();
                        } else if (e.key === 'Enter') {
                            e.preventDefault();
                            let idx = null;
                            for (let i = 0; i < select.options.length; i++) {
                                if (select.options[i].style.display !== 'none') {
                                    idx = i;
                                    break;
                                }
                            }
                            if (idx !== null) {
                                select.selectedIndex = idx;
                                const opt = select.options[idx];
                                input.value = opt.text;
                                hidden.value = opt.value;
                                select.style.display = 'none';
                            }
                        }
                    });


                    select.addEventListener('change', () => {
                        const opt = select.options[select.selectedIndex];
                        if (opt) {
                            input.value = opt.text;
                            hidden.value = opt.value;

                            const codigoBase = opt.text.split(" - ")[0];
                            const lotesCount = opt.getAttribute('data-lotes');

                            if (codigoBase) {
                                let nuevoNumero = parseInt(lotesCount) + 1;
                                let codigoGenerado = `${codigoBase}-${nuevoNumero}`;

                                const codigoInput = combo.closest('.modal-body')?.querySelector(
                                        '.codigo-lote-input') ||
                                    document.getElementById('codigo_lote');

                                if (codigoInput) {
                                    codigoInput.value = codigoGenerado;
                                }
                            }
                        }

                        select.style.display = 'none';
                        input.focus();
                    });

                    select.addEventListener('blur', () => {
                        setTimeout(() => {
                            select.style.display = 'none';
                        }, 120);
                    });

                    document.addEventListener('click', function(e) {
                        if (!combo.contains(e.target)) {
                            select.style.display = 'none';
                        }
                    });

                });
            }

            initCombos();

            $('#ModalCreateLote').on('shown.bs.modal', function() {

                initCombos(this);

                $('#codigo_lote').val('');
                $('#nombre').val('');
                $('#cliente_id').val('').trigger('change');
            });




        });

        document.getElementById('clienteSelect').addEventListener('change', function() {
            let option = this.options[this.selectedIndex];

            let codigoBase = option.getAttribute('data-codigo');
            let lotesCount = option.getAttribute('data-lotes');

            if (!codigoBase) {
                document.getElementById('codigo_lote').value = "";
                return;
            }

            let nuevoNumero = parseInt(lotesCount) + 1;
            let codigoGenerado = `${codigoBase}-${nuevoNumero}`;

            document.getElementById('codigo_lote').value = codigoGenerado;
        });
    </script>
@stop
