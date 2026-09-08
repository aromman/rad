<?php
require_once dirname(__DIR__, 2) . '/app/models/canal.php';
require_once dirname(__DIR__, 2) . '/app/models/presupuesto.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/productoFormato.php';
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';
require_once dirname(__DIR__, 2) . '/app/models/productoSerie.php';

function obtenerPosDashboardCanales()
{
    $canalPDO = new Canal();
    $presupuestoPDO = new Presupuesto();
    $ventasPDO = new Ventas();

    $canales = $canalPDO->getAllActive("nombre ASC");
    $resultado = array();

    foreach (is_array($canales) ? $canales : array() as $val) {
        $canal = $val['id'];
        $totalCostosFijos = $presupuestoPDO->getTotalByCanal($canal);
        $totalVentas = $cantidadVentas = $totalUnidadesVendidas = $promedioVenta = $costoPromedioVentas = 0;
        $puntoEquilibrioUnidad = $puntoEquilibrioMonto = 0;
        $porcentajePEU = $porcentajePEM = 0;

        $ventaMes = $ventasPDO->getTotalMonthByCanal($canal);
        if (!is_null($ventaMes)) {
            $totalVentas = $ventaMes['total_ventas'];
            $cantidadVentas = $ventaMes['cantidad_ventas'];
            $totalUnidadesVendidas = $ventaMes['total_unidades'];
            $promedioVenta = $ventaMes['promedio'];
            $costoPromedioVentas = $ventaMes['costo_promedio'];

            $margenUnitario = $promedioVenta - $costoPromedioVentas;
            if ($margenUnitario != 0) {
                $puntoEquilibrioUnidad = $totalCostosFijos / $margenUnitario;
                $puntoEquilibrioMonto = $puntoEquilibrioUnidad * $promedioVenta;

                $porcentajePEU = ($puntoEquilibrioUnidad > $totalUnidadesVendidas) ? (($totalUnidadesVendidas * 100) / $puntoEquilibrioUnidad) : 100;
                $porcentajePEM = ($puntoEquilibrioMonto > $totalVentas) ? (($totalVentas * 100) / $puntoEquilibrioMonto) : 100;
            } else {
                $porcentajePEU = $porcentajePEM = 100;
            }
        }

        $resultado[] = array(
            'nombre' => $val['nombre'],
            'totalVentas' => $totalVentas,
            'cantidadVentas' => $cantidadVentas,
            'totalUnidadesVendidas' => $totalUnidadesVendidas,
            'promedioVenta' => $promedioVenta,
            'totalCostosFijos' => $totalCostosFijos,
            'costoPromedioVentas' => $costoPromedioVentas,
            'puntoEquilibrioUnidad' => $puntoEquilibrioUnidad,
            'porcentajePEU' => $porcentajePEU,
            'puntoEquilibrioMonto' => $puntoEquilibrioMonto,
            'porcentajePEM' => $porcentajePEM,
        );
    }

    return $resultado;
}

function obtenerPosDashboardFormatos()
{
    $productoFormatoPDO = new ProductoFormato();
    $productoPDO = new Producto();
    $ventasPDO = new Ventas();

    $formatos = $productoFormatoPDO->getAll('nombre');
    $resultado = array();

    foreach (is_array($formatos) ? $formatos : array() as $formato) {
        $stock = $productoPDO->getStockAvailableByFormato($formato['id']);
        $objetivoVentas = $formato['monto_objetivo'];
        $stockDisponible = $stock['stock'];
        $stockValorizado = $stock['valorizado'];

        $montoVentas = 0;
        $ventaMes = $ventasPDO->getTotalMonthByFormato($formato['id']);
        if (!is_null($ventaMes)) {
            $montoVentas = $ventaMes['total_costo'];
        }

        $porcentajeObjetivoVentas = ($objetivoVentas > $montoVentas) ? (($montoVentas * 100) / $objetivoVentas) : 100;

        $resultado[] = array(
            'nombre' => $formato['nombre'],
            'stockDisponible' => $stockDisponible,
            'stockValorizado' => $stockValorizado,
            'montoVentas' => $montoVentas,
            'objetivoVentas' => $objetivoVentas,
            'porcentajeObjetivoVentas' => $porcentajeObjetivoVentas,
        );
    }

    return $resultado;
}

function obtenerPosDashboardViewModel($precioReferencia, $diasMes)
{
    $gastosPDO = new Gastos();
    $ventasPDO = new Ventas();
    $editorialPDO = new Editorial();
    $productoSeriePDO = new ProductoSerie();

    $totalGastosRows = $gastosPDO->getTotalMesActualPromedioDiario($precioReferencia, $diasMes);
    $ventasNetoPorDia = $ventasPDO->getUnidadesNetoPorDiaMesActual($precioReferencia);
    $gastosPorDia = $gastosPDO->getUnidadesPorDiaMesActualCuentaTipoA($precioReferencia);
    $comprasVsVentas = $editorialPDO->getComprasVsVentasMesActual($precioReferencia);
    $cantidadTotalVentasRow = $ventasPDO->getCantidadTotalMesActual();
    $ventasPorSerie = $ventasPDO->getUnidadesPorSerieMesActual();
    $ocupacionCupos = $productoSeriePDO->getOcupacionCupos();

    return array(
        'canales' => obtenerPosDashboardCanales(),
        'formatos' => obtenerPosDashboardFormatos(),
        'totalGastosRows' => is_array($totalGastosRows) ? $totalGastosRows : array(),
        'ventasNetoPorDia' => is_array($ventasNetoPorDia) ? $ventasNetoPorDia : array(),
        'gastosPorDia' => is_array($gastosPorDia) ? $gastosPorDia : array(),
        'comprasVsVentas' => is_array($comprasVsVentas) ? $comprasVsVentas : array(),
        'cantidadTotalVentas' => (!empty($cantidadTotalVentasRow['unidades'])) ? $cantidadTotalVentasRow['unidades'] : 0,
        'ventasPorSerie' => is_array($ventasPorSerie) ? $ventasPorSerie : array(),
        'ocupacionCupos' => is_array($ocupacionCupos) ? $ocupacionCupos : array(),
    );
}
?>
