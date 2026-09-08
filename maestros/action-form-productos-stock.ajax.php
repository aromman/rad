<?php 

require_once "../bff/maestros/action-form-productos-stock-view-model.php";

if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowProductoStock"){
        $idProducto =  trim($_REQUEST["producto"]);
        $productoStockFormularioViewModel = obtenerProductoStockFormularioViewModel();
    ?>
    <tr>

        <td>
            <select name="editorial[]" id="editorial" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($productoStockFormularioViewModel['editoriales'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php
                    }
                ?>
            </select>
        </td>
        <td><input type="number" step="0.1" name="cantidad[]" class="form-control" required="required"></td>
        <td>
            <select name="ubicacion[]" id="ubicacion" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($productoStockFormularioViewModel['ubicaciones'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>

            </select>
        </td>
        <td><input type="date" name="fecha[]" value="<?php echo date("Y-m-d");?>" class="form-control" required="required"></td>
        
        <td><input type="number" step=".01" name="costo[]" class="form-control" required="required"></td>
        <td>
            <select name="esNuevo[]" id="esNuevo" class="form-control" data-live-search="true" data-size="10" required="required">
                <option value="">Seleccione</option>
                <option value="1" data-subtext="(TRUE)" <?php if(1==$esNuevo) echo 'selected="selected"'; ?>>Si</option>
                <option value="0" data-subtext="(FALSE)" <?php if(0==$esNuevo) echo 'selected="selected"'; ?>>No</option>
            </select>
        </td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveProductoStockAddMore"){
    $idProducto =  trim($_REQUEST["producto"]);

    agregarProductoStockRapido($idProducto, $_REQUEST['editorial'], $_REQUEST['cantidad'], $_REQUEST['ubicacion'], $_REQUEST['fecha'], $_REQUEST['costo'], $_REQUEST['esNuevo']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}