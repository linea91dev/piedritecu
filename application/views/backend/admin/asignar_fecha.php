<?php 
    $moneda = $this->crud_model->get_info("moneda");
    $data = $this->db->get_where('sales', array('code' => $param2));
    setlocale(LC_TIME, "spanish");
    foreach ($data->result_array() as $row):
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/envios/asignar/'.$row['code'];?>" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                        <div class="alert-text">
                            Los campos marcados con * son obligatorios.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Código:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-info">
                                    <?php echo $row['code'];?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Cliente:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-warning">
                                    <?php echo $this->crud_model->getName('client', $row['client_id']);?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Fecha de venta:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-danger">
                                    <?php $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Costo de envío:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-success">
                                    <?php echo $moneda.number_format($row['shipping_cost'],2,'.',',');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Asignar fecha para la entrega:</b> <span class="text-danger">*</span></h3>
                    <div class="input-group">
                        <input type="date" name="shipping_date" value="<?php echo date('Y-m-d');?>" required="true" class="form-control" aria-label="Text input with checkbox">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
        </div>
    </form>
</div>
<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

function verificar() {

    var msg = '';
    let ver_caja = $("#ver_caja").val();
    let caja_nueva = $("#caja_nueva").val();
    let diferencia = parseFloat(ver_caja) - parseFloat(caja_nueva);

    if (diferencia > 0) {
        msg = "Diferencia sobrante";
        $("#message").attr('class', 'text-warning');
    }
    if (diferencia < 0) {
        msg = "Diferencia faltante";
        $("#message").attr('class', 'text-danger');
    }
    if (diferencia == 0) {
        msg = "Monto equivalente";
        $("#message").attr('class', 'text-success');
    }

    if (ver_caja == '') {
        diferencia = 0.00;
        msg = "";
    }

    diferencia = Math.abs(diferencia);
    diferencia_format = custom_number_format(diferencia, '2',);
    $("#lbl_diferencia").html(moneda + diferencia_format);
    $("#diferencia").val(diferencia.toFixed(2));
    $("#message").html(msg);
}

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
<?php endforeach; ?>