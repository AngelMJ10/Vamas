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

        public function registrarColaborador($datos=[]){
            try {
                $consulta = $this->conexion->prepare("CALL registrarColaboradores(?,?,?,?)");
                $consulta->execute(array(
                    $datos['idpersona'],
                    $datos['usuario'],
                    $datos['correo'],
                    $datos['clave']
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

        public function listarColaborador_A(){
            try {
                $consulta = $this->conexion->prepare("SELECT * FROM colaboradores WHERE estado = 1 AND (nivelacceso = 'S' OR nivelacceso = 'C')");
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

        public function searchUser($usuario = ''){
            try{
              $query = "CALL buscar(?)";
              $consulta = $this->conexion->prepare($query);
              $consulta->execute(array($usuario));
        
              return $consulta->fetch(PDO::FETCH_ASSOC);
            }
            catch(Exception $e){
              die($e->getMessage());
            }
        }
  
        public function restoure($data = []){
            try{
                $query = "CALL recuperar_clave(?,?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                $data['idcolaboradores'],
                $data['correo'],
                $data['clavegenerada']
                ));
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        public function validarClave($data = []){
            try{
                $query = "CALL spu_colaborador_validarclave(?,?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                $data['idcolaboradores'],
                $data['clavegenerada']
                ));
                return $consulta->fetch(PDO::FETCH_ASSOC);
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        public function validarTiempo($data = []){
            try{
                $query = "CALL spu_colaborador_validartiempo(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                $data['idcolaboradores']
                ));
                return $consulta->fetch(PDO::FETCH_ASSOC);
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }
  
        public function actualizarClave($data = []){
            $resultado = ["status" => false];
            try{
                $query = "CALL spu_colaboradores_actualizarclave(?,?)";
                $consulta = $this->conexion->prepare($query);
                $resultado["status"] =$consulta->execute(array(
                $data['idcolaboradores'],
                $data['clave']
                ));
                return $resultado;
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        // CRUD DE USUARIO

        public function listar_t_Colaborador(){
            try {
                $query = "CALL listar_colaboradores()";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute();
                $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        public function obtener_info_Colaborador($data = []){
            try {
                $query = "CALL obtener_info_colaborador(?)";
                $consulta = $this->conexion->prepare($query);
                $consulta->execute(array(
                    (
                        $data['idcolaboradores']
                    )));
                $datos = $consulta->fetch(PDO::FETCH_ASSOC);
                return $datos;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

    }

?>