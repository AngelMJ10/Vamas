function createPhase(idproyecto){
    const namephase = document.querySelector("#name-phase");
    const respnsible = document.querySelector("#responsible-phase");
    const fechainicio = document.querySelector("#fecha-inicio-phase");
    const fechafin = document.querySelector("#fecha-fin-phase");
    const comentario = document.querySelector("#comentario");

    const confirmacion = confirm("¿Estás seguro de los datos ingresados para la fase?");

    if (confirmacion) {
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "registerPhase");
        parametrosURL.append("idproyecto", idproyecto);
        parametrosURL.append("idresponsable", respnsible.value);
        parametrosURL.append("nombrefase", namephase.value);
        parametrosURL.append("fechainicio", fechainicio.value);
        parametrosURL.append("fechafin", fechafin.value);
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
    const body = document.querySelector("#body-info");
  
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
    const tipoproyectosearch = document.querySelector("#tipoProyecto-search");
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
    const responsible = document.querySelector("#responsible-phase");
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
        responsible.innerHTML = datos;
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
listartipoproyecto();
listarempresa();
listar();

const btnRegistrar = document.querySelector("#registrar-datos");
btnRegistrar.addEventListener("click", registrar);
