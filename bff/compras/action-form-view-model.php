<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerComprasFormularioViewModel()
{
    $productoPDO = new Producto();
    $ordenCompraPDO = new OrdenCompra();

    return array(
        'productos' => (array) $productoPDO->getAllSimpleOrderByTitulo(),
        'ordenes' => (array) $ordenCompraPDO->getAllConProveedorParaSelector(),
    );
}

function agregarComprasRapido($fechas, $productos, $cantidades, $preciosLista, $preciosCompra, $ordenes)
{
    foreach ($fechas as $key => $fecha) {
        $compraPDO = new Compra();
        $compraPDO->fecha = $fecha;
        $compraPDO->idProducto = $productos[$key];
        $compraPDO->cantidad = $cantidades[$key];
        $compraPDO->precioLista = $preciosLista[$key];
        $compraPDO->precioCosto = $preciosCompra[$key];
        if (!empty($ordenes[$key])) {
            $compraPDO->idOrdenCompra = $ordenes[$key];
        }
        $compraPDO->create();
    }
}
?>
