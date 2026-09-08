<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';

function obtenerVentasInconsistentesViewModel()
{
    $ventasPDO = new Ventas();

    return array(
        'ventas' => (array) $ventasPDO->getAllInconsistentesSinDescuentoAplicado(),
    );
}
?>
