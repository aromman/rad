<?php
require_once dirname(__DIR__, 2) . '/app/models/canal.php';

function obtenerCanalParaEditar($id)
{
    $canalPDO = new Canal();

    return $canalPDO->getById($id);
}

function actualizarCanal($id, $tipo, $nombre, $fechaInicio, $fechaFin, $puntoVenta, $activo, $vende)
{
    $canalPDO = new Canal();
    $canalPDO->id = $id;
    $canalPDO->tipo = $tipo;
    $canalPDO->nombre = $nombre;
    if (!empty($fechaInicio)) {
        $canalPDO->fechaInicio = $fechaInicio;
    }
    if (!empty($fechaFin)) {
        $canalPDO->fechaFin = $fechaFin;
    }
    if (!empty($puntoVenta)) {
        $canalPDO->puntoVenta = $puntoVenta;
    }
    $canalPDO->activo = $activo;
    $canalPDO->vende = $vende;
    $canalPDO->update();
}
?>
