<?php
require_once dirname(__DIR__, 2) . '/app/models/ventasHeader.php';
require_once dirname(__DIR__, 2) . '/app/models/ventas.php';
require_once dirname(__DIR__, 2) . '/app/models/producto.php';

function obtenerVentasInconsistentesListadoViewModel()
{
    $ventasHeaderPDO = new VentasHeader();
    $ventasPDO = new Ventas();

    $headersSinDetalle = array();
    foreach ((array) $ventasHeaderPDO->getSinDetalle() as $row) {
        $headersSinDetalle[] = array(
            'id' => (int) $row['id'],
            'fecha' => $row['fecha'],
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'medioPago' => isset($row['medio_pago']) ? $row['medio_pago'] : '',
            'cantidad' => (int) $row['cantidad'],
            'subTotal' => number_format((float) $row['subTotal'], 2, '.', ''),
            'descuento' => number_format((float) $row['descuento'], 2, '.', ''),
            'total' => number_format((float) $row['total'], 2, '.', ''),
            'usuario' => isset($row['username']) ? $row['username'] : '',
            'actualizado' => isset($row['updateDate']) ? $row['updateDate'] : '',
        );
    }

    $ventasSinHeader = array();
    foreach ((array) $ventasPDO->getSinHeader() as $row) {
        $ventasSinHeader[] = array(
            'id' => (int) $row['id'],
            'idHeader' => $row['id_ventas_header'] !== null ? (int) $row['id_ventas_header'] : null,
            'fecha' => $row['fecha'],
            'canal' => isset($row['canal']) ? $row['canal'] : '',
            'producto' => isset($row['producto']) ? $row['producto'] : '',
            'cliente' => isset($row['cliente']) ? $row['cliente'] : '',
            'medioPago' => isset($row['medio_pago']) ? $row['medio_pago'] : '',
            'unidades' => (int) $row['unidades'],
            'precioUnitario' => number_format((float) $row['precio_unitario'], 2, '.', ''),
            'descuento' => number_format((float) $row['descuento'], 2, '.', ''),
            'total' => number_format((float) $row['total'], 2, '.', ''),
        );
    }

    return array(
        'headersSinDetalle' => $headersSinDetalle,
        'ventasSinHeader' => $ventasSinHeader,
    );
}

/**
 * Datos para asociar productos a una cabecera de venta sin detalle.
 * Devuelve null si la cabecera no existe o ya tiene detalle.
 */
function obtenerAsociarProductosViewModel($idHeader)
{
    $ventasHeaderPDO = new VentasHeader();
    $rows = (array) $ventasHeaderPDO->getSinDetalle((int) $idHeader);
    if (count($rows) === 0) {
        return null;
    }
    $header = $rows[0];

    $productoPDO = new Producto();
    $productos = array();
    foreach ((array) $productoPDO->getAllSimpleOrderByTitulo() as $row) {
        $productos[] = array(
            'id' => (int) $row['id'],
            'titulo' => $row['titulo'],
            'sku' => isset($row['sku']) ? $row['sku'] : '',
            'precio' => (float) $row['precio'],
        );
    }

    return array(
        'header' => array(
            'id' => (int) $header['id'],
            'fecha' => $header['fecha'],
            'canal' => isset($header['canal']) ? $header['canal'] : '',
            'cliente' => isset($header['cliente']) ? $header['cliente'] : '',
            'medioPago' => isset($header['medio_pago']) ? $header['medio_pago'] : '',
            'cantidad' => (int) $header['cantidad'],
            'subTotal' => (float) $header['subTotal'],
            'descuento' => (float) $header['descuento'],
            'total' => (float) $header['total'],
            'usuario' => isset($header['username']) ? $header['username'] : '',
        ),
        'productos' => $productos,
    );
}

/**
 * Graba el detalle (ventas) de una cabecera que quedo sin productos.
 * Usa Ventas::createMasivo, igual que el POS, con el porcentaje de descuento que tuvo la venta.
 * No toca stock ni movimientos de cuenta: eso ya se registro cuando se hizo la venta original.
 * Devuelve un mensaje de error, o null si se grabo bien.
 */
function asociarProductosAVentaHeader($idHeader, $idsProducto, $cantidades, $precios)
{
    if (!ctype_digit((string) $idHeader)) {
        return 'Venta invalida.';
    }

    $ventasHeaderPDO = new VentasHeader();
    $rows = (array) $ventasHeaderPDO->getSinDetalle((int) $idHeader);
    if (count($rows) === 0) {
        return 'La venta no existe o ya tiene productos asociados.';
    }
    $header = $rows[0];

    $productoPDO = new Producto();
    $productosPorId = array();
    foreach ((array) $productoPDO->getAllSimpleOrderByTitulo() as $row) {
        $productosPorId[(int) $row['id']] = $row;
    }

    $idsProducto = is_array($idsProducto) ? $idsProducto : array();
    $lista = array();
    foreach ($idsProducto as $i => $idProducto) {
        $cantidad = isset($cantidades[$i]) ? trim((string) $cantidades[$i]) : '';
        $precio = isset($precios[$i]) ? str_replace(',', '.', trim((string) $precios[$i])) : '';

        if (!ctype_digit((string) $idProducto) || !isset($productosPorId[(int) $idProducto])) {
            return 'Hay un renglon sin producto valido.';
        }
        if (!ctype_digit($cantidad) || (int) $cantidad <= 0) {
            return 'La cantidad debe ser un numero entero mayor a 0.';
        }
        if ($precio === '' || !is_numeric($precio) || (float) $precio < 0) {
            return 'El precio debe ser un numero mayor o igual a 0.';
        }

        $producto = $productosPorId[(int) $idProducto];
        $lista[] = array(
            'id' => (int) $idProducto,
            'cantidad' => (int) $cantidad,
            'precio' => (float) $precio,
            'costo' => (float) $producto['precio_costo'],
        );
    }

    if (count($lista) === 0) {
        return 'Agrega al menos un producto.';
    }

    $porcentaje = (float) $header['subTotal'] > 0 ? ((float) $header['descuento'] / (float) $header['subTotal']) * 100 : 0;

    $ventasPDO = new Ventas();
    $ventasPDO->createMasivo(
        (int) $header['id'],
        $header['fecha'],
        (int) $header['id_canal'],
        (int) $header['id_cliente'],
        (int) $header['id_medio_pago'],
        $porcentaje,
        $lista
    );

    // createMasivo no informa errores (los deja en el log): se verifica que el detalle haya quedado grabado
    if ($ventasPDO->countByHeader((int) $header['id']) === 0) {
        return 'No se pudieron grabar los productos. Revisa app/models/my-errors.log.';
    }

    return null;
}
?>
