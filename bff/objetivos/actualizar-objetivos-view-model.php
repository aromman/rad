<?php
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/objetivo.php';

function obtenerNombreTipoGasto($idGasto)
{
    if ($idGasto == 'F') {
        return 'Gastos Fijo';
    }
    if ($idGasto == 'V') {
        return 'Costo por Ventas';
    }
    if ($idGasto == 'C') {
        return 'Pago Comisiones';
    }
    if ($idGasto == 'D') {
        return 'Pago Deudas';
    }

    return 'Otros Gastos';
}

function obtenerActualizarObjetivosViewModel()
{
    $gastosPDO = new Gastos();
    $productoPDO = new Producto();
    $ventasPDO = new Ventas();
    $objetivoPDO = new Objetivo();

    // Detalle de gastos
    $totalGastosFijos = 0;
    $gastosDetalle = array();
    $gastosRows = $gastosPDO->getResumenPorTipoExcluyendoDeudas();
    foreach (is_array($gastosRows) ? $gastosRows : array() as $rowPagos) {
        $amountPago = $rowPagos['monto'];
        $tipoGasto = obtenerNombreTipoGasto($rowPagos['tipo']);
        $gastosDetalle[] = $tipoGasto . ': $ ' . number_format($amountPago, 2, '.', '');
        $totalGastosFijos = $totalGastosFijos + $amountPago;
    }
    $textoTotalGastos = 'Total : $ ' . number_format($totalGastosFijos, 2, '.', '');

    // Datos de referencia de ventas
    $precioVentaReferencia = 0;
    $costoVentaReferencia = 0;
    $precioRow = $productoPDO->getPrecioMasFrecuente();
    if (is_array($precioRow)) {
        $precioVentaReferencia = $precioRow['precio'];
        $costoVentaReferencia = $precioVentaReferencia * 0.7;
    }

    // calculos
    $totalUnidadesPC = 10 * 300; // 10 meses

    $totalUnidadesVendidas = 0;
    $cantidadRow = $ventasPDO->getCantidadTotalUnidadesHistorico();
    if (is_array($cantidadRow)) {
        $totalUnidadesVendidas = $cantidadRow['unidades'];
    }

    // actualizo anual
    $logrado = number_format($totalUnidadesVendidas, 0, '.', '');
    $objetivo = number_format($totalUnidadesPC, 0, '.', '');
    if ($totalUnidadesVendidas > $totalUnidadesPC) {
        $logrado = $objetivo;
    }
    $objetivoPDO->actualizarLogradoYObjetivoPorPrioridad(5, $logrado, $objetivo); // anual

    // semestral
    $logrado = number_format($logrado / 2, 0, '.', '');
    $objetivo = number_format($objetivo / 2, 0, '.', '');
    $objetivoPDO->actualizarLogradoYObjetivoPorPrioridad(4, $logrado, $objetivo); // semestral

    // mensual
    $logrado = number_format($logrado / 6, 0, '.', '');
    $objetivo = number_format($objetivo / 6, 0, '.', '');
    $objetivoPDO->actualizarLogradoYObjetivoPorPrioridad(3, $logrado, $objetivo); // mensual

    // semanal
    $logrado = number_format($logrado / 4, 0, '.', '');
    $objetivo = number_format($objetivo / 4, 0, '.', '');
    $objetivoPDO->actualizarLogradoYObjetivoPorPrioridad(2, $logrado, $objetivo); // semanal

    // diario
    $logrado = number_format($logrado / 7, 0, '.', '');
    $objetivo = number_format($objetivo / 7, 0, '.', '');
    $objetivoPDO->actualizarLogradoYObjetivoPorPrioridad(1, $logrado, $objetivo); // diario

    // detalle de objetivos actualizado
    $objetivosDetalle = array();
    $objetivosRows = $objetivoPDO->getAll('prioridad');
    foreach (is_array($objetivosRows) ? $objetivosRows : array() as $row) {
        $filaObjetivo = $row['objetivo'];
        $filaLogrado = $row['logrado'];
        if ($filaLogrado < $filaObjetivo) {
            $porcentaje = ($filaLogrado * 100) / $filaObjetivo;
        } else {
            $porcentaje = 100;
        }
        $objetivosDetalle[] = $row['nombre'] . ' : ' . $filaObjetivo . ' / ' . $filaLogrado . ' : ' . $porcentaje . ' %';
    }

    return array(
        'gastosDetalle' => $gastosDetalle,
        'textoTotalGastos' => $textoTotalGastos,
        'precioVentaReferencia' => $precioVentaReferencia,
        'costoVentaReferencia' => $costoVentaReferencia,
        'totalUnidadesPC' => $totalUnidadesPC,
        'objetivosDetalle' => $objetivosDetalle,
    );
}
?>
