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
	SELECT hab.idhabilidades,col.idcolaboradores,per.apellidos,per.nombres,per.genero,col.usuario,col.nivelacceso,hab.habilidad
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
    SELECT col.idcolaboradores, col.usuario, col.correo, col.nivelacceso,
        per.apellidos, per.nombres,per.genero,
        (SELECT COUNT(DISTINCT idhabilidades) FROM habilidades WHERE idcolaboradores = col.idcolaboradores) AS Habilidades,
        (SELECT COUNT(DISTINCT fas.idfase) FROM fases fas WHERE fas.idresponsable = col.idcolaboradores AND fas.estado = 1) AS Fases,
        (SELECT COUNT(DISTINCT tar.idtarea) FROM tareas tar WHERE tar.idcolaboradores = col.idcolaboradores AND tar.estado = 1) AS Tareas
    FROM colaboradores col
    INNER JOIN personas per ON col.idpersona = per.idpersona
    WHERE col.estado = '1';
END $$

DROP PROCEDURE listar_colaboradores
CALL listar_colaboradores()
SELECT * FROM habilidades

-------------------------------------------------------------
-- Buscar usuario
DELIMITER $$
CREATE PROCEDURE buscar_colaboradores(
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
END $$
DROP PROCEDURE buscar_colaboradores
CALL buscar_colaboradores('A','','');
-------------------------------------------
-- P.A Para obtener la información de un colaborador por su ID

DELIMITER $$
CREATE PROCEDURE obtener_info_colaborador(IN _idcolaboradores SMALLINT)
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
END $$


DROP PROCEDURE obtener_info_colaborador
CALL obtener_info_colaborador(2)

------------------------------------
-- Para editar al colaborador

DELIMITER $$
CREATE PROCEDURE editar_Colaborador
(
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
END $$

CALL editar_Colaborador(1,'AngelMJ','1342364@senati.pe','A','Marquina Jaime','Ángel Eduardo','M',72745028,951531166);

-----------------------------------------------
-- Para registrar habilidades con usuario

DELIMITER $$
CREATE PROCEDURE registrar_habilidades
(
	IN _idcolaboradores 	SMALLINT,
	IN _habilidad		VARCHAR(40)	
)
BEGIN 
	INSERT INTO habilidades(idcolaboradores,habilidad)
	VALUES(_idcolaboradores,_habilidad);
END $$

CALL registrar_habilidades(1,'Back-end Básico');
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

-------------------------------------------

DELIMITER $$
CREATE PROCEDURE buscar_proyecto(IN _idtipoproyecto INT, IN _idempresa INT, IN _estado_proyecto VARCHAR(255))
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
END $$

DROP PROCEDURE buscar_proyecto
CALL buscar_proyecto('','', '');


---------------------------------------

-- P.A para obtener info del proyecto con su ID

DELIMITER $$
CREATE PROCEDURE obtener_proyecto(IN _idproyecto SMALLINT)
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
END $$
DROP PROCEDURE obtener_proyecto
CALL obtener_proyecto(1);

--------------------------------------
-- Solo te aparecerán las fases donde estás
DELIMITER $$
CREATE PROCEDURE listar_proyecto_by_Colaborador(IN _idcolaboradores SMALLINT)
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
END $$
DELIMITER ;

DROP PROCEDURE listar_proyecto_by_Colaborador
CALL listar_proyecto_by_Colaborador(1);
--------------------------------------------------------------------
-- Para contar los usuario en los proyectos
DELIMITER $$
CREATE PROCEDURE contar_total_colaboradores(IN _idproyecto SMALLINT)
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
END $$

DROP PROCEDURE contar_total_colaboradores;
CALL contar_total_colaboradores(2);

---------------------------------------------------------
-- Contar colaboradores con nombres

DELIMITER $$
CREATE PROCEDURE contar_colaboradores(IN _idproyecto SMALLINT)
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
END $$

DROP PROCEDURE contar_colaboradores
CALL contar_colaboradores(1);

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

-----------------------------------------
-- Solo te aparecerán las fases donde estás
DELIMITER $$
CREATE PROCEDURE listar_fase_by_Colaborador(IN _idcolaboradores SMALLINT)
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
END $$

CALL listar_fase_by_Colaborador(4);

------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE buscar_fase(
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
END $$

DROP PROCEDURE buscar_fase
CALL buscar_fase('','','','1');

------------------------------------------------------------

-- P.A para listar las fases de un proyecto con el ID del un  proyecto

DELIMITER $$
CREATE PROCEDURE listar_fase_proyecto(IN _idproyecto SMALLINT)
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
END $$

DROP PROCEDURE listar_fase_proyecto
CALL listar_fase_proyecto(1);

------------------------------------------------------------------
-- Para listar las fases de un proyecto con el ID del proyecto y el ID del colaborador
DELIMITER $$
CREATE PROCEDURE listar_fase_proyecto_by_C(IN _idproyecto SMALLINT, IN _idcolaboradores SMALLINT)
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
END $$
DELIMITER ;

DROP PROCEDURE listar_fase_proyecto_by_C
CALL listar_fase_proyecto_by_C('',4);


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
	 WHERE fas.idfase = _idfase
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

CALL listar_tarea_colaboradores(1);

------------------------------------------
-- P.A para buscar tareas por la fase,nombre de la tarea y estado
DELIMITER $$
CREATE PROCEDURE buscarTareas(
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
END $$
DELIMITER ;

DROP PROCEDURE buscarTareas;
CALL buscarTareas('1','','','','4','');

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
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.porcentaje,tar.evidencia, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE fas.idfase = _idfase
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
        WHERE tar.idtarea = _idtarea;
END $$
DROP PROCEDURE obtener_tarea
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
        WHERE idtarea = _idtarea
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

CALL hallar_porcentaje_proyecto

-----------------------------------

-- P.A para calcular el porcentaje de la fase

DELIMITER $$
CREATE PROCEDURE hallar_porcentaje_fase()
BEGIN
	UPDATE fases fas
	SET fas.porcentaje_fase = (
		SELECT SUM(tar.porcentaje_tarea * tar.porcentaje /100) FROM tareas tar
		WHERE tar.idfase = fas.idfase AND tar.estado != 0
	);
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

--------------------------------------------

DELIMITER $$
CREATE PROCEDURE grafico_proyecto()
BEGIN	
	SELECT porcentaje
	FROM proyecto
	WHERE estado = 1;
END $$

CALL grafico_proyecto()

-------------------------------------------------------------

-- P.A para finalizar proyecto

DELIMITER $$
CREATE PROCEDURE finalizar_proyecto(IN _idproyecto SMALLINT)
BEGIN 
	UPDATE proyecto SET estado = 2, fecha_update = NOW()
	WHERE idproyecto = _idproyecto;
END $$

CALL finalizar_proyecto(2);
SELECT * FROM proyecto;
DROP PROCEDURE finalizar_proyecto;
----------------------------------------------

-- Para reactivar un proyecto 
DELIMITER $$
CREATE PROCEDURE reactivar_proyecto(IN _idproyecto SMALLINT)
BEGIN 
	UPDATE proyecto SET estado = 1
	WHERE idproyecto = _idproyecto;
END $$

CALL reactivar_proyecto(2);

DROP PROCEDURE reactivar_proyecto;

-----------------------------------------------------
-- Para finalizar la fase con el proyecto 
DELIMITER $$
CREATE PROCEDURE finalizar_fase()
BEGIN 
    UPDATE fases AS fas
    INNER JOIN proyecto AS pro ON pro.idproyecto = fas.idproyecto
    SET fas.estado = 2, fas.fecha_update = NOW()
    WHERE pro.estado = 2;
END $$

CALL finalizar_fase();
SELECT * FROM fases

-- Para finalizar la fase por su ID
DELIMITER $$
CREATE PROCEDURE finalizar_fase_by_id(IN _idfase SMALLINT)
BEGIN 
    UPDATE fases AS fas
    SET fas.estado = 2, fas.fecha_update = NOW()
    WHERE fas.idfase = _idfase;
END $$

CALL finalizar_fase_by_id(1);

--------------------------------------------------
-- Para reactivar una fase
DELIMITER $$
CREATE PROCEDURE reactivar_fase()
BEGIN 
    UPDATE fases AS fas
    INNER JOIN proyecto AS pro ON pro.idproyecto = fas.idproyecto
    SET fas.estado = 1
    WHERE pro.estado = 1;
END $$

CALL reactivar_fase();

-- Para reactivar una fase por su ID
DELIMITER $$
CREATE PROCEDURE reactivar_fase_by_id(IN _idfase SMALLINT)
BEGIN 
    UPDATE fases AS fas
    SET fas.estado = 1
    WHERE fas.idfase = _idfase;
END $$

CALL reactivar_fase_by_id(1);
-------------------------------------------
-- Para finalizar las tareas de la fase

DELIMITER $$
CREATE PROCEDURE finalizar_tarea()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE fas.estado = 2;
END $$

SELECT * FROM tareas
CALL finalizar_tarea;

-- Para finalizar tareas por su id
DELIMITER $$
CREATE PROCEDURE finalizar_tarea_by_id(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE tar.idtarea = _idtarea;
END $$
CALL finalizar_tarea_by_id(1);

---------------------------------------

-- Para reactivar las tareas de la fase
DELIMITER $$
CREATE PROCEDURE reactivar_tarea()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 1
    WHERE fas.estado = 1;
END $$

CALL reactivar_tarea();
DROP PROCEDURE reactivar_tarea;

-- Reactivar tareas por id de la tarea

DELIMITER $$
CREATE PROCEDURE reactivar_tarea_by_id(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 1
    WHERE tar.idtarea = _idtarea;
END $$

CALL reactivar_tarea_by_id(1)
