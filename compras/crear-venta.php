<?php

session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once '../bff/compras/crear-venta-view-model.php';

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    // Get URL parameter
    $id_orden_compra = trim($_GET["id"]);

    // limpio la session
    unset($_SESSION["cart_item"]);
    unset($_SESSION["cart_header"]);

    $productosOrden = obtenerDetalleOrdenParaCarrito($id_orden_compra);

    if (count($productosOrden) > 0) {

        foreach ($productosOrden as $val) {

            error_log(PHP_EOL."Agregando : " . $val["titulo"], 3, "my-errors.log");

            $itemArray = array($val["id"]=>array('id'=>$val["id"], 'titulo'=>$val["titulo"], 'sku'=>$val["sku"], 'cantidad'=>$val["cantidad"], 'precio'=>$val["precio_lista"],'costo'=>$val["precio_costo"] ));
            if(!empty($_SESSION["cart_item"])) {
                error_log(PHP_EOL."buscar producto ".$val["titulo"]." -> agregar", 3, "my-errors.log");
                $found = FALSE;
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($val["id"] == $v["id"]) {
                        error_log(PHP_EOL."Encontrado producto ".$val["titulo"]." -> sumar cantidad", 3, "my-errors.log");
                        $found = TRUE;
                        if(empty($_SESSION["cart_item"][$k]["cantidad"])) {
                            $_SESSION["cart_item"][$k]["cantidad"] = 0;
                        }
                        $_SESSION["cart_item"][$k]["cantidad"] += 1;
                    }
                }
                if (!$found){
                    error_log(PHP_EOL."No Encontrado producto ".$val["titulo"]." -> Agregar", 3, "my-errors.log");
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                }
            } else {
                error_log(PHP_EOL."No existe coleccion ".$val["titulo"]." -> agregar", 3, "my-errors.log");
                $_SESSION["cart_item"] = $itemArray;
            }


        }
    }

    header("location: ../pos/pos.php");
    exit();

}  else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: compras.php");
    exit();
}

?>

