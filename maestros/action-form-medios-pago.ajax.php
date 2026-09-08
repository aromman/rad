<?php require_once('../bff/maestros/action-form-medios-pago-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowMediosDePago"){
    $mediosPagoFormularioViewModel = obtenerMediosPagoFormularioViewModel();
    ?>
    <tr>
        <td><input type="text" name="nombre[]" class="form-control" required="required"></td>
        <td><input type="number" step="0" name="porcentaje[]" class="form-control"></td>

        <td>
            <select name="cuenta[]" id="cuenta" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($mediosPagoFormularioViewModel['cuentas'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>

            </select>
        </td>
        <td>
            <select name="canal[]" id="canal" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($mediosPagoFormularioViewModel['canales'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar este medio de pago?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveMediosDePagoAddMore"){
    agregarMediosPagoRapido($_REQUEST['nombre'], $_REQUEST['porcentaje'], $_REQUEST['cuenta'], $_REQUEST['canal']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}