<div class="content-i">
    <div class="content-box">
        <div class="row">
            <div class="col-sm-12">
                <div class="element-wrapper">
                    <div class="element-box">
                        <?php echo form_open(base_url() . 'admin/ajustes/do_update/' , array('class' => 'form-horizontal' , 'enctype' => 'multipart/from-data'));?>
                        <h5 class="form-header">Ajustes del sistema</h5>
                        <div class="form-desc">Todos los campos pueden ser modificados, si tiene dudas con respecto a estos ajustes por favor haga <a href="<?php echo base_url();?>admin/help" target="_blank">Click aquí.</a>
                            <div class="text-right">
                                <a class="btn btn-danger" onClick="return confirm('Los datos actuales podrían no guardarse al cerrar sesión. ¿Aún así, desea continuar?')" href="<?php echo base_url();?>admin/ajustes/session">Cerrar sesión en todos los dispositivos</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""> Dirección IP</label>
                            <input class="form-control" placeholder="Para impresiones" name="dirip" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->dirip;?>" required="required" type="text">
                        </div>
                        <div class="form-group">
                            <label for=""> Nombre del sistema</label>
                            <input class="form-control" placeholder="Nombre del sistema" name="nombre" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->nombre;?>" required="required" type="text">
                        </div>
                        <div class="form-group">
                            <label for=""> Título del sistema</label>
                            <input class="form-control" placeholder="Nombre del sistema" name="titulo" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->titulo;?>" required="required" type="text">
                        </div>
                        <div class="form-group">
                            <label for=""> Teléfono del sistema</label>
                            <input class="form-control" placeholder="Teléfono del sistema" name="telefono" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->telefono;?>" required="required" type="text">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for=""> Dirección</label>
                                    <input class="form-control" name="direccion" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->direccion;?>" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Correo electrónico</label>
                                    <input class="form-control" type="email" name="correo" value="<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->correo;?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-buttons-w">
                            <button class="btn btn-primary" type="submit"> Actualizar</button>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>