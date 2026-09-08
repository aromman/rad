<?php
require_once dirname(__DIR__, 2) . '/app/models/presupuesto.php';

function obtenerPresupuestosListadoViewModel()
{
    $presupuestoPDO = new Presupuesto();
    $rows = $presupuestoPDO->getAllConCanalYClaseGasto();
    $presupuestos = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $presupuestos[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'canal' => isset($row['nombre_canal']) ? $row['nombre_canal'] : '',
            'claseGasto' => isset($row['nombre_clase_gasto']) ? $row['nombre_clase_gasto'] : '',
            'monto' => '$ ' . number_format(isset($row['monto']) ? $row['monto'] : 0, 2, '.', ''),
        );
    }

    return array(
        'presupuestos' => $presupuestos,
    );
}
?>
