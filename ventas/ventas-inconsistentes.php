<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/ventas/inconsistentes-view-model.php";

$ventasInconsistentesViewModel = obtenerVentasInconsistentesListadoViewModel();
$headersSinDetalle = $ventasInconsistentesViewModel['headersSinDetalle'];
$ventasSinHeader = $ventasInconsistentesViewModel['ventasSinHeader'];

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
        <title>Ventas Inconsistentes</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

        <script>
            $(document).ready(function(e) {
                $('#tbHeadersSinDetalle').dataTable({ "order": [[ 1, 'desc' ]] });
                $('#tbVentasSinHeader').dataTable({ "order": [[ 2, 'desc' ]] });
            });
        </script>
    </head>
    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>

        <div id="layoutSidenav">

            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Ventas Inconsistentes</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Ventas</li>
                            <li class="breadcrumb-item active">Ventas Inconsistentes</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-file-circle-exclamation me-1"></i>
                                Ventas sin detalle
                                <span class="badge bg-<?php echo count($headersSinDetalle) > 0 ? 'danger' : 'success'; ?> ms-1"><?php echo count($headersSinDetalle); ?></span>
                                <div class="small text-muted">Cabeceras de venta (ventas_header) que no tienen ningún registro en ventas.</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbHeadersSinDetalle">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Canal</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Medio Pago</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">SubTotal</th>
                                            <th class="text-center">Descuento</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Usuario</th>
                                            <th class="text-center">Actualizado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($headersSinDetalle as $row) { ?>
                                            <tr>
                                                <td align="right"><?php echo $row['id']; ?></td>
                                                <td align="center"><span style="display:none;"><?php echo $e($row['fecha']); ?></span><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                                <td><?php echo $e($row['canal']); ?></td>
                                                <td><?php echo $e($row['cliente']); ?></td>
                                                <td><?php echo $e($row['medioPago']); ?></td>
                                                <td align="right"><?php echo $row['cantidad']; ?></td>
                                                <td align="right"><?php echo $row['subTotal']; ?></td>
                                                <td align="right"><?php echo $row['descuento']; ?></td>
                                                <td align="right"><?php echo $row['total']; ?></td>
                                                <td><?php echo $e($row['usuario']); ?></td>
                                                <td align="center"><?php echo $e($row['actualizado']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-link-slash me-1"></i>
                                Ventas sin cabecera
                                <span class="badge bg-<?php echo count($ventasSinHeader) > 0 ? 'danger' : 'success'; ?> ms-1"><?php echo count($ventasSinHeader); ?></span>
                                <div class="small text-muted">Registros de ventas sin cabecera: sin id_ventas_header o con uno que no existe en ventas_header.</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbVentasSinHeader">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Id Header</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Canal</th>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Medio Pago</th>
                                            <th class="text-center">Unidades</th>
                                            <th class="text-center">P.U</th>
                                            <th class="text-center">Descuento</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ventasSinHeader as $row) { ?>
                                            <tr>
                                                <td align="right"><?php echo $row['id']; ?></td>
                                                <td align="right"><?php echo $row['idHeader'] === null ? '<span class="text-muted">(vacío)</span>' : $row['idHeader']; ?></td>
                                                <td align="center"><span style="display:none;"><?php echo $e($row['fecha']); ?></span><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                                <td><?php echo $e($row['canal']); ?></td>
                                                <td><?php echo $e($row['producto']); ?></td>
                                                <td><?php echo $e($row['cliente']); ?></td>
                                                <td><?php echo $e($row['medioPago']); ?></td>
                                                <td align="right"><?php echo $row['unidades']; ?></td>
                                                <td align="right"><?php echo $row['precioUnitario']; ?></td>
                                                <td align="right"><?php echo $row['descuento']; ?></td>
                                                <td align="right"><?php echo $row['total']; ?></td>
                                                <td align="center">
                                                    <a href="../update-ventas.php?id=<?php echo $row['id']; ?>" class="text-primary" title="Editar venta"><i class="fa fa-fw fa-pencil"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>

    </body>
</html>
