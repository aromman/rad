<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerUltimasComprasPorProducto($idProducto)
{
    $compraPDO = new Compra();

    return (array) $compraPDO->getUltimasByProducto($idProducto);
}
?>
