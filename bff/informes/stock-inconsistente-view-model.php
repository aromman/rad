<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerStockInconsistenteViewModel()
{
    $productoPDO = new Producto();

    return array(
        'productos' => (array) $productoPDO->getAllConComprasYVentasNoConsignacion(),
    );
}
?>
