<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerComprasCanalesMensualViewModel()
{
    $compraPDO = new Compra();

    return array(
        'resumen' => (array) $compraPDO->getResumenPorCanalYSerieMensual(),
    );
}
?>
