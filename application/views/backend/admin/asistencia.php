<div class="content-w">
 <div class="content-i">
	<div class="content-box">

<a class="btn btn-primary" href="<?php echo base_url();?>admin/reporte_asistencia/">Reporte de asistencia mensual</a><br><br><br>
	<?php echo form_open(base_url() . 'admin/attendance_selector/', array('class' => 'form m-b'));?>
	  <div class="row">
		<div class="col-sm-4">
		  <div class="form-group"> <label class="gi" for="">Fecha:</label> 
		  	<input class="single-daterange form-control" placeholder="Fecha" name="timestamp" type="text" required value=""> </div>
		</div>
		<div class="col-sm-2">
		  <div class="form-group"> <button class="btn btn-rounded btn-primary btn-upper" style="margin-top:20px" type="submit"><span>Ingresar</span></button></div>
		</div>
	  </div>
	<?php echo form_close();?>
  </div>
</div>
</div>