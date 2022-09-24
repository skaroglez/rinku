-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2022 a las 21:20:04
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: db_rinku
--
CREATE DATABASE IF NOT EXISTS db_rinku DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_rinku;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'dias'
--

CREATE TABLE IF NOT EXISTS dias (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  vc_nombre varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY id_creador (id_creador)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla dias
--

INSERT INTO dias (id, vc_nombre, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 'Lunes', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 'Martes', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(3, 'Miércoles', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(4, 'Jueves', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(5, 'Viernes', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(6, 'Sábado', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(7, 'Domingo', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'generos'
--

CREATE TABLE IF NOT EXISTS generos (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  vc_nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'generos'
--

INSERT INTO generos (id, vc_nombre, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 'Masculino', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 'Femenino', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(3, 'Otro', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'roles'
--

CREATE TABLE IF NOT EXISTS roles (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  vc_nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'roles'
--

INSERT INTO roles (id, vc_nombre, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 'Sistema', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 'Administrador', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'usuarios'
--

CREATE TABLE IF NOT EXISTS usuarios (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'usuarios'
--

INSERT INTO usuarios (id, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(3, 1, 0, '2022-09-23 18:36:59', '2022-09-23 00:36:59', NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'usuariosdetalles'
--

CREATE TABLE IF NOT EXISTS usuariosdetalles (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario int(10) UNSIGNED NOT NULL,
  id_genero int(10) UNSIGNED NOT NULL,
  vc_nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  vc_apellidos varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  vc_email varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  vc_password varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY FK_UsuariosDetalles_Usuarios (id_usuario),
  KEY id_genero (id_genero)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'usuariosdetalles'
--

INSERT INTO usuariosdetalles (id, id_usuario, id_genero, vc_nombre, vc_apellidos, vc_email, vc_password, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 1, 1, 'Sistema', 'Bladmir 2', 'sistema@bladmir.com', 'Sistema123.', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 2, 1, 'Admin', 'Bladmir', 'admin@bladmir.com', 'Admin123.', 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(3, 3, 2, 'Carolina', 'Gonzalez', 'caro@correo.com', '1', 1, 0, '2022-09-23 18:36:59', '2022-09-08 00:36:59', NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'usuariosroles'
--

CREATE TABLE IF NOT EXISTS usuariosroles (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario int(10) UNSIGNED NOT NULL,
  id_rol int(10) UNSIGNED NOT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY id_usuario (id_usuario),
  KEY id_rol (id_rol)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'usuariosroles'
--

INSERT INTO usuariosroles (id, id_usuario, id_rol, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(1, 1, 1, 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(2, 2, 2, 1, 0, '2022-09-23 18:30:31', '2022-09-23 18:30:31', NULL, 1),
(3, 3, 2, 1, 0, '2022-09-23 18:36:59', '2022-09-08 00:36:59', NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'usuariostokens'
--

CREATE TABLE IF NOT EXISTS usuariostokens (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario int(10) UNSIGNED NOT NULL,
  id_rol int(10) UNSIGNED NOT NULL,
  id_token text COLLATE utf8_spanish_ci NOT NULL,
  id_dispositivo text COLLATE utf8_spanish_ci DEFAULT NULL,
  sn_activo tinyint(4) NOT NULL DEFAULT 1,
  sn_eliminado tinyint(4) NOT NULL DEFAULT 0,
  dt_registro timestamp NOT NULL DEFAULT current_timestamp(),
  dt_editado timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  dt_eliminado timestamp NULL DEFAULT NULL,
  id_creador int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY id_usuario (id_usuario),
  KEY id_rol (id_rol)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla 'usuariostokens'
--

INSERT INTO usuariostokens (id, id_usuario, id_rol, id_token, id_dispositivo, sn_activo, sn_eliminado, dt_registro, dt_editado, dt_eliminado, id_creador) VALUES
(2, 2, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsInJvbCI6Miwia2V5IjoiYmFzZTY0OnFVSDlvaVV2Z0Mwd0ZzS01xRndvOWt2MHd4TVE3MWx2YUZpR29lWmNETGs9IiwiaWF0IjoxNjYyNTc1Nzk0LCJleHAiOjE2OTQxMTE3OTQsImlzcyI6Imh0dHBzOi8vbG9jYWxob3N0L2dsX2llZXNfZXZlbnRvcy9hcGkvbG9naW4iLCJuYmYiOjE2NjI1NzU3OTQsImp0aSI6IkFpZVAwSnJZdWx6b0lldzUifQ.hIADi28VNjFEqynQ2Hy_aC5RNN4munKSglwPSmSOoB0', NULL, 1, 0, '2022-09-23 18:36:34', '2022-09-08 00:36:34', NULL, 2);
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla 'dias'
--
ALTER TABLE dias
  ADD CONSTRAINT dias_ibfk_1 FOREIGN KEY (id_creador) REFERENCES usuarios (id);

--
-- Filtros para la tabla 'usuariosdetalles'
--
ALTER TABLE usuariosdetalles
  ADD CONSTRAINT FK_UsuariosDetalles_Usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  ADD CONSTRAINT usuariosdetalles_ibfk_1 FOREIGN KEY (id_genero) REFERENCES generos (id);

--
-- Filtros para la tabla 'usuariosroles'
--
ALTER TABLE usuariosroles
  ADD CONSTRAINT usuariosroles_ibfk_1 FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  ADD CONSTRAINT usuariosroles_ibfk_2 FOREIGN KEY (id_rol) REFERENCES roles (id);

--
-- Filtros para la tabla 'usuariostokens'
--
ALTER TABLE usuariostokens
  ADD CONSTRAINT usuariostokens_ibfk_1 FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  ADD CONSTRAINT usuariostokens_ibfk_2 FOREIGN KEY (id_rol) REFERENCES roles (id);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
