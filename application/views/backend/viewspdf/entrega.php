<!doctype html>
<?php ini_set("memory_limit","500M");?>
<html>
    <head> <meta charset="gb18030"> </head>
    <?php $moneda = $this->crud_model->get_info("moneda");?>
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
                    <tr>
                        <td colspan="4">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u>Reporte de Entrega</u></b></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 12px;">Generado por:
                                            <span><?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></span></b>
                                        <p style="font-size: 12px;">Fecha: <b><small
                                                    style="font-weight:bold; text-transform:uppercase"><?php echo date('d/m/Y h:i a');?></small></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                    <tr>
                        <?php $datoss = $this->db->get_where('delivery', array('code'=>$code))->row_array();?>
                        <? setlocale(LC_TIME, "spanish"); ?>
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 15px;">Entrega: <span><?php echo $datoss['code'];?></span></b>
                                        <p style="font-size: 15px;">Fecha y hora de entrega: 
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                            <?php $Nueva_Fecha = date("d-m-Y h:i A", strtotime( $datoss['fecha_entrega'] )); $Mes_Anyo = strftime("%d de %B de %Y - %H:%M", strtotime($Nueva_Fecha)); echo $Mes_Anyo;;?>
                                            </small></b>
                                        </p>
                                        <?php if($datoss['status']==1 ){
                                                    $stado = 'Activo';
                                                }else{
                                                    $stado = 'Inactivo';
                                                }?>
                                        <p style="font-size: 15px;">Estado:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $stado; ?>
                                            </small></b>
                                        </p>
                                        <p style="font-size: 15px;">Encargado:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $this->crud_model->getName('admin', $datoss['responsable_id']);?>
                                            </small></b>
                                        </p>
                                        <p style="font-size: 15px;">Dirección de entrega:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $datoss['address'];?>
                                            </small></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0"
                    style="border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;width: 100%;line-height: inherit;">
                    <tr>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            Tipo
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            Información
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Cliente:
                        </td>
                        <?php $venta = $this->db->get_where('sales', array('code'=>$datoss['sale_code']))->row_array(); ?>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $venta['client_id'] != 0 ? $this->crud_model->getName('client',$venta['client_id']) : $venta['name']; echo ' - '.$venta['nit'].' - '.$venta['phone'];?>
                        </td>
                    </tr>
                    <tr>
                        <?php $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$datoss['service_transport']))->row_array();
                            $transport = $this->db->get_where('transport', array('transport_id'=>$servicio['transport_id']))->row_array();?>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Vehículo:
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $transport['name'].' - '.$transport['license_plate'];?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Costo Inicial:
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $moneda.number_format($datoss['cost'],2,'.',',');?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Costo Adicional:
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $moneda.number_format($datoss['cost_extra'],2,'.',',');?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Costo Total:
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $moneda.number_format($datoss['total'],2,'.',',');?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Método de Pago:
                        </td>
                        <?php $tipo_pago = ''; $metodo = $servicio['payment_method']; if($metodo == 0) $tipo_pago = 'Efectivo'; elseif($metodo == 1) $tipo_pago = 'Cheque'; elseif($metodo == 2) $tipo_pago = 'Targeta de débito'; elseif($metodo == 3) $tipo_pago = 'Transferencia';
                        $origen = ''; if($metodo == 0){ $origen = '(Caja Chica)';} 
                        else{
                            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $servicio['account_bank_id']))->row_array();
                            $bank = $this->db->get_where('bank', array('bank_id' => $cuenta['bank_id']))->row()->name;
                            $origen = '('.$bank.') - '.$cuenta['name_account'];
                        }?>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $tipo_pago.' - '.$origen; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            Notas:
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:left;">
                            <?php echo $datoss['notes'];?>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </body>
</html>