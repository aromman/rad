<?php

require_once('../bff/compras/action-addclone-view-model.php');

if (isset($_POST['id'])) {
    $row = obtenerProductoParaClonar($_POST['id']);
    $data['id'] = $row['id'];
    $data['sku'] = $row['sku'] . ' (CLONE)';
    $data['titulo'] = $row['titulo'] . ' (CLONE)';
    $data['tomo'] = $row['tomo'];

    echo json_encode($data);
}        
?>