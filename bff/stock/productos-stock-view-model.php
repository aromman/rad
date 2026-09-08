<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerUltimasComprasPorProductoConAlias($idProducto)
{
    $compraPDO = new Compra();

    return (array) $compraPDO->getUltimasByProductoConAlias($idProducto);
}
?>
