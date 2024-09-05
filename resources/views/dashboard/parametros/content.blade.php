<div class="row justify-content-center">

    <div class="col-md-4 col-lg-3">
        <label>Parametros Manuales</label>
        <ul>
            <li class="text-wrap">
                numRowsPaginate
                [null|numero]
            </li>
            <li class="text-wrap">
                size_codigo
                [tamaño|null]
            </li>
            <li class="text-wrap">
                proximo_codigo_ajustes
                [empresa_id|int]
            </li>
            <li class="text-wrap">
                formato_codigo_ajustes
                [empresa_id|text]
            </li>
            <li class="text-wrap">
                editable_codigo_ajustes
                [empresa_id|1/0]
            </li>
            <li class="text-wrap">
                editable_fecha_ajustes
                [empresa_id|1/0]
            </li>
            <li class="text-wrap">
                proximo_codigo_planificacion
                [empresa_id|int]
            </li>
            <li class="text-wrap">
                formato_codigo_planificacion
                [empresa_id|text]
            </li>
            <li class="text-wrap">
                proximo_codigo_despachos
                [empresa_id|int]
            </li>
            <li class="text-wrap">
                formato_codigo_despachos
                [empresa_id|text]
            </li>
            <li class="text-wrap">
                editable_codigo_despachos
                [empresa_id|1/0]
            </li>
            <li class="text-wrap">
                editable_fecha_despachos
                [empresa_id|1/0]
            </li>
            {{--<li>iva</li>
            <li>telefono_soporte</li>
            <li>codigo_pedido</li>--}}
        </ul>
    </div>

    <div class="col-md-8 col-lg-9">
        @include('dashboard.parametros.table')
        @include('dashboard.parametros.modal')
    </div>

</div>
