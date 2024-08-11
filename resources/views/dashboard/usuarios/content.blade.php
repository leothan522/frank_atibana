<div class="row justify-content-center">
    <div class="col-12">
        @include('dashboard.usuarios.table')
        @include('dashboard.usuarios.modal_create')
        @include('dashboard.usuarios.modal_edit')
        @include('dashboard.usuarios.modal_permisos')
    </div>
</div>

@section('right-sidebar')
    @if(comprobarPermisos())
        @include('dashboard.usuarios.right-sidebar')
    @else
        @include('dashboard.right-sidebar')
    @endif
@endsection
