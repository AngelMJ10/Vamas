  // ! Estos son los inputs del modal de tareas
  const modalTarea = document.querySelector("#modalEditar"); 
  const Nproyecto = document.querySelector("#Nproyecto"); 
  const Nfase = document.querySelector("#Nfase"); 
  const Ntarea = document.querySelector("#Ntarea"); 
  const Inifase = document.querySelector("#inicio-fase"); 
  const Finfase = document.querySelector("#fin-fase"); 
  const Initarea = document.querySelector("#inicio-tarea"); 
  const Fintarea = document.querySelector("#fin-tarea"); 
  const Usufase = document.querySelector("#usuario-Fase"); 
  const Usutarea = document.querySelector("#usuario-tarea"); 
  const rol = document.querySelector("#rol"); 
  const ComFase = document.querySelector("#comentario-fase"); 
  const tablaEvidencias = document.querySelector("#tabla-evidencias tbody"); 
  
  let idtareaPdf = 0;
    
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
    const mensaje = document.querySelector("#mensaje").value;
    const porcentaje = document.querySelector("#porcentaje").value;
    const correo = document.querySelector("#correo3").value;
  
    // Validación de campos
    if (!documento || !mensaje || !porcentaje || !correo) {
      alert("Por favor, completa todos los campos.");
      return;
    }
  
    const confirmacion = confirm("¿Estás seguro del documento ingresado?");
    if (confirmacion) {
      const formData = new FormData();
      formData.append("correo", correo)
      formData.append("documento", documento);
      formData.append("mensaje", mensaje);
      formData.append("porcentaje", porcentaje);
      formData.append("idtarea", idtarea);
  
      fetch('../send/upload.php', {
        method: 'POST',
        body: formData
      })
      .then(respuesta => {
        if (respuesta.ok) {
          alert('Trabajo enviado correctamente');
          obtenerPorcentajeF();
          obtenerPorcentajeP();
          location.reload();
        } else {
          throw new Error('Error en la solicitud');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al enviar el trabajo. Por favor, inténtalo nuevamente.');
      });
    }
  }
  
  function obtenerPorcentajeF() {
    const formData = new FormData();
    formData.append("op", "obtenerPorcentajeF");
  
    fetch('../controllers/fase.php', {
      method: 'POST',
      body: formData
    })
    .then(respuesta => {
      if (respuesta.ok) {
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
  
  function obtenerPorcentajeP() {
    const formData = new FormData();
    formData.append("op", "obtenerPorcentajeP");
  
    fetch('../controllers/proyecto.php', {
      method: 'POST',
      body: formData
    })
    .then(respuesta => {
      if (respuesta.ok) {
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
  
  // Modal para enviar la evidencias
  function openModal(id) {
    const modal = document.querySelector("#modalWork"); 
    const asunto = document.querySelector("#asunto"); 
    idtareaPdf = id;
  
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
      datos = JSON.parse(datos); // Si el resultado es un JSON, debes parsearlo
      const btnEnviar = document.querySelector("#enviarTarea");
      asunto.value = datos.nombrefase;
      let idfase = datos.idfase;
      btnEnviar.addEventListener("click", function () {
        // Pasar el valor de idtarea a las siguientes funciones
        enviarTrabajo(idtareaPdf);
      });
  
      const bootstrapModal = new bootstrap.Modal(modal);
      bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }

  function listarCorreo(){
      const correo = document.querySelector("#correo3");
      const Ncorreo = document.querySelector("#para");
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
        table.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }  

  // Modal de evidencias
  function obtenerInfo(id){
    const idtarea = id; 
  
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "getWork");
    parametrosURL.append("idtarea", idtarea);

    // Ejecutar la operación adicional
    const parametrosURL2 = new URLSearchParams();
    parametrosURL2.append("op", "verEvidencias");
    parametrosURL2.append("idtarea", idtarea);
  
    fetch('../controllers/tarea.php', {
      method: 'POST',
      body: parametrosURL
    })
    .then(respuesta => {
      if (respuesta.ok) {
        return respuesta.json();
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .then(datos => {
      Nproyecto.value = datos.titulo;
      Nfase.value = datos.nombrefase;
      Ntarea.value = datos.tarea;
      Inifase.value = datos.fechainicio;
      Finfase.value = datos.fechainicio;
      Initarea.value = datos.fecha_inicio_tarea;
      Fintarea.value = datos.fecha_fin_tarea;
      Usufase.value = datos.usuario_fase;
      Usutarea.value = datos.usuario_tarea;
      rol.value = datos.roles;
      ComFase.value = datos.comentario;

      fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametrosURL2
      })
      .then(respuesta2 => {
        if (respuesta2.ok) {
          return respuesta2.text();
        } else {
          throw new Error('Error en la solicitud de evidencias');
        }
      })
      .then(evidencias => {
        // Agregar el HTML de las evidencias al elemento deseado
        tablaEvidencias.innerHTML = evidencias;
      })
      .catch(error => {
        console.error('Error:', error);
      });
      const bootstrapModal = new bootstrap.Modal(modalTarea);
      bootstrapModal.show();
      idtareaPdf = idtarea;
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }

  function generarReporte(){
      console.log(idtareaPdf);
      const parametros = new URLSearchParams();
      if(idtareaPdf > 0) {
      parametros.append("idtarea", idtareaPdf);
      window.open(`../reports/Prueba1/reporte.php?${parametros}`, '_blank');
      }
  }

  function generarReporteV(idtarea){
    const parametros = new URLSearchParams();
    if(idtarea > 0) {
    parametros.append("idtarea", idtarea);
    window.open(`../reports/Prueba1/reporte.php?${parametros}`, '_blank');
    }
}

listarCorreo();
obtenerPorcentajeP
list();

const btnGenerarReporte = document.querySelector('#generarpdf-tarea');
btnGenerarReporte.addEventListener('click',generarReporte);