<page_footer>
        <p class='center italic t-l'>Reporte generado <?= $fechaActual ?></p>
        <p class='italic'>Pag. [[page_cu]]</p>
</page_footer>
<div class="mb-3">
  <h2 class="center text-md mb-3">Fase :<?= $datosE[0]['nombrefase'] ?></h2>
</div>
<hr>
<div>
  <table class="table table-border mt-5 mb-5 center">
    <colgroup>
      <col style="width: 20%;">
      <col style="width: 25%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light">
        <th>Fase</th>
        <th>Tarea</th>
        <th>Usuario</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($datosE): ?>
        <?php $porcentaje = $datosE[0]['porcentaje_fase']?>
        <?php if($porcentaje): ?>
        <?php  $porcentaje = rtrim($porcentaje, "0")?>
        <?php  $porcentaje = rtrim($porcentaje, ".")?>
        <?php endif ?>
        <tr>
          <td><?= $datosE[0]['titulo'] ?></td>
          <td><?= $datosE[0]['nombrefase'] ?></td>
          <td><?= $datosE[0]['usuario'] ?></td>
          <td><?= $datosE[0]['fechainicio'] ?></td>
          <td><?= $datosE[0]['fechafin'] ?></td>
          <td><?= $porcentaje ?>%</td>
        </tr>
      <?php endif?>
    </tbody>
  </table>

      <h3 class="center mb-5 mt-5">Tareas de la fase</h3>

  <table class="table table-border mt-3 center">
    <colgroup>
      <col style="width: 5%;">
      <col style="width: 20%;">
      <col style="width: 15%;">
      <col style="width: 20%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light center">
        <th>#</th>
        <th>Tarea</th>
        <th>Encargado</th>
        <th>Rol</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Nº Evidencia</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody>
      <?php $contador = 1;?>
      <?php foreach($datosF as $element):?>
        <?php $porcentajeT = $element['porcentaje_tarea']?>
        <?php if($porcentajeT): ?>
        <?php  $porcentajeT = rtrim($porcentajeT, "0")?>
        <?php  $porcentajeT = rtrim($porcentajeT, ".")?>
        <?php endif ?>
      <?php $evidencia = json_decode($element['evidencia']) ?>
      <?php $count = count($evidencia) ?>
      <tr>
          <td><?= $contador ?></td>
          <td><?= $element['tarea'] ?></td>
          <td><?= $element['usuario_tarea'] ?></td>
          <td><?= $element['roles'] ?></td>
          <td><?= $element['fecha_inicio_tarea'] ?></td>
          <td><?= $element['fecha_fin_tarea'] ?></td>
          <td><?= $count ?></td>
          <td><?= $porcentajeT?>%</td>
      </tr>
      <?php $contador++;?>
      <?php endforeach ?>

    </tbody>
  </table>


</div>