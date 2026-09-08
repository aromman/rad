<?php
require_once dirname(__DIR__, 2) . '/app/models/compras.php';

function obtenerComprasPorEstadoViewModel($idEstado)
{
    $compraPDO = new Compra();
    $rows = $compraPDO->getAllByEstado($idEstado);
    $items = array();
    $sumCantidad = $sumPrecioLista = $sumPrecioCosto = 0;

    foreach (is_array($rows) ? $rows : array() as $row) {
        $cantidad = isset($row['cantidad']) ? $row['cantidad'] : 0;
        $precioLista = isset($row['precio_lista']) ? $row['precio_lista'] : 0;
        $precioCosto = isset($row['precio_costo']) ? $row['precio_costo'] : 0;
        $fechaTimestamp = !empty($row['fecha']) ? strtotime($row['fecha']) : false;

        $items[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'idProducto' => isset($row['id_producto']) ? (int) $row['id_producto'] : 0,
            'fechaId' => $fechaTimestamp !== false ? date('Ymd', $fechaTimestamp) : '',
            'fecha' => $fechaTimestamp !== false ? date('d/m/Y', $fechaTimestamp) : '',
            'producto' => isset($row['producto']) ? $row['producto'] : '',
            'cantidad' => number_format($cantidad, 0, '.', ''),
            'precioLista' => '$ ' . number_format($precioLista, 2, '.', ''),
            'precioCosto' => '$ ' . number_format($precioCosto, 2, '.', ''),
            'orden' => isset($row['id_orden_compra']) ? $row['id_orden_compra'] : '',
            'estado' => isset($row['estado']) ? $row['estado'] : '',
        );

        $sumCantidad = $sumCantidad + $cantidad;
        $sumPrecioLista = $sumPrecioLista + ($precioLista * $cantidad);
        $sumPrecioCosto = $sumPrecioCosto + ($precioCosto * $cantidad);
    }

    return array(
        'items' => $items,
        'totales' => array(
            'cantidad' => number_format($sumCantidad, 0, '.', ''),
            'precioLista' => '$ ' . number_format($sumPrecioLista, 2, '.', ''),
            'precioCosto' => '$ ' . number_format($sumPrecioCosto, 2, '.', ''),
        ),
    );
}

function obtenerComprasViewModel()
{
    return array(
        'pendientes' => obtenerComprasPorEstadoViewModel(1),
        'solicitadas' => obtenerComprasPorEstadoViewModel(2),
        'entregadas' => obtenerComprasPorEstadoViewModel(5),
    );
}
?>
