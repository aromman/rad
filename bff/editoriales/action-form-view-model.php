<?php
require_once dirname(__DIR__, 2) . '/app/models/proveedores.php';
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';

function obtenerProveedoresSelectorParaEditorialesViewModel()
{
    $proveedorPDO = new Proveedor();

    return array(
        'proveedores' => (array) $proveedorPDO->getAllSimpleOrderByNombre(),
    );
}

function agregarEditorialesRapido($nombres, $porcentajes, $montosFijos, $proveedores, $consignaciones)
{
    foreach ($nombres as $key => $nombre) {
        $editorialPDO = new Editorial();
        $editorialPDO->nombre = $nombre;
        $editorialPDO->porcentaje = isset($porcentajes[$key]) ? $porcentajes[$key] : null;
        $editorialPDO->montoFijo = isset($montosFijos[$key]) ? $montosFijos[$key] : null;
        $editorialPDO->idProveedor = isset($proveedores[$key]) ? $proveedores[$key] : null;
        $editorialPDO->consignacion = isset($consignaciones[$key]) ? $consignaciones[$key] : null;
        $editorialPDO->create();
    }
}
?>
