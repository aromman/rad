<?php
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';
require_once dirname(__DIR__, 2) . '/app/models/proveedores.php';
require_once dirname(__DIR__, 2) . '/app/models/estadoPedido.php';

function obtenerOrdenCompraParaEditarViewModel($id)
{
    $ordenCompraPDO = new OrdenCompra();
    $proveedorPDO = new Proveedor();
    $estadoPedidoPDO = new EstadoPedido();

    return array(
        'orden' => $ordenCompraPDO->getById($id),
        'proveedores' => (array) $proveedorPDO->getAllSimpleOrderByNombre(),
        'estados' => (array) $estadoPedidoPDO->getAll('estado ASC'),
    );
}

function actualizarOrdenCompra($id, $fecha, $idProveedor, $cantidad, $monto, $fechaEntrega, $idEstado)
{
    $ordenCompraPDO = new OrdenCompra();
    $ordenCompraPDO->id = $id;
    $ordenCompraPDO->fecha = $fecha;
    $ordenCompraPDO->idProveedor = $idProveedor;
    $ordenCompraPDO->cantidad = $cantidad;
    $ordenCompraPDO->monto = $monto;
    $ordenCompraPDO->fechaEntrega = $fechaEntrega;
    $ordenCompraPDO->idEstadoPedido = $idEstado;
    $ordenCompraPDO->update();
}
?>
