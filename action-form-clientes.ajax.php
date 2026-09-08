<?php require_once('bff/clientes/action-form-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowClientes"){
    $descuentosSelectorViewModel = obtenerDescuentosSelectorViewModel();
    ?>
    <tr>

        <td><input type="text" name="apellido[]" class="form-control" required="required"></td>
        <td><input type="text" name="nombre[]" class="form-control" required="required"></td>
        <td><input type="text" name="dni[]" class="form-control"></td>
        <td><input type="text" name="email[]" class="form-control"></td>

        <td>
            <select name="descuento[]" id="descuento" class="form-control selectpicker" data-live-search="true" data-size="10" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($descuentosSelectorViewModel['descuentos'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>

        
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar este cliente?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveClientesAddMore"){
    agregarClientesRapido($_REQUEST['apellido'], $_REQUEST['nombre'], $_REQUEST['dni'], $_REQUEST['email'], $_REQUEST['descuento']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}