<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Clinica | Registrar cita</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  @include('partials.links_css')

  <style>
    .custom-input{
      margin-bottom: 0px !important;
    }
  </style>

</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="#" class="logo d-flex align-items-center w-auto">
                  {{-- <img src="assets/img/logo.png" alt=""> --}}
                  <span class="d-none d-lg-block">App clinica empresarial</span>
                </a>
              </div><!-- End Logo -->
              @if(session()->has('error'))
              <div class="col-sm-12 alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif
              <div class="card mb-3 p-0">
                <div class="card-body p-2">
                  @if(session()->has('success'))
                  
                  <div class="row">
                    <div class="col-sm-12 d-flex justify-content-center">
                      <i class="bi bi-clipboard2-check" style="font-size: 60px;color: #198754;"></i>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-center">
                      <span class="badge bg-info" style="font-size: 14.5px"> {{ session('success') }}</span>
                    </div>
                    <div class="col-sm-12 d-flex flex-column align-items-center my-2">
                      <div class="card mb-0 col-sm-12 col-md-8" id="download_info_cita">
                        <div class="card-header p-1 text-center">Información de la cita</div>
                        <div class="card-body p-2 d-flex justify-content-center align-items-center" style="font-size: 13px;">
                            <div class="" style="text-align:left">
                              <p class="m-0"><b><i class="bi bi-bookmark-fill"></i> CODIGO EMPLEADO: </b>{{ session('data')['codigo_empleado'] }}</p>
                              <p class="m-0"><b><i class="bi bi-person-circle"></i> NOMBRE: </b>{{ session('data')['nombre'] }}</p>
                              <p class="m-0"><b><i class="bi bi-telephone-fill"></i> TELEFONO: </b>{{ session('data')['telefono'] }}</p>
                              <p class="m-0"><b><i class="bi bi-calendar-check"></i> FECHA DE LA CITA: </b>{{ session('data')['fecha_cita'] }}</p>
                              <p class="m-0"><b><i class="bi bi-stopwatch"></i> HORA DE LA CITA: </b>{{ session('data')['hora_cita'] }}</p>
                            </div>
                        </div>
                        <div class="card-footer p-1" style="font-size: 11px;text-align:center">
                          <p class="m-0"><b>IMPORTANTE: </b>POR FAVOR, PRESENTARSE 15 MINUTOS ANTES DE LA CITA.</p>
                        </div>
                      </div>
                      <div class="card-footer d-flex justify-content-end p-1">
                        <button class="btn btn-outline-success btn-sm" id="btn_download_img">Descargar <i class="bi bi-arrow-down-square"></i></button>
                      </div>
                    </div>
                  </div>

                  @else
                  <div class="pb-2">
                    <h5 class="card-title text-center pb-0 fs-4 p-0">Registrar cita</h5>
                    <p class="text-center small mb-3"> Ingresa tu información para generar la cita.</p>
                  </div>

                  <form class="row needs-validation" id="form_data_cita" action="{{ route('cita.public.save') }}" method="POST">
                    @csrf

                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-12 mb-2">
                      <div class="input-group">
                        <label for="sucursal_id" title="sucursal" class="input-group-title1">Sucursal </label>
                        <select name="sucursal_id" id="sucursal_id"
                          class="form-select border-radius @error('sucursal_id') {{ 'border-valid' }} @enderror"
                          data-toggle="tooltip" data-placement="bottom" title="Seleccionar genero de empleado/a">
                          @if(count($sucursales) == 1)
                          @foreach($sucursales as $item)
                          <option value="{{ $item->id }}" selected>{{ $item->nombre }}</option>
                          @endforeach
                          @else
                          <option value="">Selecccionar</option>
                          @foreach($sucursales as $item)
                          <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                          @endforeach
                          @endif

                        </select>
                        @error('sucursal_id')
                        <div class="invalid-feedback" style="display: block">
                          La sucursal es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">
                      <div class="content-input">
                        <input name="codigo_empleado" type="text"
                          class="custom-input material @error('codigo_empleado') {{ 'border-valid' }} @enderror"
                          value="{{ old('codigo_empleado') }}" placeholder=" " autocomplete="false">
                        <label class="input-label" for="codigo_empleado" title="código empleado">Código empleado
                        </label>
                        @error('codigo_empleado')
                        <div class="invalid-feedback" style="display: block">
                          El código del empleado es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8 mb-2">
                      <div class="content-input">
                        <input name="nombre_empleado" type="text"
                          class="custom-input material @error('nombre_empleado') {{ 'border-valid' }} @enderror"
                          value="{{ old('nombre_empleado') }}" placeholder=" " style="text-transform: uppercase"
                          autocomplete="false">
                        <label class="input-label" for="nombre_empleado" title="nombre completo">Nombre completo
                        </label>
                        @error('nombre_empleado')
                        <div class="invalid-feedback" style="display: block">
                          El nombre es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4 mb-2">
                      <div class="content-input">
                        <input name="telefono_emp" id="telefono_emp" type="text"
                          class="custom-input material @error('telefono_emp') {{ 'border-valid' }} @enderror" value="{{ old('telefono_emp') }}"
                          placeholder=" " style="text-transform: uppercase" autocomplete="false">
                        <label class="input-label" for="telefono_emp" title="teléfono">Teléfono </label>
                        @error('telefono_emp')
                        <div class="invalid-feedback" style="display: block">
                          El teléfono es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4 mb-2">
                      <div class="content-input">
                        <input id="fecha_cita" name="fecha_cita" type="text"
                          class="custom-input material @error('fecha_cita') {{ 'border-valid' }} @enderror" value="{{ old('fecha_cita') }}"
                          placeholder=" ">
                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                        <label class="input-label" for="fecha_cita" title="fecha cita">Fecha cita </label>
                        @error('fecha_cita')
                        <div class="invalid-feedback" style="display: block">
                          La fecha de la cita es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-2">
                      <div class="input-group">
                        <label for="hora_cita" title="hora" class="input-group-title1">horarios disponibles </label>
                        <select name="hora_cita" id="hora_cita" class="form-select border-radius" data-toggle="tooltip"
                          data-placement="bottom" title="Seleccionar hora de cita">
                          <option value="">Selecccionar</option>
                        </select>
                        @error('hora_cita')
                        <div class="invalid-feedback" style="display: block">
                          La hora de la cita es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-12 mb-2">
                      <div class="content-input mb-2">
                        <textarea id="motivo" rows="3" name="motivo" type="text"
                          class="form-control material @error('motivo') {{ 'border-valid' }} @enderror" value="{{ old('motivo') }}"
                          placeholder=" " style="text-transform: uppercase"></textarea>
                        <label class="input-label-textarea" for="motivo" title="motivo">Motivo</label>
                        @error('motivo')
                        <div class="invalid-feedback" style="display: block">
                          El motivo de la cita es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                      <div class="content-input">
                        <input id="fecha_inicio_sintoma" name="fecha_inicio_sintoma" type="text"
                          class="custom-input material @error('fecha_inicio_sintoma') {{ 'border-valid' }} @enderror" value="{{ old('fecha_inicio_sintoma') }}"
                          placeholder=" ">
                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                        <label class="input-label" for="fecha_inicio_sintoma" title="fecha de inicio de sintoma o enfermedad">Inicio de sintoma/enfermedad </label>
                        @error('fecha_inicio_sintoma')
                        <div class="invalid-feedback" style="display: block">
                          La fecha de inicio de sintoma o enfermedad es obligatorio.
                        </div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Guardar</button>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">Powered by <a href="#">avsystem</a>
              </div>
              @endif
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->
  <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/selectize/selectize.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/flatpicker/flatpickr.js') }}"></script>
  <script src="{{ asset('assets/vendor/flatpicker/es.js') }}"></script>
  <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/html2canvas/html2canvas.min.js') }}"></script>
  @routes
  <script>
    window.axios = axios;
  </script>
  <script src="{{ asset('assets/js/helpers/validation.js') }}"></script>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "center",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
      }
    });
  </script>
  <script src="{{ asset('assets/js/cita/cita.js') }}"></script>
</body>

</html>