<?php
require_once('../bff/editoriales/update-editoriales-view-model.php');

// Define variables and initialize with empty values
$nombre = $porcentaje = $id_proveedor = $consignacion = $montoFijo = "";
$nombre_err = $porcentaje_err = $id_proveedor_err = $consignacion_err = $montoFijo_err = "";

$mensaje = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    $mensaje = "Actualizando";

    // Get hidden input value
    $id = $_POST["id"];
    
    $input_nombre = trim($_POST["nombre"]);
    $input_porcentaje = trim($_POST["porcentaje"]);
    $input_monto_fijo = trim($_POST["montoFijo"]);
    $input_id_proveedor = trim($_POST["proveedor"]);
    $input_consignacion = trim($_POST["consignacion"]);

    actualizarEditorial($id, $input_nombre, $input_porcentaje, $input_monto_fijo, $input_id_proveedor, $input_consignacion);

    header('location: editoriales.php');
    exit();
    
} else{

    $mensaje = "Editando";

    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

        // Get URL parameter
        $id =  trim($_GET["id"]);

        $editorialEditarViewModel = obtenerEditorialParaEditarViewModel($id);
        $val = $editorialEditarViewModel['editorial'];

        $nombre = $val["nombre"];
        $porcentaje = $val["porcentaje"]; 
        $montoFijo = $val["monto_fijo"]; 
        $proveedor = $val["id_proveedor"]; 
        $consignacion = $val["consignacion"]; 

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
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
        <title>Tables - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    </head>
    <body class="sb-nav-fixed">

        <?php include '../topBar.php';?>

        <div id="layoutSidenav">
            
            <?php include '../sidebar.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Actualizar Editorial</h1>
                        <h2 class="mt-4"><?php echo $mensaje?></h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="editoriales.php">Editoriales</a></li>
                            <li class="breadcrumb-item active">Actualizar Editorial</li>
                        </ol>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                                
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" value="<?php echo $nombre;?>" class="form-control" required="required">
                                </div>

                                <div class="form-group">
                                    <label>Porcentaje</label>
                                    <input type="number" step="0" name="porcentaje" value="<?php echo $porcentaje;?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Monto Fijo</label>
                                    <input type="number" step="0.1" name="montoFijo" value="<?php echo $montoFijo;?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Proveedor</label>
                                    <select name="proveedor" id="proveedor" class="form-control" data-live-search="true" data-size="10">
                                        <option value="">Seleccione</option>
                                        <?php
                                            foreach ($editorialEditarViewModel['proveedores'] as $val) {
                                            ?>
                                            <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"  <?php if($val['id']==$proveedor) echo 'selected="selected"'; ?>><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Consignacion</label>
                                    <select name="consignacion" id="consignacion" class="form-control" data-size="10">
                                        <option value="">Seleccione</option>
                                        <option value="0" data-subtext="(0)" <?php if(0==$consignacion) echo 'selected="selected"'; ?>>NO</option>
                                        <option value="1" data-subtext="(1)" <?php if(1==$consignacion) echo 'selected="selected"'; ?>>SI</option>
                                    </select>
                                </div>
                                <br> 
                                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                <input type="submit" class="btn btn-primary" value="Grabar">
                                <a href="editoriales.php" class="btn btn-secondary ml-2">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </main>

                <?php include '../footer.php';?>

            </div>
        </div>

    </body>
</html>