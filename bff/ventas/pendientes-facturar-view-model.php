<?php
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';

function obtenerVentasPendientesFacturarViewModel()
{
    $ventasHeaderPDO = new VentasHeader();
    $rows = $ventasHeaderPDO->getAllPendientesFacturar();
    $ventas = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $fechaTimestamp = !empty($row['fecha']) ? strtotime($row['fecha']) : false;
        $updateTimestamp = !empty($row['updateDate']) ? strtotime($row['updateDate']) : false;

        $ventas[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'fechaId' => $fechaTimestamp !== false ? date('Ymd', $fechaTimestamp) : '',
            'fecha' => $fechaTimestamp !== false ? date('d/m/Y', $fechaTimestamp) : '',
            'fechaFactura' => $fechaTimestamp !== false ? date('Ymd', $fechaTimestamp) : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'unidades' => number_format(isset($row['unidades']) ? $row['unidades'] : 0, 0, '.', ''),
            'subTotal' => number_format(isset($row['subTotal']) ? $row['subTotal'] : 0, 2, '.', ''),
            'descuento' => number_format(isset($row['descuento']) ? $row['descuento'] : 0, 2, '.', ''),
            'total' => number_format(isset($row['total']) ? $row['total'] : 0, 2, '.', ''),
            'medioPago' => isset($row['medioPago']) ? $row['medioPago'] : '',
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'puntoVenta' => isset($row['punto_venta']) ? $row['punto_venta'] : '',
            'username' => isset($row['username']) ? $row['username'] : '',
            'updateDate' => $updateTimestamp !== false ? date('d/m/Y H:i:s', $updateTimestamp) : '',
            'comprobante' => isset($row['comprobante']) ? $row['comprobante'] : '',
            'cae' => isset($row['cae']) ? $row['cae'] : '',
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
