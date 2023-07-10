<?php
// Utilizaremos datos del BACKEND (modelo)
// Librería obtenida mediante Composer
require '../../vendor/autoload.php';

require_once '../../models/Fase.php';

// Namespaces (espacios de nombres/contenedor de clase)
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
  $fase = new Fase();

  $datosE = $fase->infoFases(["idfase" => $_GET["idfase"]]);
  $datosF = $fase->tablaFases(["idfase" => $_GET["idfase"]]);

  // Contenido (HTML) que vamos a renderizar como PDF
  $content = "";

  ob_start(); // INICIO

  include '../estilos.html';
  include 'datosF.php';

  $content .= ob_get_clean(); // FIN

  // Configuración del archivo PDF
  $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
  $html2pdf->writeHTML($content);
  $nombreFase = $datosE[0]['nombrefase'];
  $html2pdf->output($nombreFase . '.pdf');

} catch (Html2PdfException $error) {
  $formatter = new ExceptionFormatter($error);
  echo $formatter->getHtmlMessage();
}

?>
