<?php $moneda = $this->crud_model->get_info("moneda"); $branch_id = $this->session->userdata("branch_id");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar Cuentas Bancarias
                            <span class="d-block text-muted pt-2 font-size-sm">Administra tus cuentas bancarias.</span>
                        </h3>
                    </div>
                    <?php  $this->db->where("status",1);
                        $this->db->group_start();
                        $this->db->where('branch_id',$branch_id);
                        $this->db->or_where('branch_id',0);
                        $this->db->group_end(); 
                        $branch_accounts = $this->db->get('account_bank'); $accounts = $this->crud_model->get_accounts_bank(); ?>
                    <div class="card-toolbar">
                        <?php if($branch_accounts->num_rows() > 0 && ($user_type == 1 || $permisos['reportes_cuentas'] == 1)): ?>
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
                                        <a href="<?php echo base_url();?>admin/export_excel/account_banks"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url();?>admin/export_pdf/account_banks"
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
                        <?php endif; if($user_type == 1 || $permisos['crear_cuentas'] == 1):?>
                        <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                            data-target="#exampleModalSizeLg">
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
                            </span> Nueva cuenta
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php  if($branch_accounts->num_rows() > 0 ):?>
                    <form class="mb-15">
                        <div class="row mb-6">

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Cuenta:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Mi Cuenta"
                                    data-col-index="1">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>No. de cuenta:</label>
                                <input type="text" class="form-control datatable-input" placeholder="8905755210"
                                    data-col-index="2">
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Banco:</label>
                                <select class=" form-control datatable-input" data-col-index="3">
                                    <option value="">Seleccionar</option>
                                    <?php $bancos = $this->db->get_where('account_bank', array('bank_id !='=>0))->result_array(); foreach ($bancos as $bc) :?>
                                    <option
                                        value="<?php echo $this->db->get_where('bank', array('bank_id'=>$bc['bank_id']))->row()->name;?>">
                                        <?php echo $this->db->get_where('bank', array('bank_id'=>$bc['bank_id']))->row()->name;?>
                                    </option>
                                    <?php endforeach ;?>
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
                                    <th>Nombre de la cuenta</th>
                                    <th>No. de cuenta</th>
                                    <th>Banco</th>
                                    <th>Tipo de cuenta</th>
                                    <th>Moneda</th>
                                    <th>Saldo actual</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="results">
                                <?php $n = 1 ; foreach($branch_accounts->result_array() as $rows):?>
                                <tr class="row_bank">
                                    <td><?php echo $n++;?></td>
                                    <td><b><?php echo $rows['name_account'];?></b></td>
                                    <td><span
                                            class="text-warning font-weight-bolder"><?php echo $rows['no_account'];?></span>
                                    </td>
                                    <td><?php if($rows['bank_id'] == 0) echo "-----"; else echo $this->db->get_where('bank', array('bank_id' => $rows['bank_id']))->row()->name;?>
                                    </td>
                                    <td><span class="font-weight-bold"><a
                                                href="javascript:void(0);"><?php if($rows['bank_id'] == 0) echo "-----"; else echo $rows['type'];?></a></span></td>
                                    <td><span class="font-weight-bold"><?php echo $rows['currency'];?></span></td>
                                    <td><span
                                            class="label label-lg font-weight-bold label-light-info label-inline"><?php echo $moneda.number_format($rows['current_balance'], '2', '.', ',');?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php if($user_type == 1 || $permisos['editar_cuentas'] == 1):?>
                                            <a href="javascript:void(0);"
                                                onclick="showAjaxModal('<?php echo base_url();?>modal/popup/editar_cuenta_bancaria/<?php echo $rows['account_bank_id'];?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Editar datos">
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
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['transferir_cuentas'] == 1):?>
                                            <a href="javascript:void(0);"
                                                onclick="showModal('<?php echo base_url();?>modal/popup/transferir_fondos/<?php echo $rows['account_bank_id'];?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Transferir">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M16.5428932,17.4571068 L11,11.9142136 L11,4 C11,3.44771525 11.4477153,3 12,3 C12.5522847,3 13,3.44771525 13,4 L13,11.0857864 L17.9571068,16.0428932 L20.1464466,13.8535534 C20.3417088,13.6582912 20.6582912,13.6582912 20.8535534,13.8535534 C20.9473216,13.9473216 21,14.0744985 21,14.2071068 L21,19.5 C21,19.7761424 20.7761424,20 20.5,20 L15.2071068,20 C14.9309644,20 14.7071068,19.7761424 14.7071068,19.5 C14.7071068,19.3673918 14.7597852,19.2402148 14.8535534,19.1464466 L16.5428932,17.4571068 Z"
                                                                fill="#000000" fill-rule="nonzero" />
                                                            <path
                                                                d="M7.24478854,17.1447885 L9.2464466,19.1464466 C9.34021479,19.2402148 9.39289321,19.3673918 9.39289321,19.5 C9.39289321,19.7761424 9.16903559,20 8.89289321,20 L3.52893218,20 C3.25278981,20 3.02893218,19.7761424 3.02893218,19.5 L3.02893218,14.136039 C3.02893218,14.0034307 3.0816106,13.8762538 3.17537879,13.7824856 C3.37064094,13.5872234 3.68722343,13.5872234 3.88248557,13.7824856 L5.82567301,15.725673 L8.85405776,13.1631936 L10.1459422,14.6899662 L7.24478854,17.1447885 Z"
                                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['eliminar_cuentas'] == 1):?>
                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Eliminar cuenta"
                                                onclick="executeExample('<?php echo $rows['account_bank_id'];?>')">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog  modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva cuenta bancaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form class="form" action="<?php echo base_url();?>admin/cuentas_bancarias/create" method="POST">
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
                                <label>Nombre de la cuenta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="property" class="form-control" id="property"
                                        placeholder="Propietario de la cuenta" aria-label="Text input with checkbox"
                                        required="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No. de cuenta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="no_account" class="form-control" id="no_account"
                                        aria-label="Text input with checkbox" required="" pattern="[0-9-]+$" />
                                </div>
                                <small class="text-danger">Ingrese únicamente números y guiones</small>
                            </div>
                        </div>
                        <?php $caja = $this->db->get_where('account_bank', array('bank_id' => 0, 'status' => 1, 'branch_id' =>$branch_id)); ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Banco <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="select_bank" name="bank" required=""
                                        onchange="show_new_cat(this.value), datos_caja(this.value)">
                                        <option value="">Seleccionar</option>
                                        <optgroup label="Opción">
                                            <option value="-1">Agregar nuevo</option>
                                        </optgroup>
                                        <?php if($caja->num_rows() < 1): ?>
                                        <optgroup label="Caja">
                                            <option value="0">Caja Chica</option>
                                        </optgroup>
                                        <?php endif; ?>
                                        <optgroup label="Registrados">
                                            <?php $this->db->order_by('name', 'ASC');
                                                $banks = $this->db->get('bank')->result_array();
                                                foreach($banks as $rs):?>
                                            <option value="<?php echo $rs['bank_id']?>"><?php echo $rs['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo de cuenta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" name="type_account" required="" id="type_account">
                                        <option value="">Seleccionar</option>
                                        <option value="Cheques">Cuenta de cheques</option>
                                        <option value="Ahorro">Cuenta de ahorro</option>
                                        <option value="Monetaria">Cuenta monetaria</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6" id="new_cat1">
                            <div class="form-group">
                                <label>Nuevo Banco <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="name_bank" id="n_bank" class="form-control"
                                        aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6" id="new_cat2">
                            <div class="form-group">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Moneda <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" name="currency" required="" id="currency">
                                        <option value="">Seleccionar</option>
                                        <option value="(Q)">Quetzaltes</option>
                                        <option value="($ USD)">Dolares</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Saldo actual:</label>
                                <div class="input-group">
                                    <input type="number" required="" min="0" step="0.01" name="current_balance"
                                        value="0.00" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6" id="branch">
                            <div class="form-group">
                                <label>Sucursal:</label>
                                <div class="radio-inline">
                                    <label class="radio radio-success" id="all_branch">
                                        <input type="radio" class="form-control" name="branch" value="0" checked>
                                        <span></span>Todas
                                    </label>
                                    <label class="radio radio-success">
                                        <input type="radio" class="form-control" id="current_branch" name="branch" value="<?php echo $branch_id; ?>">
                                        <span></span>Actual (<?php echo $this->crud_model->getBranch($branch_id); ?>)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Agregar</button>
                </div>
            </form>
        </div>

        <script type="text/javascript">
        var branch_id = '<?php echo $branch_id; ?>';

        $('#new_cat1').hide();
        $('#new_cat2').hide();

        function show_new_cat(value) {
            if (value < 0) {
                document.getElementById("n_bank").required = true;
                $('#new_cat1').show(500);
                $('#new_cat2').show(500);
            } else {
                document.getElementById("n_bank").required = false;
                $('#new_cat1').hide(500);
                $('#new_cat2').hide(500);
            }
        }

        function datos_caja(value) {
            if (value == 0) {
                document.getElementById("property").value = "Caja Chica";
                $('#property').attr("readonly", "true");
                document.getElementById("no_account").value = "00000000";
                $('#no_account').attr("disabled", "true");
                document.getElementById("type_account").value = "";
                $('#type_account').attr("disabled", "true");
                document.getElementById("currency").value = "(Q)";
                $('#current_branch').attr("checked", "true");
                $('#all_branch').hide(500);
            } else {
                $('#all_branch').show(500);
                $('.branch').removeAttr("disabled");
                $('#property').removeAttr("disabled");
                $('#no_account').removeAttr("disabled");
                $('#type_account').removeAttr("disabled");
            }
        }
        </script>




        <script type="text/javascript">
        let timerInterval

        function executeExample(admin_id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará la cuenta bancaria",
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
                    location.href = "<?php echo base_url();?>admin/cuentas_bancarias/delete/" + admin_id;
                }
            })
        }

        window.onload = function() {
            $("#filter").keyup(function() {
                var filter = $(this).val(),
                    count = 0;
                $('#results .row_bank').each(function() {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show();
                        count++;
                    }
                });
            });
        }
        </script>

    </div>
</div>