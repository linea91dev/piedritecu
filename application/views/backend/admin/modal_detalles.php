<?php 
    $edit_data	=	$this->db->get_where('gastos' , array('id' => $param2))->result_array();
  	foreach ($edit_data as $row):
?>
<div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
    <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
</div>
<div class="onboarding-content with-gradient">
    <h4 class="onboarding-title">Detalles del gasto</h4>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Fecha</label>
                <input type="text" class="form-control single-daterange" name="fecha"
                    value="<?php echo $row['fecha'];?>" disabled>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Responsable</label>
                <input type="text" class="form-control" name="responsable" value="<?php echo $row['para'];?>" disabled>
                <small>Persona que recibe el dinero.</small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Monto</label>
                <input type="text" class="form-control" name="monto" value="Q<?php echo $row['monto'];?>" disabled>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="descripcion" rows="5"
                    disabled><?php echo $row['descripcion'];?></textarea>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>