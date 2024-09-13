<div>

    <div class="form-group">
        <label for="name">{{ __('Name') }}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" wire:model="name" placeholder="Nombre y Apellido">
            @error('name')
            <span class="col-sm-12 text-sm text-bold text-danger">
                <i class="icon fas fa-exclamation-triangle"></i>
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="email">{{ __('Email') }}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="text" class="form-control" wire:model="email" placeholder="Email">
            @error('email')
            <span class="col-sm-12 text-sm text-bold text-danger">
                <i class="icon fas fa-exclamation-triangle"></i>
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="exampleInputEmail1">{{ __('Password') }}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="text" class="form-control" wire:model="password" placeholder="Contraseña">
            <span class="input-group-append">
                <button type="button" wire:click="generarClave" class="btn btn-info btn-flat btn-sm text-sm">
                    <i class="fas fa-key"></i>
                </button>
            </span>
            @error('password')
            <span class="col-sm-12 text-sm text-bold text-danger">
                <i class="icon fas fa-exclamation-triangle"></i>
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="exampleInputEmail1">{{ __('Role') }}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
            </div>
            <select class="custom-select" wire:model="role">
                <option value="">Seleccione</option>
                {{--<option value="0">Estandar</option>--}}
                @foreach($listarRoles as $role)
                    @if($role->tabla_id == -1 || $role->valor == 0 || auth()->user()->role == 1 || auth()->user()->role == 100)
                        <option value="{{ $role->rowquid }}">{{ ucwords($role->nombre) }}</option>
                    @endif
                @endforeach
                {{--@if(comprobarPermisos())
                    <option value="1">Administrador</option>
                @endif--}}
            </select>
            @error('role')
            <span class="col-sm-12 text-sm text-bold text-danger">
                        <i class="icon fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </span>
            @enderror
        </div>
    </div>

</div>
