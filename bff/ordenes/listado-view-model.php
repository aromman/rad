<?php
require_once dirname(__DIR__, 2) . '/app/models/ordenCompra.php';

function obtenerOrdenesListadoViewModel()
{
    $ordenCompraPDO = new OrdenCompra();
    $rows = $ordenCompraPDO->getAllConProveedorYEstado();
    $ordenes = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $ordenes[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'fecha' => !empty($row['fecha']) ? date('d/m/Y', strtotime($row['fecha'])) : '',
            'proveedor' => isset($row['proveedor']) ? $row['proveedor'] : '',
            'cantidad' => number_format(isset($row['cantidad']) ? $row['cantidad'] : 0, 0, '.', ''),
            'monto' => number_format(isset($row['monto']) ? $row['monto'] : 0, 2, '.', ''),
            'fechaEntrega' => !empty($row['fecha_entrega']) ? date('d/m/Y', strtotime($row['fecha_entrega'])) : '',
            'estado' => isset($row['estado']) ? $row['estado'] : '',
        );
    }

    return array(
        'ordenes' => $ordenes,
    );
}
?>
