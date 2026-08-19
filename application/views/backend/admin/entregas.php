<?php $data = $this->crud_model->get_deliveries(); $moneda = $this->crud_model->get_info("moneda"); setlocale(LC_TIME, "spanish");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Entregas realizadas
                            <span class="d-block text-muted pt-2 font-size-sm">Consulta todas las entregas sobre productos de ventas realizadas.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                    <?php if($data->num_rows() > 0 && ($user_type == 1 || $permisos['reportes_envios'] == 1)):?>
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
                                        <a href="<?php echo base_url().'admin/export_excel/entregas';?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/entregas';?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif;?>
                        <a href="<?php echo base_url(); ?>admin/envios/" class="btn btn-light-success font-weight-bolder">
                            <span class="svg-icon menu-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </span>Regresar a envíos pendientes
                        </a>
                    </div>
                    <div class="card-toolbar">
                        <div class="alert alert-blue">
                            <span class="d-block pt-2 font-size-sm">En este módulo, puedes buscar los envíos que estan en ruta, entregados ó cancelados, según la columna de "Estado de entrega", puedes cancelar los envíos o cambiar el estado de los que están en ruta a entregados.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0):?>
                    <form class="mb-15">
                        <div class="row mb-6">

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Código Entrega:</label>
                                <input type="text" class="form-control datatable-input" placeholder="DLVR12345"
                                    data-col-index="2">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha:</label>
                                <div class="input-daterange input-group" id="kt_datepicker">
                                    <input type="text" class="form-control datatable-input" name="start"
                                        autocomplete="off" placeholder="Fecha" data-col-index="4">
                                </div>
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Responsable:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres"
                                    data-col-index="4">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Estado:</label>
                                <select class=" form-control datatable-input" data-col-index="6">
                                    <option value="">Seleccionar</option>
                                    <option value="En ruta">En ruta</option>
                                    <option value="Entregado">Entregado</option>
                                    <option value="Cancelado">Cancelado</option>
                                </select>
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
                                    <th>Código</th>
                                    <th>Venta</th>
                                    <th>Fecha</th>
                                    <th>Responsable</th>
                                    <th>Total del envío</th>
                                    <th>Estado de la entrega</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n=1; foreach($data->result_array() as $row): ?>
                                <tr>
                                    <td> <?php echo $n++; ?> </td>
                                    <td> <?php echo $row['code']; ?> </td>
                                    <td> <?php echo $row['sale_code']; ?> </td>
                                    <td>
                                        <?php $Nueva_Fecha = date("d-m-Y", strtotime( $row['fecha_entrega'] ));
                                            $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                            echo $Mes_Anyo;?>
                                    </td>
                                    <td> <?php echo $this->crud_model->getName('admin', $row['responsable_id']);?> </td>
                                    <td> <?php echo $moneda.number_format($row['total'],2,'.',',');?> </td>
                                    <td> 
                                        <?php if($row['estado'] == 1): $estado = 'En ruta';?> 
                                        <span class="label label-lg font-weight-bold label-light-blue label-inline" style="font-size: 15px;"><?php echo $estado;?></span>
                                        <?php elseif($row['estado'] == 2): $estado = 'Entregado';?> 
                                        <span class="label label-lg font-weight-bold label-light-success label-inline" style="font-size: 15px;"><?php echo $estado;?></span>
                                        <?php elseif($row['estado'] == 3): $estado = 'Cancelado';?> 
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline" style="font-size: 15px;"><?php echo $estado;?></span>
                                        <?php endif;?> 
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php /* if($user_type == 1 || $permisos['editar_entregas'] == 1):?>
                                            &nbsp;
                                            <a href="javascript:void(0);" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/editar_entrega/<?php echo $row['code'];?>');"
                                                data-toggle="tooltip" data-original-title="Editar Venta"
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
                                            <?php endif; */?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/detalles_entrega/'.$row['code'];?>"
                                                id="kt_quick_panel_toggle"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Detalles">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                                            </circle>
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7"
                                                                rx="1"></rect>
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2"
                                                                rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <?php if($row['estado'] == 1 && ($user_type == 1 || $permisos['editar_entregas'] == 1)):?>
                                            <a href="javascript:void(0);" onclick="registrarEntrega('<?php echo $row['code'];?>')"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Cambiar a entregado">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                            <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"/>
                                                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['reportes_envios'] == 1):?>
                                            <a href="<?php echo base_url().'admin/export_pdf/entrega/'.$row['code'];?>"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Imprimir">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                                                fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                                                height="2" rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['eliminar_entregas'] == 1):?>
                                            <a href="javascript:void(0);" data-toggle="tooltip"
                                                data-original-title="Eliminar entrega"
                                                onclick="executeExample('<?php echo $row['code'];?>')"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                                                fill="#000000" fill-rule="nonzero" />
                                                            <path
                                                                d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                fill="#000000" opacity="0.3" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
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
                    <?php endif;?>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function search() {
    $name = $('#name').val();
    $status = $('#status').val();

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/ventas',
        data: {
            name: $name,
            status: $status,
        },
        success: function(response) {
            jQuery('#table').html(response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
let timerInterval

function executeExample(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará toda la información de la entrega.",
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
            location.href = "<?php echo base_url();?>admin/entregas/delete/" + _id;
        }
    })
}

function registrarEntrega(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Confirmas que el envío ha sido entregado.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Confirmando información',
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
            location.href = "<?php echo base_url();?>admin/entregas/completar/" + _id;
        }
    })
}
</script>