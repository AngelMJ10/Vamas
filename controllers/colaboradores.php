<?php
    session_start();    //Encabezado script PHP

   //La sesión contendrá datos del login en formato de arreglo

    //La sesión contendrá datos del login en formato de arreglo
    $_SESSION["login"] = [];
    require_once '../models/Colaboradores.php';
    require_once '../models/Mailclave.php';

    if (isset($_POST['op'])) {
        
        $colaborador = new Colaborador();

        if ($_POST['op'] == 'login') {
        
          // Buscar al usuario a través de su nombre
          $datoObtenido = $colaborador->login($_POST['usuario']);
        
          // Arreglo que contiene datos de login
          $resultado = [
            "status" => false,
            "idcolaboradores" => "",
            "usuario" => "",
            "nivelacceso" => "",
            "correo" => "",
            "mensaje" => ""
          ];
        
          if ($datoObtenido) {
            // Encontramos el registro
            $claveEncriptada = $datoObtenido['clave'];
            if (password_verify($_POST['clave'], $claveEncriptada)) {
              // Clave correcta
              $resultado["status"] = true;
              $resultado['idcolaboradores'] = $datoObtenido['idcolaboradores'];
              $resultado["usuario"] = $datoObtenido["usuario"];
              $resultado["nivelacceso"] = $datoObtenido["nivelacceso"];
              $resultado["idcolaboradores"] = $datoObtenido["idcolaboradores"];
              $resultado["correo"] = $datoObtenido["correo"];
              
              $_SESSION['login'] = true;
              $_SESSION['idcolaboradores'] = $datoObtenido['idcolaboradores'];
              $_SESSION['usuario'] = $datoObtenido['usuario'];
              $_SESSION['nivelacceso'] = $datoObtenido['nivelacceso'];
              $_SESSION['correo'] = $datoObtenido['correo'];
            } else {
              // Clave incorrecta
              $resultado["mensaje"] = "Contraseña incorrecta";
            }
          } else {
            // Usuario no encontrado
            $resultado["mensaje"] = "No se encuentra el usuario";
          }
        
          // Actualizando la información en la variable de sesión
          $_SESSION["login"] = $resultado;
        
          // Enviando información de la sesión a la vista
          echo json_encode($resultado);
        }

        if ($_POST['op'] == 'registrarColaborador') {
            $claveEncriptada = password_hash($_POST['clave'], PASSWORD_BCRYPT);
            
            $datos = [
                "idpersona" => $_POST['idpersona'],
                "usuario" => $_POST['usuario'],
                "correo" => $_POST['correo'],
                "clave" => $claveEncriptada
            ];
            $colaborador->registrarColaborador($datos);
        }

        if ($_POST['op'] == 'listarHabilidades') {
          $datos = $colaborador->listarHabilidades();
          echo json_encode($datos);
        }

        if ($_POST['op'] == 'searchUser') {
          $datoObtenido = $colaborador->searchUser($_POST['user']);
          if ($datoObtenido) {
            echo json_encode($datoObtenido);
          }
        }

        if ($_POST['op'] == 'sendEmail') {

          // Validar que este proceso NO SE EJECUTE SINO HASTA DESPUES DE 15 MINUTOS
          $respuesta = $colaborador->validarTiempo(['idcolaboradores' => $_POST['idcolaboradores']]);
          $retorno = ["mensaje" => "Ya se envió una clave, revise su correo"];
      
          if ($respuesta["status"] == "GENERAR") {
            // Crear un valor aleatorio de 4 dígitos
            $valRandom = random_int(1000, 9999);
      
            // Cuerpo del mensaje
            $mensaje = "
              <h3>App Vamas</h3>
              <strong>Recuperación de cuenta</strong>
              <hr>
              <p>Estimado usuario, para recuperar el acceso, utilice el siguiente código</p>
              <h3>{$valRandom}</h3>
            ";
      
            // Arreglo con datos a guardar en la tabla de recuperación
            $data = [
              "idcolaboradores"       => $_POST['idcolaboradores'],
              "correo"           => $_POST['correo'],
              "clavegenerada"   => $valRandom
            ];
      
            // Creando registro
            $colaborador->restoure($data);
      
            //Enviando Correo
            enviarEmail($_POST['correo'], 'Código de restauración: ', $mensaje);
            $retorno["mensaje"] = "Se ha generado y enviado la clave al email indicado";
          }
          // Enviando el mensaje
          echo json_encode($retorno);
        }

        if ($_POST['op'] == 'validarClave') {
          $datos = [
            "idcolaboradores"       => $_POST['idcolaboradores'],
            "clavegenerada"   => $_POST['clavegenerada']
          ];
          $resultado = $colaborador->validarClave($datos);
          echo json_encode($resultado);
        }

        if ($_POST['op'] == 'validarTiempo') {
          $datos = [
            "idcolaboradores"       => $_POST['idcolaboradores']
          ];
          $resultado = $colaborador->validarTiempo($datos);
          echo json_encode($resultado);
        }

        if ($_POST['op'] == 'actualizarClave') {
          $claveEncriptada = password_hash($_POST['clave'], PASSWORD_BCRYPT);
          $idcolaboradores = $_POST['idcolaboradores'];
          $datos = [
            "idcolaboradores"     => $idcolaboradores,
            "clave"   => $claveEncriptada
          ];
          echo json_encode($colaborador->actualizarClave($datos));  
        }

        // Listar colaboradores
        if ($_POST['op'] == 'listarTcolaboradores') {
          $datos = $colaborador->listar_t_Colaborador();
          $contador = 1;
          foreach ($datos as $datos) {
            $tbody = "
              <tr class='mb-2' ondblclick='obtenerInfo({$datos['idcolaboradores']})'>
                <td class='p-3' data-label='#'>{$contador}</td>
                <td class='p-3' data-label='Usuario'>{$datos['usuario']}</td>
                <td class='p-3' data-label='Correo'>{$datos['correo']}</td>
                <td class='p-3' data-label='Nivel'>{$datos['nivelacceso']}</td>
                <td class='p-3' data-label='Apellidos'>{$datos['apellidos']}</td>
                <td class='p-3' data-label='Nombres'>{$datos['nombres']}</td>
                <td class='p-3' data-label='Habilidades'>{$datos['Habilidades']}</td>
                <td class='p-3' data-label='Tareas asig.'>{$datos['Tareas']}</td>
              </tr>
            ";
            $contador++;
            echo $tbody;
          }
        }

        if ($_POST['op'] == 'infoColaboradores') {
          $idcolaboradores = ["idcolaboradores" => $_POST['idcolaboradores']];
          $datos = $colaborador->obtener_info_Colaborador($idcolaboradores);
          echo json_encode($datos);
        }

    }

    if (isset($_GET['op']) == 'close-session'){
      session_destroy();
      session_unset();
      header("location:../");
    }

?>