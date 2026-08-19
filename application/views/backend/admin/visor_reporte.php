<?php $running_year = date('Y');?>
<div class="content-w">

  <div class="content-i">
    <div class="content-box">
       <div class="element-wrapper">
        <?php echo form_open(base_url() . 'admin/reporte_selector/', array('class' => 'form m-b')); ?>
            <form action="" class="form m-b">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group"> <label class="gi" for="">Mes:</label> 
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
                  <div class="form-group"> <button class="btn btn-primary btn-rounded btn-upper" style="margin-top:20px" type="submit"><span>Generar</span></button></div>
                </div>
              </div>
            <?php echo form_close();?>
            <?php if ($month != ''): ?>
            <div class="element-box lined-primary shadow">
              <div class="row">
                <div class="col-7 text-left">
                  <h5 class="form-header">Asistencia mensual de empleados</h5></div>
              </div>
              <div class="col-12 col-sm-12 col-lg-12">
                <div style="overflow-x:auto;">
                <table class="table table-lightborder">
                  <thead>
                    <tr class="text-center" height="50px">
                      <th class="text-left"> Empleados </th>
                      <?php
                        $year = explode('-', $running_year);
                        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year[0]); 
                        for ($i = 1; $i <= $days; $i++) { ?>
                            <td style="text-align: center;"><small><?php echo "". $i; ?></small></td>
                        <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        $data = array();
                        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                        $teachers = $this->db->get('admin')->result_array();
                        foreach ($teachers as $row): 
                    ?>
                    <tr>
                      <td><?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name; ?> </td>
                       <?php
                            $status = 0;
                            for ($i = 1; $i <= $days; $i++) {
                            $timestamp = strtotime($i . '-' . $month . '-' . $year[0]);
                            $attendance = $this->db->get_where('asistencia', array('admin_id' => $row['admin_id'], 'timestamp' => $timestamp, 'year' => $running_year, 'id_sucursal' => $this->session->userdata('login_user_id')))->result_array();
                            foreach ($attendance as $row1):
                            $month_dummy = date('d', $row1['timestamp']);
                            if ($i == $month_dummy)
                            $status = $row1['status'];
                            endforeach;
                        ?>
                            <td class="text-left"><b class="text-success"><small> <?php echo $row1['entrada'];?></small></b><br><b class="text-danger"><small><?php echo $row1['salida'];?></small></b></td>
                        <?php } ?>                 
                    </tr>   
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              </div>
            </div>
        <?php endif;?>
          </div>
        </div>
      </div>
    </div>
