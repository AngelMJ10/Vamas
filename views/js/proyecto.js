// ?Variables 
let idfase = 0;
let idproyecto = 0;

function createPhase(idproyecto){
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
        parametrosURL.append("idproyecto", idproyecto);
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
                alert('Fase registrado correctamente');
                location.reload();
            } else{
                alert('Error en la solicitud');
            }
        })
    }
}

function modalInfoFase(id) {
    const inputs = document.querySelector("#inputs-fase");
    const modalInfoFase = document.querySelector("#modal-info-fase");
    const parametros = new URLSearchParams();
    parametros.append("op" ,"infoFase");
    parametros.append("idfase" , id);
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
        inputs.innerHTML = datos;
        const bootstrapModal = new bootstrap.Modal(modalInfoFase);
        bootstrapModal.show();
        tabla_Fase(id);
        idfase = id;
      })
      .catch(error => {
        console.error('Error:', error);
      });
}

function tabla_Fase(id) {
    const tabla = document.querySelector("#tabla-info-fase");
    const parametros = new URLSearchParams();
    parametros.append("op" ,"tabla-fase");
    parametros.append("idfase" , id);
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
        tabla.innerHTML = datos;
      })
      .catch(error => {
        console.error('Error:', error);
      });
}

// Agregar Tarea

// Para abrir un miniModal de registro de tareas
function openModalAgregarTarea(){
  const modalAgregarT = document.querySelector("#modal-agregar-t");
  const bootstrapModal = new bootstrap.Modal(modalAgregarT);
  bootstrapModal.show();
}

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
    responsable.innerHTML = datos;
  })
  .catch(error => {
      console.error('Error:', error);
  });
}

function agregarTarea(){
  const idcolaboradores = document.querySelector("#asignar-empleado");
  const roles = document.querySelector("#rol-empleado");
  const tarea = document.querySelector("#tarea-agregar");
  const porcentaje = document.querySelector("#agregar-porcentaje");
  const fecha_inicio_tarea = document.querySelector("#fecha-inicio-tarea");
  const fecha_fin_tarea = document.querySelector("#fecha-fin-tarea");

  const parametros = new URLSearchParams();
  parametros.append("op", "registrarTarea");
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
      if(respuesta.ok){
        alert('Tarea registrada correctamente');
        location.reload();
      } else{
          alert('Error en la solicitud');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });

}
// Fin agregar Tarea

// Editar Fase

// Para quitar el readOnly de los inputs
function quitarReadonly() {
  const nombreFaseInput = document.getElementById('nombre-Fase');
  const porcentajeFase = document.getElementById('porcentaje-fase');
  const fechaInicioInput = document.getElementById('fechainicio-fase');
  const fechaFinInput = document.getElementById('fechafin-fase');
  const usuarioResponsableInput = document.getElementById('usuariore-fase');
  const comentarioTextarea = document.getElementById('comentario-fase');
  const btnGuardarEdicion = document.querySelector("#guardar-fase");
  const btnCancelarEdicion = document.querySelector("#cancelar-edicion");

  nombreFaseInput.readOnly = false;
  porcentajeFase.readOnly = false;
  fechaInicioInput.readOnly = false;
  fechaFinInput.readOnly = false;
  usuarioResponsableInput.readOnly = false;
  comentarioTextarea.readOnly = false;
  btnGuardarEdicion.classList.remove("d-none");
  btnCancelarEdicion.classList.remove("d-none");
  listarColaboradores();
}

function cancelarEdicion() {
  const nombreFaseInput = document.getElementById('nombre-Fase');
  const porcentajeFase = document.getElementById('porcentaje-fase');
  const fechaInicioInput = document.getElementById('fechainicio-fase');
  const fechaFinInput = document.getElementById('fechafin-fase');
  const usuarioResponsableInput = document.getElementById('usuariore-fase');
  const comentarioTextarea = document.getElementById('comentario-fase');
  const btnGuardarEdicion = document.querySelector("#guardar-fase");
  const btnCancelarEdicion = document.querySelector("#cancelar-edicion");

  nombreFaseInput.readOnly = true;
  porcentajeFase.readOnly = true;
  fechaInicioInput.readOnly = true;
  fechaFinInput.readOnly = true;
  usuarioResponsableInput.readOnly = true;
  comentarioTextarea.readOnly = true;
  btnGuardarEdicion.classList.add("d-none");
  btnCancelarEdicion.classList.add("d-none");
  modalInfoFase(idfase);
}

function editarFase() {
  const nombreFase = document.querySelector('#nombre-Fase');
  const fechaInicioFase = document.querySelector('#fechainicio-fase');
  const fechaFinFase = document.querySelector('#fechafin-fase');
  const usuarioResponsableFase = document.querySelector('#usuariore-fase');
  const comentarioFase = document.querySelector('#comentario-fase');
  const porcentajeFase = document.querySelector('#porcentaje-fase');

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
        alert('Fase Actualizada correctamente');
        location.reload();
      } else{
          alert('Error en la solicitud');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

// Fin editar Fase

// Editar Proyecto

function quitarReadonlyP() {
  const tipoproyecto_p = document.querySelector('#tipo_proyecto');
  const idempresa_p = document.querySelector('#id_empresa');
  const titulo_proyecto = document.querySelector('#titulo_proyecto');
  const porcentaje_p = document.querySelector('#porcentaje_proyecto');
  const descripcion_p = document.querySelector('#descripcion_proyecto');
  const fechainicio_p = document.querySelector('#fechainicio_proyecto');
  const fechafin_p = document.querySelector('#fechafin_proyecto');
  const precio_p = document.querySelector('#precio_proyecto');

  const btnEditar = document.querySelector('#guardar-proyecto');
  const btnCancelar = document.querySelector('#cancelar-edicion-proyecto');

  tipoproyecto_p.readOnly = false;
  idempresa_p.readOnly = false;
  titulo_proyecto.readOnly = false;
  porcentaje_p.readOnly = false;
  descripcion_p.readOnly = false;
  fechainicio_p.readOnly = false;
  fechafin_p.readOnly = false;
  precio_p.readOnly = false;

  btnEditar.classList.remove("d-none");
  btnCancelar.classList.remove("d-none");
  listartipoproyecto();
  listarempresa();
}

function cancelarEdicionP() {
  const tipoproyecto_p = document.querySelector('#tipo_proyecto');
  const idempresa_p = document.querySelector('#id_empresa');
  const titulo_proyecto = document.querySelector('#titulo_proyecto');
  const porcentaje_p = document.querySelector('#porcentaje_proyecto');
  const descripcion_p = document.querySelector('#descripcion_proyecto');
  const fechainicio_p = document.querySelector('#fechainicio_proyecto');
  const fechafin_p = document.querySelector('#fechafin_proyecto');
  const precio_p = document.querySelector('#precio_proyecto');

  const btnEditar = document.querySelector('#guardar-proyecto');
  const btnCancelar = document.querySelector('#cancelar-edicion-proyecto');

  tipoproyecto_p.readOnly = true;
  idempresa_p.readOnly = true;
  titulo_proyecto.readOnly = true;
  porcentaje_p.readOnly = true;
  descripcion_p.readOnly = true;
  fechainicio_p.readOnly = true;
  fechafin_p.readOnly = true;
  precio_p.readOnly = true;

  btnEditar.classList.add("d-none");
  btnCancelar.classList.add("d-none");
  info(id);
}

function editarProyecto(){
  const tipoproyecto_p = document.querySelector('#tipo_proyecto');
  const idempresa_p = document.querySelector('#id_empresa');
  const titulo_proyecto = document.querySelector('#titulo_proyecto');
  const porcentaje_p = document.querySelector('#porcentaje_proyecto');
  const descripcion_p = document.querySelector('#descripcion_proyecto');
  const fechainicio_p = document.querySelector('#fechainicio_proyecto');
  const fechafin_p = document.querySelector('#fechafin_proyecto');
  const precio_p = document.querySelector('#precio_proyecto');
  
  const parametros = new URLSearchParams();
  parametros.append("op", "editar");
  parametros.append("idproyecto", idproyecto);
  parametros.append("idtipoproyecto", tipoproyecto_p.value);
  parametros.append("idempresa", idempresa_p.value);
  parametros.append("titulo", titulo_proyecto.value);
  parametros.append("porcentaje", porcentaje_p.value);
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
        alert('Proyecto actualizado correctamente');
        location.reload();
      } else{
          alert('Error en la solicitud');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });

}

// Fin editar Proyecto

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

      const btnEditar = document.querySelector("#update-datos");
        btnEditar.addEventListener("click", function () {
            update(idproyecto); // Pasar el valor de idproyecto a la función update
        });

  
      const bootstrapModal = new bootstrap.Modal(modal);
      bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

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

function addPhase(id) {
    const modal = document.querySelector("#modalFase");
    const tipoproyecto = document.querySelector("#tipoProyecto-phase");
    const titulo = document.querySelector("#titulo-phase");
    const empresa = document.querySelector("#idempresa-phase");
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
        const btnPhase = document.querySelector("#create-phase");
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
    const empresasearch = document.querySelector("#idempresa-search");
    const empresaphase = document.querySelector("#idempresa-phase");
    const idempresa_p = document.querySelector('#id_empresa');

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
        idempresa_p.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function listartipoproyecto(){
    const tipoproyecto = document.querySelector("#tipoProyecto");
    const tipoproyectoupdate = document.querySelector("#tipoProyecto-update");
    const tipoproyectosearch = document.querySelector("#tipoProyecto-search");
    const tipoproyectophase = document.querySelector("#tipoProyecto-phase");
    const tipoproyecto_p = document.querySelector('#tipo_proyecto');

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
        tipoproyecto_p.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function listarColaboradores(){
    const responsable = document.querySelector("#responsible-phase");
    const usuario = document.querySelector("#usuariore-fase");
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
      usuario.innerHTML = datos;
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

    const confirmacion = confirm("¿Estás seguro de los datos ingresados?");

    if (confirmacion) {
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
                alert('Proyecto registrado correctamente');
                location.reload();
            } else{
                alert('Error en la solicitud');
            }
        })
    }
}

listarColaboradores();
listarColaboradores_A();
listartipoproyecto();
listarempresa();
listar();

// *Para tareas
  // Para abrir un miniModal de registro de tareas
  const btnAgregarT = document.querySelector("#agregar-tarea");
  btnAgregarT.addEventListener("click", openModalAgregarTarea);

  // Para registrar tareas en el miniModal
  const btnRegistrarTarea = document.querySelector("#registrar-tarea");
  btnRegistrarTarea.addEventListener("click", agregarTarea);

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

  const btnRegistrar = document.querySelector("#registrar-datos");
  btnRegistrar.addEventListener("click", registrar);
