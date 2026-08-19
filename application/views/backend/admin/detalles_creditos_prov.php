<?php  
	$shop = $this->db->get_where('shopping',array('code'=>$code))->row();
	$sale_id = $shop->shopping_id;
    $moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <?php if($sale_id != ""): ?>
            <div class="card">
                <?php if($user_type == 1 || $permisos['reportes_creditos'] == 1):?>
                <div class="card-toolbar">
                    <a href="<?php echo base_url().'admin/export_pdf/credito/'.$code;?>" style="float: right;"
                        class="mt-2 mr-2 btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                        data-toggle="tooltip" title="" data-original-title="Imprimir">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                        fill="#000000" />
                                    <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1" />
                                </g>
                            </svg>
                        </span>
                    </a>
                </div>
                <?php endif;?>
                <div class="card-body">
                    <h5>Detalles del crédito:</h5>
                    <div class="border-bottom"></div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Vendedor:</b></label><br>
                                <?php echo $this->crud_model->getName('admin',$shop->responsable) ;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Proveedor:</b></label><br>
                                <?php if($shop->provider == 0):?>
                                <?php elseif($shop->provider > 0): $provider = $this->db->get_where('provider', array('provider_id' => $shop->provider))->row();?>
                                    <?php if ($provider->provider_id > 0):?>
                                    <?php echo $provider->name;?>
                                    <?php else: ?>
                                    <span class="label label-lg font-weight-bold label-light-danger label-inline">Eliminado</span>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>NIT:</b></label>
                                <br><?php echo $shop->destiny;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Celular:</b></label>
                                <br><?php echo ($shop->phone) ? $shop->phone : 'Sin datos';?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Dirección de facturación:</b></label><br>
                                <?php echo $provider->address;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Fecha:</b></label><br>
                                <span><?php echo $shop->date;?> </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Estado:</b></label><br>
                                <span
                                    class="label label-lg font-weight-bold label-light-<?php if($shop->credito == 1){ echo 'warning';}elseif($shop->credito == 2){echo 'success';}elseif($shop->credito == 3){echo 'danger';}elseif($shop->credito == 4){echo 'info';}?> label-inline">
                                    <?php if($shop->credito == 1){ echo 'Activo'; }elseif($shop->credito == 0){ echo 'Completados' ; }elseif($shop->credito == 3){echo 'Anulado';}elseif($shop->credito == 4){echo 'Cambio';} ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Total:</b></label><br>
                                <?php echo $moneda.number_format($shop->total,2,'.',',');?>
                            </div>
                        </div>
                        <?php if($shop->credito == 1):?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Restante:</b></label><br>
                                <?php $restante = $shop->total - $shop->total_pagado;
                                    echo $moneda.number_format($restante,2,'.',',');?>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <br>
            <div class="card ">
                <div class="card-body">
                    <h3 class="card-label text-info">Descripción de pagos</h3>
                    <div class="table-responsive">
                        <table class="table table-padded">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Responsable</th>
                                    <th class="text-center">Fecha de pago</th>
                                    <th class="text-center">Método</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-right">Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $pagos = $this->db->get_where('credit_details', array('sales_id' => $shop->shopping_id, 'status' => 1)); $n=1;
									foreach($pagos->result_array() as $pg): ?>
                                <tr>
                                    <td><span> <?php echo $n;?></span></td>
                                    <td><span> <?php echo $this->crud_model->getName("admin", $pg['responsable_id']);?></span></td>
                                    <td class="text-center"><?php echo date('d/m/Y', strtotime($pg['date']));?></td>
                                    <td class="text-center bolder">
                                        <span class="text-success"><?php echo $pg['method'];?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moneda; echo ($pg['amount'] == "") ? number_format(0,2,'.',',') : number_format($pg['amount'],2,'.',',') ?>
                                    </td>
                                    <td class="text-right bolder">
                                        <?php if($pg['status'] == 1):?>
                                        <i class="fas fa-check text-success"></i>
                                        <?php elseif($pg['status'] == 0):?>
                                        <i class="fa fa-times text-danger"></i>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?php if($pg['status'] != 0  && ($user_type == 1 || $permisos['eliminar_pagos'] == 1)):?>
                                        <a href="javascript:void(0);" onclick="buscarCodigo('<?php echo $pg['credit_details_id']?>', '<?php echo $n;?>')"
                                            data-toggle="tooltip" data-original-title="Anular pago">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </a>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php $n++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                            <div class="position-absolute opacity-30 top-0 right-0">
                                <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
                                        <g clip-path="url(#clip0)">
                                            <path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
                                            <path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
                                            <path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
                                            <path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
                                            <path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
                                            <path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
                                            <path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="font-weight-boldest font-size-h5">TOTAL PAGADO</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm"
                                    id="total"><?php echo $moneda.number_format($shop->total_pagado,2,'.',',')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12"  id="codigoAuth">
                        <hr>
                        <div class="form-group">    
                            <label> Código de autorización para eliminar pago: <span class="text-danger">*</span><span id="info_pago"></span></label>
                            <div class=" spinner-success spinner-left" id='spinnerCode'>
                                <input type="password" autocomplete="off" class='form-control' id='code'
                                    placeholder='Ingresa el código de autorización' autofocus
                                    onblur="getCodigo(this.value)">
                            </div>
                            <div id="mensajeError"> </div>
                            <a href="javascript:void(0)" class="btn btn-danger" id="eliminarPago" style="float: right;" hidden>Continuar</a>
                            <input type="hidden" id="id_pago" value="" />
                        </div>

                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
        
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-toolbar">
                        <a href="<?php echo base_url().'admin/compras';?>" style="float: right;"
                            class="btn btn-light-primary font-weight-bolder">
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
                            </span> Ir a Compras
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form" action="<?php echo base_url();?>admin/detalles_credito/"
                                        method="POST" enctype="multipart/form-data">
                                        <br>
                                        <label>Ingrese el código de orden:</label>
                                        <?php if($sale_id == "" && $code != ""):?>
                                        <div class="alert alert-danger" role="alert">
                                            El código ingresado no es válido o no existe.
                                        </div>
                                        <?php endif;?>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" placeholder=""
                                                aria-describedby="basic-addon2" value="<?php echo $shop->code;?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="padding: 5px;">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <path
                                                                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                <path
                                                                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" style="float: right;" class="btn btn-primary font-weight-bold">Buscar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php if($shop->credito == 1 && ($user_type == 1 || $permisos['pagos'] == 1)): ?>
            <div class="card">
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/detalles_creditos_prov/<?php echo $code;?>/add_pay" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" name="code" value="<?php echo $code?>" />
                            <input type="hidden" name="sales_id" value="<?php echo $shop->shopping_id?>" />
                            <div class="col-sm-12">
                                <span><b>Agregar pago:</b></span>
                                <div class="border-bottom"></div><br>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Fecha:<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="date" value="<?php echo date("m/d/Y")?>" id="kt_datepicker" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Responsable:<span class="text-danger">*</span></label>
                                    <select class="form-control" name="responsable">
                                        <option value="">Seleccionar</option>
                                        <?php $resp = $this->db->get_where('admin', array('status' => 1))->result_array();
                                            foreach($resp as $rs):?>
                                        <option value="<?php echo $rs['admin_id'];?>" <?php if($rs['admin_id'] == $this->session->userdata('login_user_id')) echo 'selected';?>>
                                            <?php echo $this->crud_model->getName('admin', $rs['admin_id']);?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Pago:<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $moneda;?></span>
                                        </div>
                                        <input type="number" class="form-control" name="pago" id="pago" step="0.01" value="0.00" min="1" max="<?php echo $restante;?>" onchange="verificar_saldo()" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                <label><b>Factura:</b></label>
                                <div class="input-group">
                                    <input class="uppy-FileInput-input uppy-input-control" type="file" name="factura_img" accept="image/*" id="kt_uppy_5_input_control" style='display:none' onchange="onLoadImage_s(event.target.files)">
                                    <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold" for="kt_uppy_5_input_control">Subir factura</label>
                                </div>
                                <label>Archivo seleccionado: <b><span id="imgName_s">Niguno</span></b></label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Método:<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="metodo" id="metodo" onchange="show_accounts(this.value)">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta de crédito/débito</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Depósito">Depósito</option>
                                        </select>
                                    </div>
                                    <br>
                                    <label class="cash">Caja <span class="text-danger">*</span></label>
                                    <div class="input-group cash">
                                        <select class="form-control" name="cash_id" id="cash_id" required onchange="verificar_saldo('cash_id')">
                                            <?php $this->db->order_by('name_account', 'ASC');
                                            $this->db->where('status', '1');
                                            $this->db->group_start();
                                            $this->db->where('bank_id', '0');
                                            $this->db->or_where('bank_id IS NULL', NULL, FALSE);
                                            $this->db->group_end();
                                            $this->db->group_start();
                                            $this->db->where('branch_id', $this->session->userdata('branch_id'));
                                            $this->db->or_where('branch_id', 0);
                                            $this->db->group_end();
                                            $accounts = $this->db->get('account_bank')->result_array();
                                            foreach($accounts as $rs):?>
                                            <option value="<?php echo $rs['account_bank_id']?>"><?php echo $rs['name_account'];?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <label class="cuentas">Cuenta bancaria <span class="text-danger">*</span></label>
                                    <div class="input-group cuentas">
                                        <select class="form-control" name="account_bank" id="account_bank" required onchange="verificar_saldo('account_bank')">
                                            <?php $this->db->order_by('name_account', 'ASC');
                                            $this->db->where('status', '1');
                                            $this->db->where('bank_id !=', '0');
                                            $this->db->where('bank_id IS NOT NULL', NULL, FALSE);
                                            $this->db->group_start();
                                            $this->db->where('branch_id', $this->session->userdata('branch_id'));
                                            $this->db->or_where('branch_id', 0);
                                            $this->db->group_end();
                                            $accounts = $this->db->get('account_bank')->result_array();
                                            foreach($accounts as $rs):?>
                                            <option value="<?php echo $rs['account_bank_id']?>"><?php echo $rs['name_account'].' | '.$this->db->get_where('bank', array('bank_id' => $rs['bank_id']))->row()->name;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <small class="text-danger" id="msg_error"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Notas</label>
                                    <textarea class="form-control" name="notes" placeholder="Ingrese notas aquí"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                <button type="submit" id="success" style="float: right;" class="btn btn-primary font-weight-bold">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var moneda = '<?php echo $moneda; ?>';
    document.getElementById("account_bank").required = false;
    $('#codigoAuth').hide();
    
    $('.cuentas').hide();
    function show_accounts(value) {
        if (value != "Efectivo") {
            document.getElementById("account_bank").required = true;
            $('.cuentas').show(500);
            document.getElementById("cash_id").required = false;
            $('.cash').hide(500);
        } else {
            document.getElementById("account_bank").required = false;
            $('.cuentas').hide(500);
            document.getElementById("cash_id").required = true;
            $('.cash').show(500);
        }
        verificar_saldo();
    }
    
    function onLoadImage_s(files) {
        if (files && files[0]) {
            document
                .getElementById('imgName_s')
                .innerHTML = files[0].name;
        } else {
            document
                .getElementById('imgName_s')
                .innerHTML = 'Ninguno';
        }

    }

    function buscarCodigo(value, count) {
        $('#codigoAuth').show(500);
        $('#id_pago').val(value);
        $('#code').val('');
        $('#eliminarPago').attr('hidden', true);
        $('#eliminarPago').attr("href", "javascript:void(0);");
        $('#mensajeError').html('<small class="text-info" >Ingrese un código </small>');
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/search_pay/',
            data: {
                id_pago: value,
            },
            success: function(response) {
                $('#info_pago').html('<b> (Pago: ' + count + '.- ' + response + ') </b>');
            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    }

    function getCodigo(code) {
    var leng_code = code.length;
    var valor = 'eliminar_pagos';
    var id_pago = $('#id_pago').val();
    if (leng_code > 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/checkCodigos/',
            data: {
                code: code,
                valor: valor,
            },
            beforeSend: function() {
                $('#spinnerCode').addClass('spinner');
            },
            success: function(response) {
                if (response == 1) {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-success" >Código aceptado</small>');
                    if (valor == 'eliminar_pagos') {
                        $('#eliminarPago').removeAttr('hidden');
                        $('#eliminarPago').attr("href", "<?php echo base_url();?>admin/detalles_credito/<?php echo $code;?>/delete_pay/" + id_pago);
                    }

                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    if (valor == 'eliminar_pagos') {
                        $('#eliminarPago').attr('hidden', true);
                        $('#eliminarPago').attr("href", "javascript:void(0);");
                    }
                }

            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        $('#eliminarPago').attr('hidden', true);
        $('#mensajeError').html('<small class="text-info" >Ingrese un código </small>');
    }
}

function verificar_saldo() {
    console.log("Verificar saldo");
    var metodo = $("#metodo").val();
    if (metodo != 'Efectivo') {
        var banco = $('#account_bank').val();
    } else {
        var banco = $('#cash_id').val();
    }

    var bank_id = 0;
    $total = $('#pago').val();
    bank_id = banco;

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/saldo_cuenta/',
        data: {
            bank_id: bank_id,
            total: $total,
        },
        beforeSend: function() {
            $('#success').attr("disabled", "true");
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