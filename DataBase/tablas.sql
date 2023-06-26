USE vamas2;

CREATE TABLE personas 
(
	idpersona		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	apellidos		VARCHAR(40) 	NOT NULL,
	nombres			VARCHAR(40) 	NOT NULL,
	tipodocumento		VARCHAR(20)	NOT NULL,
	nrodocumento		CHAR(8)	   	NOT NULL,
	telefono		CHAR(9)		NOT NULL,
	direccion		VARCHAR(200)	NOT NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	fechanac		DATE 		NOT NULL,
	fecha_create		DATETIME 	NOT NULL DEFAULT NOW(),
	fechabaja		DATETIME	NULL
)ENGINE = INNODB;

INSERT INTO personas (apellidos,nombres,tipodocumento,nrodocumento,telefono,direccion,fechanac)
VALUES('Marquina Jaime','Ángel Eduardo','DNI','72745028','951531166','León de Vivero MZ V L-2','2004-07-10'),
	('Padilla Chumbiauca','Marks Steven','DNI','72854857','924563458','Atrás de plaza vea','2004-06-07'),
	('Uribe Garcia','Cristhian Manuel','DNI','72548675','95123654','Rosedal por donde roban','2004-05-21'),
	('Chacaliaza Pachas','Ítalo Jesús','DNI','7254789','963214587','AV. Santos Nagaro 210','2003-10-29'),
	('Marquina Jaime','Emily Fernanda','DNI','78383886','952145879','León de Vivero Mz V LT-22','2013-12-16');
INSERT INTO personas (apellidos,nombres,tipodocumento,nrodocumento,telefono,direccion,fechanac)
VALUES('Marquina Jaime','Wanda','DNI','85758965','951531166','León de Vivero MZ V L-2','2004-07-10')
SELECT * FROM personas;

--------------------------------------------------------------

CREATE TABLE colaboradores
(
	idcolaboradores		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	idpersona		SMALLINT 	NOT NULL,
	usuario			VARCHAR(20)	NOT NULL,
	clave			VARCHAR(200)	NOT NULL,
	corre			VARCHAR(100)	NULL,
	nivelacceso		CHAR(1)		NOT NULL DEFAULT 'C',
	fecha_create		DATETIME 	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idpersona_per FOREIGN KEY (idpersona) REFERENCES personas(idpersona)		
)ENGINE = INNODB; 

INSERT INTO colaboradores(idpersona,usuario,clave,nivelacceso)
VALUES(1,'AngelMJ','SENATI','A'),(2,'MarksPC','SENATI','S'),
	(5,'EmyMJ','SENATI','S'),(4,'JesusPC','SENATI','C');

UPDATE colaboradores SET
	clave = '$2y$10$WY.iP85bEYxBMkVBG0jKO.9Q97kEbofLVwJPUT1OAmsDzLXQ8Pcka';
UPDATE colaboradores SET
	correo = 'angelitomasna200410@gmail.com' WHERE idcolaboradores = 1;

SELECT * FROM colaboradores WHERE usuario = 'AngelMJ' AND estado = 1;

----------------------------------------------------------------

CREATE TABLE recuperarClave
(
	idrecuperar		INT AUTO_INCREMENT PRIMARY KEY,
	idcolaboradores		SMALLINT 	NOT NULL,
	fecharegeneracion	DATETIME 	NOT NULL DEFAULT NOW(),
	correo			VARCHAR(200)	NOT NULL, 		-- Email que se utilizó en ese momento
	clavegenerada		CHAR(4)		NOT NULL,
	estado 			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idcolaboradores_rcl FOREIGN KEY(idcolaboradores) REFERENCES colaboradores(idcolaboradores)
)ENGINE = INNODB;

INSERT INTO recuperarClave(idcolaboradores,correo,clavegenerada) 
VALUES(1,'email@email.com','1234');
SELECT * FROM recuperarClave;

--------------------------------------------

CREATE TABLE habilidades
(
	idhabilidades		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	idcolaboradores		SMALLINT 	NOT NULL,
	habilidad		VARCHAR(40)	NOT NULL,
	fecha_create		DATETIME 	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idcolaboradores_col FOREIGN KEY(idcolaboradores) REFERENCES colaboradores (idcolaboradores)
)ENGINE = INNODB;

INSERT INTO habilidades(idcolaboradores,habilidad)
VALUES('1','Front-end Básico'),('2','Front-end framework React'),('1','Análisis de datos');
SELECT * FROM habilidades;

----------------------------------------------------------

CREATE TABLE empresas
(
	idempresa		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	nombre			VARCHAR(40)	NOT NULL,
	razonsocial		VARCHAR(40)	NOT NULL,
	tipodocumento		VARCHAR(20)	NOT NULL,
	documento		VARCHAR(40)	NOT NULL,
	fecha_create		DATETIME	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1'
)ENGINE = INNODB;

INSERT INTO empresas(nombre,razonsocial,tipodocumento,documento)
VALUES('VAMAS S.A.C.','Vamas','RUC','20609878313'),
	('Mamá Carmen','Restaurant Mamá Carmen','RUC','20452365744'),
	('Prueba1','Prueba 1','RUC','2015785698'),
	('Prueba2','Prueba 2','RUC','20457896321');
	
UPDATE empresas SET estado = 1 WHERE idempresa = 1;

SELECT * FROM empresas;

-------------------------------------------------------------

CREATE TABLE tiposproyecto
(
	idtipoproyecto		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	tipoproyecto		VARCHAR(40)	NOT NULL,
	fecha_create		DATETIME	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL ,
	estado			CHAR(1)		NOT NULL DEFAULT '1'
)ENGINE = INNODB;

INSERT INTO tiposproyecto(tipoproyecto) 
VALUES('Desarollo Web'),('Sistema de ventas'),('Sistema de almacen'),('Marketing');
SELECT * FROM tiposproyecto WHERE estado = 1

----------------------------------------------------------

CREATE TABLE proyecto
(
	idproyecto		SMALLINT AUTO_INCREMENT PRIMARY KEY,
	idtipoproyecto      	SMALLINT    	NOT NULL,
	idempresa           	SMALLINT    	NOT NULL,
	titulo			VARCHAR(60)	NOT NULL,
	descripcion		VARCHAR(200)	NOT NULL,
	fechainicio		DATE 		NOT NULL,
	fechafin		DATE		NOT NULL,
	precio			DECIMAL(6,2)	NOT NULL,
	condiciones		JSON		NULL,
	idusuariore		SMALLINT	NOT NULL,
	porcentaje		DECIMAL(5,2)	NULL DEFAULT'0',
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	fecha_create		DATETIME	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL,
	CONSTRAINT fk_idtipoproyecto_pro FOREIGN KEY (idtipoproyecto) REFERENCES tiposproyecto (idtipoproyecto),
	CONSTRAINT fk_idempresa_tip 	FOREIGN KEY (idempresa) REFERENCES empresas (idempresa),
	CONSTRAINT fk_idusuariore_pro	FOREIGN KEY (idusuariore) REFERENCES colaboradores (idcolaboradores)
)ENGINE = INNODB;

INSERT INTO proyecto (idtipoproyecto,idempresa,titulo,descripcion,fechainicio,fechafin,precio,idusuariore)
VALUES ('1','1','Página web sobre test psicologicos','Prueba 2','2023-05-29','2023-05-31',150.00,'1');

INSERT INTO proyecto (idtipoproyecto,idempresa,titulo,descripcion,fechainicio,fechafin,precio,idusuariore)
VALUES ('2','2','Sistema de ventas pra un restaurante','Prueba 3','2023-05-29','2023-05-31',120.00,'1');

SELECT * FROM proyecto;

CALL listar_proyecto();

----------------------------------------------------------

CREATE TABLE fases
(
	idfase				SMALLINT AUTO_INCREMENT PRIMARY KEY,
	idproyecto			SMALLINT 	NOT NULL,
	idresponsable			SMALLINT 	NOT NULL,
	nombrefase				VARCHAR(40)	NOT NULL,
	fechainicio				DATE		NOT NULL,
	fechafin					DATE		NOT NULL,
	comentario				VARCHAR(200)	NOT NULL,
	porcentaje_fase		DECIMAL(5,2)	NULL DEFAULT 0,
	porcentaje				DECIMAL(5,2)	NOT NULL,
	fecha_create		DATETIME	NOT NULL DEFAULT NOW(),
	fecha_update		DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idproyecto_fas FOREIGN KEY (idproyecto) REFERENCES proyecto (idproyecto),
	CONSTRAINT fk_idresponsable_fas FOREIGN KEY (idresponsable) REFERENCES colaboradores (idcolaboradores)
)ENGINE = INNODB;

INSERT INTO fases(idproyecto,idresponsable,nombrefase,fechainicio,fechafin,comentario,porcentaje) 
VALUES(1,3,'Creación del boceto','2023-05-29','2023-05-31',
	'En esta fase se crearán diferentes tipos de bocetos e ideas para la pagina y se eligirá uno',25);

INSERT INTO fases(idproyecto,idresponsable,nombrefase,fechainicio,fechafin,comentario,porcentaje) 
VALUES(1,'2','Creación de la Vista','2023-05-31','2023-06-03',
	'En esta fase se creará la vista basada en el boceto escogido',25);
	
INSERT INTO fases(idproyecto,idresponsable,nombrefase,fechainicio,fechafin,comentario,porcentaje) 
VALUES(2,'4','Creación de un modelo de base de datos','2023-05-31','2023-06-03',
	'En esta fase se definirá la base de datos',25);


SELECT * FROM fases;
CALL listar_fase();

----------------------------------------------------------

CREATE TABLE tareas 
(
	idtarea					SMALLINT AUTO_INCREMENT PRIMARY KEY,
	idfase					SMALLINT 	NOT NULL,
	idcolaboradores		SMALLINT	NOT NULL,
	roles						VARCHAR(40)	NOT NULL,
	tarea						VARCHAR(200)	NOT NULL,
	porcentaje_tarea		DECIMAL(5,2)	NULL DEFAULT 0,
	porcentaje				DECIMAL(5,2)	NOT NULL,
	evidencia				JSON		NULL DEFAULT'[]',
	fecha_inicio_tarea	DATE 		NOT NULL,
	fecha_fin_tarea		DATE 		NOT NULL,
	fecha_create			DATETIME	NOT NULL DEFAULT NOW(),
	fecha_update			DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idfase_tar FOREIGN KEY (idfase) REFERENCES fases (idfase),
	CONSTRAINT fk_idcolaboradores_tar FOREIGN KEY (idcolaboradores) REFERENCES colaboradores (idcolaboradores)
)ENGINE = INNODB;

INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,fecha_inicio_tarea,fecha_fin_tarea)
VALUES(1,4,'Programador Front-end','Hacer el boceto y presentar su avance',10,'2023-06-22','2023-06-24');

INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,fecha_inicio_tarea,fecha_fin_tarea)
VALUES('1','3','Programador Front-end','Hacer el boceto y presentar su avance',20,'2023-06-22','2023-06-25');

INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,fecha_inicio_tarea,fecha_fin_tarea)
VALUES('1','4','Programador Front-end','Hacer el boceto y presentar su avance',60,'2023-06-22','2023-06-26');

INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,fecha_inicio_tarea,fecha_fin_tarea)
VALUES('3','4','Analista de datos','Hacer un modelo de base de datos',60,'2023-06-22','2023-06-27');

INSERT INTO tareas(idfase,idcolaboradores,roles,tarea,porcentaje,fecha_inicio_tarea,fecha_fin_tarea)
VALUES('2','3','Implementación de la vista','Implementar la vista al sistemas',60,'2023-06-22','2023-06-28');

SELECT * FROM tareas;
TRUNCATE TABLE tareas;
SELECT * FROM colaboradores;
SELECT * FROM fases;

SELECT tar.porcentaje_tarea * tar.porcentaje /100 FROM tareas tar;

SELECT fas.porcentaje_fase * fas.porcentaje /100 FROM fases fas;
