USE vamas2;
-- P.A

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


-- Listar Proyecto

DELIMITER $$
CREATE PROCEDURE listar_proyecto()
BEGIN
    SELECT pro.idproyecto,pro.titulo,pro.descripcion,pro.fechainicio,pro.fechafin,pro.precio,emp.nombre,pro.estado,col.usuario,
     COUNT(fas.idfase) AS Fases
    FROM proyecto pro
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    LEFT JOIN fases fas ON pro.idproyecto = fas.idproyecto
    INNER JOIN colaboradores col ON col.idcolaboradores = pro.idusuariore
    WHERE pro.estado = '1'
    GROUP BY pro.idproyecto;
END $$

CALL listar_proyecto();


-- Obtener info del proyecto con su ID

DELIMITER $$
CREATE PROCEDURE obtener_proyecto(IN _idproyecto SMALLINT)
BEGIN
	SELECT pro.idproyecto,tip.idtipoproyecto,tip.tipoproyecto,emp.idempresa,emp.nombre,pro.titulo,pro.descripcion,
		pro.fechainicio,pro.fechafin,pro.precio,pro.estado,col.usuario,
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


-- Listar Fases

DELIMITER $$
CREATE PROCEDURE listar_fase()
BEGIN
    SELECT pro.idproyecto,fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE fas.estado = 1
    ORDER BY pro.idproyecto, fas.fechainicio, fas.fechafin; -- Ordenar por el idproyecto ascendente
END $$

CALL listar_fase();


-- Listar las fases de un proyecto con el ID del un  proyecto

DELIMITER $$
CREATE PROCEDURE listar_fase_proyecto(IN _idproyecto SMALLINT)
BEGIN
SELECT fas.idfase, pro.titulo, pro.descripcion, pro.fechainicio AS 'InicioProyecto', pro.fechafin AS 'FinProyecto', 
		pro.precio, emp.nombre AS 'empresa', col.usuario, fas.nombrefase, fas.fechainicio, 
		fas.fechafin, fas.comentario,fas.estado
    FROM fases fas
    INNER JOIN proyecto pro ON pro.idproyecto = fas.idproyecto
    INNER JOIN empresas emp ON pro.idempresa = emp.idempresa
    INNER JOIN colaboradores col ON col.idcolaboradores = fas.idresponsable
    WHERE fas.estado = 1 AND pro.idproyecto = _idproyecto
    ORDER BY pro.idproyecto, fas.fechainicio;
END $$

DROP PROCEDURE listar_fase_proyecto;
CALL listar_fase_proyecto(1);


-- Listar tarea

DELIMITER $$
CREATE PROCEDURE listar_tarea_colaboradores(IN _idcolaboradores SMALLINT)
BEGIN
    IF EXISTS (
        SELECT 1 FROM colaboradores WHERE idcolaboradores = _idcolaboradores 
        AND nivelacceso IN ('A', 'S')
    ) THEN
        SELECT fas.idfase,tar.idtarea, fas.nombrefase, fas.fechainicio, fas.fechafin, fas.comentario, col.usuario,
		tar.roles, tar.tarea,tar.porcentaje_tarea, tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN colaboradores col ON tar.idcolaboradores = col.idcolaboradores
        WHERE tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
    ELSE
        SELECT fas.idfase,tar.idtarea, fas.nombrefase, fas.fechainicio, fas.fechafin, fas.comentario, col.usuario, tar.roles, tar.tarea, tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN colaboradores col ON tar.idcolaboradores = col.idcolaboradores
        WHERE col.idcolaboradores = _idcolaboradores AND tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
    END IF;
END $$
CALL listar_tarea_colaboradores(1);
SELECT * FROM tareas;

--------------------------------------

DELIMITER $$
CREATE PROCEDURE obtener_tarea(IN _idtarea SMALLINT)
BEGIN
	 SELECT fas.idfase,tar.idtarea, fas.nombrefase, fas.fechainicio, fas.fechafin, fas.comentario, col.usuario, tar.roles, tar.tarea, tar.porcentaje, tar.estado
        FROM tareas tar
        INNER JOIN fases fas ON tar.idfase = fas.idfase
        INNER JOIN colaboradores col ON tar.idcolaboradores = col.idcolaboradores
        WHERE idtarea = _idtarea AND tar.estado = 1
        ORDER BY fas.idfase, fas.fechainicio, fas.fechafin;
END $$

CALL obtener_tarea(1);

--------------------------------------

SELECT idcolaboradores,usuario,correo FROM colaboradores WHERE NOT nivelacceso = 'C';
SELECT idcolaboradores,usuario,correo FROM colaboradores WHERE nivelacceso IN ('A','S');

UPDATE tareas
      SET evidencia = JSON_ARRAY_APPEND(evidencia, '$', JSON_OBJECT(
      'mensaje', 'a',
      'documento', 'a',
      'fecha', 'a',
      'hora', 'a'
      
)), porcentaje = '5/10%' WHERE idtarea = 3

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
	DECLARE p_porcentaje_actual INT;
	DECLARE p_porcentaje_nuevo INT;
	DECLARE p_max_porcentaje INT; -- Variable para almacenar el valor máximo permitido
	
	SELECT porcentaje, porcentaje_tarea INTO p_max_porcentaje, p_porcentaje_actual
	FROM tareas
	WHERE idtarea = t_idtarea;
	
	SET p_porcentaje_nuevo = p_porcentaje_actual + p_porcentaje;
	
	IF p_porcentaje_nuevo > p_max_porcentaje THEN
		SET p_porcentaje_nuevo = p_max_porcentaje;
	END IF;
	
	UPDATE tareas
	SET evidencia = JSON_ARRAY_APPEND(evidencia, '$', JSON_OBJECT(
		'mensaje', e_mensaje,
		'documento', e_documento,
		'fecha', e_fecha,
		'hora', e_hora,
		'porcentaje',p_porcentaje
	)),
	porcentaje_tarea = p_porcentaje_nuevo
	WHERE idtarea = t_idtarea;
END $$
DELIMITER ;



CALL enviar_evidencia('a', 'a', 'a', 'a', 1,3);
DROP PROCEDURE enviar_evidencia;
                
SELECT * FROM tareas;

