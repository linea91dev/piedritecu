<?php $data = $this->db->limit(1)->get_where('income', array('income_id'=>$ID));$moneda = $this->crud_model->get_info("moneda") ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Detalles del ingreso
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/ingresos/' ;?>"
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

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Cantidad:</label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-warning label-inline"><?php echo $moneda.number_format($row['amount'],2,'.',',') ;?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Referencia:</label>
                                    <div class="input-group">
                                        <a href="<?php echo base_url().'admin/detalles_venta/'.$row['sale_ref'];?>" data-toggle="tooltip" title="" data-original-title="Clic para más detalles"><span class="label label-lg font-weight-bold label-light-info label-inline"><?php echo $row['sale_ref'] ;?></span></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Destino</label>
                                    <div class="input-group">
                                        <span
                                            class="label label-lg font-weight-bold label-light-success label-inline"><?php if( $income['origin'] == 0 ){echo 'Caja chica';}else{ echo $this->db->get_where('bank', array('bank_id'=>$income['origin']))->row()->name;};?></span>
                                    </div>
                                </div>
                            </div>
                            <?php if ($income['factura_img']!=''):?>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Comprobante:</label>
                                    <center>
                                        <img src="<?php echo base_url().'uploads/pagos/'.$income['factura_img']?>">
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