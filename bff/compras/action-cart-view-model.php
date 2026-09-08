<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerProductoParaCarritoOrden($id)
{
    $productoPDO = new Producto();

    return $productoPDO->getPrecioYCostoConEditorial($id);
}
?>
