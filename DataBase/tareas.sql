USE vamas2;
------------------------------------------------------------------ -- P.A DE LAS TAREAS ------------------------------------------------
-- Para listar las tareas
DELIMITER $$
CREATE PROCEDURE listar_tarea()
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
END $$

CALL listar_tarea();

------------------------------------------
-- P.A para buscar tareas por la fase,nombre de la tarea y estado
DELIMITER $$
CREATE PROCEDURE buscar_tareas(
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
END $$

CALL buscar_tareas('','1','','','');

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

---------------------------------------------------------
--  P.A para obtener ids de la fase y proyecto
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

-------------------------------------------------

-- Para finalizar tareas por su id
DELIMITER $$
CREATE PROCEDURE finalizar_tarea_by_id(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE tar.idtarea = _idtarea;
END $$
CALL finalizar_tarea_by_id(1);

-- Reactivar tareas por id de la tarea

DELIMITER $$
CREATE PROCEDURE reactivar_tarea_by_id(IN _idtarea SMALLINT)
BEGIN 
    UPDATE tareas AS tar
    SET tar.estado = 1
    WHERE tar.idtarea = _idtarea;
END $$

CALL reactivar_tarea_by_id(1)


