function getPhase(id) {
    const modal = document.querySelector("#modalPhase");
    const infoPhase = document.querySelector("#info-phase");
    const idproyecto = id; 
  
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "getPhase");
    parametrosURL.append("idproyecto", id);
  
    fetch('../controllers/fase.php', {
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
        infoPhase.innerHTML = datos;
  
      const bootstrapModal = new bootstrap.Modal(modal);
      bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function list(){
    const table = document.querySelector("#tabla-fase");
    const bodytable = table.querySelector("tbody");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "list");

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
        bodytable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function listProject(){
    const project = document.querySelector("#project-phase");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listProject");

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
        project.innerHTML = datos;
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

function createPhase(){
    const idproyecto = document.querySelector("#project-phase");
    const namephase = document.querySelector("#name-phase");
    const respnsible = document.querySelector("#responsible-phase");
    const fechainicio = document.querySelector("#fecha-inicio-phase");
    const fechafin = document.querySelector("#fecha-fin-phase");
    const comentario = document.querySelector("#comentario");

    const confirmacion = confirm("¿Estás seguro de los datos ingresados para la fase?");

    if (confirmacion) {
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "registerPhase");
        parametrosURL.append("idproyecto", idproyecto.value);
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
                alert('Fase registrada correctamente');
                location.reload();
            } else{
                alert('Error en la solicitud');
            }
        })
    }
}

const btnRegistrar = document.querySelector("#create-phase");
btnRegistrar.addEventListener("click", createPhase);


listarColaboradores();
listProject();
list();