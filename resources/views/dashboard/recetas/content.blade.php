@if($empresas_id)
    <div class="row">
        <div class="col-md-5">
            @include('dashboard.recetas.table')
        </div>
        <div class="col-md-7">
            @include('dashboard.recetas.card_view')
        </div>
    </div>
@endif
