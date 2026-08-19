<?php $moneda = $this->crud_model->get_info("moneda");?>
<style>
.resultado td:hover {
    background: #8950fc2b;
}
</style>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url();?>admin/compras/create" method="POST"
        enctype="multipart/form-data" id='solicitudForm' name="solicitudForm">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value='<?php echo $code;?>'>
                                <input type="hidden" name="type" value='3'>
                                <span>Código de solicitud: <b>
                                        <? echo $code ?>
                                    </b> </span>
                                <span style='float:right;'><b>Fecha de
                                        solicitud:  </b><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( date('Y-m-d') ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                    <?php echo date('H:i:s');?> </span>
                                <br>
                                <div class="row ">
                                    <div class="col-sm-4 mt-3">
                                        <div class="form-group">
                                            <label><b>Proveedor:</b></label>
                                            <select class="form-control" id='proveedor' required
                                                onchange="search_productos(this.value)">
                                                <option value="">Seleccionar</option>
                                                <?php $prov = $this->crud_model->get_provider(); foreach($prov->result_array() as $pr): ?>
                                                <option value='<?php echo  $pr['provider_id'] ;?>'>
                                                    <?php echo $pr['name'] ;?>
                                                </option>
                                                <?php endforeach;?>
                                            </select>
                                            <input type="hidden" name='provider' id='provider'>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 mt-3">
                                        <div class="form-group">
                                            <label>Productos</label><br>
                                            <select name="productos[]" id="productos"
                                                class='js-example-basic-single-0 form-control'
                                                onchange="search(this.value)"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-padded">
                                        <tbody id="resultado" class="mostly-customized-scrollbars col-sm-12 resultado"
                                            style="background-color: #fcfcfc; margin-bottom: 0px !important;">
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
                        <h3 class="card-label text-info">Productos a solicitar</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th class='text-left'>Producto</th>
                                        <th class='text-left'>Cantidad</th>
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

function submitForm(){
    document.solicitudForm.submit();
}

$(document).ready(function() {
    $('#proveedor').select2({
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

function search_productos(value) {
    var provider = value;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_productos',
        data: {
            provider: provider,
        },
        success: function(response) {
            $('#productos').html(response);
            $('#provider').val(provider);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function search(value) {
    var name = value;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/product_solicitud',
        data: {
            name: name,
        },
        success: function(response) {
            $('#resultado').html(response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function sum(i) {

    let cantidad = $('#amount-' + i).val();
    var count_elements = $('#amount-' + i).length;
    if (count_elements != '0')
        $('#confirm').removeAttr("hidden");
    if (count_elements == '0')
        $('#confirm').attr("hidden",'true');

}


function removeOption(i) {
    $('#producto-' + i).remove();
    sum();
    $('#proveedor').removeAttr("disabled");

}

function addOption($product_id) {
    var productoss = $('#productoss-' + $product_id).val();
    if (productoss == $product_id) {
        var aumentar = parseFloat($('.aumentar-' + $product_id).val());
        $('.aumentar-' + $product_id).val(aumentar + 1);
        $('.aumentar-' + $product_id).focus();
    } else {
        if(!addProduct) {
            addProduct = true;
            var id = Math.floor(Math.random() * 300) + 10;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productos_compras_s/' + $product_id + '/' + id,
                beforeSend: function() {
                    addProduct = true;
                },
                success: function(response) {
                    addProduct = false;
                    jQuery('#products').append(response);
                    sum(id);
                    $('#confirm').removeAttr("disabled");
                    $('#proveedor').attr('disabled', 'true');
                },
                error: function(e) {
                    addProduct = false;
                    console.log("ERROR : ", e);
                }
            });
        } else {

        }
    }
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