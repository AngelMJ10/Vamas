<?php

    require_once 'conexion.php';

    class Proyecto extends Conexion {
        private $conexion;

        public function __construct(){
            $this->conexion = parent::getConexion();
        }

        public function listar(){
            try {
                $consulta  = $this->conexion->prepare("CALL listar_proyecto()");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function registrar($idtipoproyecto,$idempresa,$titulo,$descripcion,$fechainicio,$fechafin,$precio,$idusuariore){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO proyecto (idtipoproyecto,idempresa,titulo, descripcion, fechainicio,fechafin,precio,idusuariore) values (?,?,?,?,?,?,?,?)");
                $consulta->execute(array($idtipoproyecto, $idempresa, $titulo, $descripcion, $fechainicio, $fechafin, $precio,$idusuariore));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function get($idproyecto){
            try {
                $consulta = $this->conexion->prepare("CALL obtener_proyecto(?)");
                $consulta->execute(array($idproyecto));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listarTipoProyecto(){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM tiposproyecto where estado = 1");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function countProjects(){
            try {
                $consulta = $this->conexion->prepare("SELECT COUNT(idproyecto) AS proyectos FROM proyecto WHERE estado = 1");
                $consulta->execute();
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function countFinishProjects(){
            try {
                $consulta = $this->conexion->prepare("SELECT COUNT(idproyecto) AS 'ProjectsFinish' FROM proyecto WHERE estado = 0");
                $consulta->execute();
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerPorcentajeP($idproyecto){
            try {
                $query = "CALL hallar_porcentaje_proyecto(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($idproyecto));

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }

?>