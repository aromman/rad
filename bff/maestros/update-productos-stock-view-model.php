<?php
require_once dirname(__DIR__, 2) . '/app/models/productoStock.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacion.php';

function obtenerProductoStockParaEditar($id)
{
    $productoStockPDO = new ProductoStock();

    return $productoStockPDO->getById($id);
}

function obtenerUbicacionesParaSelector()
{
    $productoUbicacionPDO = new ProductoUbicacion();

    return $productoUbicacionPDO->getAllConLabelParaSelector();
}

function actualizarProductoStock($id, $idProducto, $idEditorial, $cantidad, $idUbicacion, $fecha, $costo, $nuevo)
{
    $productoStockPDO = new ProductoStock();
    $productoStockPDO->id = $id;
    $productoStockPDO->idProducto = $idProducto;
    $productoStockPDO->idEditorial = $idEditorial;
    $productoStockPDO->cantidad = $cantidad;
    $productoStockPDO->idUbicacion = $idUbicacion;
    $productoStockPDO->fecha = $fecha;
    $productoStockPDO->costo = $costo;
    $productoStockPDO->nuevo = $nuevo;
    $productoStockPDO->update();
}
?>
