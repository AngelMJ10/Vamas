<?php

    require_once 'conexion.php';

    class Colaborador extends Conexion {
        private $conexion;

        public function __construct(){
            $this->conexion = parent::getConexion();
        }

        public function login($usuario){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM colaboradores WHERE usuario = ? AND estado = 1");
                $consulta->execute(array($usuario));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            } 
        }

        public function registarColaborador($datos){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO colaboradores(idpersona,usuario,clave,nivelacceso) VALUES(?,?,?,?)");
                $consulta->execute(array(
                    $datos['idpersona'],
                    $datos['usuario'],
                    $datos['clave'],
                    $datos['nivelacceso']
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }
            

        }

        public function listarHabilidades(){
            try {
                $consulta = $this->conexion->prepare("CALL listar_habilidades()");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function countUsers(){
            try {
                $consulta = $this->conexion->prepare("SELECT COUNT(idcolaboradores) AS users FROM colaboradores WHERE estado = 1");
                $consulta->execute();
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listarColaborador(){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM colaboradores WHERE estado = 1 and nivelacceso = 'S'");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function listarCorreo(){
            try {
                $query = "SELECT idcolaboradores,usuario,correo FROM colaboradores WHERE nivelacceso IN ('A','S')";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }

?>