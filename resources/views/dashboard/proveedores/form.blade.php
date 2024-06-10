<div class="row" xmlns:wire="http://www.w3.org/1999/xhtml">

    <form class="row col-md-12" wire:submit="save">

        <div class="col-md-6">

            <div class="card card-outline card-navy">

                <div class="card-header">
                    <h5 class="card-title">Datos Básicos</h5>
                    <div class="card-tools">
                        <span class="btn-tool"><i class="fas fa-book"></i></span>
                    </div>
                </div>

                <div class="card-body">


                    <div class="form-group">
                        <label for="name">RIF:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="rif" placeholder="RIF del Proveedor">
                            @error('rif')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="nombre" placeholder="Nombre ó Razón social">
                            @error('nombre')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Teléfonos:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="telefono" placeholder="Teléfonos">
                            @error('telefono')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Dirección:</label>
                        <div class="input-group">
                            <textarea class="form-control" wire:model="direccion" placeholder="Dirección"></textarea>
                            @error('direccion')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>


                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card card-outline card-navy">

                <div class="card-header">
                    <h5 class="card-title">Información Adicional</h5>
                    <div class="card-tools">
                        <span class="btn-tool"><i class="fas fa-book"></i></span>
                    </div>
                </div>

                <div class="card-body">


                    <div class="form-group">
                        <label for="name">Banco:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="banco" placeholder="Banco (Opcional)">
                            @error('banco')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nro. Cuenta:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                            </div>
                            <input type="text" class="form-control" wire:model="cuenta" placeholder="Nro. Cuenta (Opcional)">
                            @error('cuenta')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Imagen:</label>

                        <div class="row justify-content-center attachment-block p-3">

                            <div class="d-none">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" wire:model.live="photo" class="custom-file-input" id="customFileLang"
                                               lang="es" accept="image/jpeg, image/png">
                                        <label class="custom-file-label text-sm" for="customFileLang" data-browse="Elegir">
                                            Seleccionar Imagen</label>
                                    </div>
                                    <input type="text" wire:model.live="img_borrar_principal">
                                </div>
                            </div>

                            <div class="col-md-6 {{--mt-3 mb-3--}}">
                                <div class="text-center" style="cursor:pointer;">
                                    <img class="img-thumbnail"
                                         @if ($photo) src="{{ $photo->temporaryUrl() }}" @else src="{{ asset(verImagen($verImagen)) }}" @endif
                                         {{--width="101" height="100"--}}  alt="Logo Tienda" onclick="imgEmpresa()"/>
                                    @if($verImagen)
                                        <button type="button" class="btn badge text-danger position-absolute float-right"
                                                wire:click="btnBorrarImagen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                @error('photo')
                                <span class="text-sm text-bold text-danger">
                                        <i class="icon fas fa-exclamation-triangle"></i>
                                         {{ $message }}
                                </span>
                                @enderror
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-12">
            <div class="col-md-4 float-right">
                <button type="submit" class="btn btn-block @if(!$new_proveedor) btn-primary @else btn-success @endif">
                    <i class="fas fa-save"></i> Guardar @if(!$new_proveedor) Cambios @endif
                </button>
            </div>
        </div>


    </form>
</div>
