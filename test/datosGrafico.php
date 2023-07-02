<?php
    session_start();
    require_once '../models/Proyecto.php';


    if (isset($_POST['op'])) {

        $proyecto = new Proyecto();

        if ($_POST['op'] == "testG") {
            $datos = $proyecto->listar();
            echo json_encode($datos); // Devolver el array como JSON
        }

    }