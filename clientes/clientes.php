<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/clientes/listado-view-model.php";

$clientesViewModel = obtenerClientesListadoViewModel();
$clientesListado = $clientesViewModel['clientes'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Clientes</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">

        <script>
            $(document).ready(function(e) {
                $('#tbClientes').dataTable( {
                });
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
                        <h1 class="mt-4">Clientes</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Tablero</a></li>
                            <li class="breadcrumb-item active">Periodo Actual</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Clientes
                            </div>
                            <div class="card-body">
                                <form id="formClientes" method="post" onSubmit="return false;">

                                <div class="row justify-content-end">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal"><i class="fa-solid fa-circle-plus"></i> Nuevo Cliente</a>
                                    </div>
                                </div>
                                
                                <table class="table table-striped table-bordered" id="tbClientes">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Apellido</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Dni</th>
                                            <th class="text-center">Contacto</th>
                                            <th class="text-center">Descuento</th>
                                            <th class="text-center">Solo Contacto</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (count($clientesListado) > 0) {
                                                $_SESSION['clientesRows'] = array();
                                                foreach ($clientesListado as $row) {

                                                    $_SESSION['clientesRows'][] = $row; // guardo los registros en session

                                        ?>

                                                <tr>
                                                    <td><?php echo $row['apellido']; ?></td>
                                                    <td><?php echo $row['nombre']; ?></td>
                                                    <td><?php echo $row['dni']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php echo $row['descuento']; ?></td>
                                                    <td align="center"><input class="form-check-input" type="checkbox" value="" id="soloContacto" <?php if ($row['solo_contacto'] == 1) echo "checked"; ?>  disabled></td>

                                                    <td align="center">
                                                        <a href="view-cliente.php?forwardOk=clientes.php&id=<?php echo $row['id'];?>" class="text-primary"><i class="fa fa-fw fa-eye"></i></a>
                                                        <a href="update-clientes.php?id=<?php echo $row['id'];?>" class="text-primary"><i class="fa fa-fw fa-pencil"></i></a>
                                                        <a href="delete-cliente.php?forwardOk=clientes.php&delId=<?php echo $row['id'];?>" class="text-danger" onClick="return confirm('Esta seguro de querer borrar esta cliente?');"><i class="fa fa-fw fa-trash"></i></a>
                                                    </td>
                                                </tr>

                                        <?php
                                                }
                                            } else {
                                                echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <a href="../export-data.php?entityName=clientes" class="btn btn-primary">Export to excel</a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <?php include 'nuevoModal.php'; ?>

    </body>
</html>
