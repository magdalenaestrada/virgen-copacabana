@extends('admin.layout')

@section('content')
    <br>
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Gestión de Pesos</h4>
            </div>
            <div class="card-body">
                @php
                    use Carbon\Carbon;
                    $hoy = Carbon::now('America/Lima')->format('Y-m-d');
                @endphp

                <form id="filtros" method="POST" action="{{ route('pesos') }}" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-date text-primary"></i> Fecha
                                Desde</label>
                            <input type="date" name="desde" max="{{ $hoy }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-check text-primary"></i> Fecha
                                Hasta</label>
                            <input type="date" name="hasta" class="form-control" value="{{ $hoy }}"
                                max="{{ $hoy }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Razón Social</label>
                            <input type="text" name="RazonSocial" class="form-control" placeholder="Buscar Razón Social">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Origen</label>
                            <input type="text" name="origen" class="form-control" placeholder="Buscar Origen">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Destino</label>
                            <input type="text" name="destino" class="form-control" placeholder="Buscar Destino">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">N° Salida</label>
                            <input type="text" name="NroSalida" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Producto</label>
                            <input type="text" name="Producto" class="form-control" placeholder="Buscar Producto">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Neto</label>
                            <input type="text" name="Neto" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Conductor</label>
                            <input type="text" name="Conductor" class="form-control" placeholder="Buscar Conductor">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Placa</label>
                            <input type="text" name="Placa" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Observación</label>
                            <input type="text" name="Observacion" class="form-control" placeholder="Buscar Observación">
                        </div>
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <button class="btn btn-primary flex-fill" type="submit"><i class="bi bi-funnel-fill me-1"></i>
                                Filtrar</button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <a href="{{ route('pesos.index') }}" class="btn btn-light flex-fill"><i
                                    class="bi bi-funnel-fill me-1"></i> Limpiar</a>
                        </div>
                    </div>
                </form>

                <hr class="my-4">
                <div id="tabla-container">
                    @include('pesos.partials.tabla')
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const filtrosForm = document.getElementById("filtros");
            const tablaContainer = document.querySelector("#tabla-container");

            async function cargarTabla(url = "{{ route('pesos') }}") {
                const formData = new FormData(filtrosForm);
                try {
                    const res = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    });
                    const html = await res.text();
                    tablaContainer.innerHTML = html;
                } catch (err) {
                    console.error("Error al recargar tabla:", err);
                }
            }

            filtrosForm.querySelectorAll("input, select").forEach(input => {
                input.addEventListener("input", () => cargarTabla());
            });

            filtrosForm.addEventListener("submit", e => {
                e.preventDefault();
                cargarTabla();
            });

            document.addEventListener("click", async e => {
                const link = e.target.closest(".pagination a");
                if (link) {
                    e.preventDefault();
                    return cargarTabla(link.href);
                }
            });

            document.addEventListener("click", async e => {
                const btn = e.target.closest('.guardar-btn');
                if (!btn) return;

                const pesoId = btn.dataset.peso;
                const estadoSelect = document.querySelector(`.estado-select[data-peso="${pesoId}"]`);
                const inputLote = document.querySelector(`.lote-input[data-peso="${pesoId}"]`);
                const selectLote = inputLote.closest('.combo').querySelector('.lote-select');

                const estadoId = estadoSelect?.value;
                const loteNombre = inputLote?.value.trim();

                const option = Array.from(selectLote.options)
                    .find(opt => opt.text.trim().toLowerCase() === loteNombre.toLowerCase());

                const loteId = option ? option.value : null;

                if (!estadoId || !loteNombre) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor selecciona un estado y escribe un lote válido.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                if (!loteId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lote no encontrado',
                        text: 'El lote ingresado no coincide con ninguno de la lista.',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                try {
                    const res = await fetch(`{{ route('pesos.update', '') }}/${pesoId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            estadoId,
                            loteId
                        })
                    });

                    const result = await res.json();

                    if (res.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado correctamente',
                            text: 'El registro se actualizó exitosamente.',
                            timer: 1300,
                            showConfirmButton: false
                        });

                        const fila = btn.closest('tr');
                        fila.classList.add('table-success');

                        estadoSelect.disabled = true;
                        inputLote.disabled = true;
                        btn.disabled = true;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-secondary');
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Guardado';

                    } else {
                        throw new Error(result.message || 'Error desconocido');
                    }

                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message || 'Error al guardar los datos.',
                        confirmButtonColor: '#d33'
                    });
                }
            });


            $(document).ready(function() {
                $(document).on('input', '.lote-input', function() {
                    const input = $(this);
                    const search = input.val().toLowerCase();
                    const select = input.siblings('.lote-select');

                    if (search.length > 0) {
                        select.show();
                        select.find('option').each(function() {
                            const optionText = $(this).text().toLowerCase();
                            $(this).toggle(optionText.includes(search));
                        });
                    } else {
                        select.hide();
                    }
                });

                $(document).on('change', '.lote-select', function() {
                    const select = $(this);
                    const option = select.find('option:selected');
                    const input = select.siblings('.lote-input');
                    const hidden = select.siblings('.lote-hidden');

                    input.val(option.text().trim());
                    hidden.val(option.val());

                    const codigoBase = option.data('codigo');
                    if (codigoBase) {
                        const nuevoNumero = parseInt(option.data('lotes') || 0) + 1;
                        const codigoGenerado = `${codigoBase}-${nuevoNumero}`;
                        input.closest('tr').find('#codigo_lote').val(codigoGenerado);
                    }

                    select.hide();
                });
            });

        });
    </script>
@endpush
