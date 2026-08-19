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
    $data = $this->db->get_where('sales', array('code'=>$code))->row();
    
    $mayorista = false;
    $type = $this->db->get_where('client', array('client_id'=>$data->client_id))->row()->type;
    
    if($data->client_id == 0){
        $mayorista = false;    
    }else{
        if($type == 1)
            $mayorista = true;
        else
            $mayorista = false;
    }
    ?>
    

<body style='margin: 0; font-size:14px; font-family: Poppins; font-weight: bold;'>
    <div style="width:100%;">

        <div class="ticket">
                <p style="text-align: center; align-content: center; margin: 0px;">
                    <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Logotipo" style='max-width: 150px; width: 150px;'>
                </p>
                    <br>
                    <?php if($this->db->get_where('settings', array('type'=>'FEL'))->row()->description == ''):?>
                        <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $this->db->get_where('settings', array('type'=>'address'))->row()->description;?></p><br>
                        <p style="text-align: center; align-content: center; margin: 0px;">DOCUMENTO NO CONTABLE1111111</p>

                    <?php endif;?>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;"> <b> CREDITO </b></p>
                   
                    <p style="text-align: center; align-content: center; margin: 0px;">Fecha de emisión: <?php echo date("d/m/Y", strtotime(date('Y-m-d'))) ;?>  </p>

                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----DATOS DEL COMPRADOR-----</p>
                    <p style="text-align: center; align-content: center; margin: 0px;">NIT:<?php echo $data->nit;?></p>
                    <p style="text-align: center; align-content: center; margin: 0px;"><?php echo $data->name;?></p>
                    <br>
                    <p style="text-align: center; align-content: center; margin: 0px;">----Descripción de los productos----</p>
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
        
        <?php for ($i=0; $i < $data->num_products ; $i++):
                    if ($data->products != "" || $data->products != null) {
                        $pro = json_decode($data->products,true);
                        }else{
                        $pro = array();
                    } ?>
        <?php for ($i = 0; $i < $data->num_products; $i++): ?> 
        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?php echo $pro[$i]['amount'];?></td>
            <td style="text-align: center; white-space:nowrap;"><?php echo $this->db->get_where('products', array('products_id'=>$pro[$i]['product']))->row()->name ;?></td>

            <?php if ($mayorista):?> 
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i][price_my],2,'.',',');?></td>
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i][sub_my],2,'.',',');?></td>

                <?php $total += $pro[$i][sub_my];
                        $montoGravable = $total/($regimen + 1); ?>
                
            <?php else:?> 
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i][price],2,'.',',');?></td>
                <td style="text-align: center;"><?php echo $moneda.number_format($pro[$i][sub],2,'.',',');?></td>

                <?php $total += $pro[$i][sub];
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
        <td style="text-align: center;">---------</td>
    </tr>
    <tr style='border-collapse: collapse;'>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"></td>
        <td style="text-align: center;"><b>TOTAL</b></td>
        <td style="text-align: center;"><?php echo $moneda.number_format($data->total,2,'.',',');?></td>
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