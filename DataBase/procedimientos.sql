USE vamas2;
-- P.A
SELECT * FROM colaboradores;

--------------------------  Registro de usuario y personas ----------------------------------

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

DELIMITER $$
CREATE PROCEDURE obtener_idpersona(IN _nrodocumento CHAR(8))
BEGIN
	SELECT idpersona 
	FROM personas
	WHERE nrodocumento = _nrodocumento;
END $$

CALL obtener_idpersona(72854857);


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

DELIMITER $$
CREATE PROCEDURE listar_colaboradores()
BEGIN
	SELECT col.idcolaboradores,col.usuario,col.correo,col.nivelacceso,
	per.apellidos,per.nombres,
	COUNT(hab.idhabilidades) AS Habilidades,
	COUNT(tar.idcolaboradores) AS Tareas
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	LEFT JOIN habilidades hab ON col.idcolaboradores = hab.idcolaboradores
	LEFT JOIN tareas tar ON col.idcolaboradores = tar.idcolaboradores
	WHERE col.estado = '1'
	GROUP BY col.idcolaboradores;
END $$

CALL listar_colaboradores()

-------------------------------------------------------------------

SELECT * FROM habilidades;
-------------------------------------------
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
DROP PROCEDURE obtener_info_colaborador
SELECT * FROM habilidades

CALL obtener_info_colaborador(5)

---------------------------------------------------

-- Listar Proyecto

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

CALL listar_proyecto();

---------------------------------------

-- Obtener info del proyecto con su ID

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

SELECT * FROM personas
----------------------------------------- RESTAURAR CONTRASEÑA -----------------------------------

DELIMITER $$
CREATE PROCEDURE buscar(IN _usuario VARCHAR(20))
BEGIN
	SELECT  col.idcolaboradores,col.correo,col.usuario,col.clave,per.nombres,per.apellidos,per.nrodocumento
	FROM colaboradores col
	INNER JOIN personas per ON col.idpersona = per.idpersona
	WHERE usuario = _usuario;
END $$

DROP PROCEDURE buscar
CALL buscar('AngelMJ')

----------------------------

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
SELECT * FROM recuperarclave;

-----------------------------------

DELIMITER $$
CREATE PROCEDURE spu_colaborador_validarclave
(
	IN _idcolaboradores	  		INT,
	IN _clavegenerada		CHAR(4)
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

-------------------------------------
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

-------------------------------------

-- Listar Fases

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

DROP PROCEDURE listar_fase;
CALL listar_fase();

------------------------------------------------------------

-- Listar las fases de un proyecto con el ID del un  proyecto

DELIMITER $$
CREATE PROCEDURE listar_fase_proyecto(IN _idproyecto SMALLINT)
BEGIN
SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado,fas.porcentaje_fase
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE fas.estado = 1 AND pro.idproyecto = _idproyecto
    ORDER BY pro.idproyecto, fas.fechainicio;
END $$

DROP PROCEDURE listar_fase_proyecto;
CALL listar_fase_proyecto(2);

---------------------------------------------
-- Listar tarea con info del proyecto y fase

DELIMITER $$
CREATE PROCEDURE listar_tarea_colaboradores(IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores 
        AND nivelacceso IN ('A', 'S')
    ) THEN
        SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.porcentaje, tar.estado
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
		tar.fecha_inicio_tarea, tar.fecha_fin_tarea,tar.porcentaje_tarea, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE col_tarea.idcolaboradores = _idcolaboradores AND tar.estado = 1	
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
    END IF;
END $$
DROP PROCEDURE listar_tarea_colaboradores;

CALL listar_tarea_colaboradores(5);

--------------------------------------
-- Obtener info de la tarea

DELIMITER $$
CREATE PROCEDURE obtener_tarea(IN _idtarea SMALLINT)
BEGIN
	 SELECT pro.idproyecto, fas.idfase, tar.idtarea, pro.titulo, fas.nombrefase,tar.tarea, fas.fechainicio, fas.fechafin,
		fas.comentario,col_fase.usuario AS 'usuario_fase', col_tarea.usuario AS 'usuario_tarea',
		 tar.roles, tar.fecha_inicio_tarea, tar.fecha_fin_tarea, tar.porcentaje_tarea, tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN proyecto pro ON fas.idproyecto = pro.idproyecto
        INNER JOIN colaboradores col_tarea ON tar.idcolaboradores = col_tarea.idcolaboradores
        INNER JOIN colaboradores col_fase ON fas.idresponsable = col_fase.idcolaboradores
        WHERE tar.idtarea = _idtarea AND tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END $$

CALL obtener_tarea(1);

-----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE enviar_evidencia
(
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
CALL enviar_evidencia('a', 'a', 'a', 'a', 70,3);
                
SELECT * FROM tareas;

-----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE ver_evidencia(IN _idtarea SMALLINT)
BEGIN
	SELECT evidencia
	FROM tareas
	WHERE idtarea = _idtarea AND estado = 1;
END $$

CALL ver_evidencia(1);

-----------------------------------------------

DELIMITER $$
CREATE PROCEDURE delete_evidencia
(
	IN t_idtarea SMALLINT
)
BEGIN
	UPDATE tareas
	SET evidencia = '[]'
	WHERE idtarea = t_idtarea;
END $$

CALL delete_evidencia(1);
CALL delete_evidencia(2);
CALL delete_evidencia(3);
CALL delete_evidencia(4);


-------------------------------------------- PORCENTAJES ------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE hallar_porcentaje_proyecto(IN _idproyecto SMALLINT)
BEGIN 
	UPDATE proyecto pro
	SET pro.porcentaje = (
		SELECT SUM(fas.porcentaje_fase * fas.porcentaje / 100)
		FROM fases fas
		WHERE fas.idproyecto = _idproyecto AND fas.estado != 0
	)
	WHERE pro.idproyecto = _idproyecto;
END $$

CALL hallar_porcentaje_proyecto(2)

-----------------------------------

DELIMITER $$
CREATE PROCEDURE hallar_porcentaje_fase(IN _idfase SMALLINT)
BEGIN
	UPDATE fases fas
	SET fas.porcentaje_fase = (
		SELECT SUM(tar.porcentaje_tarea * tar.porcentaje /100) FROM tareas tar
		WHERE tar.idfase = fas.idfase AND tar.estado != 0
	)
	WHERE fas.idfase = idfase;
END $$

CALL hallar_porcentaje_fase(1);
CALL hallar_porcentaje_fase(2);
CALL hallar_porcentaje_fase(3);

UPDATE tareas
	SET porcentaje_tarea = '0.00'

SELECT * FROM tareas;
SELECT * FROM colaboradores;
SELECT * FROM fases;
SELECT * FROM proyecto;

------------------------------
-- Obtener ids

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