<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/compras.php';
require_once dirname(__DIR__, 2) . '/app/models/consignaciones.php';

function procesarLiquidacionConsignacion($idEditorial, $fecha)
{
    $ventasPDO = new Ventas();
    $ventas = $ventasPDO->getAllByEditorialAndDate($idEditorial, $fecha);

    foreach (is_array($ventas) ? $ventas : array() as $row) {
        $idEstado = 5; // Estado Entregado

        $compraPDO = new Compra();
        $compraPDO->fecha = $row['fecha'];
        $compraPDO->idProducto = $row['id_producto'];
        $compraPDO->cantidad = $row['unidades'];
        $compraPDO->precioLista = $row['precio_unitario'];
        $compraPDO->precioCosto = $row['precio_compra'];
        $compraPDO->idEstado = $idEstado;
        $idCompra = $compraPDO->create();

        $consignacionPDO = new Consignaciones();
        $consignacionPDO->fecha = $row['fecha'];
        $consignacionPDO->idVenta = $row['id'];
        $consignacionPDO->idCompra = $idCompra;
        $consignacionPDO->idEstado = $idEstado;
        $consignacionPDO->create();
    }
}
?>
