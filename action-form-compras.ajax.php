<?php require_once('bff/compras/action-form-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowCompras"){
    $comprasFormularioViewModel = obtenerComprasFormularioViewModel();
?>
    <tr>
        <td align="center"><input type="date" name="fecha[]" value="<?php echo date("Y-m-d");?>"></td>
        <td>
            <select name="producto[]" id="producto" class="form-control selectpicker" data-live-search="true" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($comprasFormularioViewModel['productos'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['titulo'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>
        <td><input type="number" step="0" name="cantidad[]" class="form-control" required="required"></td>
        <td><input type="number" step=".01" name="precioLista[]" class="form-control" required="required"></td>
        <td><input type="number" step=".01" name="precioCompra[]" class="form-control"></td>
        <td>
            <select name="orden[]" id="orden" class="form-control selectpicker" data-live-search="true">
                <option value="">Seleccione</option>
                <?php
                    foreach ($comprasFormularioViewModel['ordenes'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>
        <td><input disabled type="text" name="estado[]" class="form-control"></td> 
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar esta compra?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveComprasAddMore"){
    agregarComprasRapido($_REQUEST['fecha'], $_REQUEST['producto'], $_REQUEST['cantidad'], $_REQUEST['precioLista'], $_REQUEST['precioCompra'], $_REQUEST['orden']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}