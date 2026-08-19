<?php $edit_data	=	$this->db->get_where('type_product' , array('type_product_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    
    <form class="form" action="<?php echo base_url().'admin/tipos_p/update/'.$param2;?>" method="POST">
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
                    <label>Nombre <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" name="name_type"
                            required value='<?php echo $row['name'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Descripción </label>
                    <div class="input-group">
                        <textarea class="form-control" name="description_type" aria-label="Text input with checkbox"><?php echo $row['description'];?></textarea>
                        <br>
                    </div>
                </div>
            </div>
           
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary font-weight-bold">Actualizar</button>
        </div>
    </form>
</div>
<?php endforeach; ?>