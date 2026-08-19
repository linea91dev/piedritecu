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
                                        <p style="text-transform:uppercase"><b><u>Reporte de Compra</u></b></p>
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
                        <?php $datoss = $this->db->get_where('shopping', array('code'=>$code))->row_array();?>
                        <? setlocale(LC_TIME, "spanish"); ?>
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: right;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 12px;">Compra: <span><?php echo $datoss['code'];?></span></b>
                                        <p style="font-size: 12px;">Fecha: 
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                            <?php $Nueva_Fecha = date("d-m-Y", strtotime( $datoss['datetime'] )); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); echo $Mes_Anyo; echo ' - '; echo date('h:i:s a', strtotime($datoss['datetime']));?>
                                            </small></b>
                                        </p>
                                        <?php if($datoss['type']==1 ){
                                                    $stado = 'Completado';
                                                }elseif($datoss['type']==2){
                                                    $stado = 'Orden de compra';
                                                }elseif($datoss['type'] == 3){
                                                    $stado = 'Solicitud de compra';
                                                } ?>
                                        <p style="font-size: 12px;">Estado:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $stado; ?>
                                            </small></b>
                                        </p>
                                        <p style="font-size: 12px;">Encargado:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $this->crud_model->getName('admin', $datoss['responsable']);?>
                                            </small></b>
                                        </p>
                                        <p style="font-size: 12px;">Proveedor:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $this->db->get_where('provider', array('provider_id'=>$datoss['provider']))->row()->name;?>
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
                            Producto
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            Fecha de vencimiento
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: center;">
                            Cantidad
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: center;">
                            P/U
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: center;">
                            Descuento
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: center;">
                            Subtotal
                        </td>
                    </tr>
                    <? $prods = json_decode($datoss['products'], true);
                        foreach($prods as $pro):
                        $pr = $this->db->get_where('products',array('products_id'=>$pro['product']))->row();?>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                            <?php echo $pr->name;?>
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php if($pro['expiration'] != '' || $pro['expiration'] != null){
                            $Fecha_Exp = date("Y-m-d", strtotime($pro['expiration'])); $Exp_Fecha = strftime("%d de %B de %Y", strtotime($Fecha_Exp));
                            echo $Exp_Fecha; }
                            else{ echo 'No definida';
                            }?>
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $pro['amount']; ?>
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.number_format($pro['price_buy'],2,'.',',');?>
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo ($pro['discount'] == "") ? number_format(0,2,'.',',') : number_format($pro['discount'],2,'.',',') ; echo '%';?>
                        </td>
                        <td style="padding:15px;font-size: 15px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center">
                            <?php echo $moneda.number_format($pro['sub'],2,'.',',') ;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <tr>
                        <td colspan="6" 
                        style="padding:15px;font-size: 20px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right">
                            <b>Total:</b> <span style="border-bottom: 1px double;"><?php echo $moneda.number_format($datoss['total'],2,'.',',');?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </body>
</html>