<div>
  <p>Area de sistemas</p>
  <img src="../../img/logos vamas_Mesa de trabajo 1 copia 2.png" alt="">
</div>
<h3 class="center mb-3">Proyecto</h3>

  <table class="table table-border mb-5 center">
    <colgroup>
      <col style="width: 20%;">
      <col style="width: 20%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light">
        <th>Titulo</th>
        <th>Tipo de Proyecto</th>
        <th>Empresa</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Precio</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody>
        <tr>
          <td><?= $datosP['titulo'] ?></td>
          <td><?= $datosP['tipoproyecto'] ?></td>
          <td><?= $datosP['nombre'] ?></td>
          <td><?= $datosP['fechainicio'] ?></td>
          <td><?= $datosP['fechafin'] ?></td>
          <td>S/.<?= $datosP['precio'] ?></td>
          <td><?= $porcentaje ?>%</td>
        </tr>
    </tbody>
  </table>
<hr>
  <h3 class="center mb-3 mt-5">Fases</h3>

  <table class="table table-border mb-5 center">
    <colgroup>
      <col style="width: 5%;">
      <col style="width: 20%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 12%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light">
        <th>#</th>
        <th>Fase</th>
        <th>Usuario</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Nº Tareas</th>
        <th>Avance</th>
        <th>Representa</th>
      </tr>
    </thead>
    <tbody>
      <?php $contador1 = 1?>
      <?php foreach ($datosF as $registro): ?>
        <?php $porcentajeF = $registro['porcentaje_fase']?>
        <?php $porcentaje = $registro['porcentaje']?>
          <?php if($porcentaje): ?>
            <?php  $porcentaje = rtrim($porcentaje, "0")?>
            <?php  $porcentaje = rtrim($porcentaje, ".")?>
            <?php  $porcentajeF = rtrim($porcentajeF, "0")?>
            <?php  $porcentajeF = rtrim($porcentajeF, ".")?>
          <?php endif ?>
        <tr>
          <td><?= $contador1 ?></td>
          <td><?= $registro['nombrefase'] ?></td>
          <td><?= $registro['usuario'] ?></td>
          <td><?= $registro['fechainicio'] ?></td>
          <td><?= $registro['fechafin'] ?></td>
          <td><?= $registro['Tareas'] ?></td>
          <td><?= $porcentajeF ?>%</td>
          <td><?= $porcentaje ?>%</td>
        </tr>
        <?php $contador1++ ?>
      <?php endforeach?>
    </tbody>
  </table>

  <h2 class="center">Tareas por fase</h2>
  <?php $contador = 1?>
  <?php foreach ($datosF as $registro): ?>
    
  <h3 class="mt-5">Fase Nº<?= $contador, " ", $registro['nombrefase']?></h3>
  <table class="table table-border mb-5 center mb-5 mt-5">
    <colgroup>
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 20%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light center">
        <th>Tarea</th>
        <th>Encargado</th>
        <th>Rol</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Nº Evidencia</th>
        <th>Avance</th>
        <th>Representa</th>
      </tr>
    </thead>
    <tbody>
      <?php $datosT = $registro['idfase']; ?>
      <?php $dataT = $fase->tablaFases(["idfase" => $datosT]) ?>
      <?php foreach($dataT as $element): ?>
        <?php $porcentajeT = $element['porcentaje_tarea']?>
        <?php $porcentaje = $element['porcentaje']?>
          <?php if($porcentaje): ?>
            <?php  $porcentaje = rtrim($porcentaje, "0")?>
            <?php  $porcentaje = rtrim($porcentaje, ".")?>
            <?php  $porcentajeT = rtrim($porcentajeT, "0")?>
            <?php  $porcentajeT = rtrim($porcentajeT, ".")?>
          <?php endif ?>
        <?php $evidencia = json_decode($element['evidencia']) ?>
        <?php $count = count($evidencia) ?>
        <tr>
          <td><?= $element['tarea'] ?></td>
          <td><?= $element['usuario_tarea'] ?></td>
          <td><?= $element['roles'] ?></td>
          <td><?= $element['fecha_inicio_tarea'] ?></td>
          <td><?= $element['fecha_fin_tarea'] ?></td>
          <td><?= $count ?></td>
          <td><?= $porcentajeT ?>%</td>
          <td><?= $porcentaje ?>%</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>  
  <?php $contador++?>
<?php endforeach; ?>
