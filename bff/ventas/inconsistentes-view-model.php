<?php
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerVentasInconsistentesListadoViewModel()
{
    $ventasHeaderPDO = new VentasHeader();
    $ventasPDO = new Ventas();

    $headersSinDetalle = array();
    foreach ((array) $ventasHeaderPDO->getSinDetalle() as $row) {
        $headersSinDetalle[] = array(
            'id' => (int) $row['id'],
            'fecha' => $row['fecha'],
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'medioPago' => isset($row['medio_pago']) ? $row['medio_pago'] : '',
            'cantidad' => (int) $row['cantidad'],
            'subTotal' => number_format((float) $row['subTotal'], 2, '.', ''),
            'descuento' => number_format((float) $row['descuento'], 2, '.', ''),
            'total' => number_format((float) $row['total'], 2, '.', ''),
            'usuario' => isset($row['username']) ? $row['username'] : '',
            'actualizado' => isset($row['updateDate']) ? $row['updateDate'] : '',
        );
    }

    $ventasSinHeader = array();
    foreach ((array) $ventasPDO->getSinHeader() as $row) {
        $ventasSinHeader[] = array(
            'id' => (int) $row['id'],
            'idHeader' => $row['id_ventas_header'] !== null ? (int) $row['id_ventas_header'] : null,
            'fecha' => $row['fecha'],
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'producto' => isset($row['producto']) ? $row['producto'] : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'medioPago' => isset($row['medio_pago']) ? $row['medio_pago'] : '',
            'unidades' => (int) $row['unidades'],
            'precioUnitario' => number_format((float) $row['precio_unitario'], 2, '.', ''),
            'descuento' => number_format((float) $row['descuento'], 2, '.', ''),
            'total' => number_format((float) $row['total'], 2, '.', ''),
        );
    }

    return array(
        'headersSinDetalle' => $headersSinDetalle,
        'ventasSinHeader' => $ventasSinHeader,
    );
}
?>
