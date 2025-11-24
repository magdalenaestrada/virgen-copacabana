<div style="overflow-x: auto;">
    <table class="table table-bordered table-hover align-middle text-center"></table>
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>N° Salida</th>
                <th>Estado</th>
                <th>Lote</th>
                <th>Acción</th>
                <th>Fecha I.</th>
                <th>Fecha S.</th>
                <th>Producto</th>
                <th>Placa</th>
                <th>Conductor</th>
                <th>Razón Social</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Neto</th>
                <th>Observación</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pesos as $peso)
                @php
                    $estadoActual = optional($peso->estado);
                    $loteActual = optional($peso->lote);

                    $tieneEstado = !is_null($estadoActual->id);

                    $liquidado = $estadoActual->id === 3;
                    $retirado = $estadoActual->id === 4;
                    $cancha = $estadoActual->id === 1;
                    $procesado = $estadoActual->id === 2;

                    // Asignar clase de color de fila
                    $filaClase = '';
                    if ($liquidado) {
                        $filaClase = 'bg-liquidado';
                    } elseif ($retirado) {
                        $filaClase = 'bg-retirado';
                    } elseif ($cancha) {
                        $filaClase = 'bg-cancha';
                    } elseif ($procesado) {
                        $filaClase = 'bg-procesado';
                    } // neutro
                @endphp

                <tr class="{{ $filaClase }}">
                    <td>{{ $peso->NroSalida }}</td>
                    <td>
                        <select class="form-select form-select-sm estado-select w-150" data-peso="{{ $peso->NroSalida }}"
                            {{ $tieneEstado ? 'disabled' : '' }}>
                            <option value="">Elegir</option>
                            @foreach ($estados as $estadoOpt)
                                <option value="{{ $estadoOpt->id }}"
                                    {{ $estadoActual->id == $estadoOpt->id ? 'selected' : '' }}>
                                    {{ $estadoOpt->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="combo">
                            <input type="text" class="lote-input form-control form-control-sm"
                                placeholder="Escriba o seleccione..." value="{{ $loteActual->nombre ?? '' }}"
                                data-peso="{{ $peso->NroSalida }}" style="min-width: 150px;"
                                @if ($tieneEstado) disabled @endif>
                            <select class="lote-select form-control form-control-sm" size="5"
                                style="display: none; min-width: 150px; text-align: left;">
                                @foreach ($lotes as $loteOption)
                                    <option value="{{ $loteOption->id }}" data-codigo="{{ $loteOption->codigo_base }}">
                                        {{ $loteOption->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="lote_id[]" class="lote-hidden"
                                value="{{ $loteActual->id ?? '' }}">
                        </div>
                    </td>

                    <td>
                        @if (!$tieneEstado)
                            <button class="btn btn-success btn-sm guardar-btn" data-peso="{{ $peso->NroSalida }}">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        @endif
                    </td>
                    <td>{{ $peso->Fechai }}</td>
                    <td>{{ $peso->Fechas }}</td>
                    <td>{{ $peso->Producto }}</td>
                    <td>{{ $peso->Placa }}</td>
                    <td>{{ $peso->Conductor }}</td>
                    <td>{{ $peso->RazonSocial }}</td>
                    <td>{{ $peso->origen }}</td>
                    <td>{{ $peso->destino }}</td>
                    <td>{{ number_format($peso->Neto, 2) }}</td>
                    <td>{{ $peso->Observacion }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i> No hay registros
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pesos->links() }}
</div>
