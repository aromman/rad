<?php
require_once dirname(__DIR__, 2) . '/app/models/productoSerie.php';

function agregarProductosSerieRapido($nombres, $cuposMaximos, $cuposMinimos)
{
    foreach ($nombres as $key => $nombre) {
        $productoSeriePDO = new ProductoSerie();
        $productoSeriePDO->nombre = $nombre;
        $productoSeriePDO->cupoMaximo = $cuposMaximos[$key];
        $productoSeriePDO->cupoMinimo = $cuposMinimos[$key];
        $productoSeriePDO->create();
    }
}
?>
