<div class="content-w">
  <div class="content-i">
    <div class="content-box">
      <div class="element-wrapper">
    <div class="element-box">
      <h5 class="element-box-header">Ganancias</h5>
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
                $this->db->where("fecha >=", $staticstart);
                $this->db->where("fecha <=", $staticfinish);
                $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                $informacion  = $this->db->get('gastos')->result_array();
                foreach($informacion as $info)
                {
                    $totals += $info['monto'];
                }

                $total; 
                $viaje1;
                $viaje2;
                $total2;
                $total3;
                $data  = $this->db->get_where('ventas', array('dia' => date('d'), 'mes' => date('M'), 'anio' => date('Y'), 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                $data2  = $this->db->get_where('producto', array('id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                $viajes = $this->db->get_where('sales_order', array('dia' => date('d'), 'anio' => date('Y'), 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();
                $total3 = $this->crud_model->obtener_ganancia($staticstart,$staticfinish);
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
        <?php if($mes == '01') $mon = "Enero";?>
		<?php if($mes == '02') $mon = "Febrero";?>
	    <?php if($mes == '03') $mon = "Marzo";?>
		<?php if($mes == '04') $mon = "Abril";?>
		<?php if($mes == '05') $mon = "Mayo";?>
		<?php if($mes == '06') $mon = "Junio";?>
		<?php if($mes == '07') $mon = "Julio";?>
		<?php if($mes == '08') $mon = "Agosto";?>
		<?php if($mes == '09') $mon = "Septiembre";?>
		<?php if($mes == '10') $mon = "Octubre";?>
		<?php if($mes == '11') $mon = "Noviembre";?>
		<?php if($mes == '12') $mon = "Diciembre";?>
      <div class="element-wrapper">
        <div class="element-box">
                <?php $year = date('Y');?>
				  <h5 class="element-box-header">Reportes de ganancias</h5>
				  <?php echo form_open(base_url() . 'admin/ganancias/');?>
				    <select class="form-control" style="max-width:200px;" onchange="submit();" name="mes">
				        <option value="">Seleccionar</option>
				        <option value="01" <?php if($mes == '01') echo "selected";?>>Enero</option>
				        <option value="02" <?php if($mes == '02') echo "selected";?>>Febrero</option>
				        <option value="03" <?php if($mes == '03') echo "selected";?>>Marzo</option>
				        <option value="04" <?php if($mes == '04') echo "selected";?>>Abril</option>
				        <option value="05" <?php if($mes == '05') echo "selected";?>>Mayo</option>
				        <option value="06" <?php if($mes == '06') echo "selected";?>>Junio</option>
				        <option value="07" <?php if($mes == '07') echo "selected";?>>Julio</option>
				        <option value="08" <?php if($mes == '08') echo "selected";?>>Agosto</option>
				        <option value="09" <?php if($mes == '09') echo "selected";?>>Septiembre</option>
				        <option value="10" <?php if($mes == '10') echo "selected";?>>Octubre</option>
				        <option value="11" <?php if($mes == '11') echo "selected";?>>Noviembre</option>
				        <option value="12" <?php if($mes == '12') echo "selected";?>>Diciembre</option>
				    </select>
				   <?php echo form_close();?>
				  <div class="table-responsive" style="margin-top:25px">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
			<thead>
                    <tr>
                    
                    <th class="text-center">Mes</th>
                    <th class="text-center">Ganancias</th>
                    <th class="text-center">Gastos</th>
                    <th class="text-center">Ganancias menos gastos</th>
                    </tr>
                </thead>
			    <tbody>
                <?php 
                    $jaja = 0;
                    $juju = 0;
                    $ventass = $this->db->get_where('ganancias', array('mes' => $mes, 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();  
                    $gastos = $this->db->get_where('gastos', array('mes' => $mes, 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();  
                    $this->db->group_by('mes');
                    $ventas = $this->db->get_where('ganancias', array('mes' => $mes, 'id_sucursal' => $this->session->userdata('id_sucursal')))->result_array();   
                    foreach($ventas as $roma):
                ?>
			    <tr>
                    <td class="text-center"><small><?php echo $mon;?></small></td>
                    <td class="text-center"><b>
                        <?php
                            foreach($ventass as $jiji)
                            {
                                $jaja += $jiji['ganancia'];
                            }
                        ?>
                        <?php echo "Q".number_format($jaja,2,".",",");?>
                    
                    </b></td>
                    <td class="text-center"><span> 
                    <?php
                            foreach($gastos as $jojo)
                            {
                                $juju += $jojo['monto'];
                            }
                        ?>
                        <b><?php echo "Q".number_format($juju,2,".",",");?></b>
                    </span></td>
                    <td class="text-center text-success"><span style="font-weight:bold;font-size:18px">Q<?php echo number_format($jaja-$juju);?></span></td>
                  </tr>
                <?php endforeach;?>
			    </tbody>
			</table>
          </div>				
        </div>
      </div>	
    </div>	
  </div>
</div>