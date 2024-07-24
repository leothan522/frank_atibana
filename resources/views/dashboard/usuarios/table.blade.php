<div class="card card-outline card-navy" xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="card-header">
        <h3 class="card-title">
            @if($keyword)
                Busqueda { <b class="text-danger">{{ $keyword }}</b> }
                <button class="btn btn-tool text-danger" wire:click="limpiar">
                    <i class="fas fa-times-circle"></i>
                </button>
            @else
                Usuarios [ <b class="text-navy">{{ $rowsUsuarios }}</b> ]
            @endif
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" wire:click="limpiar">
                <i class="fas fa-sync-alt"></i>
            </button>
            @if(comprobarPermisos('usuarios.excel'))
                <a href="{{ route('usuarios.excel', $keyword) }}" class="btn btn-tool text-success swalDefaultInfo">
                    <i class="fas fa-file-excel"></i> <i class="fas fa-download"></i>
                </a>
            @endif
            <button type="button" class="btn btn-tool" wire:click="setLimit" @if($rows > $rowsUsuarios) disabled @endif >
                <i class="fas fa-sort-amount-down-alt"></i> Ver más
            </button>
        </div>
    </div>

    <div class="card-body table-responsive p-0" @if($tableStyle) style="height: 72vh;" @endif >
        <table class="table table-sm table-head-fixed table-hover text-nowrap">
            <thead>
            <tr class="text-navy">
                <th class="text-center"><i class="fas fa-cloud"></i></th>
                <th>Nombre</th>
                <th class="d-none d-lg-table-cell">Email</th>
                <th class="text-center">Rol</th>
                <th class="text-center">Estatus</th>
                <th class="text-right d-none">Creado</th>
                <th style="width: 5%;">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @if($listarUsers->isNotEmpty())
                @foreach($listarUsers as $user)
                    @if($user->role == 100 && auth()->user()->role != 100) @continue @endif
                    <tr>
                        <td class="text-center">
                            @if($user->plataforma)
                                <i class="fas fa-mobile"></i>
                            @else
                                <i class="fas fa-desktop"></i>
                            @endif
                        </td>
                        <td>{{ ucwords($user->name) }}</td>
                        <td class="d-none d-lg-table-cell">{{ strtolower($user->email) }}</td>
                        <td class="text-center">{{ verRole($user->role, $user->roles_id) }}</td>
                        <td class="text-center">
                            {!! $this->getEstatusUsuario($user->estatus, true) !!}
                            {{--<span class="text-sm"> ID: {{ $user->id }}</span>--}}
                        </td>
                        <td class="text-right d-none">{{ haceCuanto($user->created_at)  }}</td>
                        <td class="justify-content-end">
                            <div class="btn-group">
                                <button wire:click="edit({{ $user->id }})" class="btn btn-primary btn-sm"
                                        data-toggle="modal" data-target="#modal-user-edit"
                                        @if(!comprobarPermisos('usuarios.edit') || !($user->role != 1 || ($user->role ==1 && comprobarPermisos())) || ($user->id == auth()->id() && auth()->user()->role != 100)) disabled @endif>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="edit({{ $user->id }})" class="btn btn-primary btn-sm"
                                        data-toggle="modal" data-target="#modal-user-permisos" @if(!comprobarPermisos() || ($user->role == 1 && auth()->user()->role != 100)) disabled @endif>
                                    <i class="fas fa-user-cog"></i>
                                </button>
                                {{--<button wire:click="destroy({{ $user->id }})" class="btn btn-primary btn-sm"
                                        @if(!comprobarPermisos('usuarios.destroy') || !($user->role != 1 || ($user->role == 1 && comprobarPermisos())) || ($user->id == auth()->id())) disabled @endif >
                                    <i class="fas fa-trash-alt"></i>
                                </button>--}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="text-center">
                    <td colspan="7">
                        @if($keyword)
                            <span>Sin resultados</span>
                        @else
                            <span>Sin registros guardados</span>
                        @endif
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <small>Mostrando {{ $listarUsers->count() }}</small>
    </div>

    {!! verSpinner() !!}

</div>
