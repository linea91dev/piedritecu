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
    $data = $this->db->get_where('quotes', array('quotes_id'=>$ID))->row();
    $Cl = $this->db->get_where('client', array('client_id'=>$data->client_id))->row();
    
    if($this->session->userdata('branch_id')==23){
        $establecimiento = 2;   
        $direccionemisor = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor2'))->row()->description);
    }

    $client_nit = ($Cl && isset($Cl->nit) && $Cl->nit != '') ? $Cl->nit : 'CF';
    $client_name = ($Cl && isset($Cl->name)) ? $Cl->name : $this->crud_model->getName('client', $data->client_id);
    $client_type = ($Cl && isset($Cl->type)) ? (int)$Cl->type : 0;
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
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $municipio.' , '.$departamento ;?>  </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">SUJETO A PAGOS TRIMESTRALES </p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;"> <b> COTIZACIÓN </b></p>
                    <p style="margin: 0px;"> Código: <?php echo $data->code;?> </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">Fecha de emisión: <?php echo date("d/m/Y", strtotime($data->date_start)) ;?>  </p>
                    <p style="text-align: center; align-content: center; margin: 0px;">Fecha de vencimiento: <?php echo date("d/m/Y", strtotime($data->date_end)) ;?>  </p>

                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----DATOS DEL CLIENTE-----</p>
                    <p style="text-align: center; align-content: center; margin: 0px;">NIT:<?php if($client_nit == 'CF'){ echo $client_nit;}elseif(strlen($client_nit)>4){echo $client_nit;}else{echo 'CF';}?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $client_name;?></p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----Descripción de la cotización----</p>
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
        
        <?php
        $totalExento = 0; $totalAfecto = 0; $total = 0;
        if ($data->products != "" || $data->products != null) {
            $pro = json_decode($data->products, true);
        } else {
            $pro = array();
        }
        for ($i = 0; $i < $data->num_products; $i++):
            $prod = $this->db->get_where('products', array('products_id' => ($pro[$i]['product'])))->row_array();
        ?>
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
            if ($client_type == 1): 
                $precio = isset($pro[$i]['price_my']) ? $pro[$i]['price_my'] : $pro[$i]['price'];
                $subtotal = isset($pro[$i]['sub_my']) ? $pro[$i]['sub_my'] : $pro[$i]['sub'];
            elseif ($client_type == 3): 
                $precio = isset($pro[$i]['price_farma']) ? $pro[$i]['price_farma'] : $pro[$i]['price'];
                $subtotal = isset($pro[$i]['sub_farma']) ? $pro[$i]['sub_farma'] : $pro[$i]['sub'];
            elseif ($client_type == 4): 
                $precio = isset($pro[$i]['price_ferretero']) ? $pro[$i]['price_ferretero'] : $pro[$i]['price'];
                $subtotal = isset($pro[$i]['sub_ferretero']) ? $pro[$i]['sub_ferretero'] : $pro[$i]['sub'];
            else: 
                $precio = $pro[$i]['price'];
                $subtotal = $pro[$i]['sub'];
            endif;

            $total += $subtotal;

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
            $montoImpuesto2 = ($totalAfecto/1.12);
            $totalImpuesto2 = $montoImpuesto2*$regimen;
        ?>
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
                                <p style="text-align: center; align-content: center; margin: 0px;"  >Atendido por: <?php echo $this->crud_model->getName('admin', $data->responsable ? $data->responsable : $this->session->userdata('login_user_id'));?></p>
                                <br>
    </div>
    

    </div>
</body>

</html>
