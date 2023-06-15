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

        public function registerPhase($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin, $comentario){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO fases(idproyecto,idresponsable,nombrefase,fechainicio,fechafin,comentario) values(?,?,?,?,?,?)");
                $consulta->execute(array($idproyecto, $idresponsable, $nombrefase, $fechainicio, $fechafin, $comentario));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getPhase($idproyecto){
            try {
                $consulta = $this->conexion->prepare("Call listar_fase_proyecto(?)");
                $consulta->execute(array($idproyecto));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerPorcentajeF($idfase){
            try {
                $query = "CALL hallar_porcentaje_fase(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($idfase));

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }

?>  