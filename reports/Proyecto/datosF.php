<?php if ($datosP): ?>
  <?php  $porcentaje = $datosP['porcentaje']?>
    <?php   $porcentaje = rtrim($porcentaje, "0")?>
    <?php   $porcentaje = rtrim($porcentaje, ".")?>
  <?php endif ?>

<div>
  <img src="../../img/logos vamas_Mesa de trabajo 1 copia 2.png">
  <p>Area de sistemas</p>
</div>
<div class="mb-3">
  <h2 class="center text-md mb-3"><?= $datosP['titulo'] ?></h2>
</div>
<hr>
<div>
  <p class="mb-3">El proyecto titulado como: <?= $datosP['titulo'] ?> .</p>
  <p class="mb-3">Es un proyecto de <?= $datosP['tipoproyecto'] ?> .</p>
  <p class="mb-3">Para la empresa <?= $datosP['nombre'] ?> .</p>
  <p class="mb-3">Descripcion <?= $datosP['descripcion'] ?> .</p>
  <p class="mb-3">Inicio <?= $datosP['fechainicio'] ?> . </p>
  <p class="mb-3">Fin <?= $datosP['fechafin'] ?> .</p>
  <p class="mb-3">Nº de fases <?= $datosP['Fases'] ?> .</p>
  <p class="mb-3">Nº de tareas <?= $datosP['Tareas'] ?> .</p>
  <p class="mb-3">Usuarios involucrados <?= $datosC['TotalUsuarios'] ?>  .</p>
  <p class="mb-3">Está cotizado en S/.<?= $datosP['precio'] ?> .</p>
  <p class="mb-3">Porcentaje <?= $porcentaje ?>%  .</p>
</div>
<div style="page-break-after: always;"></div>