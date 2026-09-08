<?php
require_once dirname(__DIR__, 2) . '/app/models/productoFormato.php';

function agregarProductosFormatoRapido($nombres, $montos)
{
    foreach ($nombres as $key => $nombre) {
        $productoFormatoPDO = new ProductoFormato();
        $productoFormatoPDO->nombre = $nombre;
        $productoFormatoPDO->montoObjetivo = $montos[$key];
        $productoFormatoPDO->create();
    }
}
?>
