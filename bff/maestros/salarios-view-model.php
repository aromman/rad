<?php
require_once dirname(__DIR__, 2) . '/app/models/salario.php';

function obtenerSalariosListadoViewModel()
{
    $salarioPDO = new Salario();
    $rows = $salarioPDO->getAll('fecha desc');

    return array(
        'salarios' => is_array($rows) ? $rows : array(),
    );
}
?>
