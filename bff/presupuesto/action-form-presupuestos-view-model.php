<?php
require_once dirname(__DIR__, 2) . '/app/models/presupuesto.php';

function agregarPresupuestosRapido($canales, $clases, $montos)
{
    foreach ($canales as $key => $idCanal) {
        $presupuestoPDO = new Presupuesto();
        $presupuestoPDO->idCanal = $idCanal;
        $presupuestoPDO->idClaseGasto = $clases[$key];
        $presupuestoPDO->monto = $montos[$key];
        $presupuestoPDO->create();
    }
}
?>
