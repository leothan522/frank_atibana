@if($empresas_id)
    <div class="row">
        <div class="col-md-5">
            @include('dashboard.planificacion.table')
        </div>
        <div class="col-md-7">
            @include('dashboard.planificacion.card_view')
            @include('dashboard.planificacion.modal_exitencias')
        </div>
    </div>
@endif
