// ?Variables 
let idfase = 0;
let idproyecto = 0;
let idtarea = 0;
let lienzo = document.querySelector("#grafico-proyecto");

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
  
  // Functión para abrir el modal de información del proyecto(el modal que solo es para editar no el que tienen varios modales)
  function get(id) {
    const modal = document.querySelector("#modalEditar");
    const titulo = document.querySelector("#titulo-update");
    const tipoproyecto = document.querySelector("#tipoProyecto-update");
    const empresa = document.querySelector("#idempresa-update");
    const descripcion = document.querySelector("#descripcion-update");
    const fechainicio = document.querySelector("#fecha-inicio-update");
    const fechafin = document.querySelector("#fecha-fin-update");
    const precio = document.querySelector("#precio-update");
    const usuario = document.querySelector("#user-create");
  
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

  // Función para editar el proyecto del modal de solo edición
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

  // Función para quitar la función readonly y poder editar el proyecto
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

  // Función para agregar de nuevo al funcion readonly y cancelar edición
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

  // Función para editar el proyecto
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

// Agregar fase V2

  // Función para abrir el modal para agregar una fase
  function abriModal() {
    const modalFase = document.querySelector("#modalFaseV2");
    const bootstrapModal = new bootstrap.Modal(modalFase);
    bootstrapModal.show();
    const tituloP = document.querySelector("#titulo-fase");
    const tipoproyectoP = document.querySelector("#tipoProyecto-fase");
    const empresaP = document.querySelector("#idempresa-fase");
    const fechainicio = document.querySelector("#fecha-inicio-faseV2");
    const fechafin = document.querySelector("#fecha-fin-faseV2");

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
        console.log(datos.fechafin);
        tituloP.value = datos.titulo;
        tipoproyectoP.value = datos.tipoproyecto;
        empresaP.value = datos.nombre;
        fechainicio.min = datos.fechainicio;
        fechainicio.max = datos.fechafin;
        fechafin.min = datos.fechainicio;
        fechafin.max = datos.fechafin;
        fechainicio.addEventListener("change", function() {
          fechafin.min = fechainicio.value;
        });
        function agregarLabels(){
          // Debajo de fecha inicio
          const fechaInicioProyecto = document.querySelector("#fecha-inicio-container");
          fechaInicioProyecto.textContent = '';
          const fechaIni = document.createElement("label");
          fechaIni.classList.add("form-label", "text-muted", "h6");
          fechaIni.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
          fechaInicioProyecto.appendChild(fechaIni);

          // Debajo de fecha Fin
          const fechaFinProyecto = document.querySelector("#fecha-fin-container");
          fechaFinProyecto.textContent = '';
          const fechaFinP = document.createElement("label");
          fechaFinP.classList.add("form-label", "text-muted", "h6");
          fechaFinP.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
          fechaFinProyecto.appendChild(fechaFinP);
        }
        agregarLabels();
      })
      .catch(error => {
        console.error('Error:', error);
      });

    // Agregamos los colaboradores al select
    function listarSupervisores() {
      const usuarioR = document.querySelector("#responsable-faseV2");
      const parametrosURL = new URLSearchParams();
      parametrosURL.append("op", "listarColaborador");

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
          usuarioR.innerHTML = datos;
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    listarSupervisores();
  }

  // Función para registrar la fase dentro del modal de proyectos
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

    // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
    if (fechainicio.value < fechainicio.min) {
      Swal.fire({
          icon: 'warning',
          title: 'Fecha de Inicio inválida',
          text: 'La fecha de inicio de la fase debe ser mayor o igual a la fecha de inicio del proyecto.',
      });
      return;
    }

    // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
    if (fechafin.value > fechafin.max) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha fin inválida',
            text: 'La fecha fin de la fase debe ser menor o igual a la fecha de cierre del proyecto.',
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

    if(fechainicio.value > fechafin.value){
      Swal.fire({
          icon: 'warning',
          title: 'Fechas inválidas',
          text: 'La fecha de inicio debe ser menor o igual a la fecha de fin.',
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

// Fin de agregar Fase

// Agregar fase (modal aparte)

  // Aquí habre el modal de para agregar una fase(es un modal aparte)
  function addPhase(id) {
    const modal = document.querySelector("#modalFase");
    const fechainicio = document.querySelector("#fecha-inicio-phase");
    const fechafin = document.querySelector("#fecha-fin-phase");
    const tipoproyecto = document.querySelector("#tipoProyecto-phase");
    const titulo = document.querySelector("#titulo-phase");
    const empresa = document.querySelector("#idempresa-phase");
    idproyecto = id;
  
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
        fechainicio.min = datos.fechainicio;
        fechainicio.max = datos.fechafin;
        fechafin.min = datos.fechainicio;
        fechafin.max = datos.fechafin;
        const btnPhase = document.querySelector("#create-phase");
        fechainicio.addEventListener("change", function() {
          fechafin.min = fechainicio.value;
        });
        function agregarLabels(){
          // Debajo de fecha inicio
          const fechaInicioProyecto = document.querySelector("#fecha-inicio-fasev1");
          fechaInicioProyecto.textContent = '';
          const fechaIni = document.createElement("label");
          fechaIni.classList.add("form-label", "text-muted", "h6");
          fechaIni.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
          fechaInicioProyecto.appendChild(fechaIni);

          // Debajo de fecha Fin
          const fechaFinProyecto = document.querySelector("#fecha-inicio-fasev2");
          fechaFinProyecto.textContent = '';
          const fechaFinP = document.createElement("label");
          fechaFinP.classList.add("form-label", "text-muted", "h6");
          fechaFinP.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
          fechaFinProyecto.appendChild(fechaFinP);
        }
        agregarLabels();
        btnPhase.addEventListener("click", function () {
            createPhase(idproyecto); // Pasar el valor de idproyecto a la función update
        });
  
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  // Función para registrar la fase(del modal aparte)
  function createPhase(){
      const namephase = document.querySelector("#name-phase");
      const responsable = document.querySelector("#responsible-phase");
      const fechainicio = document.querySelector("#fecha-inicio-phase");
      const fechafin = document.querySelector("#fecha-fin-phase");
      const comentario = document.querySelector("#comentario");
      const porcentaje = document.querySelector("#porcentaje");
      const porcentajeValor = porcentaje.value;

      if (!namephase.value || !responsable.value || !fechainicio.value || !fechafin.value || !comentario.value || !porcentaje.value ) {
        Swal.fire({
          icon: 'warning',
          title: 'Campos incompletos',
          text: 'Por favor, completa todos los campos.',
        });
        return;
      }
  
      // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
      if (fechainicio.value < fechainicio.min) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha de Inicio inválida',
            text: 'La fecha de inicio de la fase debe ser mayor o igual a la fecha de inicio del proyecto.',
        });
        return;
      }
  
      // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
      if (fechafin.value > fechafin.max) {
          Swal.fire({
              icon: 'warning',
              title: 'Fecha fin inválida',
              text: 'La fecha fin de la fase debe ser menor o igual a la fecha de cierre del proyecto.',
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
  
      if(fechainicio.value > fechafin.value){
        Swal.fire({
            icon: 'warning',
            title: 'Fechas inválidas',
            text: 'La fecha de inicio debe ser menor o igual a la fecha de fin.',
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
      })
      .then((result) => {
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

// Modal de info de fase (está con la tabla de tareas incluida)

  // Función para abrir modal de información de la fase
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
        // Function para validar las fechas de la fases
        function validarFechasF(){
          const fecha_inicio_fase = document.querySelector("#fechainicio-fase");
          const fecha_fin_fase = document.querySelector("#fechafin-fase");
          const parametosf = new URLSearchParams();
          parametosf.append("op", "get");
          parametosf.append("idproyecto", idproyecto);
          fetch('../controllers/proyecto.php', {
              method: 'POST',
              body: parametosf,
          })
          .then(respuesta => respuesta.json())
          .then(datos=> {
            fecha_inicio_fase.min = datos.fechainicio;
            fecha_inicio_fase.max = datos.fechafin;
            fecha_fin_fase.max = datos.fechafin;
            fecha_inicio_fase.addEventListener("change", function() {
              fecha_fin_fase.min = fecha_inicio_fase.value;
            });
            console.log(datos.fechafin);
          })
          .catch(error => {
              console.error('Error:', error);
          });
        }
        validarFechasF();
      })
      .catch(error => {
        console.error('Error:', error);
      });

  }

  // Función para generar un reporte de la fase
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

  function cerrarModalFase() {
    const modalInfoFase = document.querySelector("#modal-info-fase");
    const closeButton = modalInfoFase.querySelector(".closeP");
    closeButton.setAttribute("data-bs-dismiss", "modal");
    closeButton.click();
  }
  
  // Para buscar proyectos
  const btnModalP = document.querySelector("#modal-fase");
  btnModalP.addEventListener("click",cerrarModalFase);

  // Editar Fase

  // Para quitar el readonly de los inputs
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

  // Función cancelar la edición agregando los readonly
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

  // Función para editar la fase
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

    // En el caso que la fecha de inicio sea menor a la fecha de inicio del proyecto
    if (fechaInicioFase.value < fechaInicioFase.min) {
      Swal.fire({
          icon: 'warning',
          title: 'Fecha de Inicio inválida',
          text: 'La fecha de inicio de la fase debe ser mayor o igual a la fecha de inicio del proyecto.',
      });
      return;
    }

    // En el caso de la fecha de fin exceda el maximo de la fecha de fin del proyecto
    if (fechaFinFase.value > fechaFinFase.max) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha fin inválida',
            text: 'La fecha fin de la fase debe ser menor o igual a la fecha de cierre del proyecto.',
        });
        return;
    }

    // En el caso de la fecha de inicio sea mayo a la fecha de fin
    if(fechaInicioFase.value > fechaFinFase.value){
      Swal.fire({
          icon: 'warning',
          title: 'Fechas inválidas',
          text: 'La fecha de inicio debe ser menor o igual a la fecha de fin.',
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
    console.log(idfase);
    const modalAgregarT = document.querySelector("#modal-agregar-t");
    const bootstrapModal = new bootstrap.Modal(modalAgregarT);
    const fecha_inicio_tarea = document.querySelector("#fecha-ini-tarea");
    const fecha_fin_tarea = document.querySelector("#fecha-f-tarea");

    const parametos = new URLSearchParams();
    parametos.append("op", "obtenerFase");
    parametos.append("idfase", idfase);
    fetch('../controllers/fase.php', {
      method: 'POST',
      body: parametos,
    })
    .then(respuesta => respuesta.json())
    .then(datos=> {
      fecha_inicio_tarea.min = datos.fechainicio;
      fecha_inicio_tarea.max = datos.fechafin;
      fecha_fin_tarea.max = datos.fechafin;
      fecha_inicio_tarea.addEventListener("change", function() {
        fecha_fin_tarea.min = fecha_inicio_tarea.value;
      });
      function agregarLabelsT(){
        // Debajo de fecha inicio
        const fechaInicioProyecto = document.querySelector("#fecha-inicio-fase");
        fechaInicioProyecto.textContent = '';
        const fechaIni = document.createElement("label");
        fechaIni.classList.add("form-label", "text-muted", "h6");
        fechaIni.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
        fechaInicioProyecto.appendChild(fechaIni);

        // Debajo de fecha Fin
        const fechaFinProyecto = document.querySelector("#fecha-fin-fase");
        fechaFinProyecto.textContent = '';
        const fechaFinP = document.createElement("label");
        fechaFinP.classList.add("form-label", "text-muted", "h6");
        fechaFinP.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
        fechaFinProyecto.appendChild(fechaFinP);
      }
      agregarLabelsT();
    })
    .catch(error => {
      console.error('Error:', error);
    });

    
    

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

  // Función para registrar una tarea
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

    // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
    if (fecha_inicio_tarea.value < fecha_inicio_tarea.min) {
      Swal.fire({
          icon: 'warning',
          title: 'Fecha de Inicio inválida',
          text: 'La fecha de inicio de la tarea debe ser mayor o igual a la fecha de inicio de la fase.',
      });
      return;
    }

    // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
    if (fecha_fin_tarea.value > fecha_fin_tarea.max) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha fin inválida',
            text: 'La fecha fin de la tarea debe ser menor o igual a la fecha de cierre de la fase.',
        });
        return;
    }

    if(fecha_inicio_tarea.value > fecha_fin_tarea.value){
      Swal.fire({
          icon: 'warning',
          title: 'Fechas inválidas',
          text: 'La fecha de inicio debe ser menor o igual a la fecha de fin.',
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

  // Función para listar las habilidades del colaborador
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
  // Función para abrir el modal de información de la tarea con cajas de texto
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
        // Función para validar las fechas 
        function validarFechasT() {
          const fecha_inicio_tarea = document.querySelector("#fecha-inicio-tarea");
          const fecha_fin_tarea = document.querySelector("#fecha-fin-tarea");
          const parametos = new URLSearchParams();
          parametos.append("op", "obtenerFase");
          parametos.append("idfase", idfase);
          fetch('../controllers/fase.php', {
              method: 'POST',
              body: parametos,
          })
          .then(respuesta => respuesta.json())
          .then(datos=> {
            fecha_inicio_tarea.min = datos.fechainicio;
            fecha_inicio_tarea.max = datos.fechafin;
            fecha_fin_tarea.max = datos.fechafin;
            fecha_inicio_tarea.addEventListener("change", function() {
              fecha_fin_tarea.min = fecha_inicio_tarea.value;
            });
          })
          .catch(error => {
              console.error('Error:', error);
          });

          verEvidenciasTarear(id)
          idtarea = id;
        
        }
        validarFechasT();
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  // Función para obtener las evidencias enviadas de la tareas
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

  // Función para generar un reporte de la tarea
  function generarReporteT(){
    const parametros = new URLSearchParams();
    if(idtarea > 0) {
    parametros.append("idtarea", idtarea);
    window.open(`../reports/Tarea/reporte.php?${parametros}`, '_blank');
    }
  }

  function cerrarModaltarea() {
    const modalInfoTarea = document.querySelector("#modal-info-tarea");
    const closeButton = modalInfoTarea.querySelector(".closeT");
    closeButton.setAttribute("data-bs-dismiss", "modal");
    closeButton.click();
  }
  
  const btnCerarModalFase = document.querySelector("#fase-modal");
  btnCerarModalFase.addEventListener("click",cerrarModaltarea);


// Editar Tarea
  // Función para habilitar la edición
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

  // Función para cancelar la edición
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

  // Función para listar habilidades segun el colaborador seleccionado
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

  // Función para editar la tarea
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

    // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
    if (fechaIniTarea.value < fechaIniTarea.min) {
      Swal.fire({
          icon: 'warning',
          title: 'Fecha de Inicio inválida',
          text: 'La fecha de inicio de la tarea debe ser mayor o igual a la fecha de inicio de la fase.',
      });
      return;
    }

    // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
    if (fechaFinTarea.value > fechaFinTarea.max) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha fin inválida',
            text: 'La fecha fin de la tarea debe ser menor o igual a la fecha de cierre de la fase.',
        });
        return;
    }

    // En el caso de la fecha de inicio exceda el maximo de la fecha de fin de la fase
    if(fechaIniTarea.value > fechaFinTarea.value){
        Swal.fire({
            icon: 'warning',
            title: 'Fechas inválidas',
            text: 'La fecha de inicio debe ser menor o igual a la fecha de cierre.',
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

  // Función para listar los proyectos
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

  // Función listar las empresa en diferentes etiquetas select
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

  // Función para listar los tipos de proyectos en diferentes etiquetas select
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

  // Función para listar los colaboradores de ranggo S
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

  // Función para registrar un proyecto
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

  // Función un reporte del proyecto
  function generarReporteP(idproyecto){
    const parametros = new URLSearchParams();
    if(idproyecto > 0) {
    parametros.append("idproyecto", idproyecto);
    window.open(`../reports/Proyecto/reporteP.php?${parametros}`, '_blank');
    }
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

  // Función para obtener el porcentaje del proyecto
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

  // Función para buscar proyectos
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

  // Función para abrir el modal de graficos de proyecto

  function abrirGrafico(id){
    grafico.destroy();
    const modal = document.querySelector("#modal-grafico");
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    idproyecto = id;
    console.log(idproyecto)
    renderGrafico();
    graficosF(idproyecto);
  }

  function obtenerDatos() {
    const porcentajeP = document.querySelector("#porcentajeP");
    const parametros = new URLSearchParams();
    parametros.append('op', 'graficoP');
    parametros.append('idproyecto', idproyecto);
    fetch('../controllers/proyecto.php', {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        porcentajeP.innerHTML='';
        grafico.data.labels = datos.labels;
        grafico.data.datasets[0].data = datos.data;
        grafico.update();
        porcentajeP.innerHTML= datos.porcentajep[0];
    });
  }

  function getConfig(valMin, valMax, titulo) {
      const configuraciones = {
          responsive: true,
          legend: { position: 'bottom' },
          scales: {
            y: {
              min: valMin,
              max: valMax
            }
          },
          plugins: {
              title: {
                  display: true,
                  text: titulo
              },
              legend: { position: 'bottom' }
          }
      };
      return configuraciones;
  }

  function renderGrafico() {
      const etiquetas = [];
      const coloresFondo = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)']; // Colores de fondo para las barras (puedes personalizarlos)
      const borde = 1; // Ancho del borde de las barras
      const coloresBorde = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)']; // Colores de borde para las barras (puedes personalizarlos)

      grafico = new Chart(lienzo, {
          type: 'bar',
          data: {
              labels: etiquetas,
              datasets: [
                  {
                      label: 'Porcentaje de avance',
                      data: [], // Datos de ejemplo, serán reemplazados por los datos obtenidos de la operación
                      backgroundColor: coloresFondo,
                      borderWidth: borde,
                      borderColor: coloresBorde
                  }
              ]
          },
          options: getConfig(0, 100, 'Fases en proceso') // Rango de 1 a 12 para los meses
      });

      obtenerDatos();
  }

  let chartF = {};
  function obtenerDatosF(idfaseG, chart) {
    const parametros = new URLSearchParams();
    parametros.append('op', 'graficoF');
    parametros.append('idfase', idfaseG);
    fetch('../controllers/fase.php', {
        method: 'POST',
        body: parametros
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        // Actualizar el gráfico específico con sus datos
        chart.data.labels = datos.labels;
        chart.data.datasets[0].data = datos.data;
        chart.update();
    });
  }

  function getConfigF(valMin, valMax, titulo) {
      const configuraciones = {
          responsive: true,
          legend: { position: 'bottom' },
          scales: {
              y: {
              min: valMin,
              max: valMax
              }
          },
          plugins: {
              title: {
                  display: true,
                  text: titulo
              },
              legend: { position: 'bottom' }
          }
      };
      return configuraciones;
  }

  function renderGraficoF(graficoId,idfaseG) {
    const etiquetas = []; // Define tus etiquetas según tus datos
    const datos = []; // Define tus datos según tus datos

    const coloresFondo = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)']; // Define colores de fondo
    const coloresBorde = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)']; // Define colores de borde

    const ctx = document.getElementById(graficoId);
    console.log(graficoId)
    
    chartF = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [
                {
                    label: 'Avance',
                    data: datos,
                    backgroundColor: coloresFondo,
                    borderWidth: 1,
                    borderColor: coloresBorde
                }
            ]
        },
        options: getConfigF(0, 100, 'Tareas en procesor')
    });
    obtenerDatosF(idfaseG);
  }
  
  function graficosF(id) {
    const lienzoF = document.querySelector("#graficos-fases");
    const parametos = new URLSearchParams();
    parametos.append("op", "getDatos");
    parametos.append("idproyecto", id);
    fetch(`../controllers/proyecto.php`, {
        method: 'POST',
        body: parametos
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        let contador = 1;
        lienzoF.innerHTML = "";
        datos.forEach(element => {
            const graficoId = `graficoF_${element.idfase}`;
            const fasesContainer = document.createElement('div');
            fasesContainer.innerHTML = `
                <hr>
                <p>Fase N° ${contador}: <b>${element.nombrefase}</b></p>
                <p>Avance: <b>${element.porcentaje_fase} %</b></p>
                <div class='row'>
                    <div class='col-md-2'></div>
                    <div class='col-md-8'>
                        <canvas id='${graficoId}'></canvas>
                    </div>
                    <div class='col-md-2'></div>
                </div>
            `;
            lienzoF.appendChild(fasesContainer);
            
            // Inicializar un nuevo objeto Chart para cada gráfico
            const ctx = document.getElementById(graficoId);
            chartF[graficoId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Avance',
                            data: [],
                            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                            borderWidth: 1,
                            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)']
                        }
                    ]
                },
                options: getConfigF(0, 100, 'Tareas en proceso')
            });

            // Llamar a obtenerDatosF con el gráfico y el idfase correspondientes
            obtenerDatosF(element.idfase, chartF[graficoId]);
            contador++;
        });
    });
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

