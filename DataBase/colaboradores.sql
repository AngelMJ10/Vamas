USE vamas2;
------------------------------------------ --  Registro de usuario y personas ----------------------------------
-- P.A Para registrar un colaborador

DELIMITER $$
CREATE PROCEDURE registrarColaboradores(
	IN _idpersona SMALLINT,
	IN _usuario VARCHAR(20),
	IN _correo VARCHAR(100),
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
        (SELECT COUNT(DISTINCT idhabilidades) FROM habilidades WHERE idcolaboradores = col.idcolaboradores AND estado = 1) AS Habilidades,
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
        (SELECT COUNT(DISTINCT idfase) FROM fases WHERE idresponsable = col.idcolaboradores) AS Fases,
        (SELECT COUNT(DISTINCT idtarea) FROM tareas WHERE idcolaboradores = col.idcolaboradores) AS Tareas
    FROM colaboradores col
    INNER JOIN personas per ON col.idpersona = per.idpersona
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
-- Para listar las habilidades del colaborador
DELIMITER $$
CREATE PROCEDURE listar_habilidades_by_col(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT idhabilidades,idcolaboradores,habilidad,estado
	FROM habilidades
	WHERE idcolaboradores = _idcolaboradores AND estado = 1;
END $$
DROP PROCEDURE listar_habilidades_by_col
CALL listar_habilidades_by_col(2);

-----------------------------------
-- Listar las habilidades inactivas

DELIMITER $$
CREATE PROCEDURE listar_habilidades_inac_by_col(IN _idcolaboradores SMALLINT)
BEGIN
	SELECT idhabilidades,idcolaboradores,habilidad,estado
	FROM habilidades
	WHERE idcolaboradores = _idcolaboradores AND estado = 2;
END $$

CALL listar_habilidades_inac_by_col(2);

-------------------------------------------

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

-------------------------------------------------
-- Para deshabilitar las habilidades

DELIMITER $$
CREATE PROCEDURE deshabilitar_habilidad
(
	IN _idhabilidades SMALLINT
)
BEGIN
	UPDATE habilidades
	SET estado = 2
	WHERE idhabilidades = _idhabilidades;
END $$

CALL deshabilitar_habilidad(14);
SELECT * FROM habilidades;

--------------------------------------------
-- Para reactivar Habilidades

DELIMITER $$
CREATE PROCEDURE activar_habilidad
(
	IN _idhabilidades SMALLINT
)
BEGIN
	UPDATE habilidades
	SET estado = 1
	WHERE idhabilidades = _idhabilidades;
END $$

CALL activar_habilidad(14);

