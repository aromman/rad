<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerProductoParaClonar($id)
{
    $productoPDO = new Producto();

    return $productoPDO->getById($id);
}
?>
