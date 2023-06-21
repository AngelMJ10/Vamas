<?php
    session_start();
    require_once '../models/Fase.php';

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
                    <tr class='mb-2'>
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
                                <button type='button'title='Clic, para editar el proyecto.' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' onclick='getPhase({$registro['idproyecto']})' data-id='{$registro['idproyecto']}' class='btn btn-outline-primary btn-sm' title='Clic, para más información'><i class='fa-sharp fa-solid fa-circle-info'></i></button>
                                <button type='button' class='btn btn-outline-danger btn-sm' title='Clic, para ver los reportes del proyecto.'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
                
                $contador++;
            }
        }
        
        if ($_POST['op'] == 'getPhase') {
            $idproyecto = $_POST['idproyecto'];
            $datos = $fase->getPhase($idproyecto);
            $contador = 1; // Variable contador inicializada en 1
            echo "
                <form>
                    <div class='row mb-2 mt-2'>
                        <div class='col-md-4'>
                            <div class='form-floating mb-3'>
                                <input type='text' readonly class='form-control' value='{$datos[0]['titulo']}' placeholder='Nombre del proyecto' id='name-phase' name='project'>
                                <label for='project' class='form-label'>Nombre del Proyecto</label>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-floating mb-3'>
                                <textarea class='form-control' name='descripcion' readonly placeholder='Descripcion del proyecto'>{$datos[0]['descripcion']}</textarea>
                                <label for='descripcion' class='form-label'>Descripción</label>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-floating mb-3'>
                                <input type='date' class='form-control' readonly placeholder='Inicio de la fase' value='{$datos[0]['InicioProyecto']}' name='fechaini'>
                                <label for='fechaini' class='form-label'>Fecha de Inicio</label>
                            </div>
                        </div>
                    </div>
                    <div class='row mb-2 mt-2'>
                        <div class='col-md-4'>
                            <div class='form-floating mb-3'>
                                <input type='date' class='form-control' readonly placeholder='Fin de la Fase' value='{$datos[0]['FinProyecto']}' name='fechafin'>
                                <label for='fechafin' class='form-label'>Fecha de Inicio</label>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-floating mb-3'>
                                <input type='number' class='form-control' readonly value='{$datos[0]['precio']}' placeholder='Precio del proyecto' name='precio'>
                                <label for='precio' class='form-label'></label>
                            </div>
                        </div>
                    </div>
                </form>
            ";
            echo "
                <div class='table-responsive mt-3'>
                    <table class='table table-hover'> 
        
                        <thead>
                            <th>#</th>
                            <th>Nombre de la Fase</th>
                            <th>Inicio de la Fase</th>
                            <th>Fin de la Fase</th>
                            <th>Usuario Responsable</th>
                            <th>Comentario</th>
                            <th>Porcentaje</th>
                            <th>Estado</th>
                        </thead>

                        <tbody>    
            ";
            
            foreach ($datos as $registro) {
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                $porcentaje = $registro['porcentaje_fase'];
                if ($porcentaje) {
                    $porcentaje = rtrim($porcentaje, "0");
                    $porcentaje = rtrim($porcentaje, ".");
                }
                echo "                 
                    <tr class='mb-2'>
                        <td class='p-3' data-label='#'>{$contador}</td>
                        <td class='p-3' data-label='Nombre de la Fase'>{$registro['nombrefase']}</td>
                        <td class='p-3' data-label='Inicio de la fase'>{$registro['fechainicio']}</td>
                        <td class='p-3' data-label='Fin de la fase'>{$registro['fechafin']}</td>
                        <td class='p-3' data-label='Usuario Responsable'>{$registro['usuario']}</td>
                        <td class='p-3' data-label='Comentario'>{$registro['comentario']}</td>
                        <td class='p-3' data-label='Porcentaje'>{$porcentaje}%</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                    </tr>
                ";
                
                $contador++;
            }
            echo " 
                    </tbody>
                </table>
            </div>";
        }

        if ($_POST['op'] == 'registerPhase') {
            $idproyecto = $_POST['idproyecto'];
            $idresponsable = $_POST['idresponsable'];
            $nombrefase = $_POST['nombrefase'];
            $fechainicio = $_POST['fechainicio'];
            $fechafin = $_POST['fechafin'];
            $comentario = $_POST['comentario'];
            $fase->registerPhase($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin ,$comentario);
        }

        if ($_POST['op'] == 'obtenerPorcentajeF') {
            $idfase = $_POST['idfase'];
            $fase->obtenerPorcentajeF($idfase);
        }
    }

?>