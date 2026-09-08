<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function procesarPagoConsignatario($idConsignatario)
{
    $compraPDO = new Compra();
    $compraPDO->updateEstadoByConsignacion($idConsignatario, 6);
}
?>
