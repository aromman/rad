<?php require_once('../bff/maestros/action-form-productos-serie-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowProductosSerie"){
    ?>
    <tr>
        <td><input type="text" name="nombre[]" class="form-control" required="required"></td>
        <td><input type="number" step="0" name="cupoMaximo[]" class="form-control" required="required"></td>
        <td><input type="number" step="0" name="cupoMinimo[]" class="form-control" required="required"></td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar esta serie?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveProductosSerieAddMore"){
    agregarProductosSerieRapido($_REQUEST['nombre'], $_REQUEST['cupoMaximo'], $_REQUEST['cupoMinimo']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}