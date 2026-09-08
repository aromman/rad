<?php
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';

function obtenerGastosCanalesMensualViewModel()
{
    $gastosPDO = new Gastos();

    return array(
        'resumen' => (array) $gastosPDO->getResumenPorCanalYClaseMensual(),
    );
}
?>
