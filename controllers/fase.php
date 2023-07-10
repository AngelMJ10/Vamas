<?php
    session_start();
    require_once '../models/Fase.php';
    require_once '../models/Mailclave.php';

    if (isset($_POST['op'])) {

        $fase = new Fase();

        if ($_POST['op'] == 'list') {
            $datos = $fase->list();
            $contador = 1; // Variable contador inicializada en 1
            
            foreach ($datos as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                $porcentaje = $registro['porcentaje_fase'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null){
                    $porcentaje = 0;
                }
                echo "
                    <tr class='mb-2' ondblclick='getPhase({$registro['idfase']})'>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                        <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                        <td class='p-3' data-label='Responsable'>{$registro['usuario']}</td>
                        <td class='p-3' data-label='Inicio de la Fase'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fin del Fase'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button' title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' data-id='{$registro['idproyecto']}' class='btn btn-outline-primary btn-sm' title='Clic, para más información'><i class='fa-sharp fa-solid fa-circle-info'></i></button>
                                <button type='button' onclick='generarReporteF({$registro['idfase']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
                
                $contador++;
            }
        }

        if ($_POST['op'] == 'listarFases') {
            $datos = $fase->list();
            $etiqueta = "<option value=''>Seleccione la fase</option>";
            echo $etiqueta;
            if ($_SESSION['nivelacceso'] != 'C') {
                foreach ($datos as $registro){
               
                    $etiqueta ="<option value='{$registro['idfase']}'>{$registro['nombrefase']}</option>";
                    echo $etiqueta;
                }
            } else {
                $datosF = $fase->listar_Fase_Colaborador(["idcolaboradores" => $_SESSION['idcolaboradores']]);
                foreach ($datosF as $registro){
                    $etiqueta ="<option value='{$registro['idfase']}'>{$registro['nombrefase']}</option>";
                    echo $etiqueta;
                }
            }
        }

        if ($_POST['op'] == 'listarFasesV2') {
            $datos = $fase->getFases_by_P($_POST['idproyecto']);
            $etiqueta = "<option value=''>Seleccione la fase</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
               
                $etiqueta2 ="<option value='{$registro['idfase']}'>{$registro['nombrefase']}</option>";
                echo $etiqueta2;
            }
            
        }

        if ($_POST['op'] == 'listarFasesV3') {
            $datos = $fase->listar_fase_proyecto_by_C([
                "idproyecto"         => $_POST['idproyecto'],
                "idcolaboradores"    => $_SESSION['idcolaboradores'],
            ]);
            $etiqueta = "<option value=''>Seleccione la fase</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
               
                $etiqueta2 ="<option value='{$registro['idfase']}'>{$registro['nombrefase']}</option>";
                echo $etiqueta2;
            }
            
        }

        if ($_POST['op'] == 'buscarFase') {
            $datos = ["idproyecto" => $_POST['idproyecto']];
            $datos = $fase->buscarFase($datos);
            $contador = 1; // Variable contador inicializada en 1
            
            foreach ($datos as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);
                $porcentaje = $registro['porcentaje_fase'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null){
                    $porcentaje = 0;
                }
                echo "
                    <tr class='mb-2' ondblclick='getPhase({$registro['idfase']})'>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                        <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                        <td class='p-3' data-label='Responsable'>{$registro['usuario']}</td>
                        <td class='p-3' data-label='Inicio de la Fase'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fin del Fase'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button' title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' data-id='{$registro['idproyecto']}' class='btn btn-outline-primary btn-sm' title='Clic, para más información'><i class='fa-sharp fa-solid fa-circle-info'></i></button>
                                <button type='button' onclick='generarReporteF({$registro['idfase']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
                
                $contador++;
            }
        }
        
        if ($_POST['op'] == 'getPhase') {
            $idfase = $_POST['idfase']; 
            $datos = $fase->getPhase($idfase);
            $contador = 1;

            function vista($datos){
                $porcentaje = $datos[0]['porcentaje'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null) {
                    $porcentaje = 0;
                }
                $inputs= "
                    <form>
                        <div class='row mb-2 mt-2'>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                <input type='text' class='form-control' value='{$datos[0]['nombrefase']}' placeholder='Nombre del proyecto' id='nombre-Fase' name='project' readonly>
                                <label for='project' class='form-label'>Nombre de la Fase</label>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                    <textarea class='form-control' name='descripcion' id='comentario-Fase' placeholder='Comentario dela Fase' readonly>{$datos[0]['comentario']}</textarea>
                                    <label for='descripcion' class='form-label'>Comentario de la Fase</label>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                    <input type='date' class='form-control' id='fechainicio-fase' placeholder='Inicio de la fase' value='{$datos[0]['fechainicio']}' name='fechaini' readonly>
                                    <label for='fechaini' class='form-label'>Fecha de Inicio</label>
                                </div>
                            </div>
                        </div>
                        <div class='row mb-2 mt-2'>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                    <input type='date' class='form-control' id='fechafin-fase' placeholder='Fin de la Fase' value='{$datos[0]['fechafin']}' name='fechafin' readonly>
                                    <label for='fechafin' class='form-label'>Fecha de Fin</label>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                    <select class='form-control' id='usuariore-fase' name='precio'>
                                        <option value='{$datos[0]['idresponsable']}'>{$datos[0]['usuario']}</option>
                                    </select>
                                    <label for='usuariore' class='form-label'>Usuario Responsable</label>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-floating mb-3'>
                                    <input type='number' class='form-control' value='{$porcentaje}' id='porcentaje-Fase' readonly placeholder='Porcentaje' name='porcentaje'>
                                    <label for='porcentaje' class='form-label'>Porcentaje %</label>
                                </div>
                            </div>
                        </div>
                    </form>
                ";
                $inicioT=  "
                    <div class='table-responsive mt-3'>
                        <table class='table table-hover text-center'> 
                            <thead>
                                <th>#</th>
                                <th>Nombre de la tarea</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Usuario Asignado</th>
                                <th>Avance</th>
                                <th>P. Fase</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                ";
                echo $inputs;
                echo $inicioT;
            }
            vista($datos);
            $dato_t = $fase->tablaFases(["idfase" => $datos[0]['idfase']]);
            
            foreach ($dato_t as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);
                $porcentaje = $registro['porcentaje'];
                $porcentaje_tarea = $registro['porcentaje_tarea'];
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                    $porcentaje_tarea = rtrim($porcentaje_tarea, "0");
                    $porcentaje_tarea = rtrim($porcentaje_tarea, ".");
                } elseif ($porcentaje == null) {
                    $porcentaje = 0;
                    $porcentaje_tarea = 0;
                }
                if ($estado == 'Activo') {
                    $tbody= "                 
                        <tr class='mb-2' ondblclick='modalInfoTarea({$registro['idtarea']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Nombre de la tarea'>{$registro['tarea']}</td>
                            <td class='p-3' data-label='Inicio de la tarea'>{$registro['fecha_inicio_tarea']}</td>
                            <td class='p-3' data-label='Fin de la tarea'>{$registro['fecha_fin_tarea']}</td>
                            <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario_tarea']}</td>
                            <td class='p-3' data-label='Porcentaje de avance'>{$porcentaje_tarea}%</td>
                            <td class='p-3' data-label='Porcentaje en la Fase'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='finalizarTarea({$registro['idtarea']})' class='btn btn-outline-primary btn-sm' title='Clic, para finalizar la tarea.'><i class='fa-solid fa-check'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                    echo $tbody;
                } else{
                    $tbody= "
                        <tr class='mb-2' ondblclick='modalInfoTarea({$registro['idtarea']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Nombre de la tarea'>{$registro['tarea']}</td>
                            <td class='p-3' data-label='Inicio de la tarea'>{$registro['fecha_inicio_tarea']}</td>
                            <td class='p-3' data-label='Fin de la tarea'>{$registro['fecha_fin_tarea']}</td>
                            <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario_tarea']}</td>
                            <td class='p-3' data-label='Porcentaje de avance'>{$porcentaje_tarea}%</td>
                            <td class='p-3' data-label='Porcentaje en la Fase'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='reactivarTarea({$registro['idtarea']})' class='btn btn-outline-success btn-sm' title='Clic, para ver reactivar tarea.'><i class='fa-solid fa-arrows-rotate'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                    echo $tbody;
                }
            
                $contador++;
            }
            echo "  
                    </tbody>
                    </table>
                </div>
            ";
        }

        if ($_POST['op'] == 'registerPhase') {
            $idproyecto = $_POST['idproyecto'];
            $idresponsable = $_POST['idresponsable'];
            $nombrefase = $_POST['nombrefase'];
            $fechainicio = $_POST['fechainicio'];
            $fechafin = $_POST['fechafin'];
            $porcentaje = $_POST['porcentaje'];
            $comentario = $_POST['comentario'];
            $fase->registerPhase($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin, $porcentaje ,$comentario);
        }

        // Registrar Fases y tambien se envia un correo con la información
        if ($_POST['op'] == 'registerPhaseV2') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $idproyecto = $_POST['idproyecto'];
            $idresponsable = $_POST['idresponsable'];
            $nombrefase = $_POST['nombrefase'];
            $fechainicio = $_POST['fechainicio'];
            $fechafin = $_POST['fechafin'];
            $porcentaje = $_POST['porcentaje'];
            $comentario = $_POST['comentario'];

            $fase->registerPhase($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin, $porcentaje ,$comentario);
            
            $getColabolador = $colaborador->obtener_info_Colaborador(['idcolaboradores' => $_POST['idresponsable']]);
            $mensaje = "
                <h3>App Vamas</h3>
                <strong>Se le ha asignado una nueva fase a dirigir</strong>
                <hr>
                <p>El colabolador {$_SESSION['usuario']} le ha asignado la siguiente fase:</p>
                <b>{$_POST['nombrefase']}</b>
                <p>La fase inicia: {$_POST['fechainicio']}</p>
                <p>La fase finaliza: {$_POST['fechafin']}</p>
            ";
            enviarEmail($getColabolador['correo'],'Nueva fase asignada',$mensaje);
        }

        if ($_POST['op'] == 'editarFase') {
            $data = [
                "idfase" => $_POST['idfase'] ,
                "idresponsable" => $_POST['idresponsable'],
                "nombrefase" => $_POST['nombrefase'],
                "fechainicio" => $_POST['fechainicio'],
                "fechafin" => $_POST['fechafin'],
                "comentario" => $_POST['comentario'],
                "porcentaje" => $_POST['porcentaje']
            ];
            $fase->editarFase($data);
        }

        if ($_POST['op'] == 'obtenerPorcentajeF') {
            $fase->obtenerPorcentajeF();
        }

        if ($_POST['op'] == 'tablaFases') {
            $data = ["idfase" => $_POST['idfase']];
            $datos = $fase->tablaFases($data);
            foreach ($datos as $registro) {
                $evidencia = json_decode($datos[0]['evidencia'], true);
                foreach ($evidencia as $evidencias) {
                    $count = count($evidencias);
                }
                echo "($count)";
            }
            
            
        }

        if ($_POST['op'] == 'finalizar_fase') {
            $fase->finalizar_fase();
        }

        if ($_POST['op'] == 'finalizar_fase_by_id') {
            require_once '../models/Tarea.php';
            $tarea = new Tarea();
            $fase->finalizar_fase_by_id(["idfase" => $_POST['idfase']]);
            $tarea->finalizar_tarea();
        }

        if ($_POST['op'] == 'reactivar_fase ') {
            $fase->reactivar_fase();
        }

        if ($_POST['op'] == 'reactivar_fase_by_id') {
            require_once '../models/Tarea.php';
            $tarea = new Tarea();
            $fase->reactivar_fase_by_id(["idfase" => $_POST['idfase']]);
            $tarea->reactivar_tarea();
        }
    }

?>