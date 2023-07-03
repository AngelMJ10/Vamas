function update(idempresa){
    const nombre = document.querySelector("#nombre-editar").value;
    const razonsocial = document.querySelector("#razonsocial-editar").value;
    const tipodocumento = document.querySelector("#tipodocumento-editar").value;
    const documento = document.querySelector("#documento-editar").value;
    const estado = document.querySelector("#estado-editar").value;
    
    if (!nombre || !razonsocial || !tipodocumento || !documento || !estado) {
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
    }).then((result) =>{
        if (result.isConfirmed) {
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op", "update");
            parametrosURL.append("nombre", nombre);
            parametrosURL.append("razonsocial", razonsocial);
            parametrosURL.append("tipodocumento", tipodocumento);
            parametrosURL.append("documento", documento);
            parametrosURL.append("estado", estado);
            parametrosURL.append("idempresa", idempresa);
            
            fetch('.././controllers/empresa.php', {
                method: 'POST',
                body: parametrosURL
            })
            .then(respuesta =>{
                if(respuesta.ok){
                    Swal.fire({
                        icon: 'success',
                        title: 'Empresa Actualizada',
                        text: 'La empresa ha sido actualizada.'
                    }).then(() => {
                    location.reload();
                    });
                } else{
                    throw new Error('Error en la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                  icon: 'error',
                  title: 'Error al actualizar la empresa',
                  text: 'Ocurrió un error al actualizar la empresa. Por favor, inténtelo nuevamente.'
                })
            });
        }
    })
    
}

function obtenerDatos(id) {
    const modal = document.querySelector("#modalEditar");
    const nombre = document.querySelector("#nombre-editar");
    const razonsocial = document.querySelector("#razonsocial-editar");
    const tipodocumento = document.querySelector("#tipodocumento-editar");
    const documento = document.querySelector("#documento-editar");
    const estado = document.querySelector("#estado-editar");
    const idempresa = id; 
  
    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "getDatos");
    parametrosURL.append("idempresa", id);
  
    fetch('../controllers/empresa.php', {
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
      nombre.value = datos.nombre;
      razonsocial.value = datos.razonsocial;
      tipodocumento.value = datos.tipodocumento;
      documento.value = datos.documento;
      estado.value = datos.estado;

      const btnEditar = document.querySelector("#editar-registro");
        btnEditar.addEventListener("click", function () {
            update(idempresa); // Pasar el valor de idempresa a la función update
        });

  
      const bootstrapModal = new bootstrap.Modal(modal);
      bootstrapModal.show();
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function listar(){
    const table = document.querySelector("#tabla-empresa");
    const bodytable = table.querySelector("tbody");

    const parametrosURL = new URLSearchParams();
    parametrosURL.append("op", "listar");

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
        bodytable.innerHTML = datos;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function registrar(){
    const nombre = document.querySelector("#nombre").value;
    const razonsocial = document.querySelector("#razonsocial").value;
    const tipodocumento = document.querySelector("#tipodocumento").value;
    const documento = document.querySelector("#documento").value;

    if (!nombre || !razonsocial || !tipodocumento || !documento) {
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
        parametrosURL.append("nombre", nombre);
        parametrosURL.append("razonsocial", razonsocial);
        parametrosURL.append("tipodocumento", tipodocumento);
        parametrosURL.append("documento", documento);

        fetch('.././controllers/empresa.php', {
            method: 'POST',
            body: parametrosURL
        })
        .then(respuesta =>{
            if(respuesta.ok){
                Swal.fire({
                    icon: 'success',
                    title: 'Empresa Registrada',
                    text: 'La empresa ha sido registrada correctamente.'
                }).then(() => {
                location.reload();
                });
            } else{
                throw new Error('Error en la solicitud');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error al registrar la empresa',
              text: 'Ocurrió un error al registrar la empresa. Por favor, inténtelo nuevamente.'
            })
        });
    } 
    })
    
}
const btnRegistrar = document.querySelector("#registrar-datos");
btnRegistrar.addEventListener("click", registrar);

listar();