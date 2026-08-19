<?php $edit_data = $this->db->get_where('product_details' , array('products_id' => $param2, 'branch_id !=' => $this->session->userdata('branch_id') ))->result_array();
	foreach ($edit_data as $row):
?>

<div class="onboarding-content with-gradient">

        <div class="modal-body">
            <div class="d-flex align-items-center flex-wrap mb-8">									
				<div class="symbol symbol-50 symbol-light mr-5">
					<span class="symbol-label">
						<img src="<?php echo base_url().'uploads/productos/'.$this->db->get_where('products', array('products_id'=>$param2) )->row()->img;?>" class="h-50 align-self-center" alt="">
					</span>
				</div>
				
				<div class="d-flex flex-column flex-grow-1 mr-2">
					<a href="javascript:;" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"><?php echo $this->db->get_where('products', array('products_id'=>$param2) )->row()->name; ?></a>
					<span class="text-muted font-weight-bold">Sucursal: <?php echo $this->db->get_where('branch', array('branch_id'=>$row['branch_id']) )->row()->name ;?></span>
				</div>
				
				<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder"><?php echo $row['amount'];?> Unidades</span>
			</div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>
<?php endforeach; ?>


<script type="text/javascript">


</script>