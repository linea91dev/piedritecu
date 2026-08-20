<?php
$moneda = $this->crud_model->get_info("moneda");
$row = $this->db->get_where('vacations', array('vacation_id' => $param2))->row_array();
$employees = $this->db->order_by('name', 'ASC')->get_where('admin', array('status' => 1, 'job !=' => 0))->result_array();
$current = null;
foreach ($employees as $employee) {
    if ((int) $employee['admin_id'] === (int) $row['employee_id']) {
        $current = $employee;
        break;
    }
}
$current_hiring = ($current && !empty($current['hiring'])) ? date('Y-m-d', strtotime($current['hiring'])) : '';
$eff_start = max($row['date_start'], $current_hiring ?: $row['date_start']);
$worked_days = $this->crud_model->calculate_vacation_worked_days($eff_start, $row['date_end']);
$accrued_days = $worked_days > 0 ? round(($worked_days * 15) / 365, 3) : 0;
$used_days = $this->crud_model->get_used_vacation_days($row['employee_id'], $param2);
$available_days = round(max(0, $accrued_days - $used_days), 3);

$used_by_employee = array();
$history_by_employee = array();
$vac_rows = $this->db->order_by('date_end', 'DESC')
    ->get_where('vacations', array('status' => 1))
    ->result_array();
foreach ($vac_rows as $vac) {
    if ((int) $vac['vacation_id'] === (int) $param2) {
        continue;
    }
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
<form class="form" action="<?php echo base_url().'admin/vacaciones/update/'.$param2; ?>" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Acumulados = (días trabajados × 15) / 365. Disponibles = acumulados − historial (sin este registro). Año = 365 días.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Empleado <span class="text-danger">*</span></label>
                <select class="form-control" name="employee_id" id="edit_employee_id" required onchange="onEditEmployeeChange()">
                    <?php foreach ($employees as $employee):
                        $hiring = !empty($employee['hiring']) ? date('Y-m-d', strtotime($employee['hiring'])) : '';
                        $eid = (int) $employee['admin_id'];
                        $used = isset($used_by_employee[$eid]) ? $used_by_employee[$eid] : 0;
                    ?>
                    <option value="<?php echo $eid; ?>"
                        data-salary="<?php echo (float) $employee['salary']; ?>"
                        data-hiring="<?php echo htmlspecialchars($hiring, ENT_QUOTES, 'UTF-8'); ?>"
                        data-used="<?php echo number_format($used, 3, '.', ''); ?>"
                        <?php echo ($row['employee_id'] == $employee['admin_id']) ? 'selected' : ''; ?>>
                        <?php echo $employee['name'].' '.$employee['last_name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted" id="edit_hiring_hint">
                    <?php echo $current_hiring ? ('Fecha de contratación: '.$current_hiring) : 'Sin fecha de contratación registrada.'; ?>
                </small>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Forma <span class="text-danger">*</span></label>
                <select class="form-control" name="type" id="edit_vacation_type" required onchange="recalcularEditVacacion()">
                    <option value="Gozada" <?php echo ($row['type'] === 'Gozada') ? 'selected' : ''; ?>>Gozada</option>
                    <option value="Pagada" <?php echo ($row['type'] === 'Pagada') ? 'selected' : ''; ?>>Pagada</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>Fecha de inicio <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date_start" id="edit_date_start" required
                    value="<?php echo $row['date_start']; ?>" onchange="recalcularEditVacacion()">
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>Fecha final <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date_end" id="edit_date_end" required
                    value="<?php echo $row['date_end']; ?>" onchange="recalcularEditVacacion()">
                <span class="text-danger" id="edit_date_error"></span>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Días trabajados</label>
                <input type="number" class="form-control" id="edit_worked_days" value="<?php echo (int) $worked_days; ?>" readonly>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Días acumulados</label>
                <input type="number" step="0.001" class="form-control" id="edit_accrued_days"
                    value="<?php echo number_format($accrued_days, 3, '.', ''); ?>" readonly>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Ya gozados/pagados</label>
                <input type="number" step="0.001" class="form-control" id="edit_used_days"
                    value="<?php echo number_format($used_days, 3, '.', ''); ?>" readonly>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>Días disponibles</label>
                <input type="number" step="0.001" class="form-control" name="days" id="edit_days"
                    value="<?php echo number_format($available_days, 3, '.', ''); ?>" readonly>
            </div>
        </div>

        <div class="col-sm-6" id="edit_amount_group" style="<?php echo ($row['type'] === 'Pagada') ? '' : 'display:none;'; ?>">
            <div class="form-group">
                <label>Monto a pagar (<?php echo $moneda; ?>)</label>
                <input type="number" step="0.01" min="0" class="form-control" name="amount" id="edit_amount"
                    value="<?php echo number_format((float) $row['amount'], 2, '.', ''); ?>" readonly>
                <small>días disponibles × (salario / 30)</small>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Responsable <span class="text-danger">*</span></label>
                <select class="form-control" name="responsable" required>
                    <?php
                    $admins = $this->db->get_where('admin', array('type' => 1, 'status' => 1))->result_array();
                    foreach ($admins as $admin):
                    ?>
                    <option value="<?php echo $admin['admin_id']; ?>"
                        <?php echo ($row['responsable'] == $admin['admin_id']) ? 'selected' : ''; ?>>
                        <?php echo $admin['name'].' '.$admin['last_name']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label>Notas</label>
                <textarea class="form-control" name="note" rows="3"><?php echo $row['note']; ?></textarea>
            </div>
        </div>

        <div class="col-sm-12" id="edit_vacation_history_wrap" style="display:none;">
            <div class="form-group">
                <label>Historial previo del empleado</label>
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
                        <tbody id="edit_vacation_history_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="edit_submit_vacation">Guardar</button>
    </div>
</form>

<script type="text/javascript">
var editMoneda = '<?php echo $moneda; ?>';
var editVacationHistory = <?php echo json_encode($history_by_employee); ?>;

function parseEditDate(value) {
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

function formatEditDateUTC(date) {
    var y = date.getUTCFullYear();
    var m = ('0' + (date.getUTCMonth() + 1)).slice(-2);
    var d = ('0' + date.getUTCDate()).slice(-2);
    return y + '-' + m + '-' + d;
}

function formatEditDisplayDate(value) {
    var parts = (value || '').split('-');
    if (parts.length !== 3) return value || '-';
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function getEditEmployee() {
    var option = $('#edit_employee_id option:selected');
    return {
        id: option.val() || '',
        salary: parseFloat(option.attr('data-salary')) || 0,
        hiring: option.attr('data-hiring') || '',
        used: parseFloat(option.attr('data-used')) || 0
    };
}

function renderEditVacationHistory(employeeId) {
    var rows = editVacationHistory[employeeId] || editVacationHistory[String(employeeId)] || [];
    var body = $('#edit_vacation_history_body');
    body.empty();
    if (!employeeId || !rows.length) {
        $('#edit_vacation_history_wrap').hide();
        return;
    }
    rows.forEach(function(row) {
        body.append(
            '<tr>' +
                '<td>' + formatEditDisplayDate(row.date_start) + '</td>' +
                '<td>' + formatEditDisplayDate(row.date_end) + '</td>' +
                '<td>' + row.type + '</td>' +
                '<td>' + Number(row.days).toFixed(3) + '</td>' +
                '<td>' + editMoneda + Number(row.amount).toFixed(2) + '</td>' +
            '</tr>'
        );
    });
    $('#edit_vacation_history_wrap').show();
}

function onEditEmployeeChange() {
    var emp = getEditEmployee();
    if (emp.hiring) {
        $('#edit_hiring_hint').html('Fecha de contratación: ' + emp.hiring);
        if (!$('#edit_date_start').val() || $('#edit_date_start').val() < emp.hiring) {
            $('#edit_date_start').val(emp.hiring);
        }
    } else {
        $('#edit_hiring_hint').html('Sin fecha de contratación registrada.');
    }
    renderEditVacationHistory(emp.id);
    recalcularEditVacacion();
}

function recalcularEditVacacion() {
    var emp = getEditEmployee();
    var start = parseEditDate($('#edit_date_start').val());
    var end = parseEditDate($('#edit_date_end').val());
    var hiring = parseEditDate(emp.hiring);
    var isPagada = $('#edit_vacation_type').val() === 'Pagada';

    if (isPagada) {
        $('#edit_amount_group').show();
    } else {
        $('#edit_amount_group').hide();
        $('#edit_amount').val('0.00');
    }

    if (!start || !end) {
        $('#edit_worked_days').val(0);
        $('#edit_accrued_days').val(0);
        $('#edit_used_days').val(emp.used.toFixed(3));
        $('#edit_days').val(0);
        $('#edit_amount').val('0.00');
        return;
    }

    if (hiring && start < hiring) {
        start = hiring;
        $('#edit_date_start').val(formatEditDateUTC(hiring));
        $('#edit_date_error').html('La fecha de inicio se ajustó a la contratación del empleado.');
    } else {
        $('#edit_date_error').html('');
    }

    if (end < start) {
        $('#edit_date_error').html('La fecha final debe ser igual o posterior a la fecha de inicio.');
        $('#edit_worked_days').val(0);
        $('#edit_accrued_days').val(0);
        $('#edit_used_days').val(emp.used.toFixed(3));
        $('#edit_days').val(0);
        $('#edit_amount').val('0.00');
        $('#edit_submit_vacation').attr('disabled', 'disabled');
        return;
    }

    var workedDays = Math.floor((end.getTime() - start.getTime()) / 86400000);
    var accruedDays = Math.round(((workedDays * 15) / 365 + Number.EPSILON) * 1000) / 1000;
    var usedDays = emp.used;
    var vacationDays = Math.round((Math.max(0, accruedDays - usedDays) + Number.EPSILON) * 1000) / 1000;
    var amount = 0;
    if (isPagada) {
        amount = Math.round(((vacationDays * (emp.salary / 30)) + Number.EPSILON) * 100) / 100;
    }

    $('#edit_worked_days').val(workedDays);
    $('#edit_accrued_days').val(accruedDays.toFixed(3));
    $('#edit_used_days').val(usedDays.toFixed(3));
    $('#edit_days').val(vacationDays.toFixed(3));
    $('#edit_amount').val(amount.toFixed(2));
    $('#edit_submit_vacation').removeAttr('disabled');
}

$(document).ready(function() {
    renderEditVacationHistory($('#edit_employee_id').val());
    recalcularEditVacacion();
});
</script>
