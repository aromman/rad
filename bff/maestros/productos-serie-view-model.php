<?php
require_once dirname(__DIR__, 2) . '/app/models/productoSerie.php';

function obtenerProductosSerieListadoViewModel()
{
    $productoSeriePDO = new ProductoSerie();
    $rows = $productoSeriePDO->getAll('nombre');
    $series = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $series[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'nombre' => isset($row['nombre']) ? $row['nombre'] : '',
            'cupoMaximo' => isset($row['cupo_maximo']) ? $row['cupo_maximo'] : 0,
            'cupoMinimo' => isset($row['cupo_minimo']) ? $row['cupo_minimo'] : 0,
        );
    }

    return array(
        'series' => $series,
    );
}
?>
