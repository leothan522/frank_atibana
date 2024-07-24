<div class="row justify-content-center">
    @if(comprobarPermisos('usuarios.create'))
        <div class="col-md-4 col-lg-3">
            @include('dashboard.usuarios.form')
        </div>
    @endif
    <div class="col-md-8 col-lg-9">
        @include('dashboard.usuarios.table')
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
