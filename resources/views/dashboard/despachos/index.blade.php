@extends('adminlte::page')

@section('title', 'Despachos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-gifts"></i> Despachos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{--<li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                    <li class="breadcrumb-item active">Despachos Realizados</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{--<p>En desarrollo, activo proximamente!.</p>--}}
    @livewire('dashboard.mount-empresas-component')
    <div>
        @livewire('dashboard.despachos-component')
        @livewire('dashboard.almacenes-component')
        @livewire('dashboard.segmentos-despachos-component')
    </div>
@endsection

@section('right-sidebar')
    @include('dashboard.despachos.right-sidebar')
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
            $('.cargar_despachos').removeClass('d-none');
        }

        $(document).ready(function () {
            verSpinnerOculto();
            Livewire.dispatch('updatedEmpresaID');
        });

        function changeEmpresa() {
            $('.cargar_despachos').removeClass('d-none');
        }

        function verAlmacenes() {
            Livewire.dispatch('limpiarAlmacenes');
        }

        function verSegmentos() {
            Livewire.dispatch('limpiarSegmentos');
        }

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
