<?php $data = $this->db->get('provider') ; $SendEmail = $this->db->get_where('settings', array('type'=>'noti_email'))->row()->description ; $SendWhts = $this->db->get_where('settings', array('type'=>'whatsapp'))->row()->description; $WhtsCode = $this->db->get_where('settings', array('type'=>'code'))->row()->description ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar proveedores
                            <span class="d-block text-muted pt-2 font-size-sm">Administra la información de tus
                                proveedores.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0 && ($user_type == 1 || $permisos['reportes_proveedores'] == 1)): ?>

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
                                        <a href="<?php echo base_url().'admin/export_excel/proveedores'?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/proveedores'?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; if($user_type == 1 || $permisos['crear_proveedores'] == 1):?>
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
                            </span> Nuevo proveedor
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0): ?>

                    <form class="mb-15">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Empresa:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Ejemplo: <?php echo $this->crud_model->get_info('name');?>" data-col-index="1">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Encargado:</label>
                                <input type="text" class="form-control datatable-input" data-col-index="2" placeholder="Nombres">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Estado:</label>
                                <select class=" form-control datatable-input" data-col-index="7">
                                    <option value="">Seleccionar</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
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
                                    <th>Empresa</th>
                                    <th>Encargado</th>
                                    <th>Teléfono</th>
                                    <th>WhatsApp</th>
                                    <th>Correo</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n=1; foreach ($data->result_array() as $row): ?>
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td><b class="text-info"><?php echo $row["name"];?></b></td>
                                    <td><?php echo $row['manager'];?></td>
                                    <td><span class="text-warning font-weight-bolder"><a style="color: #FFA800 !important;" href="tel:+502<?php echo $row['phone'];?>">
                                                <?php echo ($row['phone']=='') ? 'Sin datos':'(+502)'.$row['phone'] ;?></a>
                                        </span></td>
                                    <td><span class="text-warning font-weight-bolder"><a target="_blank" style="color: #99bf2d !important;" href="https://api.whatsapp.com/send?phone=502<?php echo $row['phone'];?>">
                                                <?php echo ($row['phone']=='') ? 'Sin datos':'(+502)'.$row['phone'] ;?></a>
                                        </span></td>
                                    <td><a style='color:#3F4254;' href="mailto:<?php echo $row['email'];?>">
                                            <?php echo $row['email'];?></a></td>
                                    <td><?php echo $row['address'];?></td>
                                    <td class='text-center'>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['status']==1) ? 'success':'danger' ;?> label-inline"><?php echo ($row['status']==1) ? 'Activo':'Inactivo' ;?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url();?>admin/perfil_proveedor/<?php echo $row['provider_id'];?>" data-toggle="tooltip" data-original-title="Actividad del proveedor" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <?php if($row['status']==1):?>
                                            <?php if($user_type == 1 || $permisos['editar_proveedores'] == 1):?>
                                            <a href="javascript:;" data-toggle="tooltip" data-original-title="Editar proveedor" onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_proveedor/'.$row['provider_id'];?>');" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
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
                                            <?php if($user_type == 1 || $permisos['estado_proveedores'] == 1):?>
                                            <a href="javascript:;" data-toggle="tooltip" data-original-title="Desactivar proveedor" onclick="executeExample('<?php echo $row['provider_id'];?>')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                                            <?php if($user_type == 1 || $permisos['estado_proveedores'] == 1):?>
                                            <a href="<?php echo base_url().'admin/proveedores/active/'.$row['provider_id'];?>" data-toggle="tooltip" data-original-title="Re-activar proveedor" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                                <?php endforeach;?>
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

<form class="form" action="<?php echo base_url();?>admin/proveedores/create" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-dialog  modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo proveedor</h5>
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
                                <label>NIT <span class="text-danger">*</span></label>
                                <div class=" spinner-success spinner-left" id='spinnerNit'>
                                    <input type="text" placeholder="Ej: 89907865 sin guiones" class="form-control " name="nit" id="nit" min='0' minlength="2" maxlength="12" onblur="getNit(this.value)" onblur="getNit(this.value)" autocomplete="off" >
                                </div>
                                <div id='errorNit'></div>
                                <small>Presiona TAB para obtener los datos del contribuyente.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Empresa <span class="text-danger">*</span></label>
                                <div class="spinner-primary spinner-left" id='spinnerName'>
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" required name='name' id='c_name' />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" placeholder='55446688' aria-label="Text input with checkbox" name='phone'  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Encargado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='manager'  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>WhatsApp<?php echo ($SendWhts == 1 && $WhtsCode !='')? '<span class="text-danger">*</span>' : '' ?></label>
                                <div class="input-group">
                                    <input type="number" min='0' oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" <?php echo ($SendWhts == 1 && $WhtsCode !='')? 'required' : '' ?> aria-label="Text input with checkbox" name="whatsapp" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Correo <?php echo ($SendEmail == 1 )? '<span class="text-danger">*</span>' : '' ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='email' id='email_prov_add' oninput="()" onblur="()" <?php echo ($SendEmail == 1 )?  : '' ?> />
                                </div>
                                <span id="msg_email_prov_add" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox" name='address'></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Detalles adicionales <small>(Como servicios que ofrecen)</small></label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox" name='detail'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id='add_prov_submit'>Continuar</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
function getNit() {
    var str = $('#nit').val();
    var nit = str.replace(/-/g, "");
    var leng_nit = nit.length;
    if (leng_nit >= 7) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/getNit/',
            data: {
                nit: nit,
            },
            beforeSend: function() {
                $('#spinnerName').addClass('spinner');
            },
            success: function(response) {
                $('#c_phone').val('');
                $('#c_email').val('');
                var data = JSON.parse(response);
                if (data == 'NIT no encontrado') {
                    $('#c_name').val('NIT no encontrado');
                    $('#spinnerName').removeClass('spinner');
                } else {

                    if (data.length == 2) {
                        var data1 = data['1'].replace(',', ' ');
                        var data0 = data['0'].replace(',', ' ');
                        $('#c_name').val(data1 + ' , ' + data0);
                    } else {

                        $('#c_name').val(data['0']);
                    }

                    $('#spinnerName').removeClass('spinner');
                    $('#new-client').val('1');

                }


            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        console.log('datos no encontrados');
    }

}


function search() {
    $name = $('#name').val();
    $status = $('#status').val();

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/provider',
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
        text: "Se desactivará toda la información del proveedor.",
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
            location.href = "<?php echo base_url();?>admin/proveedores/delete/" + _id;
        }
    })
}

function searchEmail() {
    var email = $('#email_prov_add').val();
    var ID = '0';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/provider',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_prov_add').html(" ");
                $('#add_prov_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_prov_add').html("Correo eléctronico no disponible");
                $('#add_prov_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_prov_add').html(" ");
                $('#add_prov_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
