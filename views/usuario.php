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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <title>Colaboradores</title>
</head>
<body>
<link rel="stylesheet" href="./css/style.css">

  <div class="capa text-center">
    <h1>Colaboradores</h1>
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
      <!-- <li class="nav-item">
        <a class="nav-link" id="editar-tab" href="empresa.php">Empresa</a>
      </li> -->
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
        
        <div class="table-responsive mt-3" id="tabla-colaboradores">
          <table class="table table-hover"> 

              <thead>
                  <th>#</th>
                  <th>Usuario</th>
                  <th>Correo</th>
                  <th>Nivel</th>
                  <th>Apellidos</th>
                  <th>Nombres</th>
                  <th>Habilidades</th>
                  <th>Fases</th>
                  <th>Tareas</th>
                  <th>Acciones</th>
              </thead>

              <tbody>
              </tbody>
          
            </table>
        </div>
      </div>

      <div class="tab-pane fade mb-5" id="registrar" role="tabpanel" aria-labelledby="registrar-tab">
        <div class="card shadow-lg border-0">
            <div class="card-header text-white capa-listar py-3" style="background: #005478">
              <h4 class="card-title mb-0">Agregar nuevo registro <i class="bi bi-universal-access"></i></h4>
            </div>
            <div class="card-body">
              <form>
                <div class="row mb-2 mt-2">
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre">
                            <label for="nombre" class="form-label">Nombre</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="razonsocial" placeholder="Razón Social" name="razonsocial">
                            <label for="razonsocial" class="form-label">Razón Social</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select name="tipodocumento" id="tipodocumento" class="form-control form-control-sm">
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
                            <input type="number" class="form-control" id="documento" placeholder="Nro de Documento" name="documento">
                            <label for="documento" class="form-label">Documento</label>
                        </div>
                    </div>
                </div>
                <button type="button" id="registrar-datos"  class="btn btn-outline-primary">Agregar</button>

              </form>
            </div>
        </div>
      </div>

    </div>

  </div>


<!-- Modal -->
<div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Datos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row mb-2 mt-2">
              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" readonly id="usuario-editar" placeholder="Nombre" name="nombre">
                      <label for="nombre" class="form-label">Usuario</label>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="text" class="form-control" readonly id="correo-editar" placeholder="Razón Social" name="razonsocial">
                      <label for="razonsocial" class="form-label">Correo</label>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                    <select name="nivel" id="nivel-editar" readonly class="form-control form-control-sm">
                      <label for="nivel">Seleccione el nivel de acceso:</label>
                      <option value="">Seleccione</option>
                      <option value="A">Administrador</option>
                      <option value="S">Supervisor</option>
                      <option value="C">Colaborador</option>
                    </select>
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control"  readonly id="fases-editar" placeholder="Razón Social" name="razonsocial">
                    <label for="razonsocial" class="form-label">Fases encargadas</label>
                </div>
              </div>
              
          </div>

          <div class="row">

            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" readonly id="nombres-editar" placeholder="Razón Social" name="razonsocial">
                    <label for="razonsocial" class="form-label">Nombres</label>
                </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="apellidos-editar" placeholder="Razón Social" name="razonsocial">
                  <label for="razonsocial" class="form-label">Apellidos</label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating mb-3">
                  <textarea type="text" class="form-control" readonly id="habilidades-editar" placeholder="Razón Social" name="razonsocial"></textarea>
                  <label for="razonsocial" class="form-label">Habilidades</label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" readonly id="tareas-editar" placeholder="Razón Social" name="razonsocial">
                <label for="razonsocial" class="form-label">Tareas Asignadas</label>
              </div>
            </div>

          </div>

          <div class="row mb-2">
            <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" readonly id="documento-cola" placeholder="N° Documento" name="nrodocumento">
                  <label for="nrodocumento" class="form-label">N° Documento</label>
                </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" readonly id="telefono-cola" placeholder="Teléfono" name="telefono">
                <label for="telefono" class="form-label">Teléfono</label>
              </div>
            </div>
          </div>

          <div class="btn-group">
            <button type="button" id="editar-colaborador"  class="btn btn-outline-primary">Editar</button>
            <button type="button" id="guardar-edicion"  class="btn btn-outline-success d-none">Guardar Cambios</button>
            <button type="button" id="cancelar-edicion"  class="btn btn-outline-danger d-none">Cancelar</button>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar habilidades -->
  <div class="modal fade" id="modal-habilidades" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header text-light" style='background-color: #005478;'>
          <h1 class="modal-title fs-5" id="modalEditarLabel">Editar Datos</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-10">
              <select name="so" id="habilidadesCol" class="form-select">
                <option value="">Seleccione las habilidades</option>
                <option value="Front-end Básico">Front-end Básico</option>
                <option value="Front-end Intermedio">Front-end Intermedio</option>
                <option value="Front-end Avanzado">Front-end Avanzado</option>
                <option value="Front-end Framework React">Front-end Framework React</option>
                <option value="Back-end Básico">Back-end Básico</option>
                <option value="Back-end Intermedio">Back-end Intermedio</option>
                <option value="Back-end Avanzado">Back-end Avanzado</option>
                <option value="Back-end FrameWork Laravel">Back-end FrameWork Laravel</option>
                <option value="Diseño Gráfico">Diseño Gráfico</option>
              </select>
            </div>

            <div class="col-md-2">
              <div class="d-grid">
                <button type="button" id="registrar-habilidad" class="btn btn-outline-success">Asignar Habilidad</button>
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

  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/colaboradores.js"></script>

</body>
</html>