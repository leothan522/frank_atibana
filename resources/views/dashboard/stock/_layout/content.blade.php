@if($empresas_id)
    <div class="row justify-content-around @if($viewMovimientos) d-none @endif">
        @include('dashboard.stock._layout.show')
        @include('dashboard.stock._layout.modal')
    </div>

    <div class="row justify-content-center @if(!$viewMovimientos) d-none @endif">
        @include('dashboard.stock._layout.show_movimientos')
    </div>
    <div class="row">
        <div class="col-12" {{--style="height: 60vh"--}}>
            <div class="overlay-wrapper d-none cargar_stock">
                <div class="overlay">
                    <div class="spinner-border text-navy" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
