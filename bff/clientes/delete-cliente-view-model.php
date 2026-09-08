<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';

function eliminarClienteConReasignacion($id)
{
    $clientePDO = new Cliente();
    $clientePDO->eliminarConReasignacion($id);
}
?>
