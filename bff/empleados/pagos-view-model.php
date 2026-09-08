<?php
require_once dirname(__DIR__, 2) . '/app/models/empleados.php';
require_once dirname(__DIR__, 2) . '/app/models/sueldos.php';

function obtenerPagosViewModel()
{
    $empleadoPDO = new Empleado();
    $sueldoPDO = new Sueldo();

    $empleadosRows = $empleadoPDO->getAllConUltimoPago();
    $pagosRows = $sueldoPDO->getAll('empleado_id, fecha');

    return array(
        'empleados' => is_array($empleadosRows) ? $empleadosRows : array(),
        'pagos' => is_array($pagosRows) ? $pagosRows : array(),
    );
}
?>
