<?php $data	=	$this->db->get_where('pagos' , array('id' => $param2))->result_array();
  foreach ($data as $row):
?>   
<div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
            </div>
<div class="onboarding-content with-gradient">
  <h4 class="onboarding-title">Actualizar la información del pago</h4>
    <?php echo form_open(base_url() . 'admin/pagos/editar/'.$row['id'] , array('enctype' => 'multipart/form-data'));?>
      <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Descripción</label>
                      <input class="form-control" value="<?php echo $row['descripcion'];?>" placeholder="Descripción del pago" name="descripcion" name="Descripción" type="text">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                     <label for="">Fecha</label><input readonly="" class="form-control single-daterange"  type="text" value="<?php echo $row['fecha'];?>" name="fecha">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                     <label for="">Monto</label><input class="form-control" value="<?php echo $row['monto'];?>" type="number" name="monto">
                    </div>
                  </div>          
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Forma de Pago</label>
                      <select class="form-control" name="metodo" required="">
                        <option value="Cheque" <?php if($row['metodo'] == "Cheque") echo "selected";?>>Cheque</option>
                        <option value="Depósito" <?php if($row['metodo'] == "Depósito") echo "selected";?>>Depósito</option>
                        <option value="Efectivo" <?php if($row['metodo'] == "Efectivo") echo "selected";?>>Efectivo</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                     <label for="">Comprobante</label> 
                     <input name="comprobante" type="file">
                    </div>
                  </div>
                </div>
                <div class="form-buttons-w text-right compact">
                   <button class="btn btn-primary" type="submit"><span>Actualizar</span></button>
                </div>
              <?php echo form_close();?>
            </div>
<?php endforeach; ?>