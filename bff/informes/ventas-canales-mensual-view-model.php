<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerVentasCanalesMensualViewModel()
{
    $ventasPDO = new Ventas();

    return array(
        'resumen' => (array) $ventasPDO->getResumenPorCanalYSerieMensual(),
    );
}
?>
