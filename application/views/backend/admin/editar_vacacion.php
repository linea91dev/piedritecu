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
$worked_days = $this->crud_model->calculate_vacation_worked_days(
    max($row['date_start'], $current_hiring ?: $row['date_start']),
    $row['date_end']
);
?>
<form class="form" action="<?php echo base_url().'admin/vacaciones/update/'.$param2; ?>" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Días = (días trabajados × 15) / 365. Monto pagado = días × (salario / 30). Parte desde la fecha de contratación.
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
                    ?>
                    <option value="<?php echo $employee['admin_id']; ?>"
                        data-salary="<?php echo (float) $employee['salary']; ?>"
                        data-hiring="<?php echo htmlspecialchars($hiring, ENT_QUOTES, 'UTF-8'); ?>"
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

        <div class="col-sm-3">
            <div class="form-group">
                <label>Días trabajados</label>
                <input type="number" class="form-control" id="edit_worked_days" value="<?php echo (int) $worked_days; ?>" readonly>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>Días de vacación</label>
                <input type="number" step="0.001" class="form-control" name="days" id="edit_days"
                    value="<?php echo number_format((float) $row['days'], 3, '.', ''); ?>" readonly>
            </div>
        </div>

        <div class="col-sm-6" id="edit_amount_group" style="<?php echo ($row['type'] === 'Pagada') ? '' : 'display:none;'; ?>">
            <div class="form-group">
                <label>Monto a pagar (<?php echo $moneda; ?>)</label>
                <input type="number" step="0.01" min="0" class="form-control" name="amount" id="edit_amount"
                    value="<?php echo number_format((float) $row['amount'], 2, '.', ''); ?>" readonly>
                <small>días de vacación × (salario / 30)</small>
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
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="edit_submit_vacation">Guardar</button>
    </div>
</form>

<script type="text/javascript">
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

function getEditEmployee() {
    var option = $('#edit_employee_id option:selected');
    return {
        salary: parseFloat(option.attr('data-salary')) || 0,
        hiring: option.attr('data-hiring') || ''
    };
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
        $('#edit_days').val(0);
        $('#edit_amount').val('0.00');
        $('#edit_submit_vacation').attr('disabled', 'disabled');
        return;
    }

    var workedDays = Math.floor((end.getTime() - start.getTime()) / 86400000) + 1;
    var vacationDays = Math.round(((workedDays * 15) / 365 + Number.EPSILON) * 1000) / 1000;
    var amount = 0;
    if (isPagada) {
        amount = Math.round(((vacationDays * (emp.salary / 30)) + Number.EPSILON) * 100) / 100;
    }

    $('#edit_worked_days').val(workedDays);
    $('#edit_days').val(vacationDays.toFixed(3));
    $('#edit_amount').val(amount.toFixed(2));
    $('#edit_submit_vacation').removeAttr('disabled');
}

$(document).ready(function() {
    recalcularEditVacacion();
});
</script>
