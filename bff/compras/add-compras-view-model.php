<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerAddComprasFormularioViewModel()
{
    $compraPDO = new Compra();
    $productoPDO = new Producto();

    $fechaCompra = date('Y-m-d');
    $filas = $compraPDO->getUltimaFechaAgrupada();
    foreach (is_array($filas) ? $filas : array() as $fila) {
        $fechaCompra = $fila['fecha'];
    }

    return array(
        'fechaCompra' => $fechaCompra,
        'productos' => (array) $productoPDO->getAllSimpleOrderByTitulo(),
    );
}

function registrarCompraSimple($fecha, $idProducto, $cantidad, $precioLista, $precioCompra)
{
    $compraPDO = new Compra();
    $compraPDO->fecha = $fecha;
    $compraPDO->idProducto = $idProducto;
    $compraPDO->cantidad = $cantidad;
    $compraPDO->precioLista = $precioLista;
    $compraPDO->precioCosto = $precioCompra;
    $compraPDO->create();
}
?>
