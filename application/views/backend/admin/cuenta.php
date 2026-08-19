<div class="content-w">
  <div class="content-i">
    <div class="content-box">
    <div class="row">
<?php 
    $edit_data = $this->db->get_where('admin' , array('admin_id' => $this->session->userdata('login_user_id')))->result_array();
    foreach ($edit_data as $row3):
?>
  <div class="col-sm-12">
    <div class="element-wrapper">
      <div class="element-box">
        <?php echo form_open(base_url() . 'admin/cuenta/editar/', array('id' => 'formValidate', 'enctype' => 'multipart/form-data'));?>
          <div class="element-info">
            <div class="element-info-with-icon">
              <div class="element-info-text">
                <h5 class="element-inner-header">Tu información personal</h5>
              </div>
            </div>
          </div>
        <div class="form-group">
          <label class="col-form-label" for=""> Nombre</label>
          <div class="input-group">
          <input class="form-control" placeholder="" value="<?php echo $row3['name'];?>" name="name" type="text" required>
          </div>
        </div>
        <div class="row">
        <div class="col-sm-6">
        <div class="form-group">
          <label class="col-form-label" for="">Usuario</label>
          <div class="input-group">
            <input class="form-control" name="username" value="<?php echo $row3['username'];?>" type="text" >
          </div>
          </div>
        </div>
        <div class="col-sm-6">
        <div class="form-group">
        <label class="col-form-label col-sm-3" for=""> Correo</label>
          <div class="input-group">
          <input class="form-control" value="<?php echo $row3['email'];?>" name="email" type="email">
          </div>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-sm-12">  
        <div class="form-group">
        <label class="col-form-label" for=""> Actualizar contraseña</label>
          <div class="input-group">
          <input class="form-control" placeholder="Nueva contraseña" name="password" type="password">
          </div>
        </div>
        </div>
        </div>
       
          <div class="form-buttons-w">
            <button class="btn btn-rounded btn-success" type="submit"> Actualizar</button>
          </div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
      <?php endforeach;?>

      </div>
    </div>
  </div>
</div>