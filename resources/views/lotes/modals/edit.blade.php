@foreach ($lotes as $lote)
    <div class="modal fade" id="ModalEditLote{{ $lote->id }}" tabindex="-1" role="dialog"
        aria-labelledby="editLoteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('lotes.update', $lote->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Lote</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre del Lote</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $lote->nombre }}">
                        </div>

                        <div class="combo form-group">
                            <label>Cliente</label>
                            <input type="text" class="clienteInput form-control" placeholder="Escriba para buscar..."
                                value="{{ $lote->cliente->nombre ?? '' }}">

                            <select class="clienteSelect form-control">
                                <option value="">-- Seleccionar cliente --</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" data-codigo="{{ $cliente->codigo }}"
                                        data-lotes="{{ $cliente->lotes->count() }}"
                                        {{ $lote->cliente_id == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->codigo }} - {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="lq_cliente_id" class="clienteHidden"
                                value="{{ $lote->cliente_id }}">
                        </div>

                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigo" class="form-control codigo-lote-input"
                                value="{{ $lote->codigo }}">
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
@endforeach
