<?php 
$moneda = $this->crud_model->get_info("moneda");
$user_id = $this->session->userdata("login_user_id");
$planilla = $this->crud_model->get_info("planilla");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar planillas
                            <span class="d-block text-muted pt-2 font-size-sm">Administra la información de tus
                                proveedores.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <div class="alert alert-blue">
                            <span class="d-block pt-2 font-size-sm">En este módulo puedes pagar las planillas de los empleados, las opciones de descuento y adelantos restan del salario, la columna de remuneración agrega cantidad al salario, la fecha de pago cambiara según la configuración establecida.
                                Si es el primer registro de planillas, te dejará colocar la fecha, caso contrario el sistema calculará las siguientes fechas de pago, las cuales no podrán cambiar, por favor verifica la primer fecha de pago.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/planillas/create" method="POST"
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
                            <?php
                                $this->db->order_by('payroll_id', 'DESC');
                                $this->db->group_start();
                                $this->db->where_in('payroll_name', array('Oficial', 'Interna'));
                                $this->db->or_where('payroll_name IS NULL', null, false);
                                $this->db->or_where('payroll_name', '');
                                $this->db->group_end();
                                $data = $this->db->get('payroll');
                                $last_pay = $data->first_row('array');
                                if ($data->num_rows() > 0) {
                                    $last_date = $last_pay['date_end']; 
                                    $date_start_pay = date("Y-m-d", strtotime($last_date."+ 1 day"));
                                    $date_end_pay = date("Y-m-d", strtotime($last_date."+ 15 day"));
                                    $month_pay = date("Y-m", strtotime($date_start_pay));
                                } else {
                                    $date_start_pay = date("Y-m-01");
                                    $date_end_pay = date("Y-m-15");
                                    $month_pay = date("Y-m");
                                }
                                ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Rango de fechas a pagar: <span class="text-danger">*</span>
                                        <small class="text-muted" id="payroll_days"></small>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Desde</span></div>
                                        <input type="date" class="form-control" id="date_start" name="date_start" required
                                            onchange="recalcularPlanilla()" 
                                            value="<?php echo $date_start_pay;?>" />
                                        <div class="input-group-prepend"><span class="input-group-text">Hasta</span></div>
                                        <input type="date" class="form-control" id="date_end" name="date_end" required
                                            onchange="recalcularPlanilla()"
                                            value="<?php echo $date_end_pay;?>" />
                                    </div>
                                    <span class="text-danger" id="date_error"></span>
                                </div>
                                <input type="hidden" name="month" value="<?php echo $month_pay;?>" />
                            </div>
                            <input type="hidden" name="rows" value="<?php echo $data->num_rows();?>" />
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Nombre de planilla <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="payroll_name" id="payroll_name" required onchange="toggleDescuentosOficial()">
                                            <option value="">Seleccionar</option>
                                            <option value="Oficial">Planilla oficial</option>
                                            <option value="Interna">Planilla interna</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Debitar de <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" id="bank" name='bank' required>
                                            <option value=''>Seleccionar</option>
                                            <?php $bancos = $this->db->get_where('account_bank', array('status'=>1, 'bank_id !='=>0))->result_array(); foreach ($bancos as $banco):?>
                                            <option value="<?php echo $banco['account_bank_id'];?>">
                                                <?php echo '('.$this->db->get_where('bank', array('bank_id'=>$banco['bank_id']))->row()->name.') - '.$banco['name_account'];?>
                                            </option>
                                            <?php endforeach;?>
                                            <option value="0">Caja chica</option>
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
                                            <option value="<?php echo $re['admin_id'] ;?>" <?php if($user_id == $re['admin_id']) echo 'selected';?>>
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
                                                <th>Salario proporcional</th>
                                                <th>Descuento IGSS</th>
                                                <th>ISR</th>
                                                <th class="col-descuento-oficial" style="display:none;">Descuentos</th>
                                                <th>Bonificación decreto</th>
                                                <th>Total</th>
                                                <th>Notas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $users = $this->db->get_where('admin', array('status'=>1,'job !=' => 0 ))->result_array(); foreach ($users as $user): $_id = $user['admin_id'];?>

                                            <tr class="payroll-row" data-employee-id="<?php echo $_id;?>">
                                                <td><?php echo $user['name'].' '.$user['last_name'];?>
                                                    <input type="hidden" name='employee[]'
                                                        value="<?php echo  $user['admin_id'];?>">
                                                </td>
                                                <td><?php
                                                    $salary_base = max(0, (float) $user['salary']);
                                                    $complemento_base = max(0, (float) (isset($user['complemento']) ? $user['complemento'] : 0));
                                                    $salario_mensual = $this->crud_model->get_employee_base_salary($user, 'Oficial');
                                                    $salario = $this->crud_model->calculate_proportional_salary($salario_mensual, $date_start_pay, $date_end_pay);
                                                    ?>
                                                    <span id="salary-display-<?php echo $_id;?>"
                                                        class="text-info font-weight-bolder"><?php echo $moneda.number_format($salario,2,'.',',');?></span>
                                                    <input type="hidden" name="salary[]" id='salary-<?php echo $_id;?>'
                                                        data-monthly-salary="<?php echo $salary_base;?>"
                                                        data-complemento="<?php echo $complemento_base;?>"
                                                        min="0" step="any" value='<?php echo $salario;?>'
                                                        onblur="sumar(<?php echo $_id;?>)">
                                                </td>
                                                <td><input type="number" step="any" class="form-control"
                                                        style="width:75px" min="0" name='discount[]'
                                                        id='discount-<?php echo $_id;?>'
                                                        onblur='sumar(<?php echo $_id;?>)' value='0'></td>

                                                <td><input type="number" step="any" class="form-control"
                                                        style="width:75px" min="0" name='advance[]'
                                                        id='advance-<?php echo $_id;?>'
                                                        onblur='sumar(<?php echo $_id;?>)' value='0'></td>

                                                <td class="col-descuento-oficial" style="display:none;">
                                                    <input type="number" step="any" class="form-control other-discount-input"
                                                        style="width:75px" min="0" name='other_discount[]'
                                                        id='other_discount-<?php echo $_id;?>'
                                                        onblur='sumar(<?php echo $_id;?>)' value='0'>
                                                </td>

                                                <td><input type="number" step="any" class="form-control"
                                                        style="width:75px" min="0" name='remuneration[]'
                                                        id='remuneration-<?php echo $_id;?>'
                                                        onblur='sumar(<?php echo $_id;?>)' value='0'></td>

                                                <td>
                                                    <span class="text-danger text-center" class='total'
                                                        id='sub-<?php echo $_id;?>'>
                                                        <?php echo $moneda.number_format($salario,2,'.',',');?>
                                                    </span>
                                                    <input type="hidden" class='total' name="sub[]" step="any"
                                                        id='subh-<?php echo $_id;?>'
                                                        value='<?php echo $salario;?>'>
                                                </td>
                                                <td><textarea rows="1" class="form-control" name='note[]'></textarea>
                                                </td>
                                            </tr>

                                            <?php endforeach;?>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="col-descuento-oficial" style="display:none;"></td>
                                                <td></td>
                                                <td class="text-right"></td>
                                                <td class="text-right">
                                                    <h4><b>TOTAL PLANILLA</b><br> 
                                                        <span class="text-danger"id="total"></span>
                                                    </h4>
                                                    <input type="hidden" name="ttl" id='ttl' value='0'>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="<?php echo base_url().'admin/planillas/'?>" type="button"
                                class="btn btn-light-primary font-weight-bold">Regresar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold" id="submit_planilla">Guardar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var lastPayrollDateRange = '';

$(document).ready(function() {
    toggleDescuentosOficial();
    recalcularPlanilla();
    $('#date_start, #date_end').on('input change', recalcularPlanilla);
    $('#payroll_name').on('change', function() {
        toggleDescuentosOficial();
        recalcularPlanilla();
    });

    document.getElementById('date_start').addEventListener('blur', recalcularPlanilla);
    document.getElementById('date_end').addEventListener('blur', recalcularPlanilla);

    setInterval(function() {
        var currentRange = document.getElementById('date_start').value + '|' +
            document.getElementById('date_end').value;

        if (currentRange !== lastPayrollDateRange) {
            recalcularPlanilla();
        }
    }, 300);
});

function isPlanillaConDescuentos() {
    var tipo = $('#payroll_name').val();
    return tipo === 'Oficial' || tipo === 'Interna';
}

function getMonthlyBaseForPayroll(salaryInput) {
    if ($('#payroll_name').val() === 'Interna') {
        return salaryInput.attr('data-complemento') || 0;
    }
    return salaryInput.attr('data-monthly-salary') || 0;
}

function toggleDescuentosOficial() {
    if (isPlanillaConDescuentos()) {
        $('.col-descuento-oficial').show();
    } else {
        $('.col-descuento-oficial').hide();
        $('.other-discount-input').val(0);
    }
}

function parseDate(value) {
    var parts = value.split('-');
    if (parts.length !== 3) {
        return null;
    }

    var date = new Date(Date.UTC(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10)));
    if (date.getUTCFullYear() !== parseInt(parts[0], 10) ||
        date.getUTCMonth() !== parseInt(parts[1], 10) - 1 ||
        date.getUTCDate() !== parseInt(parts[2], 10)) {
        return null;
    }

    return date;
}

function countPayrollQuincenas(start, end) {
    if (!start || !end || end < start) {
        return 0;
    }

    var count = 0;
    var cursor = new Date(Date.UTC(start.getUTCFullYear(), start.getUTCMonth(), 1));

    while (cursor <= end) {
        var year = cursor.getUTCFullYear();
        var month = cursor.getUTCMonth();
        var q1Start = new Date(Date.UTC(year, month, 1));
        var q1End = new Date(Date.UTC(year, month, 15));
        var q2Start = new Date(Date.UTC(year, month, 16));
        var q2End = new Date(Date.UTC(year, month + 1, 0));

        if (start <= q1End && end >= q1Start) {
            count++;
        }
        if (start <= q2End && end >= q2Start) {
            count++;
        }

        cursor = new Date(Date.UTC(year, month + 1, 1));
    }

    return count;
}

function calcularSalarioProporcional(salarioMensual, desde, hasta) {
    var start = parseDate(desde);
    var end = parseDate(hasta);
    salarioMensual = parseFloat(salarioMensual) || 0;

    if (!start || !end || end < start || salarioMensual <= 0) {
        return 0;
    }

    // Mes comercial 360/12 = 30 días; cada quincena paga exactamente 15 días.
    var quincenas = countPayrollQuincenas(start, end);
    var days = quincenas * 15;
    var total = (salarioMensual / 30) * days;

    return Math.round((total + Number.EPSILON) * 100) / 100;
}

function recalcularPlanilla() {
    var desde = $('#date_start').val();
    var hasta = $('#date_end').val();
    lastPayrollDateRange = desde + '|' + hasta;
    var start = parseDate(desde);
    var end = parseDate(hasta);

    if (!start || !end || end < start) {
        $('#date_error').html('La fecha Hasta debe ser igual o posterior a la fecha Desde.');
        $('#payroll_days').html('');
        $('#submit_planilla').attr('disabled', 'disabled');
        return;
    }

    $('#date_error').html('');
    var quincenas = countPayrollQuincenas(start, end);
    var paidDays = quincenas * 15;
    $('#payroll_days').html('(' + quincenas + (quincenas === 1 ? ' quincena' : ' quincenas') + ' · ' + paidDays + ' días)');
    $('.payroll-row').each(function() {
        var employeeId = $(this).attr('data-employee-id');
        var salaryInput = $('#salary-' + employeeId);
        var monthlyBase = getMonthlyBaseForPayroll(salaryInput);
        var salary = calcularSalarioProporcional(monthlyBase, desde, hasta);

        salaryInput.val(salary.toFixed(2));
        $('#salary-display-' + employeeId).html(moneda + custom_number_format(salary, 2));
        sumar(employeeId, false);
    });

    actualizarTotal();
}

function sumar(i, actualizar) {
    var salary = parseFloat($('#salary-' + i).val()) || 0;
    var discount = $('#discount-' + i).val();
    var advance = $('#advance-' + i).val();
    var otherDiscount = $('#other_discount-' + i).val();
    var remuneration = $('#remuneration-' + i).val();
    if (discount == "") {
        discount = 0;
    }
    if (advance == "") {
        advance = 0;
    }
    if (otherDiscount == "" || !isPlanillaConDescuentos()) {
        otherDiscount = 0;
        $('#other_discount-' + i).val(0);
    }
    if (remuneration == "") {
        remuneration = 0;
    }

    var total = salary - (parseFloat(advance) + parseFloat(discount) + parseFloat(otherDiscount)) + parseFloat(remuneration);
    total = Math.round((total + Number.EPSILON) * 100) / 100;
    var total_format = custom_number_format(total, 2);

    $('#sub-' + i).html(moneda + total_format);
    $('#subh-' + i).val(total.toFixed(2));

    if (actualizar !== false) {
        actualizarTotal();
    }
}

function actualizarTotal() {
    var suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val()) || 0;
    });
    suma = Math.round((suma + Number.EPSILON) * 100) / 100;
    var suma_format = custom_number_format(suma, 2);
    $('#total').html(moneda + suma_format);
    $('#ttl').val(suma.toFixed(2));
    verificar();
}

function verificar() {
    var total = parseFloat($('#ttl').val());
    if (!isFinite(total) || total <= 0 || $('#date_error').html() !== '') {
        $('#submit_planilla').attr('disabled', 'disabled');
    } else {
        $('#submit_planilla').removeAttr('disabled');
    }
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