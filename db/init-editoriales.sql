-- Inicializacion de la tabla `editoriales` (Marcas) con el registro actual de RAD.

CREATE TABLE IF NOT EXISTS `editoriales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL,
  `porcentaje` bigint(20) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `consignacion` tinyint(1) NOT NULL DEFAULT 0,
  `monto_fijo` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `editoriales` (`id`, `nombre`, `porcentaje`, `id_proveedor`, `consignacion`, `monto_fijo`)
VALUES (1, 'RAD', NULL, NULL, 0, NULL)
ON DUPLICATE KEY UPDATE
  `nombre` = VALUES(`nombre`),
  `porcentaje` = VALUES(`porcentaje`),
  `id_proveedor` = VALUES(`id_proveedor`),
  `consignacion` = VALUES(`consignacion`),
  `monto_fijo` = VALUES(`monto_fijo`);
