    <div class="footer py-2 py-lg-0 my-5 d-flex flex-lg-column" id="kt_footer">
        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
            <?php $sha1 = file_get_contents(base_url().'uploads/codigo.txt'); $sha2 = $this->db->get_where('settings', array('type'=>'sha1'))->row()->description ; if($sha1 != $sha2):?>
            <div class="text-dark order-2 order-md-1">
                <span class="text-muted font-weight-bold mr-2"><?php echo date('Y');?> ©</span>
                <a href="https://lineo.gt" target="_blank" class="text-dark-75 text-hover-primary"><b>MSBox</b></a>
                un producto de <b>Linea90&Uno</b>.
            </div>
           
            <?php else:?>
            <div class="text-dark order-2 order-md-1">
                <span class="text-muted font-weight-bold mr-2"><?php echo date('Y');?> ©</span>
                <a href="<?php echo base_url();?>"
                    class="text-dark-75 text-hover-primary"><b><?php  echo $this->db->get_where('settings', array('type'=>'name'))->row()->description ;?>.</b></a>
            </div>
            <?php endif;?>
        </div>
    </div>