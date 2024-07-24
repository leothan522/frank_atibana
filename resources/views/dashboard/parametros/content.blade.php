<div class="row justify-content-center">

    <div class="col-md-4">
        @include('dashboard.parametros.form')
    </div>

    <div class="col-md-8">
        @include('dashboard.parametros.table')
    </div>

</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <label for="">Parametros Manuales</label>
        <ul>
            <li>numRowsPaginate[null|numero]</li>
            <li>size_codigo[tamaño|null]</li>
            <li>proximo_codigo_ajustes[empresa_id|int]</li>
            <li>formato_codigo_ajustes[empresa_id|text]</li>
            <li>editable_codigo_ajustes[empresa_id|1/0]</li>
            <li>editable_fecha_ajustes[empresa_id|1/0]</li>
            <li>proximo_codigo_planificacion[empresa_id|int]</li>
            <li>formato_codigo_planificacion[empresa_id|text]</li>
            <li>proximo_codigo_despachos[empresa_id|int]</li>
            <li>formato_codigo_despachos[empresa_id|text]</li>
            <li>editable_codigo_despachos[empresa_id|1/0]</li>
            <li>editable_fecha_despachos[empresa_id|1/0]</li>
            {{--<li>iva</li>
            <li>telefono_soporte</li>
            <li>codigo_pedido</li>--}}
        </ul>
    </div>
</div>
