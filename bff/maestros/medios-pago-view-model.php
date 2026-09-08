<?php
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';

function obtenerMediosPagoListadoViewModel()
{
    $medioPagoPDO = new MedioPago();
    $rows = $medioPagoPDO->getAllConCuentaYCanal();

    return array(
        'mediosPago' => is_array($rows) ? $rows : array(),
    );
}
?>
