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
                    <tr class='mb-2'>
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
                                <button type='button' onclick='get({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' onclick='addPhase({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' class='btn btn-outline-success btn-sm' title='Clic, para agregar una fase.'><i class='fas fa-arrow-alt-circle-down'></i></button>
                                <button type='button' onclick='info({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' class='btn btn-outline-primary btn-sm' title='Clic, para ver más información'><i class='fa-sharp fa-solid fa-circle-info'></i></button>
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

        if ($_POST['op'] == 'get') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $proyecto->get($idproyecto);
            echo json_encode($datos);
        }

        if ($_POST['op'] == 'info') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $proyecto->get($idproyecto);
            $porcentaje = $datos['porcentaje'];
            if ($porcentaje == "0.00") {
                $porcentaje = "0";
            }

            $input = "
                <form>
                    <div class='row mb-2 mt-2'>
                        <div class='col-md-3'>
                            <div class='form-floating mb-3'>
                                <textarea name='descripcion' class='form-control'>{$datos['descripcion']}</textarea>
                                <label for='descripcion' class='form-label'>Descripción</label>
                            </div>
                        </div>
                    </div>
                </form>
            ";
            
            $body= "
                <tr class='mb-2'>
                    <td class='p-3' data-label='#'>{$datos['idproyecto']}</td>
                    <td class='p-3' data-label='Tipo de Empresa'>{$datos['tipoproyecto']}</td>
                    <td class='p-3' data-label='Empresa'>{$datos['nombre']}</td>
                    <td class='p-3' data-label='Titulo'>{$datos['titulo']}</td>
                    <td class='p-3' data-label='Fecha de Inicio'>{$datos['fechainicio']}</td>
                    <td class='p-3' data-label='Fecha de Fin'>{$datos['fechafin']}</td>
                    <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                    <td class='p-3' data-label='Precio'>{$datos['precio']}%</td>
                    <td class='p-3' data-label='N° Fases'>{$datos['Fases']}</td>
                </tr>
            ";

            $tabla = "
                <div class='table-responsive mt-3' id='tabla-info'>
                    <table class='table table-hover'> 
        
                        <thead>
                            <th>#</th>
                            <th>Tipo de Proyecto</th>
                            <th>Empresa</th>
                            <th>Titulo</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Porcentaje</th>
                            <th>N° Fases</th>
                            <th>Estado</th>
                        </thead>
        
                        <tbody>
                        {$body}
                        </tbody>
                    
                    </table>
                </div>
            ";

            echo($input);
            echo($tabla);
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

        if ($_POST['op'] == 'listProject') {
            $datos = $proyecto->listar();
            $etiqueta = "<option value='0'>Seleccione un proyecto</option>";
            echo $etiqueta;
            foreach ($datos as $registro) {
                echo "<option value='{$registro['idproyecto']}'>{$registro['titulo']}</option>";
            }
        }

        if ($_POST['op'] == 'obtenerPorcentajeP') {
            $idproyecto = $_POST['idproyecto'];
            $proyecto->obtenerPorcentajeP($idproyecto);
        }
        
    }

?>