<div class="col-12">
    <!-- Widget: user widget style 2 -->
    <div class="card card-widget widget-user-2">
        <div class="card-footer p-0">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <span class="nav-link">
                        Cedula <span class="float-right text-bold text-uppercase">{{ is_numeric($cedula) ? formatoMillares($cedula,0) : $cedula }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Nombre <span class="float-right text-bold text-uppercase">{{ $nombre }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Apellido <span class="float-right text-bold text-uppercase">{{ $apellido }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Teléfono <span class="float-right text-bold">{{ $telefono }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Email <span class="float-right text-bold text-lowercase">{{ $email }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Dirección <span class="float-right text-bold text-lowercase">{{ $direccion }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Instalación <span class="float-right text-bold">{{ verFecha($instalacion) }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Fecha Pago <span class="float-right text-bold">{{ verFecha($pago) }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Latitud <span class="float-right text-bold">{{ $latitud }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        Longitud <span class="float-right text-bold">{{ $longitud }}</span>
                    </span>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        GPS <span class="float-right text-bold">{{ $gps }}</span>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /.widget-user -->
</div>
