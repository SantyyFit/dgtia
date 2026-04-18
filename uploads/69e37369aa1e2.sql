-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-04-2026 a las 00:59:43
-- Versión del servidor: 10.6.24-MariaDB-cll-lve-log
-- Versión de PHP: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cruzzsan_dgtia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `nombre` text NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `img_perfil` varchar(255) DEFAULT 'default.png',
  `descripcion` text DEFAULT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nivel` int(11) NOT NULL,
  `numero_telefono` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `usuario`, `nombre`, `email`, `password`, `img_perfil`, `descripcion`, `grupo`, `especialidad`, `fecha_registro`, `nivel`, `numero_telefono`) VALUES
(23, 'pablo', 'pablo', NULL, 'f9515495fff096b1a914175c3765fd79', 'default.png', NULL, NULL, NULL, '2026-04-18 06:53:53', 0, 0),
(24, 'pablo', 'pablo', NULL, 'f9515495fff096b1a914175c3765fd79', 'default.png', NULL, NULL, NULL, '2026-04-18 06:58:09', 0, 0),
(22, 'ighjb', 'ghv', NULL, 'f9515495fff096b1a914175c3765fd79', 'default.png', NULL, NULL, NULL, '2026-04-18 06:49:21', 0, 0),
(21, 'hola', 'hola', NULL, 'f9515495fff096b1a914175c3765fd79', 'default.png', NULL, NULL, NULL, '2026-04-18 06:43:30', 0, 0),
(20, 'Gadiel', 'Gadiel', NULL, 'ce3adb1954d0b97b740a3fcdf6737b04', 'default.png', NULL, NULL, NULL, '2026-04-18 06:30:59', 0, 0),
(19, 'Santy', 'Santy', NULL, '4e4a970386b04938c25befc96fc0eb88', 'default.png', NULL, NULL, NULL, '2026-04-18 06:30:31', 0, 0),
(18, 'DUKI', 'DUKI', NULL, 'c0b1680593cd43fb2aa91d7675696c50', 'default.png', NULL, NULL, NULL, '2026-04-18 06:29:58', 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
