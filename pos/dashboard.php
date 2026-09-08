<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/pos/dashboard-view-model.php";

$precioReferenciaDashboard = 6900;
$diasMesDashboard = (new DateTime())->format('t');

$dashboardViewModel = obtenerPosDashboardViewModel($precioReferenciaDashboard, $diasMesDashboard);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <style>
            .row-striped:nth-of-type(odd){
            background-color: #efefef;
            border-left: 4px #000000 solid;
            }

            .row-striped:nth-of-type(even){
            background-color: #ffffff;
            border-left: 4px #efefef solid;
            }

            .row-striped {
                padding: 15px 0;
            } 
            
            .badge-secondary {
                color: #fff;
                background-color: #6c757d;
            }            
        </style>

    </head>

    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>
        
        <div id="layoutSidenav">
            
            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>

                    <!-- Begin Page Content -->
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Tablero</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Tablero</a></li>
                            <li class="breadcrumb-item active"><? echo date('F Y'); ?></li>
                        </ol>
                        
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5">
                        
                        <?php
                                foreach ($dashboardViewModel['canales'] as $canalData) {
                                    $canalNombre = $canalData['nombre'];
                                    $totalVentas = $canalData['totalVentas'];
                                    $cantidadVentas = $canalData['cantidadVentas'];
                                    $totalUnidadesVendidas = $canalData['totalUnidadesVendidas'];
                                    $promedioVenta = $canalData['promedioVenta'];
                                    $totalCostosFijos = $canalData['totalCostosFijos'];
                                    $costoPromedioVentas = $canalData['costoPromedioVentas'];
                                    $puntoEquilibrioUnidad = $canalData['puntoEquilibrioUnidad'];
                                    $porcentajePEU = $canalData['porcentajePEU'];
                                    $puntoEquilibrioMonto = $canalData['puntoEquilibrioMonto'];
                                    $porcentajePEM = $canalData['porcentajePEM'];
                        ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <p class="card-text">Total Ventas <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">$ <?php echo number_format($totalVentas, 2, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-warning text-white">
                                        <p class="card-text">Cantidad Ventas <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo number_format($cantidadVentas, 0, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                        <p class="card-text">Unidades Vendidas <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo number_format($totalUnidadesVendidas, 0, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-danger text-white">
                                        <p class="card-text">Venta Promedio <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">$ <?php echo number_format($promedioVenta, 2, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- PUNTO EQUILIBRIO -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-danger text-white">
                                        <p class="card-text">Costos Fijos <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">$ <?php echo number_format($totalCostosFijos, 2, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-danger text-white">
                                        <p class="card-text">Costos Variables <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">$ <?php echo number_format($costoPromedioVentas, 2, ".", ""); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-danger text-white">
                                        <p class="card-text">P.Equilibrio x Unidad <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo number_format($puntoEquilibrioUnidad, 0, ".", ""); ?> - <?php echo number_format($porcentajePEU, 0, ".", ""); ?>%
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$porcentajePEU;?>%" aria-valuenow="<?=$porcentajePEU;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                <div class="card-header bg-danger text-white">
                                        <p class="card-text">P.Equilibrio por Monto <?php echo $canalNombre;?></p>
                                    </div>
                                    <div class="card-body">
                                        <p class ="card-text">$ <?php echo number_format($puntoEquilibrioMonto, 2, ".", ""); ?> - <?php echo number_format($porcentajePEM, 0, ".", ""); ?>%
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$porcentajePEM;?>%" aria-valuenow="<?=$porcentajePEM;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php 
                                }
                            ?>

                            <?php 
                                foreach ($dashboardViewModel['formatos'] as $formatoData) {
                                    $stockFormato = $formatoData['nombre'];
                                    $stockDisponible = $formatoData['stockDisponible'];
                                    $stockValorizado = $formatoData['stockValorizado'];
                                    $montoVentas = $formatoData['montoVentas'];
                                    $objetivoVentas = $formatoData['objetivoVentas'];
                                    $porcentajeObjetivoVentas = $formatoData['porcentajeObjetivoVentas'];
                            ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-info">
                                        <p class="card-text"><b><?php echo $stockFormato; ?></b></p>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            Stock : <?php echo number_format($stockDisponible, 0, ".", ""); ?> unidades - $ <?php echo number_format($stockValorizado, 2, ".", ""); ?>
                                        <p>    
                                        <p class="card-text">
                                            Ventas : <?php echo number_format($montoVentas, 2, ".", ""); ?> - Objetivo $ <?php echo number_format($objetivoVentas, 2, ".", ""); ?>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$porcentajeObjetivoVentas;?>%" aria-valuenow="<?=$porcentajeObjetivoVentas;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                }
                            ?>        

                        </div>                        

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-lg-6 mb-4">

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Evolucion de Ganancia</h6>
                                    <h7 class="m-0 font-weight-bold text-primary">Gastos vs Ganancia</h7>
                                </div>
                                <?php

                                    $diasMes = $diasMesDashboard;
                                    $precioReferencia = $precioReferenciaDashboard;

                                    $totalGastos = 0;
                                    foreach ($dashboardViewModel['totalGastosRows'] as $rowTotalGastos) {
                                        $total = $rowTotalGastos['total_gastos'];
                                        if (!is_null($total)){
                                            if ($total > $totalGastos){
                                                $totalGastos = $total;
                                            }
                                        }
                                    }

                                    $idealUnidades = $totalGastos;
                                    if ($idealUnidades <= 0) {
                                        $idealUnidades = 1;
                                    }

                                    $topXVentas = $diasMes * $idealUnidades;
                                    $idealValueArray = range($idealUnidades, $topXVentas, $idealUnidades);
                                    $idealArray = range(1, $diasMes, 1);
                                    $idealXArray = array();
                                    foreach ($idealArray as $value){
                                        $value = trim($value);
                                        $idealXArray[] = $value;
                                    }
                                    $actualArray = array();

                                    $cantidadVentas = 0;
                                    $diaIdx = 1;
                                    foreach ($dashboardViewModel['ventasNetoPorDia'] as $row) {
                                        while($diaIdx < $row['dia']){
                                            // avanzo dias hasta
                                            $actualArray[] = $cantidadVentas;
                                            $diaIdx += 1;
                                        }
                                        // cuento las ventas a hoy
                                        $cantidadVentas = $cantidadVentas + $row['unidades'];
                                        if ($cantidadVentas > $topXVentas){
                                            $topXVentas = $cantidadVentas;
                                        }
                                        $actualArray[] = $cantidadVentas;
                                        $diaIdx = $row['dia'] + 1;
                                    }
                                ?>
                                <div class="card-body">
                                    <div id="container-burndown" style="max-width: 800px; height: 600px;"></div>
                                </div>
                            </div>
                        </div>                        

                        <div class="col-lg-6 mb-4">

                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ingresos Vs Egresos</h6>
                                </div>
                                <?php

                                    $precioReferencia = $precioReferenciaDashboard;
                                    $arrayEgresos = array_fill(1, $diasMes, 0);

                                    $dataEgresos = array();
                                    $cantidadGastos = 0;
                                    $diaIdx = 1;
                                    foreach ($dashboardViewModel['gastosPorDia'] as $row) {
                                        $diaIdx = $row['dia'];
                                        $cantidadGastos = $row['unidades'];
                                        $arrayEgresos[$diaIdx] = $cantidadGastos;
                                    }

                                    $cantidadEgresos = 0;

                                    for ($i = 1; $i <= $diasMes; ++$i){
                                        $cantidadEgresos = $cantidadEgresos + $arrayEgresos[$i];
                                        $dataEgresos[] = array('x' => $i, 'y' => $cantidadEgresos);
                                    }

                                    $dataIngresos = array();
                                    $cantidadVentas = 0;
                                    $diaIdx = 1;
                                    if (count($dashboardViewModel['ventasNetoPorDia']) > 0) {
                                        foreach ($dashboardViewModel['ventasNetoPorDia'] as $row) {
                                            while($diaIdx < $row['dia']){
                                                // avanzo dias hasta
                                                $dataIngresos[] = array('x' => $diaIdx, 'y' => $cantidadVentas);
                                                $diaIdx += 1;
                                            }
                                            // cuento las ventas a hoy
                                            $cantidadVentas = $cantidadVentas + $row['unidades'];
                                            $dataIngresos[] = array('x' => $diaIdx, 'y' => $cantidadVentas);
                                            $diaIdx = $row['dia'] + 1;
                                        }
                                        // completo el mes
                                        while($diaIdx <= $diasMes){
                                            // avanzo dias hasta
                                            $dataIngresos[] = array('x' => $diaIdx, 'y' => $cantidadVentas);
                                            $diaIdx += 1;
                                        }
                                    }

                                ?>
                                <div class="card-body">    
                                    <div id="chartContainer" style="max-width: 800px; height: 600px;"></div>
                                    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <div class="row">
                        <!-- bar char -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Compras vs Ventas</h6>
                                </div>

                                <?php

                                    $arrayDataCompras = array();
                                    $arrayDataVentas = array();
                                    foreach ($dashboardViewModel['comprasVsVentas'] as $row) {

                                        $montoCompras = empty($row['total_compras']) ? 0 : $row['total_compras'];
                                        $montoVentas = empty($row['total_ventas']) ? 0 : $row['total_ventas'];

                                        if ($montoCompras > 0 or $montoVentas > 0) {
                                            $label = $row['nombre'];
                                            $arrayDataCompras[] = array('label' => $label, 'y' => $montoCompras);
                                            $arrayDataVentas[] = array('label' => $label, 'y' => $montoVentas);
                                        }
                                    }

                                ?>
                                <div class="card-body">    
                                    <div id="barChartContainer" style="max-width: 800px; height: 600px;"></div>
                                    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                                </div>
                            </div>
                        </div>        

                        <!-- calendario -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ventas por Categoria</h6>
                                </div>
                                <?php

                                    $cantidadTotalVentas = $dashboardViewModel['cantidadTotalVentas'];

                                    $arrayDataVentasCategoria = array();
                                    foreach ($dashboardViewModel['ventasPorSerie'] as $row) {
                                        $unidades = empty($row['unidades']) ? 0 : $row['unidades'];

                                        $porcentaje = ($unidades * 100) / $cantidadTotalVentas;

                                        $label = $row['serie'];
                                        $arrayDataVentasCategoria[] = array('label' => $label, 'y' => $porcentaje);
                                    }

                                ?>
                                <div class="card-body">    
                                    <div id="pieChartContainer" style="height: 370px; width: 100%;"></div>
                                    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>                                
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Cupos por Categoria</h6>
                            </div>
                            <div class="card-body">

                                <?php

                                    foreach ($dashboardViewModel['ocupacionCupos'] as $row) {
                                        $porcentaje = $row['porcentaje'] > 0 ? $row['porcentaje'] : 0;
                                        $minimo = $row['minimo'] > 0 ? $row['minimo'] : 30;
                                        if ($porcentaje < $minimo) {
                                            $className = "bg-danger";
                                        } else if ($porcentaje < 100){
                                            $className = "bg-warning";
                                        } else {
                                            $className = "bg-success";
                                        }
                                        $leyenda = ($porcentaje == 100) ? "Logrado !!" : number_format($porcentaje, 2, ".", "") . "%";
                                ?>
                                <h4 class="small font-weight-bold"><?php echo $row['nombre'];?> <span
                                        class="float-right"><?php echo $leyenda;?></span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar <?php echo $className;?>" role="progressbar" style="width: <?php echo $porcentaje;?>%"
                                        aria-valuenow="<?php echo $porcentaje;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <?php
                                    }
                                ?>

                            </div>
                        </div>

                        
                    </div>
                </main>
                <?php include '../footer.php';?>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>

        <script>
            jQuery(document).ready(function() {
            var doc = $(document);
            $('#container-burndown').highcharts({
                title: {
                text: 'Objetivo de ventas diarias',
                x: -10 //center
                },
                scrollbar: {
                            barBackgroundColor: 'gray',
                            barBorderRadius: 7,
                            barBorderWidth: 0,
                            buttonBackgroundColor: 'gray',
                            buttonBorderWidth: 0,
                            buttonBorderRadius: 7,
                            trackBackgroundColor: 'none',
                            trackBorderWidth: 1,
                            trackBorderRadius: 8,
                            trackBorderColor: '#CCC'
                        },
                colors: ['blue', 'red'],
                plotOptions: {
                line: {
                    lineWidth: 3
                },
                tooltip: {
                    hideDelay: 200
                }
                },
                subtitle: {
                text: 'Mes Actual',
                x: -10
                },
                xAxis: {
                    categories: <?php echo json_encode($idealXArray);?>,
                    title: {
                        text: 'Dias'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Cantidades'
                    },
                    type: 'linear',
                    max: <?php echo $topXVentas;?>,
                    min:0,
                    tickInterval : <?php echo $idealUnidades * 5; ?>
                },
                
                tooltip: {
                valueSuffix: ' unidades',
                crosshairs: true,
                shared: true
                },
                legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
                },
                series: [{
                name: 'Ideal',
                color: 'rgba(255,0,0,0.25)',
                lineWidth: 2,
                
                data: <?php echo json_encode($idealValueArray);?>
                }, {
                name: 'Actual',
                color: 'rgba(0,120,200,0.75)',
                marker: {
                    radius: 6
                },
                data: <?php echo json_encode($actualArray);?>
                }]
            });
                });
        </script>

        <script>
        window.onload = function () {
        
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Ingresos vs Egresos",
                fontSize: 18,
                color: 'rgba(0,120,200,0.75)'
            },
            subtitles: [{
                text: "Mes Actual",
                fontSize: 15
            }],
            axisY: {
                title: 'Cantidades',
                interval: 20
            },
            axisX: {
                title: 'Dias',
                interval: 5
            },
            legend:{
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            toolTip: {
                shared: true
            },
            data: [
            {
                type: "area",
                name: "Ingresos",
                showInLegend: "true",
                xValueType: "numeric",
                // xValueFormatString: "MMM YYYY",
                //yValueFormatString: "#,##0.##",
                dataPoints: <?php echo json_encode($dataIngresos); ?>
            },
            {
                type: "area",
                name: "Egresos",
                showInLegend: "true",
                xValueType: "int",
                //xValueFormatString: "MMM YYYY",
                //yValueFormatString: "#,##0.##",
                dataPoints: <?php echo json_encode($dataEgresos); ?>
            }
            ]
        });
        
        chart.render();
        
        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }
        

            // barchar
            var barChart = new CanvasJS.Chart("barChartContainer", {
                animationEnabled: true,
                title:{
                    text: "Compras vs Ventas, <? echo date('F Y'); ?>"
                },	
                axisY: {
                    title: "Compras",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                axisY2: {
                    title: "Ventas",
                    titleFontColor: "#C0504E",
                    lineColor: "#C0504E",
                    labelFontColor: "#C0504E",
                    tickColor: "#C0504E"
                },	
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor:"pointer",
                    itemclick: toggleBarCharDataSeries
                },
                data: [{
                    type: "column",	
                    name: "Ventas",
                    legendText: "Ventas",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($arrayDataVentas, 32); ?>
                },
                {
                    type: "column",
                    name: "Compras",
                    legendText: "Compras",
                    showInLegend: true, 
                    dataPoints: <?php echo json_encode($arrayDataCompras, 32); ?>
                }]
            });
            barChart.render();

            function toggleBarCharDataSeries(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else {
                    e.dataSeries.visible = true;
                }
                barChart.render();
            }

            // pie chart
            var pieChart = new CanvasJS.Chart("pieChartContainer", {
                animationEnabled: true,
                title: {
                    text: "Cantidades por Serie - <? echo date('F Y'); ?>"
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    yValueFormatString: "##0.00\"%\"",
                    indexLabel: "{label} {y}",
                    dataPoints: <?php echo json_encode($arrayDataVentasCategoria); ?>
                }]
            });
            pieChart.render();
        }
        </script>

    </body>
</html>
