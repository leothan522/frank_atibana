<tr xmlns:wire="http://www.w3.org/1999/xhtml">
    <th scope="row" class="text-nowrap">
        @if($contador == 1)
            <span class="btn btn-default btn-xs disabled">
                <i class="fas fa-minus"></i>
            </span>
        @else
            <span wire:click="btnContador('{{ $i }}')" class="btn btn-default btn-xs">
                <i class="fas fa-minus"></i>
            </span>
        @endif
        <span class="">{{ $i + 1 }}</span>
    </th>
    <td>
        <input type="text" class="form-control form-control-sm {{ $classReceta[$i] }}
        @error('codigoReceta.'.$i) is-invalid @enderror" wire:model.blur="codigoReceta.{{ $i }}"
               data-toggle="tooltip" data-placement="bottom" title="{{ $codigoReceta[$i] }}" placeholder="código">
    </td>
    <td>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend" wire:click="itemTemporal({{ $i }})"
                 data-toggle="modal" data-target="#modal-buscar-recetas" style="cursor: pointer">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control form-control-sm"
                   data-toggle="tooltip" data-placement="bottom" title="{{ $descripcionReceta[$i] }}"
                   wire:model.live="descripcionReceta.{{ $i }}" placeholder="Descripción"
                   readonly>
        </div>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm {{ $classAlmacen[$i] }} @error('codigoAlmacen.'.$i) is-invalid @enderror"
               wire:model.blur="codigoAlmacen.{{ $i }}" placeholder="código">
    </td>
    <td>
        <input type="number" class="form-control form-control-sm
        @error('cantidad.'.$i) is-invalid @enderror" min="0.001" step=".001"
               wire:model="cantidad.{{ $i }}">
    </td>
</tr>
