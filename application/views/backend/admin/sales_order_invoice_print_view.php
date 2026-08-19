<title><?php echo $page_title;?></title>
<div id="print">
  <script src="assets/js/jquery-1.11.3.min.js"></script>
  <?php 
    $invoice_info = $this->db->get_where('sales_order' , array('order_id' => $invoice_id))->result_array();
    foreach($invoice_info as $info):
    $invoice_entries = json_decode($info['order_entries']);
  ?>
  
  <div style="width: 50%; float: left;">
    <img src="<?php echo base_url();?>assets/backend/img/logo.png" width="80">
    <br>
    <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->nombre;?>
    <br>
    <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->correo;?>
    <br>
    <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->telefono;?>
    <br>
    <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->direccion;?>
  </div>
  <div style="width: 50%; float: left; text-align: right; margin-bottom: 20px;">
    
    <b>Orden:</b> <?php echo $info['order_code'];?><br>
    <?php echo "<b>Responsable</b>";?> : <?php echo $this->db->get_where('admin', array('admin_id' => $info['seller_user']))->row()->name;?>
    <br>
    <b>Cliente:</b> <?php 
      $customer_id = $info['customer_user_id'];
      echo $this->db->get_where('clientes' , array('id' => $customer_id))->row()->nombre;
    ?>
    <br>
    <b>Dirección de entrega:</b> <?php echo $info['shipping_address'];?>
    <br>
  </div>
  <br><br><br><br><br><br>
  <table style="width:100%; border-collapse:collapse;border: 1px solid #000; margin-top: 20px;" border="1">
    <thead>
      <tr>
        <td style="text-align: center;"><strong>#</strong></td>
        <td style="text-align: center;"><strong>Producto</strong></td>
        <td style="text-align: center;"><strong>Precio</strong></td>
        <td style="text-align: center;"><strong>Cantidad</strong></td>
        <td style="text-align: center;"><strong>Subtotal</strong></td>
      </tr>
    </thead>
    <tbody>
      <?php
        $count = 1;
        foreach($invoice_entries as $invoice_entry):
          $product_id = $invoice_entry->variant_id;
      ?>
        <tr>
          <td style="text-align: center;"><?php echo $count++;?></td>
          <td style="text-align: center;"><?php echo $this->db->get_where('producto' , array('id' => $product_id))->row()->nombre;?>.</td>
          <td align="center">Q<?php echo number_format($invoice_entry->selling_price);?>.</td>
          <td align="center"><?php echo $invoice_entry->ordered_quantity;?></td>
          <td align="center">Q<?php echo number_format($invoice_entry->sub_total);?>.</td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <p style="text-align: right">
    <b><?php echo "Total";?> : <?php echo "Q" . '' . number_format($info['total_amount']);?>.</b>
  </p>

<?php endforeach;?>

</div>


<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    var elem = $('#print');
    PrintElem(elem);
    Popup(data);

  });

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); 
        mywindow.focus();
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>