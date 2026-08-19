<!doctype html>
<?php 
ini_set("memory_limit","500M");
?>
<?php
    $moneda = $this->crud_model->get_info("moneda");
    $nombre = $this->db->get_where('settings', array('type'=>'name'))->row()->description;
    $direccion = $this->db->get_where('settings', array('type'=>'address'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $tel = $this->db->get_where('settings', array('type'=>'phone'))->row()->description;
    $data = $this->db->get_where('traslado', array('id_traslado'=>$traslado_id))->row();
    setlocale(LC_TIME, "spanish");  $cont = 0; $total = 0;
    $Nueva_Fecha = date("h:i a", strtotime($data->date));				
    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($data->date));
    $prods = $this->crud_model->get_products_traslado($traslado_id);
    $desde = $this->db->get_where('branch', array('branch_id'=>$data->Desde))->row_array();
    $hacia = $this->db->get_where('branch', array('branch_id'=>$data->Hacia))->row_array();
    $responsable = $this->crud_model->getName("admin", $data->responsable);
    $totRows = $prods->num_rows();
    $restante = 25 - $totRows;
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
                                <tr>
                                    <td style="padding-bottom: 20px; width: 25px; height: 25px;  vertical-align: top;text-align:left;" colspan="3">
                                        <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Factura 1" style=" max-width: 150px!important; height: auto; border-radius: 150px;">
                                    </td>
                                </tr>
                                <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;"></td>
                                <td style="text-align: right;">
                                    <p style="font-size: 25px;font-family: poppins;"><b><?php echo $nombre;?></b></p>
                                    <p style="font-size: 15px;"><?php echo $direccion;?></b></p>
                                    <p style="font-size: 15px;">TELS: <?php echo $tel;?></p>
                                    <p style="font-size: 10px;">NIT: <?php echo $nit;?></p>
                                    <p style="font-size: 10px;">Responsable: <?php echo $responsable;?></p>
                                    <p style="font-size: 10px;">Fecha y hora: <?php echo $Mes_Anyo.' '.$Nueva_Fecha;?></p>
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
                                <td colspan="2" style="background:#f07e14;color:#fff;padding:10px;font-family:poppins;">BODEGA DE ORIGEN</td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>NOMBRE:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Desde > 0) echo $desde['name']; else echo "Bodega";?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>DIRECCIÓN:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Desde > 0) echo $desde['address'];?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>TELÉFONO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Desde > 0) echo $desde['phone'].' - '.$desde['tel'];?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>CORREO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Desde > 0) echo $desde['email'];?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;border: 0.5px solid #000;font-family:poppins;">
                            <tr>
                                <td colspan="2" style="background:#f07e14;color:#fff;padding:10px;font-family:poppins;">BODEGA DESTINO</td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>NOMBRE:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Hacia > 0) echo $hacia['name']; else echo "Bodega";?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>DIRECCIÓN:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Hacia > 0) echo $hacia['address'];?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>TELÉFONO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Hacia > 0) echo $hacia['phone'].' - '.$hacia['tel'];?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>CORREO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php if($data->Hacia > 0) echo $hacia['email'];?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="font-family:poppins;margin-top:20px; border-top:0.5px solid #000;   border-bottom:0.5px solid #000;  border-left:0.5px solid #000; border-right:0.5px solid #000; width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <th style="color:#fff;background:#f07e14;padding:10px;font-family:poppins;letter-spacing:1;font-weight:normal;text-align:left;" colspan="5">CONCEPTO</th>
                </tr>
                <tr>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">CÓDIGO</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">CANTIDAD</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px; width: 50%">DESCRIPCIÓN</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">COSTO</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">TOTAL</th>
                </tr>
                <?php foreach ($prods->result_array() as $pd):
                    $prod_id = $pd['products_id']; $cost = $pd['cost'];
                    if ($pd['products_id_2'] > 0) $prod_id = $pd['products_id_2'];
                    $pro = $this->db->get_where('products', array('products_id'=>$prod_id))->row_array();
                    $amount = $pd['total'];
                    if ($pd['products_id_2'] > 0 && $pro['cnt_prod_matriz'] > 0) $amount = $pd['total'] / $pro['cnt_prod_matriz'];
                    if ($pd['products_id_2'] > 0) $cost = $pd['cost'] * $pro['cnt_prod_matriz'];
                    $subtotal = $amount*$cost;?>
                <tr>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $pro['code'];?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $amount;?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:left;">
                        <?php echo $pro['name'];
                            if ($pd['iva'] != '') {
                            if(!$pd['iva']) echo " (Exento)";
                            else echo " (Afecto)";
                        } else {
                            if (!$pro['iva']) echo " (Exento)";
                            else echo " (Afecto)";
                        }?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $moneda.number_format($cost,2,'.',',');?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $moneda.number_format($subtotal,2,'.',',');?>
                    </td>
                </tr>
                
                <?php $cont++; $total += $subtotal; endforeach;?>
                
                <?php for($i=0; $i<$restante; $i++):?>
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
                <?php endfor;?>
            </table>
            <br>
            <table cellpadding="0" cellspacing="0" style="font-family:poppins; border:0.5px solid #000; width: 100%;line-height: inherit;text-align: left;">
                <tr>
                    <td style="padding:5px;font-size: 11px; text-align:left;background:#f07e14;color:#fff;">TOTAL</td>
                    <td style="padding:5px;font-size: 11px; text-align:left;background:#fff;color:#fff;"></td>
                    <td style="padding:5px;font-size: 11px; text-align:center;"></td>
                    <td style="padding:5px;font-size: 11px; text-align:left;"></td>
                    <td style="padding:5px;font-size: 11px; text-align:right;">
                        <?php echo $moneda.number_format($total,2,'.',',');?>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;margin-top:35px">
                <tr>
                    <td style="padding-bottom: 40px; width: 17.5%;"></td>
                    <td style="padding-bottom: 40px; width: 17.5%;"></td>
                    <td style="padding-bottom: 40px; width: 30%; border-bottom:0.5px solid black;"></td>
                    <td style="padding-bottom: 40px; width: 17.5%;"></td>
                    <td style="padding-bottom: 40px; width: 17.5%;"></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 40px; text-align: center;" colspan="5"><?php echo $responsable;?></td>
                </tr>
            </table>
        </div>
    </body>

</html>
