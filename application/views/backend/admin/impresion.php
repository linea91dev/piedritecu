<?php
	  $system_name        =	$this->db->get_where('sucursales' , array('id_sucursal'=>$this->session->userdata('id_sucursal')))->row()->nombre;
    $system_email       =   $this->db->get_where('sucursales' , array('id_sucursal'=>$this->session->userdata('id_sucursal')))->row()->correo;
    //$running_year       =   $this->db->get_where('sucursales' , array('id_sucursal'=>$this->session->userdata('id_sucursal')))->row()->description;
    $data       =   $this->db->get_where('ventas' , array('id'=>$id))->result_array();
    foreach($data as $row):
?>
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>assets/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="http://wsg.com.gt/demo/style/cms/css/main.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/backend/icon_fonts_assets/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/backend/icon_fonts_assets/picons-thin/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/backend/icon_fonts_assets/picons-social/style.css" rel="stylesheet">
    <script src="<?php echo base_url();?>assets/js/jquery-1.11.0.min.js"></script>

<div class="content-w">
<div class="content-i">
    <div class="content-box">
      <div class="element-wrapper">
      <div class="rcard-wy">
        <div class="rcard-w">
          <div class="infos">
            <div class="info-1">
              <div class="rcard-logo-w">
                <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" style="max-width:200px; max-height: 70px;">
              </div><br>
              <div class="company-name"><?php echo $system_name;?></div>
              <div class="company-address"><b>Dirección:</b> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->direccion;?><br><b>Teléfono:</b> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->telefono;?></div>
            </div>
            <div class="info-2">
            <div class="rcard-profile">
            <img alt="" src="https://www.catholique78.fr/wp-content/themes/adv-2015/img/personne-default.png">
            </div><br>
              <div class="company-name"><?php echo $this->db->get_where('clientes' , array('id' => $row['client_id']))->row()->nombre;?></div>
              <div class="company-address">
                <b>Dirección:</b> <?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->direccion;?><br/><b>Teléfono:</b><?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->telefono;?>
              </div>
            </div>
          </div>
          <div class="rcard-heading">
            <h5>Recibo #<?php echo $row['id'];?></h5>
            <div class="rcard-date"><?php echo $row['fecha'];?><br> <b>Atendió:</b>  <?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name;?></div>
          </div>
            <div class="rcard-table table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Precio Unidad</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Subtotal</th>
                  </tr>
                </thead>
                 <tbody>
                    <?php 
                      $array = unserialize($row['productos']);
                      foreach($array as $ro):                          
                    ?>  
                    <tr>
                      <td class="text-center"><?php echo $this->db->get_where('producto', array('id' => $ro['id']))->row()->nombre;?></td>
                      <td class="text-center">Q<?php echo number_format($ro['price']);?></td>
                      <td class="text-center"><?php echo number_format($ro['qty']);?></td>
                      <td class="text-center">Q<?php echo number_format($ro['price']*$ro['qty']);?></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td class="text-center"><b>Total:</b></td>
                      <td></td>
                      <td></td>
                      <td class="text-center" colspan="2"><b>Q<?php echo number_format($row['total']);?></b></td>
                    </tr>
                  </tfoot>
              </table>
          </div>
          <div class="rcard-footer">
            <div class="rcard-logo">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg"><span><?php echo $system_name;?></span>
            </div>
            <div class="rcard-info">
              <span><?php echo $system_email;?></span><span><?php echo $phone;?></span>
            </div>
          </div>
        </div>
        </div>
      </div>
      
    </div>
</div>
</div>
<?php endforeach;?>
<script type="text/javascript">
    javascript:window.print();
   // alert("Press CTRL+P to Print Marksheet");
</script>