<?php
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacionTipo.php';

function obtenerProductoUbicacionTipoParaEditar($id)
{
    $tipoPDO = new ProductoUbicacionTipo();

    return $tipoPDO->getById($id);
}

function actualizarProductoUbicacionTipo($id, $codigo, $nombre)
{
    $tipoPDO = new ProductoUbicacionTipo();
    $tipoPDO->id = $id;
    $tipoPDO->codigo = $codigo;
    $tipoPDO->nombre = $nombre;
    $tipoPDO->update();
}
?>
