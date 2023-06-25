<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
  
  <div class="container">
    <div class="row mt-3">
      <div class="col-md-12">
        <form action="" autocomplete="off">
          <h3>Validar datos del usuario</h3>
          <hr>
          <div class='mb-3'>
            <label for="user" class='form-label'>Escriba su nombre de usuario: </label>
            <div class="input-group">
              <input type="text" class='form-control' id='user' autofocus>
              <button type="button" class="btn btn-outline-primary" id="buscar">Buscar</button>
            </div>
          </div>
          <!-- Respuestas -->
          <div class='mb-3 form-floating'>
            <input type="text" class='form-control' readonly id='datouser'>
            <label for="user" class='form-label'>Datos del usuario: </label>
          </div>

          <div class='mb-3 form-floating'>
            <input type="text" class='form-control' readonly id='email'>
            <label for="user" class='form-label'>Correo electronico: </label>
          </div>
          <button class="btn btn-outline-success" type="button" id="enviar">Enviar Código</button>
        </form>
      </div>
    </div>
  </div>

  <!-- ZONA MODALES -->
  <div class="modal fade" id="modal-validacion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success text-light">
          <h5 class="modal-title" id="modalTitleId">Validar código</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" id="form-clave" autocomplete="off">
            <div class="mb-3">
              <label for="clave" class=" text-center form-label">Escriba la clave enviada al correo</label>
              <input type="text" style="font-size: 4em;" maxlength="4" class="text-center form-control" id="clave">
            </div>
            <div id="inputs-clave" class="d-none">
              <div class="mb-3">
                <label for="clave1" class="form-label">Escribe tu nueva contraseña</label>
                <input id="clave1" type="password" class="form-control">
              </div>
              <div class="mb-3">
                <label for="clave2" class="form-label">Vuelva a ingresar su contraseña</label>
                <input id="clave2" type="password" class="form-control">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button id="comprobar" type="button" class="btn btn-sm btn-outline-primary">Comprobar</button>
          <button id="actualizar" type="button" class="btn btn-sm btn-outline-success d-none">Actualizar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- FIN MODALES -->

  <!-- Librerias JS Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      
      //Variable para almacenar IDUSUARIO
      let idusuario = -1;

      // Objeto para manipular el modal
      const modal = new bootstrap.Modal(document.querySelector("#modal-validacion"));

      function buscador(){
        const nombreUser = document.querySelector("#user")
        const dataUser = document.querySelector("#datouser")
        const email = document.querySelector("#email")

        let parametros = new URLSearchParams()
        parametros.set("op" , "searchUser")
        parametros.set("user" , nombreUser.value)

        fetch(`./controllers/colaboradores.php`, {
          method: 'POST',
          body: parametros
        })
          .then(respuesta => respuesta.text())
          .then(datos => {
            if (datos != "") {
              const registro = JSON.parse(datos)
              // Enviando datos
              idusuario = registro.idcolaboradores;
              dataUser.value = `${registro.apellidos} ${registro.nombres}`
              email.value = registro.correo 
            }else{
              idusuario = -1;
              dataUser.value = ''
              email.value = ''
              alert('Usuario no encontrado')
            }
          });
      }
      
      function generarEnviarCodigo(){
        const email = document.querySelector("#email");
        const parametros = new URLSearchParams();
        parametros.append("op", "sendEmail");
        parametros.append("correo", email.value);
        parametros.append("idcolaboradores", idusuario);
        fetch(`./controllers/colaboradores.php` ,{
          method: 'POST',
          body: parametros
        })
          .then(respuesta =>respuesta.json())
          .then(datos => {
            console.log(datos)
            alert(datos.mensaje);
          })
      }
      
      function validarClave(){
        const parametros = new URLSearchParams();
        const clave = document.querySelector("#clave")
        parametros.append("op", "validarClave")
        parametros.append("idcolaboradores", idusuario)
        parametros.append("clavegenerada", clave.value)
        fetch(`./controllers/colaboradores.php`, {
          method: 'POST',
          body: parametros
        })
          .then(respuesta => respuesta.json())
          .then(datos =>{
            console.log(datos)
            // Analizando los datos
            if (datos.status == "PERMITIDO") {
              document.querySelector("#inputs-clave").classList.remove("d-none")
              document.querySelector("#comprobar").classList.add("d-none")
              document.querySelector("#actualizar").classList.remove("d-none")
              document.querySelector("#clave1").focus();
            } else {
              alert("Clave incorrecta, revise su correo por favor")
            }
          })
      }

      function actualizaClave(){
        const clave1 = document.querySelector("#clave1").value;
        const clave2 = document.querySelector("#clave1").value;

        if (clave1 != "" && clave2 != "") {
          if (clave1 == clave2) {
            const parametros = new URLSearchParams();
            parametros.append("op", "actualizarClave");
            parametros.append("idcolaboradores", idusuario);
            parametros.append("clave", clave1);
            fetch(`./controllers/colaboradores.php`, {
              method: 'POST',
              body: parametros
            })
            .then(respuesta => respuesta.json())
            .then(datos => {
              if (datos.status) {
                alert("Se actualizó su clave correctamente.. vuelva a iniciar sesión");
                window.location.href = './index.php';
              }
            })
          }
        }
      }

      // Evento nativo del MODAL... "al abrir el modal"
      modal._element.addEventListener("shown.bs.modal", () => {
        document.querySelector("#clave").focus();
      });

      // Evento "al cerrar el modal"
      modal._element.addEventListener("hidden.bs.modal", () => {
        document.querySelector("#form-clave").reset();
      });

      // Evento clik para abrir el modal
      document.querySelector("#enviar").addEventListener("click", () => {
        if (idusuario != -1) {
          generarEnviarCodigo();
          modal.toggle(); 
        }else{
          alert("No hay un nombre de usuario, por favor ingrese uno.")
          document.querySelector("#user").focus();
        }
      })

      //Evento click para le botón
      document.querySelector("#buscar").addEventListener("click", buscador)
      document.querySelector("#comprobar").addEventListener("click", validarClave)
      document.querySelector("#actualizar").addEventListener("click",actualizaClave)
      // Evento keypress (enter) caja de texto
      document.querySelector("#user").addEventListener("keypress", (key) => {
        if (key.keycode == 13) {
          buscador();
        }
      })

    })
  </script>

</body>
</html>