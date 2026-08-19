<div class="aside aside-left d-flex flex-column" id="kt_aside">
    <div class="aside-brand d-flex flex-column align-items-center flex-column-auto pt-5 pt-lg-18 pb-10">
        <div class="p-0 symbol symbol-60" href="<?php echo base_url();?>" id="kt_quick_user_toggle">
            <div class="symbol-label bg-white">
                <a href="<?php echo base_url();?>"><img alt="Logo" src="<?php echo ($this->db->get_where('settings', array('type'=>'logo'))->row()->description != '') ?  base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description : base_url().'public/assets/media/users/blank.png' ;?>"
                        style="max-width: 80px!important; height: auto;" /></a>
            </div>
        </div>
    </div>
</div>