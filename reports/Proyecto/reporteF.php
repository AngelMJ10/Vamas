<?php
// Utilizaremos datos del BACKEND (modelo)
// Librería obtenida mediante Composer
require '../../vendor/autoload.php';

require_once '../../models/Proyecto.php';
require_once '../../models/Fase.php';

// Namespaces (espacios de nombres/contenedor de clase)
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
$proyecto = new Proyecto();
$fase = new Fase();
$fechaActual = date('d-m-Y');
$datosP = $proyecto->get($_GET['idproyecto']);
$datosC = $proyecto->contarColaboradores($_GET['idproyecto']);
$datosF = $fase->getFases_by_P($_GET['idproyecto']);
$datosC_Trabajo = $proyecto->contar_trabajos_colaboradores($_GET['idproyecto']);
$piePagina = "Reporte generado el {$fechaActual}, área de sistemas";
// Contenido (HTML) que vamos a renderizar como PDF
$content = "";

ob_start(); // INICIO
include '../estilos.html';
include 'datosF.php';
include 'pag2.php';

$content .= ob_get_clean(); // FIN

try {
  // Configuración del archivo PDF
  $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
  $html2pdf->writeHTML($content);
  $datosEmpresa = $datosP['nombre'];
  $filename = $datosEmpresa . '.pdf';
  $html2pdf->output($filename);
} catch (Html2PdfException $error) {
  $formatter = new ExceptionFormatter($error);
  echo $formatter->getHtmlMessage();
}

?>
