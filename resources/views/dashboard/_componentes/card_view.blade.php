<div class="card card-navy" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;"
     xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="card-header">
        <h3 class="card-title">
            {{--@if($nuevo)
                Registrar
            @endif
            @if($ver)
                Ver
            @endif
            @if(!$editar && $cancelar && !$nuevo)
                Editar
            @endif
            @if($imagenes)
                Imagenes
            @endif--}}
            Bienes
        </h3>
        <div class="card-tools">
            {{--<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
            </button>--}}
            <button type="button" class="btn btn-tool" wire:click="limpiar">
                <i class="fas fa-sync-alt"></i>
            </button>
            @if(/*!$nuevo*/true)
                <button class="btn btn-tool" wire:click="create" @if(!comprobarPermisos('bienes.create')) disabled @endif id="btn_create_bien" >
                    <i class="fas fa-file"></i> Nuevo
                </button>
            @endif
            @if(/*$editar*/true)
                <button class="btn btn-tool" wire:click="edit" @if(!comprobarPermisos('bienes.edit')) disabled @endif >
                    <i class="fas fa-edit"></i>
                    Editar
                </button>
                {{--<a href="--}}{{--{{ route('etiquetas.print', $articulos_id) }}--}}{{--#" target="_blank" class="btn btn-tool"><i class="fas fa-print"></i> Imprimir Etiqueta</a>--}}
            @endif

            @if(/*$cancelar*/true)
                <button class="btn btn-tool" wire:click="btnCancelar">
                    <i class="fas fa-ban"></i> Cancelar
                </button>
            @endif

        </div>
    </div>

    <div class="card-body">

        {{--<div @if(!$form) class="d-none" @endif >
            @include('dashboard.bienes._layout.form')
        </div>

        <div @if(!$ver) class="d-none" @endif >
            @include('dashboard.bienes._layout.show')
        </div>

        <div @if(!$imagenes) class="d-none" @endif >
            @livewire('dashboard.imagenes-component')
        </div>--}}

        <div class="row m-5 @if(/*!$view*/false) d-none @endif ">
            <div class="col-12">
                Debes seleccionar un Bien para empezar...
            </div>
        </div>

    </div>

    <div class="card-footer text-center @if(/*!$footer*/false) d-none @endif">


        <button type="button" class="btn btn-default btn-sm" {{--wire:click="btnUbicacion" onclick="verSpinnerOculto()"--}}
                data-toggle="modal" data-target="#modal-sm-bien-ubicacion"
            {{--@if(!comprobarPermisos('bienes.edit')) disabled @endif--}}>
            <i class="fas fa-thumbtack"></i> Ubicación
        </button>

        <button type="button" class="btn btn-default btn-sm" {{--wire:click="btnImagenes"--}}
                @if(/*$imagenes*/false || !comprobarPermisos('bienes.edit')) disabled @endif>
            <i class="fas fa-images"></i> Cambiar Imagenes
        </button>

        <button type="button" class="btn btn-default btn-sm" {{--wire:click="destroy"--}}
                @if(!comprobarPermisos('bienes.destroy')) disabled @endif>
            <i class="fas fa-trash-alt"></i> Eliminar
        </button>

    </div>


    {{--{!! verSpinner() !!}--}}

    <div class="overlay-wrapper" wire:loading wire:target="create, show, save, edit, destroy, confirmed, btnCancelar, btnImagenes">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <div class="overlay-wrapper d-none cargar_">
        <div class="overlay">
            <div class="spinner-border text-navy" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

</div>
