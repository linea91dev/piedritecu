<?php $edit_data	=	$this->db->get_where('expense' , array('expense_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/egresos/update/'.$param2;?>" method="POST"
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
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Fecha:</b> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="date" value="<?php echo $row['date'];?>" class="form-control"
                            aria-label="Text input with checkbox" name="date">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Monto:</b></label>
                    <div class="input-group">
                        <input type="number" value="<?php echo $row['amount'];?>" step="0.01" class="form-control"
                            aria-label="Text input with checkbox" disabled="true" name="amount">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Responsable:</b> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" name="responsable" required="true">
                            <option value="">Seleccionar</option>
                            <?php $respons = $this->db->get_where('admin', array('status'=>1))->result_array();
                                foreach($respons as $res): ?>
                            <option value="<?php echo $res['admin_id'];?>"
                                <?php if($row['responsable'] == $res['admin_id']) echo 'selected';?>>
                                <?php echo $res['name'].' '.$res['last_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Factura:</b></label>
                    <div class="custom-file">
                        <input type="file" accept="image/*, .pdf" class="custom-file-input" name="factura_img"
                            id="customFile2" onchange="onLoadImage2(event.target.files)">
                        <label class="custom-file-label" for="customFile2" id="imgLabel2">Elegir imagen</label>
                    </div>
                    <small>Archivo seleccionado: <b><span id="imgName2">Niguno</span></b></small>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Entidad a quien compró o pagó:</b> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="provider" value="<?php echo $row['provider'];?>" class="form-control"
                            placeholder="Ej: <?php echo $this->crud_model->getNameSistema(); ?>"
                            aria-label="Text input with checkbox" required="true">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><b>Origen:</b></label>
                    <div class="input-group">
                        <select class="form-control" disabled="true">
                            <option <?php if($row['origin'] == 0) echo "selected";?>>(Caja Chica) Efectivo</option>
                            <?php $accounts = $this->db->get_where('account_bank', array('status' => 1))->result_array();
                            foreach($accounts as $rt):?>
                            <option <?php if($row['origin'] == $rt['account_bank_id']) echo "selected";?>>
                                <?php echo '('.$this->db->get_where('bank', array('bank_id' => $rt['bank_id']))->row()->name.') - '.$rt['name_account'];?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><b>Descripción:</b></label>
                    <div class="input-group">
                        <textarea class="form-control" name="details"
                            aria-label="Text input with checkbox"><?php echo $row['details'];?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <div class="checkbox-inline text-center">
                        <label class="checkbox checkbox-lg checkbox-danger">
                            <input type="checkbox" id="deactivate" name="deactivate" value='1'>
                            <span></span>Anular
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-10" id="reason" style="display: none;">
                <div class="form-group">
                    <div class="input-group">
                        <textarea class="form-control" name="reason" aria-label="Text input with checkbox"
                            placeholder="Motivo de la anulación" rows="4"><?php echo $row['reason']; ?></textarea>
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
function onLoadImage2(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName2')
            .innerHTML = files[0].name;
    } else {
        document
            .getElementById('imgName2')
            .innerHTML = 'Ninguno';
    }
}

$('#deactivate').on('click', function() {
    if ($(this).is(':checked')) {
        $('#reason').show(500);
    } else {
        $('#reason').hide(500);
    }
});
</script>
<?php endforeach; ?>