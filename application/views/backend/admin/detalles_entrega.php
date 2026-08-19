<?php  
	$delivery = $this->db->get_where('delivery',array('code'=>$code))->row();
	$delivery_id = $delivery->delivery_id;
    $moneda = $this->crud_model->get_info("moneda");
    setlocale(LC_TIME, "spanish");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <?php if($delivery_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <h5>Detalles de la entrega:</h5>
                    <div class="border-bottom"></div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Código de entrega:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-primary label-inline" style="font-size: 15px;"><?php echo $delivery->code;?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Código de venta:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-primary label-inline" style="font-size: 15px;"><?php echo $delivery->sale_code;?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Encargado de entrega:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-primary label-inline" style="font-size: 15px;"><?php echo $this->crud_model->getName('admin',$delivery->responsable_id) ;?></span>
                            </div>
                        </div>
                        <?php $venta = $this->db->get_where('sales', array('code'=>$delivery->sale_code))->row_array(); ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Cliente:</b></label><br>
                                <p style="font-size: 15px;">
                                    <?php echo $venta['client_id'] != 0 ? $this->crud_model->getName('client',$venta['client_id']) : $venta['name']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>NIT:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;"><?php echo ($venta['nit']) ? $venta['nit'] : 'Sin datos';?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Celular:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;"><?php echo ($venta['phone']) ? $venta['phone'] : 'Sin datos';?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Dirección de entrega:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;"><?php echo $delivery->address;?></span>
                            </div>
                        </div>
                        <?php $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$delivery->service_transport))->row_array();
                            if ($servicio['transport_id'] != ""):
                                $transport = $this->db->get_where('transport', array('transport_id'=>$servicio['transport_id']))->row_array();?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Vehículo:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-dark label-inline" style="font-size: 15px;"><?php echo $transport['name'];?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Placa:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-dark label-inline" style="font-size: 15px;"><?php echo $transport['license_plate'];?></span>
                            </div>
                        </div>
                        <?php elseif ($servicio['company_id'] != ""):
                                $company = $this->db->get_where('delivery_company', array('delivery_company_id' => $servicio['company_id']))->row_array();?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Empresa:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-dark label-inline" style="font-size: 15px;"><?php echo $company['name'];?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Código:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-dark label-inline" style="font-size: 15px;"><?php echo $servicio['code'];?></span>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Fecha asignada:</b></label><br>
                                <?php $fecha_asig = date("d-m-Y ", strtotime($delivery->fecha_asignada)); $nueva_asig = strftime("%d de %B de %Y", strtotime($fecha_asig));?>
                                <span class="label label-lg font-weight-bold label-light-success label-inline" style="font-size: 15px;"><?php echo $nueva_asig;?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Fecha y hora de entrega:</b></label><br>
                                <?php $fecha_entr = date("d-m-Y h:i A", strtotime($delivery->fecha_entrega)); $nueva_entr = strftime("%d de %B de %Y - %H:%M", strtotime($fecha_entr)); ?>
                                <span class="label label-lg font-weight-bold label-light-success label-inline" style="font-size: 15px;"><?php echo $nueva_entr;?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Costo inicial:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;">
                                    <?php echo $moneda.number_format($delivery->cost,2,'.',',');?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Costo adicional:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;">
                                    <?php echo $moneda.number_format($delivery->cost_extra,2,'.',',');?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Costo total:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;">
                                    <?php echo $moneda.number_format($delivery->total,2,'.',',');?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Método de pago:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;">
                                <?php $metodo = $servicio['payment_method']; if($metodo == 0) echo 'Efectivo'; elseif($metodo == 1) echo 'Cheque'; elseif($metodo == 2) echo 'Targeta de débito'; elseif($metodo == 3) echo 'Transferencia';?>
                                </span>
                            </div>
                        </div>
                        <?php $origen = ''; if($metodo == 0){ $origen = '(Caja Chica) - Efectivo';} 
                            else{
                                $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $servicio['account_bank_id']))->row_array();
                                $bank = $this->db->get_where('bank', array('bank_id' => $cuenta['bank_id']))->row()->name;
                                $origen = '('.$bank.') - '.$cuenta['name_account'];
                            }?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Origen:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-danger label-inline" style="font-size: 15px;"><?php echo $origen;?></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Estado de la entrega:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-<?php if($delivery->estado == 1){ echo 'blue';}elseif($delivery->estado == 2){echo 'success';}elseif($delivery->estado == 3){echo 'danger';}?> label-inline"  style="font-size: 15px;">
                                    <?php if($delivery->estado == 1){ echo 'En ruta'; }elseif($delivery->estado == 2){ echo 'Entregado' ; }elseif($delivery->estado == 3){ echo 'Cancelado' ; }?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Estado de registro:</b></label><br>
                                <span class="label label-lg font-weight-bold label-light-<?php if($delivery->status == 0){ echo 'danger';}elseif($delivery->status == 1){echo 'success';}?> label-inline"  style="font-size: 15px;">
                                    <?php if($delivery->status == 0){ echo 'Anulado'; }elseif($delivery->status == 1){ echo 'Activo' ; }?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Notas:</b></label><br>
                                <?php echo $delivery->notes;?>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <a href="<?php echo  base_url().'admin/entregas/' ;?>" class="btn btn-light-primary font-weight-bolder">
                                <span class="svg-icon svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M8.29606274,4.13760526 L1.15599693,10.6152626 C0.849219196,10.8935795 0.826147139,11.3678924 1.10446404,11.6746702 C1.11907213,11.6907721 1.13437346,11.7062312 1.15032466,11.7210037 L8.29039047,18.333467 C8.59429669,18.6149166 9.06882135,18.596712 9.35027096,18.2928057 C9.47866909,18.1541628 9.55000007,17.9721616 9.55000007,17.7831961 L9.55000007,4.69307548 C9.55000007,4.27886191 9.21421363,3.94307548 8.80000007,3.94307548 C8.61368984,3.94307548 8.43404911,4.01242035 8.29606274,4.13760526 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M23.2951173,17.7910156 C23.2951173,16.9707031 23.4708985,13.7333984 20.9171876,11.1650391 C19.1984376,9.43652344 16.6261719,9.13671875 13.5500001,9 L13.5500001,4.69307548 C13.5500001,4.27886191 13.2142136,3.94307548 12.8000001,3.94307548 C12.6136898,3.94307548 12.4340491,4.01242035 12.2960627,4.13760526 L5.15599693,10.6152626 C4.8492192,10.8935795 4.82614714,11.3678924 5.10446404,11.6746702 C5.11907213,11.6907721 5.13437346,11.7062312 5.15032466,11.7210037 L12.2903905,18.333467 C12.5942967,18.6149166 13.0688214,18.596712 13.350271,18.2928057 C13.4786691,18.1541628 13.5500001,17.9721616 13.5500001,17.7831961 L13.5500001,13.5 C15.5031251,13.5537109 16.8943705,13.6779456 18.1583985,14.0800781 C19.9784273,14.6590944 21.3849749,16.3018455 22.3780412,19.0083314 L22.3780249,19.0083374 C22.4863904,19.3036749 22.7675498,19.5 23.0821406,19.5 L23.3000001,19.5 C23.3000001,19.0068359 23.2951173,18.2255859 23.2951173,17.7910156 Z" fill="#000000" fill-rule="nonzero"/>
                                        </g>
                                    </svg>
                                </span>Regresar
                            </a>
                        </div>
                        &nbsp;
                        <?php if($user_type == 1 || $permisos['eliminar_entregas'] == 1):?>
                        <div>
                            <a href="javascript:void(0);" onclick="executeExample('<?php echo $code; ?>')" class="btn btn-light-danger font-weight-bolder">
                                <span class="svg-icon svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg>
                                </span>Anular
                            </a>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<script type="text/javascript">
let timerInterval

function executeExample(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Solo se cancelará el envío, no afectará la venta, pero no se revertirá esta acción!.",
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
            location.href = "<?php echo base_url();?>admin/entregas/anular/" + _id;
        }
    })
}
</script>