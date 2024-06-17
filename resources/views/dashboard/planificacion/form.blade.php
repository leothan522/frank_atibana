<form @if($new_planificacion) wire:submit="save" @else wire:submit="update" @endif xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Código:</label>
        </div>
        <div class="col-md-2 mb-2">
            <input type="text" class="form-control form-control-sm @error('codigo') is-invalid @enderror" placeholder="Código"
                   wire:model="codigo" readonly>
        </div>
        <div class="col-md-3">
            &nbsp; {{--@error('codigo') {{ $message }} @endif--}}
        </div>
        <div class="col-md-2 text-md-right">
            <label>Fecha:</label>
        </div>
        <div class="col-md-3">
            <input type="week" class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                   wire:model="fecha" >
        </div>
    </div>

    <div class="row col-12 mb-2">
        <div class="col-md-2">
            <label>Descripción:</label>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control form-control-sm @error('descripcion') is-invalid @enderror" placeholder="Descripción"
                   wire:model="descripcion">
        </div>
        {{--<div class="col-md-4">
            <input type="number" class="form-control form-control-sm @error('cantidad') is-invalid @enderror" placeholder="Cantidad (KG)"
                   wire:model="cantidad" min="0.001" step=".001" >
        </div>--}}
    </div>

    <div class="col-12">
        <div class="card card-navy card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                           href="#tabs_datos_basicos" role="tab" aria-controls="custom-tabs-three-home"
                           aria-selected="true">Detalles</a>
                    </li>
                    <div class="card-tools p-2">
                        <div class="btn-tool">
                            {{--<button type="button" wire:click="btnContador('add')" class="btn btn-default btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>--}}
                            {{--<button type="button" wire:click="btnContador('remove')" class="btn btn-default btn-sm"
                                    @if($ajuste_contador == 1) disabled @endif>
                                <i class="fas fa-minus"></i>
                            </button>--}}
                        </div>
                    </div>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="tabs_datos_basicos" role="tabpanel"
                         aria-labelledby="custom-tabs-three-home-tab">


                        <div class="row table-responsive p-0">

                            <table class="table">
                                <thead>
                                <tr class="text-navy">
                                    <th style="width: 10%">#</th>
                                    <th>Receta</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--@for($i = 0; $i < $ajuste_contador; $i++)
                                    @include('dashboard.recetas.from_detalles')
                                @endfor--}}
                                </tbody>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                {{--@if($errors->has('codigo'))
                                    <span class="col-sm-12 text-sm text-bold text-danger">
                                        <i class="icon fas fa-exclamation-triangle"></i>
                                        El codigo ya ha sido registrado.
                                        --}}{{--<br>{{ var_export($errors->messages()) }}--}}{{--
                                    </span>
                                @endif--}}
                                @if($errors->has('ajusteArticulo.*') || $errors->has('ajusteUnidad.*') || $errors->has('ajusteCantidad.*'))
                                    <span class="col-sm-12 text-sm text-bold text-danger">
                                        <i class="icon fas fa-exclamation-triangle"></i>
                                        Todos los campos son obigatorios y deben ser validados.
                                        {{--<br>{{ var_export($errors->messages()) }}--}}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row justify-content-between">
                            <div class="col-md-2 mt-3">
                                <button type="button" wire:click="btnContador('add')" class="btn btn-default btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="col-md-4 float-right mt-3">
                                <button type="submit" class="btn btn-block btn-success">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                            </div>
                        </div>

                        {{--<div class="row">
                            Variable: {{ var_export($ajusteCantidad) }}
                        </div>--}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{{--@include('dashboard.recetas.modal_buscar')--}}
