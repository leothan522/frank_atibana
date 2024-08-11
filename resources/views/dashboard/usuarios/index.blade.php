@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-users-cog"></i> Usuarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" id="header_div_listar_roles">
                    {{--<li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                    <li class="breadcrumb-item active">
                        <span class="d-none d-md-inline">Usuarios Registrados</span>
                        <span class="d-md-none">Roles [ <span id="header_span_roles_rows">{{ $smListarRoles->count() }}</span> ]</span>
                    </li>
                    @if($smListarRoles->isNotEmpty())
                        @foreach($smListarRoles as $rol)
                            <li class="breadcrumb-item d-md-none" data-toggle="modal" data-target="#modal-roles-usuarios"
                                onclick="showRol({{ $rol->id }})" id="header_button_role_id_{{ $rol->id }}">
                                <span class="btn-link" id="li_text_rol_{{ $rol->id }}">{{ ucfirst($rol->nombre) }}</span>
                            </li>
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @livewire('dashboard.usuarios-component')
    @livewire('dashboard.roles-component')
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

        $("#from_role_usuario").submit(function(e) {
            e.preventDefault();
            let nombre = $('#input_role_nombre').val();
            Livewire.dispatch('save', { nombre: nombre });
        });

        $('#sm_from_role_usuario').submit(function (e) {
            e.preventDefault();
            let nombre = $('#sm_input_role_nombre').val();
            Livewire.dispatch('save', { nombre: nombre });
        });

        Livewire.on('addRoleList', ({ id, nombre, rows }) => {

            $('#input_role_nombre')
                .val('')
                .blur();

            $('#sm_input_role_nombre')
                .val('')
                .blur();

            let boton = '';
            boton += '<button type="button" class="btn btn-primary btn-sm btn-block m-1" data-toggle="modal"';
            boton += 'data-target="#modal-roles-usuarios" onclick="showRol(\'' + id + '\')" id="button_role_id_' + id + '" >';
            boton += nombre;
            boton += '</button>';

            let li = '';
            li += '<li class="breadcrumb-item d-md-none" data-toggle="modal" data-target="#modal-roles-usuarios"';
            li += 'onclick="showRol(\'' + id + '\')" id="header_button_role_id_' + id + '">';
            li += '<span class="btn-link" id="li_text_rol_' + id + '">' + nombre + '</span>';
            li += '</li>';

            $('#div_listar_roles').append(boton);
            $('#header_div_listar_roles').append(li);

            $('#span_roles_rows').text(rows);
            $('#header_span_roles_rows').text(rows);
            Livewire.dispatch('actualizar');
        });

        function showRol(id){
            $('#div_ver_spinner_roles').removeClass('d-none');
            Livewire.dispatch('edit', { id: id } );
        }

        Livewire.on('setRolList', ({ id, nombre }) => {
            $('#button_role_id_' + id).text(nombre);
            $('#li_text_rol_' + id).text(nombre);
            Livewire.dispatch('actualizar');
        });

        Livewire.on('removeRolList', ({ id, rows }) =>{
            Livewire.dispatch('limpiar');
            $('#button_role_id_' + id).addClass('d-none');
            $('#header_button_role_id_' + id).addClass('d-none');
            $('#button_rol_modal_cerrar').click();
            $('#span_roles_rows').text(rows);
            $('#header_span_roles_rows').text(rows);
            Livewire.dispatch('actualizar');
        });

        Livewire.on('cerrarModal', ({ selector }) => {
            $('#' + selector).click();
        });

        $('#button_rol_modal_cerrar').click(function (e) {
            $('#div_ver_spinner_roles').removeClass('d-none');
        });

        $('#button_permisos_modal_cerrar').click(function (e) {
            $('#div_ver_spinner_usuarios').removeClass('d-none');
        });

        function search(){
            let input = $("#navbarSearch");
            let keyword  = input.val();
            if (keyword.length > 0){
                input.blur();
                //alert('Falta vincular con el componente Livewire');
                Livewire.dispatch('buscar', { keyword:keyword });
            }
            return false;
        }

        console.log('Hi!');
    </script>
@endsection
