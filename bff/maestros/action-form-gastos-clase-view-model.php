<?php
require_once dirname(__DIR__, 2) . '/app/models/gastosClase.php';

function agregarGastosClaseRapido($nombres, $presupuestos)
{
    foreach ($nombres as $key => $nombre) {
        $gastosClasePDO = new GastosClase();
        $gastosClasePDO->nombre = $nombre;
        $gastosClasePDO->presupuesto = $presupuestos[$key];
        $gastosClasePDO->create();
    }
}
?>
