<?php $moneda = $this->crud_model->get_info("moneda"); setlocale(LC_TIME, "spanish");
    $hoy = date("Y-m-d");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <!-- <div class="col-xl-4">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '1';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Ventas del día <small>(<?php if($date == $hoy) echo "Hoy"; else echo date("d/m/Y", strtotime($date));?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$Total_vendido_por_dia;?></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-4">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '1';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Capital del día <small>(<?php if($date == $hoy) echo "Hoy"; else echo date("d/m/Y", strtotime($date));?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$Capital_del_dia;?></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-6">
                            <a href="javascript:;"  onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '4';?>');"class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Ganancia del día <small>(<?php if($date == $hoy) echo "Hoy"; else echo date("d/m/Y", strtotime($date));?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$Total_ganancia_por_dia;?></div>
                                </div>
                            </a>
                        </div> -->
                        
                        <div class="col-xl-6">
                            <a href="javascript:;" onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '3';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ventas del mes <small>(<?php $nuevo_mes = strftime("%B del %Y", strtotime($year_month)); echo ucfirst($nuevo_mes);?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $Total_vendido_mes;?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-6">
                            <a href="javascript:;" onclick="showModalReportes('<?php echo base_url();?>modal/popup/ventas_info/<?php echo '6';?>');" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ganancia por mes <small>(<?php echo ucfirst($nuevo_mes);?>)</small></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$ganancia_mes;?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-6">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ventas del <?php echo strftime("%d de %b del %Y", strtotime($initial)).' al '.strftime("%d de %b del %Y", strtotime($final));?></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $Total_vendido_fechas;?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-6">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Ganancia del <?php echo strftime("%d de %b del %Y", strtotime($initial)).' al '.strftime("%d de %b del %Y", strtotime($final));?></div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.$ganancia_fechas;?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-12">
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
                        
                    </div>
                </div>
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