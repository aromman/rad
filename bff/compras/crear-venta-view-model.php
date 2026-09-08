<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerDetalleOrdenParaCarrito($idOrdenCompra)
{
    $compraPDO = new Compra();

    return (array) $compraPDO->getDetallePorOrdenParaCarrito($idOrdenCompra);
}
?>
