USE vamas2;
------------------------------------------ --  Registro de usuario y personas ----------------------------------
-- P.A Para registrar un colaborador

DELIMITER $$
CREATE PROCEDURE registrarColaboradores(
	IN _idpersona SMALLINT,
	IN _usuario VARCHAR(20),
	IN _correo VARCHAR(20),
	IN _clave VARCHAR(200)
)
BEGIN
	INSERT INTO colaboradores(idpersona,usuario,correo,clave,nivelacceso)
	VALUES(_idpersona,_usuario,_correo,_clave,'C');
END $$

CALL registrarColaboradores('6','FerMJ','1342364@senati.pe','SENATI');

-----------------------------------------
-- P.A Para obtener el ID de las personas por su número de documento
DELIMITER $$
CREATE PROCEDURE obtener_idpersona(IN _nrodocumento CHAR(8))
BEGIN
	SELECT idpersona 
	FROM personas
	WHERE nrodocumento = _nrodocumento;
END $$

CALL obtener_idpersona(72854857);

-------------------------------------------------

-- Listar Habilidades

DELIMITER $$
CREATE PROCEDURE listar_habilidades()
BEGIN
	SELECT hab.idhabilidades,col.idcolaboradores,per.apellidos,per.nombres,col.usuario,col.nivelacceso,hab.habilidad
	FROM habilidades hab
	INNER JOIN colaboradores col ON hab.idcolaboradores = col.idcolaboradores
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE hab.estado = 1
	ORDER BY hab.habilidad;
END $$

CALL listar_habilidades();

-----------------------------------------------------------------
-- Listar Habilidad por ID del colaborador
DELIMITER $$
CREATE PROCEDURE listar_habilidades_by_Col(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT hab.idhabilidades,col.idcolaboradores,per.apellidos,per.nombres,col.usuario,col.nivelacceso,hab.habilidad
	FROM habilidades hab
	INNER JOIN colaboradores col ON hab.idcolaboradores = col.idcolaboradores
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE col.idcolaboradores = _idcolaboradores AND hab.estado = 1
	ORDER BY hab.habilidad;
END $$

CALL listar_habilidades_by_Col(3);

----------------------------------------- RESTAURAR CONTRASEÑA -----------------------------------

DELIMITER $$
CREATE PROCEDURE buscar(IN _usuario VARCHAR(20))
BEGIN
	SELECT  col.idcolaboradores,col.correo,col.usuario,col.clave,per.nombres,per.apellidos,per.nrodocumento
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE usuario = _usuario;
END $$

CALL buscar('AngelMJ')

-------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE obtener_user(IN _correo VARCHAR(100))
BEGIN
	SELECT  col.idcolaboradores,col.correo,col.usuario,col.clave,per.nombres,per.apellidos,per.nrodocumento
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE correo = _correo;
END $$

CALL obtener_user('angelitomasna200410@gmail.com')

------------------------------------------
-- P.A para registrar y editar un registro de la tabla recuperar_clave
DELIMITER $$
CREATE PROCEDURE recuperar_clave
(
	IN _idcolaboradores		INT,
	IN _correo			VARCHAR(200),
	IN _clavegenerada		CHAR(4)
)
BEGIN
	UPDATE recuperarClave SET estado = '0' WHERE idcolaboradores = _idcolaboradores;
	INSERT INTO recuperarClave (idcolaboradores,correo,clavegenerada)
	VALUES(_idcolaboradores , _correo, _clavegenerada);
END $$

CALL recuperar_clave(1,'1342364@senati.pe','1234');

--------------------------------------------
-- P.A para poder validar el tiempo de 15 minutos 
-- En caso pasado 15 minutos de generado el código,se deniega
DELIMITER $$
CREATE PROCEDURE spu_colaborador_validartiempo
(
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
END $$

CALL spu_colaborador_validartiempo(1);

-----------------------------------
-- P.A para validar la clave generada
DELIMITER $$
CREATE PROCEDURE spu_colaborador_validarclave
(
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
END $$

CALL spu_colaborador_validarclave(1,1234);

----------------------------------------------------------------------------
-- P.A para cambiar la contraseña del usuario
DELIMITER $$
CREATE PROCEDURE spu_colaboradores_actualizarclave
(
	IN _idcolaboradores		INT,
	IN _clave			VARCHAR(100)
)
BEGIN
	UPDATE colaboradores SET clave = _clave WHERE idcolaboradores = _idcolaboradores;
	UPDATE recuperarclave SET estado = '0' WHERE idcolaboradores = _idcolaboradores;
END $$

CALL spu_usuarios_actualizarclave(?,?);

------------------------------------------------------------
-- P.A para listar a los colaboradores
DELIMITER $$
CREATE PROCEDURE listar_colaboradores()
BEGIN
	SELECT col.idcolaboradores,col.usuario,col.correo,col.nivelacceso,
	per.apellidos,per.nombres,
	COUNT(hab.idhabilidades) AS Habilidades,
	COUNT(fas.idresponsable) AS Fases,
	COUNT(tar.idcolaboradores) AS Tareas
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	LEFT JOIN habilidades hab ON col.idcolaboradores = hab.idcolaboradores
	LEFT JOIN fases fas ON col.idcolaboradores = fas.idfase
	LEFT JOIN tareas tar ON col.idcolaboradores = tar.idcolaboradores
	WHERE col.estado = '1'
	GROUP BY col.idcolaboradores;
END $$

CALL listar_colaboradores()

-------------------------------------------
-- P.A Para obtener la información de un colaborador por su ID

DELIMITER $$ 
CREATE PROCEDURE obtener_info_colaborador(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT col.idcolaboradores, col.usuario, col.correo, col.nivelacceso,
	per.apellidos, per.nombres, GROUP_CONCAT(hab.habilidad SEPARATOR ', ') AS habilidades,
	COUNT(tar.idcolaboradores) AS Tareas,
	 IF(col.nivelacceso = 'S', COUNT(fas.idfase),0) AS Fases
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	LEFT JOIN habilidades hab ON col.idcolaboradores = hab.idcolaboradores
	LEFT JOIN fases fas ON col.idcolaboradores = fas.idresponsable
	LEFT JOIN tareas tar ON col.idcolaboradores = tar.idcolaboradores
	WHERE col.idcolaboradores = _idcolaboradores AND col.estado = '1'
	GROUP BY col.idcolaboradores;
END $$

CALL obtener_info_colaborador(1)

--------------------------------------------------- -- P.A DE PROYECTOS -------------------------------------------------------------------

-- P.A para listar todos los proyecto con estado 1

DELIMITER $$
CREATE PROCEDURE listar_proyecto()
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
END $$

CALL listar_proyecto()

---------------------------------------

-- P.A para obtener info del proyecto con su ID

DELIMITER $$
CREATE PROCEDURE obtener_proyecto(IN _idproyecto SMALLINT)
BEGIN
	SELECT pro.idproyecto,tip.idtipoproyecto,tip.tipoproyecto,emp.idempresa,emp.nombre,pro.titulo,pro.descripcion,
		pro.fechainicio,pro.fechafin,pro.precio,pro.porcentaje,pro.estado,col.usuario,
	COUNT(fas.idfase) AS Fases
	FROM proyecto pro
	INNER JOIN tiposproyecto tip ON pro.idtipoproyecto = tip.idtipoproyecto
	INNER JOIN empresas emp	ON pro.idempresa = emp.idempresa
	LEFT JOIN fases fas ON pro.idproyecto = fas.idproyecto
	INNER JOIN colaboradores col ON col.idcolaboradores = pro.idusuariore
	WHERE pro.estado = '1' AND pro.idproyecto = _idproyecto
	GROUP BY pro.idproyecto;
END $$

CALL obtener_proyecto(1);

--------------------------------------------------------------
-- PA. para editar un proyecto

DELIMITER $$
CREATE PROCEDURE editar_proyecto
(
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

END $$

CALL editar_proyecto(1, 1, 1, 'Página web sobre test', 'Prueba 2', '2023-05-29', '2023-05-29', 150);


----------------------------------------------------------- P.A DE FASES ------------------------------------------------

-- P.A para listar fases

DELIMITER $$
CREATE PROCEDURE listar_fase()
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
END $$

CALL listar_fase();

------------------------------------------------------------

-- P.A para listar las fases de un proyecto con el ID del un  proyecto

DELIMITER $$
CREATE PROCEDURE listar_fase_proyecto(IN _idproyecto SMALLINT)
BEGIN
SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado,fas.porcentaje_fase,fas.porcentaje
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE fas.estado = 1 AND pro.idproyecto = _idproyecto
    ORDER BY pro.idproyecto, fas.fechainicio;
END $$

CALL listar_fase_proyecto(1);

--------------------------------------------

-- P.A para obtener la información de la fase por su ID

DELIMITER $$
CREATE PROCEDURE obtener_fase(IN _idfase SMALLINT)
BEGIN
SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa',fas.idresponsable, col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado,fas.porcentaje,fas.porcentaje_fase
	 FROM fases fas
	 INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
	 INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
	 INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
	 WHERE fas.estado = 1 AND fas.idfase = _idfase
	 ORDER BY pro.idproyecto, fas.fechainicio;
END $$

CALL obtener_fase(1);

-----------------------------------------------

-- P.A para editar una fase
DELIMITER $$
CREATE PROCEDURE editar_fase
(
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
END $$

CALL editar_fase(1,3,'Creacion del boceto','2023-06-26','2023-06-27','Prueba de edicion',25);

------------------------------------------------------------------ -- P.A DE LAS TAREAS ------------------------------------------------

-- P.A para listar las tareas con info del proyecto y fase del colaborador

DELIMITER $$
CREATE PROCEDURE listar_tarea_colaboradores(IN _idcolaboradores SMALLINT)
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
END $$
DROP PROCEDURE listar_tarea_colaboradores

CALL listar_tarea_colaboradores(1);

-----------------------------------------------------
-- P.A para editar una tarea
DELIMITER $$
CREATE PROCEDURE editarTarea
(
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
END $$

CALL editarTarea(24,4,'Analista','Prueva V4',25,'2023-06-26','2023-06-29');

------------------------------------------------

-- P.A para obtener las tareas de la fase a través de su ID

DELIMITER $$
CREATE PROCEDURE obtener_tareas_fase(IN _idfase SMALLINT)
BEGIN
	 SELECT fas.idfase, tar.idtarea, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario, col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE fas.idfase = _idfase AND tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END $$

CALL obtener_tareas_fase(1);

---------------------------------------------

-- P.A para obtener la información de la tarea
DELIMITER $$
CREATE PROCEDURE obtener_tarea(IN _idtarea SMALLINT)
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
        WHERE tar.idtarea = _idtarea AND tar.estado = 1;
END $$

CALL obtener_tarea(1);

--------------------------------------------------
-- P.A para crear un tarea

DELIMITER $$
CREATE PROCEDURE crear_tarea
(
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

END $$
DROP PROCEDURE crear_tarea
CALL crear_tarea(3,2,'Analista de Datos', 'Diseña un modelo en erwind de base de datos' , 50,'2023-06-26','2023-06-27');

-----------------------------------------------------

-- P.A para registrar las evidencias que son arrays
DELIMITER $$
CREATE PROCEDURE enviar_evidencia
(
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
END $$
DROP PROCEDURE enviar_evidencia;
CALL enviar_evidencia('a','a','a', 'a', 'a', 'a', 90,1);
SELECT * FROM tareas;
-----------------------------------------------------
-- P.A para ver las evidencias de la tarea a traves de su ID

DELIMITER $$
CREATE PROCEDURE ver_evidencia(IN _idtarea SMALLINT)
BEGIN
	SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.evidencia,tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE idtarea = _idtarea AND tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END $$
DROP PROCEDURE ver_evidencia(1);
-------------------------------------------- PORCENTAJES ------------------------------------------------------

-- P.A para calcular el porcentaje del proyecto

DELIMITER $$
CREATE PROCEDURE hallar_porcentaje_proyecto()
BEGIN 
	UPDATE proyecto pro
	SET pro.porcentaje = (
		SELECT SUM(fas.porcentaje_fase * fas.porcentaje / 100)
		FROM fases fas
		WHERE fas.idproyecto = pro.idproyecto AND fas.estado != 0
	);
END $$

CALL hallar_porcentaje_proyecto(1)

-----------------------------------

-- P.A para calcular el porcentaje de la fase

DELIMITER $$
CREATE PROCEDURE hallar_porcentaje_fase()
BEGIN
	UPDATE fases fas
	SET fas.porcentaje_fase = (
		SELECT SUM(tar.porcentaje_tarea * tar.porcentaje /100) FROM tareas tar
		WHERE tar.idfase = fas.idfase AND tar.estado != 0
	)
END $$

CALL hallar_porcentaje_fase;
SELECT * FROM fases;
------------------------------------

-- P.A para obtener ids de la fase y el proyecto con el ID de la tarea

DELIMITER $$
CREATE PROCEDURE obtener_ids(IN _idtarea SMALLINT)
BEGIN
SELECT pro.idproyecto,fas.idfase,idtarea
FROM tareas tar
INNER JOIN fases fas ON tar.idfase = fas.idfase
INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
WHERE tar.idtarea = _idtarea;
END $$

CALL obtener_ids(5);