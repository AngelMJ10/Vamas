<?php
    session_start();
    require_once '../models/Tarea.php';
    require_once '../models/Mail.php';
    require_once '../models/Mailclave.php';
    require '../vendor/autoload.php';


    if (isset($_POST['op'])) {

        $tarea = new Tarea();

        if ($_POST['op'] == 'list') {
            $nivel = $_SESSION['nivelacceso'];
            $datos = $tarea->list();
            $nombrecol = $_SESSION['usuario'];
            $contador = 1;
            // Función para imprimir la cabecera de la tabla dependiendo el nivel de acceso
            function tabla($nivel){
                $tabla = "";
                if($nivel == 'C'){
                    $tabla = 
                        "<thead>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Fase</th>
                            <th>Tarea</th>
                            <th>Duración</th>
                            <th>Usuario</th>
                            <th>Avance</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </thead>

                        <tbody>"
                    ;
                } else{
                    $tabla = 
                        "<thead>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Fase</th>
                            <th>Tarea</th>
                            <th>Duración</th>
                            <th>Usuario</th>
                            <th>Avance</th>
                            <th>Fase %</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </thead>

                        <tbody>"
                    ;
                }
                echo $tabla;
            }

            tabla($nivel);
            
            foreach ($datos as $registro) {
                $porcentajeTarea = $registro['porcentaje_tarea'];
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);

                // ? Se utiliza date(),para formatear las fechas a formato M j
                // ? strotime() se utiliza para convertir los valores pasados a un valor valido para la función date()
                $fechaInicio = date('M j', strtotime($registro['fecha_inicio_tarea']));
                $fechaFin = date('M j', strtotime($registro['fecha_fin_tarea']));

                if ($nivel == 'C'){
                    if($registro['usuario_tarea'] == $nombrecol){
                        if ($porcentajeTarea) {
                        $porcentajeTarea = rtrim($porcentajeTarea, "0");
                        $porcentajeTarea = rtrim($porcentajeTarea, ".");
                    }
                    $tbodyC= "
                        <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                            <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                            <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                            <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                            <td class='p-3' data-label='Usuario'>{$registro['usuario_tarea']}</td>
                            <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                    <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                    echo $tbodyC;
                    }

                } else {
                    $porcentaje = $registro['porcentaje'];
                    if ($porcentaje) {
                        $porcentajeTarea = rtrim($porcentajeTarea, "0");
                        $porcentajeTarea = rtrim($porcentajeTarea, ".");
                        $porcentaje = rtrim($porcentaje, "0");
                        $porcentaje = rtrim($porcentaje, ".");
                    }      
                    $tbodyA = "
                        <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                            <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                            <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                            <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                            <td class='p-3' data-label='Usuario'>{$registro['usuario_tarea']}</td>
                            <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                            <td class='p-3' data-label='Usuario'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                    <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                    echo $tbodyA;
                }
                $contador++;
            }

            if (empty($datos) && $nivel == 'C'){
                echo "
                    <tr>
                        <td colspan='9' class='text-center'>No tienes una tarea asignada.</td>
                    </tr>
                ";
            }
            
            $cierre = "</tbody>";
        }

        if ($_POST['op'] == 'buscar_tareas') {
            $idproyecto = $_POST['idproyecto'];
            $idfase = $_POST['idfase'];
            $nombretarea = $_POST['tarea'];
            
            $estado = $_POST['estado'];
            $nivel = $_SESSION['nivelacceso'];
            $nombrecol = $_SESSION['usuario'];

            if ($_SESSION['nivelacceso'] == 'C') {
                $idcolaboradorT = $_SESSION['idcolaboradores'];
            }else {
                $idcolaboradorT = $_POST['idcolaboradorT'];
            }
            
            $datos = $tarea->buscar_tareas([
                "idproyecto" => $idproyecto,
                "idfase" => $idfase,
                "tarea" => $nombretarea,
                "idcolaboradorT" => $idcolaboradorT,
                "estado" => $estado
            ]);
            $contador = 1;
            
            foreach ($datos as $registro) {
                $porcentajeTarea = $registro['porcentaje_tarea'];
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);

                // ? Se utiliza date(),para formatear las fechas a formato M j
                // ? strotime() se utiliza para convertir los valores pasados a un valor valido para la función date()
                $fechaInicio = date('M j', strtotime($registro['fecha_inicio_tarea']));
                $fechaFin = date('M j', strtotime($registro['fecha_fin_tarea']));

                if ($nivel == 'C'){
                    if($nombrecol == $registro['usuario_tarea']){
                        if ($porcentajeTarea) {
                            $porcentajeTarea = rtrim($porcentajeTarea, "0");
                            $porcentajeTarea = rtrim($porcentajeTarea, ".");
                        }
                        if ($estado == 'Finalizado') {
                            $tbodyC= "
                                <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                                    <td class='p-3' data-label='#'>{$contador}</td>
                                    <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                                    <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                                    <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                                    <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                                    <td class='p-3' data-label='Usuarios'>{$registro['usuario_tarea']}</td>
                                    <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                                    <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                                    <td data-label='Acciones'>
                                        <div class='btn-group' role='group'>
                                        <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        } else {
                            $tbodyC= "
                                <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                                    <td class='p-3' data-label='#'>{$contador}</td>
                                    <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                                    <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                                    <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                                    <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                                    <td class='p-3' data-label='Usuarios'>{$registro['usuario_tarea']}</td>
                                    <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                                    <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                                    <td data-label='Acciones'>
                                        <div class='btn-group' role='group'>
                                            <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                            <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                        echo $tbodyC;
                    }
                    
                } else {
                    $porcentaje = $registro['porcentaje'];
                    if ($porcentaje) {
                        $porcentajeTarea = rtrim($porcentajeTarea, "0");
                        $porcentajeTarea = rtrim($porcentajeTarea, ".");
                        $porcentaje = rtrim($porcentaje, "0");
                        $porcentaje = rtrim($porcentaje, ".");
                    }
                    if ($estado == 'Finalizado') {
                        $tbodyA = "
                            <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                                <td class='p-3' data-label='#'>{$contador}</td>
                                <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                                <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                                <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                                <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                                <td class='p-3' data-label='Usuario'>{$registro['usuario_tarea']}</td>
                                <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                                <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                                <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                                <td data-label='Acciones'>
                                    <div class='btn-group' role='group'>
                                        <button type='button' onclick='reactivarTarea({$registro['idtarea']})' class='btn btn-outline-success btn-sm' title='Clic, para ver reactivar tarea.'><i class='fa-solid fa-arrows-rotate'></i></button>
                                        <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                        <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                    </div>
                                </td>
                            </tr>
                        ";
                        echo $tbodyA;
                    }else{
                        $tbodyA = "
                            <tr ondblclick='obtenerInfo({$registro['idtarea']})'>
                                <td class='p-3' data-label='#'>{$contador}</td>
                                <td class='p-3' data-label='Titulo del Proyecto'>{$registro['titulo']}</td>
                                <td class='p-3' data-label='Fase'>{$registro['nombrefase']}</td>
                                <td class='p-3' data-label='Fase'>{$registro['tarea']}</td>
                                <td class='p-3' data-label='Incio de la fase'>{$fechaInicio} / {$fechaFin}</td>
                                <td class='p-3' data-label='Usuario'>{$registro['usuario_tarea']}</td>
                                <td class='p-3' data-label='Porcentaje de la fase'>{$porcentajeTarea}%</td>
                                <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                                <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>{$estado}</td>
                                <td data-label='Acciones'>
                                    <div class='btn-group' role='group'>
                                        <button type='button' onclick='finalizarTarea({$registro['idtarea']})' class='btn btn-outline-primary btn-sm' title='Clic, para finalizar la tarea.'><i class='fa-solid fa-check'></i></button>
                                        <button type='button' onclick='openModal({$registro['idtarea']})' data-id='{$registro['idtarea']}' class='btn btn-outline-primary btn-sm' title='Clic, para enviar el trabajo'><i class='fas fa-paper-plane'></i></button>
                                        <button type='button' onclick='generarReporteV({$registro['idtarea']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                    </div>
                                </td>
                            </tr>
                        ";
                        echo $tbodyA;
                    }
                }
                $contador++;
            }

            if (empty($tbodyC) && $nivel == 'C'){
                echo "
                    <tr>
                        <td colspan='9' class='text-center'>No se encontró la tarea.</td>
                    </tr>
                ";
            }
            
            $cierre = "</tbody>";
        }

        if ($_POST['op'] == 'listar_Habilidades') {
            require_once '../models/Colaboradores.php';
            $colaboradores = new Colaborador();
            $datos = [
                "idcolaboradores"                => $_POST['idcolaboradores']
            ];
            echo ($etiqueta = "<option value=''>Seleccione la habilidades</option>");
            $datas_H = $colaboradores->listar_Habilidades($datos);
            foreach ($datas_H as $registro) {
                echo "<option value='{$registro['habilidad']}'>{$registro['habilidad']}</option>";
            }
        }

        if ($_POST['op'] == 'registrarTarea') {
            $datos = [
                "idfase"                => $_POST['idfase'],
                "idcolaboradores"       => $_POST['idcolaboradores'],
                "roles"                 => $_POST['roles'],
                "tarea"                 => $_POST['tarea'],
                "porcentaje"            => $_POST['porcentaje'],
                "fecha_inicio_tarea"    => $_POST['fecha_inicio_tarea'],
                "fecha_fin_tarea"       => $_POST['fecha_fin_tarea']
            ];
            $tarea->registrarTarea($datos);
        }

        // Registrar Tarea y tambien se le envia un correo con la información
        if ($_POST['op'] == 'registrarTareaV2') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = [
                "idfase"                => $_POST['idfase'],
                "idcolaboradores"       => $_POST['idcolaboradores'],
                "roles"                 => $_POST['roles'],
                "tarea"                 => $_POST['tarea'],
                "porcentaje"            => $_POST['porcentaje'],
                "fecha_inicio_tarea"    => $_POST['fecha_inicio_tarea'],
                "fecha_fin_tarea"       => $_POST['fecha_fin_tarea']
            ];
            $tarea->registrarTarea($datos);
            $getColabolador = $colaborador->obtener_info_Colaborador(['idcolaboradores' => $_POST['idcolaboradores']]);
            $mensaje = "
                <h3>App Vamas</h3>
                <strong>Se le ha asignado una nueva tarea</strong>
                <hr>
                <p>El colabolador {$_SESSION['usuario']} le ha asignado la siguiente tarea:</p>
                <b>{$_POST['tarea']}</b>
                <p>La tarea inicia: {$_POST['fecha_inicio_tarea']}</p>
                <p>La tarea finaliza: {$_POST['fecha_fin_tarea']}</p>
            ";
            
            enviarEmail($getColabolador['correo'],'Nueva tarea asignada',$mensaje);
        }

        if ($_POST['op'] == 'editarTarea') {
            $datos = [
                "idtarea"                => $_POST['idtarea'],
                "idcolaboradores"       => $_POST['idcolaboradores'],
                "roles"                 => $_POST['roles'],
                "tarea"                 => $_POST['tarea'],
                "porcentaje"            => $_POST['porcentaje'],
                "fecha_inicio_tarea"    => $_POST['fecha_inicio_tarea'],
                "fecha_fin_tarea"       => $_POST['fecha_fin_tarea']
            ];
            $tarea->editarTarea($datos);
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
            $etiqueta = "<option value=''>Seleccione el usuario</option>";
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

        if ($_POST['op'] == 'verEvidencias') {
            $data = ["idtarea" => $_POST['idtarea']];
            $evidencias = $tarea->verEvidencias($data);
            $contador = 1;
            if (empty($evidencias) || $evidencias[0]['evidencia'] == '[]') {
                echo "
                    <tr>
                        <td colspan='9' class='text-center'>No se han enviado avances.</td>
                    </tr>
                ";
            } else {
                foreach ($evidencias as $evidencia) {
                    $evidenciaArray = json_decode($evidencia['evidencia'], true);
        
                    foreach ($evidenciaArray as $item) {
                        $tbody = "
                            <tr>
                                <td>{$contador}</td>
                                <td>{$item['colaborador']}</td>
                                <td>{$item['receptor']}</td>
                                <td>{$item['mensaje']}</td>
                                <td><a href='{$item['documento']}' target='_blank'>Enlace al documento</a></td>
                                <td>{$item['fecha']}</td>
                                <td>{$item['hora']}</td>
                                <td>{$item['porcentaje']}%</td>
                            </tr>
                        ";
                        echo $tbody;
                        $contador++;
                    }
                }
            }
        }

        if ($_POST['op'] == 'verEvidenciasT') {
            $data = ["idtarea" => $_POST['idtarea']];
            $evidencias = $tarea->verEvidencias($data);
            $inicioT="
                <table class='table table-hover'>
                <thead>
                <th>Emisor</th>
                <th>Mensaje</th>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Porcentaje</th>
                </thead>
                <tbody>
            ";
            echo $inicioT;
        
            if (empty($evidencias) || $evidencias[0]['evidencia'] == '[]') {
                echo "
                    <tr>
                        <td colspan='9' class='text-center'>No se han enviado avances.</td>
                    </tr>
                ";
            } else {
                foreach ($evidencias as $evidencia) {
                    $evidenciaArray = json_decode($evidencia['evidencia'], true);
        
                    foreach ($evidenciaArray as $item) {
                        $tbody = "
                            <tr>
                                <td>{$item['colaborador']}</td>
                                <td>{$item['mensaje']}</td>
                                <td><a href='{$item['documento']}' target='_blank'>Enlace al documento</a></td>
                                <td>{$item['fecha']}</td>
                                <td>{$item['porcentaje']}%</td>
                            </tr>
                        ";
                        echo $tbody;
                    }
                }
            }
            echo "</tbody>
            </table>";
        }
        
        if ($_POST['op'] == 'obtenerID') {
            $idtarea  = $_POST['idtarea'];
            echo json_encode($tarea->obtenerID($idtarea));
        }

        if ($_POST['op'] == 'obtenerTarea') {
            $idtarea = $_POST['idtarea'];
            $datos = $tarea->getWork($idtarea);
            function vista($datos){
                $porcentaje = $datos['porcentaje'];
                $porcentaje_tarea = $datos['porcentaje_tarea'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                    $porcentaje_tarea = rtrim($porcentaje_tarea, "0");
                    $porcentaje_tarea = rtrim($porcentaje_tarea, ".");
                } elseif ($porcentaje == null){
                    $porcentaje = 0;
                }
                $inputs= "
                    <form>
                        <div class='row mb-2 mt-2'>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <input type='text' id='nombre-tarea' readonly class='form-control' value='{$datos['tarea']}' placeholder='Tarea encargada' name='tarea'>
                                    <label for='tarea' class='form-label'>Tarea encargada</label>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <select name='tipoproyecto' id='usuario-tarea' class='form-control' readonly>
                                        <option value='{$datos['idcolaboradores_t']}'>{$datos['usuario_tarea']}</option>
                                    </select>
                                <label for='usu-tarea' class='form-label'>Usuario asignado</label>
                            </div>
                        
                            </div>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <input type='date' class='form-control' id='fecha-inicio-tarea' readonly placeholder='Inicio de la Tarea' value='{$datos['fecha_inicio_tarea']}' name='fechaini'>
                                    <label for='fechaini' class='form-label'>Fecha de Inicio</label>
                                </div>
                                <label class='form-label text-muted h6'>Duración de la fase: {$datos['fechainicio']} - {$datos['fechafin']}</label>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <input type='date' class='form-control' id='fecha-fin-tarea' readonly placeholder='Fin de la Tarea' value='{$datos['fecha_fin_tarea']}' name='fechafin'>
                                    <label for='fechafin' class='form-label'>Fecha de Cierre</label>
                                </div>
                                <label class='form-label text-muted h6'>Duración de la fase: {$datos['fechainicio']} - {$datos['fechafin']}</label>
                            </div>
                        </div>
                        <div class='row mb-2 mt-2'>                       
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <input type='number' class='form-control' readonly value='{$porcentaje_tarea}' placeholder='Porcentaje de avance' name='porcentaje-t'>
                                    <label for='porcentaje-t' class='form-label'>Porcentaje de avance %</label>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <input type='number' id='porcentaje-tarea' class='form-control' readonly value='{$porcentaje}' placeholder='Porcentaje en la fase' name='porcentaje'>
                                    <label for='porcentaje' class='form-label'>Porcentaje en la fase %</label>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-floating mb-3'>
                                    <select name='tipoproyecto' id='rol-tarea' class='form-control' readonly>
                                        <option value='{$datos['roles']}'>{$datos['roles']}</option>
                                    </select>
                                <label for='usu-tarea' class='form-label'>Rol</label>
                            </div>
                        </div>
                    </form>
                ";
                echo $inputs;
            }
            vista($datos);
        }

        if ($_POST['op'] == 'finalizar_tarea') {
            $tarea->finalizar_tarea();
        }

        if ($_POST['op'] == 'finalizar_tarea_by_id') {
            $tarea->finalizar_tarea_by_id(["idtarea" => $_POST['idtarea']]);
        }

        if ($_POST['op'] == 'reactivar_tarea') {
            $tarea->reactivar_tarea();
        }

        if ($_POST['op'] == 'reactivar_tarea_by_id') {
            $tarea->reactivar_tarea_by_id(["idtarea" => $_POST['idtarea']]);
        }
        
    }

?>