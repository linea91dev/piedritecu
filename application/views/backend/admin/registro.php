<div class="content-w">
  <div class="content-i">
    <div class="content-box">
      <div class="element-wrapper">
    <div class="element-box">
      <h5 class="element-box-header">Utilidad</h5>
        <div class="element-balances">
          <div class="balance">
            <div class="balance-title">Ventas Totales</div>
            <?php
              $total; 
              $total2;
              $total3;
              $data  = $this->db->get('ventas')->result_array();
              $data2  = $this->db->get('producto')->result_array();
              $data3  = $this->db->get('transporte')->result_array();
              foreach($data as $row)
              {
                $total += $row['total'];
              }
              foreach($data2 as $rows)
              {
                $datos = $rows['costo'] * $rows['stock'];
                $total2 += $datos;
              }
               foreach($data3 as $rowss)
              {
                $total3 += $rowss['monto'];
              }
            ?>
              <div class="balance-value"><span>Q<?php echo number_format($total);?></span><span class="trending trending-down-basic"></div>
            </div>
            <div class="balance">
              <div class="balance-title">Total inventareado</div>
              <div class="balance-value">Q<?php echo number_format($total2);?></div>
            </div>
            <div class="balance">
              <div class="balance-title">Combustible</div>

              <div class="balance-value primary">Q<?php echo number_format($total3);?></div>
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
          <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "registro") echo "bg-primary";?>" <?php if($page_name == "registro"):?> style="color: #fff;" <?php endif;?> href="<?php echo base_url();?>admin/registros/">
            <div class="value"><i <?php if($page_name == "registro"):?> style="color: #fff;" <?php endif;?> class="picons-thin-icon-thin-0022_calendar_month_day_planner"></i></div>
            <div class="label" <?php if($page_name == "registro"):?> style="color: #fff;" <?php endif;?>>Registro diario</div>
          </a>
        </div>

      </div>
      <div class="element-wrapper">
        <div class="element-box">
	  <h5 class="element-box-header">Registros diarios de empleados</h5>
 	   <div class="table-responsive" style="margin-top:25px">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
					     <thead>
                <tr>
                  <th class="text-center">Código</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Cliente</th>
                  <th class="text-center">Vendedor</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Ver Recibo</th>
                </tr>
              </thead>
					    <tbody>
                <?php 
                  $ventas = $this->db->get('ventas')->result_array();
                  foreach($ventas as $row):
                ?>
					       <tr>
                    <td class="text-center"><small><b>#<?php echo $row['codigo'];?></b></small></td>
                    <td class="text-center"><small><?php echo $row['fecha'];?></small></td>
                    <td class="text-center"><b><?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->nombre;?></b></td>
                    <td class="text-center"><img alt="" src="img/avatar1.jpg" style="height: 25px;border-radius:50%"><span> <?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name;?></span></td>
                    <td class="text-center text-success"><span style="font-weight:bold;font-size:18px">Q<?php echo number_format($row['total']);?></span></td>
                    <td class="text-center"><a class="badge badge-primary" href="<?php echo base_url();?>admin/recibo/<?php echo $row['id'];?>/"><i class="picons-thin-icon-thin-0070_paper_role"></i></a></td>
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