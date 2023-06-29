<?php
    session_start();
    require_once '../models/Proyecto.php';


    if (isset($_POST['op'])) {

        $proyecto = new Proyecto();

        if ($_POST['op'] == 'listar') {
            $datos = $proyecto->listar();
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
                                <button type='button' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
            }
        }

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

        if ($_POST['op'] == 'get') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $proyecto->get($idproyecto);
            echo json_encode($datos);
        }

        // Info Proyecto
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
                                <th>Comentario</th>
                                <th>Avance</th>
                                <th>Porcentaje P.</th>
                                <th>Estado</th>
                            </thead>
                            <tbody>    
                ";
                echo $inicioT;
            }
            vista($datos);
            
            foreach ($datos as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
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
                $tbody= "                 
                    <tr class='mb-2' ondblclick='modalInfoFase({$registro['idfase']})'>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                        <td class='p-3' data-label='Inicio de la fase'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fin de la fase'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario']}</td>
                        <td class='p-3' data-label='Comentario'>{$registro['comentario']}</td>
                        <td class='p-3' data-label='Porcentaje de avance'>{$porcentaje_fase}%</td>
                        <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                    </tr>
                ";
                echo $tbody;
                
                $contador++;
            }

            echo "  
                    </tbody>
                    </table>
                </div>
            ";
        }

        if ($_POST['op'] == 'listartipoproyecto') {
            $datos = $proyecto->listarTipoProyecto();
            $etiqueta = "<option value='0'>Seleccione el tipo de proyecto</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
                echo "<option value={$registro['idtipoproyecto']}>{$registro['tipoproyecto']}</option>";
            }
        }

        if ($_POST['op'] == 'countProjects') {
            $projects = $proyecto->countProjects();
            echo "{$projects['proyectos']}";
        }

        if ($_POST['op'] == 'countFinishProjects') {
            $projects = $proyecto->countFinishProjects();
            echo "{$projects['ProjectsFinish']}";
        }

        if ($_POST['op'] == 'countUsers') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->countUsers();
            echo "{$datos['users']}";
        }

        if ($_POST['op'] == 'listarColaborador') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listarColaborador();
            $etiqueta = "<option value='0'>Seleccione el usuario responsable</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                echo "<option value='{$registro['idcolaboradores']}'>{$registro['usuario']}</option>";
            }
        }

        if ($_POST['op'] == 'listarColaborador_A') {
            require_once '../models/Colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->listarColaborador_A();
            $etiqueta = "<option value='0'>Seleccione el usuario a elegir</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                echo "<option value='{$registro['idcolaboradores']}'>{$registro['usuario']}</option>";
            }
        }

        if ($_POST['op'] == 'listProject') {
            $datos = $proyecto->listar();
            $etiqueta = "<option value='0'>Seleccione un proyecto</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                echo "<option value='{$registro['idproyecto']}'>{$registro['titulo']}</option>";
            }
        }

        if ($_POST['op'] == 'obtenerPorcentajeP') {
            $proyecto->obtenerPorcentajeP();
        }
        
    }

?>