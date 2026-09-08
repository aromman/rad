<?php
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';

const EMPLEADOS_DASHBOARD_ID_CLASE_NOMINA = 2;

function obtenerEmpleadosDashboardPeriodo($idClase, $anio, $mes)
{
    $gastosPDO = new Gastos();
    $row = $gastosPDO->getEstadisticasByClaseAndPeriodo($idClase, $anio, $mes);

    $total = !empty($row['total_nomina']) ? (float) $row['total_nomina'] : 0;
    $maximo = !empty($row['monto_maximo']) ? (float) $row['monto_maximo'] : 0;
    $minimo = !empty($row['monto_minimo']) ? (float) $row['monto_minimo'] : 0;
    $promedio = !empty($row['promedio']) ? (float) $row['promedio'] : 0;

    $porcentajeTotal = $porcentajeMaximo = $porcentajeMinimo = $porcentajePromedio = 0;
    if ($total > 0) {
        $porcentajeTotal = 100;
        $porcentajeMaximo = ($maximo * 100) / $total;
        $porcentajeMinimo = ($minimo * 100) / $total;
        $porcentajePromedio = ($promedio * 100) / $total;
    }

    return array(
        'total' => $total,
        'sueldoMaximo' => $maximo,
        'sueldoMinimo' => $minimo,
        'sueldoPromedio' => $promedio,
        'porcentajeTotal' => $porcentajeTotal,
        'porcentajeSueldoMaximo' => $porcentajeMaximo,
        'porcentajeSueldoMinimo' => $porcentajeMinimo,
        'porcentajeSueldoPromedio' => $porcentajePromedio,
    );
}

function obtenerEmpleadosDashboardViewModel()
{
    $mesAnteriorTimestamp = strtotime('-1 month');
    $mesActualTimestamp = time();

    return array(
        'mesAnterior' => obtenerEmpleadosDashboardPeriodo(
            EMPLEADOS_DASHBOARD_ID_CLASE_NOMINA,
            (int) date('Y', $mesAnteriorTimestamp),
            (int) date('n', $mesAnteriorTimestamp)
        ),
        'mesActual' => obtenerEmpleadosDashboardPeriodo(
            EMPLEADOS_DASHBOARD_ID_CLASE_NOMINA,
            (int) date('Y', $mesActualTimestamp),
            (int) date('n', $mesActualTimestamp)
        ),
    );
}
?>
