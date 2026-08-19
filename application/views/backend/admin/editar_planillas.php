<?php 
    $moneda = $this->crud_model->get_info("moneda");
    $planilla = $this->crud_model->get_info("planilla");
    $edit_data	=	$this->db->get_where('payroll' , array('payroll_id' => $param2))->result_array(); 
  	foreach ($edit_data as $row):
        if ($row['employee'] != "" || $row['employee'] != null) {
            $employee = json_decode($row['employee'],true);
        } else {
            $employee = array();
        } ;
        $_id = $employee[$i]['employee'];
        $payroll_name = isset($row['payroll_name']) ? $row['payroll_name'] : 'Oficial';
        $is_bonus_edit = in_array($payroll_name, array('Bono 14', 'Aguinaldo'), true);
        $update_action = $is_bonus_edit
            ? base_url().'admin/bonos/update/'.$param2.'/'.$param3
            : base_url().'admin/planillas/update/'.$param2.'/'.$param3;
?>
<form class="form" action="<?php echo $update_action;?>" method="POST"
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
        <input type="hidden" name="planilla" value="<?php echo $planilla;?>"/>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nombre de planilla <span class="text-danger">*</span></label>
                <select class="form-control" name="payroll_name" required>
                    <?php if ($is_bonus_edit): ?>
                    <option value="Bono 14" <?php echo ($payroll_name === 'Bono 14') ? 'selected' : ''; ?>>Bono 14</option>
                    <option value="Aguinaldo" <?php echo ($payroll_name === 'Aguinaldo') ? 'selected' : ''; ?>>Aguinaldo</option>
                    <?php else: ?>
                    <option value="Oficial" <?php echo ($payroll_name === 'Oficial') ? 'selected' : ''; ?>>Planilla oficial</option>
                    <option value="Interna" <?php echo ($payroll_name === 'Interna') ? 'selected' : ''; ?>>Planilla interna</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <?php if($planilla == "mensual"):?>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Mes a pagar: <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="month" class="form-control" name="month" value="<?php echo $row['month_pay'];?>" required readonly/>
                </div>
            </div>
        </div>
        <?php elseif($planilla == "quincenal"):?>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Fechas <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="date" class="form-control" name="date_start" required readonly
                        value="<?php echo $row['date_start'];?>" />
                    <input type="date" class="form-control" name="date_end" required readonly
                        value="<?php echo $row['date_end'];?>" />
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Debitar de <span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-control" name='bank' required disabled>
                        <option value=''>Seleccionar</option>
                        <?php $bancos = $this->db->get_where('account_bank', array('status'=>1, 'bank_id !='=>0))->result_array(); foreach ($bancos as $banco):?>
                        <option value="<?php echo $banco['account_bank_id'];?>"
                            <?php echo ($row['bank'] == $banco['account_bank_id'])? 'selected':'' ;?>>
                            <?php echo '('.$this->db->get_where('bank', array('bank_id'=>$banco['bank_id']))->row()->name.') - '.$banco['name_account']; ?>
                        </option>
                        <?php endforeach;?>
                        <option value="0" <?php if($row['bank'] == 0) echo 'selected'; ?>>Caja chica</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label>Responsable <span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-control" name='responsable' required>
                        <option value=''>Seleccionar</option>
                        <?php $res = $this->db->get_where('admin', array('type'=>1, 'status'=>1))->result_array(); foreach ($res as $re):?>
                        <option value="<?php echo $re['admin_id'] ;?>"
                            <?php echo ($row['responsable'] == $re['admin_id'])?'selected':''?>>
                            <?php echo $re['name'].' '.$re['last_name'];?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>

        <style>
        .tbl td {
            padding: 5px !important;
        }
        </style>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Salario</th>
                            <th>Descuento IGSS</th>
                            <th>ISR</th>
                            <?php if (in_array($payroll_name, array('Oficial', 'Interna'), true)): ?>
                            <th>Descuentos</th>
                            <?php endif; ?>
                            <th>Bonificación decreto</th>
                            <th>Total</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i=0; $i < $row['num_employee'] ; $i++) :?>
                        <?php if($i == $param3):?>
                        <tr>
                            <td>
                                <?php echo $this->crud_model->getName('admin',$employee[$i]['employee']);?>
                                <input type="hidden" name="employee[]" value='<?php echo $employee[$i]['employee'];?>'>

                            </td>
                            <td><span
                                    class="text-info font-weight-bolder"><?php echo $moneda.number_format($employee[$i]['salary'],2,'.',',');?></span>
                                <input type="hidden" name="salary[]" id='salary--<?php echo $_id;?>' min='0' step='0'
                                    value='<?php echo $employee[$i]['salary'];?>' onblur="sum('<?php echo $_id;?>')">
                            </td>

                            <td><input type="number" step="any" class="form-control" style="width:75px" min=0
                                    name='discount[]' id='discount--<?php echo $_id;?>'
                                    onblur="sum('<?php echo $_id;?>')" value='<?php echo $employee[$i]['discount'];?>'>
                            </td>

                            <td><input type="number" step="any" class="form-control" style="width:75px" min='0'
                                    name='advance[]' id='advance--<?php echo $_id;?>' onblur="sum('<?php echo $_id;?>')"
                                    value='<?php echo $employee[$i]['advance']?>'></td>

                            <?php if (in_array($payroll_name, array('Oficial', 'Interna'), true)): ?>
                            <td><input type="number" step="any" class="form-control" style="width:75px" min='0'
                                    name='other_discount[]' id='other_discount--<?php echo $_id;?>' onblur="sum('<?php echo $_id;?>')"
                                    value='<?php echo isset($employee[$i]['other_discount']) ? $employee[$i]['other_discount'] : 0;?>'></td>
                            <?php else: ?>
                            <input type="hidden" name='other_discount[]' id='other_discount--<?php echo $_id;?>' value='0'>
                            <?php endif; ?>

                            <td><input type="number" step="any" class="form-control" style="width:75px" min='0'
                                    name='remuneration[]' id='remuneration--<?php echo $_id;?>' onblur="sum('<?php echo $_id;?>')"
                                    value='<?php echo $employee[$i]['remuneration']?>'></td>

                            <td>
                                <span class="text-danger text-center" id='sub--<?php echo $_id;?>'>
                                    <?php echo $moneda.number_format($employee[$i]['sub'],2,'.',',')?> </span>
                                <input type="hidden" name="sub[]" class='total-' id='subh--<?php echo $_id;?>'
                                    value='<?php echo $employee[$i]['sub'];?>'>
                            </td>
                            <td><textarea rows="1" class="form-control"
                                    name='note[]'><?php echo $employee[$i]['note'];?></textarea></td>

                        </tr>
                        <?php else:?>

                        <input type="hidden" name="employee[]" value='<?php echo $employee[$i]['employee'];?>'>

                        <input type="hidden" name="salary[]" id='salary--<?php echo $_id;?>' min='0' step='0'
                            value='<?php echo $employee[$i]['salary'];?>' onblur="sum('<?php echo $_id;?>')">

                        <input type="hidden" step="0" class="form-control" style="width:75px" min=0 name='discount[]'
                            id='discount--<?php echo $_id;?>' onblur="sum('<?php echo $_id;?>')"
                            value='<?php echo $employee[$i]['discount'];?>'>

                        <input type="hidden" step="0" class="form-control" style="width:75px" min='0' name='advance[]'
                            id='advance--<?php echo $_id;?>' onblur="sum('<?php echo $_id;?>')"
                            value='<?php echo $employee[$i]['advance'];?>'>

                        <input type="hidden" name='other_discount[]' id='other_discount--<?php echo $_id;?>'
                            value='<?php echo (in_array($payroll_name, array('Oficial', 'Interna'), true) && isset($employee[$i]['other_discount'])) ? $employee[$i]['other_discount'] : 0;?>'>

                        <input type="hidden" name='remuneration[]' id='remuneration--<?php echo $_id;?>'
                            value='<?php echo $employee[$i]['remuneration'];?>'>

                        <input type="hidden" class='total-' name="sub[]" id='subh--<?php echo $_id;?>'
                            value='<?php echo $employee[$i]['sub'];?>'>

                        <textarea rows="1" class="form-control" hidden
                            name='note[]'><?php echo $employee[$i]['note'];?></textarea>
                        <?php endif; ?>
                        <?php endfor;?>
                    </tbody>
                    <input type="hidden" name="ttl-" id='ttl-' value='0'>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Actualizar</button>
    </div>
</form>
<?php endforeach; ?>


<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$('document').ready(function() {
    sum();
});

function sum(i) {
    var salary = $('#salary--' + i).val();
    var discount = $('#discount--' + i).val();
    var advance = $('#advance--' + i).val();
    var otherDiscount = $('#other_discount--' + i).val();
    var remuneration = $('#remuneration--' + i).val();
    if (discount == "") {
        discount = 0;
    }
    if (advance == "") {
        advance = 0;
    }
    if (otherDiscount == "" || otherDiscount == null) {
        otherDiscount = 0;
    }
    if (remuneration == "") {
        remuneration = 0;
    }

    var total = parseFloat(salary) - (parseFloat(advance) + parseFloat(discount) + parseFloat(otherDiscount)) + parseFloat(remuneration);
    var total_format = custom_number_format(total, '2',);

    $('#sub--' + i).html(moneda + total_format);
    $('#subh--' + i).val(total);

    var suma = 0;
    $('.total-').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#ttl-').val(suma);
}

function custom_number_format( number_input, decimals, dec_point, thousands_sep ) {
    var number       = ( number_input + '' ).replace( /[^0-9+\-Ee.]/g, '' );
    var finite_number   = !isFinite( +number ) ? 0 : +number;
    var finite_decimals = !isFinite( +decimals ) ? 0 : Math.abs( decimals );
    var seperater     = ( typeof thousands_sep === 'undefined' ) ? ',' : thousands_sep;
    var decimal_pont   = ( typeof dec_point === 'undefined' ) ? '.' : dec_point;
    var number_output   = '';
    var toFixedFix = function ( n, prec ) {
        if( ( '' + n ).indexOf( 'e' ) === -1 ) {
            return +( Math.round( n + 'e+' + prec ) + 'e-' + prec );
        } else {
            var arr = ( '' + n ).split( 'e' );
            let sig = '';
            if ( +arr[1] + prec > 0 ) {
                sig = '+';
            }
            return ( +(Math.round( +arr[0] + 'e' + sig + ( +arr[1] + prec ) ) + 'e-' + prec ) ).toFixed( prec );
        }
    }
    number_output = ( finite_decimals ? toFixedFix( finite_number, finite_decimals ).toString() : '' + Math.round( finite_number ) ).split( '.' );
    if( number_output[0].length > 3 ) {
        number_output[0] = number_output[0].replace( /\B(?=(?:\d{3})+(?!\d))/g, seperater );
    }
    if( ( number_output[1] || '' ).length < finite_decimals ) {
        number_output[1] = number_output[1] || '';
        number_output[1] += new Array( finite_decimals - number_output[1].length + 1 ).join( '0' );
    }
    return number_output.join(decimal_pont );
}
</script>