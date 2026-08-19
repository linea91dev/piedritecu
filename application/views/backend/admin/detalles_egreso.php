<?php $data = $this->db->get_where('expense', array('expense_id'=>$ID)); $moneda = $this->crud_model->get_info("moneda"); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Detalles del egreso
                        </h3>
                    </div>
                    <?php $url = ''; if($data->row()->status == 1) $url = base_url().'admin/egresos/'; else $url = base_url().'admin/egresos/inactivos';?>
                    <div class="card-toolbar">
                        <a href="<?php echo $url;?>" class="btn btn-light-primary font-weight-bolder">
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
                            </span> Regresar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-15">
                        <div class="row align-items-center">
                            <?php foreach ($data->result_array() as $row):?>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> <b>Fecha:</b> </label>
                                    <div class="input-group">
                                        <b>
                                            <?php setlocale(LC_TIME, "spanish");$Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));$Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); echo $Mes_Anyo;?></b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Responsable:</b></label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;"><?php echo $this->crud_model->getName('admin', $row['responsable']);?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Proveedor:</b></label>
                                    <div class="input-group">
                                        <span class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;">
                                            <?php if($row['table_reference'] == 'shopping') {
                                                $provider_id = $this->db->get_where('shopping', array('code'=>$row['reference_id']))->row()->provider;
                                                echo $this->crud_model->getSingleName("provider", $provider_id);
                                            } else {
                                                echo $row['provider'];
                                            }?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Estado:</b></label>
                                    <div class="input-group">
                                        <?php if($row['status'] == 1): ?>
                                        <span
                                            class="label label-lg font-weight-bold label-light-success label-inline" style="font-size: 15px;">Activo</span>
                                        <?php else: ?>
                                        <span
                                            class="label label-lg font-weight-bold label-light-danger label-inline" style="font-size: 15px;">Anulado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Tipo:</b></label>
                                    <div class="input-group">
                                        <span class="label label-lg font-weight-bold label-light-info label-inline" style="font-size: 15px;">
                                        <?php if(!$row['table_reference']) echo 'Gastos varios';
                                            elseif($row['table_reference'] == 'delivery') echo 'Entrega';
                                            elseif($row['table_reference'] == 'shopping') echo 'Compra';
                                            elseif($row['table_reference'] == 'payroll') echo 'Planilla';
                                            elseif($row['table_reference'] == 'service_transport') echo 'Servicio de transporte';?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Origen:</b></label>
                                    <div class="input-group">
                                        <span class="label label-lg font-weight-bold label-light-success label-inline" style="font-size: 15px;">
                                            <?php if( $row['origin'] == 0 ) { echo '(Caja Chica) - Efectivo'; }
                                                else { 
                                                    $account = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->name_account;
                                                    $bank_id = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->bank_id;
                                                    $bank = $this->db->get_where('bank', array('bank_id' => $bank_id))->row()->name;
                                                    echo '('.$bank.') - '.$account;
                                                }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Saldo inicial de la cuenta:</b> </label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;"><?php echo $moneda.number_format($row['saldo_inicial'],2,'.',',');?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Débito:</b> </label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;"><?php echo $moneda.number_format($row['amount'],2,'.',',');?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><b>Nuevo saldo de la cuenta:</b> </label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-warning label-inline" style="font-size: 15px;"><?php echo $moneda.number_format($row['nuevo_saldo'],2,'.',',');?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><b>Detalle:</b> </label>
                                    <div class="input-group">
                                        <p><?php echo $row['details'];?></p>
                                    </div>
                                </div>
                            </div>
                            <?php if($row['status'] == 0): ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><b>Motivo de anulación:</b> </label>
                                    <div class="input-group">
                                        <p><?php echo $row['reason'];?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($row['factura_img']!=''):?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><b>Comprobante:</b></label>
                                    <center>
                                        <?php if ($row['factura_type'] == 'image'):?>
                                        <img src="<?php echo base_url().'uploads/vouchers/'.$row['factura_img'];?>"/>
                                        <?php elseif($row['factura_type'] == 'pdf'):?>
                                        <embed src="<?php echo base_url().'uploads/vouchers/'.$row['factura_img'];?>" width="700px" height="700px"/>
                                        <?php endif;?>
                                    </center>
                                </div>
                            </div>
                            <?php endif;?>

                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>