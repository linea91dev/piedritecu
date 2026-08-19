<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3">
            <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
                style="background-position: right top; background-size: 30% auto; background-image: url(/metronic/theme/html/demo1/dist/assets/media/svg/shapes/abstract-1.svg)">
                <div class="card-body">
                    <span class="svg-icon svg-icon-2x svg-icon-info">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path
                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path
                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </span>
                    <span
                        class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block"><?php echo $this->crud_model->total_empleados() ;?></span>
                    <span class="font-weight-bold text-muted font-size-sm">Empleados</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card card-custom bg-info card-stretch gutter-b">
                <div class="card-body">
                    <span class="svg-icon svg-icon-2x svg-icon-white">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path
                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path
                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </span>
                    <span
                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $this->crud_model->total_admins() ;?></span>
                    <span class="font-weight-bold text-white font-size-sm">Administradores</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card card-custom bg-danger card-stretch gutter-b">
                <div class="card-body">
                    <span class="svg-icon svg-icon-2x svg-icon-white">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path
                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path
                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </span>
                    <span
                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $this->crud_model->total_proveedores() ;?></span>
                    <span class="font-weight-bold text-white font-size-sm">Proveedores</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card card-custom bg-dark card-stretch gutter-b">
                <div class="card-body">
                    <span class="svg-icon svg-icon-2x svg-icon-white">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path
                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path
                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </span>
                    <span
                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block"><?php echo $this->crud_model->total_clientes();?></span>
                    <span class="font-weight-bold text-white font-size-sm">Clientes</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card card-custom gutter-b"
                        style="border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" style="padding:25px">
                                    <h4>Comparación de ingresos y egresos</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card-boby">
                                    <center>
                                        <div id="ingresos_egresos"></div>
                                    </center>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card-boby">
                                    <center>
                                        <div id="ingresos_egresos2"></div>
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
                <div class="col-xl-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Total inventario <small
                                            class="text-muted font-size-lg mt-2">(Basado en costo)</small></h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-warning">
                                    <?php echo $moneda.$this->crud_model->total_inventario();?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Total bodega <small
                                            class="text-muted font-size-lg mt-2">(Basado en costo)</small></h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-info">
                                    <?php echo $moneda.$this->crud_model->total_bodega();?></div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Total inversión </h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-success">
                                    <?php echo $moneda.$this->crud_model->total_inversion();?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bolder">Detalles movimientos</h3>
                        </div>
                        <div class="card-body">
                            <div class="card-toolbar">
                                <form class="form" method="post" action="<?php echo base_url().'admin/reportes/generales';?>">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Desde:</label>
                                                <input type="text" class="form-control" name="date_initial" id="kt_datepicker" readonly
                                                    <?php if($date_initial != ''){?> value="<?php echo date("m/d/Y", strtotime($date_initial));?>"<?php } ?> />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hasta:</label>
                                                <input type="text" class="form-control" name="date_final" id="kt_datepicker_1" readonly
                                                    <?php if($date_final != ''){?> value="<?php echo date("m/d/Y", strtotime($date_final));?>"<?php } ?> />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-lg btn-icon btn-success btn-circle btn-hover-success"
                                                    data-toggle="tooltip" data-placement="right" data-container="body"
                                                    data-boundary="window" title="Buscar">
                                                    <i class="flaticon-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th class='text-right'>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ganancias</td>
                                            <td class='text-right'>
                                                <?php $ganancias = $this->crud_model->ganancia_dates($date_initial, $date_final); ?>
                                                <span class="text-success">
                                                    <b><?php echo $moneda.number_format($ganancias,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pérdidas de productos</td>
                                            <td class='text-right'>
                                                <?php $perdidas = $this->crud_model->total_losses_dates($date_initial, $date_final);?>
                                                <span class="text-danger">
                                                    <b><?php echo $moneda.number_format($perdidas,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Planillas</td>
                                            <td class='text-right'>
                                                <?php $planillas = $this->crud_model->expenses_dates($date_initial, $date_final, 'payroll');?>
                                                <span class="text-danger">
                                                    <b><?php echo $moneda.number_format($payroll,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Entregas</td>
                                            <td class='text-right'>
                                                <?php $entregas = $this->crud_model->expenses_dates($date_initial, $date_final, 'delivery');?>
                                                <span class="text-danger">
                                                    <b><?php echo $moneda.number_format($entregas,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Servicio de transporte</td>
                                            <td class='text-right'>
                                                <?php $servicios = $this->crud_model->expenses_dates($date_initial, $date_final, 'service_transport');?>
                                                <span class="text-danger">
                                                    <b><?php echo $moneda.number_format($servicios,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Otros gastos</td>
                                            <td class='text-right'>
                                                <?php $otros = $this->crud_model->expenses_dates($date_initial, $date_final);?>
                                                <span class="text-danger">
                                                    <b><?php echo $moneda.number_format($otros,2,'.',',');?></b>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <?php $total = floatval($ganancias) - (floatval($perdidas) + floatval($planillas) + floatval($entregas) + floatval($servicios) + floatval($otros));?>
                                        <tr>
                                            <td class='text-right'><b>Total</b></td>
                                            <td class='text-right'>
                                                <b>
                                                    <?php if($total < 0) echo '-'; echo $moneda.number_format(abs($total),2,'.',',');?><br>
                                                    <span class="" id="estado_resultado"></span>
                                                </b>
                                            </td>
                                        </tr>
                                    </tfoot>
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
<script src="<?php echo base_url();?>public/assets/js/pages/features/charts/apexcharts0.js?v=7.2.9"></script>


<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var ingresos = '<?php echo $this->crud_model->total_ingresos();?>';
var egresos = '<?php echo $this->crud_model->total_egresoss();?>';
var mes = '<?php setlocale(LC_ALL,"es_ES") ; echo ucwords(strftime(" %B "));?>';
var total = parseFloat('<?php echo $total; ?>');

$(document).ready(function(){
    if (total > 0) {
        $('#estado_resultado').attr('class', 'text-success');
        $('#estado_resultado').html('Utilidad neta');
    }
    else if(total < 0){
        $('#estado_resultado').attr('class', 'text-danger');
        $('#estado_resultado').html('Pérdida');
    }
    else if(total == 0){
        $('#estado_resultado').attr('class', 'text-warning');
        $('#estado_resultado').html('Sin utilidad neta');
    }
});

var _ingresos_egresos = function() {
    const apexChart = "#ingresos_egresos";
    var options = {
        grid: {
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        series: [{
            name: 'Ingresos',
            data: [ingresos]
        }, {
            name: 'Egresos',
            data: [egresos]
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
                columnWidth: '50%',

            },
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                return moneda + custom_number_format(value, '2', );
            },
            style: {
                colors: ['#ffffff']
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Ingresos', 'Egresos'],
        },
        yaxis: {
            title: {
                text: [mes]
            },
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return moneda + custom_number_format(val, '2', );
                }
            }
        },
        colors: [warning, danger]
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
}

var ingresos_ms = '<?php echo $this->crud_model->total_ingresos_ms();?>';
var egresos_ms = '<?php echo $this->crud_model->total_egresoss_ms();?>';
var mes_ms = 'Mes anterior'


var _ingresos_egresos2 = function() {
    const apexChart = "#ingresos_egresos2";
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
                name: "Ingresos",
                data: [ingresos_ms],
            },
            {
                name: "Egresos",
                data: [egresos_ms],
            },
        ],
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
                columnWidth: "50%",
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                return moneda + custom_number_format(value, '2', );
            },
            style: {
                colors: ['#ffffff']
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: ["Ingresos", "Egresos"],
        },
        yaxis: {
            title: {
                text: [mes_ms],
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return moneda + custom_number_format(val, '2', );
                },
            },
        },
        colors: [warning, danger, info],
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
};

function custom_number_format(number_input, decimals, dec_point, thousands_sep) {
    var number = (number_input + '').replace(/[^0-9+\-Ee.]/g, '');
    var finite_number = !isFinite(+number) ? 0 : +number;
    var finite_decimals = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    var seperater = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var decimal_pont = (typeof dec_point === 'undefined') ? '.' : dec_point;
    var number_output = '';
    var toFixedFix = function(n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
        } else {
            var arr = ('' + n).split('e');
            let sig = '';
            if (+arr[1] + prec > 0) {
                sig = '+';
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
        }
    }
    number_output = (finite_decimals ? toFixedFix(finite_number, finite_decimals).toString() : '' + Math.round(
        finite_number)).split('.');
    if (number_output[0].length > 3) {
        number_output[0] = number_output[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, seperater);
    }
    if ((number_output[1] || '').length < finite_decimals) {
        number_output[1] = number_output[1] || '';
        number_output[1] += new Array(finite_decimals - number_output[1].length + 1).join('0');
    }
    return number_output.join(decimal_pont);
}
</script>