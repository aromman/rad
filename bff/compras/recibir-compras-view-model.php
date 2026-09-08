<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function marcarCompraRecibida($id)
{
    $compraPDO = new Compra();
    $compraPDO->actualizarEstado($id, 5);
}
?>
