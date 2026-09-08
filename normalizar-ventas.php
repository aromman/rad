<?php
require_once "bff/ventas/normalizar-ventas-view-model.php";

$fecha = $_POST["fecha"];
$cliente = $_POST["cliente"];
$medioPago = $_POST["medioPago"];
$cantidad = $_POST["cantidad"];
$subTotal = $_POST["subTotal"];
$descuento = $_POST["descuento"];
$total = $_POST["total"];
$id_canal = $_SESSION["user.canal"];

$id_header = registrarVentaHeaderNormalizada($id_canal, $fecha, $cliente, $medioPago, $cantidad, $subTotal, $descuento, $total);

// vuelvo a liquidaciones
header('location: ' . $_SERVER['HTTP_REFERER']);
exit;

?>