<?php
require_once dirname(__DIR__, 2) . '/app/models/cuentas.php';
require_once dirname(__DIR__, 2) . '/app/models/canal.php';
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';

function obtenerMediosPagoFormularioViewModel()
{
    $cuentasPDO = new Cuentas();
    $canalPDO = new Canal();

    return array(
        'cuentas' => (array) $cuentasPDO->getAll('id'),
        'canales' => (array) $canalPDO->getAllActive('nombre'),
    );
}

function agregarMediosPagoRapido($nombres, $porcentajes, $cuentas, $canales)
{
    foreach ($nombres as $key => $nombre) {
        $medioPagoPDO = new MedioPago();
        $medioPagoPDO->nombre = $nombre;
        $medioPagoPDO->cargoPorcentaje = isset($porcentajes[$key]) ? $porcentajes[$key] : null;
        $medioPagoPDO->idCuenta = $cuentas[$key];
        $medioPagoPDO->idCanal = $canales[$key];
        $medioPagoPDO->create();
    }
}
?>
