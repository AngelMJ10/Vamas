<page>
  <?php if ($datosP): ?>
    <?php  $porcentaje = $datosP['porcentaje']?>
      <?php   $porcentaje = rtrim($porcentaje, "0")?>
      <?php   $porcentaje = rtrim($porcentaje, ".")?>
    <?php endif ?>

  <div>
    <p>Área de sistemas</p>
  </div>
  <div class="mb-3">
    <h2 class="center text-md mb-3"><?= $datosP['titulo'] ?></h2>
  </div>
  <hr>
  <div class="mt-5">
    <p class="mb-3"><b>El proyecto titulado como:</b> <?= $datosP['titulo'] ?> .</p>
    <p class="mb-3"><b>Es un proyecto de:</b> <?= $datosP['tipoproyecto'] ?> .</p>
    <p class="mb-3"><b>Para la empresa:</b> <?= $datosP['nombre'] ?> .</p>
    <p class="mb-3"><b>Descripcion:</b> <?= $datosP['descripcion'] ?> .</p>
    <p class="mb-3"><b>Inicio:</b> <?= $datosP['fechainicio'] ?> . </p>
    <p class="mb-3"><b>Fin:</b> <?= $datosP['fechafin'] ?> .</p>
    <p class="mb-3"><b>Nº de fases:</b> <?= $datosP['Fases'] ?> .</p>
    <p class="mb-3"><b>Nº de tareas:</b> <?= $datosP['Tareas'] ?> .</p>
    <p class="mb-3"><b>Usuarios involucrados:</b> <?= $datosC['TotalUsuarios'] ?>  .</p>
    <p class="mb-3"><b>Está cotizado en</b> S/.<?= $datosP['precio'] ?> .</p>
    <p class="mb-3"><b>Porcentaje:</b> <?= $porcentaje ?>%  .</p>
  </div>

  <div class="page-break"></div>

</page>