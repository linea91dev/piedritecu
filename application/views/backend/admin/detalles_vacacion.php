<?php
$moneda = $this->crud_model->get_info("moneda");
$vacation = $this->db->get_where('vacations', array('vacation_id' => $vacation_id))->row();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Detalle de vacación
                            <span class="d-block text-muted pt-2 font-size-sm">
                                <?php
                                echo date('d/m/Y', strtotime($vacation->date_start)).' - '.date('d/m/Y', strtotime($vacation->date_end));
                                ?>
                            </span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo base_url().'admin/vacaciones'; ?>" class="btn btn-secondary font-weight-bolder">
                            Regresar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-8">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Empleado:</strong></label>
                                <p><?php echo $this->crud_model->getName('admin', $vacation->employee_id); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Forma:</strong></label>
                                <p>
                                    <span class="label label-lg font-weight-bold label-light-<?php echo ($vacation->type === 'Pagada') ? 'warning' : 'success'; ?> label-inline">
                                        <?php echo $vacation->type; ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Días trabajados:</strong></label>
                                <p><span class="text-muted font-weight-bold">
                                    <?php
                                    $employee = $this->db->select('hiring')->get_where('admin', array('admin_id' => $vacation->employee_id))->row();
                                    $hiring = ($employee && !empty($employee->hiring)) ? date('Y-m-d', strtotime($employee->hiring)) : null;
                                    $eff_start = $vacation->date_start;
                                    if ($hiring && $hiring > $eff_start) {
                                        $eff_start = $hiring;
                                    }
                                    echo (int) $this->crud_model->calculate_vacation_worked_days($eff_start, $vacation->date_end);
                                    ?>
                                </span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Días de vacación:</strong></label>
                                <p><span class="text-primary font-weight-bold"><?php echo number_format((float) $vacation->days, 3, '.', ','); ?></span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Monto:</strong></label>
                                <p><span class="text-danger font-weight-bold"><?php echo $moneda.number_format((float) $vacation->amount, 2, '.', ','); ?></span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Responsable:</strong></label>
                                <p><?php echo $this->crud_model->getName('admin', $vacation->responsable); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Estado:</strong></label>
                                <p>
                                    <span class="label label-lg font-weight-bold label-light-<?php echo ($vacation->status == 1) ? 'success' : 'danger'; ?> label-inline">
                                        <?php echo ($vacation->status == 1) ? 'Activo' : 'Anulado'; ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Registrado:</strong></label>
                                <p><?php echo date('d/m/Y H:i', strtotime($vacation->datetime)); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Notas:</strong></label>
                                <p><?php echo ($vacation->note != '') ? $vacation->note : '-'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
