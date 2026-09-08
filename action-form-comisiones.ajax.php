<?php require_once('bff/empleados/action-form-comisiones-view-model.php');
if(isset($_REQUEST['action']) and $_REQUEST['action']=="addDataRowComisiones"){
    $empleadosSelectorViewModel = obtenerEmpleadosSelectorViewModel();
    ?>
    <tr>

        <td align="center"><input type="date" name="fecha[]" value="<?php echo date("Y-m-d");?>">        </td>
        <td><input type="text" name="detalle[]" class="form-control" required="required"></td>
        <td><input type="number" step=".01" name="monto[]" class="form-control" required="required"></td>
        <td>
        <select name="empleado[]" id="empleado" class="form-control selectpicker" data-live-search="true" data-size="10" required="required">
            <option value="">Seleccione</option>
			<?php
				foreach ($empleadosSelectorViewModel['empleados'] as $val) {
				?>
				<option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['nombre']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
			<?php }?>
        </select>
        </td>
        <td align="center">
            <a class="text-danger" onClick="if(confirm('Esta seguro de querer borrar esta comision?')){$(this).closest('tr').remove();}"><i class="fa fa-fw fa-trash"></i></a>
        </td>
    </tr>
    <?php
    echo '|***|addmore';
}
 
//Submit data or extra rows
if(isset($_REQUEST['action']) and $_REQUEST['action']=="saveComisionesAddMore"){
    agregarComisionesRapido($_REQUEST['fecha'], $_REQUEST['detalle'], $_REQUEST['monto'], $_REQUEST['empleado']);
    echo '<div class="alert alert-success"><i class="fa fa-fw fa-thumbs-up"></i> Record added successfully!</div>|***|add';
}