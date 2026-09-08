<?php
require_once dirname(__DIR__, 2) . '/app/models/productoSerie.php';

function obtenerProductoSerieParaEditar($id)
{
    $productoSeriePDO = new ProductoSerie();

    return $productoSeriePDO->getById($id);
}

function actualizarProductoSerie($id, $nombre, $cupoMaximo, $cupoMinimo)
{
    $productoSeriePDO = new ProductoSerie();
    $productoSeriePDO->id = $id;
    $productoSeriePDO->nombre = $nombre;
    $productoSeriePDO->cupoMaximo = $cupoMaximo;
    $productoSeriePDO->cupoMinimo = $cupoMinimo;
    $productoSeriePDO->update();
}
?>
