<?php
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerProductosSerieViewModel($idSerie)
{
    $productoPDO = new Producto();

    return array(
        'productos' => (array) $productoPDO->getAllActivosConEditorialBySerie($idSerie),
    );
}
?>
