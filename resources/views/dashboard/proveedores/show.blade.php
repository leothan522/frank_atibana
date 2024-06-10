<div class="row col-md-12" xmlns:wire="http://www.w3.org/1999/xhtml">

    <div class="col-md-6">

        <div class="card card-outline card-navy">

            <div class="card-body box-profile">
                <h1 class="profile-username text-center text-bold">
                    {{ mb_strtoupper($nombre) }}
                </h1>
                <ul class="list-group list-group-unbordered mt-3">
                    <li class="list-group-item">
                        <b>RIF</b> <a class="float-right text-uppercase">{{ $rif }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Telefonos</b> <a class="float-right text-uppercase">{{ $telefono }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Dirección</b> <a class="float-right">{{ mb_strtoupper($direccion) }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Banco</b> <a class="float-right text-uppercase">{{ $banco }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Nro. Cuenta</b> <a class="float-right text-uppercase">{{ $cuenta }}</a>
                    </li>

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
                                <img class="img-thumbnail" src="{{ verImagen($verImagen, false, true) }}"
                                     {{--width="101" height="100"--}}  alt="Proveedor"/>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
