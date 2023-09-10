/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.21-MariaDB : Database - vamas2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vamas2` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `vamas2`;

/*Table structure for table `colaboradores` */

DROP TABLE IF EXISTS `colaboradores`;

CREATE TABLE `colaboradores` (
  `idcolaboradores` smallint(6) NOT NULL AUTO_INCREMENT,
  `idpersona` smallint(6) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `nivelacceso` char(1) NOT NULL DEFAULT 'C',
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idcolaboradores`),
  KEY `fk_idpersona_per` (`idpersona`),
  CONSTRAINT `fk_idpersona_per` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `colaboradores` */

insert  into `colaboradores`(`idcolaboradores`,`idpersona`,`usuario`,`clave`,`correo`,`nivelacceso`,`fecha_create`,`fecha_update`,`estado`) values 
(1,1,'AngelMJ','$2y$10$WY.iP85bEYxBMkVBG0jKO.9Q97kEbofLVwJPUT1OAmsDzLXQ8Pcka','angelitomasna200410@gmail.com','A','2023-07-12 00:26:28',NULL,'1'),
(2,2,'MarksPC','$2y$10$WY.iP85bEYxBMkVBG0jKO.9Q97kEbofLVwJPUT1OAmsDzLXQ8Pcka','1342364@senati.pe','S','2023-07-12 00:26:28',NULL,'1'),
(3,5,'EmyMJ','$2y$10$WY.iP85bEYxBMkVBG0jKO.9Q97kEbofLVwJPUT1OAmsDzLXQ8Pcka','1342364@senati.pe','S','2023-07-12 00:26:28',NULL,'1'),
(4,4,'JesusPC','$2y$10$WY.iP85bEYxBMkVBG0jKO.9Q97kEbofLVwJPUT1OAmsDzLXQ8Pcka','1342364@senati.pe','C','2023-07-12 00:26:28',NULL,'1'),
(5,6,'Lufc','$2y$10$NSuiz9F6LY1H8oKE/2D/ueu9NRzPqPvezsXVnMCDvFCmt596vqUzG','lfuentes.ieesa2020@gmail.com','C','2023-07-31 21:49:11',NULL,'1'),
(6,7,'jfrancia','$2y$10$0y9fRDMZg.BTOKnvkP6SbOwtKNqZa0AJwnbSVKIfX558un/1Hlx/K','1343238@senati.pe','C','2023-08-01 20:16:25',NULL,'1');

/*Table structure for table `empresas` */

DROP TABLE IF EXISTS `empresas`;

CREATE TABLE `empresas` (
  `idempresa` smallint(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `razonsocial` varchar(40) NOT NULL,
  `tipodocumento` varchar(20) NOT NULL,
  `documento` varchar(40) NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idempresa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `empresas` */

insert  into `empresas`(`idempresa`,`nombre`,`razonsocial`,`tipodocumento`,`documento`,`fecha_create`,`fecha_update`,`estado`) values 
(5,'VAMAS S.A.C','VAMAS','RUC','20609878313','2023-08-31 19:40:15',NULL,'1'),
(6,'Mamá Carmen','Restaurant Mamá Carmen','RUC','200356478941','2023-08-31 19:40:41',NULL,'1');

/*Table structure for table `fases` */

DROP TABLE IF EXISTS `fases`;

CREATE TABLE `fases` (
  `idfase` smallint(6) NOT NULL AUTO_INCREMENT,
  `idproyecto` smallint(6) NOT NULL,
  `idresponsable` smallint(6) NOT NULL,
  `nombrefase` varchar(40) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `comentario` varchar(200) NOT NULL,
  `porcentaje_fase` decimal(5,2) DEFAULT 0.00,
  `porcentaje` decimal(5,2) NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idfase`),
  KEY `fk_idproyecto_fas` (`idproyecto`),
  KEY `fk_idresponsable_fas` (`idresponsable`),
  CONSTRAINT `fk_idproyecto_fas` FOREIGN KEY (`idproyecto`) REFERENCES `proyecto` (`idproyecto`),
  CONSTRAINT `fk_idresponsable_fas` FOREIGN KEY (`idresponsable`) REFERENCES `colaboradores` (`idcolaboradores`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

/*Data for the table `fases` */

insert  into `fases`(`idfase`,`idproyecto`,`idresponsable`,`nombrefase`,`fechainicio`,`fechafin`,`comentario`,`porcentaje_fase`,`porcentaje`,`fecha_create`,`fecha_update`,`estado`) values 
(9,4,2,'Creación de bocetos','2023-08-31','2023-09-01','En esta fase se tiene que crear los bocetos y escoger uno al final de esta',50.00,25.00,'2023-08-31 19:44:43',NULL,'1'),
(10,5,2,'Crear un modelo relacional de la base de','2023-09-04','2023-09-06','Crea un modelo relacional de la base de datos',50.00,25.00,'2023-09-04 18:51:23',NULL,'1'),
(11,6,2,'Prueba 1','2023-09-04','2023-09-06','Prueba',50.00,50.00,'2023-09-04 19:11:25',NULL,'1'),
(12,7,2,'Creación de bocetos','2023-09-04','2023-09-06','Crea bocetos',75.00,50.00,'2023-09-04 19:14:08',NULL,'1');

/*Table structure for table `habilidades` */

DROP TABLE IF EXISTS `habilidades`;

CREATE TABLE `habilidades` (
  `idhabilidades` smallint(6) NOT NULL AUTO_INCREMENT,
  `idcolaboradores` smallint(6) NOT NULL,
  `habilidad` varchar(40) NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idhabilidades`),
  KEY `fk_idcolaboradores_col` (`idcolaboradores`),
  CONSTRAINT `fk_idcolaboradores_col` FOREIGN KEY (`idcolaboradores`) REFERENCES `colaboradores` (`idcolaboradores`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `habilidades` */

insert  into `habilidades`(`idhabilidades`,`idcolaboradores`,`habilidad`,`fecha_create`,`fecha_update`,`estado`) values 
(1,1,'Front-end Intermedio','2023-07-20 11:52:55',NULL,'1'),
(2,1,'Analista de datos','2023-07-20 11:53:47',NULL,'1'),
(3,1,'Back-end Intermedio','2023-07-20 11:54:04',NULL,'1'),
(4,4,'Diseño Gráfico','2023-07-20 23:10:20',NULL,'1'),
(5,5,'Diseño Gráfico','2023-07-31 21:50:01',NULL,'1'),
(6,6,'Front-end Intermedio','2023-08-01 20:16:56',NULL,'1'),
(7,6,'Analista de datos','2023-08-01 20:17:03',NULL,'1'),
(8,6,'Diseño Gráfico','2023-08-01 20:17:10',NULL,'1'),
(9,2,'Front-end Intermedio','2023-09-04 18:52:07',NULL,'1'),
(10,2,'Back-end Intermedio','2023-09-04 18:52:15',NULL,'1'),
(11,4,'Front-end Intermedio','2023-09-04 18:52:23',NULL,'1'),
(12,3,'Back-end FrameWork Laravel','2023-09-04 18:52:29',NULL,'1'),
(13,3,'Front-end Framework React','2023-09-04 18:52:36',NULL,'1'),
(14,4,'Analista de datos','2023-09-04 18:52:44',NULL,'1');

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `idpersona` smallint(6) NOT NULL AUTO_INCREMENT,
  `apellidos` varchar(40) NOT NULL,
  `nombres` varchar(40) NOT NULL,
  `tipodocumento` varchar(20) NOT NULL,
  `nrodocumento` char(8) NOT NULL,
  `telefono` char(9) NOT NULL,
  `genero` char(1) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  `fechanac` date NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fechabaja` datetime DEFAULT NULL,
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `personas` */

insert  into `personas`(`idpersona`,`apellidos`,`nombres`,`tipodocumento`,`nrodocumento`,`telefono`,`genero`,`direccion`,`estado`,`fechanac`,`fecha_create`,`fechabaja`) values 
(1,'Marquina Jaime','Ángel Eduardo','DNI','72745028','951531166','M','León de Vivero MZ V L-2','1','2004-07-10','2023-07-12 00:26:21',NULL),
(2,'Padilla Chumbiauca','Marks Steven','DNI','72854857','924563458','M','Atrás de plaza vea','1','2004-06-07','2023-07-12 00:26:21',NULL),
(3,'Uribe Garcia','Cristhian Manuel','DNI','72548675','95123654','M','Rosedal por donde roban','1','2004-05-21','2023-07-12 00:26:21',NULL),
(4,'Chacaliaza Pachas','Ítalo Jesús','DNI','7254789','963214587','M','AV. Santos Nagaro 210','1','2003-10-29','2023-07-12 00:26:21',NULL),
(5,'Marquina Jaime','Emily Fernanda','DNI','78383886','952145879','F','León de Vivero Mz V LT-22','1','2013-12-16','2023-07-12 00:26:21',NULL),
(6,'Fuentes Chirre','Lufi','DNI','7568975',' 92073190','F','Parque de la revolución','1','2004-06-01','2023-07-31 21:49:08',NULL),
(7,'Francia Minaya','Jhon','DNI','12345678','95864785','M','Chincha Alta','1','2004-10-19','2023-08-01 20:16:23',NULL);

/*Table structure for table `proyecto` */

DROP TABLE IF EXISTS `proyecto`;

CREATE TABLE `proyecto` (
  `idproyecto` smallint(6) NOT NULL AUTO_INCREMENT,
  `idtipoproyecto` smallint(6) NOT NULL,
  `idempresa` smallint(6) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `precio` decimal(6,2) NOT NULL,
  `condiciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`condiciones`)),
  `idusuariore` smallint(6) NOT NULL,
  `porcentaje` decimal(5,2) DEFAULT 0.00,
  `estado` char(1) NOT NULL DEFAULT '1',
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  PRIMARY KEY (`idproyecto`),
  KEY `fk_idtipoproyecto_pro` (`idtipoproyecto`),
  KEY `fk_idempresa_tip` (`idempresa`),
  KEY `fk_idusuariore_pro` (`idusuariore`),
  CONSTRAINT `fk_idempresa_tip` FOREIGN KEY (`idempresa`) REFERENCES `empresas` (`idempresa`),
  CONSTRAINT `fk_idtipoproyecto_pro` FOREIGN KEY (`idtipoproyecto`) REFERENCES `tiposproyecto` (`idtipoproyecto`),
  CONSTRAINT `fk_idusuariore_pro` FOREIGN KEY (`idusuariore`) REFERENCES `colaboradores` (`idcolaboradores`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `proyecto` */

insert  into `proyecto`(`idproyecto`,`idtipoproyecto`,`idempresa`,`titulo`,`descripcion`,`fechainicio`,`fechafin`,`precio`,`condiciones`,`idusuariore`,`porcentaje`,`estado`,`fecha_create`,`fecha_update`) values 
(4,1,5,'Página web','Crear una página web para la empresa Vamas que promocione a esta','2023-08-31','2023-09-15',2000.00,NULL,1,12.50,'1','2023-08-31 19:42:27',NULL),
(5,2,6,'Sistema de ventas para un restaurante','Diseñar un sistema de ventas','2023-09-04','2023-10-04',2000.00,NULL,1,12.50,'1','2023-09-04 18:49:53',NULL),
(6,3,6,'Sistema de almacen para el restaurante Mamá Carmen','Prueba','2023-09-04','2023-10-04',1000.00,NULL,1,25.00,'1','2023-09-04 19:10:48',NULL),
(7,4,5,'Página web sobre la empresa VAMAS SAC','Diseña una pagina web para la empresa Vamas','2023-09-04','2023-10-04',500.00,NULL,1,37.50,'1','2023-09-04 19:13:42',NULL);

/*Table structure for table `recuperarclave` */

DROP TABLE IF EXISTS `recuperarclave`;

CREATE TABLE `recuperarclave` (
  `idrecuperar` int(11) NOT NULL AUTO_INCREMENT,
  `idcolaboradores` smallint(6) NOT NULL,
  `fecharegeneracion` datetime NOT NULL DEFAULT current_timestamp(),
  `correo` varchar(200) NOT NULL,
  `clavegenerada` char(4) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idrecuperar`),
  KEY `fk_idcolaboradores_rcl` (`idcolaboradores`),
  CONSTRAINT `fk_idcolaboradores_rcl` FOREIGN KEY (`idcolaboradores`) REFERENCES `colaboradores` (`idcolaboradores`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `recuperarclave` */

/*Table structure for table `tareas` */

DROP TABLE IF EXISTS `tareas`;

CREATE TABLE `tareas` (
  `idtarea` smallint(6) NOT NULL AUTO_INCREMENT,
  `idfase` smallint(6) NOT NULL,
  `idcolaboradores` smallint(6) NOT NULL,
  `roles` varchar(40) NOT NULL,
  `tarea` varchar(200) NOT NULL,
  `porcentaje_tarea` decimal(5,2) DEFAULT 0.00,
  `porcentaje` decimal(5,2) NOT NULL,
  `evidencia` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`evidencia`)),
  `fecha_inicio_tarea` date NOT NULL,
  `fecha_fin_tarea` date NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idtarea`),
  KEY `fk_idfase_tar` (`idfase`),
  KEY `fk_idcolaboradores_tar` (`idcolaboradores`),
  CONSTRAINT `fk_idcolaboradores_tar` FOREIGN KEY (`idcolaboradores`) REFERENCES `colaboradores` (`idcolaboradores`),
  CONSTRAINT `fk_idfase_tar` FOREIGN KEY (`idfase`) REFERENCES `fases` (`idfase`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tareas` */

insert  into `tareas`(`idtarea`,`idfase`,`idcolaboradores`,`roles`,`tarea`,`porcentaje_tarea`,`porcentaje`,`evidencia`,`fecha_inicio_tarea`,`fecha_fin_tarea`,`fecha_create`,`fecha_update`,`estado`) values 
(11,9,4,'Diseño Gráfico','Crea un boceto para la página web de la empresa Vamas SAC',100.00,50.00,'[{\"colaborador\": \"JesusPC\", \"receptor\": \"MarksPC\", \"mensaje\": \"Hola ,este es mi avance\", \"documento\": \"https://drive.google.com/open?id=1hXOCDaMhc1K5GdWeMWT34PAWElJt9mAy\", \"fecha\": \"2023-08-31\", \"hora\": \"19:49:12\", \"porcentaje\": \"50\"}, {\"colaborador\": \"AngelMJ\", \"receptor\": \"AngelMJ\", \"mensaje\": \"100\", \"documento\": \"https://drive.google.com/open?id=1_vNs7NCZv-7mVi4yEkd74_FKoIbf9BoH\", \"fecha\": \"2023-08-31\", \"hora\": \"20:17:10\", \"porcentaje\": \"100\"}]','2023-08-31','2023-09-01','2023-08-31 19:47:34','2023-09-04 19:12:17','2'),
(12,10,4,'Analista de datos','Crea propuesta de base de datos relacional',100.00,50.00,'[{\"colaborador\": \"JesusPC\", \"receptor\": \"MarksPC\", \"mensaje\": \"Hola\", \"documento\": \"https://drive.google.com/open?id=1xEZzMIsKyK2jWeSPwZBShPjK7J3uTEIQ\", \"fecha\": \"2023-09-04\", \"hora\": \"18:54:24\", \"porcentaje\": \"100\"}]','2023-09-04','2023-09-05','2023-09-04 18:53:12','2023-09-04 19:12:30','2'),
(13,11,2,'Back-end Intermedio','Crea propuesta de base de datos relacional',50.00,100.00,'[{\"colaborador\": \"AngelMJ\", \"receptor\": \"MarksPC\", \"mensaje\": \"Hola\", \"documento\": \"https://drive.google.com/open?id=16YX829FfQi3ulM0jZVCdjTENVUavvV2i\", \"fecha\": \"2023-09-04\", \"hora\": \"19:12:50\", \"porcentaje\": \"50\"}]','2023-09-04','2023-09-05','2023-09-04 19:11:49',NULL,'1'),
(14,12,4,'Diseño Gráfico','Crea un boceto para la página web de la empresa Vamas SAC',75.00,100.00,'[{\"colaborador\": \"AngelMJ\", \"receptor\": \"MarksPC\", \"mensaje\": \"75\", \"documento\": \"https://drive.google.com/open?id=1FqPIstdpLyxC5FAKDuVmdF2cUcUPnZeO\", \"fecha\": \"2023-09-04\", \"hora\": \"19:15:08\", \"porcentaje\": \"75\"}]','2023-09-04','2023-09-05','2023-09-04 19:14:33',NULL,'1');

/*Table structure for table `tiposproyecto` */

DROP TABLE IF EXISTS `tiposproyecto`;

CREATE TABLE `tiposproyecto` (
  `idtipoproyecto` smallint(6) NOT NULL AUTO_INCREMENT,
  `tipoproyecto` varchar(40) NOT NULL,
  `fecha_create` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idtipoproyecto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tiposproyecto` */

insert  into `tiposproyecto`(`idtipoproyecto`,`tipoproyecto`,`fecha_create`,`fecha_update`,`estado`) values 
(1,'Desarollo Web','2023-07-12 00:26:47',NULL,'1'),
(2,'Sistema de ventas','2023-07-12 00:26:47',NULL,'1'),
(3,'Sistema de almacen','2023-07-12 00:26:47',NULL,'1'),
(4,'Marketing','2023-07-12 00:26:47',NULL,'1');

/* Procedure structure for procedure `activar_habilidad` */

/*!50003 DROP PROCEDURE IF EXISTS  `activar_habilidad` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `activar_habilidad`(
	IN _idhabilidades SMALLINT
)
BEGIN
	UPDATE habilidades
	SET estado = 1
	WHERE idhabilidades = _idhabilidades;
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscar` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar`(IN _usuario VARCHAR(20))
BEGIN
	SELECT  col.idcolaboradores,col.correo,col.usuario,col.clave,per.nombres,per.apellidos,per.nrodocumento
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE usuario = _usuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscarTareas` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscarTareas` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarTareas`(
    IN _idcolaboradores SMALLINT,
    IN _idproyecto SMALLINT,
    IN _idfase SMALLINT,
    IN _tarea VARCHAR(255),
    IN _idcolaboradorT SMALLINT,
    IN _estado CHAR(1)
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores AND (nivelacceso = 'A' OR nivelacceso = 'S' OR nivelacceso = 'C')
    ) THEN
        IF (SELECT nivelacceso FROM colaboradores WHERE idcolaboradores = _idcolaboradores) = 'C' THEN
            SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase, tar.tarea, fas.fechainicio, fas.fechafin,
                fas.comentario, col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
                tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia, tar.porcentaje, tar.estado
            FROM tareas tar
            INNER JOIN fases fas ON tar.idfase = fas.idfase
            INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
            INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
            INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
            WHERE
                (NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
                AND (NULLIF(_idfase, '') IS NULL OR fas.idfase = _idfase)
                AND (NULLIF(_tarea, '') IS NULL OR tar.tarea LIKE CONCAT('%', _tarea, '%'))
                AND (NULLIF(_idcolaboradores, '') IS NULL OR tar.idcolaboradores = _idcolaboradorT)
                AND (NULLIF(_estado, '') IS NULL OR tar.estado = _estado)
                AND col_tarea.idcolaboradores = _idcolaboradores
            ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
        ELSE
            SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase, tar.tarea, fas.fechainicio, fas.fechafin,
                fas.comentario, col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea', tar.roles,
                tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
            FROM tareas tar
            INNER JOIN fases fas ON tar.idfase = fas.idfase
            INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
            INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
            INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
            WHERE
                (NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
                AND (NULLIF(_idfase, '') IS NULL OR fas.idfase = _idfase)
                AND (NULLIF(_tarea, '') IS NULL OR tar.tarea LIKE CONCAT('%', _tarea, '%'))
                AND (NULLIF(_idcolaboradorT, '') IS NULL OR tar.idcolaboradores = _idcolaboradorT)
                AND (NULLIF(_estado, '') IS NULL OR tar.estado = _estado)
            ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
        END IF;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscar_colaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscar_colaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_colaboradores`(
	IN _usuario 		VARCHAR(20),
	IN _nivelacceso	CHAR(1),
	IN _correo			VARCHAR(100)
)
BEGIN
    SELECT col.idcolaboradores, col.usuario, col.correo, col.nivelacceso,
        per.apellidos, per.nombres,per.genero,
        (SELECT COUNT(DISTINCT idhabilidades) FROM habilidades WHERE idcolaboradores = col.idcolaboradores) AS Habilidades,
        (SELECT COUNT(DISTINCT fas.idfase) FROM fases fas WHERE fas.idresponsable = col.idcolaboradores AND fas.estado = 1) AS Fases,
        (SELECT COUNT(DISTINCT tar.idtarea) FROM tareas tar WHERE tar.idcolaboradores = col.idcolaboradores AND tar.estado = 1) AS Tareas
    FROM colaboradores col
    INNER JOIN personas per ON col.idpersona = per.idpersona
    WHERE 
			(NULLIF(_usuario, '') IS NULL OR col.usuario LIKE CONCAT('%', _usuario, '%'))
         AND (NULLIF(_nivelacceso, '') IS NULL OR col.nivelacceso= _nivelacceso)
         AND (NULLIF(_correo, '') IS NULL OR col.correo LIKE CONCAT('%', _correo, '%'));
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscar_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscar_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_fase`(
	IN _idproyecto SMALLINT,
	IN _nombrefase VARCHAR(40),
	IN _idresponsable SMALLINT,
	IN _estado CHAR(1)
)
BEGIN
    SELECT pro.idproyecto, fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
        pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
        fas.fechafin, fas.comentario, fas.porcentaje_fase, fas.porcentaje, fas.estado
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE (NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
			AND (NULLIF(_nombrefase, '') IS NULL OR fas.nombrefase LIKE CONCAT('%', _nombrefase, '%'))
			AND (NULLIF(_idresponsable, '') IS NULL OR fas.idresponsable = _idresponsable)
			AND (NULLIF(_estado, '') IS NULL OR fas.estado = _estado)
    ORDER BY pro.idproyecto, fas.fechainicio, fas.fechafin; -- Ordenar por el idproyecto ascendente
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscar_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscar_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_proyecto`(IN _idtipoproyecto INT, IN _idempresa INT, IN _estado_proyecto VARCHAR(255))
BEGIN
    SELECT pro.idproyecto, pro.titulo, pro.descripcion, pro.fechainicio, pro.fechafin, pro.precio,
        emp.nombre, pro.estado, col.usuario,
        COUNT(fas.idfase) AS Fases, pro.porcentaje
    FROM proyecto pro
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    LEFT JOIN fases fas ON pro.idproyecto = fas.idproyecto
    INNER JOIN colaboradores col ON col.idcolaboradores = pro.idusuariore
    WHERE
        (NULLIF(_idtipoproyecto, '') IS NULL OR pro.idtipoproyecto = _idtipoproyecto)
        AND (NULLIF(_idempresa, '') IS NULL OR pro.idempresa = _idempresa)
        AND (NULLIF(_estado_proyecto, '') IS NULL OR pro.estado = _estado_proyecto)
    GROUP BY pro.idproyecto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `buscar_tareas` */

/*!50003 DROP PROCEDURE IF EXISTS  `buscar_tareas` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_tareas`(
    IN _idproyecto SMALLINT,
    IN _idfase SMALLINT,
    IN _tarea VARCHAR(255),
    IN _idcolaboradorT SMALLINT,
    IN _estado CHAR(1)
)
BEGIN
    SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase, tar.tarea, fas.fechainicio, fas.fechafin,
	fas.comentario, col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea', tar.roles,
	tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
    FROM tareas tar
    INNER JOIN fases fas ON tar.idfase = fas.idfase
    INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
    INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
    INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
    WHERE
	(NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
	AND (NULLIF(_idfase, '') IS NULL OR fas.idfase = _idfase)
	AND (NULLIF(_tarea, '') IS NULL OR tar.tarea LIKE CONCAT('%', _tarea, '%'))
	AND (NULLIF(_idcolaboradorT, '') IS NULL OR tar.idcolaboradores = _idcolaboradorT)
	AND (NULLIF(_estado, '') IS NULL OR tar.estado = _estado)
    ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END */$$
DELIMITER ;

/* Procedure structure for procedure `contar_colaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `contar_colaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `contar_colaboradores`(IN _idproyecto SMALLINT)
BEGIN
    SELECT col.idcolaboradores, col.usuario, col.nivelacceso, col.correo,
        COUNT(DISTINCT fas.idfase) AS fases,
        COUNT(DISTINCT tar.idtarea) AS tareas
    FROM colaboradores col
    LEFT JOIN fases fas ON fas.idresponsable = col.idcolaboradores AND fas.idproyecto = _idproyecto
    LEFT JOIN tareas tar ON tar.idcolaboradores = col.idcolaboradores
    WHERE col.idcolaboradores IN (
        SELECT DISTINCT fas.idresponsable
        FROM fases fas
        WHERE fas.idproyecto = _idproyecto
        UNION
        SELECT DISTINCT tar.idcolaboradores
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        WHERE fas.idproyecto = _idproyecto
    )
    GROUP BY col.idcolaboradores, col.usuario, col.nivelacceso, col.correo;
END */$$
DELIMITER ;

/* Procedure structure for procedure `contar_total_colaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `contar_total_colaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `contar_total_colaboradores`(IN _idproyecto SMALLINT)
BEGIN
	SELECT COUNT(DISTINCT idcolaboradores) AS TotalUsuarios
	FROM (
	    SELECT col.idcolaboradores
	    FROM fases fas
	    INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
	    INNER JOIN colaboradores col ON fas.idresponsable = col.idcolaboradores
	    WHERE fas.idproyecto = _idproyecto
	    UNION
	    SELECT col.idcolaboradores
	    FROM tareas tar
	    INNER JOIN fases fas ON tar.idfase = fas.idfase
	    INNER JOIN colaboradores col ON tar.idcolaboradores = col.idcolaboradores
	    WHERE fas.idproyecto = _idproyecto
	) AS subquery;
END */$$
DELIMITER ;

/* Procedure structure for procedure `crear_tarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `crear_tarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `crear_tarea`(
	IN _idfase 			SMALLINT,
	IN _idcolaboradores		SMALLINT,
	IN _roles			VARCHAR(40),
	IN _tarea			VARCHAR(200),
	IN _porcentaje			DECIMAL(5,2),
	IN _fecha_inicio_tarea		DATE,
	IN _fecha_fin_tarea		DATE
)
BEGIN
	INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,evidencia,fecha_inicio_tarea,fecha_fin_tarea)
	VALUES(_idfase, _idcolaboradores, _roles, _tarea, _porcentaje, JSON_ARRAY(),_fecha_inicio_tarea, _fecha_fin_tarea);

END */$$
DELIMITER ;

/* Procedure structure for procedure `deshabilitar_habilidad` */

/*!50003 DROP PROCEDURE IF EXISTS  `deshabilitar_habilidad` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `deshabilitar_habilidad`(
	IN _idhabilidades SMALLINT
)
BEGIN
	UPDATE habilidades
	SET estado = 2
	WHERE idhabilidades = _idhabilidades;
END */$$
DELIMITER ;

/* Procedure structure for procedure `editarTarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `editarTarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `editarTarea`(
	IN t_idtarea 				SMALLINT,
	IN t_idcolaboradores			SMALLINT,
	IN t_roles				VARCHAR(40),
	IN t_tarea				VARCHAR(200),
	IN t_porcentaje				DECIMAL(5,2),
	IN t_fecha_inicio_tarea			DATE,
	IN t_fecha_fin_tarea			DATE
)
BEGIN
	UPDATE tareas
	SET idcolaboradores = t_idcolaboradores,
		roles = t_roles,
		tarea = t_tarea,
		porcentaje = t_porcentaje,
		fecha_inicio_tarea = t_fecha_inicio_tarea,
		fecha_fin_tarea = t_fecha_fin_tarea
	WHERE idtarea = t_idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `editar_Colaborador` */

/*!50003 DROP PROCEDURE IF EXISTS  `editar_Colaborador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `editar_Colaborador`(
	IN _idpersona 		SMALLINT,
	IN _usuario   		VARCHAR(20),
	IN _correo	   	VARCHAR(100),
	IN _nivelacceso 	CHAR(1),
	IN _apellidos 		VARCHAR(40),
	IN _nombres 		VARCHAR(40),
	IN _genero			CHAR(1),
	IN _nrodocumento 	CHAR(8),
	IN _telefono 		CHAR(9)
)
BEGIN
	UPDATE personas SET apellidos = _apellidos, nombres = _nombres, genero = _genero, nrodocumento = _nrodocumento,
			telefono = _telefono WHERE idpersona = _idpersona;
	UPDATE colaboradores SET usuario = _usuario, correo = _correo, nivelacceso = _nivelacceso
		WHERE idpersona = _idpersona;
END */$$
DELIMITER ;

/* Procedure structure for procedure `editar_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `editar_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `editar_fase`(
    IN p_idfase           SMALLINT,
    IN p_idresponsable    SMALLINT,
    IN p_nombrefase       VARCHAR(40),
    IN p_fechainicio      DATE,
    IN p_fechafin         DATE,
    IN p_comentario       VARCHAR(200),
    IN p_porcentaje       DECIMAL(5,2)
)
BEGIN
    UPDATE fases
    SET idresponsable = p_idresponsable,
        nombrefase = p_nombrefase,
        fechainicio = p_fechainicio,
        fechafin = p_fechafin,
        comentario = p_comentario,
        porcentaje = p_porcentaje
    WHERE idfase = p_idfase;
END */$$
DELIMITER ;

/* Procedure structure for procedure `editar_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `editar_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `editar_proyecto`(
    IN p_idproyecto         SMALLINT,
    IN p_idtipoproyecto     SMALLINT,
    IN p_idempresa          SMALLINT,
    IN p_titulo             VARCHAR(60),
    IN p_descripcion        VARCHAR(200),
    IN p_fechainicio        DATE,
    IN p_fechafin           DATE,
    IN p_precio             DECIMAL(6,2)
)
BEGIN
    UPDATE proyecto SET idtipoproyecto = p_idtipoproyecto, idempresa = p_idempresa,
                            titulo = p_titulo, descripcion = p_descripcion, fechainicio = p_fechainicio,
                             fechafin = p_fechafin,
                            precio = p_precio
    WHERE idproyecto = p_idproyecto;

END */$$
DELIMITER ;

/* Procedure structure for procedure `enviar_evidencia` */

/*!50003 DROP PROCEDURE IF EXISTS  `enviar_evidencia` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `enviar_evidencia`(
	IN e_colaborador VARCHAR(20),
	IN e_receptor VARCHAR(100),
	IN e_mensaje VARCHAR(255),
	IN e_documento VARCHAR(255),
	IN e_fecha VARCHAR(20),
	IN e_hora VARCHAR(20),
	IN p_porcentaje INT,
	IN t_idtarea SMALLINT
)
BEGIN

	UPDATE tareas
	SET evidencia = JSON_ARRAY_APPEND(evidencia, '$', JSON_OBJECT(
		'colaborador', e_colaborador,
		'receptor', e_receptor,
		'mensaje', e_mensaje,
		'documento', e_documento,
		'fecha', e_fecha,
		'hora', e_hora,
		'porcentaje',p_porcentaje
	)),
	porcentaje_tarea = p_porcentaje
	WHERE idtarea = t_idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `finalizar_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `finalizar_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_fase`()
BEGIN 
    UPDATE fases AS fas
    INNER JOIN proyecto AS pro ON pro.idproyecto = fas.idproyecto
    SET fas.estado = 2, fas.fecha_update = NOW()
    WHERE pro.estado = 2;
END */$$
DELIMITER ;

/* Procedure structure for procedure `finalizar_fase_by_id` */

/*!50003 DROP PROCEDURE IF EXISTS  `finalizar_fase_by_id` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_fase_by_id`(IN _idfase SMALLINT)
BEGIN 
    UPDATE fases AS fas
    SET fas.estado = 2, fas.fecha_update = NOW()
    WHERE fas.idfase = _idfase;
END */$$
DELIMITER ;

/* Procedure structure for procedure `finalizar_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `finalizar_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_proyecto`(IN _idproyecto SMALLINT)
BEGIN 
	UPDATE proyecto SET estado = 2, fecha_update = NOW()
	WHERE idproyecto = _idproyecto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `finalizar_tarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `finalizar_tarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_tarea`()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE fas.estado = 2;
END */$$
DELIMITER ;

/* Procedure structure for procedure `finalizar_tarea_by_id` */

/*!50003 DROP PROCEDURE IF EXISTS  `finalizar_tarea_by_id` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_tarea_by_id`(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE tar.idtarea = _idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `grafico_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `grafico_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `grafico_proyecto`()
BEGIN	
	SELECT porcentaje
	FROM proyecto
	WHERE estado = 1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `hallar_porcentaje_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `hallar_porcentaje_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `hallar_porcentaje_fase`()
BEGIN
	UPDATE fases fas
	SET fas.porcentaje_fase = (
		SELECT SUM(tar.porcentaje_tarea * tar.porcentaje /100) FROM tareas tar
		WHERE tar.idfase = fas.idfase AND tar.estado != 0
	);
END */$$
DELIMITER ;

/* Procedure structure for procedure `hallar_porcentaje_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `hallar_porcentaje_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `hallar_porcentaje_proyecto`()
BEGIN 
	UPDATE proyecto pro
	SET pro.porcentaje = (
		SELECT SUM(fas.porcentaje_fase * fas.porcentaje / 100)
		FROM fases fas
		WHERE fas.idproyecto = pro.idproyecto AND fas.estado != 0
	);
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_colaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_colaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_colaboradores`()
BEGIN
    SELECT col.idcolaboradores, col.usuario, col.correo, col.nivelacceso,
        per.apellidos, per.nombres,per.genero,
        (SELECT COUNT(DISTINCT idhabilidades) FROM habilidades WHERE idcolaboradores = col.idcolaboradores) AS Habilidades,
        (SELECT COUNT(DISTINCT fas.idfase) FROM fases fas WHERE fas.idresponsable = col.idcolaboradores AND fas.estado = 1) AS Fases,
        (SELECT COUNT(DISTINCT tar.idtarea) FROM tareas tar WHERE tar.idcolaboradores = col.idcolaboradores AND tar.estado = 1) AS Tareas
    FROM colaboradores col
    INNER JOIN personas per ON col.idpersona = per.idpersona
    WHERE col.estado = '1';
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_fase`()
BEGIN
    SELECT pro.idproyecto,fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.porcentaje_fase, fas.porcentaje,fas.estado
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE fas.estado = 1
    ORDER BY pro.idproyecto, fas.fechainicio, fas.fechafin; -- Ordenar por el idproyecto ascendente
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_fase_by_Colaborador` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_fase_by_Colaborador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_fase_by_Colaborador`(IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores AND nivelacceso = 'C'
    ) THEN
        SELECT fas.idfase, fas.nombrefase
        FROM fases fas
        INNER JOIN tareas tar ON tar.idfase = fas.idfase
        WHERE tar.idcolaboradores = _idcolaboradores
            AND fas.estado = 1
        ORDER BY fas.idfase; -- Ordenar por el ID de la fase ascendente
    ELSE
        SELECT fas.idfase, fas.nombrefase
        FROM fases fas
        WHERE fas.estado = 1
        ORDER BY fas.idfase; -- Ordenar por el ID de la fase ascendente
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_fase_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_fase_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_fase_proyecto`(IN _idproyecto SMALLINT)
BEGIN
    SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
        pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
        fas.fechafin, fas.comentario, fas.estado, fas.porcentaje_fase, fas.porcentaje,
        (SELECT COUNT(*) FROM tareas tar WHERE tar.idfase = fas.idfase) AS Tareas
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE pro.idproyecto = _idproyecto
    ORDER BY fas.fechainicio;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_fase_proyecto_by_C` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_fase_proyecto_by_C` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_fase_proyecto_by_C`(IN _idproyecto SMALLINT, IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores AND nivelacceso = 'C'
    ) THEN
        SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
            pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
            fas.fechafin, fas.comentario, fas.estado, fas.porcentaje_fase, fas.porcentaje,
            (SELECT COUNT(*) FROM tareas tar WHERE tar.idfase = fas.idfase AND tar.idcolaboradores = _idcolaboradores) AS Tareas
        FROM fases fas
        INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
        INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
        INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
        WHERE (NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
            AND EXISTS (SELECT 1 FROM tareas tar WHERE tar.idfase = fas.idfase AND tar.idcolaboradores = _idcolaboradores)
        ORDER BY fas.fechainicio;
    ELSE
        SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
            pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
            fas.fechafin, fas.comentario, fas.estado, fas.porcentaje_fase, fas.porcentaje,
            (SELECT COUNT(*) FROM tareas tar WHERE tar.idfase = fas.idfase) AS Tareas
        FROM fases fas
        INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
        INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
        INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
        WHERE (NULLIF(_idproyecto, '') IS NULL OR pro.idproyecto = _idproyecto)
        ORDER BY fas.fechainicio;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_habilidades` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_habilidades` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_habilidades`()
BEGIN
	SELECT hab.idhabilidades,col.idcolaboradores,per.apellidos,per.nombres,col.usuario,col.nivelacceso,hab.habilidad
	FROM habilidades hab
	INNER JOIN colaboradores col ON hab.idcolaboradores = col.idcolaboradores
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE hab.estado = 1
	ORDER BY hab.habilidad;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_habilidades_by_Col` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_habilidades_by_Col` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_habilidades_by_Col`(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT hab.idhabilidades,col.idcolaboradores,per.apellidos,per.nombres,per.genero,col.usuario,col.nivelacceso,hab.habilidad
	FROM habilidades hab
	INNER JOIN colaboradores col ON hab.idcolaboradores = col.idcolaboradores
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE col.idcolaboradores = _idcolaboradores AND hab.estado = 1
	ORDER BY hab.habilidad;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_habilidades_inac_by_col` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_habilidades_inac_by_col` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_habilidades_inac_by_col`(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT idhabilidades,idcolaboradores,habilidad,estado
	FROM habilidades
	WHERE idcolaboradores = _idcolaboradores AND estado = 2;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_proyecto`()
BEGIN
    SELECT pro.idproyecto,pro.titulo,pro.descripcion,pro.fechainicio,pro.fechafin,pro.precio,
		emp.nombre,pro.estado,col.usuario,
     COUNT(fas.idfase) AS Fases,pro.porcentaje
    FROM proyecto pro
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    LEFT JOIN fases fas ON pro.idproyecto = fas.idproyecto
    INNER JOIN colaboradores col ON col.idcolaboradores = pro.idusuariore
    WHERE pro.estado = '1'
    GROUP BY pro.idproyecto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_proyecto_by_Colaborador` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_proyecto_by_Colaborador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_proyecto_by_Colaborador`(IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores AND nivelacceso = 'C'
    ) THEN
        SELECT DISTINCT pro.idproyecto, pro.titulo
        FROM proyecto pro
        LEFT JOIN fases fas ON fas.idproyecto = pro.idproyecto
        INNER JOIN tareas tar ON tar.idfase = fas.idfase AND tar.idcolaboradores = _idcolaboradores
        WHERE pro.estado = 1
        ORDER BY pro.idproyecto; -- Ordenar por el ID de proyecto
    ELSE
        SELECT pro.idproyecto, pro.titulo
        FROM proyecto pro
        WHERE pro.estado = 1
        ORDER BY pro.idproyecto; -- Ordenar por el ID de proyecto
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_tarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_tarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_tarea`()
BEGIN
	 SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END */$$
DELIMITER ;

/* Procedure structure for procedure `listar_tarea_colaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `listar_tarea_colaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_tarea_colaboradores`(IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores 
        AND nivelacceso IN ('A', 'S')
    ) THEN
        SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
    ELSE
        SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario, col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea', tar.roles,
		tar.fecha_inicio_tarea, tar.fecha_fin_tarea,tar.porcentaje_tarea,tar.evidencia, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE col_tarea.idcolaboradores = _idcolaboradores AND tar.estado = 1	
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_fase`(IN _idfase SMALLINT)
BEGIN
SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa',fas.idresponsable, col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado,fas.porcentaje,fas.porcentaje_fase
	 FROM fases fas
	 INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
	 INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
	 INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
	 WHERE fas.idfase = _idfase
	 ORDER BY pro.idproyecto, fas.fechainicio;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_idpersona` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_idpersona` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_idpersona`(IN _nrodocumento CHAR(8))
BEGIN
	SELECT idpersona 
	FROM personas
	WHERE nrodocumento = _nrodocumento;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_ids` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_ids` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_ids`(IN _idtarea SMALLINT)
BEGIN
	SELECT pro.idproyecto,fas.idfase,idtarea
	FROM tareas tar
	INNER JOIN fases fas ON tar.idfase = fas.idfase
	INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
	WHERE tar.idtarea = _idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_info_colaborador` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_info_colaborador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_info_colaborador`(IN _idcolaboradores SMALLINT)
BEGIN
    SELECT col.idcolaboradores, per.idpersona, col.usuario, col.correo, col.nivelacceso,
        per.apellidos, per.nombres, per.genero, per.nrodocumento, telefono,
        CONCAT('[', GROUP_CONCAT(CONCAT('{"habilidad": "', hab.habilidad, '"}')), ']') AS habilidades,
        (SELECT COUNT(DISTINCT idfase) FROM fases WHERE idresponsable = col.idcolaboradores) AS Fases,
        (SELECT COUNT(DISTINCT idtarea) FROM tareas WHERE idcolaboradores = col.idcolaboradores) AS Tareas
    FROM colaboradores col
    INNER JOIN personas per ON col.idpersona = per.idpersona
    LEFT JOIN habilidades hab ON col.idcolaboradores = hab.idcolaboradores
    WHERE col.idcolaboradores = _idcolaboradores AND col.estado = '1'
    GROUP BY col.idcolaboradores;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_proyecto`(IN _idproyecto SMALLINT)
BEGIN
	SELECT pro.idproyecto, tip.idtipoproyecto, tip.tipoproyecto, emp.idempresa, emp.nombre, pro.titulo, pro.descripcion,
		pro.fechainicio, pro.fechafin, pro.precio, pro.porcentaje, pro.estado, col.usuario,
		COUNT(fas.idfase) AS Fases,
		(SELECT COUNT(*) FROM tareas tar WHERE tar.idfase IN 
		(SELECT fas.idfase FROM fases fas WHERE fas.idproyecto = pro.idproyecto)) AS Tareas
	FROM proyecto pro
	INNER JOIN tiposproyecto tip ON pro.idtipoproyecto = tip.idtipoproyecto
	INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
	LEFT JOIN fases fas ON pro.idproyecto = fas.idproyecto
	INNER JOIN colaboradores col ON col.idcolaboradores = pro.idusuariore
	WHERE pro.idproyecto = _idproyecto
	GROUP BY pro.idproyecto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_tarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_tarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_tarea`(IN _idtarea SMALLINT)
BEGIN
	 SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio,
				fas.fechafin,fas.comentario,col_fase.idcolaboradores AS 'idcolaboradores_f',col_fase.usuario AS 'usuario_fase',
				col_tarea.idcolaboradores AS 'idcolaboradores_t',col_tarea.usuario AS 'usuario_tarea',
				tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea,
				tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE tar.idtarea = _idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_tareas_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_tareas_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_tareas_fase`(IN _idfase SMALLINT)
BEGIN
	 SELECT fas.idfase, tar.idtarea, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario, col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.porcentaje,tar.evidencia, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE fas.idfase = _idfase
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END */$$
DELIMITER ;

/* Procedure structure for procedure `obtener_user` */

/*!50003 DROP PROCEDURE IF EXISTS  `obtener_user` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_user`(IN _correo VARCHAR(100))
BEGIN
	SELECT  col.idcolaboradores,col.correo,col.usuario,col.clave,per.nombres,per.apellidos,per.nrodocumento
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE correo = _correo;
END */$$
DELIMITER ;

/* Procedure structure for procedure `reactivar_fase` */

/*!50003 DROP PROCEDURE IF EXISTS  `reactivar_fase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `reactivar_fase`()
BEGIN 
    UPDATE fases AS fas
    INNER JOIN proyecto AS pro ON pro.idproyecto = fas.idproyecto
    SET fas.estado = 1
    WHERE pro.estado = 1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `reactivar_fase_by_id` */

/*!50003 DROP PROCEDURE IF EXISTS  `reactivar_fase_by_id` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `reactivar_fase_by_id`(IN _idfase SMALLINT)
BEGIN 
    UPDATE fases AS fas
    SET fas.estado = 1
    WHERE fas.idfase = _idfase;
END */$$
DELIMITER ;

/* Procedure structure for procedure `reactivar_proyecto` */

/*!50003 DROP PROCEDURE IF EXISTS  `reactivar_proyecto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `reactivar_proyecto`(IN _idproyecto SMALLINT)
BEGIN 
	UPDATE proyecto SET estado = 1
	WHERE idproyecto = _idproyecto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `reactivar_tarea` */

/*!50003 DROP PROCEDURE IF EXISTS  `reactivar_tarea` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `reactivar_tarea`()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 1
    WHERE fas.estado = 1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `reactivar_tarea_by_id` */

/*!50003 DROP PROCEDURE IF EXISTS  `reactivar_tarea_by_id` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `reactivar_tarea_by_id`(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 1
    WHERE tar.idtarea = _idtarea;
END */$$
DELIMITER ;

/* Procedure structure for procedure `recuperar_clave` */

/*!50003 DROP PROCEDURE IF EXISTS  `recuperar_clave` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `recuperar_clave`(
	IN _idcolaboradores		INT,
	IN _correo			VARCHAR(200),
	IN _clavegenerada		CHAR(4)
)
BEGIN
	UPDATE recuperarClave SET estado = '0' WHERE idcolaboradores = _idcolaboradores;
	INSERT INTO recuperarClave (idcolaboradores,correo,clavegenerada)
	VALUES(_idcolaboradores , _correo, _clavegenerada);
END */$$
DELIMITER ;

/* Procedure structure for procedure `registrarColaboradores` */

/*!50003 DROP PROCEDURE IF EXISTS  `registrarColaboradores` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarColaboradores`(
	IN _idpersona SMALLINT,
	IN _usuario VARCHAR(20),
	IN _correo VARCHAR(20),
	IN _clave VARCHAR(200)
)
BEGIN
	INSERT INTO colaboradores(idpersona,usuario,correo,clave,nivelacceso)
	VALUES(_idpersona,_usuario,_correo,_clave,'C');
END */$$
DELIMITER ;

/* Procedure structure for procedure `registrar_habilidades` */

/*!50003 DROP PROCEDURE IF EXISTS  `registrar_habilidades` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_habilidades`(
	IN _idcolaboradores 	SMALLINT,
	IN _habilidad		VARCHAR(40)	
)
BEGIN 
	INSERT INTO habilidades(idcolaboradores,habilidad)
	VALUES(_idcolaboradores,_habilidad);
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_colaboradores_actualizarclave` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_colaboradores_actualizarclave` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_colaboradores_actualizarclave`(
	IN _idcolaboradores		INT,
	IN _clave			VARCHAR(100)
)
BEGIN
	UPDATE colaboradores SET clave = _clave WHERE idcolaboradores = _idcolaboradores;
	UPDATE recuperarclave SET estado = '0' WHERE idcolaboradores = _idcolaboradores;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_colaborador_validarclave` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_colaborador_validarclave` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_colaborador_validarclave`(
	IN _idcolaboradores	  		INT,
	IN _clavegenerada			CHAR(4)
)
BEGIN 
	IF 
	(
		(
		SELECT clavegenerada FROM recuperarClave 
		WHERE idcolaboradores = _idcolaboradores 
		AND estado = '1' 
		LIMIT 1
		) = _clavegenerada
	)
	THEN 
		SELECT 'PERMITIDO' AS 'status';
	ELSE
		SELECT 'DENEGADO' AS 'status';
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_colaborador_validartiempo` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_colaborador_validartiempo` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_colaborador_validartiempo`(
	IN _idcolaboradores		INT
)
BEGIN
	IF ((SELECT COUNT(*) FROM recuperarclave WHERE idcolaboradores = _idcolaboradores) = 0) THEN
		SELECT 'GENERAR' AS 'status';
		ELSE
		-- Buscamos el ultimo estado del usuario NO IMPORTA SI es 0 o 1
		IF ((SELECT estado FROM recuperarclave WHERE idcolaboradores = _idcolaboradores ORDER BY 1 DESC LIMIT 1) = 0) THEN
			SELECT 'GENERAR' AS 'status';
		ELSE
			-- En esta seccion, el ultimo registro es '1', No sabemos si esta dentro de los 15 min permitidos
		IF
		(
				(
				SELECT COUNT(*) FROM recuperarclave
				WHERE idcolaboradores = _idcolaboradores AND estado = '1' AND NOW() NOT BETWEEN fecharegeneracion AND DATE_ADD(fecharegeneracion, INTERVAL 15 MINUTE)
				ORDER BY fecharegeneracion DESC LIMIT 1
				) = 1
		) THEN
				-- El usuario tiene estado 1, pero esta fuera de los 15 minutos
				SELECT 'GENERAR' AS 'status';
			ELSE
				SELECT 'DENEGAR' AS 'status';
			END IF;
		END IF;
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `ver_evidencia` */

/*!50003 DROP PROCEDURE IF EXISTS  `ver_evidencia` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ver_evidencia`(IN _idtarea SMALLINT)
BEGIN
	SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
	    tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE idtarea = _idtarea
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
