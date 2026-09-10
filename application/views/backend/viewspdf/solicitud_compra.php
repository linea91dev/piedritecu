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
                    <tr>
                        <td colspan="4">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u>Solicitud de Compra</u></b></p>
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
                                        <b style="font-size: 12px;">Solicitud: <span><?php echo $datoss['code'];?></span></b>
                                        <p style="font-size: 12px;">Fecha: 
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                            <?php $Nueva_Fecha = date("d-m-Y", strtotime( $datoss['datetime'] )); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); echo $Mes_Anyo; echo ' - '; echo date('h:i:s a', strtotime($datoss['datetime']));?>
                                            </small></b>
                                        </p>
                                        <p style="font-size: 12px;">Estado:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                Solicitud de compra
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
                                        <?php
                                            $lugar_id = !empty($datoss['location']) ? $datoss['location'] : $datoss['destiny'];
                                            if ($lugar_id === '0' || $lugar_id === 0) {
                                                $lugar_entrega = 'Bodega';
                                            } else {
                                                $branch_row = $this->db->get_where('branch', array('branch_id' => $lugar_id))->row();
                                                $lugar_entrega = $branch_row ? $branch_row->name : 'No definido';
                                            }
                                        ?>
                                        <p style="font-size: 12px;">Lugar de entrega:
                                            <b><small style="font-weight:bold; text-transform:uppercase">
                                                <?php echo $lugar_entrega;?>
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
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left; width:18%;">
                            Código
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            Producto
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left; width:28%;">
                            Fecha de vencimiento
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: center; width:14%;">
                            Cantidad
                        </td>
                    </tr>
                    <?php
                        $prods = json_decode($datoss['products'], true);
                        if (!is_array($prods)) $prods = array();
                        foreach($prods as $pro):
                        $pr = $this->db->get_where('products',array('products_id'=>$pro['product']))->row();
                    ?>
                    <tr>
                        <td style="padding:10px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;font-size: 12px; text-align:center;">
                            <?php echo ($pr && isset($pr->code)) ? $pr->code : '-';?>
                        </td>
                        <td style="padding:10px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;font-size: 12px;">
                            <?php echo ($pr && isset($pr->name)) ? $pr->name : '-';?>
                        </td>
                        <td style="padding:10px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; font-size: 12px; text-align:center;">
                            <?php if(!empty($pro['expiration'])){
                            $Fecha_Exp = date("Y-m-d", strtotime($pro['expiration'])); $Exp_Fecha = strftime("%d de %B de %Y", strtotime($Fecha_Exp));
                            echo $Exp_Fecha; }
                            else{ echo 'No definida';
                            }?>
                        </td>
                        <td style="padding:10px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; font-size: 12px; text-align:center;">
                            <?php echo $pro['amount']; ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>

                <br><br>
                <table cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #000;">
                    <tr>
                        <td style="background:#eee; padding:6px 10px; font-size:12px; font-weight:bold; border-bottom:1px solid #000;">
                            Observaciones
                        </td>
                    </tr>
                    <tr>
                        <td style="height:70px; padding:8px; font-size:11px; vertical-align:top;">
                            <?php echo !empty($datoss['details']) ? nl2br(htmlspecialchars($datoss['details'])) : '&nbsp;';?>
                        </td>
                    </tr>
                </table>

                <br><br>
                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="width:48%; text-align:center; vertical-align:bottom; padding:10px;">
                            <div style="border-top:1px solid #000; margin:60px 20px 8px 20px;"></div>
                            <p style="font-size:11px; margin:0; font-weight:bold;">Departamento de Compras</p>
                            <p style="font-size:10px; margin:2px 0 0 0;">Firma y nombre</p>
                        </td>
                        <td style="width:4%;"></td>
                        <td style="width:48%; text-align:center; vertical-align:bottom; padding:10px;">
                            <div style="border-top:1px solid #000; margin:60px 20px 8px 20px;"></div>
                            <p style="font-size:11px; margin:0; font-weight:bold;">Gerente General y/o Administrativo</p>
                            <p style="font-size:10px; margin:2px 0 0 0;">Firma y nombre</p>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </body>
</html>
