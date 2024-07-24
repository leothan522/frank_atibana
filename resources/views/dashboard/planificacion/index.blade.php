@extends('adminlte::page')

@section('title', 'Planificación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-tasks"></i> Planificación</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{--<li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                    <li class="breadcrumb-item active">Planificación Semanal</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{--<p>Welcome to this beautiful admin panel.</p>--}}
    @livewire('dashboard.mount-empresas-component')
    <div>
        @livewire('dashboard.planificacion-component')
    </div>
@endsection

@section('right-sidebar')
    @include('dashboard.right-sidebar')
@endsection

@section('footer')
    @include('dashboard.footer')
@endsection

@section('css')
    {{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')
    <script src="{{ asset("js/app.js") }}"></script>
    <script>

        function verSpinnerOculto() {
            $('.cargar_planificacion').removeClass('d-none');
        }

        $(document).ready(function () {
            verSpinnerOculto();
            Livewire.dispatch('updatedEmpresaID');
        });

        Livewire.on('cerrarModalExistencias', () => {
            $('#btn_modal_existencias_planificacion').click();
        });

        function buscar(){
            verSpinnerOculto();
            let input = $("#navbarSearch");
            let keyword  = input.val();
            if (keyword.length > 0){
                input.blur();
                //alert('Falta vincular con el componente Livewire');
                Livewire.dispatch('buscar', { keyword: keyword });
            }
            return false;
        }

        console.log('Hi!');
    </script>
@endsection
