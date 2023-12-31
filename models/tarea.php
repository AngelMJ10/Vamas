<?php
    require_once 'conexion.php';
    class Tarea extends Conexion {
        private $conexion;
        public function __construct(){
            $this->conexion = parent::getConexion();
        }
    
        public function list(){
            try {
                $query = "CALL listar_tarea()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function registrarTarea($data = []){
            try {
                $query = "CALL crear_tarea(?,?,?,?,?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                $data["idfase"],
                $data["idcolaboradores"],
                $data["roles"],
                $data["tarea"],
                $data["porcentaje"],
                $data["fecha_inicio_tarea"],
                $data["fecha_fin_tarea"]
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        
        public function editarTarea($data = []){
            try {
                $query = "CALL editarTarea(?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                $data["idtarea"],
                $data["idcolaboradores"],
                $data["roles"],
                $data["tarea"],
                $data["porcentaje"],
                $data["fecha_inicio_tarea"],
                $data["fecha_fin_tarea"]
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getWork($idtarea){
            try {
                $query ="CALL obtener_tarea(?)";
                $consulta =  $this->conexion->prepare($query);
                $consulta->execute(array($idtarea));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        
        public function sendWork($mensaje,$documento, $idtarea){
            try {
                $fecha = date('Y-m-d');
                $hora = date('h:i:s');
                $query = "UPDATE tareas
                SET evidencia = JSON_ARRAY_APPEND(evidencia, '$', JSON_OBJECT(
                    'mensaje', ?,
                    'documento', ?,
                    'fecha', ?,
                    'hora', ?
                )) WHERE idtarea = ?";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($mensaje,$documento,$fecha,$hora,$idtarea));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function enviarTareas($data = []){
            try {
                $query = "CALL enviar_evidencia(?,?,?,?,?,?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                    $data['e_colaborador'],
                    $data['e_emisor'],
                    $data['e_mensaje'],
                    $data['e_documento'],
                    $data['e_fecha'],
                    $data['e_hora'],
                    $data['p_porcentaje'],
                    $data['t_idtarea']
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function verEvidencias($data = []){
            try {
                $query = "CALL ver_evidencia(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idtarea']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerID($idtarea){
            try {
                $query = "CALL obtener_ids(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($idtarea));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerUser($correo){
            try {
                $query = "CALL obtener_user(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($correo));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para finalizar las tarea de las fases finalizadas finalizado
        public function finalizar_tarea(){
            try {
                $query = "CALL finalizar_tarea()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para finalizar las tareas por sus ID
        public function finalizar_tarea_by_id($data = []){
            try {
                $query = "CALL finalizar_tarea_by_id(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idtarea']));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para reactivar las tareas de las fases reactivadas
        public function reactivar_tarea(){
            try {
                $query = "CALL reactivar_tarea()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para reactivar la tarea por su ID
        public function reactivar_tarea_by_id($data = []){
            try {
                $query = "CALL reactivar_tarea_by_id(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idtarea']));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function buscar_tareas($data = []){
            try {
                $query = "CALL buscar_tareas(?,?,?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                    $data['idproyecto'],
                    $data['idfase'],
                    $data['tarea'],
                    $data['idcolaboradorT'],
                    $data['estado']
                ));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }
?>