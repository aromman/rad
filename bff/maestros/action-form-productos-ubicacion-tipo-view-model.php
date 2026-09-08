<?php
require_once dirname(__DIR__, 2) . '/app/models/productoUbicacionTipo.php';

function agregarProductosUbicacionTipoRapido($codigos, $nombres)
{
    foreach ($nombres as $key => $nombre) {
        $tipoPDO = new ProductoUbicacionTipo();
        $tipoPDO->codigo = $codigos[$key];
        $tipoPDO->nombre = $nombre;
        $tipoPDO->create();
    }
}
?>
