<?php

session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["user.loggedin"]) || $_SESSION["user.loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../bff/editoriales/view-editorial-view-model.php";

if(
    (isset($_REQUEST['saikoProductoId']) and $_REQUEST['saikoProductoId']!="" ) and
    (isset($_REQUEST['accionOferta']) and $_REQUEST['accionOferta']=="saiko" )
){
    procesarSaikoOfertaEditorial(
        $_REQUEST['saikoProductoId'],
        $_REQUEST['saikoEditorial'],
        $_REQUEST['saikoMedioPago'],
        $_REQUEST['saikoPrecio'],
        $_REQUEST['saikoCantidad']
    );
}

$colConsignacion = false;

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))
    and isset($_REQUEST['forwardOk']) and $_REQUEST['forwardOk']!=""){

    // Get URL parameter
    $id =  trim($_GET["id"]);

    $editorialDetalleViewModel = obtenerEditorialDetalleViewModel($id);

    $editorialRow = $editorialDetalleViewModel['editorial'];
    $disponibleRow = $editorialDetalleViewModel['disponible'];
    $productosListado = $editorialDetalleViewModel['productos'];
    $ventasListado = $editorialDetalleViewModel['ventas'];
    $comprasListado = $editorialDetalleViewModel['compras'];

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Marca</title>
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

                $('#tbProductos').dataTable( {
                    "order": [[ 2, 'asc' ]],
                    "lengthMenu": [[10, 20, 50, 100, 150, -1], [10, 20, 50, 100, 150, "All"]]
                });
                
                $('#tbVentas').dataTable( {
                    "order": [[ 0, 'desc' ]],
                    "lengthMenu": [[10, 20, 50, 100, 150, -1], [10, 20, 50, 100, 150, "All"]]
                });

                $('#tbCompras').dataTable( {
                    "order": [[ 0, 'desc' ]],
                    "lengthMenu": [[10, 20, 50, 100, 150, -1], [10, 20, 50, 100, 150, "All"]]
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
                    <!--Producto -->    
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Marca</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="editoriales.php">Marcas</a></li>
                            <li class="breadcrumb-item active">Ver Marca</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Datos Marca
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tb">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Porcentaje</th>
                                            <th class="text-center">Monto Fijo</th>
                                            <th class="text-center">Proveedor</th>
                                            <th class="text-center">Consignacion</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (is_array($editorialRow)) {
                                                {
                                                    $row = $editorialRow;
                                                    $colNombre = $row['nombre'];
                                                    $colPorcentaje = $row['porcentaje'];
                                                    $colMontoFijo = $row['monto_fijo'];
                                                    $colProveedor = $row['proveedor'];
                                                    $colConsignacion = $row['consignacion'];
                                            
                                        ?>

                                                <tr>
                                                    <td><?php echo $colNombre; ?></td>
                                                    <td><?php echo $colPorcentaje; ?></td>
                                                    <td><?php echo $colMontoFijo; ?></td>
                                                    <td><?php echo $colProveedor; ?></td>
                                                    <td align="center"><input class="form-check-input" type="checkbox" value="" id="consignacion" <?php if ($colConsignacion == 1) echo "checked"; ?>  disabled></td>

                                                    <td align="center">
                                                        <a href="update-editoriales.php?id=<?php echo $row['id'];?>" class="text-primary"><i class="fa fa-fw fa-pencil"></i></a>
                                                        <a href="../delete-entity.php?entityName=editoriales&forwardOk=editoriales/editoriales.php&delId=<?php echo $row['id'];?>" class="text-danger" onClick="return confirm('Esta seguro de querer borrar esta marca?');"><i class="fa fa-fw fa-trash"></i></a>
                                                    </td>                            

                                                </tr>

                                        <?php
                                                }
                                            }
                                            else
                                            {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                        <!--- Disponibles -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Disponible para Reposicion
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tb">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha Ultima Compra</th>
                                            <th class="text-center">Total Vendido</th>
                                            <th class="text-center">Disponible Teorico</th>
                                            <th class="text-center">Disponible Real</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (is_array($disponibleRow)) {
                                                {
                                                    $row = $disponibleRow;
                                                    $colFecha = $row['fecha'];
                                                    $colTotalVendido = $row['total'];
                                                    $colDisponibleTeorico = $colTotalVendido * 0.70;
                                                    $colDisponibleReal = $row['costo'];
                                            
                                        ?>
                                                <tr>
                                                    <td><?php echo $colFecha; ?></td>
                                                    <td><?php echo $colTotalVendido; ?></td>
                                                    <td><?php echo $colDisponibleTeorico; ?></td>
                                                    <td><?php echo $colDisponibleReal; ?></td>
                                                </tr>
                                        <?php
                                                }
                                            }
                                            else
                                            {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                    
                        <!-- productos -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Productos
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbProductos">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Sku</th>
                                            <th class="text-center">Titulo</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">Precio Costo</th>
                                            <th class="text-center">Serie</th>
                                            <th class="text-center">Tomo</th>
                                            <th class="text-center">Formato</th>
                                            <th class="text-center">Nuevo</th>
                                            <?php if($colConsignacion == true) { ?>
                                            <th class="text-center">A Saiko</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (count($productosListado) > 0) {

                                                foreach ($productosListado as $row) {
                                                    $colId = $row['id'];
                                                    $colSku = $row['sku'];
                                                    $colTitulo = $row['titulo'];
                                                    $colStock = $row['stock'];
                                                    $colPrecio = $row['precio'];
                                                    $colPrecioCosto = $row['precio_costo'];
                                                    $colSerie = $row['serie'];
                                                    $colTomo = $row['tomo'];
                                                    $colFormato = $row['formato'];
                                                    $colEsNuevo = $row['nuevo'];
                                        ?>

                                                <tr>
                                                    <td><?php echo $colId;?></td>
                                                    <td><?php echo $colSku; ?></td>
                                                    <td><?php echo $colTitulo; ?></td>
                                                    <td><?php echo $colStock; ?></td>
                                                    <td><?php echo $colPrecio; ?></td>
                                                    <td><?php echo $colPrecioCosto; ?></td>
                                                    <td><?php echo $colSerie; ?></td>
                                                    <td><?php echo $colTomo; ?></td>
                                                    <td><?php echo $colFormato; ?></td>
                                                    <td align="center"><input class="form-check-input" type="checkbox" value="" id="esNuevo" <?php if ($colEsNuevo == 1) echo "checked"; ?>  disabled></td>
                                                    
                                                    <?php if($colConsignacion == true) { ?>
                                                    <td align="center">
                                                        <a 
                                                           href="#" 
                                                           class="text-primary" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#saikoModal" 
                                                           data-id="<?php echo $colId;?>" 
                                                           data-nombre="<?php echo $colTitulo;?>" 
                                                           data-precio="<?php echo $colPrecio;?>" 
                                                           data-reposicion="<?php echo $colPrecioCosto;?>" 
                                                           data-cantidad="<?php echo $colStock;?>" >
                                                           <i class="fa-solid fa-arrows-turn-right"></i></a>
                                                    </td>    
                                                    <?php } ?>
                                                </tr>

                                        <?php
                                                }
                                            }
                                            else
                                            {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                        <!-- Ventas -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Ventas
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbVentas">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Canal</th>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Unidades</th>
                                            <th class="text-center">P.U</th>
                                            <th class="text-center">Descuento</th>
                                            <th class="text-center">Motivo Descuento</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Medio Pago</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Equipo</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (count($ventasListado) > 0) {

                                                foreach ($ventasListado as $row) {
                                                    $colId = date('Ymd', strtotime($row['fecha']));
                                                    $colFecha = date('d/m/Y', strtotime($row['fecha']));
                                                    $colCanal = $row['canal'];
                                                    $colProducto = $row['producto'];
                                                    $colUnidades = number_format($row['unidades'], 0, ".", ""); 
                                                    $colPrecioUnitario = number_format($row['precioUnitario'], 2, ".", ""); 
                                                    $colDescuento = number_format($row['descuento'], 2, ".", ""); 
                                                    $colMotivoDescuento = $row['motivoDescuento'];
                                                    $colTotal = number_format($row['total'], 2, ".", ""); 
                                                    $colMedioPago = $row['medioPago'];
                                                    $colCliente = $row['cliente'];
                                                    $colEquipo = $row['equipo'];
                                            
                                        ?>

                                                <tr>
                                                    <td align="center"><span style="display:none;"><?php echo $colId;?></span><?php echo $colFecha; ?></td>
                                                    <td><?php echo $colCanal;?></td>
                                                    <td><?php echo $colProducto;?></td>
                                                    <td align="right"><?php echo $colUnidades; ?></td>
                                                    <td align="right"><?php echo $colPrecioUnitario; ?></td>
                                                    <td align="right"><?php echo $colDescuento; ?></td>
                                                    <td><?php echo $colMotivoDescuento; ?></td>
                                                    <td align="right"><?php echo $colTotal; ?></td>
                                                    <td><?php echo $colMedioPago; ?></td>
                                                    <td><?php echo $colCliente; ?></td>
                                                    <td><?php echo $colEquipo; ?></td>
                                                </tr>
                                        <?php
                                                }
                                            }
                                            else
                                            {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>


                        <!-- Compras -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Compras
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tbCompras">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Unidades</th>
                                            <th class="text-center">Precio Costo</th>
                                            <th class="text-center">Costo Total</th>
                                            <th class="text-center">Precio Lista</th>
                                            <th class="text-center">Total para Venta</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (count($comprasListado) > 0) {

                                                foreach ($comprasListado as $row) {
                                                    $colId = date('Ymd', strtotime($row['fecha']));
                                                    $colFecha = date('d/m/Y', strtotime($row['fecha']));
                                                    $colProducto = $row['producto'];
                                                    $colCantidad = number_format($row['cantidad'], 0, ".", ""); 
                                                    $colPrecioLista = number_format($row['precio_lista'], 2, ".", ""); 
                                                    $colPrecioCosto = number_format($row['precio_costo'], 2, ".", ""); 
                                                    $colCostoTotal = number_format($row['costo_total'], 2, ".", ""); 
                                                    $colListaTotal = number_format($row['lista_total'], 2, ".", ""); 
                                                    $colEstado = $row['estado'];
                                            
                                        ?>

                                                <tr>
                                                    <td align="center"><span style="display:none;"><?php echo $colId;?></span><?php echo $colFecha; ?></td>
                                                    <td><?php echo $colProducto;?></td>
                                                    <td align="right"><?php echo $colCantidad; ?></td>
                                                    <td align="right"><?php echo $colPrecioCosto; ?></td>
                                                    <td align="right"><?php echo $colCostoTotal; ?></td>
                                                    <td align="right"><?php echo $colPrecioLista; ?></td>
                                                    <td align="right"><?php echo $colListaTotal; ?></td>
                                                    <td><?php echo $colEstado; ?></td>
                                                </tr>
                                        <?php
                                                }
                                            }
                                            else
                                            {
                                            echo '0 results';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>


                        <form id="formViewClientes" method="post" onSubmit="return false;">
                            <a href="<?php echo $_REQUEST['forwardOk'];?>" class="btn btn-secondary ml-2"><i class="fa fa-fw fa-plus-circle"></i>Volver</a>
                        </form>

                    </div>



                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>

        <?php include 'saikoModal.php'; ?>
        
        <script>
        $('#saikoModal').on('show.bs.modal', function (event) {
            var applicant = $(event.relatedTarget);
            var id = applicant.data('id');
            var nombre = applicant.data('nombre');
            var lista = applicant.data('precio');
            var reposicion = applicant.data('reposicion');
            var cantidad = applicant.data('cantidad');
            var modal = $(this);
            modal.find('input[name="saikoProductoId"]').val(id);
            modal.find('input[name="saikoProductoNombre"]').val(nombre);
            modal.find('input[name="saikoPrecioLista"]').val(lista);
            modal.find('input[name="saikoPrecioReposicion"]').val(reposicion);
            modal.find('input[name="saikoCantidad"]').val(cantidad);
        });
        
        </script> 
    </body>
</html>
