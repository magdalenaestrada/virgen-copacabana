<button class="btn btn-sm btn-outline-success" data-toggle="modal"
        data-target="#ModalAddPrecio{{ $reactivo->id }}">
    <i class="fas fa-dollar-sign"></i>
</button>

<div class="modal fade" id="ModalAddPrecio{{ $reactivo->id }}" tabindex="-1" role="dialog" aria-labelledby="addPrecioLabel{{ $reactivo->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('reactivosdetalles.store') }}" method="POST">
      @csrf
      <input type="hidden" name="reactivo_id" value="{{ $reactivo->id }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Añadir Precio a {{ $reactivo->productos->nombre_producto }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
              <label>Precio</label>
              <input type="number" step="0.01" name="precio" class="form-control" required>
          </div>
          <div class="form-group">
              <label>Límite</label>
              <input type="number" step="0.0001" name="limite" class="form-control" required>
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
