<?php
    session_start();    //Encabezado script PHP

   //La sesión contendrá datos del login en formato de arreglo
    $_SESSION["login"] = [];    
    
    require_once '../models/colaboradores.php';

    if (isset($_POST['op'])) {
        
        $colaborador = new Colaborador();

        if ($_POST['op'] == 'login'){
          //Buscamos al usuario a través de su nombre
          $datoObtenido = $colaborador->login($_POST['usuario']);
          //Arreglo que contiene datos de login
          $resultado = [
            "status"        => false,
            "idcolaboradores" => "",
            "usuario"       => "",
            "nivelacceso"   => "",
            "correo"        => "", 
            "mensaje"       => ""
          ];
          
          if ($datoObtenido){
            //Encontramos el registro
            $claveEncriptada = $datoObtenido['clave'];
            if (password_verify($_POST['clave'], $claveEncriptada)){
              //Clave correcta
              $resultado["status"] = true;
              $resultado["usuario"] = $datoObtenido["usuario"];
              $resultado["nivelacceso"] = $datoObtenido["nivelacceso"];
              $resultado["idcolaboradores"] = $datoObtenido["idcolaboradores"];
              $resultado["correo"] = $datoObtenido["correo"];
              $_SESSION['login'] = true;
              $_SESSION['idcolaboradores'] = $datoObtenido['idcolaboradores'];
              $_SESSION['usuario'] = $datoObtenido['usuario'];
              $_SESSION['nivelacceso'] = $datoObtenido['nivelacceso'];
              $_SESSION['correo'] = $datoObtenido['correo'];
            }else{
              //Clave incorrecta
              $resultado["mensaje"] = "Contraseña incorrecta";
            }
          }else{
            //Usuario no encontrado
            $resultado["mensaje"] = "No se encuentra el usuario";
          }
      
          //Actualizando la información en la variable de sesión
          $_SESSION["login"] = $resultado;
          
          //Enviando información de la sesión a la vista
          
          echo json_encode($resultado);
        }

        if ($_POST['op'] == 'registrarColaborador') {
            $datos = [
                "idpersona" => $_POST['idpersona'],
                "usuario" => $_POST['usuario'],
                "clave" => password_hash($_POST['clave'], PASSWORD_BCRYPT),
                "nivelacceso" => $_POST['nivelacceso']
            ];
            $colaborador->registarColaborador($datos);
        }

        if ($_POST['op'] == 'listarHabilidades') {
          $datos = $colaborador->listarHabilidades();
          echo json_encode($datos);
        }
    }

    if (isset($_GET['op']) == 'close-session'){
      session_destroy();
      session_unset();
      header("location:../");
    }

?>