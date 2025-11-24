<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Orden de compra {{ $orden->codigo }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --txt: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --head: #111827;
            --thead: #f3f4f6;
            --chip: #eef6ff;
            --radius: 12px;
        }

        @page {
            size: A4;
            margin: 18mm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        html,
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            color: var(--txt);
            font-size: 12px;
        }

        .doc {
            position: relative;
        }

        /* Marca de agua (del diseño 1) */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: .06;
            z-index: 0;
            pointer-events: none;
            width: 70%;
            max-width: 600px;
        }

        .watermark img {
            width: 100%;
        }

        /* Header simple (del diseño 1) */
        .doc-header {
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 8px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand img {
            width: 56px;
        }

        .doc-title {
            text-align: center;
            grid-column: 1/-1;
            font-weight: 700;
            color: var(--head);
            margin-top: -6px;
            letter-spacing: .5px;
        }

        .muted {
            color: var(--muted);
        }

        hr {
            border: none;
            border-top: 1px solid var(--line);
            margin: 8px 0;
        }

        /* ======= CAJAS tipo tarjeta (del diseño 2) ======= */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 12px;
            background: #fff;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .02);
        }

        .card h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: var(--head);
            letter-spacing: .3px
        }

        .kv {
            display: grid;
            grid-template-columns: 1.2fr 2.2fr;
            gap: 6px;
            padding: 6px 0;
            align-items: center;
        }

        .kv+.kv {
            border-top: 1px dashed #eee;
        }

        .label {
            color: var(--muted);
        }

        .value {
            font-weight: 600;
        }

        /* Tabla de items (del diseño 1) */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            position: relative;
            z-index: 1;
        }

        table.items thead th {
            background: var(--thead);
            font-weight: 600;
            border: 1px solid var(--line);
            padding: 8px;
            text-align: center;
        }

        table.items tbody td {
            border: 1px solid var(--line);
            padding: 8px;
            vertical-align: middle;
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        /* Totales a la derecha (del diseño 1) */
        .totals {
            width: 50%;
            margin-left: auto;
            margin-top: 8px;
            border-collapse: collapse;
            position: relative;
            z-index: 1;
        }

        .totals td {
            padding: 6px 8px;
            border: 1px solid var(--line);
        }

        .totals tr td:first-child {
            background: var(--thead);
            font-weight: 600;
        }

        .grand {
            font-weight: 800;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <!-- Marca de agua -->
    <div class="watermark">
        <img src="{{ asset('images/virgen.png') }}" alt="Marca de agua">
    </div>

    <div class="doc">
        <!-- Header -->
        <div class="doc-header">
            <div class="brand">
                <img src="{{ asset('images/virgen.png') }}" alt="Logo">
                <div>
                    <div style="font-weight:700">AGROINDUSTRIAL VIRGENCITA DE COPACABANA S.A.C</div>
                    <div class="muted" style="font-size:11px">RUC 20614686759</div>
                </div>
            </div>
            <div class="text-end">
                <div><span class="muted">Orden:</span> <strong>{{ $orden->codigo }}</strong></div>
                <div>Nasca, {{ $orden->created_at->day }}
                    de {{ \Illuminate\Support\Str::ucfirst($orden->created_at->translatedFormat('F')) }}
                    del {{ $orden->created_at->year }}</div>
            </div>
            <h3 class="doc-title">ORDEN DE COMPRA</h3>
        </div>

        <hr>

        <!-- Cajas mezcladas -->
        <div class="grid-2">
            <div class="card">
                <h4>Datos del Cliente</h4>
                <div class="kv">
                    <div class="label">Razón Social</div>
                    <div class="value">AGROINDUSTRIAL VIRGENCITA DE COPACABANA S.A.C</div>
                </div>
                <div class="kv">
                    <div class="label">RUC</div>
                    <div class="value">20614686759</div>
                </div>
                @php
                    $estados = [
                        'P' => ['label' => 'PENDIENTE'],
                        'E' => ['label' => 'EN PROCESO'],
                        'F' => ['label' => 'COMPLETADO'],
                        'A' => ['label' => 'CANCELADO'],
                        'C' => ['label' => 'PAGADO'],
                    ];

                    $estado = $estados[$orden->estado_servicio] ?? [
                        'label' => 'Desconocido',
                        'color' => 'bg-secondary',
                    ];
                @endphp

                <div class="kv">
                    <div class="label">Estado</div>
                    <div class="value">
                        <span>
                            {{ $estado['label'] }}
                        </span>
                    </div>
                </div>
                <div class="kv">
                    <div class="label">Descripcion</div>
                    <div class="value">{{ $orden->descripcion }}</div>
                </div>
            </div>

            <div class="card">
                <h4>Datos del Proveedor</h4>
                @if ($orden->proveedor)
                    <div class="kv">
                        <div class="label">Razón Social</div>
                        <div class="value">{{ $orden->proveedor->razon_social }}</div>
                    </div>
                    <div class="kv">
                        <div class="label">RUC</div>
                        <div class="value">{{ $orden->proveedor->ruc }}</div>
                    </div>
                    <div class="kv">
                        <div class="label">Teléfono</div>
                        <div class="value">{{ $orden->proveedor->telefono ?? '-' }}</div>
                    </div>
                    <div class="kv">
                        <div class="label">Dirección</div>
                        <div class="value">{{ $orden->proveedor->direccion ?? '-' }}</div>
                    </div>
                @else
                    <div class="kv">
                        <div class="label">Proveedor</div>
                        <div class="value">—</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabla de productos -->
        @if ($orden->detalles->count())
            <table class="items">
                <thead>
                    <tr>
                        <th>PRODUCTO DEL REQUERIMIENTO</th>
                        <th style="width:100px">CANTIDAD</th>
                        <th style="width:140px">VALOR UNITARIO</th>
                        <th style="width:140px">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->detalles as $producto)
                        <tr>
                            <td>{{ $producto->nombre_producto }}</td>
                            <td class="text-center">{{ number_format($producto->cantidad, 0) }}</td>
                            <td class="text-right">{{ number_format($producto->precio_unitario, 2) }}</td>
                            <td class="text-right">{{ number_format($producto->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>SUMA</strong></td>
                        <td class="text-right">
                            {{ $orden->costo_estimado > 0
                                ? number_format($orden->costo_estimado, 2)
                                : number_format($orden->costo_estimado, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Totales -->
        @php
            $mon = strtoupper($orden->tipomoneda);
            $igv = round($orden->costo_estimado * 0.18, 2);
        @endphp
        <table class="totals">
            <tr>
                <td>DESCUENTO</td>
                <td class="text-right">{{ number_format($orden->descuento, 2) }} {{ $mon }}</td>
            </tr>
            <tr>
                <td>SUBTOTAL</td>
                <td class="text-right">{{ number_format($orden->costo_estimado, 2) }} {{ $mon }}</td>
            </tr>
            <tr>
                <td>IGV (18%)</td>
                <td class="text-right">{{ number_format($igv, 2) }} {{ $mon }}</td>
            </tr>
            <tr class="grand">
                <td>PRECIO TOTAL</td>
                <td class="text-right">{{ number_format($orden->costo_final, 2) }} {{ $mon }}</td>
            </tr>
        </table>
    </div>

    {{-- <script>window.print()</script> --}}
</body>

</html>
