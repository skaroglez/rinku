USE db_rinku;

INSERT INTO roles (vc_nombre, id_creador) VALUES ('Encargado de Nomina', 1);

CREATE TABLE IF NOT EXISTS empleadosRoles (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  vc_nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO empleadosRoles (vc_nombre, id_creador) VALUES
('Chofer', 1),
('Cargador', 1),
('Auxiliar', 1);

CREATE TABLE IF NOT EXISTS empleados (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  nu_numero int(10) NOT NULL,
  vc_nombre varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  id_rol int(10) UNSIGNED NOT NULL,
  id_tipo tinyint(4) NOT NULL DEFAULT 1,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_Empleados_EmpleadosRoles FOREIGN KEY( id_rol ) REFERENCES empleadosRoles ( id )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE IF NOT EXISTS empleadosMovimientos (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_empleado int(10) UNSIGNED NOT NULL,
  dt_fecha date NOT NULL,
  sn_cubrio_turno tinyint(4) NOT NULL DEFAULT 0,
  id_rol int(10) UNSIGNED NOT NULL,
  nu_entregas INT NOT NULL DEFAULT 0,
  nu_horas_extras INT NOT NULL DEFAULT 0,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_EmpleadosMovimientos_Empleados FOREIGN KEY( id_empleado ) REFERENCES empleados ( id ),
  CONSTRAINT FK_EmpleadosMovimientos_EmpleadosRoles FOREIGN KEY( id_rol ) REFERENCES empleadosRoles ( id )
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;