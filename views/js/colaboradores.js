const tablaListar = document.querySelector("#tabla-colaboradores tbody");

// Copnstantes para el modal info
const modalInfo = document.querySelector("#modalInfo");
const txtusu = document.querySelector("#usuario-editar");
const txtCorreo = document.querySelector("#correo-editar");
const txtNivel = document.querySelector("#nivel-editar");
const txtFases = document.querySelector("#fases-editar");
const txtNombre = document.querySelector("#nombres-editar");
const txtApellido = document.querySelector("#apellidos-editar");
const txtGenero = document.querySelector("#genero-editar");
const txtxHabilidad = document.querySelector("#habilidades-editar");
const txtTarea = document.querySelector("#tareas-editar");
const txtDocumento = document.querySelector("#documento-cola");
const NTelefono = document.querySelector("#telefono-cola");
const btnRead = document.querySelector("#editar-colaborador");
const btnEditar = document.querySelector("#guardar-edicion");
const btnCancelar = document.querySelector("#cancelar-edicion");

// Modal de habilidades
const modalH = document.querySelector("#modal-habilidades");
const habilidadesCol = document.querySelector("#habilidadesCol");
const btnRegistrar = document.querySelector("#registrar-habilidad");

// Campos de filtros
const nombreColaborador = document.querySelector("#buscar-colaborador");
const buscarNivel = document.querySelector("#buscar-nivelacceso");
const buscarCorreo = document.querySelector("#buscar-correo");
const btnBuscar = document.querySelector("#buscar-colaboradores");
const listHabilidades = document.querySelector("#habilidades-colaboradores");;

let idpersona = 0;
let idcolaboradores = 0;

function obtenerInfo(id){
    const parametros = new URLSearchParams();
    parametros.append("op", "infoColaboradores");
    parametros.append("idcolaboradores", id);
    fetch('../controllers/persona.php', {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.json())
    .then(datos =>{
        txtusu.value = datos.usuario;
        txtCorreo.value = datos.correo;
        txtNivel.value = datos.nivelacceso;
        txtFases.value = datos.Fases
        txtApellido.value = datos.apellidos;
        txtNombre.value = datos.nombres;
        txtGenero.value = datos.genero;
        txtDocumento.value = datos.nrodocumento;
        NTelefono.value = datos.telefono;
        idpersona = datos.idpersona;
        idcolaboradores = id;
        const bootstrapModal = new bootstrap.Modal(modalInfo);
        bootstrapModal.show();
        function listarHabilidades() {
            listHabilidades.innerHTML = "";
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op", "listarHabilidades");
            parametrosURL.append("idcolaboradores", idcolaboradores);
            fetch('../controllers/persona.php', {
                method: 'POST',
                body: parametrosURL
            })
            .then(respuesta => respuesta.json())
            .then(datos => {
                datos.forEach(element => {
                    const li = document.createElement("li");
                    li.classList.add("list-group-item");
                    li.textContent = element.habilidad;
                     // Crear el botón de deshabilitar
                    const btnEliminar = document.createElement("button");
                    btnEliminar.setAttribute("type", "button");
                    btnEliminar.classList.add("btn", "btn-outline-danger", "btn-sm", "ms-2");
                    btnEliminar.textContent = "Eliminar";
                    btnEliminar.addEventListener("click", () => deshabilitar_habilidad(element.idhabilidades)); // Agregar evento para eliminar

                    // Agregar el botón de eliminar al final del elemento li
                    li.appendChild(btnEliminar);
                    listHabilidades.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        listarHabilidades();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function deshabilitar_habilidad(idhabilidad){
    const parametros = new URLSearchParams();
    parametros.append("op", "deshabilitar_habilidad");
    parametros.append("idhabilidades", idhabilidad);
    Swal.fire({
        icon: 'question',
        title: 'Confirmacion',
        text: '¿Está seguro de deshabilitar la habilidad?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../controllers/persona.php', {
            method: 'POST',
            body: parametros
            })
            .then(respuesta => {
                if (respuesta.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Habilidad deshabilitada',
                        text: 'La habilidad ha sido deshabilitada.'
                    }).then(() =>{
                        location.reload();
                    });
                }else{
                    console.error('Error:', error);
                    Swal.alert({
                        icon: 'Error',
                        title: 'Error al deshabilitar la habilidad',
                        text: 'Ocurrió un error al deshabilitar la habilidad. Por favor intentelo de nuevo.'
                    })
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    })
}

function listar(){
    const parametros = new URLSearchParams();
    parametros.append("op", "listarTcolaboradores");
    fetch('../controllers/persona.php', {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.text())
    .then(datos =>{
        tablaListar.innerHTML=datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function quitarRead(){
    txtusu.readOnly = false;
    txtCorreo.readOnly = false;
    txtNivel.readOnly = false;
    txtApellido.readOnly = false;
    txtNombre.readOnly = false;
    txtGenero.readOnly = false;
    txtDocumento.readOnly = false;
    NTelefono.readOnly = false;

    btnRead.classList.add("d-none");
    btnEditar.classList.remove("d-none");
    btnCancelar.classList.remove("d-none");
}

function cancelarEditar(){
    txtusu.readOnly = true;
    txtCorreo.readOnly = true;
    txtNivel.readOnly = true;
    txtApellido.readOnly = true;
    txtNombre.readOnly = true;
    txtGenero.readOnly = true;
    txtDocumento.readOnly = true;
    NTelefono.readOnly = true;

    btnRead.classList.remove("d-none");
    btnEditar.classList.add("d-none");
    btnCancelar.classList.add("d-none");
}

function editarColaborardor_Persona(){

    if (!txtusu.value || !txtCorreo.value || !txtNivel.value || !txtFases.value || !txtApellido.value || !txtNombre.value || !txtGenero.value || !txtDocumento.value || !NTelefono.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos.',
        });
        return;
    }

    Swal.fire({
        icon: 'question',
        title: 'Confirmacion',
        text: '¿Está seguro de los datos modificados?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            const parametros = new URLSearchParams();
            parametros.append("op", "editarPersona");
            parametros.append("idpersona", idpersona);
            parametros.append("usuario", txtusu.value);
            parametros.append("correo", txtCorreo.value);
            parametros.append("nivelacceso", txtNivel.value);
            parametros.append("apellidos", txtApellido.value);
            parametros.append("nombres", txtNombre.value);
            parametros.append("genero", txtGenero.value);
            parametros.append("nrodocumento", txtDocumento.value);
            parametros.append("telefono", NTelefono.value);
            fetch('../controllers/persona.php', {
                method: 'POST',
                body: parametros,
            })
            .then(respuesta =>{
                if (respuesta.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Colaborador Actualizado',
                        text: 'El colobarador ha sido actualizado correctamente.'
                    }).then(() =>{
                        location.reload();
                    });
                } else{
                    throw new Error('Error en la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.alert({
                    icon: 'Error',
                    title: 'Error al editar el usuario',
                    text: 'Ocurrió un error al actualizar el colaborador. Por favor intentelo de nuevo.'
                })
            });
        }
    })  
}

// Abrir modela de habilidades
function abrirModalH(id) {
    const bootstrapModal = new bootstrap.Modal(modalH);
    const listHabilidadesAsi = document.querySelector("#habilidades-colaboradores-editar");;
    const habilidadesCol = document.querySelector("#habilidadesCol");
    idcolaboradores = id;

    // Mostrar el modal
    bootstrapModal.show();

    const parametros = new URLSearchParams();
    parametros.append("op", "listarHabilidades");
    parametros.append("idcolaboradores", id);
    fetch('../controllers/persona.php', {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        const habilidadesOptions = habilidadesCol.options;
        // Restablecer el estado inicial del select eliminando la clase d-none de todas las opciones
        for (let i = 0; i < habilidadesOptions.length; i++) {
            habilidadesOptions[i].classList.remove("d-none");
        }
        listHabilidadesAsi.innerHTML = "";
        datos.forEach(element => {
            const li = document.createElement("li");
            li.classList.add("list-group-item");
            li.textContent = element.habilidad;
            listHabilidadesAsi.appendChild(li);
            for (let i = 0; i < habilidadesOptions.length; i++) {
                if (habilidadesOptions[i].value === element.habilidad) {
                    habilidadesOptions[i].classList.add("d-none");
                }
                
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Asiganar habilidades 
function asignarHabilidad() {
    if (!habilidadesCol.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo incompleto',
            text: 'Por favor, completa todos los campos.',
        });
        return;
    }

    Swal.fire({
        icon: 'question',
        title: 'Confirmacion',
        text: '¿Está seguro de la habilidad asignada?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            const parametrosH = new URLSearchParams();
            parametrosH.append("op", "listar_Habilidades_inactivas");
            parametrosH.append("idcolaboradores", idcolaboradores);
            fetch('../controllers/persona.php', {
                method: 'POST',
                body: parametrosH,
            })
            .then(respuestaH => respuestaH.json())
            .then(datos => {
                console.log(datos);
                if (datos.length === 0) {
                    console.log("Caso cuando no hay habilidades inactivas");
                    // Caso cuando no hay habilidades inactivas
                    const parametros = new URLSearchParams();
                    parametros.append("op", "asignarHabilidad");
                    parametros.append("idcolaboradores", idcolaboradores);
                    parametros.append("habilidad", habilidadesCol.value);
                    return fetch('../controllers/persona.php', {
                        method: 'POST',
                        body: parametros,
                    });
                } else {
                    console.log("Caso cuando hay habilidades inactivas o diferentes");
                    // Caso cuando hay habilidades inactivas o diferentes
                    const habilidadesDiferentes = datos.filter(element => element.habilidad !== habilidadesCol.value);
                    if (habilidadesDiferentes.length > 0) {
                        const primeraHabilidad = habilidadesDiferentes[0];
                        const parametros = new URLSearchParams();
                        parametros.append("op", "asignarHabilidad");
                        parametros.append("idcolaboradores", idcolaboradores);
                        parametros.append("habilidad", habilidadesCol.value);
                        return fetch('../controllers/persona.php', {
                            method: 'POST',
                            body: parametros,
                        });
                    } else {
                        console.log("Caso cuando todas las habilidades inactivas son iguales");
                        // Caso cuando todas las habilidades inactivas son iguales
                        const parametrosI = new URLSearchParams();
                        parametrosI.append("op", "activar_habilidad");
                        parametrosI.append("idhabilidades", datos[0].idhabilidades);
                        return fetch('../controllers/persona.php', {
                            method: 'POST',
                            body: parametrosI,
                        });
                    }
                }
            })
            .then(respuesta => {
                if (respuesta.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Habilidad asignada',
                        text: 'Se ha agregado una nueva habilidad correctamente.'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error('Error en la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.alert({
                    icon: 'error',
                    title: 'Error al asignar la habilidad',
                    text: 'Ocurrió un error al asignar la habilidad. Por favor, inténtelo de nuevo.'
                });
            });
        }
    });
}

function buscarColaboradores(){
    const parametros = new URLSearchParams();
    parametros.append("op", "buscarColaboradores");
    parametros.append("usuario", nombreColaborador.value);
    parametros.append("nivelacceso", buscarNivel.value);
    parametros.append("correo", buscarCorreo.value);
    fetch(`../controllers/persona.php`, {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.text())
    .then(datos => {
        tablaListar.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

listar();
btnRead.addEventListener("click", quitarRead);
btnCancelar.addEventListener("click", cancelarEditar);
btnEditar.addEventListener("click",editarColaborardor_Persona);
btnRegistrar.addEventListener("click",asignarHabilidad);
btnBuscar.addEventListener("click", buscarColaboradores);