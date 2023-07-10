// ?Variables 
let idfase = 0;
let idproyecto = 0;
let idtarea = 0;

// Aqui se abre el modal de proyecto
  function info(id) {
    const modal = document.querySelector("#modal-info");
    const body = document.querySelector("#inputs");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "info");
    parametrosURL.append("idproyecto", id);

    fetch('../controllers/proyecto.php', {
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
        body.innerHTML = datos;
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        getPhase(id);
        idproyecto = id;
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }

// Tabla de las fases del proyecto
  function getPhase(id) {
    const tabla_fases = document.querySelector("#tabla-fase");
    const parametros = new URLSearchParams();
    parametros.append("op" ,"getPhase");
    parametros.append("idproyecto" , id);
    fetch('../controllers/proyecto.php', {
      method: 'POST',
      body: parametros
    })
    .then(respuesta => {
      if (respuesta.ok) {
        return respuesta.text();
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .then(datos => {
      tabla_fases.innerHTML = datos;
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }

// Para finalizar una fase
  function finalizarFase(id){
    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de finalizar esta fase?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op" ,"finalizar_fase_by_id");
        parametrosURL.append("idfase" ,id);
        fetch('../controllers/fase.php', {
          method: 'POST',
          body: parametrosURL
        })
        .then(respuesta =>{
            if(respuesta.ok){
              Swal.fire({
                icon: 'success',
                title: 'Fase Finalizada',
                text: 'La fase ha sido finalizada con éxito.'
              }).then(() => {
                info(idproyecto);
              });
            } else{
              throw new Error('Error en la solicitud');
            }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.alert({
            icon: 'Error',
            title: 'Error al finalizar la fase',
            text: 'Ocurrió un error al finalizar la fase. Por favor intentelo nuevamente.'
          })
        });
      }
    })
  }

  // Para reactivar una fase
  function reactivarFase(id){
  Swal.fire({
    icon: 'question',
    title: 'Confirmación',
    text: '¿Está seguro de reactivar esta fase?',
    showCancelButton: true,
    confirmButtonText: 'Si',
    cancelButtonText: 'No',
  }).then((result) => {
    if (result.isConfirmed) {
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op" ,"reactivar_fase_by_id");
      parametrosURL.append("idfase" ,id);
      fetch('../controllers/fase.php', {
        method: 'POST',
        body: parametrosURL
      })
      .then(respuesta =>{
          if(respuesta.ok){
            Swal.fire({
              icon: 'success',
              title: 'Fase Reactivada',
              text: 'La fase ha sido reactivada con éxito.'
            }).then(() => {
              info(idproyecto);
            });
          } else{
            throw new Error('Error en la solicitud');
          }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.alert({
          icon: 'Error',
          title: 'Error al reactivar la fase',
          text: 'Ocurrió un error al reactivar la fase. Por favor intentelo nuevamente.'
        })
      });
    }
  })
  }

  function get(id) {
    const modal = document.querySelector("#modalEditar");
    const titulo = document.querySelector("#titulo-update");
    const tipoproyecto = document.querySelector("#tipoProyecto-update");
    const empresa = document.querySelector("#idempresa-update");
    const descripcion = document.querySelector("#descripcion-update");
    const fechainicio = document.querySelector("#fecha-inicio-update");
    const fechafin = document.querySelector("#fecha-fin-update");
    const precio = document.querySelector("#precio-update");
    const estado = document.querySelector("#estado-update");
    const usuario = document.querySelector("#user-create");
    const idproyecto = id; 
  
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "get");
    parametrosURL.append("idproyecto", id);
  
    fetch('../controllers/proyecto.php', {
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
        titulo.value = datos.titulo;
        tipoproyecto.value = datos.idtipoproyecto;
        empresa.value = datos.idempresa;
        descripcion.value = datos.descripcion;
        fechainicio.value = datos.fechainicio;
        fechafin.value = datos.fechafin;
        precio.value = datos.precio;
        estado.value = datos.estado;
        usuario.value = datos.usuario;
        idproyecto = id;


      const btnEditar = document.querySelector("#update-datos");
        btnEditar.addEventListener("click", function () {
          editarProyectoV2(idproyecto); // Pasar el valor de idproyecto a la función update
        });

  
      const bootstrapModal = new bootstrap.Modal(modal);
      bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }

  function editarProyectoV2(id){
      const titulo = document.querySelector("#titulo-update");
      const tipoproyecto = document.querySelector("#tipoProyecto-update");
      const empresa = document.querySelector("#idempresa-update");
      const descripcion = document.querySelector("#descripcion-update");
      const fechainicio = document.querySelector("#fecha-inicio-update");
      const fechafin = document.querySelector("#fecha-fin-update");
      const precio = document.querySelector("#precio-update");

      if (!tipoproyecto.value || !empresa.value || !titulo.value || !descripcion.value || !fechainicio.value || !fechafin.value || !precio.value) {
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
        text: '¿Está seguro de los datos ingresados?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
            obtenerPorcentajeF();
            obtenerPorcentajeP();
            const parametros = new URLSearchParams();
            parametros.append("op", "editar");
            parametros.append("idproyecto", id);
            parametros.append("idtipoproyecto", tipoproyecto.value);
            parametros.append("idempresa", empresa.value);
            parametros.append("titulo", titulo.value);
            parametros.append("descripcion", descripcion.value);
            parametros.append("fechainicio", fechainicio.value);
            parametros.append("fechafin", fechafin.value);
            parametros.append("precio", precio.value);
      
            fetch('../controllers/proyecto.php', {
              method: 'POST',
              body: parametros
            })
              .then(respuesta => {
                if(respuesta.ok){
                  Swal.fire({
                    icon: 'success',
                    title: 'Proyecto Actualizado',
                    text: 'El proyecto se ha actualizado correctamente.'
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
                title: 'Error al registrar la fase',
                text: 'Ocurrió un error al registrar la fase. Por favor intentelo nuevamente.'
              })
            });
        }
      })
  }

  // Editar Proyecto

  function quitarReadonlyP() {
    const tipoproyecto_p = document.querySelector('#tipo_proyecto');
    const idempresa_p = document.querySelector('#id_empresa');
    const titulo_proyecto = document.querySelector('#titulo_proyecto');
    const descripcion_p = document.querySelector('#descripcion_proyecto');
    const fechainicio_p = document.querySelector('#fechainicio_proyecto');
    const fechafin_p = document.querySelector('#fechafin_proyecto');
    const precio_p = document.querySelector('#precio_proyecto');

    const btnEditar = document.querySelector('#guardar-proyecto');
    const btnCancelar = document.querySelector('#cancelar-edicion-proyecto');
    const btnabrirEdito = document.querySelector('#editar-proyecto');
    const btnAgregaF = document.querySelector('#agregar-fase');

    tipoproyecto_p.readOnly = false;
    idempresa_p.readOnly = false;
    titulo_proyecto.readOnly = false;
    descripcion_p.readOnly = false;
    fechainicio_p.readOnly = false;
    fechafin_p.readOnly = false;
    precio_p.readOnly = false;

    btnEditar.classList.remove("d-none");
    btnCancelar.classList.remove("d-none");
    btnabrirEdito.classList.add("d-none");
    btnAgregaF.classList.add("d-none");
    function listartipoproyecto(){
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listartipoproyecto");

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
          tipoproyecto_p.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }
    function listarempresaF(){
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarempresa");

      fetch('../controllers/empresa.php',{
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
          idempresa_p.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }
    listartipoproyecto();
    listarempresaF();
  }

  function cancelarEdicionP() {
    const tipoproyecto_p = document.querySelector('#tipo_proyecto');
    const idempresa_p = document.querySelector('#id_empresa');
    const titulo_proyecto = document.querySelector('#titulo_proyecto');
    const descripcion_p = document.querySelector('#descripcion_proyecto');
    const fechainicio_p = document.querySelector('#fechainicio_proyecto');
    const fechafin_p = document.querySelector('#fechafin_proyecto');
    const precio_p = document.querySelector('#precio_proyecto');

    const btnEditar = document.querySelector('#guardar-proyecto');
    const btnCancelar = document.querySelector('#cancelar-edicion-proyecto');
    const btnabrirEdito = document.querySelector('#editar-proyecto');
    const btnAgregaF = document.querySelector('#agregar-fase');

    tipoproyecto_p.readOnly = true;
    idempresa_p.readOnly = true;
    titulo_proyecto.readOnly = true;
    descripcion_p.readOnly = true;
    fechainicio_p.readOnly = true;
    fechafin_p.readOnly = true;
    precio_p.readOnly = true;

    btnEditar.classList.add("d-none");
    btnCancelar.classList.add("d-none");
    btnabrirEdito.classList.remove("d-none");
    btnAgregaF.classList.remove("d-none");
    info(idproyecto);
  }

  function editarProyecto(){
    const tipoproyecto_p = document.querySelector('#tipo_proyecto');
    const idempresa_p = document.querySelector('#id_empresa');
    const titulo_proyecto = document.querySelector('#titulo_proyecto');
    const descripcion_p = document.querySelector('#descripcion_proyecto');
    const fechainicio_p = document.querySelector('#fechainicio_proyecto');
    const fechafin_p = document.querySelector('#fechafin_proyecto');
    const precio_p = document.querySelector('#precio_proyecto');

    if (!tipoproyecto_p.value || !idempresa_p.value || !titulo_proyecto.value || !descripcion_p.value || !fechainicio_p.value || !fechafin_p.value || !precio_p.value) {
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
      text: '¿Está seguro de los datos ingresados?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
          obtenerPorcentajeF();
          obtenerPorcentajeP();
          const parametros = new URLSearchParams();
          parametros.append("op", "editar");
          parametros.append("idproyecto", idproyecto);
          parametros.append("idtipoproyecto", tipoproyecto_p.value);
          parametros.append("idempresa", idempresa_p.value);
          parametros.append("titulo", titulo_proyecto.value);
          parametros.append("descripcion", descripcion_p.value);
          parametros.append("fechainicio", fechainicio_p.value);
          parametros.append("fechafin", fechafin_p.value);
          parametros.append("precio", precio_p.value);
    
          fetch('../controllers/proyecto.php', {
            method: 'POST',
            body: parametros
          })
            .then(respuesta => {
              if(respuesta.ok){
                Swal.fire({
                  icon: 'success',
                  title: 'Proyecto Actualizado',
                  text: 'El proyecto se ha actualizado correctamente.'
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
              title: 'Error al registrar la fase',
              text: 'Ocurrió un error al registrar la fase. Por favor intentelo nuevamente.'
            })
          });
      }
    })
  }

// Fin editar Proyecto

// Agregar fase 

  function abriModal(){
    const modalFase = document.querySelector("#modalFaseV2");
    const bootstrapModal = new bootstrap.Modal(modalFase);
    bootstrapModal.show();
    const tituloP = document.querySelector("#titulo-fase");
    const tipoproyectoP = document.querySelector("#tipoProyecto-fase");
    const empresaP = document.querySelector("#idempresa-fase");

    const parametros = new URLSearchParams();
    parametros.append("op", "get");
    parametros.append("idproyecto", idproyecto);
    fetch('../controllers/proyecto.php', {
      method: 'POST',
      body: parametros
    })
    .then(respuesta => {
      if (respuesta.ok) {
        return respuesta.json();
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .then(datos => {
      tituloP.value = datos.titulo;
      tipoproyectoP.value = datos.tipoproyecto;
      empresaP.value = datos.nombre;
    })
    .catch(error => {
      console.error('Error:', error);
    });

    // Agregamos los colaboradores al select
    function listarSupervisores(){
      const usuarioR = document.querySelector("#responsable-faseV2");
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarColaborador");

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
        usuarioR.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }

    listarSupervisores();
  }

  function agregarFase(){
    const namephase = document.querySelector("#name-faseV2");
    const responsable = document.querySelector("#responsable-faseV2");
    const fechainicio = document.querySelector("#fecha-inicio-faseV2");
    const fechafin = document.querySelector("#fecha-fin-faseV2");
    const comentario = document.querySelector("#comentario-faseV2");
    const porcentaje = document.querySelector("#porcentaje-crear-F");
    const porcentajeValor = porcentaje.value;

    if (!namephase.value || !responsable.value || !fechainicio.value || !fechafin.value || !comentario.value || !porcentaje.value ) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos.',
      });
      return;
    }

    if (isNaN(porcentajeValor) || porcentajeValor < 0 || porcentajeValor > 100) {
      Swal.fire({
        icon: 'warning',
        title: 'Porcentaje excedido',
        text: 'Por favor, ingrese un porcentaje de 0 a 100.',
      });
      return;
    }

    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de los datos ingresados?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Creando la fase...',
          allowOutsideClick: false,
          showCancelButton: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "registerPhaseV2");
        parametrosURL.append("idproyecto", idproyecto);
        parametrosURL.append("idresponsable", responsable.value);
        parametrosURL.append("nombrefase", namephase.value);
        parametrosURL.append("fechainicio", fechainicio.value);
        parametrosURL.append("fechafin", fechafin.value);
        parametrosURL.append("porcentaje", porcentaje.value);
        parametrosURL.append("comentario", comentario.value);
        
        fetch('../controllers/fase.php', {
            method: 'POST',
            body: parametrosURL
        })
        .then(respuesta =>{
            if(respuesta.ok){
              obtenerPorcentajeF();
              obtenerPorcentajeP();
              Swal.fire({
                icon: 'success',
                title: 'Fase registrada',
                text: 'La fase se ha registrado correctamente.'
            }).then(() => {
              location.reload();
            });
            } else{
              
            }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.alert({
            icon: 'Error',
            title: 'Error al registrar la fase',
            text: 'Ocurrió un error al registrar la fase. Por favor intentelo nuevamente.'
          })
        });
      }
    })
  }

  function createPhase(idproyectov){
      const namephase = document.querySelector("#name-phase");
      const respnsible = document.querySelector("#responsible-phase");
      const fechainicio = document.querySelector("#fecha-inicio-phase");
      const fechafin = document.querySelector("#fecha-fin-phase");
      const comentario = document.querySelector("#comentario");
      const porcentaje = document.querySelector("#porcentaje");

      const confirmacion = confirm("¿Estás seguro de los datos ingresados para la fase?");

      if (confirmacion) {
          const parametrosURL = new URLSearchParams();
          parametrosURL.append("op", "registerPhase");
          parametrosURL.append("idproyecto", idproyectov);
          parametrosURL.append("idresponsable", respnsible.value);
          parametrosURL.append("nombrefase", namephase.value);
          parametrosURL.append("fechainicio", fechainicio.value);
          parametrosURL.append("fechafin", fechafin.value);
          parametrosURL.append("porcentaje", porcentaje.value);
          parametrosURL.append("comentario", comentario.value);
          

          fetch('../controllers/fase.php', {
              method: 'POST',
              body: parametrosURL
          })
          .then(respuesta =>{
              if(respuesta.ok){
                  obtenerPorcentajeF();
                  obtenerPorcentajeP();
                  alert('Fase registrado correctamente');
                  location.reload();
              } else{
                  alert('Error en la solicitud');
              }
          })
      }
  }

// Fin de agregar Fase

// Modal de info de fase (está con la tabla de tareas incluida)

  function modalInfoFase(id) {
      const inputs = document.querySelector("#inputs-fase");
      const modalInfoFase = document.querySelector("#modal-info-fase");
      const parametros = new URLSearchParams();
      parametros.append("op" ,"getPhase");
      parametros.append("idfase" , id);
      fetch('../controllers/fase.php', {
          method: 'POST',
          body: parametros
        })
        .then(respuesta => {
          if (respuesta.ok) {
            return respuesta.text();
          } else {
            throw new Error('Error en la solicitud');
          }
        })
        .then(datos => {
          inputs.innerHTML = datos;
          const bootstrapModal = new bootstrap.Modal(modalInfoFase);
          bootstrapModal.show();
          idfase = id;
        })
        .catch(error => {
          console.error('Error:', error);
        });
  }

  function generarReporteF(){
    const parametros = new URLSearchParams();
    if(idfase > 0) {
    parametros.append("idfase", idfase);
    window.open(`../reports/Fase/reporteF.php?${parametros}`, '_blank');
    }
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
                modalInfoFase(idfase);
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

  // Finalizar una fase por su ID
  function reactivarTarea(id) {
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
        parametrosURL.append("op", "reactivar_tarea_by_id");
        parametrosURL.append("idtarea", id);
        fetch('../controllers/tarea.php', {
          method: 'POST',
          body: parametrosURL
        })
          .then(respuesta => {
            if (respuesta.ok) {
              Swal.fire({
                icon: 'success',
                title: 'Tarea reactivada',
                text: 'La tarea ha sido reactivada con éxito.'
              }).then(() => {
                modalInfoFase(idfase);
              });
            } else {
              throw new Error('Error en la solicitud');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.alert({
              icon: 'error',
              title: 'Error al reactivar la tarea',
              text: 'Ocurrió un error al reactivar la tarea. Por favor intentelo nuevamente.'
            });
          });
      }
    });
  }

  // Editar Fase

  // Para quitar el readOnly de los inputs
  function quitarReadonly() {
    const nombreFaseInput = document.querySelector('#nombre-Fase');
    const comentarioTextarea = document.querySelector('#comentario-Fase');
    const fechaInicioInput = document.querySelector('#fechainicio-fase');
    const fechaFinInput = document.querySelector('#fechafin-fase');
    const usuarioResponsableInput = document.querySelector('#usuariore-fase');
    const porcentajeFase = document.querySelector('#porcentaje-Fase');
    

    const btnGuardarEdicion = document.querySelector("#guardar-fase");
    const btnCancelarEdicion = document.querySelector("#cancelar-edicion");
    const btnReporteF = document.querySelector("#generar-reporte-F");

    const btnEditarF = document.querySelector("#editar-fase");
    const btnAFase = document.querySelector("#agregar-tarea");

    nombreFaseInput.readOnly = false;
    comentarioTextarea.readOnly = false;
    fechaInicioInput.readOnly = false;
    fechaFinInput.readOnly = false;
    usuarioResponsableInput.readOnly = false;
    porcentajeFase.readOnly = false;

    btnGuardarEdicion.classList.remove("d-none");
    btnCancelarEdicion.classList.remove("d-none");
    btnEditarF.classList.add("d-none");
    btnAFase.classList.add("d-none");
    btnReporteF.classList.add("d-none");

    function listarSupervisores(){
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarColaborador");

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
        usuarioResponsableInput.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }

    listarSupervisores();
  }

  function cancelarEdicion() {
    const nombreFaseInput = document.getElementById('nombre-Fase');
    const porcentajeFase = document.getElementById('porcentaje-Fase');
    const fechaInicioInput = document.getElementById('fechainicio-fase');
    const fechaFinInput = document.getElementById('fechafin-fase');
    const usuarioResponsableInput = document.getElementById('usuariore-fase');
    const comentarioTextarea = document.getElementById('comentario-Fase');

    const btnGuardarEdicion = document.querySelector("#guardar-fase");
    const btnCancelarEdicion = document.querySelector("#cancelar-edicion");
    const btnFase = document.querySelector("#generar-reporte-F");

    const btnEditarF = document.querySelector("#editar-fase");
    const btnAFase = document.querySelector("#agregar-tarea");

    nombreFaseInput.readOnly = true;
    porcentajeFase.readOnly = true;
    fechaInicioInput.readOnly = true;
    fechaFinInput.readOnly = true;
    usuarioResponsableInput.readOnly = true;
    comentarioTextarea.readOnly = true;

    btnGuardarEdicion.classList.add("d-none");
    btnCancelarEdicion.classList.add("d-none");

    btnEditarF.classList.remove("d-none");
    btnAFase.classList.remove("d-none");
    btnFase.classList.remove("d-none");
    modalInfoFase(idfase);
  }

  function editarFase() {
    const nombreFase = document.querySelector('#nombre-Fase');
    const fechaInicioFase = document.querySelector('#fechainicio-fase');
    const fechaFinFase = document.querySelector('#fechafin-fase');
    const usuarioResponsableFase = document.querySelector('#usuariore-fase');
    const comentarioFase = document.querySelector('#comentario-Fase');
    const porcentajeFase = document.querySelector('#porcentaje-Fase');
    const porcentajeValor = porcentajeFase.value;

    if (!nombreFase.value || !usuarioResponsableFase.value || !fechaInicioFase.value || !fechaFinFase.value || !comentarioFase.value || !porcentajeFase.value) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos.',
      });
      return;
    }

    if (isNaN(porcentajeValor) || porcentajeValor < 0 || porcentajeValor > 100) {
      Swal.fire({
        icon: 'warning',
        title: 'Porcentaje excedido',
        text: 'Por favor, ingrese un porcentaje de 0 a 100.',
      });
    }

    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de los datos modificados?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        obtenerPorcentajeF();
        obtenerPorcentajeP();
        const parametros = new URLSearchParams();
        parametros.append("op", "editarFase");
        parametros.append("idfase", idfase);
        parametros.append("idresponsable", usuarioResponsableFase.value);
        parametros.append("nombrefase", nombreFase.value);
        parametros.append("fechainicio", fechaInicioFase.value);
        parametros.append("fechafin", fechaFinFase.value);
        parametros.append("comentario", comentarioFase.value);
        parametros.append("porcentaje", porcentajeFase.value);
  
        fetch('../controllers/fase.php', {
          method: 'POST',
          body: parametros
        })
          .then(respuesta => {
            if(respuesta.ok){
              Swal.fire({
                icon: 'success',
                title: 'Fase Actualizada',
                text: 'La fase ha sido actualizada correctamente.'
              }).then(() => {
                cancelarEdicion();
                modalInfoFase(idfase);
              });
            } else{
              throw new Error('Error en la solicitud');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.alert({
              icon: 'Error',
              title: 'Error al editar la fase',
              text: 'Ocurrió un error al actualizar la fase. Por favor intentelo nuevamente.'
            })
          });
      }
    })
  }

// Fin editar Fase
  
// Fin de info Fase

// Agregar Tarea

// Para abrir un miniModal de registro de tareas
  function openModalAgregarTarea(){
    const modalAgregarT = document.querySelector("#modal-agregar-t");
    const bootstrapModal = new bootstrap.Modal(modalAgregarT);

    function listarColaboradores_A(){
      const responsable = document.querySelector("#asignar-empleado");
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
        responsable.innerHTML = "";
        responsable.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }
    listarColaboradores_A();

    bootstrapModal.show();
  }

  function agregarTarea() {
    const idcolaboradores = document.querySelector("#asignar-empleado");
    const roles = document.querySelector("#rol-empleado");
    const tarea = document.querySelector("#tarea-agregar");
    const porcentaje = document.querySelector("#agregar-porcentaje");
    const fecha_inicio_tarea = document.querySelector("#fecha-ini-tarea");
    const fecha_fin_tarea = document.querySelector("#fecha-f-tarea");
    const porcentajeValor = porcentaje.value;
  
    if (!roles.value || !tarea.value || !fecha_inicio_tarea.value || !fecha_fin_tarea.value || !porcentaje.value) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos.',
      });
      return;
    }
  
    if (isNaN(porcentajeValor) || porcentajeValor < 0 || porcentajeValor > 100) {
      Swal.fire({
        icon: 'warning',
        title: 'Porcentaje excedido',
        text: 'Por favor, ingrese un porcentaje válido de 0 a 100.',
      });
      return;
    }
  
    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de los datos ingresados?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Creando tarea ...',
          allowOutsideClick: false,
          showCancelButton: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        const parametros = new URLSearchParams();
        parametros.append("op", "registrarTareaV2");
        parametros.append("idfase", idfase);
        parametros.append("idcolaboradores", idcolaboradores.value);
        parametros.append("roles", roles.value);
        parametros.append("tarea", tarea.value);
        parametros.append("porcentaje", porcentaje.value);
        parametros.append("fecha_inicio_tarea", fecha_inicio_tarea.value);
        parametros.append("fecha_fin_tarea", fecha_fin_tarea.value);
  
        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametros
        })
        .then(respuesta => {
          if (respuesta.ok) {
            obtenerPorcentajeF();
            obtenerPorcentajeP();
            Swal.fire({
              icon: 'success',
              title: 'Tarea registrada',
              text: 'La tarea se ha registrado correctamente.'
            }).then(() => {
              modalInfoFase(idfase);
            });
          } else {
            throw new Error('Error en la solicitud');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error al registrar la tarea',
            text: 'Ocurrió un error al registrar la tarea. Por favor, inténtelo nuevamente.'
          })
        });
      }
    })
  }

  function listarHabilidades() {
    const empleadoSelect = document.querySelector("#asignar-empleado");
    const rolSelect = document.querySelector("#rol-empleado");
    
    const parametros = new URLSearchParams();
    parametros.append("op", "listar_Habilidades");
    parametros.append("idcolaboradores", empleadoSelect.value);

    fetch('../controllers/tarea.php', {
      method: 'POST',
      body: parametros
    })
      .then(respuesta => {
        if (respuesta.ok) {
          return respuesta.text();
        } else {
          throw new Error('Error en la solicitud');
        }
      })
      .then(datos => {
        rolSelect.innerHTML = datos;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

// Fin agregar Tarea

// Modal de Tareas
  function modalInfoTarea(id) {
    const inputs = document.querySelector("#inputs-tarea");
    const modalInfoTarea = document.querySelector("#modal-info-tarea");
    const parametros = new URLSearchParams();
    parametros.append("op" ,"obtenerTarea");
    parametros.append("idtarea" , id);
    fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametros
      })
      .then(respuesta => {
        if (respuesta.ok) {
          return respuesta.text();
        } else {
          throw new Error('Error en la solicitud');
        }
      })
      .then(datos => {
        inputs.innerHTML = datos;
        const bootstrapModal = new bootstrap.Modal(modalInfoTarea);
        bootstrapModal.show();
        verEvidenciasTarear(id)
        idtarea = id;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function verEvidenciasTarear(id) {
  const tabla_Tareas = document.querySelector("#tabla-info-tarea");
  const parametros = new URLSearchParams();
  parametros.append("op", "verEvidenciasT");
  parametros.append("idtarea", id);
  fetch('../controllers/tarea.php', {
    method: 'POST',
    body: parametros
  })
  .then(respuesta => {
    if (respuesta.ok) {
      return respuesta.text();
    } else {
      throw new Error('Error en la solicitud');
    }
  })
  .then(datos => {
    tabla_Tareas.innerHTML = datos;
  })
  .catch(error => {
    console.error('Error:', error);
  });
  }

  function generarReporteT(){
    const parametros = new URLSearchParams();
    if(idtarea > 0) {
    parametros.append("idtarea", idtarea);
    window.open(`../reports/Tarea/reporte.php?${parametros}`, '_blank');
    }
  }
  
// Editar Tarea
  function quitarRead() {
    const nombreTarea = document.getElementById('nombre-tarea');
    const usuarioTarea = document.getElementById('usuario-tarea');
    const rolTarea = document.querySelector('#rol-tarea');
    const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
    const fechaFinTarea = document.getElementById('fecha-fin-tarea');
    const porcentajeTarea = document.getElementById('porcentaje-tarea');

    const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
    const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

    const btnEditarT = document.querySelector("#quitar-readonly");
    const btnRtarea = document.querySelector("#generar-reporteT");

    nombreTarea.readOnly = false;
    usuarioTarea.readOnly = false;
    fechaIniTarea.readOnly = false;
    rolTarea.readOnly = false;
    fechaFinTarea.readOnly = false;
    porcentajeTarea.readOnly = false;

    btnGuardarEdicion.classList.remove("d-none");
    btnCancelarEdicion.classList.remove("d-none");

    btnEditarT.classList.add("d-none");
    btnRtarea.classList.add("d-none");

    function listarColaboradores_A(){
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
        usuarioTarea.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }
    listarColaboradores_A();
    usuarioTarea.addEventListener("click", listarHabilidadesEditar);
  }

  function addRead() {
    const nombreTarea = document.getElementById('nombre-tarea');
    const usuarioTarea = document.getElementById('usuario-tarea');
    const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
    const fechaFinTarea = document.getElementById('fecha-fin-tarea');
    const rolTarea = document.querySelector('#rol-tarea');
    const porcentajeTarea = document.getElementById('porcentaje-tarea');

    const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
    const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

    const btnEditarT = document.querySelector("#quitar-readonly");
    const btnRtarea = document.querySelector("#generar-reporteT");

    nombreTarea.readOnly = true;
    usuarioTarea.readOnly = true;
    fechaIniTarea.readOnly = true;
    rolTarea.readOnly = true;
    fechaFinTarea.readOnly = true;
    porcentajeTarea.readOnly = true;

    btnGuardarEdicion.classList.add("d-none");
    btnCancelarEdicion.classList.add("d-none");

    btnEditarT.classList.remove("d-none");
    btnRtarea.classList.remove("d-none");
    modalInfoTarea(idtarea);
  }

  function listarHabilidadesEditar() {
    const usuarioTarea = document.getElementById('usuario-tarea');
    const rolTarea = document.querySelector('#rol-tarea');
    
    const parametros = new URLSearchParams();
    parametros.append("op", "listar_Habilidades");
    parametros.append("idcolaboradores", usuarioTarea.value);

    fetch('../controllers/tarea.php', {
      method: 'POST',
      body: parametros
    })
      .then(respuesta => {
        if (respuesta.ok) {
          return respuesta.text();
        } else {
          throw new Error('Error en la solicitud');
        }
      })
      .then(datos => {
        rolTarea.innerHTML = datos;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function editarTarea(){
    const nombreTarea = document.querySelector('#nombre-tarea');
    const usuarioTarea = document.querySelector('#usuario-tarea');
    const rolTarea = document.querySelector('#rol-tarea');
    const fechaIniTarea = document.querySelector('#fecha-inicio-tarea');
    const fechaFinTarea = document.querySelector('#fecha-fin-tarea');
    const porcentajeTarea = document.querySelector('#porcentaje-tarea');
  
    if (!nombreTarea.value || !usuarioTarea.value || !fechaIniTarea.value || !fechaFinTarea.value || !porcentajeTarea.value || !rolTarea.value) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor, completa todos los campos.',
      });
      return;
    }

    if (isNaN(porcentajeTarea.value) || porcentajeTarea.value < 0 || porcentajeTarea.value > 100) {
      Swal.fire({
        icon: 'warning',
        title: 'Porcentaje excedido',
        text: 'Por favor, ingrese un porcentaje de 0 a 100.',
      });
    }

    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de los datos modificados?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        const parametros = new URLSearchParams();
        parametros.append("op", "editarTarea");
        parametros.append("idtarea", idtarea);
        parametros.append("idcolaboradores", usuarioTarea.value);
        parametros.append("roles", rolTarea.value);
        parametros.append("tarea", nombreTarea.value);
        parametros.append("porcentaje", porcentajeTarea.value);
        parametros.append("fecha_inicio_tarea", fechaIniTarea.value);
        parametros.append("fecha_fin_tarea", fechaFinTarea.value);
    
      fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametros
      })
        .then(respuesta => {
          if(respuesta.ok){
            obtenerPorcentajeF();
            obtenerPorcentajeP();
            Swal.fire({
              icon: 'success',
              title: 'Tarea Actualizada',
              text: 'La tarea ha sido actualizada correctamente.'
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
            title: 'Error al editar la tarea',
            text: 'Ocurrió un actualizar la tarea. Por favor intentelo nuevamente.'
          })
        });
      }
    })
  }

// Fin de modal tareas

  // Aquí habre el modal de para agregar fase
  function addPhase(id) {
      const modal = document.querySelector("#modalFase");
      const tipoproyecto = document.querySelector("#tipoProyecto-phase");
      const titulo = document.querySelector("#titulo-phase");
      const empresa = document.querySelector("#idempresa-phase");
      const idproyectov = id;
    
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "get");
      parametrosURL.append("idproyecto", id);
    
      fetch('../controllers/proyecto.php', {
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
          titulo.value = datos.titulo;
          tipoproyecto.value = datos.idtipoproyecto;
          empresa.value = datos.idempresa;
          const btnPhase = document.querySelector("#create-phase");
          btnPhase.addEventListener("click", function () {
              createPhase(idproyectov); // Pasar el valor de idproyecto a la función update
          });
    
          const bootstrapModal = new bootstrap.Modal(modal);
          bootstrapModal.show();
        })
        .catch(error => {
          console.error('Error:', error);
        });
  }

  function listar(){
      const table = document.querySelector("#tabla-proyecto");
      const bodytable = table.querySelector("tbody");

      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listar");

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
          bodytable.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }

  function listarempresa(){
      const empresa = document.querySelector("#idempresa");
      const empresaupdate = document.querySelector("#idempresa-update");
      const empresasearch = document.querySelector("#idempresa-buscar");
      const empresaphase = document.querySelector("#idempresa-phase");

      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarempresa");

      fetch('../controllers/empresa.php',{
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
          empresa.innerHTML = datos;
          empresaupdate.innerHTML = datos;
          empresasearch.innerHTML = datos;
          empresaphase.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }

  function listartipoproyecto(){
      const tipoproyecto = document.querySelector("#tipoProyecto");
      const tipoproyectoupdate = document.querySelector("#tipoProyecto-update");
      const tipoproyectosearch = document.querySelector("#tipoProyecto-buscar");
      const tipoproyectophase = document.querySelector("#tipoProyecto-phase");

      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listartipoproyecto");

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
          tipoproyecto.innerHTML = datos;
          tipoproyectoupdate.innerHTML = datos;
          tipoproyectosearch.innerHTML = datos;
          tipoproyectophase.innerHTML = datos;
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }

  function listarColaboradores(){
      const responsable = document.querySelector("#responsible-phase");
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarColaborador");

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

  function registrar(){
      const idtipoproyecto = document.querySelector("#tipoProyecto");
      const idempresa = document.querySelector("#idempresa");
      const titulo = document.querySelector("#titulo");
      const descripcion = document.querySelector("#descripcion");
      const fechainicio = document.querySelector("#fecha-inicio");
      const fechafin = document.querySelector("#fecha-fin");
      const precio = document.querySelector("#precio");

      if (!idtipoproyecto.value || !idempresa.value || !titulo.value || !descripcion.value || !fechainicio.value || !fechafin.value || !precio.value) {
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
        text: '¿Está seguro de los datos ingresados?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
          const parametrosURL = new URLSearchParams();
          parametrosURL.append("op", "registrar");
          parametrosURL.append("idtipoproyecto", idtipoproyecto.value);
          parametrosURL.append("idempresa", idempresa.value);
          parametrosURL.append("titulo", titulo.value);
          parametrosURL.append("descripcion", descripcion.value);
          parametrosURL.append("fechainicio", fechainicio.value);
          parametrosURL.append("fechafin", fechafin.value);
          parametrosURL.append("precio", precio.value);

          fetch('../controllers/proyecto.php', {
              method: 'POST',
              body: parametrosURL
          })
          .then(respuesta =>{
              if(respuesta.ok){
                Swal.fire({
                  icon: 'success',
                  title: 'Proyecto registrado',
                  text: 'El proyecto se ha registrado correctamente.'
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
              title: 'Error al registrar el proyecto',
              text: 'Ocurrió un error al registrar el proyecto. Por favor intentelo nuevamente.'
            })
          });
        }
      })
      
  }

  function generarReporteP(idproyecto){
    const parametros = new URLSearchParams();
    if(idproyecto > 0) {
    parametros.append("idproyecto", idproyecto);
    window.open(`../reports/Proyecto/reporteP.php?${parametros}`, '_blank');
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

  function buscarProyecto() {
    const table = document.querySelector("#tabla-proyecto");
    const bodytable = table.querySelector("tbody");
    const tipoproyecto = document.querySelector("#tipoProyecto-buscar");
    const empresa = document.querySelector("#idempresa-buscar");
    const estadoP = document.querySelector("#estado-buscar");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "buscarProyecto");
    parametrosURL.append("idtipoproyecto", tipoproyecto.value);
    parametrosURL.append("idempresa", empresa.value);
    parametrosURL.append("estado", estadoP.value);

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
        bodytable.innerHTML = "";
        bodytable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
  }

  // Función para finalizar un proyecto manualmente
  function finalizarProyecto(id){
    Swal.fire({
      icon: 'question',
      title: 'Confirmación',
      text: '¿Está seguro de finalizar este proyecto?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op" ,"finalizar_proyecto");
        parametrosURL.append("idproyecto" ,id);
        fetch('../controllers/proyecto.php', {
          method: 'POST',
          body: parametrosURL
        })
        .then(respuesta =>{
            if(respuesta.ok){
              Swal.fire({
                icon: 'success',
                title: 'Proyecto Finalizado',
                text: 'El proyecto ha finalizado con éxito.'
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
            title: 'Error al finalizar el proyecto',
            text: 'Ocurrió un error al finalizar el proyecto. Por favor intentelo nuevamente.'
          })
        });
      }
    })
  }

  // Función para reactivar un proyecto manualmente
  function reactivarProyecto(id) {
    // Validamos la fecha de fin (si esta es mayor a la fecha actual se podrá realizar la reactivación)
    let fechafinP = 0;
    const parametos = new URLSearchParams();
    parametos.append('op', "get");
    parametos.append('idproyecto', id);
    fetch('../controllers/proyecto.php', {
      method: 'POST',
      body: parametos
    })
    .then(respuesta => {
      if (respuesta.ok) {
        return respuesta.json();
      } else {
        throw new Error('Error en la solicitud');
      }
    })
    .then(datos => {
        fechafinP = datos.fechafin;
        console.log(fechafinP);

      // Obtenemos la fecha actual
      const fechaActual = new Date();
    
      // Convertimos la fecha de fin a objeto Date
      const fechaFin = new Date(fechafinP);

      if (fechaFin > fechaActual){
        Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro de reactivar este proyecto?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        })
        .then((result) => {
          if (result.isConfirmed) {
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op" ,"reactivar_proyecto");
            parametrosURL.append("idproyecto" ,id);
            fetch('../controllers/proyecto.php', {
              method: 'POST',
              body: parametrosURL
            })
            .then(respuesta =>{
                if(respuesta.ok){
                  Swal.fire({
                    icon: 'success',
                    title: 'Proyecto Reactivado',
                    text: 'El proyecto ha reactivado con éxito.'
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
                title: 'Error al reactivar el proyecto',
                text: 'Ocurrió un error al reactivar el proyecto. Por favor intentelo nuevamente.'
              })
            });
          }
        })
      } else{
        Swal.fire({
          icon: 'warning',
          title: 'Fecha de cierre expirada',
          text: 'Por favor, cambia la fecha de cierre del proyecto para reactivarlo.',
        });
        return;
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });

  }

  // Función que se ejecute una vez al día,para finalizar los proyectos
  function ejecutarDiariamente() {
    const fechaActual = new Date();
    const horaEjecucion = new Date();
  
    // Establecer la hora de ejecución diaria
    horaEjecucion.setHours(0, 0, 0, 0); // Ejemplo: 00:00:00 (medianoche)
  
    // Calcular los milisegundos hasta la próxima ejecución
    let milisegundosHastaEjecucion = horaEjecucion.getTime() - fechaActual.getTime();
    if (milisegundosHastaEjecucion < 0) {
      // Si la hora de ejecución ya ha pasado hoy, establecer la hora de ejecución para mañana
      horaEjecucion.setDate(horaEjecucion.getDate() + 1);
      milisegundosHastaEjecucion = horaEjecucion.getTime() - fechaActual.getTime();
    }
  
    // Establecer el intervalo para la próxima ejecución
    setTimeout(() => {
      // Ejecutar el código correspondiente a tu controlador aquí
      const parametros = new URLSearchParams();
      parametros.append('op', 'finalizar_proyectoV2');
  
      fetch('../controllers/proyecto.php', {
        method: 'POST',
        body: parametros
      })
        .then(respuesta => {
          if (respuesta.ok) {
            console.log('Proyectos finalizados con éxito');
          } else {
            throw new Error('Error en la solicitud');
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
  
      // Volver a programar la próxima ejecución
      ejecutarDiariamente();
    }, milisegundosHastaEjecucion);
  }
  
  // Iniciar la ejecución diaria
  ejecutarDiariamente();
  

listarColaboradores();
listartipoproyecto();
listarempresa();
listar();

// *Para tareas
  // Para abrir un miniModal de registro de tareas
  const btnAgregarT = document.querySelector("#agregar-tarea");
  btnAgregarT.addEventListener("click", openModalAgregarTarea);

  const btnBuscarHabilidad = document.querySelector("#asignar-empleado");
  btnBuscarHabilidad.addEventListener("click", listarHabilidades);

  // Para registrar tareas en el miniModal
  const btnRegistrarTarea = document.querySelector("#registrar-tarea-v2");
  btnRegistrarTarea.addEventListener("click", agregarTarea);

  // Para quitar o agregar la clase d-none
  const btnEditarT = document.querySelector("#quitar-readonly");
  btnEditarT.addEventListener("click",quitarRead);

  const btnAddRead = document.querySelector("#cancelar-E-Tarea");
  btnAddRead.addEventListener("click",addRead);

  // Para editar una tarea 
  const btnEditarTarea = document.querySelector("#guardar-C-Tarea");
  btnEditarTarea.addEventListener("click", editarTarea);

  // Para generar un reporte de una tarea 
  const btnReporteT = document.querySelector("#generar-reporteT");
  btnReporteT.addEventListener("click", generarReporteT);

// *Para fases
  // Para quitar el readOnly de los inputs
  const btnQuitarOnly = document.querySelector("#editar-fase");
  btnQuitarOnly.addEventListener("click", quitarReadonly);

  // Para quitar los botones y agregar la clase d-none
  const btnCancelarEdicion = document.querySelector("#cancelar-edicion");
  btnCancelarEdicion.addEventListener("click", cancelarEdicion);

  // Para editar los datos de la fase
  const btnGuardarFase = document.querySelector("#guardar-fase");
  btnGuardarFase.addEventListener("click", editarFase);

  // Para generar reporte de la fase
  const btnReporteF = document.querySelector("#generar-reporte-F");
  btnReporteF.addEventListener("click", generarReporteF)



// * Para los proyecto
  // Para quitar el readOnly de los inputs de proyecto
  const btnquitarRO = document.querySelector("#editar-proyecto");
  btnquitarRO.addEventListener("click", quitarReadonlyP);

  // Para quitar el readOnly de los inputs de proyecto
  const btnCancelarP = document.querySelector("#cancelar-edicion-proyecto");
  btnCancelarP.addEventListener("click", cancelarEdicionP);

  // Para quitar el readOnly de los inputs de proyecto
  const btnEditarProyecto = document.querySelector("#guardar-proyecto");
  btnEditarProyecto.addEventListener("click", editarProyecto);

  // Abrir modal de registro de fase
  const btnAbrilModal = document.querySelector("#agregar-fase");
  btnAbrilModal.addEventListener("click", abriModal);

  // Para registrar fase
  const btnRFase = document.querySelector("#create-fase");
  btnRFase.addEventListener("click", agregarFase);

  const btnRegistrar = document.querySelector("#registrar-datos");
  btnRegistrar.addEventListener("click", registrar);


// Para buscar proyectos
  const btnBuscarP = document.querySelector("#buscar-proyecto");
  btnBuscarP.addEventListener("click", buscarProyecto);
