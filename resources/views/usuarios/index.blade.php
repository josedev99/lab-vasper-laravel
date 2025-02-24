@extends('layouts.app')

@section('title', 'Usuarios')
@section('section-title')
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Usuarios</li>
    </ol>
    </nav>
@endsection
@section('content')
@include('usuarios.modals.modalCrearUsuario')
@include('usuarios.modals.modalCrearModulo')
@include('usuarios.modals.modalCrearPermiso')
<div class="content-wrapper">
    <div class="card" style="border-top:4px solid #012970">
        <div class="card-body">
            <h5 class="card-title text-center">Modulo de Usuarios </h5>
            <div class="card p-3 shadow-lg">
                <div class="card-body mx-0 px-0 pt-0" style="margin-top: 5px solid red;color: black !important">
                    <button type="button" class="btn btn-outline-primary btn-sm btn_new_empleado" onclick="CrearUsuarios()">Crear Usuario <i class="bi bi-person-add"></i></button>
                    <button class="btn btn-outline-success btn-sm btn_new_modulo"><i class="bi bi-pin-angle"></i> Modulos</button>
                 
                </div>
                <table class="table-responsive table-hover table-bordered table1 table-custom table-td"
                    style="width: 100%" id="usuario" data-order='[[ 0, "desc" ]]'>
                    <thead>
                        <tr>
                            <th style="padding: 0px; text-align: center;">ID</th>
                            <th style="padding: 0px; text-align: center;">Nombre</th>
                            <th style="padding: 0px; text-align: center;">Usuario</th>
                            <th style="padding: 0px; text-align: center;">Estado</th>
                            <th style="padding: 0px; text-align: center;">Cargo</th>
                            <th style="padding: 0px; text-align: center;">Sucursal</th>
                            <th style="padding: 0px; text-align: center;">Acciones</th>
                        </tr>
                    </thead>                
                    <tbody style="text-align: center;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/usuario.js') }}"></script>
@endsection







