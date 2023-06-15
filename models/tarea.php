<?php

    require_once 'conexion.php';

    class Tarea extends Conexion {
        private $conexion;

        public function __construct(){
            $this->conexion = parent::getConexion();
        }
    
        public function list($idcolaboradores){
            try {
                $query = "CALL listar_tarea_colaboradores(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($idcolaboradores));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
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
                $query = "CALL enviar_evidencia(?,?,?,?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
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
    }

?>