<div class="mb-3">
  <h2 class="center text-md mb-3">Tarea :<?= $datosE[0]['tarea'] ?></h2>
</div>
<hr>
<div>
  <table class="table table-border mb-5 center">
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
      <?php foreach ($datosE as $registro): ?>
        <?= $porcentaje = $registro['porcentaje_tarea']?>
          <?php if($porcentaje): ?>
          <?=  $porcentaje = rtrim($porcentaje, "0")?>
          <?=  $porcentaje = rtrim($porcentaje, ".")?>
          <?php endif ?>
        <tr>
          <td><?= $registro['nombrefase'] ?></td>
          <td><?= $registro['tarea'] ?></td>
          <td><?= $registro['usuario_tarea'] ?></td>
          <td><?= $registro['fecha_inicio_tarea'] ?></td>
          <td><?= $registro['fecha_fin_tarea'] ?></td>
          <td><?= $porcentaje ?>%</td>
        </tr>
      <?php endforeach?>
    </tbody>
  </table>

      <h3 class="center mb-5 mt-5">Evidencias</h3>

  <table class="table table-border mt-3 center">
    <colgroup>
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 20%;">
      <col style="width: 20%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light center">
        <th>Emisor</th>
        <th>Receptor</th>
        <th>Mensaje</th>
        <th>Documento</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($datosE as $evidencia): ?>
        <?php $evidenciaArray = json_decode($evidencia['evidencia'], true); ?>
        <?php foreach ($evidenciaArray as $item): ?>
            <tr>
                <td><?= $item['colaborador'] ?></td>
                <td><?= $item['receptor'] ?></td>
                <td><?= $item['mensaje'] ?></td>
                <td><a href='<?= $item['documento'] ?>' target='_blank'>Enlace al documento</a></td>
                <td><?= $item['fecha'] ?></td>
                <td><?= $item['hora'] ?></td>
                <td><?= $item['porcentaje'] ?>%</td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>

    </tbody>
  </table>


</div>