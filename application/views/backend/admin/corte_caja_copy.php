<?php $date = "2023-11-10";
    $monto_limite = $this->crud_model->get_info('monto_limite'); 
    $moneda = $this->crud_model->get_info("moneda");
    $ingresos = $this->crud_model->ingresos_caja_date($date);
    $egresos = $this->crud_model->egresos_caja_date($date);
    $caja = $this->crud_model->total_caja();
    $transaccion= $this->crud_model->total_transf_date($date);
?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/create_box_cut_date/';?>" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <input type="hidden" name="date" value="<?php echo $date;?>" />
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
                                    <h3 class="font-weight-bolder">Actual en Caja:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-info">
                                    <?php echo $moneda.number_format($caja,2,'.',',');?></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="caja_actual" value="<?php echo $caja;?>" step="0.01" class="form-control"
                        aria-label="Text input with checkbox" readonly="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Total TransferenciasQQ:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-warning">
                                    <?php echo $moneda.number_format($transaccion,2,'.',',');?></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="monto_limite" value="<?php echo $monto_limite;?>" step="0.01"
                        class="form-control" aria-label="Text input with checkbox" readonly="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Total vendido en efectivo:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-success">
                                    <?php if ($ingresos > 0) echo $moneda.number_format($ingresos,2,'.',','); else echo $moneda.'0.00'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="ingresos" name="ingresos"
                        value="<?php if ($ingresos > 0) echo $ingresos; else echo 0; ?>" step="0.01"
                        class="form-control" aria-label="Text input with checkbox" readonly="true">
                </div>
            </div>
            <?php $diferencia = $ingresos - $egresos;?>
            <input type="hidden" name="diferencia_day" step="0.01" value="<?php echo $diferencia; ?>"
                class="form-control" aria-label="Text input with checkbox" readonly="true">
            <?php $esperado =  $ingresos+$transaccion;?>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Monto esperado en caja:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-success">
                                    <?php echo $moneda.number_format($esperado,2,'.',',');?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="caja_nueva" id="caja_nueva" step="0.01" value="<?php echo $esperado; ?>"
                        class="form-control" aria-label="Text input with checkbox" readonly="true">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Total en la Caja</b> <span class="text-danger">*</span></h3>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-boldest font-size-h1"><?php echo $moneda;?></span>
                        </div>
                        <input type="number" name="ver_caja" id="ver_caja" value="0.00" step="0.01" required="true" min="0"
                            class="form-control font-weight-boldest font-size-h1" aria-label="Text input with checkbox" oninput="verificar()">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="card card-custom gutter-b">
                        <div class="card-body d- flex f lex-column">
                            <div class="d-f lex align-ite ms-center justify-con tent -between flex-g row-1">
                                <div class="mr-2">
                                    <h3 class="font-weight-bolder">Diferencia:</h3>
                                </div>
                                <div class="font-weight-boldest font-size-h1 text-info">
                                    <span class=".number_format" id="lbl_diferencia"><?php echo $moneda.'0.00';?></span>
                                </div>
                                <small id="message" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="diferencia" name="diferencia_cash" step="0.01" value=""
                        class="form-control" aria-label="Text input with checkbox" readonly="true">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Notas:</b></h3>
                    <div class="input-group">
                        <textarea class="form-control" name="notes" aria-label="Text input with checkbox"
                            rows="3">Ninguna</textarea>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group" id='codigoAuth'>
                    <label> Código de autorización para aplicar descuentos: <span
                            class="text-danger">*</span></label>
                    <div class=" spinner-success spinner-left" id='spinnerCode'>
                        <input type="password" autocomplete="off" class='form-control' id='code'
                            placeholder='Ingresa el código de autorización' autofocus
                            onblur="getCodigo(this.value)">
                    </div>
                    <div id='mensajeError'></div>
                    <small class='text-info'>Presionar la tecla TAB para verificar tu código</small>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success font-weight-bold" id='Guardar'>Guardar</button>
        </div>
    </form>
</div>
<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$(document).ready(function() {
    $('#Guardar').attr('disabled',true);
});


function getCodigo(code) {
    var leng_code = code.length;
    var valor = 'descuentos';
    if (leng_code > 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/checkCodigos/',
            data: {
                code: code,
                valor: valor,
            },
            beforeSend: function() {
                $('#spinnerCode').addClass('spinner');
            },
            success: function(response) {
                if (response == 1) {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-success" >Código aceptado</small>');
                    $('#Guardar').removeAttr('disabled');                    
                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    $('#Guardar').attr('disabled',true);                    
                }

            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        $('.nueva_venta').attr('hidden', true);
        $('#mensajeError').html('<small class="text-info" >Ingrese un código </small>');
    }
}


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