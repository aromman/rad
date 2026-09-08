<?php
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerCompraDetalleViewModel($idOrdenCompra)
{
    $ordenCompraPDO = new OrdenCompra();
    $compraPDO = new Compra();

    $header = $ordenCompraPDO->getById($idOrdenCompra);
    $detalle = $compraPDO->getDetails($idOrdenCompra);

    return array(
        'header' => is_array($header) ? $header : null,
        'detalle' => (array) $detalle,
    );
}
?>
