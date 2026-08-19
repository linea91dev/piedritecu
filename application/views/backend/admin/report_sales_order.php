<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box">
                    <style type="text/css">
                    #chartdiv {
                        width: 100%;
                        height: 500px;
                        font-size: 11px;
                    }
                    </style>

                    <?php echo form_open(base_url() . 'admin/report_sales_order_view' , array('class' => 'form-horizontal'));?>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Empleado:</label>
                            <select class="form-control" name="empleado">
                                <option value="">Seleccionar empleado</option>
                                <?php 
              $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
              $empleados = $this->db->get('admin')->result_array();
                  foreach($empleados as $row):
              ?>
                                <option value="<?php echo $row['admin_id'];?>"><?php echo $row['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Desde:</label>
                            <input type="text" class="form-control single-daterange" name="timestamp_range[]"
                                value="<?php echo date('m/d/Y');?>">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Hasta:</label>
                            <input type="text" class="form-control single-daterange" name="timestamp_range[]"
                                value="<?php echo date('m/d/Y');?>">
                        </div>
                        <div class="col-md-2" style=" margin-top:30px;">
                            <button type="submit" id="" class="btn btn-primary btn-icon icon-left btn-sm">Generar
                                reporte</button>
                        </div>
                    </div>
                    <?php echo form_close();?>

                    <br><br>

                    <div class="panel panel-primary" data-collapsed="0">
                        <div class="panel-body">
                            <div id="chartdiv"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <strong>
                                            <center>
                                                <font color="white">Reporte de ventas</font>
                                            </center>
                                        </strong>
                                    </div>
                                </div>

                                <div class="panel-body with-table">
                                    <table class="table table-bordered datatable color-table inverse-table"
                                        id="table-4">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Fecha</th>
                                                <th style="text-align: center;">Número de ordenes</th>
                                                <th style="text-align: center;">Empleado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
			  $start_date = date('Y-m-d' , $timestamp_start);
			  $end_date   = date('Y-m-d' , $timestamp_end);
			  $dates      = $this->crud_model->get_dates_within_range($start_date , $end_date);					
			  foreach($dates as $date):
		  	  $timestamp = strtotime($date->format('Y-m-d'));
			  $this->db->like('date_added' , $timestamp);
			  $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
		  	  $this->db->from('sales_order');
			  $sales_order_count = $this->db->count_all_results();
		        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $date->format('Y-m-d');?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <strong><?php echo $sales_order_count;?></strong></td>
                                                <td style="text-align: center;">
                                                    <strong><?php echo $this->db->get_where('admin', array('admin_id' => $admin_id))->row()->name;?></strong>
                                                </td>
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
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    var $table4 = jQuery("#table-4");

    $table4.DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
<?php $tot;?>

<script type="text/javascript">
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "dataProvider": [
        <?php 
				$start_date = date('Y-m-d' , $timestamp_start);
				$end_date   = date('Y-m-d' , $timestamp_end);
				$dates      = $this->crud_model->get_dates_within_range($start_date , $end_date);					
				foreach($dates as $date):
					$timestamp = strtotime($date->format('Y-m-d'));
					$this->db->like('date_added' , $timestamp);
					$this->db->like('seller_user' , $admin_id);
					$this->db->from('sales_order');
					$sales_order_count = $this->db->count_all_results();
			?> {
            "date": "<?php echo $date->format('d M');?>",
            "order": "<?php echo $sales_order_count;?>"
        },
        <?php endforeach;?>

    ],

    "valueAxes": [{
        "gridColor": "#FFFFFF",
        "gridAlpha": 0.2,
        "dashLength": 0
    }],

    "gridAboveGraphs": true,
    "startDuration": 1,
    "graphs": [{
        "balloonText": "[[category]] : <b>[[value]] <?php echo "Viajes"?></b>",
        "fillAlphas": 0.8,
        "lineAlpha": 0.2,
        "type": "column",
        "valueField": "order"
    }],

    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },

    "categoryField": "date",
    "categoryAxis": {
        "gridPosition": "start",
        "gridAlpha": 0,
        "tickPosition": "start",
        "labelRotation": 2,
        "tickLength": 20
    },

    "export": {
        "enabled": false
    }

});
</script>