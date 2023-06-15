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
    <title>Proyectos</title>
</head>
<body>
<link rel="stylesheet" href="./css/style.css">
<style>
    body {
    background-image: url("../img/logos\ vamas_Mesa\ de\ trabajo\ 1\ copia\ 2.png");
    background-repeat: no-repeat;
    background-size: 50%;
    background-position: center ;
    opacity: 0.9;
  }
</style>

  <div class="capa text-center">
    <h1>PROYECTOS</h1>
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
                          <select name="tipoProyecto" id="tipoProyecto-search" class="form-control form-control-sm">
                            <label for="tipoProyecto">Seleccione el tipo de Proyecto:</label>
                          </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select name="idempresa" id="idempresa-search" placeholder="Tipo de empresa" class="form-control form-control-sm">
                              <label for="idempresa">Seleccione la empresa:</label>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="titulo-search" name="titulo" placeholder="Título">
                            <label for="titulo" class="form-label">Titulo</label>
                        </div>
                    </div>
                  </div>

                  <div class="row mb-3">

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <textarea class="form-control" id="descripcion-search" placeholder="Descripción" name="descripcion"></textarea>
                            <label for="descripcion" class="form-label">Descripción</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="date" class="form-control" placeholder="Fecha de Inicio" id="fecha-inicio-search" name="fechare">
                        <label form="fecha" class="form-label">Fecha de Inicio</label>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="date" class="form-control" placeholder="Fecha de Entrega" id="fecha-fin-search" name="fechare">
                        <label form="fecha" class="form-label">Fecha de Entrega</label>
                      </div>
                    </div>
                    
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" placeholder="Precio del Proyecto" id="precio-search" name="precio">
                        <label for="precio" class="form-label">Precio de proyecto</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                          <select name="estado" class="form-control" id="estado-seach">
                            <option value="">Seleccion un estado</option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                          </select>
                        </div>
                      </div>
                  </div>
                  <button type="button" id="search"  class="btn btn-outline-primary">Buscar</button>

                </form>
              </div>
            </div>
          </div>
        </div>
        
        <div class="table-responsive mt-3" id="tabla-proyecto">
          <table class="table table-hover"> 

              <thead>
                  <th>#</th>
                  <th>Titulo</th>
                  <th>Fecha de Inicio</th>
                  <th>Fecha de Fin</th>
                  <th>Porcentaje</th>
                  <th>Empresa</th>
                  <th>N° Fases</th>
                  <th>Estado</th>
                  <th>Acción</th>
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
                        <select name="tipoProyecto" id="tipoProyecto" class="form-control form-control-sm">
                          <label for="tipoProyecto">Seleccione el tipo de Proyecto:</label>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-floating mb-3">
                          <select name="idempresa" id="idempresa" class="form-control form-control-sm">
                            <label for="idempresa">Seleccione la empresa:</label>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título">
                          <label for="titulo" class="form-label">Titulo</label>
                      </div>
                  </div>
                </div>

                <div class="row mb-3">

                  <div class="col-md-4">
                      <div class="form-floating mb-3">
                          <textarea class="form-control" id="descripcion" placeholder="Descripción" name="descripcion"></textarea>
                          <label for="descripcion" class="form-label">Descripción</label>
                      </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input type="date" class="form-control" placeholder="Fecha de Inicio" id="fecha-inicio" name="fechare">
                      <label form="fecha" class="form-label">Fecha de Inicio</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input type="date" class="form-control" placeholder="Fecha de Entrega" id="fecha-fin" name="fechare">
                      <label form="fecha" class="form-label">Fecha de Entrega</label>
                    </div>
                  </div>
                  
                </div>

                <div class="row mb-3">
                  <div class="col-md-2">
                    <div class="form-floating mb-3">
                      <input type="number" class="form-control" placeholder="Precio del Proyecto" id="precio" name="precio">
                      <label for="precio" class="form-label">Precio de proyecto</label>
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
                  <select name="tipoProyecto" id="tipoProyecto-update" class="form-control form-control-sm">
                    <label for="tipoProyecto">Seleccione el tipo de Proyecto:</label>
                    <option value="">Seleccione</option>
                    <option value="1">Desarollo Web</option>
                    <option value="2">Sistema de ventas</option>
                    <option value="3">Sistema de almacen</option>
                    <option value="4">Marketing</option>
                  </select>
              </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <select name="idempresa" id="idempresa-update" class="form-control form-control-sm">
                      <label for="idempresa">Seleccione la empresa:</label>
                      <option value="">Seleccione</option>
                      <option value="1">VAMAS S.A.C.</option>
                      <option value="2">Mamá Carmen</option>
                      <option value="3">Prueba1</option>
                      <option value="4">Prueba2</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="titulo-update" name="titulo" placeholder="Título">
                    <label for="titulo" class="form-label">Titulo</label>
                </div>
            </div>
          </div>

          <div class="row mb-3">

            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="descripcion-update" placeholder="Descripción" name="descripcion"></textarea>
                    <label for="descripcion" class="form-label">Descripción</label>
                </div>
            </div>

            <div class="col-md-4">
              <div class="form-floating mb-3">
                <input type="date" class="form-control" placeholder="Fecha de Inicio" id="fecha-inicio-update" name="fechare">
                <label form="fecha" class="form-label">Fecha de Inicio</label>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-floating mb-3">
                <input type="date" class="form-control" placeholder="Fecha de Entrega" id="fecha-fin-update" name="fechare">
                <label form="fecha" class="form-label">Fecha de Entrega</label>
              </div>
            </div>
            
          </div>

          <div class="row mb-3">
            <div class="col-md-2">
              <div class="form-floating mb-3">
                <input type="number" class="form-control" placeholder="Precio del Proyecto" id="precio-update" name="precio">
                <label for="precio" class="form-label">Precio de proyecto</label>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-floating mb-3">
                <select name="tipoProyecto" id="estado-update" class="form-control form-control-sm">
                  <label for="tipoProyecto">Seleccione el tipo de Estado:</label>
                  <option value="">Seleccione</option>
                  <option value="0">Inactivo</option>
                  <option value="1">Activo</option>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-floating">
                <input type="text" disabled class="form-control" placeholder="Usuario" id="user-create" name="user">
                <label for="user" class="form-label">Creado por el usuario:</label>
              </div>
            </div>
          
          </div>
          <button type="button" id="update-datos"  class="btn btn-outline-primary">Agregar</button>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Fase -->
<div class="modal fade" id="modalFase" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Registrar Fase</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row mb-2 mt-2">
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" disabled class="form-control" id="titulo-phase" name="titulo" placeholder="Título">
                    <label for="titulo" class="form-label">Titulo</label>
                </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating mb-3">
                  <select name="tipoProyecto" disabled id="tipoProyecto-phase" class="form-control form-control-sm">
                    <label for="tipoProyecto">Seleccione el tipo de Proyecto:</label>
                  </select>
              </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <select name="idempresa" disabled id="idempresa-phase" class="form-control form-control-sm">
                      <label for="idempresa">Seleccione la empresa:</label>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" placeholder="Nombre de la fase" id="name-phase" name="phase">
                <label for="phase" class="form-label">Nombre de la fase</label>
              </div>
            </div>
            
          </div>

          <div class="row mb-3">
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

            <div class="col-md-3">
              <div class="form-floating mb-3">
                <input type="date" class="form-control" placeholder="Fecha de Fin" id="fecha-fin-phase" name="fechare">
                <label form="fecha" class="form-label">Fecha de Fin</label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating">
                <textarea name="comentario" class="form-control" placeholder="Comentario" id="comentario"></textarea>
                <label form="comentario" class="form-label">Comentario</label>
              </div>
            </div>
          
          </div>

          <button type="button" id="create-phase"  class="btn btn-outline-primary">Agregar</button>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Info -->
<div class="modal fade" id="modal-info" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header text-light" style='background-color: #005478;'>
        <h1 class="modal-title fs-5" id="modalEditarLabel">Información del Proyecto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="body-info">
      
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/proyecto.js"></script>
    
</body>
</html>