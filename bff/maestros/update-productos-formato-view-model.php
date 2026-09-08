<?php
require_once dirname(__DIR__, 2) . '/app/models/productoFormato.php';

function obtenerProductoFormatoParaEditar($id)
{
    $productoFormatoPDO = new ProductoFormato();

    return $productoFormatoPDO->getById($id);
}

function actualizarProductoFormato($id, $nombre, $monto)
{
    $productoFormatoPDO = new ProductoFormato();
    $productoFormatoPDO->id = $id;
    $productoFormatoPDO->nombre = $nombre;
    $productoFormatoPDO->montoObjetivo = $monto;
    $productoFormatoPDO->update();
}
?>
