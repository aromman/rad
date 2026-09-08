<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';
require_once dirname(__DIR__, 2) . '/app/models/estadoPedido.php';

function obtenerCompraParaEditarViewModel($id)
{
    $compraPDO = new Compra();
    $productoPDO = new Producto();
    $ordenCompraPDO = new OrdenCompra();
    $estadoPedidoPDO = new EstadoPedido();

    return array(
        'compra' => $compraPDO->getById($id),
        'productos' => (array) $productoPDO->getAllSimpleOrderByTitulo(),
        'ordenes' => (array) $ordenCompraPDO->getAllConProveedorParaSelector(),
        'estados' => (array) $estadoPedidoPDO->getAll('estado ASC'),
    );
}

function actualizarCompra($id, $fecha, $idProducto, $cantidad, $precioLista, $precioCosto, $idOrdenCompra, $idEstado)
{
    $compraPDO = new Compra();
    $compraPDO->id = $id;
    $compraPDO->fecha = $fecha;
    $compraPDO->idProducto = $idProducto;
    $compraPDO->cantidad = $cantidad;
    $compraPDO->precioLista = $precioLista;
    $compraPDO->precioCosto = $precioCosto;
    if (!empty($idOrdenCompra)) {
        $compraPDO->idOrdenCompra = $idOrdenCompra;
    }
    $compraPDO->idEstado = $idEstado;
    $compraPDO->update();

    if (!empty($idOrdenCompra)) {
        $ordenCompraPDO = new OrdenCompra();
        $ordenCompraPDO->recalcularCantidadYMontoDesdeCompras();
    }
}
?>
