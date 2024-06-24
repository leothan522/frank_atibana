@if($empresas_id)
    <div class="row">
        <div class="col-md-5">
            @include('dashboard.despachos.table')
        </div>
        <div class="col-md-7">
            @include('dashboard.despachos.card_view')
        </div>
    </div>
@endif
