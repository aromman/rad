<?php require_once('../bff/maestros/action-form-productos-ubicacion-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowProductosUbicacion"){
    $productosUbicacionFormularioViewModel = obtenerProductosUbicacionFormularioViewModel();
    ?>
    <tr>
        <td>
            <select name="canal[]" id="canal" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($productosUbicacionFormularioViewModel['canales'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>

            </select>
        </td>
        <td>
            <select name="tipoNivel01[]" id="tipoNivel01" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($productosUbicacionFormularioViewModel['tipos'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>

            </select>
        </td>
        <td><input type="number" step="0.1" name="codigoNivel01[]" class="form-control"></td>
        <td>
            <select name="tipoNivel02[]" id="tipoNivel02" class="form-control" required="required">
                <option value="">Seleccione</option>
                <?php
                    foreach ($productosUbicacionFormularioViewModel['tipos'] as $val) {
                    ?>
                    <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                <?php }?>

            </select>
        </td>
        <td><input type="number" step="0.1" name="codigoNivel02[]" class="form-control"></td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveProductosUbicacionAddMore"){
    agregarProductosUbicacionRapido($_REQUEST['canal'], $_REQUEST['tipoNivel01'], $_REQUEST['codigoNivel01'], $_REQUEST['tipoNivel02'], $_REQUEST['codigoNivel02']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}