<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">

        <div class="col-xl-6">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Total Venta <small> <b> (Hoy)</b> </small></h3> 
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-warning">
                            <?php echo $moneda.$this->crud_model->ganancia_dia();?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Caja chica</h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-success">
                            <?php $caja_chica = $this->db->get_where('account_bank', array('bank_id' => 0, 'branch_id'=>$this->session->userdata('branch_id')));
                            if ($caja_chica->num_rows() > 0) {
                                echo $moneda.number_format($caja_chica->row()->current_balance,2,'.',',');
                            }
                            else {
                                echo $moneda.'0.00';
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Total Efectivo </h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-info">
                            <?php echo $moneda.$this->crud_model->total_efectivo_f();?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Total Transferencia</h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-success">
                            <?php echo $moneda.$this->crud_model->total_transferencia_f();?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Total Depósito</h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-danger">
                            <?php echo $moneda.$this->crud_model->total_deposito_f();?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">Total Cheque</h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-danger">
                            <?php echo $moneda.$this->crud_model->total_cheque_f();?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php $bancos = $this->db->get_where('account_bank', array('bank_id !='=>0 ,'branch_id'=>$this->session->userdata('branch_id'), 'branch_id'=>'0'))->result_array(); foreach ($bancos as $banco) :?>
        <div class="col-xl-4">
            <div class="card card-custom gutter-b">
                <div class="card-body d- flex f lex-column">
                    <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                        <div class="mr-2">
                            <h3 class="font-weight-bolder">
                                <?php echo ucwords($banco['name_account'].' ('.$this->db->get_where('bank', array('bank_id'=>$banco['bank_id']))->row()->name.')') ;?>
                            </h3>
                        </div>
                        <div class="font-weight-boldest font-size-h1 text-info">
                            <?php echo $moneda.number_format($banco['current_balance'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>

    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/assets/js/pages/features/charts/apexcharts0.js?v=7.2.9"></script>


<script type="text/javascript">
var ingresos = '<?php echo $this->crud_model->total_ingresos();?>';
var egresos = '<?php echo $this->crud_model->total_egresoss();?>';
var mes = '<?php setlocale(LC_ALL,"es_ES") ; echo strftime(" %B ");?>';
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
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '25%',

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
                    return val
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