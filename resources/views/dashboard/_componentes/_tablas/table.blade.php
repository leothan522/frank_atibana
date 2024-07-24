<div class="card card-navy" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="card-header">
        <h3 class="card-title">
            @if(/*$keyword*/false)
                Búsqueda { <b class="text-warning">{{ $keyword }}</b> }
                <button class="btn btn-tool text-warning" wire:click="limpiarTipos">
                    <i class="fas fa-times-circle"></i>
                </button>
            @else
                Tipos [ <b class="text-warning">{{--{{ $rowsTipos }}--}}0</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="limpiar">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" class="btn btn-tool" {{--wire:click="setLimit" @if($rows > $rowsTipos) disabled @endif--}} >
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>
    <div class="card-body table-responsive p-0" style="height: 47vh;">
        <table class="table table-sm table-head-fixed table-hover text-nowrap">
            <thead>
            <tr class="text-navy">
                <th>Nombre</th>
                <th style="width: 5%;">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @if(/*$listarTipos->isNotEmpty()*/false)
                @foreach($listarTipos as $tipo)
                    <tr>
                        <td class="text-uppercase">{{ $tipo->nombre }}</td>
                        <td class="justify-content-end">
                            <div class="btn-group">
                                <button wire:click="edit({{ $tipo->id }})" class="btn btn-primary btn-sm"
                                @if(!comprobarPermisos('tipos.edit')) disabled @endif >
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button wire:click="destroy({{ $tipo->id }})" class="btn btn-primary btn-sm"
                                @if(!comprobarPermisos('tipos.destroy')) disabled @endif >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr class="text-center">
                    <td colspan="2">
                        @if(/*$keyword*/false)
                            <span>Sin resultados.</span>
                        @else
                            <span>Aún se se ha creado un Tipo.</span>
                        @endif
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <small>Mostrando {{--{{ $listarTipos->count() }}--}}0</small>
    </div>
</div>
