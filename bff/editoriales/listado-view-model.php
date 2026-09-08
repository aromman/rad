<?php
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';

function obtenerEditorialesListadoViewModel()
{
    $editorialPDO = new Editorial();
    $rows = $editorialPDO->getAllConProveedor();

    return array(
        'editoriales' => is_array($rows) ? $rows : array(),
    );
}
?>
