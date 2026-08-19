<!doctype html>
<?php 
ini_set("memory_limit","500M");
?>
<?php
    $moneda = $this->crud_model->get_info("moneda");
    $nombreComercial = $this->db->get_where('settings', array('type'=>'name'))->row()->description;
    $direccionemisor = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $codigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $municipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $departamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $regimen = $this->db->get_where('settings',array('type'=>'regimen'))->row()->description/100;
    $data = $this->db->get_where('sales', array('code'=>$code))->row();
    $tel = $this->db->get_where('settings', array('type'=>'phone'))->row()->description;
    setlocale(LC_TIME, "spanish");
    $Nueva_Fecha = date("d-m-Y", strtotime($data->date));				
    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
    $sale = $this->db->get_where('sales',array('code'=>$code))->row();
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
                                    <p style="font-size: 25px;font-family: poppins;"><b><?php echo $nombreComercial;?></b></p>
                                    <p style="font-size: 15px;"><?php echo $direccionemisor;?></b></p>
                                    <p style="font-size: 15px;">TELS: <?php echo $tel;?></p>
                                    <p style="font-size: 10px;">NIT: <?php echo $nit;?></p>
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
                                <td colspan="2" style="background:#f07e14;color:#fff;padding:10px;font-family:poppins;">DATOS DEL CLIENTE</td>
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
                                <td colspan="2" style="background:#f07e14;color:#fff;padding:10px;font-family:poppins;">
                                    DETALLE DE LA COMPRA
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;"><b>CÓDIGO:</b></td>
                                <td style="padding-left:5px;padding-right:5px;font-size:10px;">
                                    <?php echo $code;?>
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
                    <th style="color:#fff;background:#f07e14;padding:10px;font-family:poppins;letter-spacing:1;font-weight:normal;text-align:left;" colspan="5">CONCEPTO</th>
                </tr>
                <tr>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">CÓDIGO</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">CANTIDAD</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px; width: 50%">DESCRIPCIÓN</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">P./UNITARIO</th>
                    <th style="border: 0.5px solid #000;font-size:10px;padding:10px;">IMPORTE</th>
                </tr>
                <?php $mayorista = $this->db->get_where('client', array('client_id' => $data->client_id))->row()->type;?>
        <?php for ($i=0; $i < $data->num_products ; $i++):
            if ($data->products != "" || $data->products != null) {
                $pro = json_decode($data->products,true);
            } else {
                $pro = array();
            } 
                    ?>
        <?php for ($i = 0; $i < $data->num_products; $i++): 
            $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array();?>
                <tr>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $prod['code'];?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:center;">
                        <?php echo $pro[$i]['amount'];?>
                    </td>
                    <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:left;">
                        <?php echo $prod['name'];
                        if ($pro[$i]['iva'] != '') {
                            if(!$pro[$i]['iva']) echo " (Exento)";
                            else echo " (Afecto)";
                        } else {
                            if (!$prod['iva']) echo " (Exento)";
                            else echo " (Afecto)";
                        }?>
                    </td>
                    
                    <?php if ($mayorista == 1):?> 
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.$pro[$i]['price_my'];?>
                        </td>
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.number_format($pro[$i]['sub_my'],2,'.',',');?>
                        </td>
                        <?php $total += $pro[$i]['sub_my']; $montoGravable = $total/($regimen + 1); ?>
                            
                    <?php elseif ($mayorista == 3):?> 
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.$pro[$i]['price_farma'];?>
                        </td>
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.number_format($pro[$i]['sub_farma'],2,'.',',');?>
                        </td>
                        <?php $total += $pro[$i]['sub_farma']; $montoGravable = $total/($regimen + 1); ?>
                            
                    <?php else:?> 
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.$pro[$i]['price'];?>
                        </td>
                        <td style="padding:5px;font-size: 11px; border-right: 0.5px solid #000; text-align:right;">
                            <?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?>
                        </td>
                        <?php $total += $pro[$i]['sub']; $montoGravable = $total/($regimen + 1); ?>
                    <?php endif;?>
                    <?php $montoImpuesto = $montoGravable*$regimen; $totalImpuesto += $montoImpuesto; ?>
                </tr>
                
                <?php endfor;?>
                
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
                    <td style="padding:5px;font-size: 11px; text-align:left;background:#f07e14;color:#fff;">
                        TOTAL
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
            <?php endfor;?>
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;margin-top:15px">
                <tr>
                    <td colspan="2" style="padding-bottom: 40px;border-top:0.5px solid black;">
                        <table style="width: 100%;line-height: inherit;text-align: left;vertical-align:top;font-family:poppins">
                            <tr>
                                <td style="font-size: 12px;">
                                    Este no es un documento tributario, solamente recibo de compra
                                </td>
                                <td style="text-align: right;font-size: 12px;">
                                    <b>¡Gracias por su compra!</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>

</html>
