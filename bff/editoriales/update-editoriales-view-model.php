<?php
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';
require_once dirname(__DIR__, 2) . '/app/models/proveedores.php';

function obtenerEditorialParaEditarViewModel($id)
{
    $editorialPDO = new Editorial();
    $proveedorPDO = new Proveedor();

    return array(
        'editorial' => $editorialPDO->getById($id),
        'proveedores' => (array) $proveedorPDO->getAllSimpleOrderByNombre(),
    );
}

function actualizarEditorial($id, $nombre, $porcentaje, $montoFijo, $idProveedor, $consignacion)
{
    $editorialPDO = new Editorial();
    $editorialPDO->id = $id;
    $editorialPDO->nombre = $nombre;
    $editorialPDO->porcentaje = $porcentaje;
    $editorialPDO->montoFijo = $montoFijo;
    $editorialPDO->idProveedor = $idProveedor;
    $editorialPDO->consignacion = $consignacion;
    $editorialPDO->update();
}
?>
