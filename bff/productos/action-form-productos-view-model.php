<?php
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerEditorialesSelectorViewModel()
{
    $editorialPDO = new Editorial();

    return array(
        'editoriales' => (array) $editorialPDO->getAll('id'),
    );
}

function agregarProductosRapido($skus, $titulos, $editoriales, $stocks, $precios)
{
    foreach ($skus as $key => $sku) {
        $productoPDO = new Producto();
        $productoPDO->sku = $sku;
        $productoPDO->titulo = $titulos[$key];
        $productoPDO->idEditorial = $editoriales[$key];
        $productoPDO->stock = $stocks[$key];
        $productoPDO->precio = $precios[$key];
        $productoPDO->create();
    }
}
?>
