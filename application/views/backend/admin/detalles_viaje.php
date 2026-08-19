<div class="content-box">
  <?php 
    $totals;
    $invoice_info = $this->db->get_where('sales_order' , array('order_id' => $code))->result_array();
    foreach($invoice_info as $info):
    $invoice_entries = json_decode($info['order_entries']);
  ?>
  <div class="element-box">
    <h5 class="form-header">Detalles del viaje</h5>   
    <h6 class="form-header"></h6>
      <div class="text-right">
        <a class="btn btn-primary" target="_blank" href="<?php echo base_url();?>admin/sales_order_invoice_print_view/<?php echo $info['order_id'];?>">Imprimir</a>
      </div>
<hr>
    <div class="row">
    <div class="col-sm-6">
    <p><b>Cliente:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $info['customer_user_id']))->row()->nombre;?></span></p>
  </div>
  <div class="col-sm-6">
    <p><b>Teléfono:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $info['customer_user_id']))->row()->telefono;?></span></p>
  </div>
  <div class="col-sm-6">
    <p><b>NIT:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $info['customer_user_id']))->row()->nit;?></span></p>
  </div>
  <div class="col-sm-6">
    <p><b>Dirección:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $info['customer_user_id']))->row()->direccion;?></span></p>
  </div>

<div class="col-sm-12">
    <p><b>Descripción:</b> <span class="text-primary"><?php echo $info['descripcion'];?></span></p>
  </div>
<div class="col-sm-12">
    <p><b>Cantidad de combustible:</b> <span class="text-primary"><?php echo $info['gas'];?></span></p>
  </div>
  </div><hr>
    
  </div>
<?php endforeach;?>
</div>