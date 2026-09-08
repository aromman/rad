<?php
require_once dirname(__DIR__, 2) . '/app/models/canal.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacionTipo.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacion.php';

function obtenerProductoUbicacionParaEditarViewModel($id)
{
    $canalPDO = new Canal();
    $tipoPDO = new ProductoUbicacionTipo();
    $productoUbicacionPDO = new ProductoUbicacion();

    return array(
        'productoUbicacion' => $productoUbicacionPDO->getById($id),
        'canales' => (array) $canalPDO->getAllActive('nombre ASC'),
        'tipos' => (array) $tipoPDO->getAll('nombre'),
    );
}

function actualizarProductoUbicacion($id, $canal, $tipoNivel01, $codigoNivel01, $tipoNivel02, $codigoNivel02)
{
    $productoUbicacionPDO = new ProductoUbicacion();
    $productoUbicacionPDO->id = $id;
    $productoUbicacionPDO->idCanal = $canal;
    $productoUbicacionPDO->idNivelTipo01 = $tipoNivel01;
    $productoUbicacionPDO->nivelCodigo01 = $codigoNivel01;
    $productoUbicacionPDO->idNivelTipo02 = $tipoNivel02;
    $productoUbicacionPDO->nivelCodigo02 = $codigoNivel02;
    $productoUbicacionPDO->update();
}
?>
