<page>
    <!-- Cabecera de la página -->
    <page_header>
        <p class='mb-1'>Area de sistemas</p>
        <hr class='b-simple'>
    </page_header>
    <!-- Pie de página -->
    <page_footer>
        <p class='center'><?= $piePagina ?></p>
        <p class='italic'>Pag. [[page_cu]]</p>
    </page_footer>

    <!-- Cuerpo de la página -->
    <div class='mt-5'>
        <h3 class='center mt-4'>Proyecto</h3>
        <table style="width:100%;" class='table table-border mt-3 center'>
          <colgroup>
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 10%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 10%;">
            <col style="width: 15%;">
          </colgroup>
            <thead>
                <tr class='text-light  bg-primary'>
                    <th style="width:15%;">Titulo</th>
                    <th style="width:15%;">Tipo de Proyecto</th>
                    <th style="width:10%;">Empresa</th>
                    <th style="width:12%;">Fecha Inicio</th>
                    <th style="width:12%;">Fecha Fin</th>
                    <th style="width:10%;">Precio</th>
                    <th style="width:15%;">Avance</th>
                </tr>
            </thead>
            <tbody>
                <tr class='center'>
                    <td><?= $datosP["titulo"] ?></td>
                    <td><?= $datosP["tipoproyecto"] ?></td>
                    <td><?= $datosP["nombre"] ?></td>
                    <td><?= $datosP["fechainicio"] ?></td>
                    <td><?= $datosP["fechafin"] ?></td>
                    <td>S/.<?= $datosP["precio"] ?></td>
                    <td><?= $porcentaje ?>%</td>
                </tr>
            </tbody>
        </table>

        <h3 class='center mt-4'>Fases</h3>
        <table style="width:100%;" class='table table-border mt-3 center'>
            <colgroup>
              <col style="width: 5%;">
              <col style="width: 20%;">
              <col style="width: 15%;">
              <col style="width: 14%;">
              <col style="width: 14%;">
              <col style="width: 10%;">
              <col style="width: 10%;">
              <col style="width: 10%;">
            </colgroup>
            <thead>
              <tr class='text-light bg-primary'>
                  <th>#</th>
                  <th>Fase</th>
                  <th>Encargado</th>
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

        <h2 class="center mt-5">Tareas por fase</h2>
        <?php $contador = 1?>
        <?php foreach ($datosF as $registro): ?>
          <br>
          <h3 class="mt-5">Fase Nº<?= $contador, " ", $registro['nombrefase']?></h3>
          <table style="width:100%;" class='table table-border mt-3 center'>
            <colgroup>
              <col style="width: 3%;">
              <col style="width: 20%;">
              <col style="width: 13%;">
              <col style="width: 14%;">
              <col style="width: 10%;">
              <col style="width: 10%;">
              <col style="width: 10%;">
              <col style="width: 8%;">
              <col style="width: 10%;">
            </colgroup>
            <thead>
                <tr class='text-light bg-primary'>
                    <th>#</th>
                    <th>Tarea</th>
                    <th>Encargado</th>
                    <th>Rol</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Nº Avanc.</th>
                    <th>Avance</th>
                    <th>Representa</th>
                </tr>
            </thead>
            <tbody>
              <?php $datosT = $registro['idfase']; ?>
              <?php $dataT = $fase->tablaFases(["idfase" => $datosT]) ?>
              <?php $contadorTarea = 1;?>
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
                  <td><?= $contadorTarea ?></td>
                  <td><?= $element['tarea'] ?></td>
                  <td><?= $element['usuario_tarea'] ?></td>
                  <td><?= $element['roles'] ?></td>
                  <td><?= $element['fecha_inicio_tarea'] ?></td>
                  <td><?= $element['fecha_fin_tarea'] ?></td>
                  <td><?= $count ?></td>
                  <td><?= $porcentajeT ?>%</td>
                  <td><?= $porcentaje ?>%</td>
                </tr>
              <?php $contadorTarea++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
          <br>
          <?php $contador++?>
        <?php endforeach; ?>

        <h2 class="center mt-5 mb-4">Usuario involucrados</h2>
        <table class="table table-border mb-5 center">
          <colgroup>
            <col style="width: 5%;">
            <col style="width: 20%;">
            <col style="width: 10%;">
            <col style="width: 30%;">
            <col style="width: 15%;">
            <col style="width: 10%;">
          </colgroup>
          <thead>
            <tr class="bg-primary text-light">
              <th>#</th>
              <th>Usuario</th>
              <th>Nivel</th>
              <th>Correo</th>
              <th>Nº Fases</th>
              <th>Nº Tareas</th>
            </tr>
          </thead>
          <tbody>
            <?php $contadorCo = 1;?>
            <?php foreach ($datosC_Trabajo as $elemento): ?>
              <tr>
                <td><?= $contadorCo ?></td>
                <td><?= $elemento['usuario']?></td>
                <td><?= $elemento['nivelacceso']?></td>
                <td><?= $elemento['correo']?></td>
                <td><?= $elemento['fases']?></td>
                <td><?= $elemento['tareas']?></td>
              </tr>
              <?php $contadorCo++;?>
            <?php endforeach;?>
          </tbody>
        </table>

    </div>
</page>

