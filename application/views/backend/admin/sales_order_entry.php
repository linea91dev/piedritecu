<?php 
	$variant_info = $this->db->get_where('producto' , array('id' => $variant_id))->result_array();
	foreach($variant_info as $info):
?>
<br>
<div class="row">
  <div class="col-sm-4">
      <strong><?php echo $info['nombre'];?></strong>
      <input id="" class="variant" type="hidden" name="variant_id[]" value="<?php echo $info['id'];?>">
  </div>

  <div class="col-sm-2" style="width: 10%;">
    <input id="selling_price_<?php echo $count;?>" type="text" class="form-control" name="selling_price[]" value="<?php echo $info['precio'];?>" readonly>
  </div>

  <div class="col-sm-2" style="width: 9%; text-align: center;">
    <strong><?php echo $info['stock'];?></strong>
  </div>

  <div class="col-sm-2" style="width: 9%;">
    <input id="ordered_quantity_<?php echo $count;?>" type="number" class="form-control" name="qty[]" value="1" min="1"
      onchange="calculate_subtotal(<?php echo $count;?>)" onkeyup="calculate_subtotal(<?php echo $count;?>)">
  </div>

<div class="col-sm-2" style="width: 12%; display:none;">
		<input id="discount_<?php echo $count;?>" type="number" class="form-control" name="discount[]" value="0" step="0.1" min="0"
			onchange="calculate_subtotal(<?php echo $count;?>)" onkeyup="calculate_subtotal(<?php echo $count;?>)">
	</div>


<div class="col-sm-2" style="width: 15%; display: none;">
		<select class="form-control selectboxit" name="tax_value[]" id="tax_value_<?php echo $count;?>"
			onchange="calculate_subtotal(<?php echo $count;?>)">
			<option value="0">Sin impuestos</option>
		</select>
	</div>

 
  <div class="col-sm-2" style="display: none; text-align: center;">
    <strong>Q</strong><strong id="subtotal_<?php echo $count;?>"></strong>
  </div>
  <div class="col-sm-2" style="width: 2%;">
     <button class="btn btn-primary" onclick="deleteParentElement(this)"><i class="picons-thin-icon-thin-0060_error_warning_danger_stop_delete_exit" style="color: #fff; cursor: pointer;"
                  ></i></button>
  </div>

</div>
<?php endforeach;?>

<script type="text/javascript">
	function calculate_subtotal(count) {
		selling_price = Number($('#selling_price_' + count).val());
		ordered_quantity = Number($('#ordered_quantity_' + count).val());
		discount_value = Number($('#discount_' + count).val() / 100);
		tax_value = Number($('#tax_value_' + count).val() / 100);
		total = (selling_price * ordered_quantity);
		total_with_discount = total - (total * discount_value);
		subtotal = total_with_discount + (total_with_discount * tax_value);
		subtotal = subtotal.toFixed(2);
		$('#subtotal_' + count).html(subtotal);
		calculate_grand_total();
	}

	function calculate_grand_total() {
		count = '<?php echo $count;?>';
		grand_total = 0;
		for(i = 1; i <= count; i++) {
			if ($('#subtotal_'+ i).length) {
		    	grand_total += Number( $('#subtotal_'+ i).html() );
			}
		}
		grand_total = grand_total.toFixed(2);
		$('#grand_total').html(grand_total);
	}

	$(document).ready(function() {
		calculate_subtotal(count);
		$('#add_entry_button').prop('disabled' , false);
		if($.isFunction($.fn.selectBoxIt))
		{
			$("select.selectboxit").each(function(i, el)
			{
				var $this = $(el),
					opts = {
						showFirstOption: attrDefault($this, 'first-option', true),
						'native': attrDefault($this, 'native', false),
						defaultText: attrDefault($this, 'text', ''),
					};
				$this.addClass('visible');
				$this.selectBoxIt(opts);
			});
		}
	});
</script>