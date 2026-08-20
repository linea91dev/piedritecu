<?php
$branch = $this->session->userdata('branch_id');
$data = $this->db->order_by('vacation_id', 'DESC')->get_where('vacations', array('branch_id' => $branch));
$moneda = $this->crud_model->get_info("moneda");
$can_create = ($user_type == 1 || !empty($permisos['crear_vacaciones']));
$can_edit = ($user_type == 1 || !empty($permisos['editar_vacaciones']));
$can_status = ($user_type == 1 || !empty($permisos['estado_vacaciones']));
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Control de vacaciones
                            <span class="d-block text-muted pt-2 font-size-sm">Administra las vacaciones gozadas y pagadas de tus colaboradores.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if ($can_create): ?>
                        <a href="<?php echo base_url().'admin/registrar_vacacion';?>"
                            class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Registrar vacación
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($data->num_rows() > 0): ?>
                    <form class="mb-15">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha de inicio:</label>
                                <input type="text" class="form-control datatable-input" id="kt_datepicker"
                                    placeholder="mm/dd/aaaa" data-col-index="2" readonly>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha final:</label>
                                <input type="text" class="form-control datatable-input" id="kt_datepicker_1"
                                    placeholder="mm/dd/aaaa" data-col-index="3" readonly>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Empleado:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres"
                                    data-col-index="1">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Forma:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Gozada / Pagada"
                                    data-col-index="5">
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Buscar</span>
                                    </span>
                                </button>&nbsp;&nbsp;
                                <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Limpiar</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha final</th>
                                    <th>Días</th>
                                    <th>Forma</th>
                                    <th>Monto</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1; foreach ($data->result_array() as $row): ?>
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td>
                                        <span class="text-info">
                                            <b><?php echo $this->crud_model->getName('admin', $row['employee_id']); ?></b>
                                        </span>
                                    </td>
                                    <td><?php echo date('m/d/Y', strtotime($row['date_start'])); ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($row['date_end'])); ?></td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-primary label-inline">
                                            <?php echo number_format((float) $row['days'], 3, '.', ','); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['type'] === 'Pagada') ? 'warning' : 'success'; ?> label-inline">
                                            <?php echo $row['type']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <b><?php echo $moneda.number_format((float) $row['amount'], 2, '.', ','); ?></b>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <b><?php echo $this->crud_model->getName('admin', $row['responsable']); ?></b>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['status'] == 1) ? 'success' : 'danger'; ?> label-inline">
                                            <?php echo ($row['status'] == 1) ? 'Activo' : 'Anulado'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url().'admin/vacaciones/detalle/'.$row['vacation_id']; ?>"
                                                data-toggle="tooltip" title="Ver detalle"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url().'admin/vacaciones/imprimir/'.$row['vacation_id']; ?>"
                                                target="_blank" rel="noopener"
                                                data-toggle="tooltip" title="Imprimir comprobante"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <?php if ($row['status'] == 1 && $can_edit): ?>
                                            <a href="javascript:;"
                                                onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_vacacion/'.$row['vacation_id']; ?>');"
                                                data-toggle="tooltip" title="Editar"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if ($can_status): ?>
                                                <?php if ($row['status'] == 1): ?>
                                                <a href="javascript:;"
                                                    onclick="executeVacation('<?php echo $row['vacation_id']; ?>')"
                                                    data-toggle="tooltip" title="Anular"
                                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php else: ?>
                                                <a href="<?php echo base_url().'admin/vacaciones/active/'.$row['vacation_id']; ?>"
                                                    data-toggle="tooltip" title="Reactivar"
                                                    class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="card-body" style="padding-top: 120px;padding-bottom: 120px;">
                        <center>
                            <h3>Sin datos</h3><br>
                            <img src="<?php echo base_url(); ?>uploads/empty.jpg" style="max-width:25%">
                        </center>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
let timerInterval;

function executeVacation(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se anulará el registro de vacaciones",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Anulando información',
                icon: 'success',
                html: 'Esta ventana se cerrará en <strong></strong>.',
                timer: 2000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong').textContent = Swal.getTimerLeft()
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            })
            location.href = "<?php echo base_url(); ?>admin/vacaciones/delete/" + _id;
        }
    })
}
</script>
