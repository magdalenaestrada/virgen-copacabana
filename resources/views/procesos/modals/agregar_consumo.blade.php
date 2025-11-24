<!-- Modal Agregar Consumo -->
<div class="modal fade" id="modalConsumo" tabindex="-1" aria-labelledby="modalConsumoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsumoLabel">Agregar Consumo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
            </div>

            <div class="modal-body">
                <form id="formConsumo">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Reactivo</label>
                        <select id="reactivo_id" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach ($stock_reactivos as $reactivo)
                                <option value="{{ $reactivo->id }}">{{ $reactivo->producto->nombre_producto }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" step="0.01" id="cantidad" class="form-control" required>
                    </div>
                    @php
                        use Carbon\Carbon;
                        $hoy = Carbon::now('America/Lima')->format('Y-m-d');
                    @endphp
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" id="fecha" class="form-control" max={{ $hoy }} required>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnGuardarConsumo" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
