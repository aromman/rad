<?php
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';

function registrarComprobanteVenta($id, $numeroFactura, $cae)
{
    $ventasHeaderPDO = new VentasHeader();
    $ventasHeaderPDO->actualizarComprobanteYCae($id, $numeroFactura, $cae);
}
?>
