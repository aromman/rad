<?php
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';
require_once dirname(__DIR__, 2) . '/app/models/gastos.php';
require_once dirname(__DIR__, 2) . '/app/models/cuentasMovimientos.php';

function obtenerMediosPagoSelectorViewModel()
{
    $medioPagoPDO = new MedioPago();

    return array(
        'mediosPago' => (array) $medioPagoPDO->getAll('nombre ASC'),
    );
}

function agregarCostosRapido($fechas, $detalles, $montos, $mediosPago)
{
    foreach ($detalles as $key => $detalle) {
        $fecha = $fechas[$key];
        $monto = $montos[$key];
        $idMedioPago = $mediosPago[$key];

        $gastosPDO = new Gastos();
        $gastosPDO->fecha = $fecha;
        $gastosPDO->detalle = $detalle;
        $gastosPDO->monto = $monto;
        $gastosPDO->tipo = 'V';
        $gastosPDO->idMedioPago = $idMedioPago;
        $gastosPDO->create();

        $medioPagoPDO = new MedioPago();
        $medioPagoRow = $medioPagoPDO->getById($idMedioPago);
        $idCuenta = $medioPagoRow['id_cuenta'];
        $idCausal = 4; // pagos

        $movimiento = new CuentasMovimientos();
        $movimiento->setIdCuenta($idCuenta);
        $movimiento->setFecha($fecha);
        $movimiento->setTipoMovimiento('D');
        $movimiento->setDescripcion($detalle);
        $movimiento->setMonto($monto);
        $movimiento->setConsolidado(false);
        $movimiento->setIdCausal($idCausal);
        $movimiento->create();
    }
}
?>
