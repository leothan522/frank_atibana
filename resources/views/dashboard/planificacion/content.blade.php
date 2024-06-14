@if($empresas_id)
    <div class="row">
        <div class="col-md-5">
            {{--@include('dashboard.recetas.table')--}}
            <p>En desarrollo, activo proximamente!.</p>
        </div>
        <div class="col-md-7">
            @include('dashboard.planificacion.card_view')
        </div>
    </div>
@endif
