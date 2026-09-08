<?php
require_once dirname(__DIR__, 2) . '/app/models/gastosClase.php';

function obtenerGastosClaseParaEditar($id)
{
    $gastosClasePDO = new GastosClase();

    return $gastosClasePDO->getById($id);
}

function actualizarGastosClase($id, $nombre, $presupuesto)
{
    $gastosClasePDO = new GastosClase();
    $gastosClasePDO->id = $id;
    $gastosClasePDO->nombre = $nombre;
    $gastosClasePDO->presupuesto = $presupuesto;
    $gastosClasePDO->update();
}
?>
