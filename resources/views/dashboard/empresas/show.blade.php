<div class="row justify-content-center" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="col-md-6">

        <div class="card card-outline card-navy">

            <div class="card-body box-profile">
                <h1 class="profile-username text-center text-bold text-uppercase">
                    {{ $nombre }}
                </h1>
                <ul class="list-group list-group-unbordered mt-3">
                    <li class="list-group-item">
                        <b>RIF</b> <a class="float-right text-uppercase">{{ $rif }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Jefe</b> <a class="float-right text-uppercase">{{ $jefe }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Moneda Base</b> <a class="float-right text-uppercase">{{ $moneda }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Teléfonos</b> <a class="float-right text-uppercase">{{ $telefonos }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right text-lowercase">{{ $email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Dirección</b> <a class="float-right text-uppercase">{{ $direccion }}</a>
                    </li>
                    @if(auth()->user()->role == 100)
                        <li class="list-group-item">
                            <b>empresas_id</b> <a class="float-right">{{ $empresas_id }}</a>
                        </li>
                    @endif
                    @if($verDefault)
                        <li class="list-group-item text-muted text-center">
                            <i class="fas fa-certificate text-muted text-xs"></i>
                            Tienda Default
                        </li>
                    @endif
                </ul>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card card-navy card-outline">
            <div class="card-body box-profile">
                <div class="row justify-content-center">

                    <div class="row col-12 attachment-block p-3">


                        <div class="col-12">
                            <label class="col-md-12" for="name">
                                Imagen
                                <span class="badge float-right"><i class="fas fa-image"></i></span>
                            </label>

                        </div>

                        <div class="row col-12 justify-content-center mb-3 mt-3">
                            <div class="col-8">
                                @if($verImagen)
                                    <a href="{{ verImagen($verImagen, false, true) }}" data-toggle="lightbox" data-title="{{ mb_strtoupper($nombre) }}">
                                        <img class="img-thumbnail" src="{{ verImagen($verMini, false, true) }}" {{--width="101" height="100"--}}  alt="Logo Tienda"/>
                                    </a>
                                @else
                                    <img class="img-thumbnail" src="{{ verImagen($verMini, false, true) }}" {{--width="101" height="100"--}}  alt="Logo Tienda"/>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if(estatusTienda($empresas_id))
                            <div class="alert alert-success">
                                <h5><i class="icon fas fa-check"></i> ¡Abierto!</h5>
                                Hora actual: <strong>{{ date('h:i a') }}</strong>. Estatus: <strong> OPEN </strong>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> ¡Cerrado!</h5>
                                Hora actual: <strong>{{ date('h:i a') }}</strong>. Estatus: <strong> CLOSED </strong>
                            </div>
                        @endif
                        {{--@if($verDefault)
                            <ul class="list-group text-sm">
                                <li class="list-group-item bg-warning text-bold">
                                    Tienda Default
                                    <span class="float-right text-bold"><i
                                                class="fas fa-certificate text-muted text-xs"></i></span>
                                </li>
                            </ul>
                        @endif--}}
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
