<?php
require_once dirname(__DIR__, 2) . '/app/models/salario.php';

function obtenerSalarioParaEditar($id)
{
    $salarioPDO = new Salario();

    return $salarioPDO->getById($id);
}

function actualizarSalario($id, $fecha, $monto)
{
    $salarioPDO = new Salario();
    $salarioPDO->id = $id;
    $salarioPDO->fecha = $fecha;
    $salarioPDO->monto = $monto;
    $salarioPDO->update();
}
?>
