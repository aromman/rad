<div class="modal fade" id="nuevoModal" tabindex="-1" aria-labelledby="nuevoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevoModalLabel">Agregar registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">

                    <div class="mb-3">
                        <label>Apellido</label>
                        <input type="text" name="apellido" class="form-control" required="required">
                    </div>
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required="required">
                    </div>
                    <div class="mb-3">
                        <label>Dni</label>
                        <input type="text" name="dni" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Descuento</label>
                        <select name="descuento" id="descuento" class="form-control" data-live-search="true" data-size="10">
                        <option value="">Seleccione</option>
                        <?php
                            foreach ($descuentosSelectorViewModel['descuentos'] as $val) {
                            ?>
                            <option value="<?php echo $val['id']?>" data-subtext="(<?php echo $val['id']?>)" ><?php echo mb_strtoupper($val['nombre'],'UTF-8')?></option>
                        <?php }?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Es solo Contacto?</label>
                        <select name="soloContacto" id="soloContacto" class="form-control" data-live-search="true" data-size="10" required="required">
                            <option value="">Seleccione</option>
                            <option value="1" data-subtext="(SI)">SI</option>
                            <option value="0" data-subtext="(NO)">NO</option>
                        </select>
                    </div>                                

                    <div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="clientes.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>                                
                </form>
            </div>
        </div>
    </div>
</div>