<style>
    /* Custom style swal alert */
    .swal2-html-container{
        margin: 10px !important;
        overflow: hidden !important;
    }
    .mayus{
        text-transform: uppercase;
    }
    /* Eliminar decoradores de input type number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }
    input[type=number] {
        -moz-appearance:textfield; 
    }
    .table-title{
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        padding: 6px;
    }
    .table-body-row{
        display: flex;
    }
    .table-item{
        padding: 0px !important;
        width: 15%;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .table-th{
        font-size: 13px;
        font-weight: 700;
        padding: 1px 2px;
        background: #cde4f5;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
    }
    .table-td{
        padding: 1px;
    }
    .b-b-t{
        border-top: 1px solid #dadce0;
        border-bottom: 1px solid #dadce0;
    }
    .b-right{
        border-right: 1px solid #dadce0;
    }
    .cie10-container{
        position: absolute;
        z-index: 5;
        background: #fff;
        outline: none;
        padding: 2px;
        border-radius: 4px;
        top: 41px;
        width: 75%;
        max-height: 350px;
        overflow: auto;
    }
    /* Media Queries */
    @media screen and (max-width: 1150px){
        .table-body-row{
            flex-wrap: wrap
        }
        .table-item{
            width: 25%;
        }
    }
    @media screen and (max-width: 580px){
        .cie10-container{
            width: 95%;
        }
        .table-item{
            width: 50%;
        }
    }
    .t-border{
        border: 1px solid #dee2e6;
    }
</style>
<div class="modal fade" id="modal_reg_consulta" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false"
    tabindex="-1">
    <div class="modal-dialog modal-responsive">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">NUEVA CONSULTA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="register_consulta">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1 card-header p-1 d-flex justify-content-between align-items-center">
                            <h4 class="fs-6 m-0" id="titleSectionColaborador"></h4>
                            <div class="radio icheck-success d-inline p-0" id="component-check-historial-consult">
                                <input type="checkbox" id="checkHisConsult" name="checkHisConsult">
                                <label for="checkHisConsult">Hist. Consultas</label>
                            </div>
                        </div>
                        <div class="card-body pt-2 pb-0 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12" id="component_form_consulta">
                                    <div id="register_colaborador">
                                        <div id="register_colaborador_child">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="input-group">
                                                        <label for="categoria_empleado" class="input-group-title1">Categoria*: </label>
                                                        <select name="categoria_empleado" class="form-select validInputEmpleado border-radius"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Seleccionar categoria de empleado/a">
                                                            <option value="">Selecccionar</option>
                                                            <option value="Administrativo">Administrativo</option>
                                                            <option value="Operativo">Operativo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="codigo_empleado" type="text"
                                                            class="custom-input material cod_colaborador c_valid_emp" 
                                                            placeholder=" " placeholder=" " style="text-transform: uppercase">
                                                        <label class="input-label" for="codigo_empleado">Código empleado</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="nombre_empleado" type="text"
                                                            class="custom-input material c_valid_emp nombre_colaborador"
                                                             placeholder=" " placeholder=" "
                                                            style="text-transform: uppercase">
                                                        <label class="input-label" for="nombre_empleado">Nombre completo</label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="fecha_nac_emp" type="text"
                                                            class="custom-input material c_valid_emp fecha_nac_emp" 
                                                            placeholder=" " placeholder=" ">
                                                        <span class="icon-calendar"><i
                                                                class="bi bi-calendar2-week-fill"></i></span>
                                                        <label class="input-label" for="fecha_nac_emp">Fecha de
                                                            nacimiento</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="input-group">
                                                        <label for="genero_emp" class="input-group-title1">Género*: </label>
                                                        <select name="genero_emp" class="form-select c_valid_emp border-radius"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Seleccionar genero de empleado/a">
                                                            <option value="0">Selecccionar</option>
                                                            <option value="M">Masculino</option>
                                                            <option value="F">Femenino</option>
                                                            <option value="Otro">Otros</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="telefono" type="text"
                                                            class="custom-input material c_valid_emp telefono_colaborador"
                                                             placeholder=" " placeholder=" ">
                                                        <label class="input-label" for="telefono">Teléfono</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="fecha_ing_emp" type="text"
                                                            class="custom-input material c_valid_emp fecha_ing_emp" 
                                                            placeholder=" " placeholder=" ">
                                                        <span class="icon-calendar"><i
                                                                class="bi bi-calendar2-week-fill"></i></span>
                                                        <label class="input-label" for="fecha_ing_emp">Ingreso a empresa</label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-4 mb-2">
                                                    <div class="input-group mb-2">
                                                        <label for="depto_emp" class="input-group-title1">Área/Departamento
                                                        </label>
                                                        <select name="depto_emp" id="area_departamento_emp"
                                                            class="form-select border-radius" data-toggle="tooltip"
                                                            data-placement="bottom" title="Seleccionar genero de empleado/a">
                                                            <option >Selecccionar</option>
                                                            @foreach($areas_depto as $item)
                                                            <option value="{{ $item['id'] }}">{{ $item['departamento'] }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
                                                    <div class="content-input mb-2">
                                                        <input name="cargo_emp" type="text"
                                                            class="custom-input material c_valid_emp"  placeholder=" "
                                                            placeholder=" ">
                                                        <label class="input-label" for="cargo_emp">Cargo de empleado</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 mb-2">
                                                    <div class="input-group">
                                                        <label for="sucursal_emp" class="input-group-title1">Sucursal*: </label>
                                                        <select name="sucursal_emp"
                                                            class="form-select c_valid_emp border-radius" data-toggle="tooltip"
                                                            data-placement="bottom" title="Seleccionar genero de empleado/a">
                                                            <option value="0">Selecccionar</option>
                                                            @foreach($sucursales as $item)
                                                            <option value="{{ $item['id'] }}">{{ $item['nombre'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div id="show_info_colaborador">
                                        <div id="show_info_colaborador_child">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                    <div class="content-input">
                                                        <input readonly name="codigo_empleado" type="text"
                                                            class="custom-input material cod_colaborador input-valid-consult" title="código empleado" placeholder=" ">
                                                        <label class="input-label" for="codigo_empleado">Código empleado</label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-8 col-lg-6">
                                                    <div class="content-input">
                                                        <input readonly name="nombre_empleado" type="text"
                                                            class="custom-input material nombre_colaborador input-valid-consult" 
                                                            placeholder=" " title="nombre colaborador" style="text-transform: uppercase">
                                                        <label class="input-label" for="nombre_empleado">Nombre completo</label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-12 col-lg-3">
                                                    <div class="content-input">
                                                        <input readonly name="telefono" type="text"
                                                            class="custom-input material telefono_colaborador" 
                                                            placeholder=" ">
                                                        <label class="input-label" for="telefono">Teléfono</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="content_consult_historial">
                                        <div id="content_consult_historial_child">
                                            <div class="card-header p-1">
                                                <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> Agregar información la consulta</h4>
                                            </div>
                                            <div class="card-body py-2 px-1">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
                                                        <div class="content-input">
                                                            <input id="fecha_inicio_sintoma" name="fecha_inicio_sintoma" type="text"
                                                                class="custom-input material input-valid-consult" value="" placeholder=" ">
                                                            <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                                            <label class="input-label" for="fecha_inicio_sintoma" title="fecha inicio sintoma o enfermedad">Fecha inicio sintoma/enfermedad</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                                        <div class="card p-0 m-0">
                                                            <div class="card-header p-1">
                                                                Consulta por:
                                                            </div>
                                                            <div class="card-body p-1">
                                                                <div id="motivo-editor" style="height: 100px;">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                                        <div style="border: 1px solid #cde4f5; border-radius: 8px">
                                                            <div class="table-container">
                                                                <div class="table-header">
                                                                    <h4 class="table-title mb-0">SIGNOS VITALES Y MEDIDAS ANTROPOMÉTRICAS</h4>
                                                                </div>
                                                                <div class="table-body">
                                                                    <div class="table-body-row">
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                FC(Ipm)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="signo_vital_fc" class="form-control input-valid-consult reset_valid" type="number">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                FR(rpm)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="signo_vital_fr" class="form-control input-valid-consult reset_valid" type="number">
                                                                            </div>
                                                                        </div>

                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                PA(ps/pd)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="signo_vital_pa" class="form-control input-valid-consult reset_valid" type="search">
                                                                            </div>
                                                                        </div>

                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                Temp(°C)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="medida_temp" class="form-control input-valid-consult reset_valid" type="number" step=".01" min="0" max="45">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                Saturación Oxig(%).
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="signo_vital_saturacion" class="form-control input-valid-consult reset_valid" type="number">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                Peso(Kg)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="medida_peso" class="form-control input-valid-consult" type="number" step=".01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t b-right">
                                                                                Talla(cm)
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input name="medida_talla" class="form-control input-valid-consult" type="number" step=".01">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-item">
                                                                            <div class="table-th b-b-t">
                                                                                IMC
                                                                            </div>
                                                                            <div class="table-td b-right">
                                                                                <input readonly name="medida_imc" class="form-control input-valid-consult reset_valid" type="number">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2" style="position: relative">
                                                        <div class="content-input mb-2">
                                                            <input id="diagnostico_consulta" name="diagnostico_consulta" type="text" 
                                                                class="custom-input material input-valid-consult mayus" title="diagnóstico"
                                                                 placeholder=" ">
                                                            <label class="input-label" for="diagnostico_consulta">Diagnóstico</label>
                                                        </div>
                                                        <div id="cie10-container" class="shadow-lg cie10-container" style="display: none">
                                                            <table width="100%" class="table-hover" style="text-align:center;font-size:12px">
                                                                <tbody id="showItemsCIE10" style="text-transform: capitalize;">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                                        <div class="card p-0 m-0">
                                                            <div class="card-header p-1">
                                                                Nota y observaciones:
                                                            </div>
                                                            <div class="card-body p-1">
                                                                <div class="col-sm-12 col-md-12 col-lg-6">
                                                                    <div class="content-input">
                                                                        <input name="categoria_observaciones" id="categoria_observaciones" type="text" class="custom-input material mayus" title="categoria" placeholder=" ">
                                                                        <span class="icon-input btn-event-cat"><i class="bi bi-plus-lg"></i></span>
                                                                        <label class="input-label" for="categoria_observaciones">Categoria</label>
                                                                    </div>
                                                                </div>
                                                                <div id="nota-observaciones-editor" style="height: 100px;">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-3 col-lg-2 mb-2">
                                                                <div style="border: 1px solid #cde4f5;">
                                                                    <table width="100%" data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                                                        <thead class="bg-dark" style="color:white;min-height:10px;border-radius: 2px;font-size:14px">
                                                                            <tr>
                                                                                <th colspan="100" style="width: 100%;text-align:center;color: #f2f2f2;border-bottom: 1px solid #dadce0;">INCAPACIDAD</th>
                                                                            </tr>                                                                
                                                                        </thead>
                                                                        <tbody style="font-size: 12px;text-align:center">
                                                                            <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 13px;color:#020202">
                                                                                <th colspan="20" style="text-align:center;width: 20%;border-right: 1px solid #dadce0;padding: 6px 0px;">
                                                                                    <div class="radio icheck-turquoise d-inline">
                                                                                        <input type="checkbox" id="checkIncapacidad" name="incapacidad" value="Si">
                                                                                        <label for="checkIncapacidad"></label>
                                                                                    </div>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div id="component_incapacidad">
                                                <div id="component_incapacidad_child">
                                                    <div class="card-header p-1">
                                                        <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> Agregar incapacidad</h4>
                                                    </div>
                                                    <div class="card-body py-2 px-1">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                                                <div class="input-group mb-2">
                                                                    <label for="cat_incapacidad" class="input-group-title1">Categoria </label>
                                                                    <select name="cat_incapacidad" id="cat_incapacidad" class="form-select border-radius input-valid-consult" data-toggle="tooltip" data-placement="bottom"
                                                                        title="categoria incapacidad">
                                                                            <option value="ISSS-CE">ISSS-CE</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                                                <div class="content-input">
                                                                        <input id="rango_fecha_incapacidad" name="rango_fecha_incapacidad" type="text"
                                                                            class="custom-input material input-valid-consult" title="periodo de incapacidad" placeholder=" ">
                                                                        <label class="input-label" for="rango_fecha_incapacidad">Periodo de incapacidad</label>
                                                                    </div>                 
                                                            </div>
                                                              <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                                                <div class="content-input">
                                                                    <input name="dias_incapacitado" id="dias_incapacitado" type="text"
                                                                        class="custom-input material"  placeholder=" " readonly>
                                                                    <label class="input-label" for="Dias">Días incapacitados</label>
                                                                </div>
                                                            </div>   
                                                            <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                                                <div class="input-group">
                                                                    <label for="motivo" class="input-group-title1">Motivo </label>
                                                                    <select name="motivo" id="motivo" class="form-select border-radius input-valid-consult" data-toggle="tooltip" data-placement="bottom"
                                                                    title="motivo de incapacidad">
                                                                    <option value="">Selecccionar</option>
                                                                        @foreach($motivos as $item)
                                                                            <option value="{{ $item['id'] }}">{{ $item['motivo'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label class="input-group-label1 bg-info btnAdd btn-add-motivo"><i class="bi bi-plus-lg" style="font-size: 16px"></i></label>
                                                                </div>
                                                            </div>
                            
                                                            <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                                                <div class="input-group">
                                                                    <label for="riesgo" class="input-group-title1">Riesgo </label>
                                                                    <select name="riesgo" id="riesgo" class="form-select border-radius input-valid-consult" data-toggle="tooltip" data-placement="bottom"
                                                                        title="Riesgo">
                                                                            <option value="">Selecccionar</option>
                                                                            <option value="Enfermedad Comun">ENFERMEDAD COMUN</option>
                                                                            <option value="Enfermedad Profesional">ENFERMEDAD PROFESIONAL</option>
                                                                            <option value="Accidente Comun">ACCIDENTE COMUN</option>
                                                                            <option value="Accidente De Trabajo">ACCIDENTE DE TRABAJO</option>
                                                                            <option value="Maternidad">MATERNIDAD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                            
                                                            <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                                                <div class="input-group">
                                                                    <label for="tipo_incapacidad" class="input-group-title1">Tipo incapacidad </label>
                                                                    <select name="tipo_incapacidad" id="tipo_incapacidad" class="form-select border-radius input-valid-consult" data-toggle="tooltip" data-placement="bottom"
                                                                        title="tipo incapacidad">
                                                                        <option value="">Selecccionar</option>
                                                                        <option value="Inicial">INICIAL</option>
                                                                        <option value="Prorroga">PRORROGA</option>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                                                <div class="content-input">
                                                                        <input id="fecha_expedicion" name="fecha_expedicion" type="text"
                                                                            class="custom-input material input-valid-consult" title="fecha de expedición" placeholder=" ">
                                                                        <label class="input-label" for="fecha_expedicion">Fecha Expedición</label>
                                                                    </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-12 col-lg-12 mb-1">
                                                                <div class="content-input">
                                                                    <input name="diagnostico" type="text" id="diagnostico"
                                                                        class="custom-input material input-valid-consult"  placeholder=" ">
                                                                    <label class="input-label" for="diagnostico">Diagnóstico incapacidad</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 d-none" id="component_historial">
                                    <div class="card-header p-1">
                                        <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> HISTORIAL DE CONSULTAS
                                        </h4>
                                    </div>
                                    <div class="card-body py-2 px-1" style="height: 480px;overflow: auto;">
                                        <div class="col-12" id="lists_consultas">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btn-save-consulta"><i
                                    class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>