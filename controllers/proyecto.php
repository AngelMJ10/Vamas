<?php
    session_start();
    require_once '../models/proyecto.php';

    if (isset($_POST['op'])) {

        $proyecto = new Proyecto();

        if ($_POST['op'] == 'listar') {
            $datos = $proyecto->listar();
            foreach ($datos as $registro){
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                echo "
                    <tr class='mb-2'>
                        <td class='p-3' data-label='#'>{$registro['idproyecto']}</td>
                        <td class='p-3' data-label='Titulo'>{$registro['titulo']}</td>
                        <td class='p-3' data-label='Descripcion'>{$registro['descripcion']}</td>
                        <td class='p-3' data-label='Fecha de Inicio'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fecha de Fin'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Precio'>{$registro['precio']}</td>
                        <td class='p-3' data-label='Empresa'>{$registro['nombre']}</td>
                        <td class='p-3' data-label='N° Fases'>{$registro['Fases']}</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button' onclick='get({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' onclick='addPhase({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' class='btn btn-outline-primary btn-sm' title='Clic, para agregar una fase.'><i class='fas fa-arrow-alt-circle-down'></i></button>
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
            require_once '../models/colaboradores.php';
            $colaborador = new Colaborador();
            $datos = $colaborador->countUsers();
            echo "{$datos['users']}";
        }

        if ($_POST['op'] == 'listarColaborador') {
            require_once '../models/colaboradores.php';
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
        
    }

?>