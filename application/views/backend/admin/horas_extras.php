<?php
$branch = $this->session->userdata('branch_id');
$this->db->order_by('payroll_id', 'DESC');
$this->db->where('branch_id', $branch);
$this->db->where('payroll_name', 'Horas extras');
$data = $this->db->get('payroll');
$moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Horas extras y viáticos
                            <span class="d-block text-muted pt-2 font-size-sm">Planillas de horas extras y viáticos separadas de las ordinarias.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($user_type == 1 || $permisos['pagar_planillas'] == 1):?>
                        <a href="<?php echo base_url();?>admin/pagar_horas_extras" class="btn btn-primary font-weight-bolder">
                            Pagar horas extras
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
                                    <th>Período</th>
                                    <th>Origen</th>
                                    <th>Empleados</th>
                                    <th>Responsable</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1; foreach ($data->result_array() as $row): ?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($row['date_start']));?>
                                        -
                                        <?php echo date('d/m/Y', strtotime($row['date_end']));?>
                                    </td>
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
                                    <td><span class="text-danger font-weight-bold"><?php echo $moneda.number_format((float)$row['total'], 2, '.', ',');?></span></td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['status'] == 1) ? 'success' : 'danger'; ?> label-inline">
                                            <?php echo ($row['status'] == 1) ? 'Activo' : 'Anulado'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url().'admin/horas_extras/detalle/'.$row['payroll_id'];?>"
                                                data-toggle="tooltip" title="Ver detalle"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/horas_extras/imprimir/'.$row['payroll_id'];?>"
                                                target="_blank" rel="noopener"
                                                data-toggle="tooltip" title="Imprimir"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <?php endif;?>
                                            <?php if($user_type == 1 || $permisos['estado_planillas'] == 1):?>
                                                <?php if ($row['status'] == 1): ?>
                                                <a href="javascript:;"
                                                    onclick="executeOvertime('<?php echo $row['payroll_id']; ?>')"
                                                    data-toggle="tooltip" title="Anular"
                                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php else: ?>
                                                <a href="<?php echo base_url().'admin/horas_extras/active/'.$row['payroll_id']; ?>"
                                                    data-toggle="tooltip" title="Reactivar"
                                                    class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                                <?php endif; ?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php else:?>
                    <div class="card-body" style="padding-top: 120px;padding-bottom: 120px;">
                        <center>
                            <h3>Sin datos</h3><br>
                            <img src="<?php echo base_url(); ?>uploads/empty.jpg" style="max-width:25%">
                        </center>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function executeOvertime(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se anulará la planilla de horas extras",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            location.href = "<?php echo base_url(); ?>admin/horas_extras/delete/" + _id;
        }
    })
}
</script>
