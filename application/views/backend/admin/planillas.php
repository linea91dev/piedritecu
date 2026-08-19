<?php 
$branch = $this->session->userdata('branch_id');
$this->db->order_by('payroll_id','DESC');
$this->db->where('branch_id', $branch);
$this->db->group_start();
$this->db->where_in('payroll_name', array('Oficial', 'Interna'));
$this->db->or_where('payroll_name IS NULL', null, false);
$this->db->or_where('payroll_name', '');
$this->db->group_end();
$data = $this->db->get('payroll');
$moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar planillas
                            <span class="d-block text-muted pt-2 font-size-sm">Administra las planillas de tus
                                colaboradores.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0 && ($user_type == 1 || $permisos['reportes_planillas'] == 1)): ?>
                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                fill="#000000" opacity="0.3" />
                                            <path
                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                </span>Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li
                                        class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/planillas';?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/planillas';?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; if($user_type == 1 || $permisos['pagar_planillas'] == 1):?>
                        <a href="<?php echo base_url().'admin/pagar_planillas';?>"
                            class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path
                                            d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Pagar planilla
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0):?>
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
                                    data-col-index="5">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Responsable:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres"
                                    data-col-index="6">
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
                                    <th>Planilla</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha final</th>
                                    <th>Origen</th>
                                    <th># Empleados</th>
                                    <th>Responsable</th>
                                    <th>Total Planilla</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php  $n =1; foreach ($data->result_array() as $row):
                                    // Calcular totales agrupados
                                    $total_salary = 0;
                                    $total_advance = 0;
                                    $total_discount = 0;
                                    $total_remuneration = 0;
                                    $total_sub = 0;
                                    
                                    if ($row['employee'] != "" || $row['employee'] != null) {
                                        $employee = json_decode($row['employee'],true);
                                        foreach($employee as $emp) {
                                            $total_salary += floatval($emp['salary'] ?? 0);
                                            $total_advance += floatval($emp['advance'] ?? 0);
                                            $total_discount += floatval($emp['discount'] ?? 0);
                                            $total_remuneration += floatval($emp['remuneration'] ?? 0);
                                            $total_sub += floatval($emp['sub'] ?? 0);
                                        }
                                    }
                                    ;?>
                                <tr>
                                    <td> <?php echo $n++;?></td>
                                    <td>
                                        <?php $payroll_name = isset($row['payroll_name']) ? $row['payroll_name'] : 'Oficial'; ?>
                                        <?php $payroll_label = in_array($payroll_name, array('Oficial', 'Interna'), true) ? 'Planilla '.strtolower($payroll_name) : $payroll_name; ?>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($payroll_name === 'Interna') ? 'info' : (($payroll_name === 'Oficial') ? 'success' : 'warning'); ?> label-inline">
                                            <?php echo $payroll_label; ?>
                                        </span>
                                    </td>
                                    <td><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date_start'] ));				
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?> </td>
                                    <td><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date_end'] ));				
                                        $Mes_Anyo2 = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo2;?> </td>
                                    <td>
                                        <?php if ($row['bank'] == 0){
                                            echo 'Caja Chica';
                                        }
                                        else {
                                            $name_account = $this->db->get_where('account_bank', array('account_bank_id'=>$row['bank']))->row()->name_account;
                                            $bank_id = $this->db->get_where('account_bank', array('account_bank_id'=>$row['bank']))->row()->bank_id;
                                            $bank_name = $this->db->get_where('bank', array('bank_id'=> $bank_id))->row()->name;
                                            echo '('.$bank_name.') - '.$name_account;
                                        }
                                        ?>
                                    </td>
                                    <td><span class="label label-lg font-weight-bold label-light-primary label-inline"><?php echo $row['num_employee'];?></span></td>
                                    <td><span class="text-success"><b><?php echo $this->crud_model->getName('admin', $row['responsable']);?></b></span></td>
                                    <td><span class="text-danger"><b><?php echo $moneda.number_format($total_sub,2,'.',',');?></b></span></td>
                                    <td><?php echo ($row['note'] != '')? substr($row['note'], 0, 30).'...' :'-';?></td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php if($row['status']==1):?>
                                                <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/planillas/detalle/'.$row['payroll_id'];?>"
                                                data-toggle="tooltip" data-original-title="Ver detalle" title="Ver detalle"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M12,4.5 C7,4.5 2.73,7.61 1,12 C2.73,16.39 7,19.5 12,19.5 C17,19.5 21.27,16.39 23,12 C21.27,7.61 17,4.5 12,4.5 Z M12,17 C9.24,17 7,14.76 7,12 C7,9.24 9.24,7 12,7 C14.76,7 17,9.24 17,12 C17,14.76 14.76,17 12,17 Z M12,9 C10.34,9 9,10.34 9,12 C9,13.66 10.34,15 12,15 C13.66,15 15,13.66 15,12 C15,10.34 13.66,9 12,9 Z" fill="#000000"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                                <?php endif;?>
                                            &nbsp;
                                                <?php if($user_type == 1 || $permisos['reportes_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/planillas/imprimir/'.$row['payroll_id'].'/0';?>"
                                                data-toggle="tooltip" data-original-title="Imprimir boletas" title="Imprimir boletas"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path
                                                                d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                                                fill="#000000"></path>
                                                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                                                height="2" rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                                <?php endif;?>
                                            &nbsp;
                                                <?php if($user_type == 1 || $permisos['editar_planillas'] == 1):?>
                                            <a href="javascript:;" data-toggle="tooltip" title="Editar planilla"
                                                data-original-title="Editar planilla"
                                                onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_planillas/'. $row['payroll_id'].'/0';?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                            fill="#8950FC" />
                                                        <path
                                                            d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                            fill="#8950FC" />
                                                    </svg>
                                                </span>
                                            </a>
                                                <?php endif;?>
                                            <?php else:?>
                                                <?php if($user_type == 1 || $permisos['estado_planillas'] == 1):?>
                                            <a href="<?php echo base_url().'admin/planillas/active/'.$row['payroll_id'];?>"
                                                data-toggle="tooltip" data-original-title="Re-activar" title="Re-activar"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                                                fill="#000000" opacity="0.3" />
                                                            <path
                                                                d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                                                fill="#000000" />
                                                        </g>
                                                    </svg>
                                                </span>
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
                    <div class="card-body"
                        style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                        <center>
                            <h3>Sin datos</h3><br>
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
let timerInterval;

function execute_example(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará toda la información del pago de planilla",
        type: 'info',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Eliminando información',
                type: 'success',
                icon: 'success',
                titleTextColor: '#000',
                html: 'Esta ventana se cerrará en <strong></strong>.',
                timer: 2000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong').textContent = Swal
                            .getTimerLeft()
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            })
            location.href = "<?php echo base_url();?>admin/planillas/delete/" + _id;
        }
    })
}
</script>