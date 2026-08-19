<?php
$moneda = $this->crud_model->get_info("moneda");
$user_id = $this->session->userdata("login_user_id");
$year = (int) date('Y');
$ref_start = ($year - 1) . '-07-01';
$ref_end = $year . '-06-30';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Pagar Bono 14 / Aguinaldo
                            <span class="d-block text-muted pt-2 font-size-sm">El período se calcula por empleado. Fórmula: salario × días del período ÷ 365.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo base_url();?>admin/bonos/" class="btn btn-light-primary font-weight-bold">Regresar</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/bonos/create" method="POST">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="alert alert-custom alert-default" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                                        <div class="alert-text">Los campos marcados con * son obligatorios. Ajusta Desde/Hasta por empleado según su fecha de ingreso o días trabajados.</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="planilla" value="mensual"/>
                            <input type="hidden" name="rows" value="0"/>
                            <input type="hidden" name="date_start" id="date_start" value="<?php echo $ref_start;?>"/>
                            <input type="hidden" name="date_end" id="date_end" value="<?php echo $ref_end;?>"/>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Tipo de pago <span class="text-danger">*</span></label>
                                    <select class="form-control" name="payroll_name" id="payroll_name" required onchange="aplicarPeriodoReferencia()">
                                        <option value="">Seleccionar</option>
                                        <option value="Bono 14" selected>Bono 14</option>
                                        <option value="Aguinaldo">Aguinaldo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Período de referencia</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Desde</span></div>
                                        <input type="date" class="form-control" id="ref_date_start" value="<?php echo $ref_start;?>" onchange="aplicarPeriodoReferencia()"/>
                                        <div class="input-group-prepend"><span class="input-group-text">Hasta</span></div>
                                        <input type="date" class="form-control" id="ref_date_end" value="<?php echo $ref_end;?>" onchange="aplicarPeriodoReferencia()"/>
                                    </div>
                                    <small class="text-muted">Se usa para sugerir fechas; cada empleado puede tener su propio rango.</small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Debitar de <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bank" name="bank" required>
                                        <option value="">Seleccionar</option>
                                        <?php $bancos = $this->db->get_where('account_bank', array('status'=>1, 'bank_id !='=>0))->result_array(); foreach ($bancos as $banco):?>
                                        <option value="<?php echo $banco['account_bank_id'];?>">
                                            <?php echo '('.$this->db->get_where('bank', array('bank_id'=>$banco['bank_id']))->row()->name.') - '.$banco['name_account'];?>
                                        </option>
                                        <?php endforeach;?>
                                        <option value="0">Caja chica</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Responsable <span class="text-danger">*</span></label>
                                    <select class="form-control" name="responsable" required>
                                        <option value="">Seleccionar</option>
                                        <?php $res = $this->db->get_where('admin', array('type'=>1, 'status'=>1))->result_array(); foreach ($res as $re):?>
                                        <option value="<?php echo $re['admin_id'];?>" <?php if($user_id == $re['admin_id']) echo 'selected';?>>
                                            <?php echo $re['name'].' '.$re['last_name'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Empleado</th>
                                                <th>Desde</th>
                                                <th>Hasta</th>
                                                <th>Salario proporcional</th>
                                                <th>Total</th>
                                                <th>Notas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $users = $this->db->get_where('admin', array('status'=>1,'job !=' => 0))->result_array();
                                            foreach ($users as $user):
                                                $_id = $user['admin_id'];
                                                $salary_base = max(0, (float) $user['salary']);
                                                $hiring = !empty($user['hiring']) ? date('Y-m-d', strtotime($user['hiring'])) : '';
                                                $emp_start = $ref_start;
                                                if ($hiring && $hiring > $ref_start && $hiring <= $ref_end) {
                                                    $emp_start = $hiring;
                                                }
                                                $emp_end = $ref_end;
                                                $salario = $this->crud_model->calculate_bonus_salary($salary_base, $emp_start, $emp_end);
                                            ?>
                                            <tr class="bonus-row" data-employee-id="<?php echo $_id;?>">
                                                <td>
                                                    <?php echo $user['name'].' '.$user['last_name'];?>
                                                    <input type="hidden" name="employee[]" value="<?php echo $_id;?>">
                                                    <input type="hidden" name="discount[]" value="0">
                                                    <input type="hidden" name="advance[]" value="0">
                                                    <input type="hidden" name="other_discount[]" value="0">
                                                    <input type="hidden" name="remuneration[]" value="0">
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control emp-date-start"
                                                        style="width:145px" name="emp_date_start[]"
                                                        id="emp_date_start-<?php echo $_id;?>"
                                                        data-hiring="<?php echo htmlspecialchars($hiring, ENT_QUOTES, 'UTF-8');?>"
                                                        value="<?php echo $emp_start;?>"
                                                        onchange="recalcularEmpleado(<?php echo $_id;?>)" required>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control emp-date-end"
                                                        style="width:145px" name="emp_date_end[]"
                                                        id="emp_date_end-<?php echo $_id;?>"
                                                        value="<?php echo $emp_end;?>"
                                                        onchange="recalcularEmpleado(<?php echo $_id;?>)" required>
                                                </td>
                                                <td>
                                                    <span id="salary-display-<?php echo $_id;?>" class="text-info font-weight-bolder">
                                                        <?php echo $moneda.number_format($salario,2,'.',',');?>
                                                    </span>
                                                    <input type="hidden" name="salary[]" id="salary-<?php echo $_id;?>"
                                                        data-monthly-salary="<?php echo $salary_base;?>"
                                                        value="<?php echo number_format($salario, 2, '.', '');?>">
                                                </td>
                                                <td>
                                                    <span class="text-danger font-weight-bold" id="sub-<?php echo $_id;?>">
                                                        <?php echo $moneda.number_format($salario,2,'.',',');?>
                                                    </span>
                                                    <input type="hidden" class="total" name="sub[]" id="subh-<?php echo $_id;?>"
                                                        value="<?php echo number_format($salario, 2, '.', '');?>">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="note[]"></textarea>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right" colspan="2">
                                                    <h4><b>TOTAL PLANILLA</b><br>
                                                        <span class="text-danger" id="total"></span>
                                                    </h4>
                                                    <input type="hidden" name="ttl" id="ttl" value="0">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="<?php echo base_url();?>admin/bonos/" class="btn btn-light-primary font-weight-bold">Regresar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold" id="submit_bono">Pagar Bono</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$(document).ready(function() {
    aplicarPeriodoReferencia();
    actualizarTotalBono();
});

function parseDate(value) {
    var parts = (value || '').split('-');
    if (parts.length !== 3) return null;
    var date = new Date(Date.UTC(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10)));
    if (date.getUTCFullYear() !== parseInt(parts[0], 10) ||
        date.getUTCMonth() !== parseInt(parts[1], 10) - 1 ||
        date.getUTCDate() !== parseInt(parts[2], 10)) {
        return null;
    }
    return date;
}

function formatDateUTC(date) {
    var y = date.getUTCFullYear();
    var m = ('0' + (date.getUTCMonth() + 1)).slice(-2);
    var d = ('0' + date.getUTCDate()).slice(-2);
    return y + '-' + m + '-' + d;
}

function calcularBonoProporcional(salarioMensual, desde, hasta) {
    var start = parseDate(desde);
    var end = parseDate(hasta);
    salarioMensual = parseFloat(salarioMensual) || 0;
    if (!start || !end || end < start || salarioMensual <= 0) {
        return 0;
    }
    var days = Math.floor((end.getTime() - start.getTime()) / 86400000) + 1;
    if (days >= 365) {
        return Math.round((salarioMensual + Number.EPSILON) * 100) / 100;
    }
    return Math.round(((salarioMensual * days / 365) + Number.EPSILON) * 100) / 100;
}

function aplicarPeriodoReferencia() {
    var tipo = $('#payroll_name').val();
    var year = new Date().getFullYear();
    var refStart = $('#ref_date_start').val();
    var refEnd = $('#ref_date_end').val();

    if (tipo === 'Bono 14' && !refStart) {
        refStart = (year - 1) + '-07-01';
        refEnd = year + '-06-30';
        $('#ref_date_start').val(refStart);
        $('#ref_date_end').val(refEnd);
    } else if (tipo === 'Aguinaldo') {
        var aguinaldoStart = (year - 1) + '-12-01';
        var aguinaldoEnd = year + '-11-30';
        if ($('#ref_date_start').data('last-tipo') !== 'Aguinaldo') {
            refStart = aguinaldoStart;
            refEnd = aguinaldoEnd;
            $('#ref_date_start').val(refStart);
            $('#ref_date_end').val(refEnd);
        }
    } else if (tipo === 'Bono 14' && $('#ref_date_start').data('last-tipo') === 'Aguinaldo') {
        refStart = (year - 1) + '-07-01';
        refEnd = year + '-06-30';
        $('#ref_date_start').val(refStart);
        $('#ref_date_end').val(refEnd);
    }

    $('#ref_date_start').data('last-tipo', tipo);
    refStart = $('#ref_date_start').val();
    refEnd = $('#ref_date_end').val();
    $('#date_start').val(refStart);
    $('#date_end').val(refEnd);

    var refStartDate = parseDate(refStart);
    var refEndDate = parseDate(refEnd);

    $('.bonus-row').each(function() {
        var employeeId = $(this).attr('data-employee-id');
        var startInput = $('#emp_date_start-' + employeeId);
        var endInput = $('#emp_date_end-' + employeeId);
        var hiring = startInput.attr('data-hiring');
        var empStart = refStart;
        var hiringDate = parseDate(hiring);
        if (hiringDate && refStartDate && refEndDate && hiringDate > refStartDate && hiringDate <= refEndDate) {
            empStart = formatDateUTC(hiringDate);
        }
        startInput.val(empStart);
        endInput.val(refEnd);
        recalcularEmpleado(employeeId, false);
    });
    actualizarTotalBono();
}

function recalcularEmpleado(i, actualizar) {
    var salaryInput = $('#salary-' + i);
    var desde = $('#emp_date_start-' + i).val();
    var hasta = $('#emp_date_end-' + i).val();
    var salary = calcularBonoProporcional(salaryInput.attr('data-monthly-salary'), desde, hasta);
    salaryInput.val(salary.toFixed(2));
    $('#salary-display-' + i).html(moneda + custom_number_format(salary, 2));
    sumarBono(i, actualizar !== false);
}

function sumarBono(i, actualizar) {
    var salary = parseFloat($('#salary-' + i).val()) || 0;
    var total = Math.round((salary + Number.EPSILON) * 100) / 100;
    $('#sub-' + i).html(moneda + custom_number_format(total, 2));
    $('#subh-' + i).val(total.toFixed(2));
    if (actualizar !== false) {
        actualizarTotalBono();
    }
}

function actualizarTotalBono() {
    var suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val()) || 0;
    });
    suma = Math.round((suma + Number.EPSILON) * 100) / 100;
    $('#total').html(moneda + custom_number_format(suma, 2));
    $('#ttl').val(suma.toFixed(2));
    if (suma > 0) {
        $('#submit_bono').removeAttr('disabled');
    } else {
        $('#submit_bono').attr('disabled', 'disabled');
    }
}

function custom_number_format(number_input, decimals, dec_point, thousands_sep) {
    var number = (number_input + '').replace(/[^0-9+\-Ee.]/g, '');
    var finite_number = !isFinite(+number) ? 0 : +number;
    var finite_decimals = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    var seperater = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var decimal_pont = (typeof dec_point === 'undefined') ? '.' : dec_point;
    var toFixedFix = function(n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
        }
        var arr = ('' + n).split('e');
        var sig = (+arr[1] + prec > 0) ? '+' : '';
        return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
    };
    var number_output = (finite_decimals ? toFixedFix(finite_number, finite_decimals).toString() : '' + Math.round(finite_number)).split('.');
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
