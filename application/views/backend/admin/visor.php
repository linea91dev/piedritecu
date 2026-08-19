<?php $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;?>
<div class="content-w">
 <div class="content-i">
    <div class="content-box">
    <?php echo form_open(base_url() . 'admin/attendance_selector/', array('class' => 'form m-b'));?>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group"> <label class="gi" for="">Fecha:</label> <input class="single-daterange form-control" placeholder="Date" name="timestamp" type="text" required value="<?php echo date("m/d/Y", $timestamp);?>"> </div>
        </div>
        <input type="hidden" name="year" value="<?php echo $running_year;?>">
        <div class="col-sm-2">
          <div class="form-group"> <button class="btn btn-rounded btn-primary btn-upper" style="margin-top:20px" type="submit"><span>Ingresar</span></button></div>
        </div>
      </div>
    <?php echo form_close();?>
    <div class="element-box lined-primary shadow">
      <h5 class="form-header">Asistencia diaria de empleados</h5><br>
      <div class="table-responsive">
      <?php echo form_open(base_url() . 'admin/attendance_update2/' . $timestamp); ?>
        <table class="table table-lightborder">
          <thead>
            <tr>
              <th>Empleado</th>
              <th style="text-align: center;">Entrada</th>
              <th style="text-align: center;">Salida</th>
            </tr>
          </thead>
          <tbody>
           <?php
                $count = 1;
                $select_id = 0;
                $attendance = $this->db->get_where('asistencia', array('year' => date('Y'), 'timestamp' => $timestamp, 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                foreach ($attendance as $row):
            ?>
            <tr>
              <td style="min-width:170px"><?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name; ?></td>
               <td style="text-align: center;">
                <div class="form-group">
                  <div class="time-input">
                    <input class="form-control" id="time" value="<?php echo $row['entrada'];?>" name="entrada_<?php echo $row['attendance_id']; ?>" type="time">
                  </div>
                </div>
              </td>
              <td>
                <div class="form-group">
                  <div class="time-input">
                    <input class="form-control" id="time" value="<?php echo $row['salida'];?>" name="salida_<?php echo $row['attendance_id']; ?>" type="time">
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class="form-buttons-w">
          <button class="btn btn-success btn-rounded" type="submit">Actualizar</button>
        </div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
</div>