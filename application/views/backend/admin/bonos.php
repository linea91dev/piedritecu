<?php
$branch = $this->session->userdata('branch_id');
$this->db->order_by('payroll_id', 'DESC');
$this->db->where('branch_id', $branch);
$this->db->where_in('payroll_name', array('Bono 14', 'Aguinaldo'));
$data = $this->db->get('payroll');
$moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Bono 14 y Aguinaldo
                            <span class="d-block text-muted pt-2 font-size-sm">Pagos anuales separados de las planillas ordinarias.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($user_type == 1 || $permisos['pagar_planillas'] == 1):?>
                        <a href="<?php echo base_url();?>admin/pagar_bonos" class="btn btn-primary font-weight-bolder">
                            Pagar bono
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0):?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Origen</th>
                                    <th>Empleados</th>
                                    <th>Responsable</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1; foreach ($data->result_array() as $row):
                                    $total_sub = 0;
                                    if (!empty($row['employee'])) {
                                        $employee = json_decode($row['employee'], true);
                                        if (is_array($employee)) {
                                            foreach ($employee as $emp) {
                                                $total_sub += floatval($emp['sub'] ?? 0);
                                            }
                                        }
                                    }
                                    $payroll_name = isset($row['payroll_name']) ? $row['payroll_name'] : '';
                                ?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-warning label-inline">
                                            <?php echo $payroll_name; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($row['date_start']));?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['date_end']));?></td>
                                    <td>
                                        <?php if ($row['bank'] == 0) {
                                            echo 'Caja Chica';
                                        } else {
                                            $account = $this->db->get_where('account_bank', array('account_bank_id'=>$row['bank']))->row();
                                            if ($account) {
                                                $bank_name = $this->db->get_where('bank', array('bank_id'=> $account->bank_id))->row()->name;
                                                echo '('.$bank_name.') - '.$account->name_account;
                                            } else {
                                                echo '-';
                                            }
                                        } ?>
                                    </td>
                                    <td><span class="label label-lg font-weight-bold label-light-primary label-inline"><?php echo $row['num_employee'];?></span></td>
                                    <td><?php echo $this->crud_model->getName('admin', $row['responsable']);?></td>
                                    <td><span class="text-danger"><b><?php echo $moneda.number_format($total_sub,2,'.',',');?></b></span></td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php if($row['status']==1):?>
                                                <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/bonos/detalle/'.$row['payroll_id'];?>"
                                                data-toggle="tooltip" title="Ver detalle"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="flaticon-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url().'admin/bonos/imprimir/'.$row['payroll_id'].'/0';?>"
                                                target="_blank" rel="noopener"
                                                data-toggle="tooltip" title="Imprimir boletas"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="flaticon2-printer"></i>
                                            </a>
                                                <?php endif;?>
                                                <?php if($user_type == 1 || $permisos['editar_planillas'] == 1):?>
                                            <a href="javascript:;" data-toggle="tooltip" title="Editar"
                                                onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_planillas/'. $row['payroll_id'].'/0';?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="flaticon2-pen"></i>
                                            </a>
                                                <?php endif;?>
                                                <?php if($user_type == 1 || $permisos['estado_planillas'] == 1):?>
                                            <a href="javascript:;" data-toggle="tooltip" title="Eliminar"
                                                onclick="execute_bonus_delete(<?php echo $row['payroll_id'];?>)"
                                                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                                <i class="flaticon2-trash"></i>
                                            </a>
                                                <?php endif;?>
                                            <?php else:?>
                                                <?php if($user_type == 1 || $permisos['estado_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/bonos/active/'.$row['payroll_id'];?>"
                                                data-toggle="tooltip" title="Re-activar"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <i class="flaticon2-check-mark"></i>
                                            </a>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="card-body" style="padding-top: 120px;padding-bottom: 120px;">
                        <center>
                            <h3>Sin pagos de Bono 14 / Aguinaldo</h3><br>
                            <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                        </center>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function execute_bonus_delete(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará el pago de bono seleccionado",
        type: 'info',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            location.href = "<?php echo base_url();?>admin/bonos/delete/" + _id;
        }
    });
}
</script>
