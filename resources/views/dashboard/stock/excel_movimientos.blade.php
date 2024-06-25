@php
    $border = 'border: 1px solid #000000;';
    $color = 'background-color: #0c84ff;';
    $centro = 'text-align: center;';
    $title = 'color: white;';
@endphp
<table>
    <thead>
    <tr><th colspan="9">Empresa: {{ mb_strtoupper($empresa->nombre, 'utf8') }}</th></tr>
    <tr><th colspan="9">R.I.F: {{ mb_strtoupper($empresa->rif, 'utf8') }}</th></tr>
    <tr><th colspan="9" style="text-align: center; font-weight: bold;">MOVIMIENTOS DEL ALMACEN</th></tr>
    <tr><th colspan="9">{{ mb_strtoupper($almacen->nombre, 'utf8') }}</th></tr>
    <tr>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Tipo</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Modulo</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Código</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Fecha</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Segmento</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Articulo</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Descripción</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Unidad</th>
        <th style="{{ $color }}{{ $border }}{{ $centro }} {{ $title }}">Cantidad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($listarMovimientos as $movimientos)
        @foreach($movimientos['detalles'] as $detalle)
            <tr>
                <td style="{{ $border }}">{{ mb_strtoupper($detalle['tipo'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($movimientos['tabla'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($movimientos['codigo'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ verFecha($movimientos['fecha'], 'd/m/Y H:i') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($movimientos['segmento'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($detalle['codigo'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($detalle['articulo'], 'utf8') }}</td>
                <td style="{{ $border }}">{{ mb_strtoupper($detalle['unidad'], 'utf8') }}</td>
                <td style="{{ $border }}">
                    <span>
                        @if($detalle['entrada'])
                            {{ $detalle['cantidad'] }}
                        @else
                            {{ $detalle['cantidad'] * -1 }}
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
