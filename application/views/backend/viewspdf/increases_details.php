<!doctype html>
<?php ini_set("memory_limit","500M");?>
<html>
    <head> <meta charset="gb18030"> </head>
    <?php $branch_id = $this->session->userdata("branch_id");?>
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
                                        <p style="text-transform:uppercase"><b><u>Reporte de ingresos</u></b></p>
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
                            #
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Fecha
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Código
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Sucursal
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Responsable
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Unidades registradas
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Costo
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Total en inventario <?php echo $ini.'--'.$final?>
                        </td>
                    </tr>
                    <?php $datoss = $this->crud_model->get_increases_details($ini,$final); $n=1; $moneda = $this->crud_model->get_info("moneda");
                        $total = 0; $sub = 0;
                        foreach($datoss->result_array() as $rows):
                            $products_id = $rows['products_id'];
                            if ($rows['products_id_2']) $products_id = $rows['products_id_2'];
                            $prod = $this->db->get_where('products', array('products_id'=>$products_id))->row_array();
                            $sub = $rows['amount'] * $rows['cost'];
                        ?>
                    <tr>
                        <td colspan="3"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                            <?php echo $n++;?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $rows['code'];?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $prod['code'].' '.$prod['name'];?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo date("d/m/Y h:i a", strtotime($rows['datetime']));?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center"
                            colspan="4">
                            <?php echo $this->crud_model->getName("admin", $rows['responsable']);?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center"
                            colspan="4">
                            <?php echo $rows['amount'];?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center"
                            colspan="4">
                            <?php echo $rows['cost'];?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center"
                            colspan="4">
                            <?php echo $moneda.number_format($sub,2,'.',',');?>
                        </td>
                    </tr>
                    <?php $total += $sub; endforeach;?>
                    <tr>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right"
                            colspan="27">
                            Total
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:center"
                            colspan="4">
                            <?php echo $moneda.number_format($total,2,'.',',');?>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
    </body>
</html>