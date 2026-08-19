<?php $moneda = $this->crud_model->get_info("moneda"); setlocale(LC_TIME, "spanish");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '1';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Ventas del día <small>(Hoy)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '1';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Capital del día <small>(Hoy)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->capital_vendido_hoy();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3">
                            <a href="javascript:;" onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '2';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                        Ventas de la semana <small>(<?php echo $this->crud_model->primerDiaSemana(date("Y"), date("m"), date("d"))." - ".$this->crud_model->ultimoDiaSemana(date("Y"), date("m"), date("d"));?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido_semana();?></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-3">
                            <a href="javascript:;" onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '3';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ventas del mes <small>(<?php $mes = date("F"); $nuevo_mes = strftime("%B", strtotime($mes)); echo ucwords($nuevo_mes);?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido_mes();?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-4">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '4';?>');"class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Ganancia del día <small>(Hoy)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido_por_dia();?></div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-4">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '5';?>');"class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                        Ganancia por semana <small>(<?php echo $this->crud_model->primerDiaSemana(date("Y"), date("m"), date("d"))." - ".$this->crud_model->ultimoDiaSemana(date("Y"), date("m"), date("d"));?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido_por_semana();?></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-4">
                            <a href="javascript:;" onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '6';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ganancia por mes <small>(<?php echo ucwords($nuevo_mes);?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$this->crud_model->total_vendido_por_mes();?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="card card-custom gutter-b"
                        style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" style="padding:25px">
                                    <h4>Comparación de ventas</h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-boby">
                                    <center>
                                        <div id="chart_3"></div>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <div
                            style="background: #8950fc; height: 50px; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;padding:15px;text-align:center;color:#fff">
                            <h5>Los gráficos representan al mes actual con el mes anterior.</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Ventas</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-sm">Se visualizarán los primeros
                                    <span class="text-primary">15 días</span> del mes.</span>
                            </h3>
                        </div>
                        <style>
                        table td {
                            padding: 5px !important;
                        }

                        table tr {
                            border-bottom: 1px solid #dddfe9;
                        }
                        </style>
                        <div class="border-bottom"></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-vertical-center">
                                    <tbody>
                                        <?php for ($i=1; $i <16 ; $i++) :?>
                                        <tr>
                                            <td><span
                                                    class="text-muted font-weight-bold"><?php  setlocale(LC_ALL,"es_ES") ; echo $i.' '.strftime(" %B ");?></span>
                                            </td>
                                            <td class="text-right">
                                                <span class="text-muted font-weight-bolder d-block font-size-lg"><a
                                                        href="javascript:;"><?php echo $this->crud_model->get_venta_diarias($i);?>
                                                        ventas</a></span>
                                            </td>
                                        </tr>
                                        <?php endfor;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bolder">Top 8 de productos más vendidos</h3>
                        </div>
                        <?php $productos = $this->crud_model->top_8_products();
                            if ($productos->num_rows() > 0):?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Categoría</th>
                                            <th class='text-right'>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos->result_array() as $pr):?>
                                        <tr>
                                            <td><?php echo $this->db->get_where('products', array('products_id'=>$pr['products_id']))->row()->name ;?>
                                            </td>
                                            <td><span
                                                    class="label label-lg font-weight-bold label-light-info label-inline"><?php  $category = $this->db->get_where('products', array('products_id'=>$pr['products_id']))->row()->category ; echo $this->db->get_where('categories',array('category_id'=>$category))->row()->name;?></span>
                                            </td>
                                            <td class='text-right'>
                                                <span class="text-success"><b><?php echo $pr['total'] ;?></b></span>
                                            </td>
                                        </tr>
                                        <?php endforeach ;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php else:?>
                        <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                            </center>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <!--<div class="col-sm-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title font-weight-bolder">Metas de rendimiento</h3>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="flex-grow-1">
                                <div id="kt_mixed_widget_14_chart" style="height: 200px"></div>
                            </div>
                            <div class="pt-5">
                                <?php if ($this->crud_model->get_venta_mesuales() >= '50'):?>
                                <p class="text-center font-weight-normal font-size-lg pb-7"><b>¡MUY CERCA!</b> Estás a
                                    poco de lograr alcanzar la meta en ventas de este mes, continúa así para no perder
                                    la racha.</p>

                                <?php elseif($this->crud_model->get_venta_mesuales()  <= '50'):?>
                                <p class="text-center font-weight-normal font-size-lg pb-7"><b>¡Advertencia!</b>
                                    Tu rendimiento es muy bajo y debe ser mejorado.</p>
                                <?php elseif($this->crud_model->get_venta_mesuales()  >= '100'):?>
                                <p class="text-center font-weight-normal font-size-lg pb-7"><b>¡Felicidades!</b>
                                    Se ha logrado el objetivo de ventas de este mes.</p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/assets/js/pages/features/charts/apexcharts1.js?v=7.2.9"></script>

<script type="text/javascript">
var procentaje = '<?php echo $this->crud_model->get_venta_mesuales();?>';
var _initMixedWidget14 = function() {
    var element = document.getElementById("kt_mixed_widget_14_chart");
    var height = parseInt(KTUtil.css(element, "height"));

    if (!element) {
        return;
    }

    var options = {
        series: [procentaje],
        chart: {
            height: height,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: "65%",
                },
                dataLabels: {
                    showOn: "always",
                    name: {
                        show: false,
                        fontWeight: "700",
                    },
                    value: {
                        color: KTApp.getSettings()["colors"]["gray"]["gray-700"],
                        fontSize: "30px",
                        fontWeight: "700",
                        offsetY: 12,
                        show: true,
                        formatter: function(val) {
                            return val + "%";
                        },
                    },
                },
                track: {
                    background: "#add7ff",
                    strokeWidth: "100%",
                },
            },
        },
        colors: ["#0084ff"],
        stroke: {
            lineCap: "round",
        },
        labels: ["Progress"],
    };

    var chart = new ApexCharts(element, options);
    chart.render();
};

var anulaciones = [<?php echo $this->crud_model->total_anulaciones()?>];
var cambios = [<?php echo $this->crud_model->total_cambios()?>];
var ventas = [<?php echo $this->crud_model->total_ventas()?>];
var _demo3 = function() {
    const apexChart = "#chart_3";

    var options = {
        series: [{
            name: 'Anulaciones',
            data: anulaciones
        }, {
            name: 'Cambios',
            data: cambios
        }, {
            name: 'Ventas',
            data: ventas
        }],
        chart: {
            type: 'bar',
            height: 350,
            locales: [{
            "name": "es",
            "options": {
                "months": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                "shortMonths": ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                "days": ["Domingo", "Lunes", "Martes", "Miércoles", "Juevez", "Viernes", "Sabado"],
                "shortDays": ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                "toolbar": {
                    "exportToSVG": "Descargar SVG",
                    "exportToPNG": "Descargar PNG",
                    "exportToCSV": "Descargar CSV",
                    "menu": "Menú",
                    "selection": "Selección",
                    "selectionZoom": "Zoom de selección",
                    "zoomIn": "Acercar",
                    "zoomOut": "Alejar",
                    "pan": "Panorámica",
                    "reset": "Restablecer zoom"
                }
            }
            }],
            defaultLocale: "es"
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ["Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Sep",
                "Oct",
                "Nov",
                "Dic",
            ],
        },
        yaxis: {
            title: {
                text: 'Ventas'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
};

var anulaciones_ms = '<?php echo $this->crud_model->total_anulaciones_ms()?>';
var cambios_ms = '<?php echo $this->crud_model->total_cambios_ms()?>';
var ventas_ms = '<?php echo $this->crud_model->total_ventas_ms()?>';
var _demo3x = function() {
    const apexChart = "#chart_3x";
    var options = {
        grid: {
            xaxis: {
                lines: {
                    show: false,
                },
            },
            yaxis: {
                lines: {
                    show: false,
                },
            },
        },
        series: [{
                name: "Anulaciones",
                data: [anulaciones_ms],
            },
            {
                name: "Cambios",
                data: [cambios_ms],
            },
            {
                name: "Ventas",
                data: [ventas_ms],
            },
        ],
        chart: {
            type: "bar",
            height: 350,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "25%",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: ["Anulaciones", "Cambios", "Ventas"],
        },
        yaxis: {
            title: {
                text: "Mes anterior",
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val;
                },
            },
        },
        colors: [primary, success, warning],
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
};
</script>