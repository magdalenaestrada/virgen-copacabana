<!-- Botón Ver Precios -->
<button class="btn btn-sm btn-outline-primary mr-1" data-toggle="modal"
        data-target="#ModalViewPrecios{{ $reactivo->id }}">
    <i class="fas fa-eye"></i>
</button>

<div class="modal fade" id="ModalViewPrecios{{ $reactivo->id }}" tabindex="-1" role="dialog" aria-labelledby="viewPreciosLabel{{ $reactivo->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Precios de {{ $reactivo->productos->nombre_producto }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if ($reactivo->detalles->count() > 0)
          <ul class="list-group">
            @foreach ($reactivo->detalles as $detalle)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Precio: {{ $detalle->precio }} | Límite: {{ $detalle->limite }}
                <button class="btn btn-danger btn-sm btn-eliminar-detalle" data-id="{{ $detalle->id }}">
                    <i class="fas fa-trash"></i>
                </button>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted">No hay precios registrados.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
