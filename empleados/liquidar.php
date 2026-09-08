<?php

require_once('../bff/empleados/liquidar-view-model.php');

if(isset($_REQUEST['id']) and $_REQUEST['id']!=""
  and isset($_REQUEST['fecha']) and $_REQUEST['fecha']!=""
){

    $fecha = $_REQUEST['fecha'];
    $id_editorial = $_REQUEST['id'];

    procesarLiquidacionConsignacion($id_editorial, $fecha);

    header('location:'.$_REQUEST['forwardOk']);
    exit;
}
?>