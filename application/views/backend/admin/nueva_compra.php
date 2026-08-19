<?php $moneda = $this->crud_model->get_info("moneda");?>
<style>
.resultado td:hover {
    background: #8950fc2b;
}

</style>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url();?>admin/compras/create" method="POST" enctype="multipart/form-data" id='compraForm' name='compraForm' onsubmit="return checkSubmit();">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    Para comenzar, ingresa el nombre del producto en el buscador ubicado en la parte inferior y luego
                    presiona la tecla
                    <b>ENTER</b> para iniciar la busqueda.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value='<?php echo $code;?>'>
                                <input type="hidden" name="type" value='1'>
                                <span>Codigo de compra: <b>
                                        <? echo $code ?>
                                    </b> <span style="float:right"><b>Fecha de solicitud:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( date('Y-m-d') ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                        <?php echo date('H:i:s');?> </span></span>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12"> <br>
                                        <div class="spinner-success spinner-left" id='spinnerPr'>
                                            <input autocomplete="off" type="text" class='form-control' id='name' onchange="search()"  placeholder='Ingrese el nombre o codigo' autofocus>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-padded">
                                        <tbody id="resultado" class="mostly-customized-scrollbars col-sm-12 resultado" style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="col-sm-12">
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos a registrar</h3>
                        <div class="table-responsive">
                            <table class="table table-padded" id="traslados">
                                <thead>
                                    <tr>
                                        <th class='text-left'>Codigo</th>
                                        <th class='text-left'>Producto</th>
                                        <th class='text-center'>Vencimiento</th>
                                        <th class='text-center'>Cantidad</th>
                                        <th class='text-center'>Precio (<?php echo $moneda;?>)</th>
                                        <th class='text-center'>Subtotal</th>
                                        <th class='text-right'>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var addProduct = false;

/*
var row_count = $('#traslados tr').length;
$('#compraForm').submit(function (e){
    if(row_count>=1){
    e.preventDefault();    
    return;
    }
});*/

$(document).ready(function() {
    $('#provider_id').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    
    $('.js-example-basic-single-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });

    $('.js-example-basic-single-').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    sum(0);
})


function submitForm() {
    document.compraForm.submit();
}

function search(value) {
    let lenght_name = $('#name').val().length;
    var name = $('#name').val();

    if (lenght_name >= 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/search/productPurchase',
            data: {
                name: name,
            },
            beforeSend: function() {
                $('#spinnerPr').addClass('spinner');
            },
            success: function(response) {
                $('#spinnerPr').removeClass('spinner');
                var data = JSON.parse(response);
                $('#resultado').html(data.table);
                if (data.scan > 0) {
                    $('#click').trigger("click");
                    $('#name').focus();
                }
            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else if (lenght_name <= 0) {
        $('#resultado').html("");
    }

}

function promotionProducts() {
    var ind = $("input[name='cont[]']").map(function () { 
        return this.value;
    });
    console.log("Largo inx:", ind.length, "Valores:", ind);
    if ($("#promocion").is(":checked")) {
        for (var i = 0; i < ind.length; i++) {
            var ix = ind[i];
            $("#price_buy-"+ix).val(0);
            $("#price_buy-"+ix).prop("readonly", true);
            sum(ix);
        }
    } else {
        for (var i = 0; i < ind.length; i++) {
            var ix = ind[i];
            var last_cost = $("#hidden_cost-"+ix).val();
            $("#price_buy-"+ix).prop("readonly", false);
            $("#price_buy-"+ix).val(last_cost);
            sum(ix);
        }
    }
}

function sumAll() {
    var ind = $("input[name='cont[]']").map(function () { 
        return this.value;
    });
    for (var i = 0; i < ind.length; i++) {
        var ix = ind[i];
        sum(ix);
    }
}

function sum(i) {

    let cantidad = $('#amount-' + i).val();
    let precio = Number($('#price_buy-' + i).val());
    let descuento = $('#discount-' + i).val();
    if (descuento == '' || descuento == null || !$.isNumeric(descuento)) {
        descuento = 0;
    }
    let subtotal = parseInt(cantidad) * parseFloat(precio);
    let total = subtotal - ((parseFloat(descuento) / 100) * (subtotal));
    var total_format = custom_number_format(total, '2', );
    $('#sub-' + i).html(moneda + total_format);
    $('#subt-' + i).val(total.toFixed(2));

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    var suma_format = custom_number_format(suma, '2', );
    $('#total').html(moneda + suma_format);
    $('#ttl').val(suma.toFixed(2));
    if ($('.total').length != 0) {
        jQuery('#success').removeAttr('hidden');
    }
    if ($('.total').length == 0) {
        jQuery('#success').attr('hidden', true);
    }
}

function removeOption(i) {
    console.log('toco'+i);
    $('#producto-' + i).remove();
    
    sum();
    if ($('.total').length == 0)
        jQuery('#success').prop('disabled', true);
}

function addOption(product_id) {
    var promo = 1;
    console.log('estoy en addoption'+product_id);
    var productoss = $('#productoss-' + product_id).val();
    if ($("#promocion").is(":checked")) promo = 0;
    if (productoss == product_id) {
        var aumentar = parseFloat($('.aumentar-' + product_id).val());
        $('.aumentar-' + product_id).val(aumentar + 1);
        $('.aumentar-' + product_id).focus();
    } else {
        if (!addProduct) {
            addProduct = true;
            var id = Math.floor(Math.random() * 300) + 10;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productos_compras/' + product_id + '/' + id + '/' + promo,
                beforeSend: function() {
                    addProduct = true;
                },
                success: function(response) {
                    addProduct = false;
                    jQuery('#products').append(response);
                    jQuery('#success').prop('disabled', false);
                    sum(product_id);

                },
                error: function(e) {
                    addProduct = false;
                    console.log("ERROR : ", e);
                }
            });
        } else {
            console.log("Espera a que se agregue el producto");
        }
    }
}

$('#compraForm').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

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

function verificar(bank_id) {
    $total = $('#ttl').val();

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/saldo_cuenta/',
        data: {
            bank_id: bank_id,
            total: $total,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_error').html("");
                $('#success').removeAttr("hidden");
            } else if (response == 2) {
                $('#msg_error').html('El pago se realizar��, pero la cuenta quedara en cero');
                $('#success').prop("hidden");
            } else if (response == 3) {
                $('#msg_error').html('La cuenta no tiene los fondos suficientes');
                $('#success').attr("hidden", true);
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
