<div id="sales_order_entry_<?php echo $count;?>">
<br>
<div class="row">
  <div class="col-sm-4" style="width: 30%;">	 
    <select id="<?php echo $count;?>" class="form-control" onchange="show_response_for_append(this.value , <?php echo $count;?>)">
	<option value="">Seleccione</option>
	<?php 
	    $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
   	    $products = $this->db->get('producto')->result_array();
	  foreach($products as $row):
	  $stock_quantity = $row['stock'];
          $selected_variants_array = explode("." , $selected_variants);
	  if(in_array($row['id'], $selected_variants_array))
          continue;
	?>
  	  <option value="<?php echo $row['id'];?>" <?php if($stock_quantity == 0) echo 'disabled';?>><?php echo $row['nombre'];?>
	    <?php if($stock_quantity == 0) echo '(' . "Sin existencias" . ')';?>
	  </option>
	<?php endforeach;?>
    </select>
  </div>

<div class="col-sm-2" style="width: 10%;">
  <input id="" type="text" class="form-control" disabled="disabled">
</div>

<div class="col-sm-2" style="width: 9%;">
			
</div>

<div class="col-sm-2" style="width: 9%;">
  <input id="" type="text" class="form-control" disabled="disabled">
</div>

<div class="col-sm-2" style="width: 12%; display:none;">
   <input id="" type="text" class="form-control" disabled="disabled">
</div>
<div class="col-sm-2" style="width: 15%; display:none;">
  <input id="" type="text" class="form-control" disabled="disabled">
</div>
<div class="col-sm-2" style="width: 12%; display:none;">
			
</div>

<div class="col-sm-2" style="width: 2%;">
  <button class="btn btn-primary" onclick="deleteParentElement(this)"><i class="picons-thin-icon-thin-0060_error_warning_danger_stop_delete_exit" style="color: #fff; cursor: pointer;"
                  ></i></button>
</div>
</div>
</div>


<script type="text/javascript">
	function show_response_for_append(variant_id , count)
	{
		$.ajax({
			url: '<?php echo base_url();?>admin/sales_order_entry_response/' + variant_id + '/' + count,
			success: function(response)
			{
				jQuery('#sales_order_entry_' + '<?php echo $count;?>').html(response);
			}
		});
	}
</script>