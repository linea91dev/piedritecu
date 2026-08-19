<div class="content-w">
  <div class="content-i">
    <div class="content-box">
			<div class="row">
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="<?php echo base_url();?>admin/usuarios/">
            <div class="value"><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i></div>
              <div class="label">Clientes</div>
          </a>
        </div>
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="<?php echo base_url();?>admin/empleados/">
            <div class="value"><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i></div>
            <div class="label">Empleados</div>
          </a>
        </div>
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="<?php echo base_url();?>admin/proveedores/">
            <div class="value"><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman"></i></div>
            <div class="label">Proveedores</div>
          </a>
        </div>
      </div>

      <div class="element-wrapper">
        <div class="element-box">
          <a data-target="#agregarusuarios" data-toggle="modal" class="btn btn-lg btn-primary pull-right btn-sm" href="#">
            <span>Agregar cliente</span><i class="picons-thin-icon-thin-0151_plus_add_new"></i>
          </a>
          <h5 class="text-center element-box-header">Manejar clientes</h5>           
            <div class="table-responsive">
            <table id="user_data" width="100%" class="table table-striped table-lightfont">
				<thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Opciones</th>
                </tr>
              </thead>
 
		    </table>
          </div>
        </div>
      </div>	  
    </div>
  </div>
</div>



<div aria-hidden="true" class="onboarding-modal modal fade animated" id="agregarusuarios" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg modal-centered" role="document">
          <div class="modal-content text-center">
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="os-icon os-icon-close"></span></button>
            <div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
            </div>
            <div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Crear nuevo cliente</h4>
              <div class="onboarding-text">Rellene todos los datos solicitados.</div>
              <?php echo form_open(base_url() . 'admin/usuarios/cliente/' , array('enctype' => 'multipart/form-data'));?>
                <div class="row">         
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Nombre</label><input class="form-control" placeholder="Ingresar nombre" name="nombre" type="text" required="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Correo</label><input class="form-control" placeholder="Correo" type="email" name="correo">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="">Teléfono</label><input class="form-control" placeholder="Teléfono" type="phone" name="telefono">
                    </div>
                  </div>
                </div>
                <div class="form-buttons-w text-right compact">
                  <button class="btn btn-primary" type="submit"><span>Agregar</span></button>
                </div>
              <?php echo form_close();?>
            </div>
          </div>
        </div>
      </div>
      
      
      
      
      <script type="text/javascript" language="javascript" >  
 $(document).ready(function(){  
      var dataTable = $('#user_data').DataTable({  
           "processing":true,  
           "serverSide":true,  
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'admin/get_clientes'; ?>",  
                type:"POST"  
           },  
           "columnDefs":[  
                {  
                     "targets":[0, 3, 4],  
                     "orderable":false,  
                },  
           ],  
      });  
 });  
 </script> 