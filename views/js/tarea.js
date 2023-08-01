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

  function enviarTrabajo(idtarea) {
    const documento = document.querySelector("#documento").files[0];
    const mensaje = document.querySelector("#mensaje").value;
    const porcentaje = document.querySelector("#porcentaje").value;
    const correo = document.querySelector("#correo3").value;
  
    // Validación de campos

    if (isNaN(porcentaje) || porcentaje < 0 || porcentaje > 100) {
      Swal.fire({
        icon: 'warning',
        title: 'Porcentaje excedido',
        text: 'Por favor, ingrese un porcentaje de 0 a 100.',
      });
      return;
    }

    if (!documento || !mensaje || !porcentaje || !correo) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos.',
      });
      return;
    }

    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro del avance a enviar?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Enviando avance...',
          allowOutsideClick: false,
          showCancelButton: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
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
            obtenerPorcentajeF();
            obtenerPorcentajeP();
            Swal.fire({
              icon: 'success',
              title: 'Avance enviado',
              text: 'El avance se ha enviado correctamente.'
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
            icon: 'Error',
            title: 'Error al enviar el avance',
            text: 'Ocurrió un error al enviar el avance. Por favor intentelo nuevamente.'
          })
        });
      }
    })
  }
  
  // Función para calcular el porcentaje de la fase
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
  
  // Función para calcular el porcentaje del proyecto
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

  // Función para generar el reporte
  function generarReporte(){
      console.log(idtareaPdf);
      const parametros = new URLSearchParams();
      if(idtareaPdf > 0) {
      parametros.append("idtarea", idtareaPdf);
      window.open(`../reports/Tarea/reporte.php?${parametros}`, '_blank');
      }
  }

  // Función para listar los proyectos en los filtros
  function listarProyecto(){
    const proyecto = document.querySelector("#buscar-proyecto");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listarProyecto");

    fetch('../controllers/proyecto.php',{
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
      proyecto.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

  // Función para listara las fases en los filtros
  function listarFase(){
    const fase = document.querySelector("#buscar-fase");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listarFases");

    fetch('../controllers/fase.php',{
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
      fase.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

  // Para listar las fases del proyecto
  function listarFasesSelect(){
    const fase = document.querySelector("#buscar-fase");
    const proyecto = document.querySelector("#buscar-proyecto");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listarFasesV3");
    parametrosURL.append("idproyecto", proyecto.value);

    fetch('../controllers/fase.php',{
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
      fase.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

  function buscarTarea(){
    const table = document.querySelector("#tabla-tareas");
    const bodytable = table.querySelector("tbody");
    const proyecto = document.querySelector("#buscar-proyecto");
    const fase = document.querySelector("#buscar-fase");
    const tarea = document.querySelector("#nombre-tarea");
    const responsable = document.querySelector("#buscar-colaborador-t");
    const estadoT = document.querySelector("#buscar-estado");
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "buscar_tareas");
    parametrosURL.append("idproyecto", proyecto.value);
    parametrosURL.append("idfase", fase.value);
    parametrosURL.append("tarea", tarea.value);
    parametrosURL.append("idcolaboradorT", responsable.value);
    parametrosURL.append("estado", estadoT.value);

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
        bodytable.innerHTML = "";
        bodytable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

  // Finalizar una tarea por su ID
  function finalizarTarea(id){
    Swal.fire({
    icon: 'question',
    title: 'Confirmación',
    text: '¿Está seguro de finalizar esta tarea?',
    showCancelButton: true,
    confirmButtonText: 'Si',
    cancelButtonText: 'No',
    }).then((result) => {
    if (result.isConfirmed) {
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op" ,"finalizar_tarea_by_id");
        parametrosURL.append("idtarea" ,id);
        fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametrosURL
        })
        .then(respuesta =>{
            if(respuesta.ok){
            Swal.fire({
                icon: 'success',
                title: 'Tarea Finalizada',
                text: 'La tarea ha sido finalizada con éxito.'
            }).then(() => {
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
            title: 'Error al finalizar la tarea',
            text: 'Ocurrió un error al finalizar la tarea. Por favor intentelo nuevamente.'
        })
        });
    }
    })
}

// Reactivar una tarea por su ID
  function reactivarTarea(id){
      Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de reactivar esta tarea?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
      }).then((result) => {
      if (result.isConfirmed) {
          const parametrosURL = new URLSearchParams();
          parametrosURL.append("op" ,"reactivar_tarea_by_id");
          parametrosURL.append("idtarea" ,id);
          fetch('../controllers/tarea.php', {
          method: 'POST',
          body: parametrosURL
          })
          .then(respuesta =>{
              if(respuesta.ok){
              Swal.fire({
                  icon: 'success',
                  title: 'Tarea reactivada',
                  text: 'La tarea ha sido reactivada con éxito.'
              }).then(() => {
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
              title: 'Error al reactivar la tarea',
              text: 'Ocurrió un error al reactivar la tarea. Por favor intentelo nuevamente.'
          })
          });
      }
      })
  }

  function listarColaboradores_A(){
    const responsable = document.querySelector("#buscar-colaborador-t");
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listarColaborador_A");

    fetch('../controllers/proyecto.php',{
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
        responsable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

listarColaboradores_A();
listarCorreo();
list();
listarProyecto();
listarFase();

const btnGenerarReporte = document.querySelector('#generarpdf-tarea');
btnGenerarReporte.addEventListener('click',generarReporte);

const btnBuscarT = document.querySelector('#buscar-tareas');
btnBuscarT.addEventListener('click',buscarTarea);
const btnBuscarF = document.querySelector('#buscar-proyecto');
btnBuscarF.addEventListener('click',listarFasesSelect);