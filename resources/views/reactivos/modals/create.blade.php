<!-- Modal Crear Reactivo -->
<div class="modal fade" id="ModalCreateReactivo" tabindex="-1" role="dialog" aria-labelledby="createReactivoLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('reactivos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReactivoLabel">Crear Nuevo Reactivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label>Nombre del Reactivo</label>
                        <select name="producto_id" id="producto_id" class="form-control" required>
                            <option value="" selected disabled>Seleccione un reactivo...</option>

                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
