<?php $edit_data	=	$this->db->get_where('transport' , array('transport_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    
    <form class="form" action="<?php echo base_url().'admin/transporte/update/'.$param2;?>" method="POST">
       
        <div class="modal-body">
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Vehículo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="name_transport" required="" class="form-control" aria-label="Text input with checkbox" value="<?php echo $row['name'];?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Placas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="license_plate" required="" class="form-control" aria-label="Text input with checkbox" value="<?php echo $row['license_plate'];?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Recorrido actual</label>
                        <div class="input-group">
                            <input type="number" name="km" placeholder="km" required="" class="form-control" aria-label="Text input with checkbox" value="<?php echo $row['km'];?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Responsable</label>
                        <div class="input-group">
                            <select class="form-control" required="" name="responsable">
                                <option value="">Seleccionar</option>
                                <?php $employees = $this->db->get_where('admin', array('type' => '2'))->result_array();
                                foreach($employees as $rs):?>
                                <option value="<?php echo $rs['admin_id']?>" <?php if($rs['admin_id'] == $row['responsable']) echo "selected";?>><?php echo $this->crud_model->getName('admin', $rs['admin_id']);?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Próximo servicio</label>
                        <div class="input-group">
                            <input type="text" name="next_service" class="form-control" aria-label="Text input with checkbox" required="" 
                                value="<?php echo date("m/d/Y", strtotime($row['next_service']));?>" id="kt_datepicker_2" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Notas</label>
                        <div class="input-group">
                            <textarea class="form-control" name="notes" aria-label="Text input with checkbox"><?php echo $row['notes'];?></textarea>
                        </div>
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
<script type="text/javascript">
    
    $("#kt_datepicker_2").datepicker({
        language: "es",
        todayHighlight: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

</script>
<?php endforeach; ?>