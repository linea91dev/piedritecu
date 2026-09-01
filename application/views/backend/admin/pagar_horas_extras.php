<?php
$moneda = $this->crud_model->get_info("moneda");
$user_id = $this->session->userdata("login_user_id");
$users = $this->db->order_by('name', 'ASC')->get_where('admin', array('status' => 1, 'job !=' => 0))->result_array();
$date_start = date('Y-m-d', strtotime('monday this week'));
$date_end = date('Y-m-d', strtotime('sunday this week'));
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Pagar horas extras y viáticos
                            <span class="d-block text-muted pt-2 font-size-sm">
                                Total horas = horas × costo hora. Total viático = días × costo viático. Total a pagar = ambos totales.
                            </span>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/horas_extras/create" method="POST">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Período <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Desde</span></div>
                                        <input type="date" class="form-control" name="date_start" id="date_start" required value="<?php echo $date_start; ?>">
                                        <div class="input-group-prepend"><span class="input-group-text">Hasta</span></div>
                                        <input type="date" class="form-control" name="date_end" id="date_end" required value="<?php echo $date_end; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Debitar de <span class="text-danger">*</span></label>
                                    <select class="form-control" name="bank" required>
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
                            <div class="col-sm-4">
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
                                <div class="form-group">
                                    <label>Referencia / descripción del período</label>
                                    <input type="text" class="form-control" name="note" placeholder="Ej. Semana 24 al 29 de agosto">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="overtime_table">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Cantidad de horas</th>
                                        <th>Costo hora</th>
                                        <th>Total horas extras</th>
                                        <th>Días viáticos</th>
                                        <th>Costo viático</th>
                                        <th>Total viático</th>
                                        <th>Total a pagar</th>
                                        <th>Notas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): $_id = $user['admin_id']; ?>
                                    <tr class="overtime-row" data-id="<?php echo $_id; ?>">
                                        <td>
                                            <?php echo $user['name'].' '.$user['last_name']; ?>
                                            <input type="hidden" name="employee[]" value="<?php echo $_id; ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control hours-input"
                                                style="width:90px" name="hours[]" id="hours-<?php echo $_id; ?>"
                                                value="0" oninput="recalcOvertime(<?php echo $_id; ?>)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control hour-cost-input"
                                                style="width:90px" name="hour_cost[]" id="hour_cost-<?php echo $_id; ?>"
                                                value="0" oninput="recalcOvertime(<?php echo $_id; ?>)">
                                        </td>
                                        <td>
                                            <span class="text-info font-weight-bold" id="overtime_total_display-<?php echo $_id; ?>"><?php echo $moneda; ?>0.00</span>
                                            <input type="hidden" id="overtime_total-<?php echo $_id; ?>" value="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control viatico-days-input"
                                                style="width:90px" name="viatico_days[]" id="viatico_days-<?php echo $_id; ?>"
                                                value="0" oninput="recalcOvertime(<?php echo $_id; ?>)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control viatico-cost-input"
                                                style="width:90px" name="viatico_cost[]" id="viatico_cost-<?php echo $_id; ?>"
                                                value="0" oninput="recalcOvertime(<?php echo $_id; ?>)">
                                        </td>
                                        <td>
                                            <span class="text-primary font-weight-bold" id="viatico_total_display-<?php echo $_id; ?>"><?php echo $moneda; ?>0.00</span>
                                            <input type="hidden" id="viatico_total-<?php echo $_id; ?>" value="0">
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold" id="sub_display-<?php echo $_id; ?>"><?php echo $moneda; ?>0.00</span>
                                            <input type="hidden" class="row-sub" id="sub-<?php echo $_id; ?>" value="0">
                                        </td>
                                        <td>
                                            <textarea rows="1" class="form-control" name="row_note[]"></textarea>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="7" class="text-right"><strong>TOTAL PLANILLA</strong></td>
                                        <td>
                                            <h4 class="mb-0"><span class="text-danger" id="grand_total"><?php echo $moneda; ?>0.00</span></h4>
                                            <input type="hidden" name="ttl" id="ttl" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <a href="<?php echo base_url();?>admin/horas_extras/" class="btn btn-light-primary font-weight-bold">Regresar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold" id="submit_overtime">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

function money(n) {
    return moneda + Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function recalcOvertime(id) {
    var hours = parseFloat($('#hours-' + id).val()) || 0;
    var hourCost = parseFloat($('#hour_cost-' + id).val()) || 0;
    var viaticoDays = parseFloat($('#viatico_days-' + id).val()) || 0;
    var viaticoCost = parseFloat($('#viatico_cost-' + id).val()) || 0;

    var overtimeTotal = Math.round(((hours * hourCost) + Number.EPSILON) * 100) / 100;
    var viaticoTotal = Math.round(((viaticoDays * viaticoCost) + Number.EPSILON) * 100) / 100;
    var sub = Math.round(((overtimeTotal + viaticoTotal) + Number.EPSILON) * 100) / 100;

    $('#overtime_total-' + id).val(overtimeTotal.toFixed(2));
    $('#viatico_total-' + id).val(viaticoTotal.toFixed(2));
    $('#sub-' + id).val(sub.toFixed(2));
    $('#overtime_total_display-' + id).html(money(overtimeTotal));
    $('#viatico_total_display-' + id).html(money(viaticoTotal));
    $('#sub_display-' + id).html(money(sub));
    updateGrandTotal();
}

function updateGrandTotal() {
    var total = 0;
    $('.row-sub').each(function() {
        total += parseFloat($(this).val()) || 0;
    });
    total = Math.round((total + Number.EPSILON) * 100) / 100;
    $('#ttl').val(total.toFixed(2));
    $('#grand_total').html(money(total));
    if (total > 0) {
        $('#submit_overtime').removeAttr('disabled');
    } else {
        $('#submit_overtime').attr('disabled', 'disabled');
    }
}

$(document).ready(function() {
    updateGrandTotal();
});
</script>
