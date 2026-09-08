<?php
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';

function registrarVentaHeaderNormalizada($idCanal, $fecha, $idCliente, $idMedioPago, $cantidad, $subTotal, $descuento, $total)
{
    $ventasHeaderPDO = new VentasHeader();
    $ventasHeaderPDO->idCanal = $idCanal;
    $ventasHeaderPDO->fecha = $fecha;
    $ventasHeaderPDO->idCliente = $idCliente;
    $ventasHeaderPDO->idMedioPago = $idMedioPago;
    $ventasHeaderPDO->cantidad = $cantidad;
    $ventasHeaderPDO->subTotal = $subTotal;
    $ventasHeaderPDO->total = $total;
    $ventasHeaderPDO->descuento = !empty($descuento) ? $descuento : 0;

    return $ventasHeaderPDO->create();
}
?>
