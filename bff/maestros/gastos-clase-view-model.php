<?php
require_once dirname(__DIR__, 2) . '/app/models/gastosClase.php';

function obtenerGastosClaseListadoViewModel()
{
    $gastosClasePDO = new GastosClase();
    $rows = $gastosClasePDO->getAll('nombre');

    return array(
        'gastosClase' => is_array($rows) ? $rows : array(),
    );
}
?>
