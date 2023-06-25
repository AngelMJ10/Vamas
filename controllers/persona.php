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
    }

?>