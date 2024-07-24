<div class="card card-navy" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="card-header">
        <h3 class="card-title">
            @if($keyword)
                Búsqueda { <b class="text-warning">{{ $keyword }}</b> }
                <button class="btn btn-tool text-warning" wire:click="limpiarSegmentos"><i class="fas fa-times-circle"></i>
                </button>
            @else
                Segmentos [ <b class="text-warning">{{ $rowsSegmento }}</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="limpiarSegmentos">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" class="btn btn-tool" wire:click="setLimit" @if($rows > $rowsSegmento) disabled @endif >
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>
    <div class="card-body table-responsive p-0" style="height: 60vh;">
        <table class="table table-sm table-head-fixed table-hover text-nowrap">
            <thead>
            <tr class="text-navy">
                <th>Descripción</th>
                <th style="width: 5%;">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @if($listarSegmentos->isNotEmpty())
                @foreach($listarSegmentos as $tipo)
                    <tr>
                        <td class="text-uppercase">{{ $tipo->descripcion }}</td>
                        <td class="justify-content-end">
                            <div class="btn-group">
                                <button wire:click="edit({{ $tipo->id }})" class="btn btn-primary btn-sm"
                                @if(!comprobarPermisos()) disabled @endif >
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button wire:click="destroy({{ $tipo->id }})" class="btn btn-primary btn-sm"
                                @if(!comprobarPermisos() || $tipo->tipo) disabled @endif >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr class="text-center">
                    <td colspan="2">
                        <span>Aún no se ha creado un Segmento.</span>
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <small>Mostrando {{ $listarSegmentos->count() }}</small>
    </div>
</div>
