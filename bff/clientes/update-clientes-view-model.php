<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';
require_once dirname(__DIR__, 2) . '/app/models/descuento.php';

function obtenerClienteParaEditarViewModel($id)
{
    $clientePDO = new Cliente();
    $descuentoPDO = new Descuento();

    return array(
        'cliente' => $clientePDO->getById($id),
        'descuentos' => (array) $descuentoPDO->getAll('id'),
    );
}

function actualizarCliente($id, $apellido, $nombre, $dni, $email, $idDescuento, $soloContacto)
{
    $clientePDO = new Cliente();
    $clientePDO->id = $id;
    $clientePDO->apellido = $apellido;
    $clientePDO->nombre = $nombre;
    $clientePDO->dni = $dni;
    $clientePDO->email = $email;
    $clientePDO->idDescuento = $idDescuento;
    $clientePDO->soloContacto = $soloContacto;
    $clientePDO->update();
}
?>
