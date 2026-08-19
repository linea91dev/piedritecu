<!doctype html>
<?php 
ini_set("memory_limit","500M");
?>
<?php
    
    $data_pago = $this->db->get_where('credit_details', array('credit_details_id'=>$code))->row();
    $moneda = $this->crud_model->get_info("moneda");
    $nombre = $this->db->get_where('settings', array('type'=>'name'))->row()->description;
    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombrecomercial'))->row()->description;
    $direccionemisor = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $codigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $municipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $departamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $regimen = $this->db->get_where('settings',array('type'=>'regimen'))->row()->description/100;
    $data = $this->db->get_where('sales', array('sales_id'=>$data_pago->sales_id))->row();
    $tel = $this->db->get_where('settings', array('type'=>'phone'))->row()->description;
    setlocale(LC_TIME, "spanish");
    $Nueva_Fecha = date("d-m-Y", strtotime($data->date));				
    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
    $sale = $this->db->get_where('sales', array('sales_id'=>$data_pago->sales_id))->row();
?>
<html>
    <header>
    </header>

    <body>
        <div style="width:100%; font-size: 16px; line-height: 24px; font-family: poppins; color: #555;">
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <td colspan="2">
                        <table style="width: 100%;line-height: inherit;text-align: right;font-family: poppins;">
                            <tr>
                                <td style="padding-bottom: 0px; width: 25px; height: 25px; vertical-align: top; text-align:left;" >
                                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Factura 1" style=" max-width: 180px!important; height: auto; border-radius: 150px;">
                                </td>
                                <td style="padding-bottom: 0px; vertical-align: top;text-align:right;padding-top:0px;">
                                    <p style="font-size: 20px;font-family: poppins;"><b><?php echo $nombre ;?></b></p>
                                    <p style="font-size: 12px;"><?php echo $nombreComercial;?></b></p>
                                    <p style="font-size: 12px;"><?php echo $direccionemisor.', '.$this->db->get_where('settings', array('type'=>'municipio'))->row()->description.', '.$this->db->get_where('settings', array('type'=>'departamento'))->row()->description;?></b></p>
                                    <p style="font-size: 12px;"><?php echo 'Email: otorreszarate@gmail.com';?></b></p>
                                    <p style="font-size: 10px;">NIT Emisor: <?php echo $nit;?></p>
                                    <p style="font-size: 12px;">TEL: <?php echo $tel;?></p>
                                </td>
                                <td style="padding-bottom: 0px; vertical-align: top;text-align:right;padding-top:0px;">
                                    <p style="font-size: 12px;"> <b>Recibo de pago</b></p>
                                    <p style="font-size: 12px;">Documento Tributario Electronico (DTE)</p>
                                    <p style="font-size: 10px;">Numero de DTE: <?php echo $data->numero_fel;?></b></p>
                                    <p style="font-size: 10px;">Serie: <?php echo $data->serie_fel;?></p>
                                    <p style="font-size: 10px;">No. AUTORIZACIÓN:<br> <?php echo  $data->code_fel;?></p>
                                    <p style="font-size: 10px;">Fecha Hora Certificación: <?php echo $data->date_fel;?></p>
                                    <p style="font-size: 10px;">CERTIFICADOR: INFILE, S.A. NIT: 125213</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;margin-right:10px;border: 0.5px solid #000;font-family:poppins;">
                            <tr>
                                <td colspan="2" style="padding:10px;font-family:poppins;">DATOS DEL CLIENTE</td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>NOMBRE:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $data->name;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>DIRECCIÓN:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    Ciudad
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>NIT:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $data->nit;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>Teléfono:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $data->phone;?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;border: 0.5px solid #000;font-family:poppins;">
                            <tr>
                                <td colspan="2" style="padding:10px;font-family:poppins;">
                                    DETALLE DEL CREDITO
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>CÓDIGO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $data->code;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>FECHA:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $data->date.' '.$data->time;?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>ATENDIÓ:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>-</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo '-'; //sprintf('%06d', $data->sales_id)?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="font-family:poppins;margin-top:20px; border-top:0.5px solid #000;   border-bottom:0.5px solid #000;  border-left:0.5px solid #000; border-right:0.5px solid #000; width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <th style="padding:10px;font-family:poppins;letter-spacing:1;font-weight:normal;text-align:left;" colspan="5">RECIBO DE PAGO POR</th>
                </tr>
                <tr>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">Fecha</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">Pago</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px; width: 50%">DESCRIPCIÓN</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">Metodo</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">Cajero</th>
                </tr>
                
                <tr>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                     <?php echo $data_pago->date ?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                    <?php echo 'Q'.number_format($data_pago->amount,2,".",",") ?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:left;">
                    <?php echo $data_pago->notes ?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                    <?php echo $data_pago->method ?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                    <?php echo $this->crud_model->getName("admin", $data_pago->responsable_id);?>             
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:left;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">

                    </td>
                </tr>
                <tr>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:left;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">

                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">

                    </td>
                </tr>
            </table>
            <br>
            <table cellpadding="0" cellspacing="0" style="font-family:poppins; border:0.5px solid #000; width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <td style="padding:5px;font-size: 11px; text-align:left;">
                        TOTAL ABONADO
                    </td>
                    <td style="padding:5px;font-size: 11px; text-align:left;background:#fff;color:#fff;">
                        <!--  TOTAL EN LETRAS -->
                    </td>
                    <td style="padding:5px;font-size: 11px;  text-align:center;">
                        <?php  //echo $this->crud_model->numberTowords(number_format($info['total'],2,".",""));?>
                    </td>
                    <td style="padding:5px;font-size: 11px; text-align:left;">

                    </td>

                    <td style="padding:5px;font-size: 11px; text-align:right;">
                        <?php echo $moneda.$data->total_pagado;?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px;font-size: 11px; text-align:left;">
                        TOTAL FACTURA
                    </td>
                    <td style="padding:5px;font-size: 11px; text-align:left;background:#fff;color:#fff;">
                        <!--  TOTAL EN LETRAS -->
                    </td>
                    <td style="padding:5px;font-size: 11px;  text-align:center;">
                        <?php  //echo $this->crud_model->numberTowords(number_format($info['total'],2,".",""));?>
                    </td>
                    <td style="padding:5px;font-size: 11px; text-align:left;">

                    </td>

                    <td style="padding:5px;font-size: 11px; text-align:right;">
                        <?php echo $moneda.$data->total;?>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;margin-top:15px">
                <tr>
                    <td colspan="2" style="padding-bottom: 40px;border-top:0.5px solid black;">
                        <table style="width: 100%;line-height: inherit;text-align: left;vertical-align:top;font-family:poppins">
                            <tr>
                                <td style="font-size: 12px;">
                                    SUJETOS A PAGOS TRIMESTRALES IVA (IMPUESTOS AL VALOR AGREGADO) 12%
                                    <br>
                                    <b>AGENTE DE RETENCION DEL IVA</b>
                                </td>
                            </tr>
                            <tr>
                                 <td style="text-align: center;font-size: 12px;">
                                    <b >¡GRACIAS POR SU PAGO!  ES UN PLACER SERVIRLE</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>

</html>
