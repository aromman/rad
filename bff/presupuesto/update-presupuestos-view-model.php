<?php
require_once dirname(__DIR__, 2) . '/app/models/presupuesto.php';

function obtenerPresupuestoParaEditar($id)
{
    $presupuestoPDO = new Presupuesto();

    return $presupuestoPDO->getById($id);
}

function actualizarPresupuesto($id, $monto, $idClaseGasto, $idCanal)
{
    $presupuestoPDO = new Presupuesto();
    $presupuestoPDO->id = $id;
    $presupuestoPDO->monto = $monto;
    $presupuestoPDO->idClaseGasto = $idClaseGasto;
    $presupuestoPDO->idCanal = $idCanal;
    $presupuestoPDO->update();
}
?>
