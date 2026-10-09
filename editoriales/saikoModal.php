<div class="modal fade" id="saikoModal" tabindex="-1" aria-labelledby="saikoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="abrirModalLabel">Pasaje a Saiko</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="saikoProductoId">
                    <input type="hidden" name="saikoEditorialOldId">
                    <div class="mb-3">
                        <label>Producto</label>
                        <input type="text" name="saikoProductoNombre" class="form-control" readOnly>
                    </div>
                    <div class="mb-3">
                        <label>Cantidad</label>
                        <input type="text" name="saikoCantidad" class="form-control" readOnly>
                    </div>
                    <div class="mb-3">
                        <label>Precio Lista</label>
                        <input type="number" step=".01" name="saikoPrecioLista" class="form-control" readOnly>
                    </div>
                    <div class="mb-3">
                        <label>Precio Reposicion</label>
                        <input type="number" step=".01" name="saikoPrecioReposicion" class="form-control" readOnly>
                    </div>
                    <div class="mb-3">
                        <label>Precio Consignado</label>
                        <input type="number" step="0" name="saikoPrecio" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <select name="saikoEditorial" id="saikoEditorial" class="form-control" data-live-search="true" data-size="10" required="required">
                        <option value="">Seleccione</option>
                        <?php
                            $saikoSelectoresViewModel = obtenerSaikoSelectoresViewModel();
                            foreach ($saikoSelectoresViewModel['editoriales'] as $val) {
                            ?>
                            <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                        <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Medio Pago</label>
                        <select name="saikoMedioPago" id="saikoMedioPago" class="form-control" data-live-search="true" data-size="10" required="required">
                        <option value="">Seleccione</option>
                        <?php
                            foreach ($saikoSelectoresViewModel['mediosPago'] as $val) {
                            ?>
                            <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)"><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                        <?php }?>
                        </select>
                    </div>

                    <br>
                    <div>
                        <input type="hidden" name="accionOferta" value="saiko"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>                                
                </form>
            </div>
        </div>
    </div>
</div>