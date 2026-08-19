<!doctype html>
<?php ini_set("memory_limit","500M");?>
<html>
    <head> <meta charset="gb18030"> </head>
    <body>
        <header style="text-align: center; margin-top: -25px !important;">
            <img src="<?php echo base_url().'uploads/img/'.$this->crud_model->get_info('logo');?>" style="width: 75px; height: auto; border-radius: 15px;" />
            <div style="position:relative;">
                <b style="font-size: 12px;"><span><?php echo $this->crud_model->get_info('name');?></span></b>
                <p style="font-size: 12px; margin-top: 0px;">
                    <b><?php echo $this->crud_model->get_info('slogan');?></b><br>
                    <b><?php echo $this->crud_model->get_info('phone');?></b><br>
                    <b><?php echo $this->crud_model->get_info('email');?></b>
                </p>
            </div>
        </header>
        <main>
            <div style="width:100%; font-size: 16px; line-height: 24px; font-family: 'nunito'; color: #555;">
                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                        <td colspan="2">
                            <table  style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u>Reporte de Servicio de Transportes</u></b></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="2">
                            <table  style="width: 100%;line-height: inherit;text-align: left;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 12px;">Generado por: <span><?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></span></b>
                                        <p style="font-size: 12px;">Fecha: <b><small style="font-weight:bold; text-transform:uppercase"><?php echo date('d/m/Y H:i a');?></small></b></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <?php $moneda = $this->crud_model->get_info("moneda"); ?>
                <table cellpadding="0" cellspacing="0"  style="border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;width: 100%;line-height: inherit;text-align: left;">
                    <tr>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            ID
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Transporte
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Fecha
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Próximo servicio
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Método de pago
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Precio
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Lugar de servicio
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;">
                            Responsable
                        </td>
                    </tr>
                    <?php $datoss = $this->crud_model->get_transportservices();
                        foreach($datoss->result_array() as $rows): ?>
                        <tr>
                            <td rowspan="2" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                                <?php echo $rows['service_transport_id'];?>
                            </td>
                            <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                                <?php if($rows['transport_id']){
                                        $vehiculo = $this->db->get_where('transport', array('transport_id' => $rows['transport_id']))->row_array();
                                        echo $vehiculo['name'].' - '.$vehiculo['license_plate'];
                                    }
                                    elseif($rows['company_id']){
                                        $company = $this->db->get_where('delivery_company', array('delivery_company_id' => $rows['company_id']))->row_array();
                                        echo $company['name'].' - '.$rows['code'];
                                    }?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center">
                                <?php echo date('d M, Y', strtotime($rows['date']));?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center">
                                <?php if($rows['next_service'] != "") echo date('d M, Y', strtotime($rows['next_service'])); else echo "No definido";?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center">
                                <?php if($rows['payment_method'] == 0) echo 'Efectivo'; ?>
                                <?php if($rows['payment_method'] == 1) echo 'Cheque'; ?>
                                <?php if($rows['payment_method'] == 2) echo 'Tarjeta Débito'; ?>
                                <?php if($rows['payment_method'] == 3) echo 'Transferencia'; ?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right">
                                <?php echo $moneda.number_format($rows['price'],2,'.',',');?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right">
                                <?php echo $rows['place_service'];?>
                            </td>
                            <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center">
                                <?php echo $this->crud_model->getName('admin', $rows['responsable']);?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right">
                                <strong>Detalles:</strong> <?php echo $rows['details'];?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </main>
    </body>
</html>
