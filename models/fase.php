<?php
    require_once 'conexion.php';
    class Fase extends Conexion {
        private $conexion;
        public function __construct(){
            $this->conexion = parent::getConexion();
        }

        public function list(){
            try {
                $consulta = $this->conexion->prepare("Call listar_fase()");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listar_Fase_Colaborador($data = []){
            try {
                $consulta = $this->conexion->prepare("Call listar_fase_by_Colaborador(?)");
                $consulta->execute(array($data['idcolaboradores']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function buscarFase($data = []){
            try {
                $consulta = $this->conexion->prepare("CALL buscar_fase(?)");
                $consulta->execute(array($data['idproyecto']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function registerPhase($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin,$porcentaje, $comentario){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO fases(idproyecto,idresponsable,nombrefase,fechainicio,fechafin,porcentaje,comentario) values(?,?,?,?,?,?,?)");
                $consulta->execute(array($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin,$porcentaje, $comentario));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function editarFase($data = []){
            try {
                $query = "CALL editar_fase(?,?,?,?,?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                    $data["idfase"],
                    $data["idresponsable"],
                    $data["nombrefase"],
                    $data["fechainicio"],
                    $data["fechafin"],
                    $data["comentario"],
                    $data["porcentaje"]
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getFases_by_P($idproyecto){
            try {
                $consulta = $this->conexion->prepare("Call listar_fase_proyecto(?)");
                $consulta->execute(array($idproyecto));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getPhase($idfase){
            try {
                $consulta = $this->conexion->prepare("Call obtener_fase(?)");
                $consulta->execute(array($idfase));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function infoFases($data = []){
            try {
                $query = "CALL obtener_fase(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idfase']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function tablaFases($data = []){
            try {
                $query = "CALL obtener_tareas_fase(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idfase']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerPorcentajeF(){
            try {
                $query = "CALL hallar_porcentaje_fase";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para finalizar las fases de un proyecto finalizado
        public function finalizar_fase(){
            try {
                $query = "CALL finalizar_fase()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para reactivar las fases de un proyecto reactivado
        public function reactivar_fase(){
            try {
                $query = "CALL reactivar_fase()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }
?>