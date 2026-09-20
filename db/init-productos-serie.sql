-- Alta de la serie "Generico" en productos_serie, para productos sin
-- una serie particular asignada.
-- No hay UNIQUE en productos_serie.nombre, asi que se guarda con
-- NOT EXISTS para no duplicarla si el script se corre mas de una vez.
-- cupo_maximo/cupo_minimo quedan en su default (0).

INSERT INTO `productos_serie` (`nombre`)
SELECT 'Generico'
WHERE NOT EXISTS (
    SELECT 1 FROM `productos_serie` WHERE `nombre` = 'Generico'
);
