<?php
    session_start();
    require_once '../models/Tarea.php';
    require_once '../models/Mail.php';
    require '../vendor/autoload.php';


    if (isset($_POST['op'])) {

        $tarea = new Tarea();

        if ($_POST['op'] == 'list') {
            $idcolaboradores = $_SESSION['idcolaboradores'];
            $datos = $tarea->list($idcolaboradores);
            $contador = 1;
            foreach ($datos as $registro) {
                $porcentaje = $registro['porcentaje_tarea'];
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                }
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                echo "
                    <tr>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                        <td class='p-3' data-label='Incio de la fase'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fín de la fase'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Usuario'>{$registro['usuario']}</td>
                        <td class='p-3' data-label='Rol'>{$registro['roles']}</td>
                        <td class='p-3' data-label='Porcentaje de la fase'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button'title='Clic, para editar la tarea.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                <button type='button' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
                $contador++;
            }          
        }

        if ($_POST['op'] == 'getWork') {
            $idtarea  = $_POST['idtarea'];
            echo json_encode($tarea->getWork($idtarea));
        }

        if ($_POST['op'] == 'sendWork') {
            $idtarea = $_POST['idtarea'];
            $documento = "";
        
            // Verificar si el usuario envió el archivo
            if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
                // Carpeta
                $rutaDestino = '../views/evidencias/';
        
                // Fecha y hora
                $fechaActual = date("c");       //c = complete (FECHA + HORA)
        
                // Encriptado fecha y hora.pdf
                $nombreArchivo = sha1($fechaActual) . ".pdf";
        
                // Ruta final
                $rutaDestino .= $nombreArchivo;
        
                if (move_uploaded_file($_FILES['documento']['tmp_name'], $rutaDestino)) {
                    // Se logró subir el archivo
                    // Acciones por definir ...
                    $documento = $rutaDestino; // Guardar la ruta del documento en lugar del nombre del archivo
                } else {
                    // Error al mover el archivo
                    echo "Error al mover el archivo";
                    exit;
                }
            }
            $datos =$tarea->getWork($idtarea);
            $mensajeAdicional = "
                <h2>{$datos['nombrefase']}</h2>
                <p>Avance de trabajo</p>
                <p>{$datos['tarea']}</p>
                <hr>
                <h4>
            ";
            $mensaje = $_POST['mensaje'];
            $tarea->sendWork($mensaje, $documento, $idtarea);
            $mensaje = $mensajeAdicional . $mensaje. "</h4>";
        
            // Arreglo con datos a guardar en la tabla de recuperación
            $correo = $_POST['correo'];
        
            // Llamar al método sendWork
            
        
            // Enviando Correo
            sendEmail($correo, $documento, 'Avance de trabajo: ', $mensaje);
        }

        if ($_POST['op'] == 'listarCorreo') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listarCorreo();
            $etiqueta = "<option value='0'>Seleccione el usuario</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                $etiqueta = "<option value='{$registro['correo']}'>{$registro['usuario']}</option>";
                echo $etiqueta;
            }
        }

        if ($_POST['op'] == 'enviarTrabajo') {
            $idtarea = $_POST['idtarea'];
            $documento = "";

            // Verificar si el usuario envió el archivo
            if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
                // Carpeta
                $rutaDestino = '../views/evidencias/';
        
                // Fecha y hora
                $fechaActual = date("c");       //c = complete (FECHA + HORA)
        
                // Encriptado fecha y hora.pdf
                $nombreArchivo = sha1($fechaActual) . ".pdf";
        
                // Ruta final
                $rutaDestino .= $nombreArchivo;
        
                if (move_uploaded_file($_FILES['documento']['tmp_name'], $rutaDestino)) {
                    // Se logró subir el archivo
                    // Acciones por definir ...
                    $documento = $rutaDestino; // Guardar la ruta del documento en lugar del nombre del archivo
                } else {
                    // Error al mover el archivo
                    echo "Error al mover el archivo";
                    exit;
                }
            }

            $datos =$tarea->getWork($idtarea);
            $mensajeAdicional = "
                <h2>{$datos['nombrefase']}</h2>
                <p>Avance de trabajo</p>
                <p>{$datos['tarea']}</p>
                <hr>
                <h4>
            ";

            $mensaje = $_POST['mensaje'];
            $fecha = date('Y-m-d');
            $hora = date('h:i:s');
            $datos['porcentaje'];
            $porcentaje = $_POST['porcentaje'];
            $data = [
                "e_mensaje"   => $mensaje,
                "e_documento"   => $documento,
                "e_fecha"   => $fecha,
                "e_hora"   => $hora,
                "p_porcentaje"   => $porcentaje,
                "t_idtarea"   => $idtarea,
            ];
            $tarea->enviarTareas($data);
            $mensaje = $mensajeAdicional . $mensaje. "</h4>";
        
            // Arreglo con datos a guardar en la tabla de recuperación
            $correo = $_POST['correo'];

            // Enviando Correo
            sendEmail($correo, $documento, 'Avance de trabajo: ', $mensaje);
        }
        
        if ($_POST['op'] == 'obtenerID') {
            $idtarea  = $_POST['idtarea'];
            echo json_encode($tarea->obtenerID($idtarea));
        }
        
    }

?>