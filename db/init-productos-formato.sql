-- Alta del formato "Sin Formato" en productos_formato, para productos sin
-- un formato particular asignado.
-- No hay UNIQUE en productos_formato.nombre, asi que se guarda con
-- NOT EXISTS para no duplicarlo si el script se corre mas de una vez.
-- monto_objetivo es NOT NULL sin default, se explicita en 0.

INSERT INTO `productos_formato` (`nombre`, `monto_objetivo`)
SELECT 'Sin Formato', 0
WHERE NOT EXISTS (
    SELECT 1 FROM `productos_formato` WHERE `nombre` = 'Sin Formato'
);
