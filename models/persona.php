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

    }

?>