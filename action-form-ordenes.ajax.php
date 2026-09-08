<?php
require_once('bff/ordenes/action-form-view-model.php');

if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowOrdenes"){
    $proveedoresSelectorViewModel = obtenerProveedoresSelectorViewModel();
    ?>
    <tr>
        <td align="center"><input type="date" name="fecha[]" value="<?php echo date("Y-m-d");?>"></td>
        <td>
            <select name="proveedor[]" id="proveedor" class="form-control selectpicker" data-live-search="true" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($proveedoresSelectorViewModel['proveedores'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>
        <td colspan="4"></td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar este orden?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveOrdenesAddMore"){
    agregarOrdenesRapido($_REQUEST['fecha'], $_REQUEST['proveedor']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}