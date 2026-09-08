<?php
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once('../bff/stock/init-validate-productos-view-model.php');

$id_canal = $_SESSION["user.canal"];

inicializarValidacionProductos($id_canal);

header('location: existencias.php');
exit();
