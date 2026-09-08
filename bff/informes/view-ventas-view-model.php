<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerVentasDetalleDiaViewModel($fecha, $idCanal)
{
    $ventasPDO = new Ventas();

    return array(
        'ventas' => (array) $ventasPDO->getAllDetalleByFechaYCanal($fecha, $idCanal),
        'mediosPago' => (array) $ventasPDO->getResumenPorMedioPagoFechaYCanal($fecha, $idCanal),
        'series' => (array) $ventasPDO->getResumenPorSerieFechaYCanal($fecha, $idCanal),
        'topVentas' => (array) $ventasPDO->getTopVentasPorProductoFechaYCanal($fecha, $idCanal),
    );
}
?>
