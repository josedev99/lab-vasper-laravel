<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Iniciar sesión | App</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        .log-div {
            background-color: #fff;
            border-radius: 8px !important;
        }

        .login-card-body {
            border-radius: 8px !important;
        }

        .input-group-text {
            border: 1px solid rgba(180, 179, 179, 0.745);

        }

        .form-control {
            border: 1px solid rgba(180, 179, 179, 0.745);
        }

        .btn-primary {
            background-color: #0320AD;
            border-radius: 10px;
        }

        @media (min-width: 992px) {
            .custom-width {
                max-width: 30%;
                /* Ajusta el porcentaje según tu necesidad */
            }
        }
        .input-group-text{
            border-top-right-radius: 0.375rem !important;
            border-top-left-radius: 0px;
            border-bottom-right-radius: 0.375rem !important;
            border-bottom-left-radius: 0px;
            background: no-repeat;
        }
        .form-control{
            border-right: none !important;
        }
        .form-control:focus{
            box-shadow: none !important;
            border-color: #86b7fe !important;
            border-right: 1px solid #86b7fe;
        }
    </style>
</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 col-xl-4 custom-width">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center py-1" style="height: 100px;">
                                        <img src="assets/img/vasperoficial.jpg" class="img-responsive log" width="180" height="70">
                                    </div><!-- End Logo -->
                                    @if (session('error'))
                                    <div id="error-message" class="alert alert-danger">
                                        {{ session('error') }}
                                        <button id="close-error" type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Usuario" id="usuario"
                                                name="usuario">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="bi bi-person-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Contraseña"
                                                name="pass" id="cambioss" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="bi bi-shield-slash-fill text-success"
                                                        onclick="show_hide_passwordsss()" id="pass2_"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select name="sucursal" id="sucursal" class="form-control">
                                                <option value="" disabled selected>Seleccione sucursal</option>
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="bi bi-building-fill-check"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="enviar" class="form-control" value="si">
                                        </div>
                                        <div class="row">
                                            <div class="d-flex justify-content-center py-1">
                                                <button type="submit" class="btn btn-dark btn-block">INICIAR
                                                    SESIÓN</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
    <script>
        window.axios = axios;
    </script>
    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const usuario = document.getElementById('usuario');
    const passInput = document.getElementById('cambioss'); // Campo de la contraseña
    const sucursalSelect = document.getElementById('sucursal');

    passInput.addEventListener('blur', () => { // Cuando el usuario sale del campo de la contraseña
        let currentUser = usuario.value;
        let password = passInput.value;

        // Verificar que el campo usuario tiene más de 3 caracteres y la contraseña no está vacía
        if (currentUser.length > 3 && password.length > 0) {
            // Realiza una solicitud AJAX para obtener las sucursales del usuario
            axios.get(`/obtener/sucursales/${currentUser}`) // Se usa el valor de 'usuario'
                .then(response => {
                    const sucursales = response.data;

                    // Limpia las opciones actuales del select
                    sucursalSelect.innerHTML = '';

                    // Agrega una opción predeterminada
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.text = 'Seleccione sucursal';
                    sucursalSelect.appendChild(defaultOption);

                    // Agrega las nuevas opciones al select
                    sucursales.forEach(sucursal => {
                        const option = document.createElement('option');
                        option.value = sucursal.id;
                        option.text = sucursal.nombre;
                        sucursalSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al obtener las sucursales:', error);
                });
        }
    });
});


   document.addEventListener('DOMContentLoaded', function() {
       var closeButton = document.getElementById('close-error');
       var errorMessage = document.getElementById('error-message');

       if (closeButton && errorMessage) {
           setTimeout((e) => {
               errorMessage.style.display = 'none';
           }, 3000);
           closeButton.addEventListener(event, function() {
               errorMessage.style.display = 'none';
           });
       }
   });

   /**
    * GET USUARIOS && sucursal
    */

   document.addEventListener('DOMContentLoaded', () => {
       const usuario = document.getElementById('usuario');
       usuario.addEventListener('keyup', (e) => {
           let currentUser = usuario.value;
           if (currentUser.length > 3) {}
       })
   })
   var switchState = true;

   function show_hide_passwordsss() {
       var passwordInput = document.getElementById("cambioss");

       var cambioIcon = document.getElementById("pass2_");

       if (switchState) {
           cambioIcon.classList.add('bi-shield-lock'); // Cambié la clase aquí
           cambioIcon.classList.remove('bi-shield-slash-fill'); // Cambié la clase aquí
           passwordInput.type = "text";
           switchState = false;
       } else {
           cambioIcon.classList.remove('bi-shield-lock'); // Cambié la clase aquí shield-slash-fill
           cambioIcon.classList.add('bi-shield-slash-fill'); // Cambié la clase aquí
           passwordInput.type = "password";
           switchState = true;
       }
   }
    </script>
</body>

</html>