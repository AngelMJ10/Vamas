<?php
    session_start();
    require_once '../models/Empresa.php';

    if (isset($_POST['op'])) {

        $empresa = new Empresa();

        if ($_POST['op'] == 'listar') {
            $datos = $empresa->listar();
            foreach ($datos as $registro){
                $estado = $registro['estado'] == 1 ? 'Activo' : $registro['estado'];
                echo "
                    <tr class='mb-2'>
                        <td class='p-3' data-label='#'>{$registro['idempresa']}</td>
                        <td class='p-3' data-label='Nombre'>{$registro['nombre']}</td>
                        <td class='p-3' data-label='Razón Social'>{$registro['razonsocial']}</td>
                        <td class='p-3' data-label='Tipo de Documento'>{$registro['tipodocumento']}</td>
                        <td class='p-3' data-label='Documento'>{$registro['documento']}</td>
                        <td class='p-3' data-label='Estado'><span class='badge rounded-pill' style='background-color: #005478'>$estado</span></td>
                        <td data-label='Acciones'>
                            <div class='btn-group' role='group'>
                                <button type='button' onclick='obtenerDatos({$registro['idempresa']})' data-id='{$registro['idempresa']}' class='btn btn-outline-warning btn-sm editar-btn'><i class='fa-solid fa-pencil'></i></button>
                                <button type='button' class='btn btn-outline-danger btn-sm'><i class='fa-solid fa-file-pdf'></i></button>
                            </div>
                        </td>
                    </tr>
                ";
            }
        }

        if ($_POST['op'] == 'listarempresa') {
            $datos = $empresa->listar();
            $etiqueta = "<option value='0'>Seleccione la empresa</option>";
            echo $etiqueta;
            foreach ($datos as $registro){
               
                $etiqueta ="<option value='{$registro['idempresa']}'>{$registro['nombre']}</option>";
                echo $etiqueta;
            }
        }
        
        if ($_POST['op'] == 'getDatos') {
            $idempresa = $_POST['idempresa'];
            $datos = $empresa->getDatos($idempresa);
            echo json_encode($datos);
        }

        if ($_POST['op'] == 'registrar') {
            $nombre = $_POST['nombre'];
            $razonsocial = $_POST['razonsocial'];
            $tipodocumento = $_POST['tipodocumento'];
            $documento = $_POST['documento'];
            $datos = $empresa->registrar($nombre, $razonsocial, $tipodocumento, $documento);
        }

        if ($_POST['op'] == 'update') {
            $idempresa = $_POST['idempresa'];
            $nombre = $_POST['nombre'];
            $razonsocial = $_POST['razonsocial'];
            $tipodocumento = $_POST['tipodocumento'];
            $documento = $_POST['documento'];
            $estado = $_POST['estado'];
            $datos = $empresa->update($nombre, $razonsocial, $tipodocumento, $documento, $estado, $idempresa);
        }
    }

?>