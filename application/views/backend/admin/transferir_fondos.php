<?php $moneda = $this->crud_model->get_info("moneda");?>
<?php $edit_data	=	$this->db->get_where('account_bank' , array('account_bank_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/cuentas_bancarias/transfer_account/'.$param2;?>" method="POST">
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
                    <label>Monto disponible: &nbsp;&nbsp;<span class="font-weight-bold"><a href="javascript:void(0);"><?php echo $moneda.number_format($row['current_balance'], '2', '.', ',');?></a></span></label>

                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>Elegir a que cuenta transferir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" id="select_bank" name="destiny" required>

                            <option value="">Seleccionar</option>
                            <?php if($param2 != '0'):?>
                            <option value="0">Caja chica</option>
                            <?php endif;?>
                            <?php $this->db->order_by('name_account', 'ASC');
                            $this->db->where('status', '1');
                            $this->db->where('account_bank_id<>', $row['account_bank_id']);
                            $banks = $this->db->get('account_bank')->result_array();
                            foreach($banks as $rs):?>
                            <option value="<?php echo $rs['account_bank_id'];?>"><?php echo $rs['name_account'];?> - Banco:(<?php echo $this->db->get_where('bank', array('bank_id' => $rs['bank_id']))->row()->name?>)</option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>Monto a transferir: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="amount" step="0.01" class="form-control" max="<?php echo $row['current_balance'];?>" aria-label="Text input with checkbox" min="1" max="<?php echo $row['current_balance'];?>" required />
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary font-weight-bold">Transferir</button>
        </div>
    </form>
</div>
<?php endforeach; ?>
