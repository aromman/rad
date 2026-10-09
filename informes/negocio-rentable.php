<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/informes/negocio-rentable-view-model.php";

if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarGastoFijo') {
    $errorGastoFijo = actualizarMontoGastoFijo(
        isset($_POST['idPresupuesto']) ? $_POST['idPresupuesto'] : '',
        isset($_POST['monto']) ? $_POST['monto'] : ''
    );
    if ($errorGastoFijo !== null) {
        $_SESSION['negocioRentable.error'] = $errorGastoFijo;
    }
    header('location: negocio-rentable.php');
    exit;
}

$mensajeError = isset($_SESSION['negocioRentable.error']) ? $_SESSION['negocioRentable.error'] : '';
unset($_SESSION['negocioRentable.error']);

$negocioRentableViewModel = obtenerNegocioRentableViewModel();
$gastosFijosListado = $negocioRentableViewModel['gastosFijos'];
$semaforo = $negocioRentableViewModel['semaforo'];

$reposicionColores = array(
    'rojo' => array('borde' => 'border-danger', 'encabezado' => 'bg-danger text-white', 'leyenda' => 'Más de un 10% por debajo del objetivo'),
    'amarillo' => array('borde' => 'border-warning', 'encabezado' => 'bg-warning text-dark', 'leyenda' => 'Cerca del objetivo (hasta un 10% por debajo)'),
    'verde' => array('borde' => 'border-success', 'encabezado' => 'bg-success text-white', 'leyenda' => 'Supera el objetivo'),
    'sin-objetivo' => array('borde' => 'border-secondary', 'encabezado' => 'bg-secondary text-white', 'leyenda' => 'Sin objetivo: no hay gastos presupuestados'),
);
$reposicionColor = $reposicionColores[$negocioRentableViewModel['reposicionEstado']];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Negocio Rentable</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <style>
            .semaforo {
                display: inline-flex;
                gap: 6px;
                padding: 6px 8px;
                background-color: #212529;
                border-radius: 999px;
            }
            .semaforo-luz {
                width: 18px;
                height: 18px;
                border-radius: 50%;
                opacity: 0.2;
            }
            .semaforo-luz.rojo { background-color: #dc3545; }
            .semaforo-luz.amarillo { background-color: #ffc107; }
            .semaforo-luz.verde { background-color: #198754; }
            .semaforo-luz.encendida { opacity: 1; }
            .semaforo-luz.rojo.encendida { box-shadow: 0 0 8px #dc3545; }
            .semaforo-luz.amarillo.encendida { box-shadow: 0 0 8px #ffc107; }
            .semaforo-luz.verde.encendida { box-shadow: 0 0 8px #198754; }
        </style>
    </head>
    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>

        <div id="layoutSidenav">

            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Negocio Rentable <small class="text-muted fs-4">(Estimado)</small></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Informes</li>
                            <li class="breadcrumb-item">Planes</li>
                            <li class="breadcrumb-item active">Negocio Rentable</li>
                        </ol>
                        <?php if ($mensajeError !== '') { ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card shadow h-100 border-primary">
                                    <div class="card-header fw-bold bg-primary text-white">
                                        <i class="fas fa-file-invoice-dollar me-1"></i>
                                        Gastos Fijos
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <?php
                                            if (count($gastosFijosListado) > 0) {
                                                foreach ($gastosFijosListado as $row) {
                                        ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><?php echo htmlspecialchars($row['claseGasto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    <span class="text-nowrap">
                                                        <?php echo $row['monto']; ?>
                                                        <a href="#" class="text-primary ms-2" title="Actualizar presupuesto"
                                                           data-bs-toggle="modal" data-bs-target="#gastoFijoModal"
                                                           data-id="<?php echo $row['id']; ?>"
                                                           data-nombre="<?php echo htmlspecialchars($row['claseGasto'], ENT_QUOTES, 'UTF-8'); ?>"
                                                           data-monto="<?php echo number_format($row['montoValor'], 2, '.', ''); ?>"><i class="fas fa-pencil"></i></a>
                                                    </span>
                                                </li>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                                <li class="list-group-item text-center text-muted">No hay gastos presupuestados</li>
                                        <?php
                                            }
                                        ?>
                                    </ul>
                                    <div class="card-footer d-flex justify-content-between align-items-center fw-bold">
                                        <span>Total</span>
                                        <span class="fs-5 text-nowrap"><?php echo $negocioRentableViewModel['totalGastosFijos']; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card shadow h-100 border-success">
                                    <div class="card-header fw-bold bg-success text-white">
                                        <i class="fas fa-bullseye me-1"></i>
                                        Ventas Objetivo
                                    </div>
                                    <div class="card-body">
                                        <div class="text-muted small">Objetivo (Gastos Fijos x 2)</div>
                                        <div class="fs-3 fw-bold text-nowrap"><?php echo $negocioRentableViewModel['ventasObjetivo']; ?></div>

                                        <hr>

                                        <div class="text-muted small">Ventas al momento (mes actual)</div>
                                        <div class="fs-4 text-nowrap"><?php echo $negocioRentableViewModel['ventasAlMomento']; ?></div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <?php if ($semaforo === 'sin-objetivo') { ?>
                                            <span class="text-muted">Sin objetivo: no hay gastos presupuestados</span>
                                        <?php } else { ?>
                                            <span class="fw-bold">Logro <?php echo $negocioRentableViewModel['porcentajeLogro']; ?>%</span>
                                        <?php } ?>
                                        <span class="semaforo" title="Logro del objetivo">
                                            <span class="semaforo-luz rojo<?php if ($semaforo === 'rojo') echo ' encendida'; ?>"></span>
                                            <span class="semaforo-luz amarillo<?php if ($semaforo === 'amarillo') echo ' encendida'; ?>"></span>
                                            <span class="semaforo-luz verde<?php if ($semaforo === 'verde') echo ' encendida'; ?>"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card shadow h-100 <?php echo $reposicionColor['borde']; ?>">
                                    <div class="card-header fw-bold <?php echo $reposicionColor['encabezado']; ?>">
                                        <i class="fas fa-boxes-stacked me-1"></i>
                                        Reposicion de Mercaderia
                                    </div>
                                    <div class="card-body">
                                        <div class="text-muted small">Objetivo (Ventas Objetivo / 2)</div>
                                        <div class="fs-3 fw-bold text-nowrap"><?php echo $negocioRentableViewModel['reposicionObjetivo']; ?></div>

                                        <hr>

                                        <div class="text-muted small">Reposicion al momento (Ventas al momento / 2)</div>
                                        <div class="fs-4 text-nowrap"><?php echo $negocioRentableViewModel['reposicionAlMomento']; ?></div>
                                    </div>
                                    <div class="card-footer fw-bold">
                                        <?php echo $reposicionColor['leyenda']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card shadow h-100 border-info">
                                    <div class="card-header fw-bold bg-info text-dark">
                                        <i class="fas fa-piggy-bank me-1"></i>
                                        Inversiones
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Compra de nueva mercaderia</span>
                                            <span class="text-nowrap"><?php echo $negocioRentableViewModel['compraNuevaMercaderia']; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Prevision para Costos Fijos</span>
                                            <span class="text-nowrap"><?php echo $negocioRentableViewModel['previsionCostosFijos']; ?></span>
                                        </li>
                                        <?php if (!$negocioRentableViewModel['hayExcedenteReposicion']) { ?>
                                            <li class="list-group-item text-center text-muted small">La reposicion al momento todavia no supera el objetivo</li>
                                        <?php } ?>
                                    </ul>
                                    <div class="card-footer d-flex justify-content-between align-items-center fw-bold mt-auto">
                                        <span>Total</span>
                                        <span class="fs-5 text-nowrap"><?php echo $negocioRentableViewModel['totalInversiones']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header fw-bold">
                                <i class="fas fa-lightbulb me-1"></i>
                                Consejos para generar inversiones
                            </div>
                            <div class="card-body">
                                <?php foreach ($negocioRentableViewModel['consejos'] as $consejo) { ?>
                                    <div class="alert alert-<?php echo $consejo['tipo']; ?> d-flex align-items-start mb-2" role="alert">
                                        <i class="fas <?php echo $consejo['icono']; ?> fa-lg me-3 mt-1"></i>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($consejo['titulo'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div><?php echo htmlspecialchars($consejo['texto'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </main>

                <?php include '../footer.php';?>

                <div class="modal fade" id="gastoFijoModal" tabindex="-1" aria-labelledby="gastoFijoModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="gastoFijoModalLabel">Actualizar presupuesto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="accion" value="actualizarGastoFijo">
                                    <input type="hidden" name="idPresupuesto" id="gastoFijoId">
                                    <div class="mb-3">
                                        <label class="form-label">Gasto fijo</label>
                                        <input type="text" class="form-control" id="gastoFijoNombre" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="gastoFijoMonto">Monto mensual</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" name="monto" class="form-control" id="gastoFijoMonto" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Grabar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script>
            document.getElementById('gastoFijoModal').addEventListener('show.bs.modal', function (event) {
                var origen = event.relatedTarget;
                document.getElementById('gastoFijoId').value = origen.getAttribute('data-id');
                document.getElementById('gastoFijoNombre').value = origen.getAttribute('data-nombre');
                document.getElementById('gastoFijoMonto').value = origen.getAttribute('data-monto');
            });
            document.getElementById('gastoFijoModal').addEventListener('shown.bs.modal', function () {
                document.getElementById('gastoFijoMonto').select();
            });
        </script>

    </body>
</html>
