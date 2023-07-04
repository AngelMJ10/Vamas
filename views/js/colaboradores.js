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
const txtDocumento = document.querySelector("#documento-cola");
const NTelefono = document.querySelector("#telefono-cola");
const btnRead = document.querySelector("#editar-colaborador");
const btnEditar = document.querySelector("#guardar-edicion");
const btnCancelar = document.querySelector("#cancelar-edicion");

// Modal de habilidades
const modalH = document.querySelector("#modal-habilidades");
const habilidades = document.querySelector("#habilidades");

let idpersona = 0;

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
        txtDocumento.value = datos.nrodocumento;
        NTelefono.value = datos.telefono;
        txtTarea.value = datos.Tareas;
        txtxHabilidad.value = datos.habilidades;
        idpersona = datos.idpersona;
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

function quitarRead(){
    txtusu.readOnly = false;
    txtCorreo.readOnly = false;
    txtNivel.readOnly = false;
    txtApellido.readOnly = false;
    txtNombre.readOnly = false;
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
    txtDocumento.readOnly = true;
    NTelefono.readOnly = true;

    btnRead.classList.remove("d-none");
    btnEditar.classList.add("d-none");
    btnCancelar.classList.add("d-none");
}

function editarColaborardor_Persona(){
    if (!txtusu.value || !txtCorreo.value || !txtNivel.value || !txtFases.value || !txtApellido.value || !txtNombre.value || !txtDocumento.value || !NTelefono.value) {
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

function abrirModalH(){
    const bootstrapModal = new bootstrap.Modal(modalH);
    bootstrapModal.show();
}

listar();
btnRead.addEventListener("click", quitarRead);
btnCancelar.addEventListener("click", cancelarEditar);
btnEditar.addEventListener("click",editarColaborardor_Persona);