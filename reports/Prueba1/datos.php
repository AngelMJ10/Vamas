<div class="mb-3">
  <h2 class="center text-md mb-3">Tarea Encargada</h2>
</div>
<hr>
<div>
  <table class="table table-border mb-5">
    <colgroup>
      <col style="width: 5%;">
      <col style="width: 20%;">
      <col style="width: 25%;">
      <col style="width: 10%;">
      <col style="width: 15%;">
      <col style="width: 15%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light">
        <th>ID</th>
        <th>Fase</th>
        <th>Tarea</th>
        <th>Usuario</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($datos as $registro): ?>
        <tr>
          <td><?= $registro['idtarea'] ?></td>
          <td><?= $registro['nombrefase'] ?></td>
          <td><?= $registro['tarea'] ?></td>
          <td><?= $registro['usuario_tarea'] ?></td>
          <td><?= $registro['fecha_inicio_tarea'] ?></td>
          <td><?= $registro['fecha_fin_tarea'] ?></td>
          <td><?= $registro['porcentaje_tarea'] ?></td>
        </tr>
      <?php endforeach?>
    </tbody>
  </table>

  <table class="table table-border">
    <colgroup>
      <col style="width: 10%;">
      <col style="width: 15%;">
      <col style="width: 25%;">
      <col style="width: 20%;">
      <col style="width: 15%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr class="bg-primary text-light center">
        <th>Emisor</th>
        <th>Receptor</th>
        <th>Mensaje</th>
        <th>Documento</th>
        <th>Fecha</th>
        <th>Avance</th>
      </tr>
    </thead>
    <tbody class='center'>
    <?php foreach ($datosE as $evidencia): ?>
        <?php $evidenciaArray = json_decode($evidencia['evidencia'], true); ?>
        <?php foreach ($evidenciaArray as $item): ?>
            <tr>
                <td><?= $item['colaborador'] ?></td>
                <td><?= $item['receptor'] ?></td>
                <td><?= $item['mensaje'] ?></td>
                <td><a href='<?= $item['documento'] ?>' target='_blank'>Enlace al documento</a></td>
                <td><?= $item['fecha'] ?></td>
                <td><?= $item['porcentaje'] ?>%</td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>

    </tbody>
  </table>


</div>