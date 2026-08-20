<?php 
$moneda = $this->crud_model->get_info("moneda");
$payroll = $this->db->get_where('payroll', array('payroll_id'=>$payroll_id))->row();
$employee = json_decode($payroll->employee,true);
$is_bonus_detail = in_array(isset($payroll->payroll_name) ? $payroll->payroll_name : '', array('Bono 14', 'Aguinaldo'), true);
$back_url = $is_bonus_detail ? base_url().'admin/bonos' : base_url().'admin/planillas';
$print_url = $is_bonus_detail
    ? base_url().'admin/bonos/imprimir/'.$payroll_id.'/0'
    : base_url().'admin/planillas/imprimir/'.$payroll_id.'/0';
$print_label = $is_bonus_detail ? 'Imprimir boletas' : 'Imprimir planilla';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label"><?php echo $is_bonus_detail ? 'Detalle de Bono' : 'Detalle de Planilla'; ?>
                            <span class="d-block text-muted pt-2 font-size-sm">
                                <?php setlocale(LC_TIME, "spanish");
                                $fecha_inicio = strftime("%d de %B de %Y", strtotime($payroll->date_start));
                                $fecha_fin = strftime("%d de %B de %Y", strtotime($payroll->date_end));
                                echo $fecha_inicio . ' - ' . $fecha_fin;
                                ?>
                            </span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo $back_url;?>" class="btn btn-secondary font-weight-bolder mr-2">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M9.6 11.12c1.38-.01 2.79.37 3.94 1.02l1.36-1.36c-.71-1.16-1.73-2.09-2.95-2.66l.64-1.36c1.53.72 2.82 2.06 3.56 3.76-1.36 1.36-3.22 2.22-5.28 2.22-3.25 0-5.9-2.66-5.9-5.9s2.65-5.9 5.9-5.9c1.36 0 2.65.46 3.72 1.22l-1.36 1.36c-.89-.58-1.94-.9-3.08-.9-2.76 0-5 2.24-5 5s2.24 5 5 5c2.07 0 3.85-1.26 4.68-3.04l-1.36-1.36c-.58.89-1.38 1.64-2.32 2.16v1.42z" fill="#000000"/>
                                    <path d="M11.66 18.08c-2.76 0-5-2.24-5-5 0-2.07 1.26-3.85 3.04-4.68l-1.36 1.36c-.89.58-1.64 1.38-2.16 2.32h-1.42c-.72-1.53-2.06-2.82-3.76-3.56l1.36-.64c.57 1.22 1.5 2.24 2.66 2.95l-1.36 1.36c-.65-1.15-1.03-2.56-1.02-3.94 0-3.25 2.66-5.9 5.9-5.9 3.24 0 5.9 2.65 5.9 5.9 0 3.24-2.66 5.9-5.9 5.9z" fill="#000000" opacity="0.3"/>
                                </svg>
                            </span> Regresar
                        </a>
                        <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                        <a href="<?php echo $print_url;?>" target="_blank" rel="noopener" class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"></path>
                                        <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"></rect>
                                    </g>
                                </svg>
                            </span> <?php echo $print_label; ?>
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información de la planilla -->
                    <div class="row mb-8">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Planilla:</strong></label>
                                <?php $payroll_name = isset($payroll->payroll_name) ? $payroll->payroll_name : 'Oficial'; ?>
                                <?php $payroll_label = in_array($payroll_name, array('Oficial', 'Interna'), true) ? 'Planilla '.strtolower($payroll_name) : $payroll_name; ?>
                                <p><span class="label label-lg font-weight-bold label-light-primary label-inline">
                                    <?php echo $payroll_label; ?>
                                </span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Origen:</strong></label>
                                <p>
                                    <?php if ($payroll->bank == 0):?>
                                        <span class="label label-lg font-weight-bold label-light-warning label-inline">Caja Chica</span>
                                    <?php else:?>
                                        <?php 
                                        $name_account = $this->db->get_where('account_bank', array('account_bank_id'=>$payroll->bank))->row()->name_account;
                                        $bank_id = $this->db->get_where('account_bank', array('account_bank_id'=>$payroll->bank))->row()->bank_id;
                                        $bank_name = $this->db->get_where('bank', array('bank_id'=> $bank_id))->row()->name;
                                        ?>
                                        <span class="label label-lg font-weight-bold label-light-primary label-inline">(<?php echo $bank_name;?>) - <?php echo $name_account;?></span>
                                    <?php endif;?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Responsable:</strong></label>
                                <p><?php echo $this->crud_model->getName('admin', $payroll->responsable);?></p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Total Empleados:</strong></label>
                                <p><span class="text-primary font-weight-bold"><?php echo $payroll->num_employee;?></span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Notas:</strong></label>
                                <p><?php echo ($payroll->note != '')? $payroll->note :'-';?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de empleados -->
                    <?php
                    $is_oficial = ($payroll_name === 'Oficial');
                    $show_other_discount = in_array($payroll_name, array('Oficial', 'Interna'), true);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empleado</th>
                                    <th>Salario</th>
                                    <?php if ($is_oficial): ?>
                                    <th>Descuento IGSS</th>
                                    <th>Descuentos ISR</th>
                                    <?php endif; ?>
                                    <?php if ($show_other_discount): ?>
                                    <th>Descuentos</th>
                                    <?php endif; ?>
                                    <?php if ($is_oficial): ?>
                                    <th>Bonificacion decreto </th>
                                    <?php endif; ?>
                                    <th>Total</th>
                                    <th>Notas</th>
                                    <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                    <th>Boleta</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_salary = 0;
                                $total_advance = 0;
                                $total_discount = 0;
                                $total_other_discount = 0;
                                $total_remuneration = 0;
                                $total_sub = 0;
                                $n = 1;
                                $print_base = $is_bonus_detail
                                    ? base_url().'admin/bonos/imprimir/'.$payroll_id.'/'
                                    : base_url().'admin/planillas/imprimir/'.$payroll_id.'/';
                                foreach($employee as $emp): 
                                    $total_salary += floatval($emp['salary'] ?? 0);
                                    $total_advance += floatval($emp['advance'] ?? 0);
                                    $total_discount += floatval($emp['discount'] ?? 0);
                                    $total_other_discount += floatval($emp['other_discount'] ?? 0);
                                    $total_remuneration += floatval($emp['remuneration'] ?? 0);
                                    $total_sub += floatval($emp['sub'] ?? 0);
                                ?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><span class="label label-lg font-weight-bold label-light-info label-inline"><?php echo $this->crud_model->getName('admin', $emp['employee']);?></span></td>
                                    <td><?php echo $moneda.number_format($emp['salary'] ?? 0,2,'.',',');?></td>
                                    <?php if ($is_oficial): ?>
                                    <td><?php echo $moneda.number_format($emp['discount'] ?? 0,2,'.',',');?></td>
                                    <td><?php echo $moneda.number_format($emp['advance'] ?? 0,2,'.',',');?></td>
                                    <?php endif; ?>
                                    <?php if ($show_other_discount): ?>
                                    <td><?php echo $moneda.number_format($emp['other_discount'] ?? 0,2,'.',',');?></td>
                                    <?php endif; ?>
                                    <?php if ($is_oficial): ?>
                                    <td><?php echo $moneda.number_format($emp['remuneration'] ?? 0,2,'.',',');?></td>
                                    <?php endif; ?>
                                    <td><span class="text-danger font-weight-bold"><?php echo $moneda.number_format($emp['sub'] ?? 0,2,'.',',');?></span></td>
                                    <td><?php echo ($emp['note'] ?? '') != '' ? $emp['note'] : '-';?></td>
                                    <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                    <td>
                                        <a href="<?php echo $print_base.$emp['employee'];?>" class="btn btn-sm btn-light-primary" target="_blank" rel="noopener">
                                            Imprimir
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach;?>
                                
                                <!-- Totales -->
                                <tr style="background-color: #f3f6f9;">
                                    <td colspan="2"><strong>TOTALES</strong></td>
                                    <td><strong><?php echo $moneda.number_format($total_salary,2,'.',',');?></strong></td>
                                    <?php if ($is_oficial): ?>
                                    <td><strong><?php echo $moneda.number_format($total_discount,2,'.',',');?></strong></td>
                                    <td><strong><?php echo $moneda.number_format($total_advance,2,'.',',');?></strong></td>
                                    <?php endif; ?>
                                    <?php if ($show_other_discount): ?>
                                    <td><strong><?php echo $moneda.number_format($total_other_discount,2,'.',',');?></strong></td>
                                    <?php endif; ?>
                                    <?php if ($is_oficial): ?>
                                    <td><strong><?php echo $moneda.number_format($total_remuneration,2,'.',',');?></strong></td>
                                    <?php endif; ?>
                                    <td><strong><span class="text-danger"><?php echo $moneda.number_format($total_sub,2,'.',',');?></span></strong></td>
                                    <td></td>
                                    <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                    <td></td>
                                    <?php endif; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>