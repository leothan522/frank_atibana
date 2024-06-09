@extends('layouts.adminlte_print')

@section('title', 'Empresa: '. $empresa)

@section('content')


    <div class="card card-navy" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;">

        <div class="card-header">
            <h3 class="card-title">
                Recetas
            </h3>
            <div class="card-tools">
                Empresa: <span class="text-uppercase">{{ $empresa }}</span>
            </div>
        </div>

        <div class="card-body">


            @if($recetas_id)
                <div class="row col-12 mb-2">
                    <div class="col-2">
                        <label>Código:</label>
                    </div>
                    <div class="col-5 mb-2">
                        <span class="border badge-pill text-uppercase">{{ $codigo }}</span>
                    </div>
                    <div class="col-2 text-md-right">
                        <label>Fecha:</label>
                    </div>
                    <div class="col-3">
                        <span class="border badge-pill">{{ verFecha($fecha, 'd/m/Y h:i a') }}</span>
                    </div>
                </div>

                <div class="row col-12 mb-2">
                    <div class="col-2">
                        <label>Descripción:</label>
                    </div>
                    <div class="col-6">
                        <span class="border badge-pill text-uppercase">{{ $descripcion }}</span>
                    </div>
                    @if($cantidad)
                        <div class="col-3 text-md-right">
                            <label>Cantidad (KG):</label>
                        </div>
                        <div class="col-1">
                            <span class="border badge-pill text-uppercase text-nowrap">{{ formatoMillares($cantidad, 3) }}</span>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <div class="card card-navy card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#tabs_datos_basicos" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Detalles</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade active show" id="tabs_datos_basicos" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">


                                    <div class="row table-responsive p-0">
                                        <form>
                                            <table class="table">
                                                <thead>
                                                <tr class="text-navy">
                                                    <th style="width: 5%">#</th>
                                                    <th>Articulo</th>
                                                    <th>Descripción</th>
                                                    <th>Unidad</th>
                                                    <th class="text-right">Cantidad</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php($i = 0)
                                                @if($listarDetalles)
                                                    @foreach($listarDetalles as $detalle)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td class="text-uppercase">{{ $detalle->articulo->codigo }}</td>
                                                            <td class="text-uppercase">{{ $detalle->articulo->descripcion }}</td>
                                                            <td class="text-uppercase">{{ $detalle->unidad->codigo }}</td>
                                                            <td class="text-right">
                                                                {{ formatoMillares($detalle->cantidad, 3) }}
                                                            </td>
                                                        </tr>
                                                        @php($i++)
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            @endif


        </div>

    </div>



@endsection

