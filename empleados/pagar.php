<?php

require_once('../bff/empleados/pagar-view-model.php');

if(isset($_REQUEST['id']) and $_REQUEST['id']!="" ){

    $id_consignatario = $_REQUEST['id'];

    procesarPagoConsignatario($id_consignatario);

    header('location: consignaciones.php');
    exit;
}
?>