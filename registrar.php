<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Ususario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>

<div class="container col-xl-10 col-xxl-8 px-4 py-5">
  <div class="row align-items-center g-lg-5 py-5">
    <div class="col-lg-7 text-center text-lg-start">
      <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Registro de Usuario</h1>
      <p class="col-lg-10 fs-4">Por favor complete los siguientes datos para registrarse:</p>
    </div>
    <div class="col-md-10 mx-auto col-lg-5">
      <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" autocomplete="off">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="apellidos" placeholder="Apellidos">
          <label for="apellidos">Apellidos</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="nombres" placeholder="Nombres">
          <label for="nombres">Nombres</label>
        </div>
        <div class="mb-3">
          <select class="form-select" id="tipo_documento">
            <option selected disabled>Tipo de documento</option>
            <option value="DNI">DNI</option>
            <option value="Pasaporte">Pasaporte</option>
          </select>
        </div>
        <div class="form-floating mb-3">
          <input type="number" class="form-control" id="numero_documento" placeholder="Número de documento">
          <label for="numero_documento">Número de documento</label>
        </div>
        <div class="form-floating mb-3">
          <input type="tel" class="form-control" id="telefono" placeholder="Teléfono">
          <label for="telefono">Teléfono</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="direccion" placeholder="Dirección">
          <label for="direccion">Dirección</label>
        </div>
        <div class="form-floating mb-3">
          <input type="date" class="form-control" id="fechanac" placeholder="Fecha Nacimiento">
          <label for="fechanac">Fecha de Nacimiento</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="usuario" placeholder="vamas">
          <label for="floatingInput">Nombre de usuario</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="clave" placeholder="*******">
          <label for="floatingPassword">Contraseña</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="correo" placeholder="vamas@gmail.com">
          <label for="floatingInput">Gmail</label>
        </div>                  
        <button id="registrarColaborador" class="w-100 btn btn-lg btn-warning" type="button">Registrarme</button>

        <h6 class="text-center mt-3">O</h6>
        <a class="w-100 btn btn-lg btn-primary mt-2" href="index.php" type="button">Iniciar Sesión</a>
        <hr class="my-4">
        <h6 class="text-body-secondary text-center">Permisos reservados Vamas 2023</h6>
      </form>
    </div>
  </div>
</div>

<script >

document.addEventListener("DOMContentLoaded", () => {
  const apellidos = document.querySelector("#apellidos");
  const nombres = document.querySelector("#nombres");
  const tipo_documento = document.querySelector("#tipo_documento");
  const numero_documento = document.querySelector("#numero_documento");
  const telefono = document.querySelector("#telefono");
  const direccion = document.querySelector("#direccion");
  const fechanac = document.querySelector("#fechanac");
  const usuarioregis = document.querySelector("#usuario");
  const clave = document.querySelector("#clave");
  const correo = document.querySelector("#correo");
  const btnregistrar = document.querySelector("#registrarColaborador");

  let idpersona = 0;

  function registrarPersona() {
    const parametros = new URLSearchParams();
    parametros.append("op", "registrarPersona");
    parametros.append("apellidos", apellidos.value);
    parametros.append("nombres", nombres.value);
    parametros.append("tipodocumento", tipo_documento.value);
    parametros.append("nrodocumento", numero_documento.value);
    parametros.append("telefono", telefono.value);
    parametros.append("direccion", direccion.value);
    parametros.append("fechanac", fechanac.value);

    fetch('controllers/persona.php', {
        method: 'POST',
        body: parametros
      })
      .then(respuesta => {
        if (respuesta.ok) {
          alert("Persona registrada correctamente");
          ObtenerID();
        } else {
          throw new Error('Error en la solicitud');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function ObtenerID() {
    const parametros = new URLSearchParams();
    parametros.append("op", "getID");
    parametros.append("nrodocumento", numero_documento.value);

    fetch('controllers/persona.php', {
        method: 'POST',
        body: parametros
      })
      .then(respuesta => respuesta.json())
      .then(datos => {
        idpersona = datos.idpersona;
        if (idpersona !== 0) {
          registrarColaboradores(idpersona);
        } else {
          throw new Error('Error al obtener el ID de la persona');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function registrarColaboradores(idpersona) {
    const parametros = new URLSearchParams();
    parametros.append("op", "registrarColaborador");
    parametros.append("idpersona", idpersona);
    parametros.append("usuario", usuarioregis.value);
    parametros.append("correo", correo.value);
    parametros.append("clave", clave.value);

    fetch('controllers/colaboradores.php', {
        method: 'POST',
        body: parametros
      })
      .then(respuesta => {
        if (respuesta.ok) {
          alert("Usuario registrado correctamente");
          window.location.href='index.php'

        } else {
          throw new Error('Error en el registro del colaborador');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  btnregistrar.addEventListener("click", registrarPersona);
});

</script>
    
</body>
</html>