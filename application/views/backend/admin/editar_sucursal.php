<?php 
    $edit_data	=	$this->db->get_where('branch' , array('branch_id' => $param2))->result_array();
  	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/sucursales/update/'.$param2;?>" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                        <div class="alert-text">
                            Los campos marcados con * son obligatorios.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Sucursal <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='name'
                            required value='<?php echo $row['name'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Correo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" class="form-control" aria-label="Text input with checkbox" name='email'
                            required value='<?php echo $row['email'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Celular</label>
                    <div class="input-group">
                        <input type="tel" class="form-control" aria-label="Text input with checkbox" name='phone'
                            pattern="[0-9]{8}" value='<?php echo $row['phone'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Teléfono <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='tel'
                            required value='<?php echo $row['tel'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Encargado <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" name="manager" required>
                            <option value=''>Seleccionar</option>
                            <?php $manager = $this->db->get_where('admin', array('username !='=>'admin', 'status'=>1)); foreach ($manager->result_array() as $cow):?>
                            <?php if($manager->num_rows() > 0 ):?>
                            <option value="<?php echo $cow['admin_id'];?>"
                                <?php echo ($row['manager']==$cow['admin_id']) ? 'selected' : '' ;?>>
                                <?php echo $cow['name'];?>
                            </option>
                            <?php else:?>
                            <option value="">Sin Datos
                            </option>
                            <?php endif;?>
                            <?php endforeach  ;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Dirección</label>
                    <div class="input-group">
                        <textarea class="form-control" aria-label="Text input with checkbox"
                            name='address'><?php echo $row['address'];?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-buttons-w text-right compact">
            <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary" type="submit"><span>Guardar</span></button>
        </div>
    </form>
</div>
<?php endforeach; ?>