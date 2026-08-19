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
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u>Reporte de envíos pendientes</u></b></p>
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
                                                    style="font-weight:bold; text-transform:uppercase"><?php echo date('d/m/Y H:i a');?></small></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0"
                    style="border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;width: 100%;line-height: inherit;text-align: left;">
                    <tr>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="3">
                            ID
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Fecha
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Estado
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Cliente
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Forma de pago
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Vendedor
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Envío
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Costo de Envío
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Total
                        </td>
                    </tr>
                    <?php $datoss = $this->crud_model->get_shippings(); $n=1;
                        foreach($datoss->result_array() as $rows): ?>
                    <tr>
                        <td colspan="3"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                            <?php echo $n++;?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?  setlocale(LC_TIME, "spanish"); $Nueva_Fecha = date("d-m-Y", strtotime( $rows['date'] ));	$Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); echo $Mes_Anyo?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <? if($rows['estado']==1 ){$stado = 'Activo'; }elseif($rows['estado']==2){ $stado = 'Completado';}elseif($rows['estado'] == 3){$stado = 'Anulado';}elseif($rows['estado'] == 4){$stado = 'Cambios';} echo $stado;?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <? echo $rows['name'];?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $rows['metodo'];?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $this->crud_model->getName('admin', $rows['responsable']);?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <? if($rows['shipping']==1){$shipping ='Pendiente';}elseif($rows['shipping']==0){$shipping = 'No';}elseif($rows['shipping']==2){$shipping = 'Entregada';} echo $shipping;?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:left"
                            colspan="4">
                            <?php echo $moneda.number_format($rows['shipping_cost'],2,'.',',') ;?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:left"
                            colspan="4">
                            <?php echo $moneda.number_format($rows['total'],2,'.',',') ;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </main>
    </body>
</html>