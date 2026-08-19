<div class="content-w">
      <div class="os-tabs-w menu-shad">
      <div class="os-tabs-controls">
        <ul class="nav nav-tabs upper">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo base_url();?>admin/settings/"><i class="os-icon picons-thin-icon-thin-0050_settings_panel_equalizer_preferences"></i><span><?php echo get_phrase('system_settings');?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url();?>admin/email/"><i class="os-icon picons-thin-icon-thin-0315_email_mail_post_send"></i><span><?php echo get_phrase('email_settings');?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url();?>admin/translate/"><i class="os-icon picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i><span><?php echo get_phrase('translate');?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url();?>admin/database/"><i class="picons-thin-icon-thin-0356_database"></i><span><?php echo get_phrase('database');?></span></a>
        </li>
        </ul>
      </div>
      </div>
  <div class="content-i">
    <div class="content-box">
    <?php echo form_open(base_url() . 'admin/settings/do_update');?>
  <div class="row">
    <div class="col-sm-12">
      <div class="element-box lined-primary shadow">
      <h5 class="form-header"><?php echo get_phrase('system_settings');?></h5><br>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_name');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0047_home_flat"></i>
        </div>
        <input class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?>" type="text" name="system_name">
        </div>
      </div></div>
            <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_title');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
        </div>
        <input class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?>" required name="system_title" type="text">
        </div>
      </div></div>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_address');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0536_navigation_location_drop_pin_map"></i>
        </div>
        <input class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'address'))->row()->description;?>" name="address" type="text">
        </div>
      </div></div>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_phone');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0296_phone_call_contact"></i>
        </div>
        <input class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'system_phone'))->row()->description;?>" name="phone" type="text">
        </div>
      </div></div>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_email');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0319_email_mail_post_card"></i>
        </div>
        <input class="form-control" name="system_email" value="<?php echo $this->db->get_where('settings', array('type' => 'system_email'))->row()->description;?>" type="email">
        </div>
      </div></div>
        <div class="form-group row">
        <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('languages');?></label>
        <div class="col-sm-9">
          <div class="input-group">
          <div class="input-group-addon">
            <i class="picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i>
          </div>
          <select class="form-control" name="language">
            <option value=""><?php echo get_phrase('select');?></option>
           <?php $fields = $this->db->list_fields('language');
                        foreach ($fields as $field)
                        {
                        if ($field == 'phrase_id' || $field == 'phrase') continue;
                      $current_default_language = $this->db->get_where('settings' , array('type'=>'language'))->row()->description; ?>
                        <option value="<?php echo $field;?>"<?php if ($current_default_language == $field) echo 'selected';?>> <?php echo $field;?> </option>
                      <?php } ?>
          </select>
          </div>
        </div>
        </div>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('system_currency');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i>
        </div>
        <input class="form-control" name="currency" placeholder="$" value="<?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description;?>" type="text">
        </div>
      </div></div>
      <div class="form-group row">
      <label class="col-form-label col-sm-3" for=""> <?php echo get_phrase('paypal_email');?></label>
        <div class="col-sm-9">
        <div class="input-group">
        <div class="input-group-addon">
          <i class="icon-paypal"></i>
        </div>
        <input class="form-control" name="paypal_email" value="<?php echo $this->db->get_where('settings', array('type' => 'paypal_email'))->row()->description;?>" type="email">
        </div>
        </div>
      </div>
      <div class="form-buttons-w text-right">
            <button class="btn btn-primary btn-rounded" type="submit"> <?php echo get_phrase('update');?></button>
          </div>
    </div>
    </div>
<?php echo form_close();?>

    <div class="col-sm-12">
      <div class="element-box lined-purple shadow">
    <?php echo form_open(base_url() . 'admin/settings/logo', array('enctype' => 'multipart/form-data'));?>
      <legend><span>Logo & Favicon</span></legend>
      <div class="row padded-v">
        <div class="col-sm-6">
        <div class="form-group">
        <label class="col-form-label" for=""><?php echo get_phrase('logo');?></label>
            <input accept="image/x-png,image/gif,image/jpeg" type="file"/ name="userfile">     <br>    
          <div class="margin:0 auto"><img width="30%" height="30%" src="<?php echo base_url();?>uploads/logo.png"></div>
       </div></div>
       <div class="col-sm-6">
        <div class="form-group">
        <label class="col-form-label" for=""><?php echo get_phrase('favicon');?></label>
            <input accept="image/x-png,image/gif,image/jpeg" type="file"/ name="favicon">  <br>           
          <div class="margin:0 auto"><img width="15%" height="15%" id="favicon" src="<?php echo base_url();?>uploads/favicon.png"></div>
       </div></div>
       </div>
       <div class="form-buttons-w text-right">
            <button class="btn btn-primary btn-rounded" type="submit"> <?php echo get_phrase('update');?></button>
          </div>
       </div>
    </div>
    <?php echo form_close();?>
  </div>
  </div>
</div>
</div>