<?php
    session_start();
    require_once '../models/Proyecto.php';


    if (isset($_POST['op'])) {

        $proyecto = new Proyecto();

        // Operación para listar tareas
        if ($_POST['op'] == 'listar') {
            $datos = $proyecto->listar();
            $contador = 1;
            foreach ($datos as $registro){
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                $porcentaje = $registro['porcentaje'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null) {
                    $porcentaje = 0;
                }
                echo "
                    <tr class='mb-2' ondblclick='info({$registro['idproyecto']})'>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                        <td class='p-3' data-label='Fecha de Inicio'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fecha de Fin'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Empresa'>{$registro['nombre']}</td>
                        <td class='p-3' data-label='N° Fases'>{$registro['Fases']}</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button' onclick='get({$registro['idproyecto']})'  title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' onclick='addPhase({$registro['idproyecto']})' class='btn btn-outline-success btn-sm' title='Clic, para agregar una fase.'><i class='fas fa-arrow-alt-circle-down'></i></button>
                                <button type='button' onclick='generarReporteP({$registro['idproyecto']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                <button type='button' onclick='abrirGrafico({$registro['idproyecto']})' class='btn btn-outline-info btn-sm' title='Clic, para ver gráfico de avance.'><i class='fa-solid fa-chart-column'></i></button>
                                <button type='button' onclick='finalizarProyecto({$registro['idproyecto']})' class='btn btn-outline-primary btn-sm' title='Clic, para finalizar el proyecto.'><i class='fa-solid fa-check'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
                $contador++;
            }
        }

        // Operación para listar proyectos en los filtros de tareas
        if ($_POST['op'] == 'listarProyecto') {
            $datos = $proyecto->listar();
            $etiqueta = "<option value=''>Seleccione el proyecto</option>";
            echo $etiqueta;
            if ($_SESSION['nivelacceso'] != 'C') {
                foreach ($datos as $registro){
                    $etiqueta ="<option value='{$registro['idproyecto']}'>{$registro['titulo']}</option>";
                    echo $etiqueta;
                }
            } else {
                $datosF = $proyecto->listar_proyecto_by_Colaborador(["idcolaboradores" => $_SESSION['idcolaboradores']]);
                foreach ($datosF as $registro){
                    $etiqueta ="<option value='{$registro['idproyecto']}'>{$registro['titulo']}</option>";
                    echo $etiqueta;
                }
            }
        }

        // Operación para buscar proyectos
        if ($_POST['op'] == 'buscarProyecto') {
            $data = [
                "idtipoproyecto"    => $_POST['idtipoproyecto'],
                "idempresa"         => $_POST['idempresa'],
                "estado"            => $_POST['estado']
            ];
            $datos = $proyecto->buscarProyecto($data);
            foreach ($datos as $registro){
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);
                $porcentaje = $registro['porcentaje'];
                // If para poder quitar ".00" de los porcentajes y en caso del que porcentaje sea NULL,
                // Se muestre como "0" 
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null) {
                    $porcentaje = 0;
                }
                if ($registro['estado'] == 1){
                    echo "
                        <tr class='mb-2' ondblclick='info({$registro['idproyecto']})'>
                            <td class='p-3' data-label='#'>{$registro['idproyecto']}</td>
                            <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                            <td class='p-3' data-label='Fecha de Inicio'>{$registro['fechainicio']}</td>
                            <td class='p-3' data-label='Fecha de Fin'>{$registro['fechafin']}</td>
                            <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Empresa'>{$registro['nombre']}</td>
                            <td class='p-3' data-label='N° Fases'>{$registro['Fases']}</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='get({$registro['idproyecto']})'  title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                    <button type='button' onclick='addPhase({$registro['idproyecto']})' class='btn btn-outline-success btn-sm' title='Clic, para agregar una fase.'><i class='fas fa-arrow-alt-circle-down'></i></button>
                                    <button type='button' onclick='generarReporteP({$registro['idproyecto']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                    <button type='button' onclick='finalizarProyecto({$registro['idproyecto']})' class='btn btn-outline-primary btn-sm' title='Clic, para finalizar el proyecto.'><i class='fa-solid fa-check'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                } else{
                    echo "
                        <tr class='mb-2' ondblclick='info({$registro['idproyecto']})'>
                            <td class='p-3' data-label='#'>{$registro['idproyecto']}</td>
                            <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                            <td class='p-3' data-label='Fecha de Inicio'>{$registro['fechainicio']}</td>
                            <td class='p-3' data-label='Fecha de Fin'>{$registro['fechafin']}</td>
                            <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Empresa'>{$registro['nombre']}</td>
                            <td class='p-3' data-label='N° Fases'>{$registro['Fases']}</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='get({$registro['idproyecto']})'  title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                    <button type='button' onclick='addPhase({$registro['idproyecto']})' class='btn btn-outline-success btn-sm' title='Clic, para agregar una fase.'><i class='fas fa-arrow-alt-circle-down'></i></button>
                                    <button type='button' onclick='generarReporteP({$registro['idproyecto']})' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                                    <button type='button' onclick='reactivarProyecto({$registro['idproyecto']})' class='btn btn-outline-primary btn-sm' title='Clic, para reactivar el proyecto.'><i class='fa-solid fa-arrows-rotate'></i></button>    
                                </div>
                            </td>
                        </tr>
                    ";
                }
                
            }
        }

        // Operación para listar proyectos en los filtros de fases
        if ($_POST['op'] == 'listarSelectProyecto') {
            $datos = $proyecto->listar();
            $etiqueta = "<option value=''>Seleccione el proyecto</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
                echo "<option value={$registro['idproyecto']}>{$registro['titulo']}</option>";
            }
        }

        // Operación para registrar un proyectos
        if ($_POST['op'] == 'registrar') {
            $idtipoproyecto = $_POST['idtipoproyecto'];
            $idempresa = $_POST['idempresa'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fechainicio = $_POST['fechainicio'];
            $fechafin = $_POST['fechafin'];
            $precio = $_POST['precio'];
            $idusuariore = $_SESSION['idcolaboradores'];
            $proyecto->registrar($idtipoproyecto, $idempresa, $titulo, $descripcion, $fechainicio, $fechafin, $precio,$idusuariore);
            echo $idusuariore;
        }

        // Operación para editar un proyecto
        if ($_POST['op'] == 'editar') {
            $data = [
                "idproyecto" => $_POST['idproyecto'] ,
                "idtipoproyecto" => $_POST['idtipoproyecto'],
                "idempresa" => $_POST['idempresa'],
                "titulo" => $_POST['titulo'],
                "descripcion" => $_POST['descripcion'],
                "fechainicio" => $_POST['fechainicio'],
                "fechafin" => $_POST['fechafin'],
                "precio" => $_POST['precio']
            ];
            $proyecto->actualizar_proyecto($data);
            echo "Proyecto creado";
        }

        // Operación obtener los datos del proyecto
        if ($_POST['op'] == 'get') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $proyecto->get($idproyecto);
            echo json_encode($datos);
        }

        // Graficos
        // Gráfico 1
        if ($_POST['op'] == "iniGrafico") {
            $datos = $proyecto->listar();
            $labels = array(); // Array para almacenar los títulos
            $data = array(); // Array para almacenar los porcentajes
        
            foreach ($datos as $registro) {
                $porcentaje = $registro['porcentaje'];
                $fechafin = $registro['fechafin'];
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } elseif ($porcentaje == null || $porcentaje == 0) {
                    $porcentaje = 0;
                }

                // Extraer el número del mes a partir de la fecha de finalización
                $mes = date('n', strtotime($fechafin));
        
                $labels[] = $porcentaje.'%'; // Agregar el porcentaje al array de data
                $data[] = $mes; // Agregar el nº del mes en que finaliza
            }
        
            $result = array(
                "labels" => $labels,
                "data" => $data
            );
        
            echo json_encode($result); // Devolver el array como JSON
        }

        if ($_POST['op'] == "getDatos") {
            require_once '../models/Fase.php';
            $fase = new Fase();
            $idproyecto = $_POST['idproyecto'];
            $datos = $fase->getFases_by_P($idproyecto);
            echo json_encode($datos);
        }

        if ($_POST['op'] == "graficoP") {
            require_once '../models/Fase.php';
            $fase = new Fase();
            $idproyecto = $_POST['idproyecto'];
            $datos = $fase->getFases_by_P($idproyecto);
            $labels = array(); // Array para almacenar los títulos
            $data = array(); // Array para almacenar los porcentajes
            $porcentajeP = array(); // Array para almacenar los porcentajes
            foreach ($datos as $registro) {
                $porcentaje = $registro['porcentaje_fase'];
                $nombrefase = $registro['nombrefase'];
                
                $labels[] = $nombrefase; // Agregar el porcentaje al array de data
                $data[] = $porcentaje; // Agregar el nº del mes en que finaliza
                $porcentajeP[] = 'El proyecto está al '.$registro['porcentaje_pro'] . '%'; // Agregar el nº del mes en que finaliza
            }
        
            $result = array(
                "labels" => $labels,
                "data" => $data,
                "porcentajep" => $porcentajeP
            );
        
            echo json_encode($result); // Devolver el array como JSON
        }

        // Operación obtener información del proyecto en cajas de texto
        if ($_POST['op'] == 'info') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $proyecto->get($idproyecto);
            $porcentaje = $datos['porcentaje'];
            if ($porcentaje) {
                $porcentaje = rtrim($porcentaje, "0");
                $porcentaje = rtrim($porcentaje, ".");
            } elseif ($porcentaje == null) {
                $porcentaje = 0;
            }

            $inputs = "
                <form>
                    <div class='row mb-2 mt-2'>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <select name='tipoproyecto' id='tipo_proyecto' class='form-control' readonly>
                                    <option value='{$datos['idtipoproyecto']}' selected>{$datos['tipoproyecto']}</option>
                                </select>
                                <label for='tipoproyecto' class='form-label'>Tipo de Proyecto</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <select name='empresa' id='id_empresa' class='form-control' readonly>
                                    <option value='{$datos['idempresa']}' selected>{$datos['nombre']}</option>
                                </select>
                                <label for='empresa' class='form-label'>Empresa</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <input type='text' name='titulo' id='titulo_proyecto' readonly class='form-control' value='{$datos['titulo']}'>
                                <label for='titulo' class='form-label'>Titulo del proyecto</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <input type='number' name='porcentaje' id='porcentaje_proyecto' readonly class='form-control' value='{$porcentaje}'>
                                <label for='porcentaje' class='form-label'>Porcentaje</label>
                            </div>
                        </div>
                    </div>
                    <div class='row mb-2'>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <textarea name='descripcion' id='descripcion_proyecto' readonly class='form-control'>{$datos['descripcion']}</textarea>
                                <label for='descripcion' class='form-label'>Descripción</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <input type='date' name='fechainicio' id='fechainicio_proyecto' readonly class='form-control' value='{$datos['fechainicio']}'>
                                <label for='fechainicio' class='form-label'>Fecha de inicio</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <input type='date' name='fechafin' id='fechafin_proyecto' readonly class='form-control' value='{$datos['fechafin']}'>
                                <label for='fechafin' class='form-label'>Fecha de cierre</label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <input type='number' name='precio' id='precio_proyecto' readonly class='form-control' value='{$datos['precio']}'>
                                <label for='precio' class='form-label'>Precio</label>
                            </div>
                        </div>
                    </div>
                </form>
        
            ";

            echo($inputs);
        }

        // Operación obtener información de las fases del proyectos en una tabla
        if ($_POST['op'] == 'getPhase') {
            require_once '../models/Fase.php';
            $fase = new Fase();
            $idproyecto = $_POST['idproyecto'];
            $datos = $fase->getFases_by_P($idproyecto);
            $contador = 1;

            function vista($datos){
                $inicioT=  "
                    <div class='table-responsive mt-3'>
                        <table class='table table-hover'> 
                            <thead>
                                <th>#</th>
                                <th>Nombre de la Fase</th>
                                <th>Inicio de la Fase</th>
                                <th>Fin de la Fase</th>
                                <th>Usuario Responsable</th>
                                <th>Avance</th>
                                <th>Porcentaje P.</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>    
                ";
                echo $inicioT;
            }
            vista($datos);
            
            foreach ($datos as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : ($registro['estado'] == 2 ? 'Finalizado' : $registro['estado']);
                $porcentaje = $registro['porcentaje'];
                $porcentaje_fase = $registro['porcentaje_fase'];
                if ($porcentaje !== null) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                } else {
                    $porcentaje = 0;
                    $porcentaje_fase = 0; // Se agrega la asignación de 0 a $porcentaje_fase
                }
                
                if ($porcentaje_fase !== null) {
                    $porcentaje_fase = rtrim($porcentaje_fase, "0");
                    $porcentaje_fase = rtrim($porcentaje_fase, ".");
                } else {
                    $porcentaje_fase = 0;
                }
                if ($estado == 'Activo') {
                    $tbody= "                 
                        <tr class='mb-2' ondblclick='modalInfoFase({$registro['idfase']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                            <td class='p-3' data-label='Inicio de la fase'>{$registro['fechainicio']}</td>
                            <td class='p-3' data-label='Fin de la fase'>{$registro['fechafin']}</td>
                            <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario']}</td>
                            <td class='p-3' data-label='Porcentaje de avance'>{$porcentaje_fase}%</td>
                            <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='finalizarFase({$registro['idfase']})' class='btn btn-outline-primary btn-sm' title='Clic, para finalizar la fase.'><i class='fa-solid fa-check'></i></button>
                                </div>
                            </td>
                        </tr>
                    ";
                    echo $tbody;
                }else {
                    $tbody= "                 
                        <tr class='mb-2' ondblclick='modalInfoFase({$registro['idfase']})'>
                            <td class='p-3' data-label='#'>{$contador}</td>
                            <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                            <td class='p-3' data-label='Inicio de la fase'>{$registro['fechainicio']}</td>
                            <td class='p-3' data-label='Fin de la fase'>{$registro['fechafin']}</td>
                            <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario']}</td>
                            <td class='p-3' data-label='Porcentaje de avance'>{$porcentaje_fase}%</td>
                            <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                            <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                            <td data-label='Acciones'>
                                <div class='btn-group' role='group'>
                                    <button type='button' onclick='reactivarFase({$registro['idfase']})' class='btn btn-outline-success btn-sm' title='Clic, para ver reactivar la fase.'><i class='fa-solid fa-arrows-rotate'></i></button>
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

        // Operación para listar los tipos de proyectos en los filtros
        if ($_POST['op'] == 'listartipoproyecto') {
            $datos = $proyecto->listarTipoProyecto();
            $etiqueta = "<option value=''>Seleccione el tipo de proyecto</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
                echo "<option value={$registro['idtipoproyecto']}>{$registro['tipoproyecto']}</option>";
            }
        }

        // Operación para contar los proyectos activos
        if ($_POST['op'] == 'countProjects') {
            $projects = $proyecto->countProjects();
            echo "{$projects['proyectos']}";
        }

        // Operación para contar los proyectos finalizados
        if ($_POST['op'] == 'countFinishProjects') {
            $projects = $proyecto->countFinishProjects();
            echo "{$projects['ProjectsFinish']}";
        }

        // Operación los usuarios
        if ($_POST['op'] == 'countUsers') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->countUsers();
            echo "{$datos['users']}";
        }

        // Operación para listar los supervisores en el registro de fases
        if ($_POST['op'] == 'listarColaborador') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listarColaborador();
            $etiqueta = "<option value=''>Seleccione el usuario responsable</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                echo "<option value='{$registro['idcolaboradores']}'>{$registro['usuario']}</option>";
            }
        }

        // Operación para listar a todos los usuario menos al administrador
        if ($_POST['op'] == 'listarColaborador_A') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listarColaborador_A();
            $etiqueta = "<option value=''>Seleccione el usuario a elegir</option>";
            $idcolaboradores = $_SESSION['idcolaboradores'];
            $usuario = $_SESSION['usuario'];
            if ($_SESSION['nivelacceso'] == 'C') {
                echo "<option value='{$idcolaboradores}'>{$usuario}</option>";
            }else {
                echo $etiqueta;
                foreach ($datos as $registro) {
                    echo "<option value='{$registro['idcolaboradores']}'>{$registro['usuario']}</option>";
                }
            }
            
        }

        // Operación calcular el porcentaje del proyecto
        if ($_POST['op'] == 'obtenerPorcentajeP') {
            $proyecto->obtenerPorcentajeP();
        }

        // Finalizar el proyecto si lo seleccionas
        if ($_POST['op'] == 'finalizar_proyecto'){
            $data = ["idproyecto" => $_POST['idproyecto']];
            $proyecto->finalizar_proyecto($data);
            require_once '../models/Fase.php';
            $fase = new Fase();
            $fase->finalizar_fase();
            require_once '../models/Tarea.php';
            $tarea = new Tarea();
            $tarea->finalizar_tarea();
        }

        // Finaliza los proyectos que ya pasan de su fecha limite
        if ($_POST['op'] == 'finalizar_proyectoV2') {
            $datosP = $proyecto->listarTodos();
            
            // Obtener la fecha actual
            $fechaActual = date('Y-m-d');
            
            foreach ($datosP as $registro) {
                if ($registro['fechafin'] < $fechaActual) {
                    $data = ["idproyecto" => $registro['idproyecto']];
                    
                    // Ejecutar el método finalizar_proyecto
                    $proyecto->finalizar_proyecto($data);
                    
                    // Ejecutar el método finalizar_fase
                    require_once '../models/Fase.php';
                    $fase = new Fase();
                    $fase->finalizar_fase();
                    
                    // Ejecutar el método finalizar_tarea
                    require_once '../models/Tarea.php';
                    $tarea = new Tarea();
                    $tarea->finalizar_tarea();
                }
            }
        }

        // Reactiva el proyecto si lo seleccionas
        if ($_POST['op'] == 'reactivar_proyecto'){
            $data = ["idproyecto" => $_POST['idproyecto']];
            $proyecto->reactivar_proyecto($data);
            require_once '../models/Fase.php';
            $fase = new Fase();
            $fase->reactivar_fase();
            require_once '../models/Tarea.php';
            $tarea = new Tarea();
            $tarea->reactivar_tarea();
        }

        // Reactiva los proyectos que se han modificado la fecha limite
        if ($_POST['op'] == 'reactivar_proyectoV2') {
            $datosP = $proyecto->listarTodos();
            
            // Obtener la fecha actual
            $fechaActual = date('Y-m-d');
            
            foreach ($datosP as $registro) {
                if ($registro['fechafin'] > $fechaActual) {
                    $data = ["idproyecto" => $registro['idproyecto']];
                    
                    // Ejecutar el método reactivar_proyecto
                    $proyecto->reactivar_proyecto($data);
                    
                    // Ejecutar el método reactivar_fase
                    require_once '../models/Fase.php';
                    $fase = new Fase();
                    $fase->reactivar_fase();
                    
                    // Ejecutar el método reactivar_tarea
                    require_once '../models/Tarea.php';
                    $tarea = new Tarea();
                    $tarea->reactivar_tarea();
                }
            }
        }

    }

?>