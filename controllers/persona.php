<?php
    session_start();
    require_once '../models/Persona.php';

    if (isset($_POST['op'])) {

        $persona = new Persona();

        if ($_POST['op'] == 'listar') {
            $datos = $persona->listar();
            echo json_encode($datos);
        }
        
        if ($_POST['op'] == 'getDatos') {
            $idpersona = $_POST['idpersona'];
            $datos = $persona->getDatos($idpersona);
            echo json_encode($datos);
        }

        if ($_POST['op'] == 'registrarPersona') {
            $datos = [
                "apellidos" => $_POST['apellidos'],
                "nombres" => $_POST['nombres'],
                "tipodocumento" => $_POST['tipodocumento'],
                "nrodocumento" => $_POST['nrodocumento'],
                "telefono" => $_POST['telefono'],
                "direccion" => $_POST['direccion'],
                "fechanac" => $_POST['fechanac']
                
            ];
            $persona->registrarPersona($datos);
        }

        if ($_POST['op'] == 'getID') {
            $nrodocumento = $_POST['nrodocumento'];
            $datos = $persona->getID($nrodocumento);
            echo json_encode($datos);
        }

        // Listar colaboradores
          if ($_POST['op'] == 'listarTcolaboradores') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listar_t_Colaborador();
            $contador = 1;
            foreach ($datos as $datos) {
              $tbody = "
                <tr class='mb-2' title='Doble click para ver más información' ondblclick='obtenerInfo({$datos['idcolaboradores']})'>
                  <td class='p-3' data-label='#'>{$contador}</td>
                  <td class='p-3' data-label='Usuario'>{$datos['usuario']}</td>
                  <td class='p-3' data-label='Correo'>{$datos['correo']}</td>
                  <td class='p-3' data-label='Nivel'>{$datos['nivelacceso']}</td>
                  <td class='p-3' data-label='Apellidos'>{$datos['apellidos']}</td>
                  <td class='p-3' data-label='Nombres'>{$datos['nombres']}</td>
                  <td class='p-3' data-label='Habilidades'>{$datos['Habilidades']}</td>
                  <td class='p-3' data-label='Habilidades'>{$datos['Fases']}</td>
                  <td class='p-3' data-label='Tareas asig.'>{$datos['Tareas']}</td>
                  <td data-label='Acciones'>
                      <div class='btn-group' role='group'>
                          <button type='button' onclick='abrirModalH()' title='Clic, para editar la tarea.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                      </div>
                  </td>
                </tr>
              ";
              $contador++;
              echo $tbody;
            }
          }
  
          if ($_POST['op'] == 'infoColaboradores') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $idcolaboradores = ["idcolaboradores" => $_POST['idcolaboradores']];
            $datos = $colaborador->obtener_info_Colaborador($idcolaboradores);
            echo json_encode($datos);
          }
        // Editar persona y colaborador
          if ($_POST['op'] == 'editarPersona') {
            $datos = [
                "idpersona" => $_POST['idpersona'],
                "usuario" => $_POST['usuario'],
                "correo" => $_POST['correo'],
                "nivelacceso" => $_POST['nivelacceso'],
                "apellidos" => $_POST['apellidos'],
                "nombres" => $_POST['nombres'],
                "nrodocumento" => $_POST['nrodocumento'],
                "telefono" => $_POST['telefono']        
            ];
            $persona->editarCol_Per($datos);
        }

    }

?>