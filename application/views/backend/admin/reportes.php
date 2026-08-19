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
                            <div class="balance-value"><span>Q<?php echo number_format($total+$viaje1);?></span><span
                                    class="trending trending-down-basic"></div>
                        </div>
                        <div class="balance">
                            <div class="balance-title">Total inventareado</div>
                            <div class="balance-value">Q<?php echo number_format($total2);?></div>
                        </div>
                        <div class="balance">
                            <div class="balance-title">Ganancias de la semana
                                <br><small>(<b><?php echo $staticstart." al ".$staticfinish;?>)</b></small></div>

                            <div class="balance-value primary">Q<?php echo number_format($total3-$totals,2,".",",");?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "reportes") echo "bg-primary";?>"
                        <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?>
                        href="<?php echo base_url();?>admin/reportes/">
                        <div class="value"><i <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?>
                                class="picons-thin-icon-thin-0424_money_payment_dollar_cash"></i></div>
                        <div class="label" <?php if($page_name == "reportes"):?> style="color: #fff;" <?php endif;?>>
                            Ventas</div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "pagos") echo "bg-primary";?>"
                        <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?>
                        href="<?php echo base_url();?>admin/pagos/">
                        <div class="value"><i <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?>
                                class="picons-thin-icon-thin-0450_shipping_box_delivery"></i></div>
                        <div class="label" <?php if($page_name == "pagos"):?> style="color: #fff;" <?php endif;?>>
                            Control de pagos</div>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php if($page_name == "ganancias") echo "bg-primary";?>"
                        <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?>
                        href="<?php echo base_url();?>admin/ganancias/">
                        <div class="value"><i <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?>
                                class="picons-thin-icon-thin-0406_money_dollar_euro_currency_exchange_cash"></i></div>
                        <div class="label" <?php if($page_name == "ganancias"):?> style="color: #fff;" <?php endif;?>>
                            Gananacias</div>
                    </a>
                </div>

            </div>
            <div class="element-wrapper">
                <div class="element-box">
                    <?php $year = date('Y');?>
                    <h5 class="element-box-header">Historial de ventas</h5>
                    <div class="table-responsive" style="margin-top:25px">
                        <table id="user_data" width="100%" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Ver Recibo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var dataTable = $('#user_data').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "<?php echo base_url() . 'admin/get_ventas'; ?>",
            type: "POST"
        },
        "columnDefs": [{
            "targets": [0, 3, 4],
            "orderable": false,
        }, ],
    });
});
</script>