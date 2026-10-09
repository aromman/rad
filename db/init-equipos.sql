-- Alta del equipo 1 en `equipos`.
-- Las ventas se graban siempre con id_equipo = 1 (Ventas::createMasivo) y
-- ventas.id_equipo tiene FK a equipos.id, asi que sin este registro toda venta falla.
-- Se inserta con id explicito y NOT EXISTS: si el equipo 1 ya existe (por ejemplo
-- en produccion, con otro nombre) no se modifica.

INSERT INTO `equipos` (`id`, `equipo`)
SELECT 1, 'General'
WHERE NOT EXISTS (
    SELECT 1 FROM `equipos` WHERE `id` = 1
);
