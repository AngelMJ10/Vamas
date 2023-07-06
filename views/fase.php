<?php require_once './permisos.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/24503cbed7.js" crossorigin="anonymous"></script>
    <title>Fases</title>
</head>
<body>
<link rel="stylesheet" href="./css/style.css">

  <div class="capa text-center">
    <h1>FASES</h1>
  </div>
  <div class="container py-5">
    <!-- Navs -->
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="listar-tab" data-bs-toggle="tab" href="#listar" role="tab" aria-controls="listar" aria-selected="true">Listar</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="registrar-tab" data-bs-toggle="tab" href="#registrar" role="tab" aria-controls="registrar" aria-selected="false">Registrar</a>
      </li>
    </ul>

    <!-- Tabs -->
    <div class="tab-content" id="myTabContent">

      <div class="tab-pane fade show active" id="listar" role="tabpanel" aria-labelledby="listar-tab">

        <div class="accordion" id="acordion1">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filtros" aria-expanded="true" aria-controls="collapseOne">
                Filtros
              </button>
            </h2>
            <div id="filtros" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#acordion1">
              <div class="accordion-body">
              <form>
                <div class="row mb-2 mt-2">
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="buscar-nombre" placeholder="Nombre" name="nombre">
                            <label for="nombre" class="form-label">Nombre</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="buscar-razonsocial" placeholder="Razón Social" name="razonsocial">
                            <label for="razonsocial" class="form-label">Razón Social</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select name="tipodocumento" id="buscar-tipodocumento" class="form-control form-control-sm">
                              <label for="tipodocumento">Seleccione el tipo de Documento:</label>
                              <option value="">Seleccione</option>
                              <option value="DNI">DNI</option>
                              <option value="RUC">RUC</option>
                              <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="buscar-documento" placeholder="Nro de Documento" name="documento">
                            <label for="documento" class="form-label">Documento</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="buscar-documento" placeholder="Nro de Documento" name="documento">
                            <label for="documento" class="form-label">Estado</label>
                        </div>
                    </div>
                </div>
                <button type="button" id="buscar-empresa"  class="btn btn-outline-primary">Buscar</button>

              </form>
              </div>
            </div>
          </div>
        </div>
        
        <div class="table-responsive mt-3" id="tabla-fase">
          <table class="table table-hover"> 

              <thead>
                  <th>#</th>
                  <th>Nombre del Proyecto</th>
                  <th>Nombre de la Fase</th>
                  <th>Responsable</th>
                  <th>Inicio de la Fase</th>
                  <th>Fin de la Fase</th>
                  <th>Porcentaje</th>
                  <th>Estado</th>
                  <th>Acción</th>
              </thead>

              <tbody>
              </tbody>
          
            </table>
        </div>
      </div>

    </div>

  </div>


<!-- Modal-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Datos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row mb-2 mt-2">
              <div class="col-md-4">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="nombre-editar" placeholder="Nombre" name="nombre">
                      <label for="nombre" class="form-label">Nombre</label>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="razonsocial-editar" placeholder="Razón Social" name="razonsocial">
                      <label for="razonsocial" class="form-label">Razón Social</label>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-floating mb-3">
                      <select name="tipodocumento" id="tipodocumento-editar" class="form-control form-control-sm">
                        <label for="tipodocumento">Seleccione el tipo de Documento:</label>
                        <option value="">Seleccione</option>
                        <option value="DNI">DNI</option>
                        <option value="RUC">RUC</option>
                        <option value="Pasaporte">Pasaporte</option>
                      </select>
                  </div>
              </div>
              
          </div>

          <div class="row mb-2">
              <div class="col-md-4">
                  <div class="form-floating mb-3">
                      <input type="number" class="form-control" id="documento-editar" placeholder="Nro de Documento" name="documento">
                      <label for="documento" class="form-label">Documento</label>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mb-3">
                  <select name="estado" id="estado-editar" class="form-control form-control-sm">
                    <label for="estado">Seleccione el tipo estado:</label>
                    <option value="">Seleccione</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                  </select>
                </div>
              </div>
          </div>
          <button type="button" id="editar-registro"  class="btn btn-outline-primary">Agregar</button>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Info -->
<div class="modal fade" id="modalPhase" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Información sobre la Fase</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="info-phase"></div>
        <div class="btn-group">
            <button class="btn btn-outline-primary" id="agregar-tarea">Agregar nueva tarea</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal agregar Tarea -->
<div class="modal fade" id="modal-agregar-t" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Agregar Tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form action="">
              <div class="row mb-3">
                <div class="col-md-6 form-floating mb-3">
                    <select name="tipoProyecto" id="asignar-empleado" class="form-control form-control-sm">
                      <label for="tipoProyecto">Seleccione el empleado:</label>
                      <option value="">Seleccione el empleado</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" id="btn-habilidades">Habilidades</button>
                </div>

                <div class="col-md-6 form-floating mb-3">
                    <select name="tipoProyecto" id="rol-empleado" class="form-control form-control-sm">
                      <label for="tipoProyecto">Seleccione el Rol:</label>
                      <option value="">Seleccione el rol</option>
                    </select>
                </div>

              </div>
              <div class="row mb-3">
                <div class="col-md-6 form-floating mb-3">
                  <input type="text" name="tarea" class="form-control" id="tarea-agregar"  placeholder="Tarea">
                  <label for="tarea" class="form-label">Tarea</label>
                </div>
              
                <div class="col-md-6 form-floating mb-3">
                  <input type="number" class="form-control" name="porcentaje" id="agregar-porcentaje"  placeholder="Porcentaje">
                  <label for="porcentaje" class="form-label">Porcentaje</label>
                </div>

              </div>
              <div class="row mb-3">
                <div class="col-md-6 form-floating mb-3">
                  <input type="date" class="form-control" name="fecha-ini" id="fecha-ini-tarea"  placeholder="Fecha Inicio">
                  <label for="fecha-ini" class="form-label">Fecha de inicio</label>
                </div>
              
                <div class="col-md-6 form-floating mb-3">
                  <input type="date" name="fecha-fin" class="form-control" id="fecha-f-tarea"  placeholder="Fecha Fin">
                  <label for="fecha-fin" class="form-label">Fecha de Fin</label>
                </div>

              </div>
              <button type='button' class="btn btn-outline-primary" id="registrar-tarea">Registrar</button>
          </form>

      </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Info de la tarea y evidencias -->
<div class="modal fade" id="modal-info-tarea" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Información de la Tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="body-info">
        <div class="mb-5" id="inputs-tarea">
        </div>

        <div id="tabla-info-tarea">
        </div>
        <div class="btn-group">
          <button class="btn btn-outline-success" id="quitar-readonly">Editar Tarea</button>
          <button class="btn btn-outline-danger" id="generar-reporteT">Ver Reporte</button>
          <button class="btn btn-outline-primary d-none" id="guardar-C-Tarea">Guardar Cambios</button>
          <button class="btn btn-outline-danger d-none" id="cancelar-E-Tarea">Cancelar Edicion</button>
        </div>
      </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/fase.js"></script>
    
</body>
</html>