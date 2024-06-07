@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title', 'Stock')

@section('content_header')

    <div class="row mb-2">
        <div class="col-sm-4">
            <h1 class="m-0 text-dark"><i class="fas fa-boxes"></i> Stock</h1>
        </div>
        <div class="col-sm-8">
            <button type="button" class="btn btn-default btn-sm float-right ml-1 mr-1"
                    onclick="showRow('ajustes')" id="button_ajustes"
                {{--wire:click="verAjustes" @if($view == "ajustes" || !comprobarPermisos('ajustes.index')) disabled @endif--}}>
                <i class="fas fa-list"></i> Ajustes de Entrada y Salida
            </button>
            <button type="button" class="btn btn-default btn-sm float-right ml-1 mr-1"
                    onclick="showRow('stock')" id="button_stock" disabled
                {{-- wire:click="verAjustes" @if($view == "stock") disabled @endif--}}>
                <i class="fas fa-boxes"></i> Inventario
            </button>
            <button type="button" class="btn btn-default btn-sm float-right ml-1 mr-1"
                    onclick="actualizar()" >
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
    </div>
@stop

@section('content')
    {{--<p>Welcome to this beautiful admin panel.</p>--}}
    @livewire('dashboard.mount-empresas-component')
    <div id="row_button_stock">
        @livewire('dashboard.stock-component')
    </div>
    <div class="d-none" id="row_button_ajustes">
        @livewire('dashboard.ajustes-component')
    </div>
    <div class="row">
        @livewire('dashboard.tipos-ajustes-component')
        @livewire('dashboard.almacenes-component')
        @livewire('dashboard.segmentos-component')
        @livewire('dashboard.reportes-component')
    </div>

@stop

@section('right-sidebar')
    @include('dashboard.stock.right-sidebar')
@endsection

@section('footer')
    @include('dashboard.footer')
@stop

@section('css')
    {{--<link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')
    <script src="{{ asset("js/app.js") }}"></script>
    <script>

        function verSpinnerOculto() {
            $('.cargar_stock').removeClass('d-none');
        }

        $(document).ready(function () {
            //verSpinnerOculto();
            Livewire.dispatch('updatedEmpresaID');
        });

        function showRow(row) {
            let ajustes = $('#row_button_ajustes');
            let stock = $('#row_button_stock');

            $('#button_ajustes').prop("disabled",false);
            $('#button_stock').prop("disabled",false);

            if (row === 'ajustes'){
                ajustes.removeClass('d-none');
                stock.addClass('d-none');
            }else {
                ajustes.addClass('d-none');
                stock.removeClass('d-none');
            }

            $('#button_' + row).prop("disabled",true);

        }

        function actualizar() {
            //verSpinnerOculto();
            $('.cargar_ajustes').removeClass('d-none');
            Livewire.dispatch('showStock');
            Livewire.dispatch('showAjustes');
        }

        function cerrarInventarios() {
            //verSpinnerOculto();
            $('.cerra_inventarios').click();
        }

        function irAjuste() {
            $('.cargar_ajustes').removeClass('d-none');
            $('#button_ajustes').click();
        }

        function changeEmpresa() {
            $('.cargar_ajustes').removeClass('d-none');
        }

        function verTiposAjuste() {
            Livewire.dispatch('limpiarTiposAjuste');
        }

        function verAlmacenes() {
            Livewire.dispatch('limpiarAlmacenes');
        }

        function verSegmentos() {
            Livewire.dispatch('limpiarSegmentos');
        }

        function select_2(id, data)
        {
            let html = '<div class="input-group-prepend">' +
                '<span class="input-group-text">' +
                '<i class="fas fa-code"></i>' +
                '</span>' +
                '</div> ' +
                '<select id="'+ id +'"></select>';
            $('#div_' + id).html(html);

            $('#'  + id).select2({
                dropdownParent: $('#modal-cuotas'),
                theme: 'bootstrap4',
                data: data,
                placeholder: 'Seleccione',
                /*allowClear: true*/
            });
            $('#'  + id).val(null).trigger('change');
            $('#'  + id).on('change', function() {
                var val = $(this).val();
                Livewire.dispatch('cuotaSeleccionada', { codigo: val });
            });
        }

        //pendiente revisar
        $('#reportes_articulos').select2({
            theme: 'bootstrap4',
        });


        function buscar(){
            let input = $("#navbarSearch");
            let keyword  = input.val();
            if (keyword.length > 0){
                input.blur();
                //alert('Falta vincular con el componente Livewire');
                irAjuste();
                Livewire.dispatch('buscar', { keyword: keyword });
                $('#nabvar_cerrar_buscar').click();
            }
            return false;
        }

        console.log('Hi!');
    </script>
@stop
