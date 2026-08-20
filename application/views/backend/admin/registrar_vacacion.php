<?php
$user_id = $this->session->userdata("login_user_id");
$moneda = $this->crud_model->get_info("moneda");
$branch = $this->session->userdata('branch_id');
$employees = $this->db->order_by('name', 'ASC')->get_where('admin', array('status' => 1, 'job !=' => 0))->result_array();

$used_by_employee = array();
$history_by_employee = array();
$vac_rows = $this->db->order_by('date_end', 'DESC')
    ->get_where('vacations', array('branch_id' => $branch, 'status' => 1))
    ->result_array();
foreach ($vac_rows as $vac) {
    $eid = (int) $vac['employee_id'];
    if (!isset($used_by_employee[$eid])) {
        $used_by_employee[$eid] = 0;
        $history_by_employee[$eid] = array();
    }
    $used_by_employee[$eid] += (float) $vac['days'];
    $history_by_employee[$eid][] = array(
        'id' => (int) $vac['vacation_id'],
        'date_start' => $vac['date_start'],
        'date_end' => $vac['date_end'],
        'days' => (float) $vac['days'],
        'type' => $vac['type'],
        'amount' => (float) $vac['amount'],
    );
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Registrar vacación
                            <span class="d-block text-muted pt-2 font-size-sm">
                                Acumulados: (días trabajados × 15) / 365. Se restan los días ya gozados o pagados. Monto: días × (salario / 30).
                            </span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <div class="alert alert-blue">
                            <span class="d-block pt-2 font-size-sm">
                                El cálculo parte desde la fecha de contratación. Un año completo cuenta 365 días (sin sumar un día extra).
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url(); ?>admin/vacaciones/create" method="POST">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="alert alert-custom alert-default" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                                        <div class="alert-text">Los campos marcados con * son obligatorios.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Empleado <span class="text-danger">*</span></label>
                                    <select class="form-control" name="employee_id" id="employee_id" required onchange="onEmployeeChange()">
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($employees as $employee):
                                            $hiring = !empty($employee['hiring']) ? date('Y-m-d', strtotime($employee['hiring'])) : '';
                                            $eid = (int) $employee['admin_id'];
                                            $used = isset($used_by_employee[$eid]) ? $used_by_employee[$eid] : 0;
                                        ?>
                                        <option value="<?php echo $eid; ?>"
                                            data-salary="<?php echo (float) $employee['salary']; ?>"
                                            data-hiring="<?php echo htmlspecialchars($hiring, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-used="<?php echo number_format($used, 3, '.', ''); ?>">
                                            <?php echo $employee['name'].' '.$employee['last_name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted" id="hiring_hint"></small>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Forma <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" id="vacation_type" required onchange="recalcularVacacion()">
                                        <option value="">Seleccionar</option>
                                        <option value="Gozada">Gozada</option>
                                        <option value="Pagada">Pagada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Fecha de inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date_start" id="date_start" required
                                        onchange="recalcularVacacion()">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Fecha final <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date_end" id="date_end" required
                                        onchange="recalcularVacacion()">
                                    <span class="text-danger" id="date_error"></span>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Días trabajados</label>
                                    <input type="number" class="form-control" id="worked_days" value="0" readonly>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Días acumulados</label>
                                    <input type="number" step="0.001" class="form-control" id="accrued_days" value="0" readonly>
                                    <small class="text-muted">(días × 15) / 365</small>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Ya gozados/pagados</label>
                                    <input type="number" step="0.001" class="form-control" id="used_days" value="0" readonly>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Días disponibles</label>
                                    <input type="number" step="0.001" class="form-control" name="days" id="days" value="0" readonly>
                                    <small class="text-muted">Acumulados − historial</small>
                                </div>
                            </div>

                            <div class="col-sm-6" id="amount_group" style="display:none;">
                                <div class="form-group">
                                    <label>Monto a pagar (<?php echo $moneda; ?>)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="amount" id="amount" value="0" readonly>
                                    <small>días disponibles × (salario / 30)</small>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Responsable <span class="text-danger">*</span></label>
                                    <select class="form-control" name="responsable" required>
                                        <option value="">Seleccionar</option>
                                        <?php
                                        $admins = $this->db->get_where('admin', array('type' => 1, 'status' => 1))->result_array();
                                        foreach ($admins as $admin):
                                        ?>
                                        <option value="<?php echo $admin['admin_id']; ?>"
                                            <?php if ($user_id == $admin['admin_id']) echo 'selected'; ?>>
                                            <?php echo $admin['name'].' '.$admin['last_name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Notas</label>
                                    <textarea class="form-control" name="note" rows="3" placeholder="Observaciones del periodo de vacaciones"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12" id="vacation_history_wrap" style="display:none;">
                                <div class="form-group">
                                    <label>Historial de vacaciones del empleado</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Desde</th>
                                                    <th>Hasta</th>
                                                    <th>Tipo</th>
                                                    <th>Días</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody id="vacation_history_body">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="<?php echo base_url().'admin/vacaciones/'; ?>"
                                class="btn btn-light-primary font-weight-bold">Regresar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold" id="submit_vacation">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var vacationHistory = <?php echo json_encode($history_by_employee); ?>;

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

function formatDisplayDate(value) {
    var parts = (value || '').split('-');
    if (parts.length !== 3) return value || '-';
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function getSelectedEmployee() {
    var option = $('#employee_id option:selected');
    return {
        id: option.val() || '',
        salary: parseFloat(option.attr('data-salary')) || 0,
        hiring: option.attr('data-hiring') || '',
        used: parseFloat(option.attr('data-used')) || 0
    };
}

function renderVacationHistory(employeeId) {
    var rows = vacationHistory[employeeId] || vacationHistory[String(employeeId)] || [];
    var body = $('#vacation_history_body');
    body.empty();

    if (!employeeId || !rows.length) {
        $('#vacation_history_wrap').hide();
        return;
    }

    rows.forEach(function(row) {
        body.append(
            '<tr>' +
                '<td>' + formatDisplayDate(row.date_start) + '</td>' +
                '<td>' + formatDisplayDate(row.date_end) + '</td>' +
                '<td>' + row.type + '</td>' +
                '<td>' + Number(row.days).toFixed(3) + '</td>' +
                '<td>' + moneda + Number(row.amount).toFixed(2) + '</td>' +
            '</tr>'
        );
    });
    $('#vacation_history_wrap').show();
}

function onEmployeeChange() {
    var emp = getSelectedEmployee();
    if (emp.hiring) {
        $('#date_start').val(emp.hiring);
        $('#hiring_hint').html('Fecha de contratación: ' + emp.hiring);
        if (!$('#date_end').val()) {
            var today = new Date();
            $('#date_end').val(formatDateUTC(new Date(Date.UTC(today.getFullYear(), today.getMonth(), today.getDate()))));
        }
    } else {
        $('#hiring_hint').html('Sin fecha de contratación registrada.');
    }
    renderVacationHistory(emp.id);
    recalcularVacacion();
}

function recalcularVacacion() {
    var emp = getSelectedEmployee();
    var start = parseDate($('#date_start').val());
    var end = parseDate($('#date_end').val());
    var hiring = parseDate(emp.hiring);
    var isPagada = $('#vacation_type').val() === 'Pagada';

    if (isPagada) {
        $('#amount_group').show();
    } else {
        $('#amount_group').hide();
        $('#amount').val('0.00');
    }

    if (!start || !end) {
        $('#worked_days').val(0);
        $('#accrued_days').val(0);
        $('#used_days').val(emp.used.toFixed(3));
        $('#days').val(0);
        $('#amount').val('0.00');
        return;
    }

    if (hiring && start < hiring) {
        start = hiring;
        $('#date_start').val(formatDateUTC(hiring));
        $('#date_error').html('La fecha de inicio se ajustó a la contratación del empleado.');
    } else {
        $('#date_error').html('');
    }

    if (end < start) {
        $('#date_error').html('La fecha final debe ser igual o posterior a la fecha de inicio.');
        $('#worked_days').val(0);
        $('#accrued_days').val(0);
        $('#used_days').val(emp.used.toFixed(3));
        $('#days').val(0);
        $('#amount').val('0.00');
        $('#submit_vacation').attr('disabled', 'disabled');
        return;
    }

    var startVal = formatDateUTC(start);
    var endVal = formatDateUTC(end);
    var historyRows = vacationHistory[emp.id] || vacationHistory[String(emp.id)] || [];
    var periodExists = historyRows.some(function(row) {
        return row.date_start === startVal && row.date_end === endVal;
    });
    if (periodExists) {
        $('#date_error').html('Este empleado ya tiene un registro con el mismo período. No se puede duplicar.');
        $('#submit_vacation').attr('disabled', 'disabled');
    }

    // Sin +1: un año aniversario = 365 días (366 si el período incluye 29/feb).
    var workedDays = Math.floor((end.getTime() - start.getTime()) / 86400000);
    var accruedDays = Math.round(((workedDays * 15) / 365 + Number.EPSILON) * 1000) / 1000;
    var usedDays = emp.used;
    var vacationDays = Math.round((Math.max(0, accruedDays - usedDays) + Number.EPSILON) * 1000) / 1000;
    var amount = 0;
    if (isPagada) {
        amount = Math.round(((vacationDays * (emp.salary / 30)) + Number.EPSILON) * 100) / 100;
    }

    $('#worked_days').val(workedDays);
    $('#accrued_days').val(accruedDays.toFixed(3));
    $('#used_days').val(usedDays.toFixed(3));
    $('#days').val(vacationDays.toFixed(3));
    $('#amount').val(amount.toFixed(2));
    if (!periodExists) {
        $('#submit_vacation').removeAttr('disabled');
    }
}

$(document).ready(function() {
    $('#employee_id').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    recalcularVacacion();
});
</script>
