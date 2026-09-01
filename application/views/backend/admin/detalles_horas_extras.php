<?php
$moneda = $this->crud_model->get_info("moneda");
$payroll = $this->db->get_where('payroll', array('payroll_id' => $payroll_id))->row();
$employee = !empty($payroll->employee) ? json_decode($payroll->employee, true) : array();
if (!is_array($employee)) {
    $employee = array();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Detalle de horas extras y viáticos
                            <span class="d-block text-muted pt-2 font-size-sm">
                                <?php echo date('d/m/Y', strtotime($payroll->date_start)).' - '.date('d/m/Y', strtotime($payroll->date_end)); ?>
                                <?php if (!empty($payroll->note)): ?>
                                · <?php echo htmlspecialchars($payroll->note, ENT_QUOTES, 'UTF-8'); ?>
                                <?php endif; ?>
                            </span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo base_url().'admin/horas_extras'; ?>" class="btn btn-secondary font-weight-bolder mr-2">Regresar</a>
                        <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                        <a href="<?php echo base_url().'admin/horas_extras/imprimir/'.$payroll_id; ?>"
                            target="_blank" rel="noopener" class="btn btn-primary font-weight-bolder">Imprimir</a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-8">
                        <div class="col-md-3">
                            <label><strong>Origen:</strong></label>
                            <p>
                                <?php if ($payroll->bank == 0): ?>
                                    Caja Chica
                                <?php else:
                                    $account = $this->db->get_where('account_bank', array('account_bank_id'=>$payroll->bank))->row();
                                    if ($account) {
                                        $bank_name = $this->db->get_where('bank', array('bank_id'=> $account->bank_id))->row()->name;
                                        echo '('.$bank_name.') - '.$account->name_account;
                                    } else {
                                        echo '-';
                                    }
                                endif; ?>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label><strong>Responsable:</strong></label>
                            <p><?php echo $this->crud_model->getName('admin', $payroll->responsable); ?></p>
                        </div>
                        <div class="col-md-3">
                            <label><strong>Empleados:</strong></label>
                            <p><?php echo (int) $payroll->num_employee; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label><strong>Total:</strong></label>
                            <p><span class="text-danger font-weight-bold"><?php echo $moneda.number_format((float)$payroll->total, 2, '.', ','); ?></span></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empleado</th>
                                    <th>Horas</th>
                                    <th>Costo hora</th>
                                    <th>Total horas</th>
                                    <th>Días viáticos</th>
                                    <th>Costo viático</th>
                                    <th>Total viático</th>
                                    <th>Total a pagar</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $n = 1;
                                $sum_hours = 0;
                                $sum_overtime = 0;
                                $sum_viatico_days = 0;
                                $sum_viatico = 0;
                                $sum_sub = 0;
                                foreach ($employee as $emp):
                                    $sum_hours += (float)($emp['hours'] ?? 0);
                                    $sum_overtime += (float)($emp['overtime_total'] ?? 0);
                                    $sum_viatico_days += (float)($emp['viatico_days'] ?? 0);
                                    $sum_viatico += (float)($emp['viatico_total'] ?? 0);
                                    $sum_sub += (float)($emp['sub'] ?? 0);
                                ?>
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td><?php echo $this->crud_model->getName('admin', $emp['employee']); ?></td>
                                    <td><?php echo number_format((float)($emp['hours'] ?? 0), 2, '.', ','); ?></td>
                                    <td><?php echo $moneda.number_format((float)($emp['hour_cost'] ?? 0), 2, '.', ','); ?></td>
                                    <td><?php echo $moneda.number_format((float)($emp['overtime_total'] ?? 0), 2, '.', ','); ?></td>
                                    <td><?php echo number_format((float)($emp['viatico_days'] ?? 0), 2, '.', ','); ?></td>
                                    <td><?php echo $moneda.number_format((float)($emp['viatico_cost'] ?? 0), 2, '.', ','); ?></td>
                                    <td><?php echo $moneda.number_format((float)($emp['viatico_total'] ?? 0), 2, '.', ','); ?></td>
                                    <td><span class="text-danger font-weight-bold"><?php echo $moneda.number_format((float)($emp['sub'] ?? 0), 2, '.', ','); ?></span></td>
                                    <td><?php echo !empty($emp['note']) ? htmlspecialchars($emp['note'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr style="background:#f3f6f9;">
                                    <td colspan="2"><strong>TOTALES</strong></td>
                                    <td><strong><?php echo number_format($sum_hours, 2, '.', ','); ?></strong></td>
                                    <td></td>
                                    <td><strong><?php echo $moneda.number_format($sum_overtime, 2, '.', ','); ?></strong></td>
                                    <td><strong><?php echo number_format($sum_viatico_days, 2, '.', ','); ?></strong></td>
                                    <td></td>
                                    <td><strong><?php echo $moneda.number_format($sum_viatico, 2, '.', ','); ?></strong></td>
                                    <td><strong><span class="text-danger"><?php echo $moneda.number_format($sum_sub, 2, '.', ','); ?></span></strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
