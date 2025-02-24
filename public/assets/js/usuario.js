//CREACION DE USUARIOS
document.addEventListener('DOMContentLoaded', function() {
    let urlDataTableUsuario = window.location.origin + "/Usuarios/show";
    dataTable("usuario", urlDataTableUsuario);
    let table11 = new DataTable('#usuario');
    table11.on('draw.dt', attachEvent);
    let tablemod = new DataTable('#modulos');
    tablemod.on('draw.dt', attachModulos);
})

function CrearUsuarios() {
    $("#CrearUsuarios").modal("show");
    document.getElementById('CrearUsuario').reset();
    document.querySelectorAll('.custom-input, .form-select').forEach(input => {
        input.classList.remove('error');
        let btnRegistrar = document.querySelector('#btnActualizar'); 
        let  btnUpdate = document.querySelector('#btnRegisOD'); 
        if (btnUpdate) {
                           btnUpdate.classList.remove('d-none');
                       }
        if (btnRegistrar) {
                           btnRegistrar.classList.add('d-none'); 
                       }                
    });
}

document.getElementById('btnRegisOD').addEventListener('click', function (event) {
    event.preventDefault();
    const nombreUsuario = document.getElementById('nombreUsuario').value.trim();
    const cargoUsuario = document.getElementById('cargoUsuario').value;
    const estadoUsuario = document.getElementById('estadoUsuario').value;
    const Usuario = document.getElementById('Usuario').value.trim();
    const Contraseña = document.getElementById('Contraseña').value.trim();
   document.querySelectorAll('.custom-input, .form-select').forEach(input => {
    input.classList.remove('error');
});
let hasErrors = false;

if (!nombreUsuario) {
    document.getElementById('nombreUsuario').classList.add('error');
    hasErrors = true;
}
if (!cargoUsuario) {
    document.getElementById('cargoUsuario').classList.add('error');
    hasErrors = true;
}
if (!estadoUsuario) {
    document.getElementById('estadoUsuario').classList.add('error');
    hasErrors = true;
}
if (!Usuario) {
    document.getElementById('Usuario').classList.add('error');
    hasErrors = true;
}
if (!Contraseña) {
    document.getElementById('Contraseña').classList.add('error');
    hasErrors = true;
}
if (hasErrors) {
    Swal.fire({
        icon: 'error',
        title: 'Faltan campos',
        text: 'Favor llenar todos los campos obligatorios.',
    });
    return;
}
    const token = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(document.getElementById('CrearUsuario'));

      const permisosSeleccionados = {};
      document.querySelectorAll('input[name="permisos[]"]:checked').forEach((checkbox) => {
        const permisoId = checkbox.value;
        const moduloId = checkbox.closest('.accordion-item').querySelector('.form-check-label').getAttribute('for').replace('moduloCheck', '');
        if (!permisosSeleccionados[moduloId]) {
            permisosSeleccionados[moduloId] = {
                modulo: moduloId,
                permisos: []
            };
        }
        permisosSeleccionados[moduloId].permisos.push(permisoId);
    });
     formData.append('permisos_modulos', JSON.stringify(permisosSeleccionados));

    axios.post('/usuarios/crear', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': token 
        }
    })
    .then(function (response) {
        Swal.fire({
            icon: 'success',
            title: 'Usuario registrado',
            text: 'Usuario registrado con éxito',
        })
        $("#CrearUsuarios").modal("hide");
          let urlDataTableUsuario = window.location.origin + "/Usuarios/show";
          dataTable("usuario", urlDataTableUsuario);
    })
    .catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al registrar el usuario',
        });
    });
});

function attachEvent() {
    let btnEdicions = document.querySelectorAll('.btn-o-user');
    let btnPassword = document.querySelectorAll('.btn-o-pass')
    if (btnEdicions) {
        btnEdicions.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empleado_id = btn.dataset.emp_ref;
                let btnUpdate = document.querySelector('#btnActualizar');
                let btnRegistrar = document.querySelector('#btnRegisOD');
                
                if (btnRegistrar) {
                    btnRegistrar.classList.add('d-none');
                }
                if (btnUpdate) {
                    btnUpdate.classList.remove('d-none');
                }
                
                let response = await axios.post(route('app.Usuarios.getUsuario'), {
                    ref_emp: empleado_id
                }, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                });
                
                let data = response.data;
                console.log(data);

                // Asignar valores a los campos del formulario
                document.querySelector('#nombreUsuario').value = data.nombre || '';
                document.querySelector('#telefonoUsuario').value = data.telefono || '';
                document.querySelector('#CorreoUsuario').value = data.correo || ''; 
                document.querySelector('#duiUsuario').value = data.dui || '';
                document.querySelector('#nitUsuario').value = data.nit || '';
                document.querySelector('#direccionUsuario').value = data.direccion || '';
                document.querySelector('#SucursalUsuario').value = data.sucursal_id || '';
                document.querySelector('#cargoUsuario').value = data.cargo || '';
                document.querySelector('#estadoUsuario').value = data.estado || '';
                document.querySelector('#Usuario').value = data.usuario || '';

                // Resetear todos los checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                // Marcar los permisos correspondientes
                if (data.modulos_permisos && data.modulos_permisos.length > 0) {
                    data.modulos_permisos.forEach(modulo => {
                        modulo.permisos.forEach(permiso_id => {
                            let permisoCheckbox = document.querySelector(`#permiso${permiso_id}`);
                            if (permisoCheckbox) {
                                permisoCheckbox.checked = true;
                            }
                        });
                    });
                }
                $("#CrearUsuarios").modal("show");
            });
        });
    }
        if (btnPassword){
            btnPassword.forEach(btn => {
            btn.addEventListener('click', async () => {
                let empleado_id = btn.dataset.emp_ref;
                ConfigPassword(empleado_id)
                //FUNCION PARA CAMBIAR LA CONTRASEÑA
                    function ConfigPassword(id_user){
                        async function showPasswordPrompt() {
                            const { value: password, dismiss: dismissReason } = await Swal.fire({
                            title: "Restablecer contraseña",
                            html: `
                                <div class="input-group mb-3">
                                <span class="input-group-text"> 
                                    <i class="text-success ri-eye-off-line" id="toggle-password" style="font-size: 25px; cursor: pointer; margin-left: 5px;"></i>
                                </span>
                                <input type="password" id="swal-input-password" class="form-control" maxlength="10" autocapitalize="off" autocorrect="off" required>
                                </div>
                                <div id="password-error" class="text-danger" style="display: none;">La contraseña No puede estar vacia</div>`,
                            showCancelButton: true,
                            confirmButtonText: 'Confirmar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const passwordInput = document.getElementById('swal-input-password');
                                const errorElement = document.getElementById('password-error');
                                if (!passwordInput.value) {
                                errorElement.style.display = 'block';
                                return false; // Esto evita que el modal se cierre
                                }
                                errorElement.style.display = 'none';
                                return passwordInput.value;
                            },
                            didOpen: () => {
                                const togglePassword = document.getElementById('toggle-password');
                                const passwordInput = document.getElementById('swal-input-password');
                                
                                togglePassword.addEventListener('click', () => {
                                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                passwordInput.setAttribute('type', type);
                                togglePassword.className = type === 'password' ? 'text-success ri-eye-off-line' : 'text-success ri-eye-line';
                                });
                            }
                            });
                        
                            if (password && dismissReason !== Swal.DismissReason.cancel) {
                                axios.post('/CambiarPassword', {
                                    id_user: id_user,
                                    password: password
                                })
                                .then(response => {
                                    console.log(response.data);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'Contraseña cambiada exitosamente!'
                                    });  
                                })
                                .catch(error => {
                                    console.error(error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'No se pudo cambiar la contraseña. Intente nuevamente!'
                                    });  
                                });
                            }
                        }
                        // Llamar a la función
                        showPasswordPrompt();
                    }
            })
        })
    }   
}


document.getElementById('btnActualizar').addEventListener('click', function (event) {
    event.preventDefault();
    const nombreUsuario = document.getElementById('nombreUsuario').value.trim();
    const cargoUsuario = document.getElementById('cargoUsuario').value;
    const estadoUsuario = document.getElementById('estadoUsuario').value;
    const Usuario = document.getElementById('Usuario').value.trim();
   document.querySelectorAll('.custom-input, .form-select').forEach(input => {
    input.classList.remove('error');
});
let hasErrors = false;

if (!nombreUsuario) {
    document.getElementById('nombreUsuario').classList.add('error');
    hasErrors = true;
}
if (!cargoUsuario) {
    document.getElementById('cargoUsuario').classList.add('error');
    hasErrors = true;
}
if (!estadoUsuario) {
    document.getElementById('estadoUsuario').classList.add('error');
    hasErrors = true;
}
if (!Usuario) {
    document.getElementById('Usuario').classList.add('error');
    hasErrors = true;
}
if (hasErrors) {
    Swal.fire({
        icon: 'error',
        title: 'Faltan campos',
        text: 'Favor llenar todos los campos obligatorios.',
    });
    return;
}

    const token = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(document.getElementById('CrearUsuario'));

    formData.append('actualizar', 'true');
    const permisosSeleccionados = {};
    document.querySelectorAll('input[name="permisos[]"]:checked').forEach((checkbox) => {
        const permisoId = checkbox.value;
        const moduloId = checkbox.closest('.accordion-item').querySelector('.form-check-label').getAttribute('for').replace('moduloCheck', '');
        if (!permisosSeleccionados[moduloId]) {
            permisosSeleccionados[moduloId] = {
                modulo: moduloId,
                permisos: []
            };
        }
        permisosSeleccionados[moduloId].permisos.push(permisoId);
    });
    formData.append('permisos_modulos', JSON.stringify(permisosSeleccionados));
    axios.post('/usuarios/crear', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': token,
        }
    })
    .then(function (response) {
        Swal.fire({
            icon: 'success',
            title: 'Usuario Actualizado',
            text: 'Usuario actualizado con éxito',
        })
        $("#CrearUsuarios").modal("hide");
          let urlDataTableUsuario = window.location.origin + "/Usuarios/show";
          dataTable("usuario", urlDataTableUsuario);
    })
    .catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al Actualizar el usuario',
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {

    try {
        let btn_new_empleado = document.querySelector('.btn_new_modulo');
        if (btn_new_empleado) {
            btn_new_empleado.addEventListener('click', (e) => {
                e.stopPropagation();
                $("#modal_new_modulo").modal('show');
                let urlDataTableUsuario = window.location.origin + "/modulos/show";
                dataTable("modulos", urlDataTableUsuario);
            })
        }
    } catch (err) {
        if (err.status === 500) {
            Swal.fire({
                title: "Error",
                text: 'Ha ocurrido un error al proccesar esta solicitud.',
                icon: "error"
            });
        } else if (err.status === 422) {
            Swal.fire({
                title: "Error",
                text: 'Por favor, verificar si la información ingresada es correcta.',
                icon: "error"
            });
        }
    }


document.getElementById('btn-save-modulo').addEventListener('click', function(event) {
    event.preventDefault();
    let nombre = document.getElementById('nombreModulo').value;
    let descripcion = document.getElementById('descripcionModulo').value;
    if (nombre === '' || descripcion === '') {
        Swal.fire({
            title: "Error",
            text: 'Favor completar todos los campos',
            icon: "error"
        });
                return;
    }
    axios.post(route('app.modulo.save'), {
        nombreModulo: nombre,
        descripcionModulo: descripcion
    })
    .then(function (response) {
        Swal.fire({
            title: "Exito",
            text: 'Módulo creado exitosamente.',
            icon: "success"
        });
        let urlDataTableUsuario = window.location.origin + "/modulos/show";
        dataTable("modulos", urlDataTableUsuario);
       document.getElementById('nombreModulo').value = " ";
       document.getElementById('descripcionModulo').value = " ";
    })
    .catch(function (error) {
        console.error(error);
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error al proccesar esta solicitud.',
            icon: "error"
        });
    });
});
})


function attachModulos() {
    let btn_plus = document.querySelectorAll('.btn-verModulo');

    if (btn_plus) {
        btn_plus.forEach(btn => {
            btn.addEventListener('click', async () => {
                let id_permiso = btn.dataset.ref;
                let input = document.getElementById('id_n');
                input.value = id_permiso;
                let urlDataTableUsuario = window.location.origin + "/permisos/show?permiso_id=" + id_permiso;
                dataTable("permisos", urlDataTableUsuario);
                $('#modal_new_permisos').modal('show'); 
            });
        });
    }
}



document.getElementById('btn-save-permiso').addEventListener('click', function(event) {
    event.preventDefault();
    let nombre = document.getElementById('nombrePermiso').value;
    let descripcion = document.getElementById('descripcionPermiso').value;
    let id_permiso = document.getElementById('id_n').value;
    if (nombre === '' || descripcion === '') {
        Swal.fire({
            title: "Error",
            text: 'Favor completar todos los campos',
            icon: "error"
        });
        return;
    }
    axios.post(route('app.permiso.save'), {
        nombrePermiso: nombre,
        descripcionPermiso: descripcion,
        id_modulo: id_permiso
    })
    .then(function (response) {
        Swal.fire({
            title: "Exito",
            text: 'Permiso creado exitosamente.',
            icon: "success"
        });
        let urlDataTableUsuario = window.location.origin + "/permisos/show?permiso_id=" + id_permiso;
        dataTable("permisos", urlDataTableUsuario);
       document.getElementById('nombrePermiso').value = " ";
       document.getElementById('descripcionPermiso').value = " ";
       document.getElementById('id_n').value = " ";
    })
    .catch(function (error) {
        console.error(error);
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error al proccesar esta solicitud.',
            icon: "error"
        });
    });
});

let seleccionados = {};
function gestionarSeleccionPermiso(permisoId, permisoNombre, moduloId, moduloNombre, checkbox) {
    if (checkbox.checked) {
        if (!seleccionados[moduloId]) {
            seleccionados[moduloId] = {
                id_modulo: moduloId,
                nombre: moduloNombre,
                permisos: []
            };
        }
        seleccionados[moduloId].permisos.push({
            id: permisoId,
            nombre: permisoNombre
        });
    } else {
        seleccionados[moduloId].permisos = seleccionados[moduloId].permisos.filter(p => p.id !== permisoId);
        if (seleccionados[moduloId].permisos.length === 0) {
            delete seleccionados[moduloId];
        }
    }
}