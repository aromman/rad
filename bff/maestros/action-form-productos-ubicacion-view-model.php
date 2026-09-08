<?php
require_once dirname(__DIR__, 2) . '/app/models/canal.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacionTipo.php';
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacion.php';

function obtenerProductosUbicacionFormularioViewModel()
{
    $canalPDO = new Canal();
    $tipoPDO = new ProductoUbicacionTipo();

    return array(
        'canales' => (array) $canalPDO->getAllActive('nombre'),
        'tipos' => (array) $tipoPDO->getAll('nombre'),
    );
}

function agregarProductosUbicacionRapido($canales, $tiposNivel01, $codigosNivel01, $tiposNivel02, $codigosNivel02)
{
    foreach ($canales as $key => $canal) {
        $productoUbicacionPDO = new ProductoUbicacion();
        $productoUbicacionPDO->idCanal = $canal;
        $productoUbicacionPDO->idNivelTipo01 = $tiposNivel01[$key];
        $productoUbicacionPDO->nivelCodigo01 = $codigosNivel01[$key];
        $productoUbicacionPDO->idNivelTipo02 = $tiposNivel02[$key];
        $productoUbicacionPDO->nivelCodigo02 = $codigosNivel02[$key];
        $productoUbicacionPDO->create();
    }
}
?>
