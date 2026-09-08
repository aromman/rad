<?php
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacionTipo.php';

function obtenerProductosUbicacionTipoListadoViewModel()
{
    $productoUbicacionTipoPDO = new ProductoUbicacionTipo();

    return array(
        'tipos' => (array) $productoUbicacionTipoPDO->getAll('nombre'),
    );
}
?>
