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
                                        Anulaciones diarias</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_anulaciones_diarias();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4">
                            <a href="#" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                        Anulaciones semanales</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_anulaciones_semanales();?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4">
                            <a href="#" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div
                                        class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                        Anulaciones mensuales</div>
                                    <div class="font-weight-bold text-inverse-white font-size-sm">
                                        <?php echo $moneda.$this->crud_model->total_anulaciones_mensuales();?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="card card-custom gutter-b"
                        style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" style="padding:25px">
                                    <h4>Historial de anulaciones</h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-boby">
                                    <center>
                                        <div id="anulaciones"></div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Ventas anuladas</span>
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
                                                        <?php echo $this->crud_model->get_anulaciones_diarias($i);?>
                                                        Anulaciones </a></span>
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
<script src="<?php echo base_url();?>public/assets/js/pages/features/charts/apexcharts2.js?v=7.2.9"></script>

<script type="text/javascript">
var meses = [<?php echo $this->crud_model->meses_anulaciones();?>];
var _anulaciones = function() {
    const apexChart = "#anulaciones";
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
            data: meses,
        }, ],
        chart: {
            type: "bar",
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
            categories: [
                "Ene",
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
                text: "Anual",
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
        colors: [warning, danger, info],
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
};
</script>