<?php
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';
require_once dirname(__DIR__, 2) . '/app/models/cuentas.php';
require_once dirname(__DIR__, 2) . '/app/models/canal.php';

function obtenerMedioPagoParaEditarViewModel($id)
{
    $medioPagoPDO = new MedioPago();
    $cuentasPDO = new Cuentas();
    $canalPDO = new Canal();

    return array(
        'medioPago' => $medioPagoPDO->getConCuentaById($id),
        'cuentas' => (array) $cuentasPDO->getAll('id'),
        'canales' => (array) $canalPDO->getAllActive('nombre ASC'),
    );
}

function actualizarMedioPago($id, $nombre, $porcentaje, $idCuenta, $idCanal)
{
    $medioPagoPDO = new MedioPago();
    $medioPagoPDO->id = $id;
    $medioPagoPDO->nombre = $nombre;
    $medioPagoPDO->cargoPorcentaje = $porcentaje;
    $medioPagoPDO->idCuenta = $idCuenta;
    $medioPagoPDO->idCanal = $idCanal;
    $medioPagoPDO->update();
}
?>
