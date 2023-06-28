<?php
    session_start();
    require_once '../models/Tarea.php';
    require_once '../vendor/autoload.php';

    putenv('GOOGLE_APPLICATION_CREDENTIALS=vamas-390501-7ca65ced97af.json');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendEmail($destino, $ruta, $asunto, $mensaje){
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'angelitomasna200410@gmail.com';
            $mail->Password   = 'tpfqljbdhikqztwg';
            $mail->CharSet    = 'UTF-8';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('angelitomasna200410@gmail.com', 'Area de Sistemas');
            $mail->addAddress($destino);
            $mail->addStringAttachment(file_get_contents($_FILES['documento']['tmp_name']), $_FILES['documento']['name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;
            $mail->AltBody = 'El mensaje requiere soporte HTML';
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->send();
            echo json_encode(["status" => true]);
        } catch (Exception $e) {
            echo json_encode(["status" => false]);
        }
    }

    try {
        // Instanciar la clase Conexion y obtener la conexión PDO
        $conexion = new Conexion();
        $conn = $conexion->getConexion();

        // Crear instancia del cliente de Google
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes(['https://www.googleapis.com/auth/drive.file']);

        // Crear una instancia del servicio de Google Drive
        $service = new Google_Service_Drive($client);

        $file_path = $_FILES['documento']['tmp_name'];
        $file_name = $_FILES['documento']['name'];
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $file_name,
            'parents' => ['1eNNBcJixQSxIsJm-P53g99juldNpMqOR'],
            'description' => 'Archivo Cargado desde PHP',
            'mimeType' => mime_content_type($file_path)
        ));

        $resultado = $service->files->create(
            $fileMetadata,
            array(
                'data' => file_get_contents($file_path),
                'mimeType' => mime_content_type($file_path),
                'uploadType' => 'media'
            )
        );

        $ruta = 'https://drive.google.com/open?id=' . $resultado->id;

        // Obtener los valores del formulario
        $e_mensaje = $_POST['mensaje'];
        $e_porcentaje = $_POST['porcentaje'];
        $e_fecha = date('Y-m-d'); // Obtener la fecha actual
        $e_hora = date('H:i:s'); // Obtener la hora actual

        // Preparar la consulta SQL utilizando parámetros para evitar inyección SQL
        echo $resultado->id . '<br>';
        echo $resultado->name . '<br>';
        echo '<a href="' . $ruta . '" target="_blank">' . $resultado->name . '</a>';


        $e_colaborador = $_SESSION['usuario'];
        // Llamar al procedimiento almacenado enviar_evidencia
        $e_documento = $ruta;

        // Llamar al procedimiento almacenado enviar_evidencia
        $p_porcentaje = $_POST['porcentaje'];
        $t_idtarea = $_POST['idtarea'];
        $destino = $_POST['correo'];
        
        $callProcedure = "CALL enviar_evidencia('$e_colaborador','$destino', '$e_mensaje', '$e_documento', '$e_fecha', '$e_hora', $p_porcentaje, $t_idtarea)";
        $conn->exec($callProcedure);
        echo "El procedimiento almacenado 'enviar_evidencia' se ejecutó exitosamente.";
        $tarea = new Tarea();
        $datosT =$tarea->getWork($t_idtarea);
        $mensajeAdicional = "
            <h2>{$datosT['nombrefase']}</h2>
            <p>Avance de trabajo</p>
            <p>{$datosT['tarea']}</p>
            <p>Enviado por el colaborado: {$e_colaborador}</p>
            <hr>
            <h4>
        ";


        // Envío de correo electrónico
        
        $asunto = 'Avance de trabajo';
        $mensaje = $mensajeAdicional . $e_mensaje;

        sendEmail($destino, $file_path, $asunto, $mensaje);
    } catch (Google_Service_Exception $gs) {
        $mensaje = json_decode($gs->getMessage());
        echo $mensaje->error->message;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
