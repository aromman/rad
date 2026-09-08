<?php
require_once dirname(__DIR__, 2) . '/app/models/entityDeleter.php';

function eliminarEntidad($entityName, $id)
{
    $entityDeleterPDO = new EntityDeleter();
    return $entityDeleterPDO->eliminarPorEntidadYId($entityName, $id);
}
?>
