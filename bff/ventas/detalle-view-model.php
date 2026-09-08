<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerVentasDetalleViewModel()
{
    $ventaPDO = new Ventas();
    $rows = $ventaPDO->getAllDetalle();
    $ventas = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $fecha = '';
        if (!empty($row['fecha'])) {
            $timestamp = strtotime($row['fecha']);
            $fecha = $timestamp !== false ? date('d/m/Y', $timestamp) : $row['fecha'];
        }

        $ventas[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'idProducto' => isset($row['id_producto']) ? (int) $row['id_producto'] : 0,
            'fecha' => $fecha,
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'producto' => isset($row['producto']) ? $row['producto'] : '',
            'serie' => isset($row['serie']) ? $row['serie'] : '',
            'unidades' => number_format(isset($row['unidades']) ? $row['unidades'] : 0, 0, '.', ''),
            'precioUnitario' => number_format(isset($row['precioUnitario']) ? $row['precioUnitario'] : 0, 2, '.', ''),
            'descuento' => number_format(isset($row['descuento']) ? $row['descuento'] : 0, 2, '.', ''),
            'motivoDescuento' => isset($row['motivoDescuento']) ? $row['motivoDescuento'] : '',
            'total' => number_format(isset($row['total']) ? $row['total'] : 0, 2, '.', ''),
            'medioPago' => isset($row['medioPago']) ? $row['medioPago'] : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'equipo' => isset($row['equipo']) ? $row['equipo'] : '',
        );
    }

    return array(
        'ventas' => $ventas,
        'totales' => array(
            'registros' => count($ventas),
        ),
    );
}
?>
