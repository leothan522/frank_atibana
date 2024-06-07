@php
    $border = 'border: 1px solid #000000;';
    $color = 'background-color: #0c84ff;';
    $centro = 'text-align: center;'
@endphp
<table>
    <thead>
    <tr><th colspan="9">Empresa: {{ mb_strtoupper($empresa->nombre, 'utf8') }}</th></tr>
    <tr><th colspan="9">R.I.F: {{ $empresa->rif }}</th></tr>
    <tr><th colspan="9" style="text-align: center; font-weight: bold;">MOVIMIENTOS DEL ALMACEN</th></tr>
    <tr><th colspan="9">{{ mb_strtoupper($getNombre, 'utf8') }}</th></tr>
    <tr>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Nro. Ajuste</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Descripción</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Tipo</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Fecha</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Articulo</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Segmento</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Unidad</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Cantidad</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }}">Saldo</th>
    </tr>
    </thead>
    <tbody>
    @if($getAjustes)
        @php($i = 0)
        @php($arraySaldo = array())
        @foreach($getAjustes as $ajuste)
            @if($ajuste->detalles->isNotEmpty())
                @if($i >= $getLimit)
                    @break
                @endif
                @foreach($ajuste->detalles as $detalle)
                    @php($i++)
                    @if(!array_key_exists($detalle->articulo->codigo,$arraySaldo))
                        @php($arraySaldo[$detalle->articulo->codigo] = $getSaldo)
                    @endif
                    <tr>
                        <td style="{{ $border }}">{{ $ajuste->codigo }}</td>
                        <td style="{{ $border }}">{{ mb_strtoupper($ajuste->descripcion, 'utf8') }}</td>
                        <td style="{{ $border }}">{{ $detalle->tipo->codigo }}</td>
                        <td style="{{ $border }}">{{ diaEspanol($ajuste->fecha) }}, {{ verFecha($ajuste->fecha, 'd/m/Y h:i a') }}</td>
                        <td style="{{ $border }}">
                            <small>({{ $detalle->articulo->codigo }})</small>&nbsp;
                            <span>{{ mb_strtoupper($detalle->articulo->descripcion, 'utf8') }}</span>
                        </td>
                        <td style="{{ $border }}">
                            @if($ajuste->segmentos_id)
                                {{ mb_strtoupper($ajuste->segmentos->descripcion, 'utf8') }}
                            @endif
                        </td>
                        <td style="{{ $border }}">{{ $detalle->unidad->codigo }}</td>
                        <td style="{{ $border }}">
                            <span>
                                @if($detalle->tipo->tipo == 1)
                                    {{ $detalle->cantidad }}
                                @else
                                    {{ $detalle->cantidad * -1 }}
                                @endif
                            </span>
                        </td>
                        <td style="{{ $border }}">
                            <span>
                                {{ $arraySaldo[$detalle->articulo->codigo] }}
                            </span>
                        </td>
                    </tr>
                    @php($saldo = $arraySaldo[$detalle->articulo->codigo])
                    @if($detalle->tipo->tipo == 1)
                        @php($arraySaldo[$detalle->articulo->codigo] = $saldo - $detalle->cantidad)
                    @else
                        @php($arraySaldo[$detalle->articulo->codigo] = $saldo + $detalle->cantidad)
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
</table>
