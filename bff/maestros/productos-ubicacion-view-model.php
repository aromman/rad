<?php
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacion.php';

function obtenerProductosUbicacionListadoViewModel()
{
    $productoUbicacionPDO = new ProductoUbicacion();

    return array(
        'ubicaciones' => (array) $productoUbicacionPDO->getAllConCanalYNiveles(),
    );
}
?>
