<!doctype html>
<?php 
ini_set("memory_limit","500M");
?>
<html>

<head>
    <meta charset="gb18030">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<?php 
    $moneda = $this->crud_model->get_info("moneda");
    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;
    $direccionemisor = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $codigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $municipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $departamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    $nit = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $regimen = $this->db->get_where('settings',array('type'=>'regimen'))->row()->description/100;
    $data = $this->db->get_where('sales', array('code'=>$code))->row();
    
    setlocale(LC_TIME, "spanish");
    $Nueva_Fecha = date("d-m-Y", strtotime($data->date));				
    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
?>
    

<body style='margin: 0; font-size:14px; font-family: Poppins; font-weight: bold;'>
    <div style="width:100%;">

        <div class="ticket">
                <p style="text-align: center; align-content: center; margin: 0px;">
                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Logotipo" style='max-width: 160px; width: 160px;'>
                </p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">DOCUMENTO NO CONTABLE </p>
                    <p style="text-align: center; align-content: center; margin: 0px;"> Tels: 5928-9874 5928-9776, 14 Av 0-82 Zona 1 Quetzaltenango. </p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----DATOS DE LA COMPRA-----</p>
                    <p style="text-align: center; align-content: center; margin: 0px;">Código: <?php echo $code;?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;">Fecha: <?php echo $Mes_Anyo;?></p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----DATOS DEL COMPRADOR-----</p>
                    <p style="text-align: center; align-content: center; margin: 0px;">NIT: <?php echo $data->nit;?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $data->name;?></p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----Descripción----</p>
                    <br>
                    <table style='border-collapse: collapse; border-spacing: 0; width: 100%;'>
                    <thead style="text-align: center; align-content: center; margin: 0px;">
                    <tr>
                        <th ></th>
                        <th >CANTIDAD</th>
                        <th >CODIGO</th>
                        <th style=" width: 200px;">PRODUCTO</th>
                        <th >PRECIO</th>
                        <th >SUBTOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
        <?php $mayorista = $this->db->get_where('client', array('client_id' => $data->client_id))->row()->type;?>
        <?php for ($i=0; $i < $data->num_products ; $i++):
            if ($data->products != "" || $data->products != null) {
                $pro = json_decode($data->products,true);
                }else{
                $pro = array();
            } 
            $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array();
            ?>
        <?php for ($i = 0; $i < $data->num_products; $i++): ?> 
        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?php echo $pro[$i]['amount'];?></td>
            <td style="text-align: center;"><?php echo $prod['code'];?></td>
            <td style="text-align: center;">
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
                <td style="text-align: center;"><?php echo $moneda.$pro[$i]['price_my'];?></td>
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i]['sub_my'],2,'.',',');?></td>

                <?php $total += $pro[$i]['sub_my'];
                        $montoGravable = $total/($regimen + 1); ?>
                
            <?php else:?> 
                <td style="text-align: center;"><?php echo $moneda.$pro[$i]['price'];?></td>
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?></td>

                <?php $total += $pro[$i]['sub'];
                        $montoGravable = $total/($regimen + 1); ?>

            <?php endif;?>
            
            <?php $montoImpuesto = $montoGravable*$regimen;
                $totalImpuesto += $montoImpuesto; ?>
    </tr>
    <?php endfor;?>

    <tr style='border-collapse: collapse;'>
        <td style='border-collapse: collapse;' ></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;">-------</td>
    </tr>
    <tr style='border-collapse: collapse;'>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"><b>TOTAL</b></td>
        <td style="text-align: center;"><?php echo $moneda.$data->total;?></td>
    </tr>
                                    </tbody>
                                </table>
                                <br>
                                <p style="text-align: center; align-content: center; margin: 0px;"  >Atendido por: <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></p>
                                <br>
                    
        <?php endfor;?>

            
                        
            
    </div>
    

    </div>
</body>

</html>