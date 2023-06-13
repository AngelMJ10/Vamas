function sendWork(idtarea) {
    const documento = document.querySelector("#documento").files[0];
    const mensaje = document.querySelector("#mensaje");
    const confirmacion = confirm("¿Estás seguro del documento ingresado?");
    if (confirmacion) {
        const formData = new FormData();
        formData.append("op", "sendWork");
        formData.append("idtarea", idtarea);
        formData.append("documento", documento, documento.name);
        formData.append("mensaje", mensaje.value);

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
            sendWork(idtarea); // Pasar el valor de idempresa a la función update
        });
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
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

list();