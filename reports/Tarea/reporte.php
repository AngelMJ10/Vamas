<?php

// Utilizaremos fatos del BACKEND (modelo)
// Librería obtenida mediante Composer
require '../../vendor/autoload.php';

require '../../models/Tarea.php';

// Namespaces (espacios de nombres/contenedor de clase)
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
  $fechaActual = date('d-m-Y');
  // Paso 2: Instanciar la clase
  $tarea = new Tarea();

  // Paso 3: Obtener los datos (Método: list)
  $datosE = $tarea->verEvidencias(["idtarea" => $_GET['idtarea']]);

  // Contenido (HTML) que vamos a renderizar como PDF
  $content = "";

  ob_start(); // INICIO

  include '../estilos.html';
  include 'datos.php';

  $content .= ob_get_clean(); // FIN

  // Configuración del archivo PDF
  $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
  $html2pdf->writeHTML($content);
  $html2pdf->output('reporte.pdf');

} catch (Html2PdfException $error) {
  $formatter = new ExceptionFormatter($error);
  echo $formatter->getHtmlMessage();
}

?>
