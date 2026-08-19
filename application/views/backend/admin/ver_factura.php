<?php  $ID = base64_decode($param2); $factura = $this->db->get_where('expense', array('expense_id'=>$ID))->row_array();?>
<div class="onboarding-content with-gradient">
    <h4 class="onboarding-title">Comprobante</h4>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <center>
                    <?php if($factura['factura_type'] == 'image'):?>
                    <img src="<?php echo base_url()."uploads/vouchers/".$factura['factura_img'];?>" style="max-height: 700px; max-width: 700px;"/>
                    <?php elseif($factura['factura_type'] == 'pdf'):?>
                    <embed src="<?php echo base_url().'uploads/vouchers/'.$factura['factura_img'];?>" width="700px" height="700px"/>
                    <?php endif;?>
                </center>
            </div>
        </div>
    </div>
</div>