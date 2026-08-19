<!doctype html>
<?php 
    ini_set("memory_limit","1024M");
    setlocale(LC_TIME,"es_ES");
    $moneda = $this->crud_model->get_info("moneda");
    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;
    $direccionemisor = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $tel = $this->db->get_where('settings', array('type'=>'phone'))->row()->description;
    $data = $this->db->get_where('shopping', array('code'=>$code))->row();
    $prov = $this->db->get_where('provider', array('provider_id'=>$data->provider))->row();
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
                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" style="height:50px; margin-top: 5px; margin-bottom: 5px;"/>
                </div>
                <div style="width: 65%; float: left;">
                    <p style="margin-left: 15px; font-size: 8px; font-weight: bold; text-align: right;">
                        <?php echo $nombreComercial;?><br>
                        <?php echo $direccionemisor;?><br>
                        Tel: <?php echo $tel;?>
                        NIT: <?php echo $nit;?>
                    </p>
                </div>
                <div style="width: 50%; float: left;">
                    <p style="font-size: 8px; font-weight: bold;">
                        <b>Proveedor:</b><br>
                        <?php echo $prov->name;?><br>
                        <?php echo $prov->address;?><br>
                        <?php echo $prov->nit;?><br>
                        <?php echo $prov->phone;?>
                    </p>
                </div>
                <div style="width: 50%; float: left;">
                    <p style="text-align: left; font-size: 8px;">
                        <b>Compra:</b><br>
                        <?php echo $code;?><br>
                        <?php echo date("d/m/Y", strtotime($data->date));?><br>
                        <?php echo $this->crud_model->getName('admin', $data->responsable);?>
                    </p>
                </div>
                <div style="width: 100%; float: center;">
                    <table style="margin-bottom: 1.5rem !important; font-size: 8px; font-family: 'Poppins'; width: 100%; text-align: center; border-collapse: collapse;">
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
                        <tbody style="border-top: 1px solid #e0e6ed; border-bottom: 1px solid #e0e6ed;">
                            <?php if ($data->products != "" || $data->products != null) {
                                    $pro = json_decode($data->products,true);
                                } else {
                                    $pro = array();
                                } ?>
                            <?php for ($i = 0; $i < $data->num_products; $i++): 
                                $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array();?>
                            <tr style="border-bottom: 1px solid black; border-collapse: collapse;">
                                <td style="text-align: left; height: 30px; border-bottom: 1px solid black; border-collapse: collapse;">
                                    <span style="margin-right:5px; margin-left:5px; margin-top: 3px;">
                                        <?php echo $prod['code']." - ".$prod['name']." (".$pro[$i]['amount'].'x'.$moneda.number_format($pro[$i]['price_buy'],2,'.',',').')';?>
                                    </span>
                                </td>
                                <td style="text-align: right; border-bottom: 1px solid black; border-collapse: collapse;">
                                    <span style="margin-right:5px; margin-left:5px; margin-top: 3px;">
                                        <?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?>
                                    </span>
                                </td>
                            </tr>
                            <?php $total += $pro[$i]['sub']; $cont++; endfor;?>
                        </tbody>
                    </table>
                </div>
                <div style="width: 100%; float: left;">
                    <div style="width: 100%; display: table; clear: both;">
                        <div style="width: 50%; float: left; text-align: right;">
                            <p style="margin-left: 85px; font-size: 10px; font-weight: bold;">
                                Total
                            </p>
                        </div>
                        <div style="width: 50%; float: left; text-align: right;">
                            <p style="margin-left: 5px; font-size: 10px; font-weight: bold;">
                                <?php echo $moneda.number_format($total,2,'.',',');?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>