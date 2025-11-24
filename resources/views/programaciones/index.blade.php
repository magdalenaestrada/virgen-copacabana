@extends('admin.layout')

@section('content')
    <div class="container py-4">
        @php
            use Carbon\Carbon;
            $hoy = Carbon::now('America/Lima')->format('Y-m-d\TH:i');
        @endphp
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold text-secondary mb-0">
                <i class="bi bi-calendar3 me-2 text-muted"></i>Gestión de Programaciones
            </h4>
            <button class="btn btn-primary" id="btnNuevaProgramacion">
                <i class="bi bi-plus-lg me-1"></i> Nueva Programación
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    @include('programaciones.modals.create')
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .swal2-popup {
            font-size: 0.9rem !important;
            border-radius: 12px;
        }

        #tablaPesos tbody tr.selected {
            background-color: #baf0b1 !important;
        }

        #modalPesoManual.modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.75) !important;
        }

        #modalPesoManual.modal {
            z-index: 1055;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales-all.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/moment@6.1.8/index.global.min.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalProgramacion'));
            const tablaBody = $('#tablaPesos tbody');

            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: @json($programaciones).map(p => ({
                    id: p.id,
                    title: `Salida #${p.lote_nombre ?? 'N/A'}`,
                    start: p.fecha_inicio,
                    end: p.fecha_fin,
                    color: '#4dabf7',
                    extendedProps: {
                        lote_id: p.lote_id,
                        pesos: p.pesos ?? [],
                        circuito: p.circuito
                    }
                }))

            });
            calendar.render();

            $('#btnNuevaProgramacion').click(() => {
                $('#formProgramacion')[0].reset();
                $('#formPesoManual')[0].reset();
                $('#programacion_id').val('');
                $('#peso_total').val('');
                $('#lote_id').prop('disabled', false);
                $('#otra_balanza').hide();

                tablaBody.html('<tr><td colspan="8" class="text-muted">Seleccione un lote...</td></tr>');
                modal.show();
            });


            $('#btnCancelar').click(() => {
                modal.hide();
            });



            $(document).on('change', '.peso-check', function() {
                let total = 0;
                $('.peso-check:checked').each(function() {
                    total += parseFloat($(this).data('neto') || 0);
                });
                $('#peso_total').val(total.toFixed(2));
            });

            $('#lote_id_input').on('input', function() {
                let filtro = $(this).val().toLowerCase().trim();

                if (filtro.length === 0) {
                    $('#lote_id').hide();
                    return;
                }

                let coincidencias = 0;

                $('#lote_id option').each(function() {
                    let texto = $(this).text().toLowerCase();

                    if (texto.includes(filtro)) {
                        $(this).show();
                        coincidencias++;
                    } else {
                        $(this).hide();
                    }
                });

                if (coincidencias > 0) {
                    $('#lote_id').show();
                } else {
                    $('#lote_id').hide();
                }
            });

            $('#lote_id').on('change', function() {
                const loteId = $(this).val();
                const programacionId = $('#programacion_id').val();

                const loteTexto = $("#lote_id option:selected").text().trim();
                $('#lote_id_input').val(loteTexto);
                $('#lote_id_real').val(loteId);
                $('#lote_id').hide();

                if (!loteId) {
                    tablaBody.html(
                        '<tr><td colspan="8" class="text-muted">Seleccione un lote...</td></tr>');
                    return;
                }

                if (!programacionId) {
                    axios.get(`/lotes/${loteId}/pesos`)
                        .then(res => {
                            const pesos = res.data;
                            tablaBody.html('');
                            pesos.forEach(peso => {
                                tablaBody.append(`
                        <tr>
                            <td><input type="checkbox" class="peso-check" value="${peso.NroSalida}" data-neto="${peso.Neto}"></td>
                            <td>${peso.NroSalida}</td>
                            <td>${peso.Fechas ?? '-'}</td>
                            <td>${peso.Bruto ?? '-'}</td>
                            <td>${peso.Tara ?? '-'}</td>
                            <td>${peso.Neto ?? '-'}</td>
                            <td>${peso.Horai ?? '-'}</td>
                            <td>${peso.Horas ?? '-'}</td>
                        </tr>
                    `);
                            });
                            calcularTotal();
                        })
                        .catch(() => {
                            tablaBody.html(
                                '<tr><td colspan="8" class="text-danger">Error al cargar los pesos.</td></tr>'
                            );
                        });
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#lote_id, #lote_id_input').length) {
                    $('#lote_id').hide();
                }
            });

            $('#btnGuardar').click(function() {
                const programacionId = $('#programacion_id').val();
                const data = {
                    lote_id: $('#lote_id_real').val(),
                    fecha_inicio: $('#fecha_inicio').val(),
                    fecha_fin: $('#fecha_fin').val(),
                    circuito: $('#circuito').val(),
                    pesos: $('.peso-check:checked').map(function() {
                        return this.value;
                    }).get(),
                    peso_total: $('#peso_total').val()
                };

                if (!data.lote_id || !data.fecha_inicio || !data.fecha_fin || !data.circuito) {
                    return Swal.fire('Campos incompletos', 'Por favor complete todos los campos.',
                        'warning');
                }
                if (data.fecha_inicio >= data.fecha_fin) {
                    return Swal.fire('Fechas inválidas',
                        'La fecha de inicio debe ser anterior a la fecha de fin.', 'error');
                }
                if (programacionId && data.pesos.length === 0) {
                    Swal.fire({
                        title: '¿Eliminar programación?',
                        text: 'No hay pesos seleccionados. ¿Desea eliminar esta programación?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.delete(`/programaciones/${programacionId}`)
                                .then(() => {
                                    Swal.fire({
                                        title: 'Eliminado',
                                        text: 'La programación fue eliminada correctamente.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {

                                        modal.hide();
                                        location.reload();
                                        calendar.refetchEvents();

                                    });
                                })
                                .catch(() => {
                                    Swal.fire('Error', 'No se pudo eliminar la programación.',
                                        'error');
                                });
                        }
                    });
                    return;
                }

                if (!programacionId && data.pesos.length === 0) {
                    return Swal.fire('Sin pesos seleccionados', 'Debe seleccionar al menos un peso.',
                        'warning');
                }


                const url = programacionId ? `/programaciones/${programacionId}` : '/programaciones';
                const method = programacionId ? 'put' : 'post';

                axios({
                        method,
                        url,
                        data
                    })
                    .then(res => {
                        const deleted = res.data.deleted === true || res.data.deleted === 'true';

                        if (deleted) {
                            Swal.fire({
                                title: 'Eliminado',
                                text: 'La programación fue eliminada correctamente.',
                                icon: 'info',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                modal.hide();
                                location.reload();

                            });
                        } else {
                            const msg = programacionId ?
                                'Programación actualizada correctamente.' :
                                'Programación registrada correctamente.';
                            Swal.fire({
                                title: 'Éxito',
                                text: msg,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                modal.hide();
                                location.reload();

                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Hubo un problema al guardar la programación.', 'error');
                    });
            });

            calendar.on('eventClick', function(info) {
                const programacionId = info.event.id;
                $('#programacion_id').val(programacionId);

                axios.get(`/programaciones/${programacionId}/pesos`)
                    .then(res => {
                        const p = res.data.programacion;
                        const pesosTodos = res.data.pesos;

                        // Separar pesos por tipo
                        const pesosBalanza = pesosTodos.filter(peso => peso.tipo === 'balanza');
                        const pesosOtrasBalanzas = pesosTodos.filter(peso => peso.tipo === 'manual');

                        $('#lote_id').val(p.proceso.lote.id).trigger('change');
                        $('#lote_id').prop('disabled', true);
                        $('#circuito').val(p.proceso.circuito).trigger('change');
                        $('#fecha_inicio').val(moment(p.fecha_inicio).format('YYYY-MM-DDTHH:mm'));
                        $('#fecha_fin').val(moment(p.fecha_fin).format('YYYY-MM-DDTHH:mm'));

                        // Mostrar sección de otras balanzas
                        $('#otra_balanza').show();

                        // ====== TABLA DE PESOS NORMALES (BALANZA PRINCIPAL) ======
                        const tablaBody = $('#tablaPesos tbody');
                        tablaBody.html('');

                        if (pesosBalanza.length === 0) {
                            tablaBody.html(
                                '<tr><td colspan="8" class="text-muted">No hay pesos de balanza principal asignados</td></tr>'
                            );
                        } else {
                            pesosBalanza.forEach(peso => {
                                const disabled = (peso.estado_id === 3 || peso.estado_id ===
                                    4) ? 'disabled' : '';
                                tablaBody.append(`
                        <tr>
                            <td>
                                <input type="checkbox" 
                                       class="peso-check" 
                                       value="${peso.NroSalida}" 
                                       data-neto="${peso.Neto}" 
                                       checked 
                                       ${disabled}>
                            </td>
                            <td>${peso.NroSalida}</td>
                            <td>${peso.Fechas ?? '-'}</td>
                            <td>${peso.Bruto ?? '-'}</td>
                            <td>${peso.Tara ?? '-'}</td>
                            <td class="fw-bold">${peso.Neto ?? '-'}</td>
                            <td>${peso.Horai ?? '-'}</td>
                            <td>${peso.Horas ?? '-'}</td>
                        </tr>
                    `);
                            });
                        }

                        cargarTablaOtrasBalanzas(pesosOtrasBalanzas);

                        calcularTotal();
                        modal.show();
                    })
                    .catch((error) => {
                        console.error('Error al cargar datos:', error);
                        Swal.fire('Error', 'No se pudieron cargar los pesos de esta programación',
                            'error');
                    });
            });

            function cargarTablaOtrasBalanzas(pesosOtrasBalanzas) {
                const tablaOtrasBody = $('#tablaOtrasBalanzas tbody');
                tablaOtrasBody.html('');

                if (pesosOtrasBalanzas.length === 0) {
                    tablaOtrasBody.html(
                        '<tr><td colspan="9" class="text-muted">No hay pesos de otras balanzas registrados</td></tr>'
                    );
                } else {
                    pesosOtrasBalanzas.forEach(peso => {
                        const estadoBadge = peso.estado_nombre ?
                            `<span class="badge bg-info">${peso.estado_nombre}</span>` :
                            '<span class="badge bg-secondary">-</span>';

                        let botonEliminar = '';
                        if (peso.estado_id === 1) {
                            botonEliminar = `
                    <button class="btn btn-sm btn-danger btn-eliminar-otra-balanza" 
                            data-id="${peso.NroSalida}"
                            title="Eliminar peso">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
                        } else {
                            botonEliminar = `
                    <button class="btn btn-sm btn-secondary" 
                            disabled 
                            title="No se puede eliminar. Estado: ${peso.estado_nombre}">
                        <i class="fa fa-lock"></i>
                    </button>
                `;
                        }

                        tablaOtrasBody.append(`
                <tr>
                    <td>${peso.id}</td>
                    <td>${moment(peso.fechai).format('DD/MM/YYYY HH:mm')}</td>
                    <td>${moment(peso.fechas).format('DD/MM/YYYY HH:mm')}</td>
                    <td>${peso.placa ?? '-'}</td>
                    <td>${peso.conductor ?? '-'}</td>
                    <td>${peso.bruto ?? '-'}</td>
                    <td>${peso.tara ?? '-'}</td>
                    <td class="fw-bold text-success">${peso.neto ?? '-'}</td>
                    <td>${peso.balanza ?? '-'}</td>
                    <td>${peso.producto ?? '-'}</td>
                    <td>${estadoBadge}</td>
                    <td>${botonEliminar}</td>

                </tr>
            `);
                    });
                }
            }

            $(document).on('click', '#btnAgregarPesoManual', function() {
                const programacionId = $('#programacion_id').val();

                if (!programacionId) {
                    return Swal.fire('Atención', 'Primero debes crear o seleccionar una programación.',
                        'warning');
                }

                const form = document.getElementById('formPesoManual');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                Swal.fire({
                    title: '¿Agregar este peso?',
                    html: `
            <div class="text-start">
                <p><strong>Neto:</strong> ${data.neto} kg</p>
                <p><strong>Balanza:</strong> ${data.balanza || 'N/A'}</p>
                <p><strong>Placa:</strong> ${data.placa || 'N/A'}</p>
            </div>
        `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, agregar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/otras-balanza/${programacionId}`, data)
                            .then(response => {

                                Swal.fire({
                                    title: 'Éxito',
                                    text: 'Peso de otra balanza agregado correctamente.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    form.reset();

                                    axios.get(`/programaciones/${programacionId}/pesos`)
                                        .then(res => {
                                            const pesosTodos = res.data.pesos;
                                            const pesosBalanza = pesosTodos.filter(
                                                p => p.tipo === 'balanza');
                                            const pesosOtrasBalanzas = pesosTodos
                                                .filter(p => p.tipo === 'manual');

                                            const tablaBody = $(
                                                '#tablaPesos tbody');
                                            tablaBody.html('');

                                            if (pesosBalanza.length === 0) {
                                                tablaBody.html(
                                                    '<tr><td colspan="8" class="text-muted">No hay pesos disponibles</td></tr>'
                                                );
                                            } else {
                                                pesosBalanza.forEach(peso => {
                                                    const disabled = (peso
                                                            .estado_id ===
                                                            3 || peso
                                                            .estado_id === 4
                                                        ) ? 'disabled' :
                                                        '';
                                                    tablaBody.append(`
                                            <tr>
                                                <td><input type="checkbox" class="peso-check" value="${peso.NroSalida}" data-neto="${peso.Neto}" checked ${disabled}></td>
                                                <td>${peso.NroSalida}</td>
                                                <td>${peso.Fechas ?? '-'}</td>
                                                <td>${peso.Bruto ?? '-'}</td>
                                                <td>${peso.Tara ?? '-'}</td>
                                                <td class="fw-bold">${peso.Neto ?? '-'}</td>
                                                <td>${peso.Horai ?? '-'}</td>
                                                <td>${peso.Horas ?? '-'}</td>
                                            </tr>
                                        `);
                                                });
                                            }

                                            cargarTablaOtrasBalanzas(
                                                pesosOtrasBalanzas);

                                            calcularTotal();
                                        })
                                        .catch(err => {
                                            console.error(
                                                "Error al recargar pesos:", err);
                                        });
                                });
                            })
                            .catch(error => {
                                console.error('Error al guardar:', error);
                                const mensaje = error.response?.data?.message ||
                                    'No se pudo guardar el peso manual.';
                                Swal.fire('Error', mensaje, 'error');
                            });
                    }
                });
            });

            // Eliminar peso de otra balanza
            $(document).on('click', '.btn-eliminar-otra-balanza', function() {
                const pesoId = $(this).data('id');
                const programacionId = $('#programacion_id').val();

                Swal.fire({
                    title: '¿Eliminar este peso?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/otras-balanza/${pesoId}`)
                            .then(() => {
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: 'Peso eliminado correctamente',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                axios.get(`/programaciones/${programacionId}/pesos`)
                                    .then(res => {
                                        const pesosTodos = res.data.pesos;
                                        const pesosOtrasBalanzas = pesosTodos.filter(p => p
                                            .tipo === 'manual');
                                        cargarTablaOtrasBalanzas(pesosOtrasBalanzas);
                                        calcularTotal();
                                    });
                            })
                            .catch((error) => {
                                console.error('Error al eliminar:', error);
                                Swal.fire('Error', 'No se pudo eliminar el peso', 'error');
                            });
                    }
                });
            });
            $(document).on('change', '.peso-check', function() {
                calcularTotal();
            });

            function calcularTotal() {
                let total = 0;
                $('.peso-check').each(function() {
                    const neto = parseFloat($(this).data('neto') || 0);
                    if ($(this).is(':checked')) {
                        total += neto;
                    }
                });
                $('#peso_total').val(total.toFixed(2));
            }


        });



        $(document).on('input', '#formPesoManual input[name="bruto"], #formPesoManual input[name="tara"]', function() {
            const bruto = parseFloat($('#formPesoManual input[name="bruto"]').val()) || 0;
            const tara = parseFloat($('#formPesoManual input[name="tara"]').val()) || 0;
            const neto = bruto - tara;
            $('#formPesoManual input[name="neto"]').val(neto >= 0 ? neto.toFixed(2) : 0);
        });
    </script>
@endpush
