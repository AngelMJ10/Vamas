<?php
    session_start();
    require_once '../models/persona.php';

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
    }

?>