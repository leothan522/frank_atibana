<div class="tab-pane fade @if($dia == 'miercoles') active show @endif " id="custom-tabs-three-miercoles" role="tabpanel"
     aria-labelledby="custom-tabs-three-miercoles-tab" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="row table-responsive p-0">

        <table class="table">
            <thead>
            <tr class="text-navy">
                <th style="width: 10%">#</th>
                <th>Receta</th>
                <th style="width: 50%">Descripción</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody>

            @for($i = 0; $i < $contador_miercoles; $i++)

                <tr>
                    <th scope="row" class="text-nowrap">
                        @if($contador_miercoles == 0)
                            <span class="btn btn-default btn-xs disabled">
                            <i class="fas fa-minus"></i>
                        </span>
                        @else
                            <span wire:click="btnContador('{{ $i }}', 'miercoles')" class="btn btn-default btn-xs">
                            <i class="fas fa-minus"></i>
                        </span>
                        @endif
                        <span class="">{{ $i + 1 }}</span>
                    </th>
                    <td>
                        <input type="text" class="form-control form-control-sm {{ $classRecetaMiercoles[$i] }}
                        @error('codigoRecetaMiercoles.'.$i) is-invalid @enderror" wire:model.blur="codigoRecetaMiercoles.{{ $i }}"
                               data-toggle="tooltip" data-placement="bottom" title="{{ $codigoRecetaMiercoles[$i] }}" placeholder="código">
                    </td>
                    <td>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend" wire:click="itemTemporal({{ $i }})"
                                 data-toggle="modal" data-target="#modal-buscar-recetas" style="cursor: pointer">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   data-toggle="tooltip" data-placement="bottom" title="{{ $descripcionRecetaMiercoles[$i] }}"
                                   wire:model.live="descripcionRecetaMiercoles.{{ $i }}" placeholder="Descripción"
                                   readonly>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm
                        @error('cantidadMiercoles.'.$i) is-invalid @enderror" min="0.001" step=".001"
                               wire:model="cantidadMiercoles.{{ $i }}">
                    </td>
                </tr>

            @endfor

            </tbody>
        </table>

    </div>

    <div class="row justify-content-start">
        <div class="col-md-2">
            <button type="button" wire:click="btnContador('add', 'miercoles')" class="btn btn-default btn-sm">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

</div>
