<?php  $data = $this->db->get_where('client',array('status'=>1)); $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar clientes
                            <span class="d-block text-muted pt-2 font-size-sm">Administra la información de tus
                                clientes.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0 && ($user_type == 1 || $permisos['reportes_clientes'] == 1)): ?>

                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                  fill="#000000" opacity="0.3" />
                                            <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                  fill="#000000" />
                                        </g>
                                    </svg>
                                </span>Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/clientes'?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/clientes'?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; if($user_type == 1 || $permisos['crear_clientes'] == 1):?>
                        <a href="javascript:;" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#exampleModalSizeLg">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                              fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Nuevo cliente
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0): ?>

                    <form class="mb-15">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Nombre:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres" data-col-index="1">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>NIT:</label>
                                <input type="text" class="form-control datatable-input" data-col-index="2" placeholder="Ejemplo: 312768">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Tipo:</label>
                                <select class=" form-control datatable-input" data-col-index="4">
                                    <option value="">Seleccionar</option>
                                    <option value="Mayorista">Mayorista</option>
                                    <option value="Minorista">Minorista</option>
                                    <option value="Farmacia">Socio</option>
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
                                    <th>Nombre</th>
                                    <th>NIT</th>
                                    <th>Celular</th>
                                    <th>Tipo</th>
                                    <th>Correo</th>
                                    <th>Límite de crédito</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n = 1;  foreach ($data->result_array() as $row):?>
                                <tr>
                                    <td><?php echo $n++	;?></td>
                                    <td><span class="text-secondary"><b><?php echo $row['name'].' '. $row['last_name'];?></b></span>
                                    </td>
                                    <td><?php echo ($row['nit'] == '')? 'Sin datos': $row['nit'] ;?></td>
                                    <td><?php echo ($row['phone'] == '')? 'Sin datos': '(+502)'.$row['phone'] ;?></td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-<?php if($row['type'] == 1){ echo'info ';}elseif($row['type'] == 2){echo'warning';}else{echo 'success';};?> label-inline"><?php if($row['type'] == 1){echo'Mayorista';}elseif($row['type'] == 2){echo'Publico';}else{echo'Socio';};?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['email']):?>
                                        <a style='color:#3F4254;' href="mailto:<?php echo $row['email'];?>">
                                            <?php echo $row['email'];?></a>
                                        <?php else:?>Sin datos
                                        <?php endif;?>
                                    </td>
                                    <td><span class="text-danger"><b>
                                                <?php echo ($row['limite'] == 0)? 'Sin Limite': $moneda.number_format($row['limite'],2,'.',',') ;?></b></span>
                                    </td>
                                    <td class='text-center'>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['status']==1) ? 'success':'danger' ;?> label-inline"><?php echo ($row['status']==1) ? 'Activo':'Inactivo' ;?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">

                                            <?php if($row['status']==1):?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['creditos'] == 1):?>
                                            <a href="<?php echo base_url().'admin/detalles_creditos/'.$row['client_id']; ?>" id="kt_quick_panel_toggle" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-toggle="tooltip" title="" data-original-title="Historial de créditos">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                                            </circle>
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"></rect>
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif; if($user_type == 1 || $permisos['editar_clientes'] == 1):?>
                                            <a href="javascript:;" title='' data-toggle="tooltip" data-original-title="Editar cliente" onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_cliente/'.$row['client_id'];?>');" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                              d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                              fill="#8950FC" />
                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                              fill="#8950FC" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['estado_clientes'] == 1):?>
                                            <a href="javascript:;" title="" data-toggle="tooltip" data-original-title="Desactivar cliente" onclick="executeExample('<?php echo $row['client_id'];?>')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                  fill="#000000" opacity="0.3" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            <?php else:?>
                                            <?php if($user_type == 1 || $permisos['estado_clientes'] == 1):?>
                                            <a href="<?php echo base_url().'admin/clientes/active/'.$row['client_id'];?>" data-toggle="tooltip" data-original-title="Re-activar Cliente" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                                                  fill="#000000" opacity="0.3" />
                                                            <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
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
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
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

<form class="form" action="<?php echo base_url();?>admin/clientes/create" method="POST" enctype="multipart/form-data">

    <div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-dialog  modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="alert alert-custom alert-default" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                                    <div class="alert-text">
                                        Los campos marcados con * son obligatorios.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> NIT (opcional)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id='nit' onkeyup="getNit()" minlength="2"  name='nit' pattern="[A-Z0-9-]+$"/>
                                </div>
                                <small>Presiona TAB para obtener los datos del contribuyente.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Correo </label>
                                <div class="input-group">
                                    <input type="email" class="form-control" aria-label="Text input with checkbox" id="email_client_add" name='email'/>
                                </div>
                                <span id="msg_email_client_add" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-sm-6" id='nombres'>
                            <div class="form-group">
                                <label>Nombres <span class="text-danger">*</span></label>
                                <div class="spinner-primary spinner-left" id='spinnerName'>
                                    <input type="text" class="form-control mb-5 " aria-label="Text input with checkbox" name='name' id='name' required />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6" id='apellidos'>
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <div class="spinner-primary spinner-left" id='spinnerLastName'>
                                    <input type="text" class="form-control " aria-label="Text input with checkbox" name='last_name' id='lastName' required />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Celular o WhatsApp</label>
                                <div class="input-group">
                                    <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" min='0' class="form-control" aria-label="Text input with checkbox" name='phone' pattern="[0-9]{8}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Sucursal <span class="text-danger">*</span> </label>
                                <select name="branch" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    <?php $sucursal = $this->db->get_where('branch')->result_array(); foreach ($sucursal as $sc):
                            ?>
                                    <option value="<?php echo $sc['branch_id'];?>" <?php if ($sc['branch_id'] == $this->session->userdata('branch_id')) echo "selected";?>>
                                        <?php echo $sc['name'];?>
                                    </option>
                                    <?php  endforeach ;?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo de cliente <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" name='type' required>
                                        <option>Seleccionar</option>
                                        <option value="1">Mayorista</option>
                                        <option value="2">Publico</option>
                                        <option value="3">Socio</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Límite de crédito <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" aria-label="Text input with checkbox" name='limite' min='0' value='0' required />
                                </div>
                                <small>(Establezca 0 para ilimitado)</small>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group">
                                    <textarea class="form-control" name='address' aria-label="Text input with checkbox"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="add_client_submit">Continuar</button>
                </div>
            </div>
        </div>
    </div>

</form>



<script type="text/javascript">
function getNit() {
    var nit = $('#nit').val();
    var leng_nit = nit.length;
    if (leng_nit >= 8) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/getNit/',
            data: {
                nit: nit,
            },
            beforeSend: function() {
                $('#spinnerName').addClass('spinner');
                $('#spinnerLastName').addClass('spinner');
            },
            success: function(response) {
                var data = JSON.parse(response);
                var data1 = data['1'].replace(',', ' ');
                var data0 = data['0'].replace(',', ' ');
                $('#name').val(data1);
                $('#lastName').val(data0);
                $('#spinnerName').removeClass('spinner');
                $('#spinnerLastName').removeClass('spinner');

            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        console.log('datos incorrectos');
    }

}


let timerInterval

function executeExample(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se desactivará toda la información del cliente.",
        type: 'info',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, desactivar',
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
            location.href = "<?php echo base_url();?>admin/clientes/delete/" + _id;
        }
    })
}

function searchEmail() {
    var email = $('#email_client_add').val();
    var ID = '0';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/client',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_client_add').html(" ");
                $('#add_client_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_client_add').html("Correo eléctronico no disponible");
                $('#add_client_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_client_add').html(" ");
                $('#add_client_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
