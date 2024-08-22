<div class="row justify-content-center" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="col-md-6">

        <div class="card card-outline card-navy">

            <div class="card-body box-profile">
                <h1 class="profile-username text-center text-bold text-uppercase">
                    {{ $nombre }}
                </h1>
                <ul class="list-group {{--list-group-unbordered--}} mt-3">
                    <li class="list-group-item list-group-item-dark">
                        <span>RIF</span> <span class="text-bold float-right text-uppercase">{{ $rif }}</span>
                    </li>
                    <li class="list-group-item list-group-item-dark">
                        <span>Jefe</span> <span class="text-bold float-right text-uppercase">{{ $jefe }}</span>
                    </li>
                    <li class="list-group-item list-group-item-dark">
                        <span>Moneda Base</span> <span class="text-bold float-right text-uppercase">{{ $moneda }}</span>
                    </li>
                    <li class="list-group-item list-group-item-dark">
                        <span>Teléfonos</span> <span class="text-bold float-right text-uppercase">{{ $telefonos }}</span>
                    </li>
                    <li class="list-group-item list-group-item-dark">
                        <span>Email</span> <span class="text-bold float-right text-lowercase">{{ $email }}</span>
                    </li>
                    <li class="list-group-item list-group-item-dark">
                        <span>Dirección</span> <span class="text-bold float-right text-uppercase">{{ $direccion }}</span>
                    </li>
                    @if(auth()->user()->role == 100)
                        <li class="list-group-item list-group-item-dark">
                            <span>empresas_id</span> <span class="text-bold float-right">{{ $empresas_id }}</span>
                        </li>
                    @endif
                    @if($verDefault)
                        <li class="list-group-item list-group-item-dark text-center text-bold">
                            <i class="fas fa-certificate text-xs"></i>
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
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
