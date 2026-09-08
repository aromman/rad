<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/productoStock.php';

function inicializarValidacionProductos($idCanal)
{
    $ventasPDO = new Ventas();
    $ventasPDO->reasignarCanalHuerfano();

    $productoStockPDO = new ProductoStock();
    $productoStockPDO->invalidarPorCanal($idCanal);
}
?>
