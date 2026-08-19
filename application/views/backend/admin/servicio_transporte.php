<?php $edit_data	=	$this->db->get_where('transport' , array('transport_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/transporte_servicios/create_service/'.$param2;?>" method="POST">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-settings-1 text-primary"></i></div>
                        <div class="alert-text">
                            Servicio de transporte:  <b><?php echo $row['name'];?></b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Precio <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" id="precio" name="precio" class="form-control" aria-label="Text input with checkbox" required="" oninput="verificar()"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Lugar de servicio <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="provider" class="form-control" aria-label="Text input with checkbox" required=""/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Forma de pago <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" id="origin" name="origin" onchange="show_opts(this.value), verificar()" required="true">
                            <option value="">Seleccionar</option>
                            <option value="0">Efectivo</option>
                            <option value="1">Cheque</option>
                            <option value="2">Tarjeta Débito</option>
                            <option value="3">Transferencia</option>
                        </select>
                    </div>
                    <span class="text-danger" id="msg_error"></span>
                </div>
            </div>
            <div class="col-sm-12" id="new_cat1">
                <div class="form-group">
                    <label>Cuenta bancaria <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" required="" name="account_bank" id="account_bank" onchange="verificar()">
                            <option value="">Seleccionar</option>
                            <?php $this->db->order_by('name_account', 'ASC');
                            $this->db->where('status', '1');
                            $this->db->where('bank_id !=', '0');
                            $accounts = $this->db->get('account_bank')->result_array();
                            foreach($accounts as $rs):?>
                            <option value="<?php echo $rs['account_bank_id']?>"><?php echo $rs['name_account'].' | '.$this->db->get_where('bank', array('bank_id' => $rs['bank_id']))->row()->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Responsable <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" required name="responsable">
                            <option value="">Seleccionar</option>
                            <?php $employees = $this->db->get_where('admin', array('type' => '2'))->result_array();
                                foreach($employees as $rs):?>
                            <option value="<?php echo $rs['admin_id']?>" <?php if($rs['admin_id'] == $user_id) echo "selected";?>>
                                <?php echo $this->crud_model->getName('admin', $rs['admin_id']);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha aplicación <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="fecha" class="form-control" aria-label="Text input with checkbox" required="" 
                            value="<?php echo date('m/d/Y')?>" id="kt_datepicker_1" readonly />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha próximo servicio <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="fecha_prox" id="kt_datepicker_2" class="form-control" placeholder="mm/dd/aaaa" required="" readonly />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Detalles del servicio</label>
                    <div class="input-group">
                        <textarea class="form-control" aria-label="Text input with checkbox" name="details" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary font-weight-bold" id="success">Agregar</button>
        </div>
    </form>
</div>


<script type="text/javascript">
    
    $("#kt_datepicker_1").datepicker({
        language: "es",
        todayHighlight: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    $("#kt_datepicker_2").datepicker({
        language: "es",
        todayHighlight: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    $('#new_cat1').hide();
    function show_opts(value) {
        if (value > 0) {
            document.getElementById("account_bank").required = true;
            $('#new_cat1').show(500);
        } else {
            document.getElementById("account_bank").required = false;
            $('#new_cat1').hide(500);
        }
    }

    function verificar() {
        let bank_id = 0;
        var metodo = $('#origin').val();
        var cuenta = $('#account_bank').val();
        $total = $('#precio').val();

        if (metodo == 0) {
            bank_id = metodo;
        }
        else{
            bank_id = cuenta;
        }
      
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/saldo_cuenta/',
            data: {
                bank_id: bank_id,
                total: $total,
            },
            success: function(response) {
                
                if (response == 1) {
                    $('#msg_error').html("");
                    document.getElementById('success').disabled = false;
                } else if (response == 2) {
                    $('#msg_error').html('El pago se realizará, pero la cuenta quedara en cero');
                    document.getElementById('success').disabled = false;
                } else if (response == 3) {
                    $('#msg_error').html('La cuenta no tiene los fondos suficientes');
                    document.getElementById('success').disabled = true;
                }
            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    }

</script> 
<?php endforeach; ?>