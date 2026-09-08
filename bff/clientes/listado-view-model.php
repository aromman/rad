<?php
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';

function obtenerClientesListadoViewModel()
{
    $clientePDO = new Cliente();
    $rows = $clientePDO->getAllConDescuento();
    $clientes = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $clientes[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'apellido' => isset($row['apellido']) ? $row['apellido'] : '',
            'nombre' => isset($row['nombre']) ? $row['nombre'] : '',
            'dni' => isset($row['dni']) ? $row['dni'] : '',
            'email' => isset($row['email']) ? $row['email'] : '',
            'descuento' => isset($row['descuento']) ? $row['descuento'] : '',
            'solo_contacto' => isset($row['solo_contacto']) ? $row['solo_contacto'] : 0,
        );
    }

    return array(
        'clientes' => $clientes,
        'totales' => array(
            'registros' => count($clientes),
        ),
    );
}
?>
