<?php
require_once dirname(__DIR__, 2) . '/app/models/productoFormato.php';

function obtenerProductosFormatoListadoViewModel()
{
    $productoFormatoPDO = new ProductoFormato();
    $rows = $productoFormatoPDO->getAll('nombre');
    $formatos = array();

    foreach (is_array($rows) ? $rows : array() as $row) {
        $formatos[] = array(
            'id' => isset($row['id']) ? (int) $row['id'] : 0,
            'nombre' => isset($row['nombre']) ? $row['nombre'] : '',
            'montoObjetivo' => number_format(isset($row['monto_objetivo']) ? $row['monto_objetivo'] : 0, 2, '.', ''),
        );
    }

    return array(
        'formatos' => $formatos,
    );
}
?>
