<?php
require_once dirname(__DIR__, 2) . '/app/models/proveedores.php';
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';

function obtenerProveedoresSelectorViewModel()
{
    $proveedorPDO = new Proveedor();

    return array(
        'proveedores' => (array) $proveedorPDO->getAllSimpleOrderByNombre(),
    );
}

function agregarOrdenesRapido($fechas, $proveedores)
{
    foreach ($fechas as $key => $fecha) {
        $ordenCompraPDO = new OrdenCompra();
        $ordenCompraPDO->fecha = $fecha;
        $ordenCompraPDO->idProveedor = $proveedores[$key];
        $ordenCompraPDO->idEstadoPedido = 1;
        $ordenCompraPDO->create();
    }
}
?>
