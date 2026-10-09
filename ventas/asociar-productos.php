<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/ventas/inconsistentes-view-model.php";

$idHeader = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = asociarProductosAVentaHeader(
        $idHeader,
        isset($_POST['producto']) ? $_POST['producto'] : array(),
        isset($_POST['cantidad']) ? $_POST['cantidad'] : array(),
        isset($_POST['precio']) ? $_POST['precio'] : array()
    );
    if ($error === null) {
        $_SESSION['ventasInconsistentes.mensaje'] = 'Se asociaron los productos a la venta ' . (int) $idHeader . '.';
        header('location: ventas-inconsistentes.php');
        exit;
    }
    $_SESSION['asociarProductos.error'] = $error;
    header('location: asociar-productos.php?id=' . urlencode($idHeader));
    exit;
}

$mensajeError = isset($_SESSION['asociarProductos.error']) ? $_SESSION['asociarProductos.error'] : '';
unset($_SESSION['asociarProductos.error']);

$viewModel = ctype_digit($idHeader) ? obtenerAsociarProductosViewModel((int) $idHeader) : null;
if ($viewModel === null) {
    $_SESSION['ventasInconsistentes.mensaje'] = 'La venta no existe o ya tiene productos asociados.';
    header('location: ventas-inconsistentes.php');
    exit;
}
$header = $viewModel['header'];

$e = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Asociar Productos</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>

        <div id="layoutSidenav">

            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Asociar Productos <small class="text-muted fs-4">Venta <?php echo $header['id']; ?></small></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Ventas</li>
                            <li class="breadcrumb-item"><a href="ventas-inconsistentes.php">Ventas Inconsistentes</a></li>
                            <li class="breadcrumb-item active">Asociar Productos</li>
                        </ol>

                        <?php if ($mensajeError !== '') { ?>
                            <div class="alert alert-danger"><?php echo $e($mensajeError); ?></div>
                        <?php } ?>

                        <div class="card mb-4">
                            <div class="card-header fw-bold">
                                <i class="fas fa-receipt me-1"></i>
                                Datos de la venta
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-2"><div class="text-muted small">Fecha</div><?php echo date('d/m/Y', strtotime($header['fecha'])); ?></div>
                                    <div class="col-md-2"><div class="text-muted small">Canal</div><?php echo $e($header['canal']); ?></div>
                                    <div class="col-md-3"><div class="text-muted small">Cliente</div><?php echo $e($header['cliente']); ?></div>
                                    <div class="col-md-2"><div class="text-muted small">Medio de pago</div><?php echo $e($header['medioPago']); ?></div>
                                    <div class="col-md-3"><div class="text-muted small">Usuario</div><?php echo $e($header['usuario']); ?></div>
                                    <div class="col-md-2"><div class="text-muted small">Cantidad</div><span class="fw-bold"><?php echo $header['cantidad']; ?></span></div>
                                    <div class="col-md-2"><div class="text-muted small">SubTotal</div><span class="fw-bold">$ <?php echo number_format($header['subTotal'], 2, '.', ''); ?></span></div>
                                    <div class="col-md-2"><div class="text-muted small">Descuento</div>$ <?php echo number_format($header['descuento'], 2, '.', ''); ?></div>
                                    <div class="col-md-2"><div class="text-muted small">Total</div><span class="fw-bold">$ <?php echo number_format($header['total'], 2, '.', ''); ?></span></div>
                                </div>
                            </div>
                        </div>

                        <form method="post" id="formAsociar">
                            <input type="hidden" name="id" value="<?php echo $header['id']; ?>">
                            <div class="card mb-4">
                                <div class="card-header fw-bold">
                                    <i class="fas fa-boxes-stacked me-1"></i>
                                    Productos de la venta
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info small">
                                        El precio se completa con el precio actual del producto: si cambió desde la venta, corregilo.
                                        El descuento se aplica solo, con el mismo porcentaje que tuvo la venta.
                                        No se modifica el stock ni la cuenta: eso ya se registró cuando se hizo la venta.
                                    </div>
                                    <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="tbProductos">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Producto</th>
                                                <th class="text-center" style="width: 120px;">Cantidad</th>
                                                <th class="text-center" style="width: 160px;">Precio unitario</th>
                                                <th class="text-center" style="width: 140px;">SubTotal</th>
                                                <th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-end">Cargado / Venta</th>
                                                <th class="text-center"><span id="sumCantidad">0</span> / <?php echo $header['cantidad']; ?></th>
                                                <th></th>
                                                <th class="text-end">$ <span id="sumSubTotal">0.00</span><div class="small text-muted fw-normal">de $ <?php echo number_format($header['subTotal'], 2, '.', ''); ?></div></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                    <div id="avisoDiferencia" class="alert alert-warning d-none"></div>
                                    <button type="button" class="btn btn-outline-primary" id="btnAgregar"><i class="fas fa-plus me-1"></i>Agregar producto</button>
                                </div>
                                <div class="card-footer d-flex justify-content-end gap-2">
                                    <a href="ventas-inconsistentes.php" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Grabar</button>
                                </div>
                            </div>
                        </form>

                        <template id="filaProducto">
                            <tr>
                                <td>
                                    <select name="producto[]" class="form-select producto" required>
                                        <option value="">Seleccione</option>
                                        <?php foreach ($viewModel['productos'] as $producto) { ?>
                                            <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo number_format($producto['precio'], 2, '.', ''); ?>"><?php echo $e($producto['titulo']); ?><?php if ($producto['sku'] !== '') echo ' (' . $e($producto['sku']) . ')'; ?> - $ <?php echo number_format($producto['precio'], 2, '.', ''); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control text-end cantidad" min="1" step="1" value="1" required></td>
                                <td><input type="number" name="precio[]" class="form-control text-end precio" min="0" step="0.01" required></td>
                                <td class="text-end subtotal">0.00</td>
                                <td class="text-center"><a href="#" class="text-danger quitar" title="Quitar"><i class="fas fa-trash"></i></a></td>
                            </tr>
                        </template>
                    </div>
                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script>
            (function () {
                var cantidadVenta = <?php echo (int) $header['cantidad']; ?>;
                var subTotalVenta = <?php echo json_encode(round($header['subTotal'], 2)); ?>;
                var cuerpo = document.querySelector('#tbProductos tbody');
                var plantilla = document.getElementById('filaProducto');
                var aviso = document.getElementById('avisoDiferencia');

                function numero(valor) {
                    var n = parseFloat(String(valor).replace(',', '.'));
                    return isNaN(n) ? 0 : n;
                }

                function recalcular() {
                    var sumCantidad = 0;
                    var sumSubTotal = 0;
                    cuerpo.querySelectorAll('tr').forEach(function (fila) {
                        var cantidad = numero(fila.querySelector('.cantidad').value);
                        var subtotal = cantidad * numero(fila.querySelector('.precio').value);
                        fila.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                        sumCantidad += cantidad;
                        sumSubTotal += subtotal;
                    });
                    document.getElementById('sumCantidad').textContent = sumCantidad;
                    document.getElementById('sumSubTotal').textContent = sumSubTotal.toFixed(2);

                    var diferencias = [];
                    if (sumCantidad !== cantidadVenta) {
                        diferencias.push('la cantidad cargada (' + sumCantidad + ') no coincide con la de la venta (' + cantidadVenta + ')');
                    }
                    if (Math.round(sumSubTotal * 100) !== Math.round(subTotalVenta * 100)) {
                        diferencias.push('el subtotal cargado ($ ' + sumSubTotal.toFixed(2) + ') no coincide con el de la venta ($ ' + subTotalVenta.toFixed(2) + ')');
                    }
                    aviso.textContent = diferencias.length ? 'Atención: ' + diferencias.join(' y ') + '.' : '';
                    aviso.classList.toggle('d-none', diferencias.length === 0);
                    return diferencias;
                }

                function agregarFila() {
                    cuerpo.appendChild(plantilla.content.cloneNode(true));
                    recalcular();
                }

                cuerpo.addEventListener('change', function (event) {
                    if (event.target.classList.contains('producto')) {
                        var opcion = event.target.selectedOptions[0];
                        var precio = event.target.closest('tr').querySelector('.precio');
                        precio.value = opcion && opcion.dataset.precio ? opcion.dataset.precio : '';
                    }
                    recalcular();
                });
                cuerpo.addEventListener('input', recalcular);
                cuerpo.addEventListener('click', function (event) {
                    var quitar = event.target.closest('.quitar');
                    if (!quitar) return;
                    event.preventDefault();
                    if (cuerpo.querySelectorAll('tr').length > 1) {
                        quitar.closest('tr').remove();
                        recalcular();
                    }
                });
                document.getElementById('btnAgregar').addEventListener('click', agregarFila);

                document.getElementById('formAsociar').addEventListener('submit', function (event) {
                    var diferencias = recalcular();
                    if (diferencias.length && !confirm('Atención: ' + diferencias.join(' y ') + '.\n\n¿Grabar de todos modos?')) {
                        event.preventDefault();
                    }
                });

                agregarFila();
            })();
        </script>

    </body>
</html>
