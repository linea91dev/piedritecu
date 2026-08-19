<?php $data	=	$this->db->get_where('creditos' , array('id' => $param2))->result_array();
  foreach ($data as $row):
?>   
<div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
            </div>
<div class="onboarding-content with-gradient">
  <h4 class="onboarding-title">Agregar nuevo pago al crédito</h4>
    <?php echo form_open(base_url() . 'admin/regis/'.$row['id'] , array('enctype' => 'multipart/form-data'));?>
      <div class="row">
          <div class="col-sm-12">
                    <div class="form-group">
                     <label for="">Monto total a crédito</label><input class="form-control" value="Q<?php echo $row['total'];?>" type="text" readonly>
                    </div>
                  </div>          
                  
                  <div class="col-sm-12">
                    <div class="form-group">
                     <label for="">Monto restante</label><input class="form-control" value="Q<?php echo $row['restante'];?>" type="text" readonly>
                    </div>
                  </div>          
                  
                  
                  <div class="col-sm-12">
                    <div class="form-group">
                     <label for="">Monto a cancelar</label><input class="form-control" value="" type="number" name="monto">
                    </div>
                  </div>          
                </div>
                <div class="form-buttons-w text-right compact">
                   <button class="btn btn-primary" type="submit"><span>Registrar</span></button>
                </div>
              <?php echo form_close();?>
            </div>
<?php endforeach; ?>