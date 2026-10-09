-- Inicializacion del presupuesto mensual de gastos fijos del canal RAD.
-- Solo carga lo que falta: no modifica clases de gasto ni montos ya cargados
-- (los cambios hechos desde la pantalla de Presupuestos se respetan).
-- No hay UNIQUE en gastos_clase.nombre ni en presupuesto (id_canal, id_clase_gasto),
-- asi que se guarda con NOT EXISTS para no duplicar si el script se corre mas de una vez.

-- 1) Clases de gasto. gastos_clase.presupuesto tambien se carga con el monto,
--    porque lo usan el dashboard de finanzas, el flujo de caja proyectado y el estado de resultado.
--    prioridad y dia_pago quedan en su default (1 y 30).
INSERT INTO `gastos_clase` (`nombre`, `presupuesto`)
SELECT g.nombre, g.monto
FROM (
    SELECT 'Alquiler' AS nombre, 700000 AS monto
    UNION ALL SELECT 'Empleados', 300000
    UNION ALL SELECT 'Sueldo Titular', 600000
    UNION ALL SELECT 'Luz', 70000
    UNION ALL SELECT 'Contador', 100000
    UNION ALL SELECT 'Municipal', 100000
    UNION ALL SELECT 'ARBA', 100000
    UNION ALL SELECT 'Monotributo', 100000
    UNION ALL SELECT 'Internet', 50000
    UNION ALL SELECT 'Seguros', 100000
) AS g
WHERE NOT EXISTS (
    SELECT 1 FROM `gastos_clase` gc WHERE gc.nombre = g.nombre
);

-- 2) Presupuesto del canal RAD por clase de gasto (buscando ambos por nombre,
--    para no depender de los ids de cada base).
INSERT INTO `presupuesto` (`id_canal`, `id_clase_gasto`, `monto`)
SELECT c.id, gc.id, g.monto
FROM (
    SELECT 'Alquiler' AS nombre, 700000 AS monto
    UNION ALL SELECT 'Empleados', 300000
    UNION ALL SELECT 'Sueldo Titular', 600000
    UNION ALL SELECT 'Luz', 70000
    UNION ALL SELECT 'Contador', 100000
    UNION ALL SELECT 'Municipal', 100000
    UNION ALL SELECT 'ARBA', 100000
    UNION ALL SELECT 'Monotributo', 100000
    UNION ALL SELECT 'Internet', 50000
    UNION ALL SELECT 'Seguros', 100000
) AS g
JOIN `canal` c
    ON c.id = (SELECT MIN(c2.id) FROM `canal` c2 WHERE c2.nombre = 'RAD')
JOIN `gastos_clase` gc
    ON gc.id = (SELECT MIN(gc2.id) FROM `gastos_clase` gc2 WHERE gc2.nombre = g.nombre)
WHERE NOT EXISTS (
    SELECT 1 FROM `presupuesto` p
    WHERE p.id_canal = c.id
    AND p.id_clase_gasto = gc.id
);
