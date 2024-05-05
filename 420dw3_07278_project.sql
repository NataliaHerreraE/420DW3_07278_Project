-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2024 a las 01:41:27
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `420dw3_07278_project`
--
CREATE DATABASE IF NOT EXISTS `420dw3_07278_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `420dw3_07278_project`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_key` varchar(30) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `description` varchar(70) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_key` (`permission_key`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_key`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'LOGIN_ALLOWED', 'Login Access', 'Allows users to log-in to the system.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(2, 'CREATE_PERMISSIONS', 'Create Permissions', 'Allows creating new permission entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(3, 'UPDATE_PERMISSIONS', 'Update Permissions', 'Allows updating existing permission entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(4, 'DELETE_PERMISSIONS', 'Delete Permissions', 'Allows deletion of permission entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(5, 'CREATE_USERGROUPS', 'Create User Groups', 'Allows creating new user group entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(6, 'UPDATE_USERGROUPS', 'Update User Groups', 'Allows updating existing user group entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(7, 'DELETE_USERGROUPS', 'Delete User Groups', 'Allows deletion of user group entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(8, 'CREATE_USERS', 'Create Users', 'Allows creating new user entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(9, 'UPDATE_USERS', 'Update Users', 'Allows updating existing user entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(10, 'DELETE_USERS', 'Delete Users', 'Allows deletion of user entities.', '2024-03-29 13:04:14', '2024-03-29 13:04:14'),
(11, 'SEARCH_USERS', 'Search Users', 'Allows users to perform user search operations.', '2024-03-29 13:31:55', '2024-04-28 03:16:44'),
(12, 'SEARCH_USERGROUPS', 'Search User Groups', 'Allows users to perform user group search operations.', '2024-05-05 19:21:11', '2024-05-05 19:21:11'),
(13, 'SEARCH_PERMISSIONS', 'Search Permissions', 'Allows users to perform permissions search operations.', '2024-04-28 03:16:44', '2024-04-28 03:16:44'),
(14, 'PERMISSION_TEST', 'TestUpdate', 'allow testing', '2024-05-05 18:21:09', '2024-05-05 18:44:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usergroups`
--

DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE IF NOT EXISTS `usergroups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL,
  `description` varchar(70) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usergroups`
--

INSERT INTO `usergroups` (`group_id`, `group_name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'Administrators', 'Group with full access to all management features.', '2024-03-29 17:12:52', '2024-03-29 17:12:52', 0),
(2, 'Managers', 'Group with access to user and group management features.', '2024-03-29 17:12:52', '2024-03-29 17:12:52', 0),
(3, 'Editors', 'Group with access to create and update features.', '2024-03-29 17:12:52', '2024-03-29 17:12:52', 0),
(4, 'Deleters', 'Group with access to delete records.', '2024-03-29 17:12:52', '2024-03-29 17:12:52', 0),
(5, 'Users', 'Basic access group for standard users.', '2024-03-29 17:34:39', '2024-03-29 17:34:39', 0),
(6, 'testUpdated', 'testingUpdate', '2024-05-05 08:14:32', '2024-05-05 10:30:28', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `user_password` varchar(85) NOT NULL,
  `email` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`, `email`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'salem', '$2y$10$dIq/JhlTdgEypTUVY7cvY.tRmYVnMKuUbVaSmaz9jJV4xAMXXKFRm', 'salem@email.com', '2024-04-28 02:04:19', '2024-04-29 00:37:29', 0),
(2, 'tintin', '$2y$10$dIq/JhlTdgEypTUVY7cvY.tRmYVnMKuUbVaSmaz9jJV4xAMXXKFRm', 'tintin@email.com', '2024-04-28 02:04:19', '2024-04-29 00:37:29', 0),
(3, 'mjane', '$2y$10$dIq/JhlTdgEypTUVY7cvY.tRmYVnMKuUbVaSmaz9jJV4xAMXXKFRm', 'mjane@email.com', '2024-04-28 02:04:19', '2024-04-29 00:37:29', 0),
(4, 'pruebaupdate', '$2y$10$7hawumNO3c9Xsox4kWbp2.PQbHmjZq998EZkF8NrQ8KYMlZMxjIku', 'pruebaupdate@email.com', '2024-05-04 00:12:53', '2024-05-04 06:22:26', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_group_permissions`
--

DROP TABLE IF EXISTS `user_group_permissions`;
CREATE TABLE IF NOT EXISTS `user_group_permissions` (
  `user_group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`user_group_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_group_permissions`
--

INSERT INTO `user_group_permissions` (`user_group_id`, `permission_id`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 12),
(2, 5),
(2, 8),
(3, 6),
(3, 9),
(4, 7),
(4, 10),
(5, 1),
(5, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_permissions`
--

INSERT INTO `user_permissions` (`user_id`, `permission_id`) VALUES
(1, 1),
(1, 5),
(2, 1),
(2, 6),
(3, 1),
(3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_usergroup`
--

DROP TABLE IF EXISTS `user_usergroup`;
CREATE TABLE IF NOT EXISTS `user_usergroup` (
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_group_id`),
  KEY `user_group_id` (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_usergroup`
--

INSERT INTO `user_usergroup` (`user_id`, `user_group_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 2),
(3, 3);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `user_group_permissions`
--
ALTER TABLE `user_group_permissions`
  ADD CONSTRAINT `user_group_permissions_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `usergroups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_group_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_usergroup`
--
ALTER TABLE `user_usergroup`
  ADD CONSTRAINT `user_usergroup_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_usergroup_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `usergroups` (`group_id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
