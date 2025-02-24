@extends('layouts.app')

@section('title', 'Perfiles')
@section('section-title')
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
    <li class="breadcrumb-item active">Examenes y perfiles</li>
  </ol>
</nav>
@endsection
@section('content')
@include('perfilesExamenes.registrar_examen')
@include('perfilesExamenes.registrar_categoria')
@include('perfilesExamenes.registrar_exspeciales')
@include('perfilesExamenes.registrar_exComplementarios')
@include('perfilesExamenes.registrar_perfil')
@include('perfilesExamenes.editar_perfil')

<div class="content-wrapper">
  <div class="card" style="border-top:4px solid #012970">
    <div class="card-body">
      <h5 class="card-title text-center">EXÁMENES</h5>
      <div class="d-flex justify-content-between">
        <!-- Primera sección: LABORATORIO CLÍNICO (NO SE MODIFICA) -->
        <div class="card p-3 shadow-lg" style="flex: 1; margin-right: 10px;">
          <h6 class="text-center">LABORATORIO CLÍNICO</h6>
          <div class="card-body">
            <!-- Bordered Tabs Justified -->
            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home"
                  aria-selected="true">Examenes</button>
              </li>
              <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                  data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile"
                  aria-selected="false">Perfiles</button>
              </li>
            </ul>
            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
              <!-- Pestaña de Exámenes -->
              <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel"
                aria-labelledby="home-tab">
                <div class="card p-1 mb-0">
                  <div class="card-header p-1" style="background: #f6f9ff">
                    <button type="button" title="Registrar nuevo examen"
                      class="btn btn-outline-success btn-sm btnAdExamen" style="display: block;">
                      <i class="bi bi-plus-lg"></i> Examen
                    </button>
                    <div class="row justify-content-end">
                      <div class="col-sm-8">
                        <input type="search" class="input-search" id="input-search" name="search_examen"
                          placeholder="Buscar examen" title="Buscar examen">
                        <button class="icon-input-search" type="submit" title="Search"><i
                            class="bi bi-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="rows_examenes_ordenP"></div>
                </div>
              </div>

              <!-- Pestaña de Perfiles -->
              <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                <button type="button" title="Registrar nuevo examen"
                  class="btn btn-outline-success btn-sm btn_new_perfil" style="display: block;">
                  <i class="bi bi-plus-lg"></i> NUEVO PERFIL
                </button>

                <div class="card p-3">
                  <div class="row justify-content-end mb-2">
                    <div class="col-sm-8">
                      <input type="search" class="input-search" id="input-search-perfil" name="search_perfil"
                        placeholder="Buscar perfil" title="Buscar perfil">
                      <button class="icon-input-search" type="submit" title="Search"><i
                          class="bi bi-search"></i></button>
                    </div>
                  </div>
                  <div class="row" id="rows_examenes_ordenP2">
                    <!-- Aquí se generarán dinámicamente los acordeones de perfiles y sus exámenes -->
                  </div>
                </div>
              </div>
            </div>


          </div>

        </div>
        <!-- Segunda sección: PRUEBAS ESPECIALES -->
        <div class="card p-3 shadow-lg" style="flex: 1; margin-right: 10px;">
          <h6 class="text-center">PRUEBAS ESPECIALES</h6>

          <div class="card p-1 mb-0">
            <div class="card-header p-1" style="background: #f6f9ff">
              <button type="button" title="Registrar nuevo examen" class="btn btn-outline-success btn-sm btnAdExamenEsp"
                style="float: right; display: block;">
                <i class="bi bi-plus-lg"></i> Examen
              </button>
            </div>
            <div class="p-1">
              <table id="examenes_esp" width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                  <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                    <th style="text-align:center">Id</th>
                    <th style="text-align:center">Nombre</th>
                    <th style="text-align:center">*</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Tercera sección: PRUEBAS COMPLEMENTARIAS -->
        <div class="card p-3 shadow-lg" style="flex: 1;">
          <h6 class="text-center">PRUEBAS COMPLEMENTARIAS</h6>
          <div class="card p-1 mb-0">
            <div class="card-header p-1" style="background: #f6f9ff">
              <button type="button" title="Registrar nuevo examen"
                class="btn btn-outline-success btn-sm btnAdExamenComp" style="float: right; display: block;">
                <i class="bi bi-plus-lg"></i> Examen
              </button>
            </div>
            <div class="p-1">
              <table id="examenes_comple" width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                  <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                    <th style="text-align:center">Id</th>
                    <th style="text-align:center">Nombre</th>
                    <th style="text-align:center">*</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider" style="font-size: 12px;text-align:center">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/perfilesEx/perfiles.js') }}"></script>
@endsection