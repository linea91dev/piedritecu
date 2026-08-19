<?php 
    $edit_data	=	$this->db->get_where('bodega' , array('id' => $param2))->result_array();
  	foreach ($edit_data as $row):
?>   
<div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
            </div>
<div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Actualizar la información del producto</h4>
              <form action="<?php echo base_url('admin/productos/update/'.$row["id"].'');?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Producto</label><input class="form-control" placeholder="Producto" value="<?php echo $row['nombre'];?>" name="nombre" required="" type="text" value="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Marca</label><input class="form-control" value="<?php echo $row['marca'];?>" name="marca" placeholder="Marca" type="text" value="">
                    </div>
                  </div>
                 
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Categoría</label>
                      <select class="form-control" name="codigo_categoria" required="">
                        <option value="">Seleccionar categoría</option>
                        <?php 
                          $categorias = $this->db->get('categoria')->result_array();
                          foreach($categorias as $row2):
                        ?>
                          <option value="<?php echo $row2['codigo'];?>" <?php if($row['codigo_categoria'] == $row2['codigo']) echo "selected";?>><?php echo $row2['nombre'];?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>  
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Descripción</label>
                      <input class="form-control" name="descripcion" value="<?php echo $row['descripcion'];?>" placeholder="Descripción" type="text" value="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Precio de Compra</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Q</div>
                        </div>
                        <input name="costo" class="form-control" value="<?php echo $row['costo'];?>" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Precio de Venta</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">Q</div>
                          </div>
                          <input class="form-control" name="precio" value="<?php echo $row['precio'];?>" type="text">
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Stock Inicial</label>
                      <input class="form-control" name="stock" value="<?php echo $row['stock'];?>" placeholder="Stock Inicial" type="text" value="">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Cantidad de Alerta</label>
                      <input class="form-control" name="alerta" value="<?php echo $row['alerta'];?>" placeholder="Cantidad de Alerta" type="number" value="">
                    </div>
                  </div>
                </div>
                <div class="form-buttons-w text-right compact">
                   <button class="btn btn-primary" type="submit"><span>Actualizar</span></button>
                </div>
             </form>
            </div>
<?php endforeach; ?>