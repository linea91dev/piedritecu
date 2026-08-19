<?php $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;?>
<div class="content-w">
  <div class="content-i">
    <div class="content-box">
	   <div class="element-wrapper">
        <?php echo form_open(base_url() . 'admin/reporte_selector/', array('class' => 'form m-b')); ?>
            <form action="" class="form m-b">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group"> <label class="gi" for="">Seleccionar mes:</label> 
                      <select name="month" class="form-control" id="month" onchange="show_year()">
            <?php
                for ($i = 1; $i <= 12; $i++):
                if ($i == 1) $m = "Enero";
                else if ($i == 2) $m = "Febrero";
                else if ($i == 3) $m = "Marzo";
                else if ($i == 4) $m = "Abril";
                else if ($i == 5) $m = "Mayo";
                else if ($i == 6) $m = "Junio";
                else if ($i == 7) $m = "Julio";
                else if ($i == 8) $m = "Agosto";
                else if ($i == 9) $m = "Septiembre";
                else if ($i == 10) $m = "Octubre";
                else if ($i == 11) $m = "Noviembre";
                else if ($i == 12) $m = "Diciembre";
            ?>
                <option value="<?php echo $i; ?>"<?php if($month == $i) echo 'selected'; ?>  ><?php echo ucwords($m); ?></option>
                <?php endfor; ?>
            </select>
                  </div>
                </div>
                <input type="hidden" name="operation" value="selection">
                <input type="hidden" name="year" value="<?php echo $running_year;?>">
                <div class="col-sm-2">
                  <div class="form-group"> 
                    <button class="btn btn-rounded btn-primary btn-upper" style="margin-top:20px" type="submit"><span>Generar</span></button>
                  </div>
                </div>
              </div>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
    </div>