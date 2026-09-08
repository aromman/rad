<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/ventas/pendientes-facturar-view-model.php";

$ventasViewModel = obtenerVentasPendientesFacturarViewModel();
$ventasPendientes = $ventasViewModel['ventas'];

$rol = $_SESSION["user.rol"];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Ventas</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">

        <script>
        </script>

    </head>
    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>

        <div id="layoutSidenav">
            
            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Ventas a Facturar</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Tablero</a></li>
                            <li class="breadcrumb-item active">Periodo Actual</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Ventas
                            </div>
                            <div class="card-body">
                                <form id="formVentas" method="post" onSubmit="return false;">

                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbVentas">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Unidades</th>
                                            <th class="text-center">SubTotal</th>
                                            <th class="text-center">Descuento</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Medio Pago</th>
                                            <th class="text-center">Canal</th>
                                            <th class="text-center">Usuario</th>
                                            <th class="text-center">Actualizado</th>
                                            <th class="text-center">Comprobante</th>
                                            <th class="text-center">CAE</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (count($ventasPendientes) > 0) {
                                                foreach ($ventasPendientes as $row) {
                                        ?>

                                                <tr>
                                                    <td align="center"><span style="display:none;"><?php echo $row['fechaId'];?></span><?php echo $row['fecha']; ?></td>
                                                    <td><?php echo $row['cliente']; ?></td>
                                                    <td align="right"><?php echo $row['unidades']; ?></td>
                                                    <td align="right"><?php echo $row['subTotal']; ?></td>
                                                    <td align="right"><?php echo $row['descuento']; ?></td>
                                                    <td align="right"><?php echo $row['total']; ?></td>
                                                    <td><?php echo $row['medioPago']; ?></td>
                                                    <td><?php echo $row['canal']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['updateDate']; ?></td>
                                                    <td><?php echo $row['comprobante']; ?></td>
                                                    <td><?php echo $row['cae']; ?></td>
                                                    <td align="center">
                                                        <a href="view-ventas.php?forwardOk=ventas-a-facturar.php&id=<?php echo $row['id'];?>" class="text-primary"><i class="fa fa-fw fa-eye"></i></a>
                                                        <?php if ($rol == 0 && empty($row['comprobante'])) { ?>
                                                        <a href="facturar.php?forwardOk=ventas-a-facturar.php&id=<?php echo $row['id'];?>&total=<?php echo $row['total'];?>&fecha=<?php echo $row['fechaFactura'];?>&puntoVenta=<?php echo $row['puntoVenta'];?>" class="text-primary"><i class="fa fa-fw fa-credit-card"></i></a>
                                                        <?php } else {
                                                        ?>
                                                            <a href="view-comprobante-venta.php?forwardOk=ventas-a-facturar.php&id=<?php echo $row['comprobante'];?>&puntoVenta=<?php echo $row['puntoVenta'];?>" class="text-primary"><i class="fa fa-fw fa-print"></i></a>
                                                        <?php
                                                        } ?>
                                                    </td>
                                                </tr>
                                        <?php
                                                }
                                            } else {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>


    </body>
</html>
