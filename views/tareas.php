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
    <h1>Tareas</h1>
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

    <!-- Tab de listar -->
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
        
        <div class="table-responsive mt-3" >
          <table class="table table-hover" id="tabla-tareas"> 
            </table>
        </div>
      </div>

    <!-- Tab para registrar -->
      <div class="tab-pane fade mb-5" id="registrar" role="tabpanel" aria-labelledby="registrar-tab">
        <div class="card shadow-lg border-0">
            <div class="card-header text-white capa-listar py-3" style="background: #005478">
              <h4 class="card-title mb-0">Agregar nuevo registro <i class="bi bi-universal-access"></i></h4>
            </div>
            <div class="card-body">
            <form>
              <div class="row mb-2 mt-2">
                <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <select name="project" id="project-phase" class="form-control form-control-sm">
                        <label for="project">Seleccione el Proyecto:</label>
                      </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Nombre de la fase" id="name-phase" name="phase">
                    <label for="phase" class="form-label">Nombre de la fase</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating mb-3">
                    <select name="responsible" id="responsible-phase" class="form-control form-control-sm">
                      <label for="responsible">Seleccione el responsable de la fase:</label>
                    </select>
                  </div>
                </div> 
                <div class="col-md-3">
                  <div class="form-floating mb-3">
                    <input type="date" class="form-control" placeholder="Fecha de Inicio" id="fecha-inicio-phase" name="fechare">
                    <label form="fecha" class="form-label">Fecha de Inicio</label>
                  </div>
                </div>

              </div>

              <div class="row mb-3">
                
                <div class="col-md-3">
                  <div class="form-floating mb-3">
                    <input type="date" class="form-control" placeholder="Fecha de Fin" id="fecha-fin-phase" name="fechare">
                    <label form="fecha" class="form-label">Fecha de Fin</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <textarea name="comentario" class="form-control" placeholder="Comentario" id="comentario"></textarea>
                    <label for="comentario" class="form-label">Comentario</label>
                  </div>
                </div>
              
              </div>

              <button type="button" id="create-phase"  class="btn btn-outline-primary">Agregar</button>

            </form>
            </div>
        </div>
      </div>

    </div>

  </div>


<!-- Modal info de tareas -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Información de la Tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          
          <div class="row mb-2 mt-2">

              <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" readonly id="Nproyecto" placeholder="Proyecto" name="proyecto">
                    <label for="proyecto" class="form-label">Nombre del Proyecto</label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" readonly id="Nfase" placeholder="Fase" name="fase">
                    <label for="fase" class="form-label">Nombre de la fase</label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="Ntarea" placeholder="Tarea" name="tarea">
                  <label for="tarea" class="form-label">Nombre de la Tarea</label>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <textarea type="text" class="form-control" readonly id="comentario-fase" placeholder="comentario de la fase" name="comentario-fase"></textarea>
                  <label for="comentario-tarea" class="form-label">comentario fase</label>
                </div>
              </div>

          </div>

          <div class="row mb-3">

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" readonly id="inicio-fase" placeholder="Inicio de la fase" name="documento">
                      <label for="inicio-fase" class="form-label">Inicio Fase</label>
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="fin-fase" placeholder="Fin de la fase" name="fin-fase">
                  <label for="fin-fase" class="form-label">Fin Fase</label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="inicio-tarea" placeholder="Fin de la tarea" name="inicio-tarea">
                  <label for="inicio-tarea" class="form-label">Inicio Tarea</label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="fin-tarea" placeholder="Fin de la tarea" name="fin-tarea">
                  <label for="fin-tarea" class="form-label">Fin Tarea</label>
                </div>
              </div>

          </div>

          <div class="row mb-3">

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="usuario-Fase" placeholder="Usuario encargado" name="usuario-fase">
                  <label for="usuario-fase" class="form-label">Supervisor de Fase</label>
                </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" readonly id="usuario-tarea" placeholder="Usuario asignado" name="usuario">
                      <label for="usuario" class="form-label">Encargado de la tarea</label>
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="rol" placeholder="Rol" name="rol">
                  <label for="rol" class="form-label">Rol</label>
                </div>
              </div>

              
          </div>

        </form>

        <div class="btn-group">
          <button type="button" id="generarpdf-tarea" class="btn btn-outline-danger">Generar Reporte</button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-3" id="tabla-evidencias">
            <thead>
              <tr>
                <th>#</th>
                <th>Emisor</th>
                <th>Receptor</th>
                <th>Mensaje</th>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Porcentaje</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para enviar tareas -->
<div class="modal fade" id="modalWork" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Enviar Trabajo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="info-phase">

        <div class="card text-left">
          <div class="card-header">
              <h4 class="card-title">Enviar trabajo</h4>
          </div>
          <div class="card-body">
              <div class="mb-3">
                  <div class="input-group mb-3">
                      <span class="input-group-text bg-light" id="correo2">Para:</span>
                      <select name="correo" id="correo3" class="form-control form-control-sm" aria-label="correo" aria-describedby="correo"></select>
                  </div>
              </div>

              <div class="mb-3">
                  <div class="input-group mb-3">
                      <span class="input-group-text bg-light" id="asunto2">Asunto</span>
                      <input type="text" class="form-control" id="asunto" readonly placeholder="Asunto" aria-label="asunto" aria-describedby="asunto">
                  </div>
              </div>

              <div class="mb-3">
                  <div class="input-group mb-3">
                      <input type="file" class="form-control form-control-sm text-right mb-3" id="documento">
                  </div>
              </div>

              <div class="mb-3">
                  <div class="input-group mb-3">
                      <span class="input-group-text bg-light" id="">Mensaje</span>
                      <textarea type="text" class="form-control" id="mensaje" placeholder="Mensaje" aria-label="asunto" aria-describedby="mensaje"></textarea>
                  </div>
              </div>

              <div class="mb-3">
                  <div class="input-group mb-3">
                      <span class="input-group-text bg-light" id="porcentaje2">Porcentaje de la tarea</span>
                      <input type="number" class="form-control" id="porcentaje" placeholder="Porcentaje %" aria-label="asunto" aria-describedby="asunto">
                  </div>
              </div>

              <div class="d-flex justify-content-center">
                  <button type="button" class="btn btn-outline-success w-50" id="enviarTarea">Enviar Tarea</button>
              </div>
          </div>
      </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/tarea.js"></script>
    
</body>
</html>