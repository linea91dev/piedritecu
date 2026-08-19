<div class="content-w">
  <div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
    <div class="element-box">
      <h5 class="element-box-header">Utilidad</h5>
        <div class="element-balances">
          <div class="balance">
            <div class="balance-title">Ventas del día</div>
            <?php
                if(date('D')!='Sat'){    
                    $staticstart = date('Y-m-d',strtotime('last Saturday'));    
                }else{
                    $staticstart = date('Y-m-d');   
                }
                if(date('D') != 'Fri') {
                    $staticfinish = date('Y-m-d',strtotime('next Friday'));
                }else{
                    $staticfinish = date('Y-m-d');
                }
                $totals; 
                $this->db->where("status", 1);
                $this->db->where("fecha >= '$staticstart' AND fecha <= '$staticfinish'");
                //$this->db->where("fecha );
                $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                $informacion  = $this->db->get('gastos')->result_array();
                foreach($informacion as $info)
                {
                    $totals += $info['monto'];
                }
                $ioriginalDate = $staticstart;
                $newDate = date("d m, Y", strtotime($ioriginalDate));
                $total; 
                $viaje1;
                $viaje2;
                $total2;
                $total3;
                $data  = $this->db->get_where('ventas', array('dia' => date('d'), 'mes' => date('M'), 'anio' => date('Y'), 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                $data2  = $this->db->get_where('producto', array('id_sucursal'=> $this->session->userdata('id_sucursal')))->result_array();
                $viajes = $this->db->get_where('sales_order', array('dia' => date('d'), 'anio' => date('Y'), 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                $total3  = $this->crud_model->obtener_ganancia($staticstart,$staticfinish);
                foreach($data as $row)
                {
                    $total += $row['total'];
                }
                foreach($viajes as $row2)
                {
                    $viaje1 += $row2['total_amount'];
                }
                foreach($data2 as $rows)
                {
                    $datos = $rows['costo'] * $rows['stock'];
                    $total2 += $datos;
                }
                 
            ?>
              <div class="balance-value"><span>Q<?php echo number_format($total+$viaje1);?></span><span class="trending trending-down-basic"></div>
            </div>
            <div class="balance">
              <div class="balance-title">Total inventareado</div>
              <div class="balance-value">Q<?php echo number_format($total2);?></div>
            </div>
            <div class="balance">
              <div class="balance-title">Ganancias de la semana <br><small>(<b><?php echo $staticstart." al ".$staticfinish;?>)</b></small></div>

              <div class="balance-value primary">Q<?php echo number_format($total3-$totals,2,".",",");?></div>
            </div>
          </div>
        </div>
    </div>
      <div class="row">
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "reportes") echo "bg-primary";?>" <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?> href="<?php echo base_url();?>admin/reportes/">
            <div class="value"><i <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?> class="picons-thin-icon-thin-0424_money_payment_dollar_cash"></i></div>
              <div class="label" <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?>>Ventas</div>
          </a>
        </div>
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "pagos") echo "bg-primary";?>" <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?> href="<?php echo base_url();?>admin/pagos/">
            <div class="value"><i <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?> class="picons-thin-icon-thin-0450_shipping_box_delivery"></i></div>
            <div class="label" <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?>>Control de pagos</div>
          </a>
        </div>
        
        <div class="col-sm-4">
          <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "ganancias") echo "bg-primary";?>" <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?> href="<?php echo base_url();?>admin/ganancias/">
            <div class="value"><i <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?> class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i></div>
            <div class="label" <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?>>Gananacias</div>
          </a>
        </div>

      </div>
      <div class="element-wrapper">
        <div class="element-box">
          <h5 class="element-box-header">Control de pagos</h5>
          <br>
          <div class="text-right">
            <a data-target="#agregarproducto" data-toggle="modal" class="btn btn-lg btn-primary btn-sm" href="#">
              <span>Registrar pago</span> <i class="picons-thin-icon-thin-0151_plus_add_new"></i>
            </a>
          </div>
            <div class="table-responsive" style="margin-top:25px">
              <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                 <thead>
                  <tr>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Encargado</th>
                    <th class="text-center">Método de pago</th>
                    <th class="text-center">Monto</th>
                    <th class="text-center">Ver Comprobante</th>
                    <th class="text-center">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                    $pagos = $this->db->get('pagos')->result_array();
                    foreach($pagos as $row):
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $row['fecha'];?></td>
                    <td><small><?php echo $row['descripcion'];?></small></td>
                    <td class="text-center"><b><?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name;?></b></td>
                    <td class="text-center"><?php echo $row['metodo'];?></td>
                    <td class="text-center"><span class="text-success"><b>Q<?php echo number_format($row['monto']);?></b></span></td>
                    <td class="text-center"><a class="badge badge-primary" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_pago/<?php echo $row['id'];?>');"><i class="picons-thin-icon-thin-0070_paper_role"></i></a></td>
                    <td class="text-center">
                      <a onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_editar_pago/<?php echo $row['id'];?>');" href="#" class="btn btn-success btn-sm btn-rounded"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i> Editar</a>

                      <a onClick="return confirm('¿Esta seguro que desea eliminar el comprobante?')" href="<?php echo base_url();?>admin/pagos/eliminar/<?php echo $row['id'];?>"><button class="btn btn-danger btn-sm btn-rounded"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i> Eliminar</button></a></td>
                  </tr>
                <?php endforeach;?>
               </tbody>
            </table>
          </div>        
        </div>
      </div>  
    </div>  
  </div>





  <div aria-hidden="true" class="onboarding-modal modal fade animated" id="agregarproducto" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg modal-centered" role="document">
          <div class="modal-content text-center">
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="os-icon os-icon-close"></span></button>
            <div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
              <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
            </div>
            <div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Registrar nuevo pago</h4>
              <?php echo form_open(base_url() . 'admin/pagos/nuevo/', array('enctype' => 'multipart/form-data'));?>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Descripción</label>
                      <input class="form-control" placeholder="Descripción del pago" name="descripcion" name="Descripción" type="text">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                     <label for="">Fecha</label><input class="form-control single-daterange"  type="text" name="fecha">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                     <label for="">Monto</label><input class="form-control"  type="number" name="monto">
                    </div>
                  </div>          
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Forma de Pago</label>
                      <select class="form-control" name="metodo" required="">
                        <option value="Cheque">Cheque</option>
                        <option value="Depósito">Depósito</option>
                        <option value="Efectivo">Efectivo</option>
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
                  <button type="submit" class="btn btn-primary"><span>Registrar</span></button>
                </div>
              <?php echo form_close();?>
            </div>
          </div>
        </div>
    </div>
</div>