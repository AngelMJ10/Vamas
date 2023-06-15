function sendWork(idtarea) {
    const documento = document.querySelector("#documento").files[0];
    const correo = document.querySelector("#correo");
    const mensaje = document.querySelector("#mensaje");
    const confirmacion = confirm("¿Estás seguro del documento ingresado?");
    if (confirmacion) {
        const formData = new FormData();
        formData.append("op", "sendWork");
        formData.append("idtarea", idtarea);
        formData.append("documento", documento, documento.name);
        formData.append("mensaje", mensaje.value);
        formData.append("correo", correo.value);

        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: formData
        }).then(respuesta => {
            if (respuesta.ok) {
                alert('Trabajo enviado correctamente');
                location.reload();
            } else {
                alert('Error en la solicitud');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
}

function enviarTrabajo(idtarea) {
    const documento = document.querySelector("#documento").files[0];
    const correo = document.querySelector("#correo");
    const porcentaje = document.querySelector("#porcentaje");
    const mensaje = document.querySelector("#mensaje");
    const confirmacion = confirm("¿Estás seguro del documento ingresado?");
    if (confirmacion) {
        const formData = new FormData();
        formData.append("op", "enviarTrabajo");
        formData.append("idtarea", idtarea);
        formData.append("documento", documento, documento.name);
        formData.append("mensaje", mensaje.value);
        formData.append("correo", correo.value);
        formData.append("porcentaje", porcentaje.value);

        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: formData
        }).then(respuesta => {
            if (respuesta.ok) {
                alert('Trabajo enviado correctamente');
                location.reload();
            } else {
                alert('Error en la solicitud');
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    }
}

function obtenerID(idtarea){
        const formData = new FormData();
        formData.append("op", "obtenerID");
        formData.append("idtarea", idtarea);

        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: formData
        }).then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json();
            } else {
                alert('Error en la solicitud');
            }
        })
        .then(datos => {
            const idfase = datos.idfase;
            const idproyecto = datos.idproyecto;
            obtenerPorcentajeF(idfase);
            obtenerPorcentajeP(idproyecto);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function obtenerPorcentajeF(idfase){
    const formData = new FormData();
    formData.append("op", "obtenerPorcentajeF");
    formData.append("idfase", idfase);

    fetch('../controllers/fase.php', {
        method: 'POST',
        body: formData
    }).then(respuesta => {
        if (respuesta.ok) {
            alert('Fase actualizada Correctamente');
        } else {
            alert('Error en la solicitud');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function obtenerPorcentajeP(idproyecto){
    const formData = new FormData();
    formData.append("op", "obtenerPorcentajeP");
    formData.append("idproyecto", idproyecto);

    fetch('../controllers/proyecto.php', {
        method: 'POST',
        body: formData
    }).then(respuesta => {
        if (respuesta.ok) {
            alert('Proyecto actualizado Correctamente');
        } else {
            alert('Error en la solicitud');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function openModal(id) {
    const modal = document.querySelector("#modalWork");
    const idtarea = id; 
  
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "getWork");
    parametrosURL.append("idtarea", id);
  
    fetch('../controllers/tarea.php', {
      method: 'POST',
      body: parametrosURL
    })
    .then(respuesta => {
      if (respuesta.ok) {
        return respuesta.text();
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .then(datos => {
        const btnEnviar = document.querySelector("#enviarTarea");
        btnEnviar.addEventListener("click", function () {
            // Pasar el valor de idtarea a las siguientes funciones
            enviarTrabajo(idtarea); 
            obtenerID(idtarea);
            obtenerPorcentajeF(idfase);
            obtenerPorcentajeP(idproyecto);
        });
        
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function listarCorreo(){
    const correo = document.querySelector("#correo");
    const parametros = new URLSearchParams();
    parametros.append("op","listarCorreo");
    fetch(`../controllers/tarea.php`, {
        method: 'POST',
        body: parametros
    })
        .then(respuesta => respuesta.text())
        .then(datos => {
            correo.innerHTML = datos;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function list(){
    const table = document.querySelector("#tabla-tareas");
    const bodytable = table.querySelector("tbody");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "list");

    fetch('../controllers/tarea.php',{
        method: 'POST',
        body: parametrosURL
    })
    .then(respuesta => {
        if(respuesta.ok){
            return respuesta.text();
        } else{
            throw new Error('Error en la solicitud');
        }
    })
    .then(datos =>{
        bodytable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

listarCorreo();
list();