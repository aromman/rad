<?php 

/**
 * Params
 * entityName : Nombre de entidad a eliminar
 * delId : Identificador
 * forwardOk : Redireccion por exito
 * 
*/

session_start();
require_once('bff/shared/delete-entity-view-model.php');

if(isset($_REQUEST['entityName']) and $_REQUEST['entityName']!=""
  and isset($_REQUEST['delId']) and $_REQUEST['delId']!=""
  and isset($_REQUEST['forwardOk']) and $_REQUEST['forwardOk']!=""
){
    eliminarEntidad($_REQUEST['entityName'], $_REQUEST['delId']);

    unset($_SESSION["productosRows"]);

    header('location:'.$_REQUEST['forwardOk']);

    exit;
}
?>