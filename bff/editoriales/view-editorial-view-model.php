<?php
require_once dirname(__DIR__, 2) . '/app/models/editoriales.php';
require_once dirname(__DIR__, 2) . '/app/models/compras.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';
require_once dirname(__DIR__, 2) . '/app/models/cuentasMovimientos.php';

function procesarSaikoOfertaEditorial($idProducto, $idEditorialNueva, $idMedioPago, $precio, $cantidad)
{
    $monto = $precio * $cantidad;

    $productoUpdate = new Producto();
    $productoUpdate->setId($idProducto);
    $productoUpdate->setIdEditorial($idEditorialNueva);
    $productoUpdate->update();

    $medioPagoPDO = new MedioPago();
    $medioPago = $medioPagoPDO->getById($idMedioPago);
    $idCuenta = is_array($medioPago) ? $medioPago['id_cuenta'] : null;

    $movimiento = new CuentasMovimientos();
    $movimiento->setIdCuenta($idCuenta);
    $movimiento->setFecha(date('Y-m-d'));
    $movimiento->setTipoMovimiento('D');
    $movimiento->setDescripcion('Inversion libro ');
    $movimiento->setMonto($monto);
    $movimiento->setConsolidado(false);
    $movimiento->setIdCausal(2); // COMPRAS
    $movimiento->create();
}

function obtenerEditorialDetalleViewModel($idEditorial)
{
    $editorialPDO = new Editorial();
    $compraPDO = new Compra();
    $ventasPDO = new Ventas();
    $productoPDO = new Producto();

    $editorial = $editorialPDO->getById($idEditorial);

    $ultimaCompraRow = $compraPDO->getUltimaFechaByEditorial($idEditorial);
    $ultimaCompra = is_array($ultimaCompraRow) ? (string) $ultimaCompraRow['fecha'] : '';

    $disponible = $ventasPDO->getResumenDisponibleByEditorial($idEditorial, $ultimaCompra);

    return array(
        'editorial' => is_array($editorial) ? $editorial : null,
        'disponible' => is_array($disponible) ? $disponible : null,
        'productos' => (array) $productoPDO->getAllActivosConDetalleByEditorial($idEditorial),
        'ventas' => (array) $ventasPDO->getAllDetalleByEditorial($idEditorial),
        'compras' => (array) $compraPDO->getAllDetalleByEditorial($idEditorial),
    );
}
?>
