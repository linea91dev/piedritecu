<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-xl-4">
                            <a href="#" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Cambios diarios</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_cambios_diarios();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4">
                            <a href="#" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Cambios semanales</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_cambios_semanales();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4">
                            <a href="#" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                        Cambios mensuales</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_cambios_mensuales();?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="card card-custom gutter-b"
                        style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" style="padding:25px">
                                    <h4>Historial de Cambios</h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-boby">
                                    <center>
                                        <div id="chart_1"></div>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <!-- <div
                            style="background: #8950fc; height: 50px; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;padding:15px;text-align:center;color:#fff">
                            <h5>Ver detalles</h5>
                        </div> -->
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Cambios realizados</span>
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
                                                        href="javascript:;">
                                                        <?php echo $this->crud_model->get_cambios_diarios($i);?>
                                                        cambios </a></span>
                                            </td>
                                        </tr>
                                        <?php endfor;?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/assets/js/pages/features/charts/apexcharts3.js?v=7.2.9"></script>

<script type="text/javascript">
var meses = [<?php echo $this->crud_model->meses_cambios();?>];

var _cambios = function() {
    const apexChart = "#chart_1";
    var options = {
        series: [{
            name: "Cambios",
            data: meses,
        }, ],
        chart: {
            height: 350,
            type: "line",
            zoom: {
                enabled: false,
            },
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
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "straight",
        },
        grid: {
            row: {
                colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                opacity: 0.5,
            },
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
        colors: [primary],
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
};
</script>