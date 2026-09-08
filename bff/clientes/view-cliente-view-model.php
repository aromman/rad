<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerClienteDetalleViewModel($id)
{
    $clientePDO = new Cliente();
    $ventasPDO = new Ventas();

    return array(
        'cliente' => $clientePDO->getById($id),
        'ventas' => (array) $ventasPDO->getAllDetalleByCliente($id),
    );
}
?>
