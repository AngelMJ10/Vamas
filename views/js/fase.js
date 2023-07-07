let idfase = 0;

    function getPhase(id) {
        const modal = document.querySelector("#modalPhase");
        const infoPhase = document.querySelector("#info-phase");
        const idproyecto = id; 
    
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "getPhase");
        parametrosURL.append("idfase", id);
    
        fetch('../controllers/fase.php', {
        method: 'POST',
        body: parametrosURL
        })
        .then(respuesta => {
        if (respuesta.ok) {
            return respuesta.text();
        } else {
            throw new Error('Error en la solicitud');
        }
        })
        .then(datos => {
            infoPhase.innerHTML = datos;
    
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        idfase = id;
        })
        .catch(error => {
        console.error('Error:', error);
        });
    }

    function list(){
        const table = document.querySelector("#tabla-fase");
        const bodytable = table.querySelector("tbody");

        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "list");

        fetch('../controllers/fase.php',{
            method: 'POST',
            body: parametrosURL
        })
        .then(respuesta => {
            if(respuesta.ok){
                return respuesta.text();
            } else{
                throw new Error('Error en la solicitud');
            }
        })
        .then(datos =>{
            bodytable.innerHTML = datos;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function listarProyectosSelect() {
        const selectProyecto = document.querySelector("#buscar-proyecto");
    
        const parametros = new URLSearchParams();
        parametros.append("op", "listarSelectProyecto");
        fetch('../controllers/proyecto.php', {
            method: 'POST',
            body: parametros
        })
            .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.text();
            } else {
                throw new Error('Error en la solicitud');
            }
            })
            .then(datos => {
            selectProyecto.innerHTML = datos;
            })
            .catch(error => {
            console.error('Error:', error);
            });
    }

    function createPhase(){
        const idproyecto = document.querySelector("#project-phase");
        const namephase = document.querySelector("#name-phase");
        const respnsible = document.querySelector("#responsible-phase");
        const fechainicio = document.querySelector("#fecha-inicio-phase");
        const fechafin = document.querySelector("#fecha-fin-phase");
        const porcentaje = document.querySelector("#porcentaje");
        const comentario = document.querySelector("#comentario");

        const confirmacion = confirm("¿Estás seguro de los datos ingresados para la fase?");

        if (confirmacion) {
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op", "registerPhase");
            parametrosURL.append("idproyecto", idproyecto.value);
            parametrosURL.append("idresponsable", respnsible.value);
            parametrosURL.append("nombrefase", namephase.value);
            parametrosURL.append("fechainicio", fechainicio.value);
            parametrosURL.append("fechafin", fechafin.value);
            parametrosURL.append("porcentaje", porcentaje.value);
            parametrosURL.append("comentario", comentario.value);
            

            fetch('../controllers/fase.php', {
                method: 'POST',
                body: parametrosURL
            })
            .then(respuesta =>{
                if(respuesta.ok){
                    alert('Fase registrada correctamente');
                    location.reload();
                } else{
                    alert('Error en la solicitud');
                }
            })
        }
    }

    function buscarFase(){
        const table = document.querySelector("#tabla-fase");
        const bodytable = table.querySelector("tbody");
        const selectProyecto = document.querySelector("#buscar-proyecto");

        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "buscarFase");
        parametrosURL.append("idproyecto", selectProyecto.value);

        fetch('../controllers/fase.php',{
            method: 'POST',
            body: parametrosURL
        })
        .then(respuesta => {
            if(respuesta.ok){
                return respuesta.text();
            } else{
                throw new Error('Error en la solicitud');
            }
        })
        .then(datos =>{
            bodytable.innerHTML = "";
            bodytable.innerHTML = datos;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

// Agregar Tarea

// Para abrir un miniModal de registro de tareas
    function openModalAgregarTarea(){
        const modalAgregarT = document.querySelector("#modal-agregar-t");
        const bootstrapModal = new bootstrap.Modal(modalAgregarT);
        bootstrapModal.show();

        function listarColaboradores_A(){
            const responsable = document.querySelector("#asignar-empleado");
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op", "listarColaborador_A");
        
            fetch('../controllers/proyecto.php',{
                method: 'POST',
                body: parametrosURL
            })
            .then(respuesta => {
                if(respuesta.ok){
                    return respuesta.text();
                } else{
                    throw new Error('Error en la solicitud');
                }
            })
            .then(datos =>{
                responsable.innerHTML = datos;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        listarColaboradores_A();
    }

    function agregarTarea(){
        const idcolaboradores = document.querySelector("#asignar-empleado");
        const roles = document.querySelector("#rol-empleado");
        const tarea = document.querySelector("#tarea-agregar");
        const porcentaje = document.querySelector("#agregar-porcentaje");
        const fecha_inicio_tarea = document.querySelector("#fecha-ini-tarea");
        const fecha_fin_tarea = document.querySelector("#fecha-f-tarea");
        const porcentajeValor = porcentaje.value;

        if (!roles.value || !tarea.value || !fecha_inicio_tarea.value || !fecha_fin_tarea.value || !porcentaje.value) {
            Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos.',
            });
            return;
        }
        
        if (isNaN(porcentajeValor) || porcentajeValor < 0 || porcentajeValor > 100) {
        Swal.fire({
            icon: 'warning',
            title: 'Porcentaje excedido',
            text: 'Por favor, ingrese un porcentaje válido de 0 a 100.',
        });
        return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            text: '¿Está seguro de los datos ingresados?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Creando tarea ...',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                const parametros = new URLSearchParams();
                parametros.append("op", "registrarTareaV2");
                parametros.append("idfase", idfase);
                parametros.append("idcolaboradores", idcolaboradores.value);
                parametros.append("roles", roles.value);
                parametros.append("tarea", tarea.value);
                parametros.append("porcentaje", porcentaje.value);
                parametros.append("fecha_inicio_tarea", fecha_inicio_tarea.value);
                parametros.append("fecha_fin_tarea", fecha_fin_tarea.value);

                fetch('../controllers/tarea.php', {
                    method: 'POST',
                    body: parametros
                })
                .then(respuesta => {
                if(respuesta.ok){
                    obtenerPorcentajeF();
                    obtenerPorcentajeP();
                    Swal.fire({
                        icon: 'success',
                        title: 'Tarea Registrada',
                        text: 'Tarea registrada correctamente'
                    })
                    .then(() => {
                        location.reload();
                    });
                } else{
                    throw new Error('Error en la solicitud');
                }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al registrar la tarea',
                        text: 'Ocurrió un error al registrar la tarea. Por favor, inténtelo nuevamente.'
                    })
                });
            }
        })
    }

    function listarHabilidades() {
    const empleadoSelect = document.querySelector("#asignar-empleado");
    const rolSelect = document.querySelector("#rol-empleado");

    const parametros = new URLSearchParams();
    parametros.append("op", "listar_Habilidades");
    parametros.append("idcolaboradores", empleadoSelect.value);

    fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametros
    })
        .then(respuesta => {
        if (respuesta.ok) {
            return respuesta.text();
        } else {
            throw new Error('Error en la solicitud');
        }
        })
        .then(datos => {
        rolSelect.innerHTML = datos;
        })
        .catch(error => {
        console.error('Error:', error);
        });
    }


// Editar Tarea
    function quitarRead() {
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
        const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

        const btnEditarT = document.querySelector("#quitar-readonly");
        const btnRtarea = document.querySelector("#generar-reporteT");

        nombreTarea.readOnly = false;
        usuarioTarea.readOnly = false;
        fechaIniTarea.readOnly = false;
        fechaFinTarea.readOnly = false;
        porcentajeTarea.readOnly = false;

        btnGuardarEdicion.classList.remove("d-none");
        btnCancelarEdicion.classList.remove("d-none");

        btnEditarT.classList.add("d-none");
        btnRtarea.classList.add("d-none");
        function listarColaboradores_A(){
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op", "listarColaborador_A");
        
            fetch('../controllers/proyecto.php',{
                method: 'POST',
                body: parametrosURL
            })
            .then(respuesta => {
                if(respuesta.ok){
                    return respuesta.text();
                } else{
                    throw new Error('Error en la solicitud');
                }
            })
            .then(datos =>{
                usuarioTarea.innerHTML = datos;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        listarColaboradores_A();
    }

    function addRead() {
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
        const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

        const btnEditarT = document.querySelector("#quitar-readonly");
        const btnRtarea = document.querySelector("#generar-reporteT");

        nombreTarea.readOnly = true;
        usuarioTarea.readOnly = true;
        fechaIniTarea.readOnly = true;
        fechaFinTarea.readOnly = true;
        porcentajeTarea.readOnly = true;

        btnGuardarEdicion.classList.add("d-none");
        btnCancelarEdicion.classList.add("d-none");

        btnEditarT.classList.remove("d-none");
        btnRtarea.classList.remove("d-none");
        modalInfoTarea(idtarea);
    }

    function editarTarea(){
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const rolTarea = document.getElementById('rol-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        const parametros = new URLSearchParams();
        parametros.append("op", "editarTarea");
        parametros.append("idtarea", idtarea);
        parametros.append("idcolaboradores", usuarioTarea.value);
        parametros.append("roles", rolTarea.value);
        parametros.append("tarea", nombreTarea.value);
        parametros.append("porcentaje", porcentajeTarea.value);
        parametros.append("fecha_inicio_tarea", fechaIniTarea.value);
        parametros.append("fecha_fin_tarea", fechaFinTarea.value);

        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametros
        })
            .then(respuesta => {
            if(respuesta.ok){
                obtenerPorcentajeF();
                obtenerPorcentajeP();
                alert('Tarea Editada correctamente');
                location.reload();
            } else{
                alert('Error en la solicitud');
            }
            })
            .catch(error => {
            console.error('Error:', error);
            });

    }

    // Modal de Tareas
    function modalInfoTarea(id) {
        const inputs = document.querySelector("#inputs-tarea");
        const modalInfoTarea = document.querySelector("#modal-info-tarea");
        const parametros = new URLSearchParams();
        parametros.append("op" ,"obtenerTarea");
        parametros.append("idtarea" , id);
        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametros
        })
        .then(respuesta => {
            if (respuesta.ok) {
            return respuesta.text();
            } else {
            throw new Error('Error en la solicitud');
            }
        })
        .then(datos => {
            inputs.innerHTML = datos;
            const bootstrapModal = new bootstrap.Modal(modalInfoTarea);
            bootstrapModal.show();
            verEvidenciasTarear(id)
            idtarea = id;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function verEvidenciasTarear(id) {
        const tabla_Tareas = document.querySelector("#tabla-info-tarea");
        const parametros = new URLSearchParams();
        parametros.append("op", "verEvidenciasT");
        parametros.append("idtarea", id);
        fetch('../controllers/tarea.php', {
        method: 'POST',
        body: parametros
        })
        .then(respuesta => {
        if (respuesta.ok) {
            return respuesta.text();
        } else {
            throw new Error('Error en la solicitud');
        }
        })
        .then(datos => {
        tabla_Tareas.innerHTML = datos;
        })
        .catch(error => {
        console.error('Error:', error);
        });
    }

    function generarReporteF(idfase){
        const parametros = new URLSearchParams();
        if(idfase > 0) {
        parametros.append("idfase", idfase);
        window.open(`../reports/Fase/reporteF.php?${parametros}`, '_blank');
        }
    }

    function obtenerPorcentajeF() {
        const formData = new FormData();
        formData.append("op", "obtenerPorcentajeF");
      
        fetch('../controllers/fase.php', {
          method: 'POST',
          body: formData
        })
        .then(respuesta => {
          if (respuesta.ok) {
          } else {
            throw new Error('Error en la solicitud');
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    function obtenerPorcentajeP() {
    const formData = new FormData();
    formData.append("op", "obtenerPorcentajeP");
    
    fetch('../controllers/proyecto.php', {
        method: 'POST',
        body: formData
    })
    .then(respuesta => {
        if (respuesta.ok) {
        } else {
        throw new Error('Error en la solicitud');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    }

listarProyectosSelect();
list();

// *Para tareas
// Para abrir un miniModal de registro de tareas
const btnAgregarT = document.querySelector("#agregar-tarea");
btnAgregarT.addEventListener("click", openModalAgregarTarea);

const btnBuscar = document.querySelector("#btn-habilidades");
btnBuscar.addEventListener("click", listarHabilidades);

// Para registrar tareas en el miniModal
const btnRegistrarTarea = document.querySelector("#registrar-tarea");
btnRegistrarTarea.addEventListener("click", agregarTarea);

// Para quitar o agregar la clase d-none
const btnEditarT = document.querySelector("#quitar-readonly");
btnEditarT.addEventListener("click",quitarRead);

const btnAddRead = document.querySelector("#cancelar-E-Tarea");
btnAddRead.addEventListener("click",addRead);

  // Para editar una tarea 
const btnEditarTarea = document.querySelector("#guardar-C-Tarea");
btnEditarTarea.addEventListener("click", editarTarea);

// Para buscar fases por el proyecto
const btnBuscarFase = document.querySelector("#buscar-por-proyecto");
btnBuscarFase.addEventListener("click", buscarFase);
