<?php
require_once dirname(__DIR__, 2) . '/app/models/salario.php';

function agregarSalariosRapido($fechas, $montos)
{
    foreach ($fechas as $key => $fecha) {
        $salarioPDO = new Salario();
        $salarioPDO->fecha = $fecha;
        $salarioPDO->monto = $montos[$key];
        $salarioPDO->create();
    }
}
?>
