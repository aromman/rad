<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerValoracionAbcViewModel()
{
    $productoPDO = new Producto();
    $ventasPDO = new Ventas();

    $resumen = $productoPDO->getResumenValoracionAbcNoConsignacion();

    return array(
        'totalInventario' => is_array($resumen) && !empty($resumen['total']) ? $resumen['total'] : 0,
        'totalPrecio' => is_array($resumen) && !empty($resumen['precio']) ? $resumen['precio'] : 0,
        'porInversion' => (array) $productoPDO->getAllValoracionPorInversionNoConsignacion(),
        'porPrecio' => (array) $productoPDO->getAllValoracionPorPrecioNoConsignacion(),
        'demandaAnual' => (array) $ventasPDO->getDemandaAnualNoConsignacion(),
    );
}
?>
