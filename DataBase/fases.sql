USE vamas2;
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
-------------------------------------------------
-- Para finalizar las tareas de la fase

DELIMITER $$
CREATE PROCEDURE finalizar_tarea()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 2, tar.fecha_update = NOW()
    WHERE fas.estado = 2;
END $$

CALL finalizar_tarea;

-- Para reactivar las tareas de la fase
DELIMITER $$
CREATE PROCEDURE reactivar_tarea()
BEGIN 
    UPDATE tareas AS tar
    INNER JOIN fases AS fas ON fas.idfase = tar.idfase
    SET tar.estado = 1
    WHERE fas.estado = 1;
END $$

-------------------------------------------- PORCENTAJES ------------------------------------------------------


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

