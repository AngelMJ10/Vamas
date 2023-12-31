let idfase = 0;
let idtarea = 0;
let lienzo = document.querySelector("#grafico-fase");

    // Función para obtener traer las cajas de texto con la información de la fase
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

    // Finalizar una tarea por su ID
    function finalizarTarea(id){
        Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro de finalizar esta tarea?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        }).then((result) => {
        if (result.isConfirmed) {
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op" ,"finalizar_tarea_by_id");
            parametrosURL.append("idtarea" ,id);
            fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametrosURL
            })
            .then(respuesta =>{
                if(respuesta.ok){
                Swal.fire({
                    icon: 'success',
                    title: 'Tarea Finalizada',
                    text: 'La tarea ha sido finalizada con éxito.'
                }).then(() => {
                    location.reload();
                });
                } else{
                throw new Error('Error en la solicitud');
                }
            })
            .catch(error => {
            console.error('Error:', error);
            Swal.alert({
                icon: 'Error',
                title: 'Error al finalizar la tarea',
                text: 'Ocurrió un error al finalizar la tarea. Por favor intentelo nuevamente.'
            })
            });
        }
        })
    }

    // Reactivar una tarea por su ID
    function reactivarTarea(id){
        Swal.fire({
        icon: 'question',
        title: 'Confirmación',
        text: '¿Está seguro de reactivar esta tarea?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        }).then((result) => {
        if (result.isConfirmed) {
            const parametrosURL = new URLSearchParams();
            parametrosURL.append("op" ,"reactivar_tarea_by_id");
            parametrosURL.append("idtarea" ,id);
            fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametrosURL
            })
            .then(respuesta =>{
                if(respuesta.ok){
                Swal.fire({
                    icon: 'success',
                    title: 'Tarea reactivada',
                    text: 'La tarea ha sido reactivada con éxito.'
                }).then(() => {
                    location.reload();
                });
                } else{
                throw new Error('Error en la solicitud');
                }
            })
            .catch(error => {
            console.error('Error:', error);
            Swal.alert({
                icon: 'Error',
                title: 'Error al reactivar la tarea',
                text: 'Ocurrió un error al reactivar la tarea. Por favor intentelo nuevamente.'
            })
            });
        }
        })
    }

    // Función para listar las fases
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

    // Función para generar un reporte de la fase
    function generarReporteF(idfase){
        const parametros = new URLSearchParams();
        if(idfase > 0) {
        parametros.append("idfase", idfase);
        window.open(`../reports/Fase/reporteF.php?${parametros}`, '_blank');
        }
    }

    // Función para listar los proyectos en los filtros
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

    // Función para buscar fases
    function buscarFase(){
        const table = document.querySelector("#tabla-fase");
        const bodytable = table.querySelector("tbody");
        const selectProyecto = document.querySelector("#buscar-proyecto");
        const nombrefase = document.querySelector("#nombre-fase-buscar");
        const supervisor = document.querySelector("#buscar-supervisor");
        const estadoFase = document.querySelector("#buscar-estado");

        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "buscarFase");
        parametrosURL.append("idproyecto", selectProyecto.value);
        parametrosURL.append("nombrefase", nombrefase.value);
        parametrosURL.append("idresponsable", supervisor.value);
        parametrosURL.append("estado", estadoFase.value);

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
        const fecha_inicio_tarea = document.querySelector("#fecha-ini-tarea");
        const fecha_fin_tarea = document.querySelector("#fecha-f-tarea");
        bootstrapModal.show();

        const parametos = new URLSearchParams();
        parametos.append("op", "obtenerFase");
        parametos.append("idfase", idfase);
        fetch('../controllers/fase.php', {
            method: 'POST',
            body: parametos,
        })
        .then(respuesta => respuesta.json())
        .then(datos=> {
            fecha_inicio_tarea.min = datos.fechainicio;
            fecha_inicio_tarea.max = datos.fechafin;
            fecha_fin_tarea.max = datos.fechafin;
            fecha_inicio_tarea.addEventListener("change", function() {
                fecha_fin_tarea.min = fecha_inicio_tarea.value;
            });    
            function agregarLabelsT(){
                // Debajo de fecha inicio
                const fechaInicioProyecto = document.querySelector("#fecha-inicio-fase");
                fechaInicioProyecto.textContent = '';
                const fechaIni = document.createElement("label");
                fechaIni.classList.add("form-label", "text-muted", "h6");
                fechaIni.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
                fechaInicioProyecto.appendChild(fechaIni);
        
                // Debajo de fecha Fin
                const fechaFinProyecto = document.querySelector("#fecha-fin-fase");
                fechaFinProyecto.textContent = '';
                const fechaFinP = document.createElement("label");
                fechaFinP.classList.add("form-label", "text-muted", "h6");
                fechaFinP.textContent = "Duración: " + datos.fechainicio + " / " + datos.fechafin;
                fechaFinProyecto.appendChild(fechaFinP);
            }
            agregarLabelsT();
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
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

    // Función para registrar una tarea
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

        // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
        if (fecha_inicio_tarea.value < fecha_inicio_tarea.min) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha de Inicio inválida',
                text: 'La fecha de inicio de la tarea debe ser mayor o igual a la fecha de inicio de la fase.',
            });
            return;
        }

        // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
        if (fecha_fin_tarea.value > fecha_fin_tarea.max) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha fin inválida',
                text: 'La fecha fin de la tarea debe ser menor o igual a la fecha de cierre de la fase.',
            });
            return;
        }

        // En el caso de la fecha de inicio exceda el maximo de la fecha de fin de la fase
        if(fecha_inicio_tarea.value > fecha_fin_tarea.value){
            Swal.fire({
                icon: 'warning',
                title: 'Fechas inválidas',
                text: 'La fecha de inicio debe ser menor o igual a la fecha de cierre.',
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

    // Función para listar las habilidades del colaborador
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

    // Función para listar los supervisores en los filtros
    function listarColaboradores(){
        const responsable = document.querySelector("#buscar-supervisor");
        const parametrosURL = new URLSearchParams();
        parametrosURL.append("op", "listarColaborador");
  
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


// Editar Tarea
    // Función para quitar la función readonly para poder editar la tarea
    function quitarRead() {
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const rolTarea = document.getElementById('rol-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
        const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

        const btnEditarT = document.querySelector("#quitar-readonly");
        const btnRtarea = document.querySelector("#generar-reporteT");

        nombreTarea.readOnly = false;
        usuarioTarea.readOnly = false;
        rolTarea.readOnly = false;
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

    // Función para cancelar la edición
    function addRead() {
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const rolTarea = document.getElementById('rol-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        const btnGuardarEdicion = document.querySelector("#guardar-C-Tarea");
        const btnCancelarEdicion = document.querySelector("#cancelar-E-Tarea");

        const btnEditarT = document.querySelector("#quitar-readonly");
        const btnRtarea = document.querySelector("#generar-reporteT");

        nombreTarea.readOnly = true;
        usuarioTarea.readOnly = true;
        rolTarea.readOnly = true;
        fechaIniTarea.readOnly = true;
        fechaFinTarea.readOnly = true;
        porcentajeTarea.readOnly = true;

        btnGuardarEdicion.classList.add("d-none");
        btnCancelarEdicion.classList.add("d-none");

        btnEditarT.classList.remove("d-none");
        btnRtarea.classList.remove("d-none");
        modalInfoTarea(idtarea);
    }

    // Función para editar la tarea
    function editarTarea(){
        const nombreTarea = document.getElementById('nombre-tarea');
        const usuarioTarea = document.getElementById('usuario-tarea');
        const rolTarea = document.getElementById('rol-tarea');
        const fechaIniTarea = document.getElementById('fecha-inicio-tarea');
        const fechaFinTarea = document.getElementById('fecha-fin-tarea');
        const porcentajeTarea = document.getElementById('porcentaje-tarea');

        if (!nombreTarea.value || !usuarioTarea.value || !fechaIniTarea.value || !fechaFinTarea.value || !porcentajeTarea.value || !rolTarea.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, completa todos los campos.',
            });
            return;
        }

         // En el caso que la fecha de inicio sea menor a la fecha de inicio de la fase
        if (fechaIniTarea.value < fechaIniTarea.min) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha de Inicio inválida',
                text: 'La fecha de inicio de la tarea debe ser mayor o igual a la fecha de inicio de la fase.',
            });
            return;
        }
    
        // En el caso de la fecha de fin exceda el maximo de la fecha de fin de la fase
        if (fechaFinTarea.value > fechaFinTarea.max) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha fin inválida',
                text: 'La fecha fin de la tarea debe ser menor o igual a la fecha de cierre de la fase.',
            });
            return;
        }
    
        // En el caso de la fecha de inicio exceda el maximo de la fecha de fin de la fase
        if(fechaIniTarea.value > fechaFinTarea.value){
            Swal.fire({
                icon: 'warning',
                title: 'Fechas inválidas',
                text: 'La fecha de inicio debe ser menor o igual a la fecha de cierre.',
            });
            return;
        }

        if (isNaN(porcentajeTarea.value) || porcentajeTarea.value < 0 || porcentajeTarea.value > 100) {
        Swal.fire({
            icon: 'warning',
            title: 'Porcentaje excedido',
            text: 'Por favor, ingrese un porcentaje de 0 a 100.',
        });
        }

        const parametros = new URLSearchParams();
        parametros.append("op", "editarTarea");
        parametros.append("idtarea", idtarea);
        parametros.append("idcolaboradores", usuarioTarea.value);
        parametros.append("roles", rolTarea.value);
        parametros.append("tarea", nombreTarea.value);
        parametros.append("porcentaje", porcentajeTarea.value);
        parametros.append("fecha_inicio_tarea", fechaIniTarea.value);
        parametros.append("fecha_fin_tarea", fechaFinTarea.value);

        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            text: '¿Está seguro de los datos modificados?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Tarea Actualizada',
                        text: 'La tarea ha sido actualizada correctamente.'
                    }).then(() => {
                        location.reload();
                    });
                    } else{
                    throw new Error('Error en la solicitud');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.alert({
                    icon: 'Error',
                    title: 'Error al editar la tarea',
                    text: 'Ocurrió un actualizar la tarea. Por favor intentelo nuevamente.'
                    })
                });
                }
            })

    }

    // Función para listar las habilidades de los colaboradores
    function listarHabilidadesEditar() {
        const usuarioTarea = document.getElementById('usuario-tarea');
        const rolTarea = document.getElementById('rol-tarea');
        
        const parametros = new URLSearchParams();
        parametros.append("op", "listar_Habilidades");
        parametros.append("idcolaboradores", usuarioTarea.value);
    
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
            rolTarea.innerHTML = datos;
          })
          .catch(error => {
            console.error('Error:', error);
          });
    }

// Modal de Tareas
    // Función para abrir el modal y cargar las cajas de texto con la información de la tarea
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
            // Para buscar las habilidades en la edición de tareas
            const btnBuscarHabilidad2 = document.querySelector('#usuario-tarea');
            btnBuscarHabilidad2.addEventListener("click", listarHabilidadesEditar);
            verEvidenciasTarear(id)
            idtarea = id;

            // Función para validar las fechas 
            function validarFechasT() {
                const fecha_inicio_tarea = document.querySelector("#fecha-inicio-tarea");
                const fecha_fin_tarea = document.querySelector("#fecha-fin-tarea");
                const parametos = new URLSearchParams();
                parametos.append("op", "obtenerFase");
                parametos.append("idfase", idfase);
                fetch('../controllers/fase.php', {
                    method: 'POST',
                    body: parametos,
                })
                .then(respuesta => respuesta.json())
                .then(datos=> {
                    fecha_inicio_tarea.min = datos.fechainicio;
                    fecha_inicio_tarea.max = datos.fechafin;
                    fecha_fin_tarea.max = datos.fechafin;
                    fecha_inicio_tarea.addEventListener("change", function() {
                        fecha_fin_tarea.min = fecha_inicio_tarea.value;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

                verEvidenciasTarear(id)
                idtarea = id;
            
            }
            validarFechasT();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Función para obtener las evidencias enviadas de la tarea
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

    // Función para calcular el porcentaje de la fase
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

    // Función para obtener el porcentaje del proyecto
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

    // Función gener el reporte de la tarea
    function generarReporteT(){
        console.log(idtarea);
        const parametros = new URLSearchParams();
        if(idtarea > 0) {
        parametros.append("idtarea", idtarea);
        window.open(`../reports/Tarea/reporte.php?${parametros}`, '_blank');
        }
    }

    function cerrarModaltarea() {
        const modalInfoTarea = document.querySelector("#modal-info-tarea");
        const closeButton = modalInfoTarea.querySelector(".closeT");
        closeButton.setAttribute("data-bs-dismiss", "modal");
        closeButton.click();
    }

    const btnModalP = document.querySelector("#tarea-modal");
    btnModalP.addEventListener("click",cerrarModaltarea);

     // Función para abrir el modal de graficos de proyecto

    function abrirGrafico(id){
        grafico.destroy();
        const modal = document.querySelector("#modal-grafico");
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        idfase = id;
        console.log(idfase)
        renderGrafico();
        graficosT(id)
    }

    function obtenerDatos() {
        const parametros = new URLSearchParams();
        parametros.append('op', 'graficoF');
        parametros.append('idfase', idfase);
        fetch('../controllers/fase.php', {
            method: 'POST',
            body: parametros
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            // console.log(datos.Covid)
            grafico.data.labels = datos.labels;
            grafico.data.datasets[0].data = datos.data;
            grafico.update();
        });
    }

    function getConfig(valMin, valMax, titulo) {
        const configuraciones = {
            responsive: true,
            legend: { position: 'bottom' },
            scales: {
                y: {
                min: valMin,
                max: valMax
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: titulo
                },
                legend: { position: 'bottom' }
            }
        };
        return configuraciones;
    }

    function renderGrafico() {
        const etiquetas = [];
        const coloresFondo = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)']; // Colores de fondo para las barras (puedes personalizarlos)
        const borde = 1; // Ancho del borde de las barras
        const coloresBorde = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)']; // Colores de borde para las barras (puedes personalizarlos)

        grafico = new Chart(lienzo, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [
                    {
                        label: 'Avance',
                        data: [], // Datos de ejemplo, serán reemplazados por los datos obtenidos de la operación
                        backgroundColor: coloresFondo,
                        borderWidth: borde,
                        borderColor: coloresBorde
                    }
                ]
            },
            options: getConfig(0, 100, 'Fases en proceso') // Rango de 1 a 12 para los meses
        });

        obtenerDatos();
    }

    let chartF = {};
    function obtenerDatosT(idtareaG, chart) {
        const parametros = new URLSearchParams();
        parametros.append('op', 'graficoT');
        parametros.append('idtarea', idtareaG);
        fetch('../controllers/tarea.php', {
            method: 'POST',
            body: parametros
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            // Actualizar el gráfico específico con sus datos
            chart.data.labels = datos.labels;
            chart.data.datasets[0].data = datos.data;
            chart.update();
        });
    }

    function getConfigT(valMin, valMax, titulo) {
        const configuraciones = {
            responsive: true,
            legend: { position: 'bottom' },
            scales: {
                y: {
                min: valMin,
                max: valMax
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: titulo
                },
                legend: { position: 'bottom' }
            }
        };
        return configuraciones;
    }

    function renderGraficoT(graficoId,idtareaG) {
        const etiquetas = []; // Define tus etiquetas según tus datos
        const datos = []; // Define tus datos según tus datos

        const coloresFondo = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)']; // Define colores de fondo
        const coloresBorde = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)']; // Define colores de borde

        const ctx = document.getElementById(graficoId);
        console.log(graficoId)
        
        chartF = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [
                    {
                        label: 'Avance',
                        data: datos,
                        backgroundColor: coloresFondo,
                        borderWidth: 1,
                        borderColor: coloresBorde
                    }
                ]
            },
            options: getConfigT(0, 100, 'Tareas en procesor')
        });
        obtenerDatosT(idtareaG);
    }
    
    function graficosT(id) {
        const lienzoF = document.querySelector("#graficos-tareas");
        const parametos = new URLSearchParams();
        parametos.append("op", "tareaxF");
        parametos.append("idfase", id);
        fetch(`../controllers/fase.php`, {
            method: 'POST',
            body: parametos
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            let contador = 1;
            lienzoF.innerHTML = "";
            datos.forEach(element => {
                const graficoId = `graficoF_${element.idtarea}`;
                const fasesContainer = document.createElement('div');
                fasesContainer.innerHTML = `
                    <hr>
                    <p>Tarea N° ${contador}: <b>${element.tarea}</b></p>
                    <p>Avance: <b>${element.porcentaje_tarea} %</b></p>
                    <div class='row'>
                        <div class='col-md-2'></div>
                        <div class='col-md-8'>
                            <canvas id='${graficoId}'></canvas>
                        </div>
                        <div class='col-md-2'></div>
                    </div>
                `;
                lienzoF.appendChild(fasesContainer);
                
                // Inicializar un nuevo objeto Chart para cada gráfico
                const ctx = document.getElementById(graficoId);
                chartF[graficoId] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: 'Avance',
                                data: [],
                                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                                borderWidth: 1,
                                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)']
                            }
                        ]
                    },
                    options: getConfigT(0, 100, 'Evidencias')
                });

                // Llamar a obtenerDatosF con el gráfico y el idfase correspondientes
                obtenerDatosT(element.idtarea, chartF[graficoId]);
                contador++;
            });
        });
    }

listarColaboradores();
listarProyectosSelect();
list();

// *Para tareas
    // Para abrir un miniModal de registro de tareas
    const btnAgregarT = document.querySelector("#agregar-tarea");
    btnAgregarT.addEventListener("click", openModalAgregarTarea);

    const btnBuscarHabilidad = document.querySelector("#asignar-empleado");
    btnBuscarHabilidad.addEventListener("click", listarHabilidades);

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

    // Para generar el reporte de la tarea
    const btnRtarea = document.querySelector("#generar-reporteT");
    btnRtarea.addEventListener("click", generarReporteT);

