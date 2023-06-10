document.addEventListener("DOMContentLoaded", () => {
  const botonIniciarSesion = document.querySelector("#acceder");
  const textPassword = document.querySelector("#clave");
  const usuario = document.querySelector("#email");

  function validarDatos(){

      const parametros = new URLSearchParams();
      parametros.append("op", "login")
      parametros.append("usuario", usuario.value)
      parametros.append("clave", textPassword.value)

      fetch(`./controllers/colaboradores.php`, {
      method: 'POST',
      body: parametros
      })
      .then(respuesta => respuesta.json())
      .then(datos => {
          if (!datos.status){
          alert(datos.mensaje);
          usuario.focus();
          }else{
              alert(`Bienvenido ${datos.usuario}`);
              window.location.href = './views/';
          }
      })
      .catch(error => {
          console.log(error);
      });
  }

  textPassword.addEventListener("keypress", (evt) => {
      if (evt.charCode == 13) validarDatos();
  });

  botonIniciarSesion.addEventListener("click", validarDatos);
  });