<?php
require_once dirname(__DIR__, 2) . '/app/models/presupuesto.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerNegocioRentableViewModel()
{
    $presupuestoPDO = new Presupuesto();
    $rows = $presupuestoPDO->getAllConCanalYClaseGasto();
    $gastosFijos = array();
    $totalGastosFijos = 0;
    $mayorGastoFijo = null;

    foreach (is_array($rows) ? $rows : array() as $row) {
        $monto = isset($row['monto']) ? (float) $row['monto'] : 0;
        $totalGastosFijos += $monto;

        if ($mayorGastoFijo === null || $monto > $mayorGastoFijo['monto']) {
            $mayorGastoFijo = array(
                'nombre' => isset($row['nombre_clase_gasto']) ? $row['nombre_clase_gasto'] : '',
                'monto' => $monto,
            );
        }

        $gastosFijos[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'claseGasto' => isset($row['nombre_clase_gasto']) ? $row['nombre_clase_gasto'] : '',
            'monto' => '$ ' . number_format($monto, 2, '.', ''),
            'montoValor' => $monto,
        );
    }

    // Ventas Objetivo: el doble de los gastos fijos, contra las ventas del mes actual
    $ventasObjetivo = $totalGastosFijos * 2;

    $ventasPDO = new Ventas();
    $ventasMes = $ventasPDO->getTotalMonth();
    $ventasAlMomento = (is_array($ventasMes) && isset($ventasMes['total_ventas'])) ? (float) $ventasMes['total_ventas'] : 0;
    $cantidadVentas = (is_array($ventasMes) && isset($ventasMes['cantidad_ventas'])) ? (int) $ventasMes['cantidad_ventas'] : 0;

    $porcentajeLogro = $ventasObjetivo > 0 ? ($ventasAlMomento / $ventasObjetivo) * 100 : 0;

    if ($ventasObjetivo <= 0) {
        $semaforo = 'sin-objetivo';
    } elseif ($porcentajeLogro < 30) {
        $semaforo = 'rojo';
    } elseif ($porcentajeLogro < 100) {
        $semaforo = 'amarillo';
    } else {
        $semaforo = 'verde';
    }

    // Reposicion de Mercaderia: la mitad de las ventas (objetivo y al momento)
    $reposicionObjetivo = $ventasObjetivo / 2;
    $reposicionAlMomento = $ventasAlMomento / 2;

    // se compara a centavos para que "coincide" no dependa de decimales de punto flotante
    $reposicionObjetivoCentavos = (int) round($reposicionObjetivo * 100);
    $reposicionAlMomentoCentavos = (int) round($reposicionAlMomento * 100);

    if ($reposicionObjetivoCentavos <= 0) {
        $reposicionEstado = 'sin-objetivo';
    } elseif ($reposicionAlMomentoCentavos * 10 < $reposicionObjetivoCentavos * 9) {
        // mas de un 10% por debajo del objetivo
        $reposicionEstado = 'rojo';
    } elseif ($reposicionAlMomentoCentavos <= $reposicionObjetivoCentavos) {
        // hasta un 10% por debajo del objetivo, o igual
        $reposicionEstado = 'amarillo';
    } else {
        $reposicionEstado = 'verde';
    }

    // Inversiones: el excedente de reposicion sobre el objetivo se reparte en dos mitades
    // se reparte en centavos para que las dos mitades sumen exactamente el total
    $excedenteCentavos = max(0, $reposicionAlMomentoCentavos - $reposicionObjetivoCentavos);
    $compraNuevaMercaderiaCentavos = intdiv($excedenteCentavos, 2);
    $previsionCostosFijosCentavos = $excedenteCentavos - $compraNuevaMercaderiaCentavos;

    $excedenteReposicion = $excedenteCentavos / 100;
    $compraNuevaMercaderia = $compraNuevaMercaderiaCentavos / 100;
    $previsionCostosFijos = $previsionCostosFijosCentavos / 100;

    return array(
        'gastosFijos' => $gastosFijos,
        'totalGastosFijos' => '$ ' . number_format($totalGastosFijos, 2, '.', ''),
        'ventasObjetivo' => '$ ' . number_format($ventasObjetivo, 2, '.', ''),
        'ventasAlMomento' => '$ ' . number_format($ventasAlMomento, 2, '.', ''),
        // floor: un 99,6% no debe mostrarse como 100% mientras el semaforo sigue en amarillo
        'porcentajeLogro' => (int) floor($porcentajeLogro),
        'semaforo' => $semaforo,
        'reposicionObjetivo' => '$ ' . number_format($reposicionObjetivo, 2, '.', ''),
        'reposicionAlMomento' => '$ ' . number_format($reposicionAlMomento, 2, '.', ''),
        'reposicionEstado' => $reposicionEstado,
        'hayExcedenteReposicion' => $excedenteReposicion > 0,
        'compraNuevaMercaderia' => '$ ' . number_format($compraNuevaMercaderia, 2, '.', ''),
        'previsionCostosFijos' => '$ ' . number_format($previsionCostosFijos, 2, '.', ''),
        'totalInversiones' => '$ ' . number_format($excedenteReposicion, 2, '.', ''),
        'consejos' => obtenerConsejosNegocioRentable(
            $totalGastosFijos,
            $mayorGastoFijo,
            $ventasObjetivo,
            $ventasAlMomento,
            $cantidadVentas,
            $excedenteReposicion,
            $compraNuevaMercaderia,
            $previsionCostosFijos,
            new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'))
        ),
    );
}

/**
 * Actualiza el monto presupuestado de un gasto fijo.
 * Devuelve un mensaje de error, o null si se guardo bien.
 */
function actualizarMontoGastoFijo($id, $monto)
{
    $monto = str_replace(',', '.', trim((string) $monto));

    if (!ctype_digit((string) $id) || (int) $id <= 0) {
        return 'Gasto fijo invalido.';
    }
    if ($monto === '' || !is_numeric($monto) || (float) $monto < 0) {
        return 'El monto debe ser un numero mayor o igual a 0.';
    }

    $presupuestoPDO = new Presupuesto();
    if (!$presupuestoPDO->updateMonto((int) $id, (float) $monto)) {
        return 'No se pudo actualizar el presupuesto.';
    }

    return null;
}

function formatoMonedaNegocioRentable($valor)
{
    return '$ ' . number_format($valor, 2, '.', '');
}

/**
 * Consejos para llegar a tener Inversiones (ventas por encima del objetivo).
 * Cada consejo: tipo (clase de alert de Bootstrap), icono, titulo y texto.
 */
function obtenerConsejosNegocioRentable($totalGastosFijos, $mayorGastoFijo, $ventasObjetivo, $ventasAlMomento, $cantidadVentas, $excedente, $compraNuevaMercaderia, $previsionCostosFijos, DateTime $hoy)
{
    $consejos = array();

    if ($ventasObjetivo <= 0) {
        $consejos[] = array(
            'tipo' => 'danger',
            'icono' => 'fa-file-invoice-dollar',
            'titulo' => 'Cargá los gastos fijos',
            'texto' => 'No hay gastos presupuestados, así que no se puede calcular el objetivo de ventas. Cargalos en Finanzas > Presupuesto > Presupuestos.',
        );
        return $consejos;
    }

    $diaActual = (int) $hoy->format('j');
    $diasMes = (int) $hoy->format('t');
    $diasRestantes = $diasMes - $diaActual + 1; // incluye hoy

    $promedioDiario = $ventasAlMomento / $diaActual;
    $proyeccionMes = $promedioDiario * $diasMes;
    $faltante = max(0, $ventasObjetivo - $ventasAlMomento);
    $ticketPromedio = $cantidadVentas > 0 ? $ventasAlMomento / $cantidadVentas : 0;

    if ($excedente > 0) {
        $consejos[] = array(
            'tipo' => 'success',
            'icono' => 'fa-piggy-bank',
            'titulo' => 'Ya hay inversiones este mes',
            'texto' => 'Las ventas superan el objetivo y hay ' . formatoMonedaNegocioRentable($excedente) . ' para invertir. '
                . 'Destiná ' . formatoMonedaNegocioRentable($compraNuevaMercaderia) . ' a mercadería de alta rotación (revisá Más Vendidos y Agotados) '
                . 'y separá ' . formatoMonedaNegocioRentable($previsionCostosFijos) . ' en una cuenta aparte como previsión para los costos fijos.',
        );
    } elseif ($ventasAlMomento <= 0) {
        $consejos[] = array(
            'tipo' => 'warning',
            'icono' => 'fa-cash-register',
            'titulo' => 'Todavía no hay ventas este mes',
            'texto' => 'Para llegar al objetivo hay que vender ' . formatoMonedaNegocioRentable($ventasObjetivo / $diasRestantes) . ' por día en los ' . $diasRestantes . ' días que quedan.',
        );
    } else {
        $ventaDiariaNecesaria = $faltante / $diasRestantes;
        $porcentajeProyectado = (int) floor(($proyeccionMes / $ventasObjetivo) * 100);

        if ($proyeccionMes > $ventasObjetivo) {
            $consejos[] = array(
                'tipo' => 'info',
                'icono' => 'fa-chart-line',
                'titulo' => 'Al ritmo actual se llega al objetivo',
                'texto' => 'Con un promedio de ' . formatoMonedaNegocioRentable($promedioDiario) . ' por día, el mes cerraría en ' . formatoMonedaNegocioRentable($proyeccionMes)
                    . ', con unos ' . formatoMonedaNegocioRentable(($proyeccionMes - $ventasObjetivo) / 2) . ' para inversiones. '
                    . 'Faltan ' . formatoMonedaNegocioRentable($faltante) . ' para empezar a generarlas: sostené el ritmo.',
            );
        } else {
            $aumentoNecesario = (int) ceil((($ventaDiariaNecesaria / $promedioDiario) - 1) * 100);
            $consejos[] = array(
                'tipo' => 'warning',
                'icono' => 'fa-chart-line',
                'titulo' => 'Al ritmo actual no se llega al objetivo',
                'texto' => 'Con un promedio de ' . formatoMonedaNegocioRentable($promedioDiario) . ' por día, el mes cerraría en ' . formatoMonedaNegocioRentable($proyeccionMes)
                    . ' (' . $porcentajeProyectado . '% del objetivo). Faltan ' . formatoMonedaNegocioRentable($faltante)
                    . ': hay que vender ' . formatoMonedaNegocioRentable($ventaDiariaNecesaria) . ' por día en los ' . $diasRestantes . ' días que quedan, un ' . $aumentoNecesario . '% más que el promedio actual.',
            );
        }

        if ($ticketPromedio > 0) {
            $ventasFaltantes = (int) ceil($faltante / $ticketPromedio);
            $consejos[] = array(
                'tipo' => 'info',
                'icono' => 'fa-receipt',
                'titulo' => 'Ticket promedio',
                'texto' => 'El ticket promedio es de ' . formatoMonedaNegocioRentable($ticketPromedio) . ' (' . $cantidadVentas . ' ventas en el mes). '
                    . 'Con ese ticket faltan unas ' . $ventasFaltantes . ' ventas, cerca de ' . (int) ceil($ventasFaltantes / $diasRestantes) . ' por día. '
                    . 'Subir el ticket (combos, sugerir un segundo producto, ofertas por cantidad) reduce las ventas necesarias.',
            );
        }

        if ($proyeccionMes <= $ventasObjetivo && $mayorGastoFijo !== null && $mayorGastoFijo['monto'] > 0) {
            $reduccionNecesaria = ($ventasObjetivo - $proyeccionMes) / 2;
            $participacion = (int) round(($mayorGastoFijo['monto'] / $totalGastosFijos) * 100);
            $consejos[] = array(
                'tipo' => 'info',
                'icono' => 'fa-scissors',
                'titulo' => 'Revisá los gastos fijos',
                'texto' => 'Cada $ 1 menos de gastos fijos baja el objetivo de ventas en $ 2. '
                    . 'Para que el ritmo actual alcance, los gastos fijos tendrían que bajar ' . formatoMonedaNegocioRentable($reduccionNecesaria)
                    . ' (de ' . formatoMonedaNegocioRentable($totalGastosFijos) . ' a ' . formatoMonedaNegocioRentable(max(0, $totalGastosFijos - $reduccionNecesaria)) . '). '
                    . 'El más pesado es "' . $mayorGastoFijo['nombre'] . '" (' . formatoMonedaNegocioRentable($mayorGastoFijo['monto']) . ', ' . $participacion . '% del total): '
                    . 'bajarlo un 10% reduce el objetivo en ' . formatoMonedaNegocioRentable($mayorGastoFijo['monto'] * 0.1 * 2) . '.',
            );
        }
    }

    if ($diaActual <= 5) {
        $consejos[] = array(
            'tipo' => 'secondary',
            'icono' => 'fa-circle-info',
            'titulo' => 'Proyección temprana',
            'texto' => 'Van ' . $diaActual . ' días del mes: las proyecciones todavía pueden cambiar bastante.',
        );
    }

    return $consejos;
}
?>
