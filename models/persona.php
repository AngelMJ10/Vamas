<?php

    require_once 'conexion.php';

    class Persona extends Conexion{
        private $conexion;

        public function __construct(){
            $this->conexion = parent::getConexion();
        }

        public function listar(){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM personas");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getDatos($idpersona){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM personas where idpersona = ?");
                $consulta->execute(array($idpersona));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getID($nrodcumento){
            try {
                $consulta = $this->conexion->prepare("call obtener_idpersona(?)");
                $consulta->execute(array($nrodcumento));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        
        public function registrarPersona($datos){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO personas(apellidos,nombres,tipodocumento,nrodocumento,telefono,direccion,fechanac) VALUES(?,?,?,?,?,?,?)");
                $consulta->execute(array(
                    $datos['apellidos'],
                    $datos['nombres'],
                    $datos['tipodocumento'],
                    $datos['nrodocumento'],
                    $datos['telefono'],
                    $datos['direccion'],
                    $datos['fechanac']
                ));
            } catch (Exception $e) {
                die($e->getMessage());
            }         

        }

    }

?>