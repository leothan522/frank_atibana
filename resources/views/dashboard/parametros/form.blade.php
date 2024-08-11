<div>

    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text text-bold">nombre</span>
            </div>
            <input type="text" class="form-control" wire:model="nombre" name="nombre"
                   placeholder="[string]">
            @error('nombre')
            <span class="col-sm-12 text-sm text-bold text-danger">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text text-bold">tabla_id</span>
            </div>
            <input type="text" class="form-control" wire:model="tabla_id" name="tabla_id"
                   placeholder="[integer]">
            @error('tabla_id')
            <span class="col-sm-12 text-sm text-bold text-danger">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text text-bold">valor</span>
            </div>
            <input type="text" class="form-control" wire:model="valor" name="valor" placeholder="[string]">
            @error('valor')
            <span class="col-sm-12 text-sm text-bold text-danger">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </span>
            @enderror
        </div>
    </div>

    {{--<div class="form-group text-right">
        <input type="submit" class="btn btn-block @if($view == "edit") btn-primary @else btn-success @endif " value="Guardar @if($view == "edit") Cambios @endif">
    </div>--}}

</div>
