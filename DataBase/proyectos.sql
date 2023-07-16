USE vamas2;
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

-----------------------------------------------

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

------------------------------------------

-- Para finalizar la fase por su ID
DELIMITER $$
CREATE PROCEDURE finalizar_fase_by_id(IN _idfase SMALLINT)
BEGIN 
    UPDATE fases AS fas
    SET fas.estado = 2, fas.fecha_update = NOW()
    WHERE fas.idfase = _idfase;
END $$

CALL finalizar_fase_by_id(1);


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


DELIMITER $$
CREATE PROCEDURE grafico_proyecto()
BEGIN	
	SELECT porcentaje
	FROM proyecto
	WHERE estado = 1;
END $$

CALL grafico_proyecto()
