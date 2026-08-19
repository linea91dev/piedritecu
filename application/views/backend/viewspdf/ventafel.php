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
    $Cl = $this->db->get_where('client', array('client_id'=>$data->client_id))->row();
    
    if($this->session->userdata('branch_id')==23){
    $establecimiento = 2;   
    $direccionemisor = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor2'))->row()->description);
    }
    ?>
    

<body style='margin: 0; font-size:14px; font-family: Poppins; font-weight: bold;'>
    <div style="width:100%;">

        <div class="ticket">
                <p style="text-align: center; align-content: center; margin: 0px;">
                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Logotipo" style='max-width: 80px; width: 80px;'>
                </p>
                    <br>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $nombreComercial;?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;">NIT: <?php echo $nit;?> </p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $direccionemisor;?> </p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $municipio.' , ',$departamento ;?>  </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">SUJETO A PAGOS TRIMESTRALES </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">DOCUMENTO TRIBUTARIO ELECTRÓNICO </p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;"> <b> FACTURA </b></p>
                    <p style="margin: 0px;"> No. de autorización: </p>
                    <p style="margin: 0px;"><?php echo $data->code_fel;?>  </p>
                    <p style="margin: 0px;">Serie: <?php echo $data->serie_fel;?> </p>
                    <p style="margin: 0px;">Número: <?php echo $data->numero_fel;?>  </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">Fecha de emisión: <?php echo date("d/m/Y", strtotime($data->date_fel)) ;?>  </p>

                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----DATOS DEL COMPRADOR-----</p>
                    <p style="text-align: center; align-content: center; margin: 0px;">NIT:<?php if($data->nit == 'CF'){ echo $data->nit;}elseif(strlen($data->nit)>4){echo $data->nit;}else{echo 'CF';}?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $data->name;?></p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----Descripción de la factura----</p>
                    <br>
                    <table style='border-collapse: collapse; border-spacing: 0; width: 100%;'>
                    <thead style="text-align: center; align-content: center; margin: 0px;">
                    <tr>
                        <th ></th>
                        <th >CANTIDAD</th>
                        <th style=" width: 200px;">PRODUCTO</th>
                        <th >PRECIO</th>
                        <th >SUBTOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
        
        <?php $totalExento = 0; $totalAfecto = 0; $total=0;
        for ($i=0; $i < $data->num_products ; $i++):
                    if ($data->products != "" || $data->products != null) {
                        $pro = json_decode($data->products,true);
                        }else{
                        $pro = array();
                    } ?>
      <?php for ($i = 0; $i < $data->num_products; $i++): ?> 
    <?php $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array(); ?>
    <tr>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"><?php echo $pro[$i]['amount']; ?></td>
        <td style="text-align: center;">
            <?php 
                echo $this->db->get_where('products', array('products_id' => $pro[$i]['product']))->row()->name; 
                echo !$prod['iva'] ? " (Exento)" : " (Afecto)";
            ?>
        </td>

        <?php 
            if ($Cl->type == 1): 
                $precio = $pro[$i]['price_my'];
                $subtotal = $pro[$i]['sub_my'];
            elseif ($Cl->type == 3): 
                $precio = $pro[$i]['price_farma'];
                $subtotal = $pro[$i]['sub_farma'];
            else: 
                $precio = $pro[$i]['price'];
                $subtotal = $pro[$i]['sub'];
            endif;

            $total += $subtotal;
            $montoGravable = $total / ($regimen + 1);

            if (!$prod['iva']) {
                $totalExento += $subtotal;
            } else {
                $totalAfecto += $subtotal;
            }
        ?>

        <td style="text-align: center;"><?php echo $moneda . $precio; ?></td>
        <td style="text-align: center;"><?php echo $moneda . $subtotal; ?></td>
    </tr>
<?php endfor; ?>

        <?php 
            $montoImpuesto = ($total/1.12);
                $totalImpuesto = $montoImpuesto*$regimen; 
                
                $montoImpuesto2 = ($totalAfecto/1.12);
                $totalImpuesto2 = $montoImpuesto2*$regimen; ?>
    <tr style='border-collapse: collapse;'>
        <td style='border-collapse: collapse;' ></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;">---------</td>
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
                                <p style="text-align: center; align-content: center; margin: 0px;" >IVA <?php echo $moneda.number_format($totalImpuesto2,2,".",",");?></p>
                                <br>
                                <p style="text-align: center; align-content: center; margin: 0px;" >-----DATOS DEL CERTIFICADOR-----</p>
                                <br>
                                <p style="text-align: center; align-content: center; margin: 0px;" >NIT: 12521337</p>
                                <p style="text-align: center; align-content: center; margin: 0px;" >INFILE, S.A</p>
                                <br>
                                <p style="text-align: center; align-content: center; margin: 0px;"  >Atendido por: <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></p>
                                <br>
                    
        <?php endfor;?>

            
                        
            
    </div>
    

    </div>
</body>

</html>