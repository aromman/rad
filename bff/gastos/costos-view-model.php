<?php
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';

function obtenerCostosListadoViewModel()
{
    $gastosPDO = new Gastos();

    return array(
        'costos' => (array) $gastosPDO->getAllCostosVentaConClaseYMedioPago(),
    );
}
?>
