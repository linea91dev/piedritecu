<!doctype html>
<?php 
    ini_set("memory_limit","1024M");
    setlocale(LC_TIME,"es_ES");
    $moneda = $this->crud_model->get_info("moneda");
    $nombreComercial = $this->db->get_where('settings', array('type'=>'name'))->row()->description;
    $nombreFEL = $this->db->get_where('settings', array('type'=>'name_fel'))->row()->description;
    $direccionemisor = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $tel = $this->db->get_where('settings', array('type'=>'phone'))->row()->description;
    $data = $this->db->get_where('sales', array('code'=>$code))->row();
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $emisorDepartamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    if($this->session->userdata('branch_id')==23){
    $establecimiento = 2;   
    $direccionemisor = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor2'))->row()->description);
    $tel = '4166 2755';
    }
    log_message("error", "Tipo de cliente: ".$data->my);
    $mayorista = 2;
    if ($data->my > 0) $mayorista = $data->my;
    $total = 0;
?>
<html>
    <head> <meta charset="gb18030"> </head>
    <body style="margin: 0px; font-size: 12px; font-family: 'Poppins'; font-weight: bold;">
        <main>
            <div style="width: 100%; display: table; clear: both;">
                <div style="width: 30%; float: left; text-align: center;">
                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" style="height:250px; margin-bottom: 5px;"/>
                </div>
                <div style="width: 65%; float: left;">
                    <p style="margin-left: 15px; font-size: 8px; font-weight: bold; text-align: right;">
                        <?php echo $nombreFEL;?><br>
                        <?php echo $direccionemisor.', '.$emisorMunicipio.', '.$emisorDepartamento;?><br>
                        Tel: <?php echo $tel;?>
                        NIT: <?php echo $nit;?>
                    </p>
                </div>
                <div style="width: 50%; float: left;">
                    <p style="font-size: 11px; font-weight: bold;">
                        <b> -Cliente:</b><br>
                        <?php echo '-'.$data->name;?><br>
                        <?php if($data->address != '') echo $data->address; else echo "Ciudad";?><br>
                        <?php echo '-'.$data->nit;?><br>
                        <?php echo '-'.$data->phone;?>
                    </p>
                </div>
                <div style="width: 50%; float: left;">
                    <p style="text-align: left; font-size: 11px;">
                        <b>Venta:</b><br>
                        <?php echo $code;?><br>
                        <b>Fecha de emision:</b><br>
                        <?php echo $data->date.' '.$data->time;?><br>
                        <b>Fecha de certificacion:</b><br>
                        <?php echo $data->date_fel;?><br>
                        <?php echo $this->crud_model->getName('admin', $data->responsable);?>
                    </p>
                </div>
                <?php if($data->FEL): ?>
                <div style="width: 100%; float: left;">
                    <p style="text-align: center; font-size: 10px;">
                        <b>DATOS DE FACTURACIÓN</b><br>
                        <?if($data->credito!=1):?>
                        <b>Factura electrónica</b><br> 
                        <?else:?>
                        <b>Factura Cambiaria</b><br> 
                        <?endif;?>
                        <b>Autorización</b><br>
                        <?php echo $data->code_fel;?><br>
                         <b>Serie:</b><br>
                        <?php echo $data->serie_fel;?><br>
                         <b>Número:</b><br>
                        <?php echo $data->numero_fel;?><br>
                       
                    </p>
                </div>
                <?php endif; ?>
                <div style="width: 100%; float: center;">
                    <table style="margin-bottom: 1.5rem !important; font-size: 12px solid black; font-family: 'Arial';font-weight: 400; width: 100%; text-align: center; border-collapse: collapse; font-weight: bold;">
                        <thead style="border-top: 12px solid black; border-bottom: 1px solid black; border-collapse: collapse;">
                            <tr style="height: 50px; border: 1px solid black; border-collapse: collapse;">
                                <th style="border: 1px solid black; border-collapse: collapse; width: 75%; height: 20px; text-align: center;">
                                    Descripción
                                </th>
                                <th style="border: 1px solid black; border-collapse: collapse; width: 25%; text-align: center;">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody style="border-top: 1px solid #e0e6ed; border-bottom: 1px solid #e0e6ed;font-weight: bold;">
                            <?php  $exento = 0; $afecto = 0;
                            if ($data->products != "" || $data->products != null) {
                                    $pro = json_decode($data->products,true);
                                } else {
                                    $pro = array();
                                } ?>
                            <?php for ($i = 0; $i < $data->num_products; $i++): 
                                $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array();?>
                            <tr style="border-bottom: 1px solid black; border-collapse: collapse; font-weight: bold;">
                                <td style="text-align: left; height: 30px; border-bottom: 1px solid black; border-collapse: collapse;">
                                    <span style="margin-right:5px; margin-left:5px; margin-top: 3px;">
                                        <?php echo $prod['name'];
                                        if ($pro[$i]['iva'] != '') {
                                            if (!$prod['iva']){
                                                if($mayorista == 1){
                                                  $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price_my']);  
                                                  echo " (Exento)";
                                                }elseif($mayorista == 3){
                                                    $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price_farma']);  
                                                  echo " (Exento)";
                                                }else{
                                                    $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price']);  
                                                  echo " (Exento)";
                                                }
                                            } 
                                            else{
                                                if($mayorista == 1){
                                                  $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price_my']);  
                                                  echo " (Afecto)";
                                                }elseif($mayorista == 3){
                                                    $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price_farma']);  
                                                  echo " (Afecto)";
                                                }else{
                                                    $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price']);  
                                                  echo " (Afecto)";
                                                }
                                            }
                                        } else {
                                            if (!$prod['iva']){
                                                if($mayorista == 1){
                                                  $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price_my']);  
                                                  echo " (Exento)";
                                                }elseif($mayorista == 3){
                                                    $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price_farma']);  
                                                  echo " (Exento)";
                                                }else{
                                                    $exento = $exento + ($pro[$i]['amount']*$pro[$i]['price']);  
                                                  echo " (Exento)";
                                                }
                                            } 
                                            else{
                                                if($mayorista == 1){
                                                  $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price_my']);  
                                                  echo " (Afecto)";
                                                }elseif($mayorista == 3){
                                                    $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price_farma']);  
                                                  echo " (Afecto)";
                                                }else{
                                                    $afecto = $afecto + ($pro[$i]['amount']*$pro[$i]['price']);  
                                                  echo " (Afecto)";
                                                }
                                            }
                                        }
                                        echo " (".$pro[$i]['amount'].'x'.$moneda; if($mayorista == 1) echo number_format($pro[$i]['price_my'],2,'.',','); elseif($mayorista == 3) echo number_format($pro[$i]['price_farma'],2,'.',','); else echo number_format($pro[$i]['price'],2,'.',','); echo ')';?>
                                    </span>
                                </td>
                                <td style="text-align: right; border-bottom: 1px solid black; border-collapse: collapse;">
                                    <span style="margin-right:5px; margin-left:5px; margin-top: 3px;">
                                        <?php echo $moneda; if($mayorista == 1) echo number_format($pro[$i]['sub_my'],2,'.',','); elseif($mayorista == 3) echo number_format($pro[$i]['sub_farma'],2,'.',','); else echo number_format($pro[$i]['sub'],2,'.',',');?>
                                    </span>
                                </td>
                            </tr>
                            <?php if($mayorista == 1) $total += $pro[$i]['sub_my']; elseif($mayorista == 3) $total += $pro[$i]['sub_farma']; else $total += $pro[$i]['sub']; $cont++; endfor;?>
                        </tbody>
                    </table>
                </div>
                <div style="width: 100%; float: left;">
                    <div style="width: 100%; display: table; clear: both; margin-top: 0.5rem;font-weight: bold;">
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; ">
                            Total exento:<br>
                        </div>
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px;font-weight: bold; ">
                            <?php echo $moneda.number_format($exento,2,'.',',')."<br>"; ?>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; float: left;">
                    <div style="width: 100%; display: table; clear: both; margin-top: 0.5rem; font-weight: bold;">
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; ">
                            Total Afecto:<br>
                        </div>
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; ">
                            <?php echo $moneda.number_format($afecto,2,'.',',')."<br>"; ?>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; float: left; font-weight: bold;">
                    <div style="width: 100%; display: table; clear: both; margin-top: 0.5rem;">
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; ">
                            Total IVA Afecto:<br>
                        </div>
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; ">
                            <?php $iva = (($afecto/1.12)*0.12); echo $moneda.number_format($iva,2,'.',',')."<br>"; ?>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; float: left;">
                    <div style="width: 100%; display: table; clear: both; margin-top: 0.5rem;">
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; font-weight: bold;">
                            Subtotal:<br><?php if($data->credito == 1) echo "Crédito"; else echo $data->metodo; echo ":<br>"; if($data->metodo == 'Efectivo') echo "Vuelto:<br>";?>Total:
                        </div>
                        <div style="width: 50%; float: left; text-align: right; font-size: 12px; font-weight: bold;">
                            <?php echo $moneda.number_format($total,2,'.',',')."<br>"; 
                                echo $moneda; if($data->metodo == 'Efectivo') echo number_format($total+$data->cambio,2,'.',',')."<br>".$moneda.number_format($data->cambio,2,'.',',')."<br>";
                                else echo number_format($total,2,'.',',')."<br>";
                                echo $moneda.number_format($total,2,'.',',');?>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; float: left; font-size: 10px; font-weight: bodl;">
                    <div style="margin-left: 15px; margin-top: 0px;"><b>DOCUMENTO TRIBUTARIO ELECTRONICO</b></div>
                    <div style="margin-left: 15px; margin-top: 0px;"><b>Sujeto a pagos trimestrales</b></div>
                    <div style="margin-left: 15px; margin-top: 0px;"><b>Certificador: INFILE S.A <br> Nit: 12521337</b></div>
                </div>
                <div style="width: 100%; float: left; font-size: 10px; font-weight: bold;">
                    <div style="margin-left: 15px; margin-top: 0px;"><b>Gracias por tu compra</b></div>
                </div>
            </div>
        </main>
    </body>
</html>