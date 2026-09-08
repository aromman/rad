<?php require_once('../bff/editoriales/action-form-view-model.php');

if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowEditoriales"){
    $proveedoresSelectorViewModel = obtenerProveedoresSelectorParaEditorialesViewModel();
    ?>
    <tr>
        <td><input type="text" name="nombre[]" class="form-control" required="required"></td>
        <td><input type="number" step="0" name="porcentaje[]" class="form-control"></td>
        <td><input type="number" step=".01" name="montoFijo[]" class="form-control"></td>
        <td>
            <select name="proveedor[]" id="proveedor" class="form-control selectpicker" data-live-search="true" data-size="10">
                <option value="">Seleccione</option>
                <?php
                    foreach ($proveedoresSelectorViewModel['proveedores'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>
            </select>
        </td>
        <td>
            <select name="consignacion[]" id="consignacion" class="form-control selectpicker" data-live-search="true" data-size="10">
                <option value="">Seleccione</option>
                <option value="0" data-subtext="(0)">NO</option>
                <option value="1" data-subtext="(1)">SI</option>
            </select>
        </td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar esta Editorial?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveEditorialesAddMore"){
    agregarEditorialesRapido($_REQUEST['nombre'], $_REQUEST['porcentaje'], $_REQUEST['montoFijo'], $_REQUEST['proveedor'], $_REQUEST['consignacion']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}