<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "bff/objetivos/actualizar-objetivos-view-model.php";

$objetivosViewModel = obtenerActualizarObjetivosViewModel();

?>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script defer src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    </head>
    <body class="sb-nav-fixed">
        <?php include 'topBar.php';?>
        
        <div id="layoutSidenav">
            
            <?php include 'sidebar.php';?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h4 class="mt-4">Objetivos</h4>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Procesos de Objetivos para punto de Quiebre</li>
                        </ol>
                        <div class="row">

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-header">Gastos</div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Detalle de Gastos
                                        </p>
                                    </div>
                                    <div class="list-group list-group-flush">

                                        <?php
                                            foreach ($objetivosViewModel['gastosDetalle'] as $textoPago) {
                                        ?>
                                            <a href="#" class="list-group-item list-group-item-action"><?php echo $textoPago; ?></a>

                                        <?php
                                            }
                                        ?>

                                    </div>
                                    <div class="card-footer">
                                        <a href="#" class="list-group-item list-group-item-action"><?php echo $objetivosViewModel['textoTotalGastos']; ?></a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-header">Ventas</div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Datos de Referencia de ventas
                                        </p>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        <?php
                                            $textoRepartos = "Precio Venta: $ " . number_format($objetivosViewModel['precioVentaReferencia'], 2, ".", "");
                                            $textoCosto = "Costo Venta: $ " . number_format($objetivosViewModel['costoVentaReferencia'], 2, ".", "") . " (30%)";
                                        ?>
                                            <a href="#" class="list-group-item list-group-item-action"><?php echo $textoRepartos; ?></a>
                                            <a href="#" class="list-group-item list-group-item-action"><?php echo $textoCosto; ?></a>

                                    </div>
                                </div>
                            </div>


                            <?php
                                $totalUnidadesPC = $objetivosViewModel['totalUnidadesPC'];
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-header">Punto de Quiebre</div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Calculo Actual
                                        </p>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">Unidades : <?php echo number_format(($totalUnidadesPC), 0, ".", "");?></a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-header">Objetivos</div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Detalle de objetivos
                                        </p>
                                    </div>
                                    <div class="list-group list-group-flush">

                                        <?php
                                            foreach ($objetivosViewModel['objetivosDetalle'] as $texto) {
                                        ?>
                                            <a href="#" class="list-group-item list-group-item-action"><?php echo $texto; ?></a>

                                        <?php
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </main>
                
                <?php include 'footer.php';?>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
