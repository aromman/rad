<?php 

/**
 * Params
 * entityName : Nombre de entidad a eliminar
 * delId : Identificador
 * forwardOk : Redireccion por exito
 * 
*/

require_once('../bff/clientes/delete-cliente-view-model.php');

if(isset($_REQUEST['delId']) and $_REQUEST['delId']!=""
  and isset($_REQUEST['forwardOk']) and $_REQUEST['forwardOk']!=""
){

    eliminarClienteConReasignacion($_REQUEST['delId']);

    header('location:'.$_REQUEST['forwardOk']);

    exit;
}
?>