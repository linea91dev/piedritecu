<?php $edit_data	=	$this->db->get_where('product_details' , array('product_details_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Motivo</label>
                        <div class="input-group">
                            <textarea class="form-control" name="motivo" 
                                > <?php echo ($row['motivo'] !='')? $row['motivo'] : 'Sin motivos'  ;?></textarea>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>
<?php endforeach; ?>

