<?php
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacion.php';
require_once dirname(__DIR__, 2) . '/app/models/productoStock.php';

function obtenerProductoStockFormularioViewModel()
{
    $editorialPDO = new Editorial();
    $productoUbicacionPDO = new ProductoUbicacion();

    return array(
        'editoriales' => (array) $editorialPDO->getAll('nombre'),
        'ubicaciones' => (array) $productoUbicacionPDO->getAllConLabelParaSelector(),
    );
}

function agregarProductoStockRapido($idProducto, $editoriales, $cantidades, $ubicaciones, $fechas, $costos, $esNuevos)
{
    foreach ($editoriales as $key => $idEditorial) {
        $productoStockPDO = new ProductoStock();
        $productoStockPDO->idProducto = $idProducto;
        $productoStockPDO->idEditorial = $idEditorial;
        $productoStockPDO->cantidad = $cantidades[$key];
        $productoStockPDO->idUbicacion = $ubicaciones[$key];
        $productoStockPDO->fecha = $fechas[$key];
        $productoStockPDO->costo = $costos[$key];
        $productoStockPDO->nuevo = $esNuevos[$key];
        $productoStockPDO->create();
    }
}
?>
