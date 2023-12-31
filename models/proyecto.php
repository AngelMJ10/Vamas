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

        public function listar_proyecto_by_Colaborador($data = []){
            try {
                $consulta = $this->conexion->prepare("Call listar_proyecto_by_Colaborador(?)");
                $consulta->execute(array($data['idcolaboradores']));
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listarTodos(){
            try {
                $consulta  = $this->conexion->prepare("SELECT * FROM proyecto");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listarPFinalizados(){
            try {
                $query = "SELECT * FROM proyecto WHERE estado = 2";
                $consulta  = $this->conexion->prepare($query);
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function buscarProyecto($data = []){
            try {
                $query = "CALL buscar_proyecto(?,?,?)";
                $consulta  = $this->conexion->prepare($query);
                $consulta->execute(array(
                    $data['idtipoproyecto'],
                    $data['idempresa'],
                    $data['estado']
                ));
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

        public function actualizar_proyecto($data = []){
            try {
                $query = "CALL editar_proyecto(?, ?, ?, ?, ?, ?, ?, ?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                    $data['idproyecto'],
                    $data['idtipoproyecto'],
                    $data['idempresa'],
                    $data['titulo'],
                    $data['descripcion'],
                    $data['fechainicio'],
                    $data['fechafin'],
                    $data['precio']
                ));
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

        public function contarColaboradores($idproyecto){
            try {
                $consulta = $this->conexion->prepare("CALL contar_total_colaboradores(?)");
                $consulta->execute(array($idproyecto));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function contar_trabajos_colaboradores($idproyecto){
            try {
                $consulta = $this->conexion->prepare("CALL contar_colaboradores(?)");
                $consulta->execute(array($idproyecto));
                $datos = $consulta->fetchall(PDO::FETCH_ASSOC);
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
                $consulta = $this->conexion->prepare("SELECT COUNT(idproyecto) AS 'ProjectsFinish' FROM proyecto WHERE estado = 2");
                $consulta->execute();
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtenerPorcentajeP(){
            try {
                $query = "CALL hallar_porcentaje_proyecto";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();

            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para poder finalizar un proyecto
        public function finalizar_proyecto($data = []){
            try {
                $query = "CALL finalizar_proyecto(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idproyecto']));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        // Método para poder reactivar un proyecto
        public function reactivar_proyecto($data = []){
            try {
                $query = "CALL reactivar_proyecto(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array($data['idproyecto']));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }

?>