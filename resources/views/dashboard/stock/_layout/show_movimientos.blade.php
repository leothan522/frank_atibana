<div class="col-md-12" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="card">

        <div class="card-header border-0">
            <h3 class="card-title text-uppercase">{{ $getNombre }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" wire:click="aumetarLimit">
                    <i class="fas fa-sort-amount-down-alt"></i> Ver más
                </button>
                <a href="{{ route('movimientos.reportes', [$getAlmacen ?? 0, $empresas_id ?? 1, $getLimit]) }}" class="btn btn-tool btn-sm" target="_blank">
                    <i class="fas fa-download"></i>
                </a>
                <button type="button" class="btn btn-tool" data-card-widget="remove" wire:click="limpiarStock" {{--onclick="verSpinnerOculto()"--}}>
                    <i class="fas fa-times"></i> {{--{{ $limit }} - {{ $getLimit }}--}}
                </button>
            </div>
        </div>

        <div class="card-body table-responsive p-0" style="height: 60vh;">
            <table class="table table-head-fixed table-striped table-valign-middle table-sm">
                <thead>
                <tr>
                    <th class="d-none d-md-table-cell text-center">Tipo</th>
                    <th class="d-none d-md-table-cell text-center">Fecha</th>
                    <th>Articulo</th>
                    <th>Segmento</th>
                    <th class="d-none d-md-table-cell text-right">Unidad</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right"><span class="mr-2">Saldo</span></th>
                    <th class="d-none d-md-table-cell text-center" style="width: 2%">Más</th>
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
                                <tr @if($modulo == 'compartir') onclick="verAjuste({{ $ajuste->id }})" style="cursor: pointer;" @endif >
                                    <td class="d-none d-md-table-cell text-center">{{ $detalle->tipo->codigo }}</td>
                                    <td class="d-none d-md-table-cell text-center">{{ diaEspanol($ajuste->fecha) }}, {{ verFecha($ajuste->fecha, 'd/m/Y h:i a') }}</td>
                                    <td class="text-uppercase">
                                        <span class="d-none d-md-table-cell">{{ $detalle->articulo->descripcion }}</span>
                                        <small class="d-md-none">{{ $detalle->articulo->codigo }}</small>
                                    </td>
                                    <td class="text-uppercase">
                                        @if($ajuste->segmentos_id)
                                            {{ $ajuste->segmentos->descripcion }}
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell text-right">{{ $detalle->unidad->codigo }}</td>
                                    <td class="text-right">
                                        <span class="text-nowrap">
                                            @if($detalle->tipo->tipo == 1)
                                                <small class="text-success mr-1">
                                                <i class="fas fa-arrow-up"></i>
                                                </small>
                                            @else
                                                <small class="text-danger mr-1">
                                                <i class="fas fa-arrow-down"></i>
                                                </small>
                                            @endif
                                            {{ formatoMillares($detalle->cantidad, 0) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-nowrap mr-2">
                                            {{ formatoMillares($arraySaldo[$detalle->articulo->codigo], 0) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell text-center">
                                        <button type="button" class="btn btn-link text-muted"
                                                wire:click="irAjuste({{ $ajuste->id }}, '{{ $ajuste->codigo }}')" onclick="irAjuste()" >
                                            <i class="fas fa-search"></i>
                                        </button>
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
        </div>

        {!! verSpinner() !!}

    </div>
</div>

