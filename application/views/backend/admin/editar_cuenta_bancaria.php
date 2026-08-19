<?php $edit_data	=	$this->db->get_where('account_bank' , array('account_bank_id' => $param2))->result_array(); $branch_id = $this->session->userdata("branch_id");
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/cuentas_bancarias/update/'.$param2;?>" method="POST">
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
                    <label>Nombre de la cuenta <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="property" class="form-control" placeholder="Propietario de la cuenta" aria-label="Text input with checkbox" required="" value="<?php echo $row['name_account'];?>"/>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12">
                <div class="form-group">
                    <label>No. de cuenta <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="no_account" class="form-control" aria-label="Text input with checkbox" required="" 
                            value="<?php echo $row['no_account'];?>" <?php if ($row['bank_id'] == 0):?> readonly="true" <?php endif ?>/>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Banco <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control"  id="select_bank" name="bank" disabled="true">
                            <option value="">Seleccionar</option>
                            <?php $this->db->order_by('name', 'ASC');
                            $banks = $this->db->get('bank')->result_array();
                            foreach($banks as $rs):?>
                            <option value="<?php echo $rs['bank_id'];?>" <?php if($row['bank_id'] == $rs['bank_id']) echo "selected";?>><?php echo $rs['name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Tipo de cuenta <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" name="type_account" disabled="">
                            <option value="">Seleccionar</option>
                            <option value="Cheques"   <?php if($row['type'] == 'Cheques')   echo "selected"; ?>>Cuenta de cheques</option>
                            <option value="Ahorro"    <?php if($row['type'] == 'Ahorro')    echo "selected"; ?>>Cuenta de ahorro</option>
                            <option value="Monetaria" <?php if($row['type'] == 'Monetaria') echo "selected"; ?>>Cuenta monetaria</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Moneda <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" disabled=""> 
                            <option value="">Seleccionar</option>
                            <option value="(Q)"     <?php if($row['currency'] == '(Q)')   echo "selected"; ?>>Quetzaltes</option>
                            <option value="($ USD)" <?php if($row['currency'] == '($ USD)')   echo "selected"; ?>>Dolares</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Saldo actual:</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="sld" class="form-control" aria-label="Text input with checkbox" value="<?php echo $row['current_balance'];?>"/>
                    </div>
                </div>
            </div>

            <div class="col-sm-6" id="branch">
                <div class="form-group">
                    <label>Sucursal:</label>
                    <div class="radio-inline">
                        <?php if ($row['bank_id'] != 0):?>
                        <label class="radio radio-success" id="all_branch">
                            <input type="radio" class="form-control" name="branch" value="0" <?php if($row['branch_id'] == 0) echo "checked";?>>
                            <span></span>Todas
                        </label>
                        <?php endif;?>
                        <label class="radio radio-success">
                            <input type="radio" class="form-control" id="current_branch" name="branch" value="<?php echo $branch_id; ?>" <?php if($row['branch_id'] == $branch_id) echo "checked";?>>
                            <span></span>Actual (<?php echo $this->crud_model->getBranch($branch_id); ?>)
                        </label>
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