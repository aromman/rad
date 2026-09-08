<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';
require_once dirname(__DIR__, 2) . '/app/models/descuento.php';

function obtenerClienteParaEditarSimpleViewModel($id)
{
    $clientePDO = new Cliente();
    $descuentoPDO = new Descuento();

    return array(
        'cliente' => $clientePDO->getById($id),
        'descuentos' => (array) $descuentoPDO->getAll('id'),
    );
}

function actualizarClienteSimple($id, $apellido, $nombre, $dni, $email, $idDescuento)
{
    $clientePDO = new Cliente();
    $clientePDO->id = $id;
    $clientePDO->apellido = $apellido;
    $clientePDO->nombre = $nombre;
    $clientePDO->dni = $dni;
    $clientePDO->email = $email;
    $clientePDO->idDescuento = $idDescuento;
    $clientePDO->update();
}
?>
