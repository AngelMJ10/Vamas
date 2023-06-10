<?php
session_start();
if (!isset($_SESSION['login']) || !$_SESSION['login']['status']){
    header("Location:../");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bienvenidos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/24503cbed7.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template-->
    <!-- <link href="css/sb-admin-2.min.css" rel="stylesheet"> -->
    <link href="./css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- SIDEBAR -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Inicio</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Sección A
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Opciones generales</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Lista de opciones:</h6>
                        <a class="collapse-item" href="#">Opción 1</a>
                        <a class="collapse-item" href="#">Opción 2</a>
                        <a class="collapse-item" href="#">Opción 3</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Sección B
            </div>

            <!-- OPCIONES QUE DEBEN SER FILTRADAS DE ACUERD AL PERFIL -->
            <?php require_once './opciones.php' ?>
            <!-- FIN OPCIONES DEL SIDEBAR -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Botón circular Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- FIN Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar"
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $_SESSION['login']['usuario'] ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="./img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Datos de perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cambiar contraseña
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuración
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../controllers/colaboradores.php?op=close-session">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Aquí cargará el contenido dinámico -->
                <!-- Begin Page Content -->
                <div class="container-fluid" id="content-dinamics">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-left mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h4 class="text-primary card-title">Proyectos</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <i class="fa-solid fa-diagram-project fa-3x"></i>
                                        </div>
                                    </div>
                                    
                                    <p id="num-projects" class="card-text"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-left mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="text-warning card-title">Usuarios</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <i class="fa-solid fa-user fa-3x"></i>
                                        </div>
                                    </div>        
                                    <p id="num-users" class="card-text"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-left mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="text-danger card-title">Proyecto Finalizados</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <i class="fa-solid fa-list-check fa-3x"></i>
                                        </div>
                                    </div>      
                                    <p id="num-project-fin" class="card-text"></p>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <!-- /.container-fluid -->
                <!-- Fin contenido dinámico -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Todos los derechos reservados &copy; SENATI 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="./js/sb-admin-2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () =>{
            // Obtiene el nombre del enlace(vista);
            function getURL(){
                // Paso 1. Obtener la URL (barra de direcciones)
                const url = new URL(window.location.href);
                // Paso 2. ¿Qué vista desea cargar el usuario?
                const vista = url.searchParams.get("view");
                console.log(vista);
                //Paso 3. Crear objeto conetenedor de vistas
                const contenedor = document.querySelector("#content-dinamics");
                // Paso 4. Conociendo el nombre de la vista, la enviaremos al contenedor
                if (vista != null) {
                    fetch(vista)
                        .then(respuesta => respuesta.text())
                        .then(datos => {
                            contenedor.innerHTML = datos;
                            
                            // Accedemos a todos los bloques SCRIPT integrados en la vista
                            const tagscripts = contenedor.getElementsByTagName("script");
                            console.log(tagscripts);
                            //Activar el bloque SCRIPT
                            for (let i = 0; i < tagscripts.length; i++) {
                                // Si el bloque SCRIPT tiene el atributo src, cargar la URL
                                if (tagscripts[i].src) {
                                    const scriptElement = document.createElement("script");
                                    scriptElement.src = tagscripts[i].src;
                                    document.head.appendChild(scriptElement);
                                } else {
                                // Si no tiene el atributo src, ejecutar el contenido del bloque
                                // Solo utiliza eval(),cuando sepa la fuente dek archivo
                                eval(tagscripts[i].innerText);
                                }
                            }
                        });
                }
                
                //Conociendo el nombre de la vista, la enviaremos al contenedo del dashboard
                
            }

            function getNum() {
                const numProjects = document.querySelector("#num-projects");
                const numUsers = document.querySelector("#num-users");
                const numFinishProjects = document.querySelector("#num-project-fin");
                
                const countProjectsParams = new URLSearchParams();
                countProjectsParams.append("op", "countProjects");
                
                const countUsersParams = new URLSearchParams();
                countUsersParams.append("op", "countUsers");
                
                const countFinishProjectsParams = new URLSearchParams();
                countFinishProjectsParams.append("op", "countFinishProjects");
                
                fetch('../controllers/proyecto.php', {
                    method: 'POST',
                    body: countProjectsParams
                })
                .then(respuesta => {
                    if (respuesta.ok) {
                        return respuesta.json();
                    } else {
                        throw new Error('Error en la solicitud');
                    }
                })
                .then(datos => {
                    numProjects.innerHTML = datos;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                
                fetch('../controllers/proyecto.php', {
                    method: 'POST',
                    body: countUsersParams
                })
                .then(respuesta => {
                    if (respuesta.ok) {
                        return respuesta.json();
                    } else {
                        throw new Error('Error en la solicitud');
                    }
                })
                .then(datos => {
                    numUsers.innerHTML = datos;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                
                fetch('../controllers/proyecto.php', {
                    method: 'POST',
                    body: countFinishProjectsParams
                })
                .then(respuesta => {
                    if (respuesta.ok) {
                        return respuesta.json();
                    } else {
                        throw new Error('Error en la solicitud');
                    }
                })
                .then(datos => {
                    numFinishProjects.innerHTML = datos;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            getNum();
            getURL();
        });
    </script>

</body>

</html>