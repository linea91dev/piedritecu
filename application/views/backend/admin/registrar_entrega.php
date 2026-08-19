<?php $code = $this->crud_model->getCodeEntrega(); 
    $user_id = $this->session->userdata('login_user_id');
    $branch_id = $this->session->userdata('branch_id');
    $moneda = $this->crud_model->get_info("moneda");
    setlocale(LC_TIME, "spanish");
    $sale = $this->db->get_where('sales' , array('code' => $param2))->result_array(); 
    foreach ($sale as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/entregas/create';?>" method="POST">
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
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Código de entrega: </label>
                    <div class="input-group">
                        <input type="text" name="code" class="form-control" value="<?php echo $code; ?>" readonly="true" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Código de venta: </label>
                    <div class="input-group">
                        <input type="text" name="sale_code" class="form-control" value="<?php echo $row['code']; ?>" readonly="true" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Responsable: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" required name="responsable">
                            <option value="">Seleccionar</option>
                            <?php $employees = $this->db->get_where('admin', array('type' => '2', 'status' => 1))->result_array();
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
                    <label>Fecha asignada: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="date" name="shipping_date" class="form-control" value="<?php echo $row['shipping_date']; ?>" required />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de Entrega: <span class="text-danger">*</span></label>
                    <div class="input-group"><?php $actual = date("Y-m-d").'T'.date('H:i')?>
                        <input type="datetime-local" name="delivery_date" value="<?php echo $actual;?>" required class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estado del envío <span class="text-danger">*</span></label>
                    <div class="checkbox-inline text-center">
                        <label class="checkbox checkbox-info checkbox-rounded">
                            <input type="radio" value="en_ruta" name="estado" checked>
                            <span></span>En ruta
                        </label>
                        <label class="checkbox checkbox-success checkbox-rounded">
                            <input type="radio" value="entregado" name="estado">
                            <span></span>Entregado
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Costo de entrega: </label>
                    <div class="input-group">
                        <label class=""><strong><?php echo $moneda.number_format($row['shipping_cost'],2,'.',',');?></strong></label>
                        <input type="hidden" id="cost" name="cost" value="<?php echo $row['shipping_cost'];?>" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Costo adicional: </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo $moneda;?></span>
                        </div>
                        <input type="number" id="cost_extra" name="cost_extra" value="0.00" step="0.1" min="0" class="form-control" oninput="calcular_total()" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Total:</label>
                    <div class="input-group">
                        <label class=""><strong><span id="lbl_total"><?php echo $moneda.number_format($row['shipping_cost'],2,'.',',');?></span></strong></label>
                        <input type="hidden" id="total" name="total" value="<?php echo $row['shipping_cost'];?>" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Servicio transporte: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" required name="transporte" onchange="ver_transporte(this.value)">
                            <option value="">Seleccionar</option>
                            <optgroup label="Externo">
                                <option value="externo">Servicio externo</option>
                            </optgroup>
                            <optgroup label="Interno">
                                <option value="new_interno">Nuevo transporte</option>
                                <?php $transports = $this->db->get_where('transport', array('status' => 1, 'branch_id' => $branch_id))->result_array();
                                    foreach($transports as $tr):?>
                                <option value="<?php echo $tr['transport_id']?>">
                                    <?php echo $tr['name'].' - '.$tr['license_plate'];?>
                                </option>
                                <?php endforeach;?>
                            </optgroup>
                        </select>
                    </div>
                    <br>
                    <div class="input-group" id="new_transport">
                        <input class="form-control" type="text" id="transport_name" name="transport_name" placeholder="Nombre" />
                        <input class="form-control" type="text" id="transport_plate" name="transport_plate" placeholder="Placa" />
                    </div>
                    <div class="input-group" id="servicio_externo">
                        <input class="form-control" type="text" id="delivery_code" name="delivery_code" placeholder="Código" />
                        <select class="form-control" id="list_company" name="list_company">
                            <?php $company = $this->db->get_where('delivery_company', array('status'=>1))->result_array();
                                foreach ($company as $com):?>
                            <option value="<?php echo $com['delivery_company_id'];?>"><?php echo $com['name'];?></option>
                            <?php endforeach; ?>
                        </select>
                        <input class="form-control" type="search" id="company_name" name="company_name" placeholder="Nombre de la empresa"/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <label class="checkbox checkbox-inline">
                                    <input type="checkbox" class="form-control" id="new_company" name="new_company" value="0" />
                                    <span></span> &nbsp; Nuevo
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Forma de pago <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select class="form-control" id="origin" name="origin" onchange="show_accounts(this.value), verificar()" required>
                            <option value="0">Efectivo</option>
                            <option value="1">Cheque</option>
                            <option value="2">Tarjeta Débito</option>
                            <option value="3">Transferencia</option>
                        </select>
                    </div>
                    <br>
                    <label class="cuentas">Cuenta bancaria <span class="text-danger">*</span></label>
                    <div class="input-group cuentas">
                        <select class="form-control" name="account_bank" id="account_bank"  onchange="verificar()">
                            <?php $this->db->order_by('name_account', 'ASC');
                            $this->db->where('status', '1');
                            $this->db->where('bank_id !=', '0');
                            $this->db->group_start();
                            $this->db->where('branch_id', $this->session->userdata('branch_id'));
                            $this->db->or_where('branch_id', 0);
                            $this->db->group_end();
                            $accounts = $this->db->get('account_bank')->result_array();
                            foreach($accounts as $rs):?>
                            <option value="<?php echo $rs['account_bank_id']?>"><?php echo $rs['name_account'].' | '.$this->db->get_where('bank', array('bank_id' => $rs['bank_id']))->row()->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <span class="text-danger" id="msg_error"></div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Dirección: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <?php $direccion = $this->db->get_where('client', array('client_id'=>$row['client_id']))->row()->address?>
                        <textarea name="address" class="form-control" required placeholder="Dirección exacta donde se realizo la entrega"><?php echo $direccion;?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Notas: </label>
                    <div class="input-group">
                        <textarea name="notes" class="form-control" placeholder="Anotaciones sobre la entrega, como el motivo y/o descripción del gasto extra"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary font-weight-bold" id="success" value="Guardar">
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var moneda = '<?php echo $moneda; ?>';

    function calcular_total() {
        let costo = $('#cost').val();
        let costo_extra = $('#cost_extra').val();
        let total = 0;
        total = parseFloat(costo) + parseFloat(costo_extra);
        total_format = custom_number_format(total, '2',);
        $('#total').val(total.toFixed(2));
        $('#lbl_total').html(moneda + total_format);
        verificar();
    }

    $('.cuentas').hide();
    function show_accounts(value) {
        if (value > 0) {
            document.getElementById("account_bank").required = true;
            $('.cuentas').show(500);
        } else {
            document.getElementById("account_bank").required = false;
            $('.cuentas').hide(500);
        }
    }

    $('#new_transport').hide();
    $('#servicio_externo').hide();
    $('#company_name').hide();
    function ver_transporte(value){
        if(value == "new_interno"){
            document.getElementById("transport_name").required = true;
            document.getElementById("transport_plate").required = true;
            document.getElementById("delivery_code").required = false;
            document.getElementById("list_company").required = false;
            document.getElementById("company_name").required = false;
            $('#new_transport').show(500);
            $('#servicio_externo').hide(500);
        } else if(value == "externo"){
            document.getElementById("transport_name").required = false;
            document.getElementById("transport_plate").required = false;
            document.getElementById("delivery_code").required = true;
            document.getElementById("list_company").required = true;
            $('#servicio_externo').show(500);
            $('#new_transport').hide(500);
        }
        else{
            document.getElementById("transport_name").required = false;
            document.getElementById("transport_plate").required = false;
            document.getElementById("delivery_code").required = false;
            document.getElementById("list_company").required = false;
            document.getElementById("company_name").required = false;
            $('#new_transport').hide(500);
            $('#servicio_externo').hide(500);
        }
    }

    $("#new_company").on('change', function() {
        if( $(this).is(':checked') ) {
            document.getElementById("list_company").required = false;
            $('#list_company').hide();
            document.getElementById("company_name").required = true;
            $('#company_name').show();
            $(this).val(1);
        } else {
            document.getElementById("list_company").required = true;
            $('#list_company').show();
            document.getElementById("company_name").required = false;
            $('#company_name').hide();
            $(this).val(0);
        }
    });

    function verificar() {
        let bank_id = 0;
        var metodo = $('#origin').val();
        var cuenta = $('#account_bank').val();
        $total = $('#total').val();

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

    function custom_number_format(number_input, decimals, dec_point, thousands_sep) {
        var number = (number_input + '').replace(/[^0-9+\-Ee.]/g, '');
        var finite_number = !isFinite(+number) ? 0 : +number;
        var finite_decimals = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var seperater = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
        var decimal_pont = (typeof dec_point === 'undefined') ? '.' : dec_point;
        var number_output = '';
        var toFixedFix = function(n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
            } else {
                var arr = ('' + n).split('e');
                let sig = '';
                if (+arr[1] + prec > 0) {
                    sig = '+';
                }
                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
            }
        }
        number_output = (finite_decimals ? toFixedFix(finite_number, finite_decimals).toString() : '' + Math.round(
            finite_number)).split('.');
        if (number_output[0].length > 3) {
            number_output[0] = number_output[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, seperater);
        }
        if ((number_output[1] || '').length < finite_decimals) {
            number_output[1] = number_output[1] || '';
            number_output[1] += new Array(finite_decimals - number_output[1].length + 1).join('0');
        }
        return number_output.join(decimal_pont);
    }
</script>

<?php endforeach; ?>