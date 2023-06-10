<?php

    require_once 'conexion.php';

    class Empresa extends Conexion{
        private $conexion;

        public function __construct(){
            $this->conexion = parent::getConexion();
        }

        public function listar(){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM empresas where estado = 1");
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function getDatos($idempresa){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM empresas where idempresa = ?");
                $consulta->execute(array($idempresa));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function registrar($nombre, $razonsocial, $tipodocumento, $documento){
            try {
                $consulta = $this->conexion->prepare("INSERT INTO empresas(nombre,razonsocial,tipodocumento,documento)VALUES(?,?,?,?)");
                $consulta->execute(array($nombre, $razonsocial, $tipodocumento, $documento));
                
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function update($nombre, $razonsocial, $tipodocumento, $documento, $estado, $idempresa){
            try {
                $consulta = $this->conexion->prepare("UPDATE empresas set nombre = ? , razonsocial = ? , tipodocumento = ? , documento = ?, estado = ? WHERE idempresa = ?");
                $consulta->execute(array($nombre, $razonsocial, $tipodocumento, $documento, $estado, $idempresa));
                echo "Empresa actualizada corectamente.";
            } catch (Exception $e) {
                echo "Error al actualizar la empresa: " . $e->getMessage();
            }
        }

    }

?>