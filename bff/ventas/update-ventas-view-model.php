<?php
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';
require_once dirname(__DIR__, 2) . '/app/models/cuentasMovimientos.php';
require_once dirname(__DIR__, 2) . '/app/models/canal.php';
require_once dirname(__DIR__, 2) . '/app/models/descuento.php';
require_once dirname(__DIR__, 2) . '/app/models/medioPago.php';
require_once dirname(__DIR__, 2) . '/app/models/clientes.php';
require_once dirname(__DIR__, 2) . '/app/models/equipo.php';

function obtenerVentaParaEditar($id)
{
    $ventasPDO = new Ventas();

    return $ventasPDO->getById($id);
}

function obtenerVentaFormularioSelectoresViewModel()
{
    $canalPDO = new Canal();
    $descuentoPDO = new Descuento();
    $medioPagoPDO = new MedioPago();
    $clientePDO = new Cliente();
    $equipoPDO = new Equipo();

    return array(
        'canales' => (array) $canalPDO->getAll('nombre ASC'),
        'descuentos' => (array) $descuentoPDO->getAll('nombre ASC'),
        'mediosPago' => (array) $medioPagoPDO->getAll('nombre ASC'),
        'clientes' => (array) $clientePDO->getAll('apellido, nombre ASC'),
        'equipos' => (array) $equipoPDO->getAll('equipo ASC'),
    );
}

function actualizarVenta($id, $fecha, $idCanal, $idProducto, $unidades, $precioUnitario, $descuento, $motivoDescuento, $idDescuento, $total, $idMedioPago, $idCliente, $idEquipo, $idVentasHeader)
{
    $ventasPDO = new Ventas();
    $ventasPDO->id = $id;
    $ventasPDO->fecha = $fecha;
    $ventasPDO->idCanal = $idCanal;
    $ventasPDO->idProducto = $idProducto;
    $ventasPDO->unidades = $unidades;
    $ventasPDO->precioUnitario = $precioUnitario;
    $ventasPDO->descuento = !empty($descuento) ? $descuento : null;
    $ventasPDO->motivoDescuento = !empty($motivoDescuento) ? $motivoDescuento : null;
    $ventasPDO->idDescuento = $idDescuento;
    $ventasPDO->total = $total;
    $ventasPDO->idMedioPago = $idMedioPago;
    $ventasPDO->idCliente = $idCliente;
    $ventasPDO->idEquipo = $idEquipo;
    $ventasPDO->update();

    if (!empty($idVentasHeader)) {
        $ventasHeaderPDO = new VentasHeader();
        $totales = $ventasPDO->getTotalesByHeader($idVentasHeader);

        $cantidad = $totales['cantidad'];
        $subTotal = $totales['subtotal'];
        $descuentoHeader = $totales['descuento'];
        $totalHeader = $subTotal - $descuentoHeader;

        $ventasHeaderPDO->actualizarDatosYTotales($idVentasHeader, $fecha, $idCliente, $cantidad, $subTotal, $descuentoHeader, $totalHeader);

        $movimientoPDO = new CuentasMovimientos();
        $movimientoPDO->actualizarMontoPorDescripcion('Venta-' . $idVentasHeader, $totalHeader);
    }
}
?>
