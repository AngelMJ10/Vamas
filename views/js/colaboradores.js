const tablaListar = document.querySelector("#tabla-colaboradores tbody");

// Copnstantes para el modal info
const modalInfo = document.querySelector("#modalInfo");
const txtusu = document.querySelector("#usuario-editar");
const txtCorreo = document.querySelector("#correo-editar");
const txtNivel = document.querySelector("#nivel-editar");
const txtFases = document.querySelector("#fases-editar");
const txtNombre = document.querySelector("#nombres-editar");
const txtApellido = document.querySelector("#apellidos-editar");
const txtxHabilidad = document.querySelector("#habilidades-editar");
const txtTarea = document.querySelector("#tareas-editar");

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
        txtxHabilidad.value = datos.habilidades;
        txtTarea.value = datos.Tareas;
        const bootstrapModal = new bootstrap.Modal(modalInfo);
      bootstrapModal.show();
    })
    .catch(error => {
        console.error('Error:', error);
    });
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

listar();