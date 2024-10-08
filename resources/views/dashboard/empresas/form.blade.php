<form class="row" wire:submit="save">

    <div class="col-md-6">

        <div class="card card-outline card-navy">

            <div class="card-header">
                <h5 class="card-title">Información</h5>
                <div class="card-tools">
                    <span class="btn-tool"><i class="fas fa-book"></i></span>
                </div>
            </div>

            <div class="card-body">


                <div class="form-group">
                    {{--<label for="name">{{ __('Name') }}</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                                <span class="input-group-text">{{--<i class="fas fa-book"></i>--}}{{ __('Name') }}</span>
                        </div>
                        <input type="text" class="form-control" wire:model="nombre" placeholder="Nombre de la Empresa">
                        @error('nombre')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    {{--<label for="email">RIF</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{--<i class="fas fa-id-card"></i>--}}RIF</span>
                        </div>
                        <input type="text" class="form-control" wire:model="rif" placeholder="RIF de la Empresa">
                        @error('rif')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    {{--<label for="name">Jefe</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{--<i class="fas fa-user"></i>--}}Jefe</span>
                        </div>
                        <input type="text" class="form-control" wire:model="jefe"
                               placeholder="Nombre del jefe de la Empresa">
                        @error('jefe')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    {{--<label for="email">Moneda Base</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                                <span class="input-group-text">{{--<i class="fas fa-credit-card"></i>--}}Moneda Base</span>
                        </div>
                        <select class="custom-select" wire:model="moneda">
                            <option value="">Seleccione</option>
                            <option value="Bolivares">Bolivares</option>
                            <option value="Dolares">Dolares</option>
                        </select>
                        @error('moneda')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    {{--<label for="email">Telefonos</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{--<i class="fas fa-phone"></i>--}}Teléfonos</span>
                        </div>
                        <input type="text" class="form-control" wire:model="telefonos" placeholder="Telefonos">
                        @error('telefonos')
                            <span class="col-sm-12 text-sm text-bold text-danger">
                                <i class="icon fas fa-exclamation-triangle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    {{--<label for="email">Email</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{--<i class="fas fa-envelope"></i>--}}Email</span>
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
                    {{--<label for="email">Dirección</label>--}}
                    <div class="input-group {{--mb-3--}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{--<i class="fas fa-directions"></i>--}}Dirección</span>
                        </div>
                        <input type="text" class="form-control" wire:model="direccion" placeholder="Dirección">
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
                <h5 class="card-title">Imagen</h5>
                <div class="card-tools">
                    <span class="btn-tool"><i class="fas fa-image"></i></span>
                </div>
            </div>

            <div class="card-body">


                <div class="row justify-content-center attachment-block p-3"
                     x-data="{ uploading: false, progress: 0 }"
                     x-on:livewire-upload-start="uploading = true"
                     x-on:livewire-upload-finish="uploading = false; progress = 0"
                     x-on:livewire-upload-cancel="uploading = false"
                     x-on:livewire-upload-error="uploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress"
                >

                    <div class="d-none">
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" wire:model.live="photo" class="custom-file-input"
                                       id="customFileLang"
                                       lang="es" accept="image/jpeg, image/png">
                                <label class="custom-file-label text-sm" for="customFileLang" data-browse="Elegir">
                                    Seleccionar Imagen</label>
                            </div>
                            <input type="text" wire:model.live="img_borrar_principal">
                        </div>
                    </div>

                    <div class="col-md-6 mt-3 mb-3">
                        <div class="text-center" style="cursor:pointer;">
                            <img class="img-thumbnail" src="{{ asset(verImagen($srcImagen)) }}" alt="Logo Empesa" onclick="imgEmpresa()" {{--width="101" height="100"--}}   />
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

                    <div class="col-10">
                        <!-- Progress Bar -->
                        <div x-show="uploading">
                            {{--<progress max="100" x-bind:value="progress"></progress>--}}
                            <div class="progress rounded">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" x-bind:style="`width: ${progress}%`" x-bind:aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
                                    <span x-text="`${progress}%`"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>

    </div>

    <div class="col-12">
        <div class="col-md-4 float-right">
            <button type="submit" class="btn btn-block @if($empresas_id) btn-primary @else btn-success @endif">
                <i class="fas fa-save"></i>
                Guardar
                @if($empresas_id)
                    Cambios
                @endif
            </button>
        </div>
    </div>


</form>

