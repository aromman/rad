<?php 

/**
 * Params
 * entityName : Nombre de entidad a eliminar
 * delId : Identificador
 * forwardOk : Redireccion por exito
 * 
*/

require_once('bff/compras/solicitar-compras-view-model.php');


if(isset($_REQUEST['id']) and $_REQUEST['id']!=""
  and isset($_REQUEST['forwardOk']) and $_REQUEST['forwardOk']!=""
){
    // actualiza estado a entregado
    // queda pendiente generar venta
    marcarCompraSolicitada($_REQUEST['id']);

    header('location:'.$_REQUEST['forwardOk']);
    exit;
}
?>