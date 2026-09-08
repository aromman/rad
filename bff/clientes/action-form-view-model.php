<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';
require_once dirname(__DIR__, 2) . '/app/models/descuento.php';

function obtenerDescuentosSelectorViewModel()
{
    $descuentoPDO = new Descuento();

    return array(
        'descuentos' => (array) $descuentoPDO->getAll('id'),
    );
}

function agregarClientesRapido($apellidos, $nombres, $dnis, $emails, $descuentos)
{
    foreach ($apellidos as $key => $apellido) {
        $clientePDO = new Cliente();
        $clientePDO->apellido = $apellido;
        $clientePDO->nombre = $nombres[$key];
        $clientePDO->dni = isset($dnis[$key]) ? $dnis[$key] : null;
        $clientePDO->email = isset($emails[$key]) ? $emails[$key] : null;
        $clientePDO->idDescuento = isset($descuentos[$key]) ? $descuentos[$key] : null;
        $clientePDO->create();
    }
}
?>
