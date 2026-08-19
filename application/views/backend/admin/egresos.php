<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar egresos
                            <u><?php if($status == 'activos') echo 'activos'; elseif($status == 'inactivos') echo 'anulados';?></u>
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona los egresos de tus
                                tiendas.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($status == 'inactivos'):?>
                        <a href="<?php echo base_url();?>admin/egresos/" class="btn btn-success font-weight-bolder">
                            Ver Activos
                        </a>
                        <?php elseif($status == 'activos'): ?>
                        <a href="<?php echo base_url();?>admin/egresos/inactivos/"
                            class="btn btn-danger font-weight-bolder">
                            Ver Anulados
                        </a>
                        <?php endif;?>
                        &nbsp;
                        <?php if($expenses->num_rows()> 0 && ($user_type == 1 || $permisos['reportes_egresos'] == 1)):?>
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
                            <?php $state = 0; if ($status == 'activos') { $state = 1; } elseif ($status == 'inactivos') { $state = 0; }?>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li
                                        class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/egreso/'.$state;?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/egresos/'.$state;?>"
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
                        <?php endif; if($user_type == 1 || $permisos['crear_egresos'] == 1):?>
                        <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                            data-target="#modalEgreso">
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
                            </span> Nuevo gasto
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($expenses->num_rows() > 0):?>
                    <form class="mb-15">
                        <div class="row mb-6">

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha:</label>
                                <input type="text" class="form-control datatable-input" id="kt_datepicker"
                                    placeholder="mm/dd/aaaa" data-col-index="1" readonly>
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Responsable:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres"
                                    data-col-index="3">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Entidad:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Proveedor"
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
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Responsable</th>
                                    <th>Factura/Comprobante</th>
                                    <th>Origen</th>
                                    <th>Entidad a quien compró o pagó</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n=1; foreach($expenses->result_array() as $row):?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><b><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?></b></td>
                                    <td><span
                                            class="text-warning font-weight-bolder"><?php echo $moneda.number_format($row['amount'], '2', '.', ',');?></span>
                                    </td>
                                    <td><?php echo $this->crud_model->getName('admin', $row['responsable']);?></td>
                                    <?php if($row['factura_img'] != ''):?>
                                    <td><span class="font-weight-bold"><a href="javascript:void(0);"
                                                onclick="showModal('<?php echo base_url();?>modal/popup/ver_factura/<?php echo base64_encode($row['expense_id']);?>');">Ver
                                                factura</a></span></td>
                                    <?php else:?>
                                    <td><span class="font-weight-bold"><a href="javascript:void(0);">n/a</a></span></td>
                                    <?php endif;?>
                                    <?php if($row['origin'] > 0): $bnkid = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->bank_id;  $acc_name = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->name_account;  $acc_type = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->type;  $nm_bank = $this->db->get_where('bank', array('bank_id' => $bnkid))->row()->name; ?>
                                    <td><span
                                            class="label label-lg font-weight-bold label-light-info label-inline">(<?php echo $nm_bank;?>)
                                            <?php echo $acc_name;?> - <?php echo $acc_type;?></span></td>
                                    <?php else:?>
                                    <td><span
                                            class="label label-lg font-weight-bold label-light-info label-inline">(Caja
                                            Chica)
                                            - Efectivo</span></td>
                                    <?php endif;?>
                                    <td>
                                        <?php if($row['table_reference'] == 'shopping') {
                                            $provider_id = $this->db->get_where('shopping', array('code'=>$row['reference_id']))->row()->provider;
                                            echo $this->crud_model->getSingleName("provider", $provider_id);
                                        } else {
                                            echo $row['provider'];
                                        }?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url().'admin/detalles_egreso/'.$row['expense_id'];?>"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Ver detalle">
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
                                            <?php if ($status != 'inactivos' && ($user_type == 1 || $permisos['editar_egresos'] == 1)):?>
                                            <a href="javascript:void(0);"
                                                onclick="showAjaxModal('<?php echo base_url();?>modal/popup/editar_egreso/<?php echo $row['expense_id']; ?>')"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Editar egreso">
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
                                            <?php endif; ?>
                                            &nbsp;
                                            <!--<a href="javascript:void(0);"
                                                onclick="executeExample('<?php echo $row['expense_id'];?>')"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Eliminar egreso">
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
                                            </a>-->
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


<div class="modal fade" id="modalEgreso" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog  modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo gasto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form class="form" action="<?php echo base_url();?>admin/egresos/create" method="POST"
                enctype="multipart/form-data">
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
                                <label><b>Fecha:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="date" class="form-control datatable-input" id="kt_datepicker_1" placeholder="mm/dd/aaaa" readonly
                                    data-col-index="1" value="<?php echo date('m/d/Y');?>"
                                        required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Monto:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $moneda;?></span>
                                    </div>
                                    <input type="number" id="amount" name="amount" step="0.01" class="form-control"
                                        min="1" aria-label="Text input with checkbox" required="true" value="0.00"
                                        oninput="verificar()">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Responsable:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <!--<input type="text" name="responsable" class="form-control" aria-label="Text input with checkbox" required="true">-->
                                    <select class="form-control" name="responsable" required="true">
                                        <option value="">Seleccionar</option>
                                        <?php $respons = $this->db->get_where('admin', array('status'=>1))->result_array();
												foreach($respons as $res): ?>
                                        <option value="<?php echo $res['admin_id'];?>">
                                            <?php echo $res['name'].' '.$res['last_name'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Factura:</b></label>
                                <div class="custom-file">
                                    <input type="file" name="factura_img" accept="image/*, .pdf"
                                        class="custom-file-input" id="customFile"
                                        onchange="onLoadImage(event.target.files)">
                                    <label class="custom-file-label" for="customFile" id="imgLabel">Elegir
                                        imagen o documento</label>
                                </div>
                                <label>Archivo seleccionado: <b><span id="imgName">Niguno</span></b></label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Entidad a quien compró o pagó:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="provider" class="form-control"
                                        placeholder="Ej: <?php echo $this->crud_model->getNameSistema();?>"
                                        aria-label="Text input with checkbox" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Origen:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="origin" name="origin" required="true"
                                        onchange="verificar()">
                                        <option value="" selected>Seleccionar</option>
                                        <option value="0">(Caja Chica) Efectivo</option>
                                        <?php $cuentas = $this->crud_model->get_accounts_to_pay();
                                            foreach($cuentas->result_array() as $cts):?>
                                        <option value="<?php echo $cts['account_bank_id'];?>">
                                            <?php echo '('.$this->db->get_where('bank', array('bank_id' => $cts['bank_id']))->row()->name.') - '.$cts['name_account'];?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <span class="text-danger" id="msg_error"></span>
                            </div>
                        </div>

                        <!--<div class="col-sm-12" id="bancos" style="display: none;">
                            <div class="form-group">
                                <label><b>Banco:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" name="bank_id">
                                        <?php /* $bancos = $this->db->get('account_bank')->result_array();
												foreach($bancos as $ban):
													if ($ban['bank_id'] != 0): ?>
                                        <option value="<?php echo $ban['account_bank_id']; ?>">
                                            <?php echo '('.$this->db->get_where('bank', array('bank_id' => $ban['bank_id']))->row()->name.') - '.$ban['name_account'];?>
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach; */ ?>
                                    </select>
                                </div>
                            </div>
                        </div>-->

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Descripción</b></label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox"
                                        name="details"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="success">Guardar</button>
                </div>

            </form>
        </div>
    </div>
</div>






<script type="text/javascript">
function onLoadImage(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName')
            .innerHTML = files[0].name;
    } else {
        document
            .getElementById('imgName')
            .innerHTML = 'Ninguno';
    }
}


let timerInterval

function executeExample(expense_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará el egreso!",
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
            location.href = "<?php echo base_url();?>admin/egresos/delete/" + expense_id;
        }
    })
}

function bancos(metodo) {
    if (metodo == 'bancos') {
        $('#bancos').show(500);
    } else {
        $('#bancos').hide(500);
    }
}

function verificar() {
    let bank_id = 0;
    var banco = $('#origin').val();
    $total = $('#amount').val();

    bank_id = banco;

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/saldo_cuenta/',
        data: {
            bank_id: bank_id,
            total: $total,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_error').html("");
                $('#success').removeAttr("disabled");
            } else if (response == 2) {
                $('#msg_error').html('El pago se realizará, pero la cuenta quedara en cero');
                $('#success').removeAttr("disabled");
            } else if (response == 3) {
                $('#msg_error').html('La cuenta no tiene los fondos suficientes');
                $('#success').attr("disabled", "true");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>