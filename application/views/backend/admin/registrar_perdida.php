<?php $moneda = $this->crud_model->get_info("moneda");?>
<style>
.resultado td:hover {
    background: #8950fc2b;
}

</style>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url();?>admin/record_loss/" method="POST" enctype="multipart/form-data" id='perdidaForm' name='perdidaForm'>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    Para comenzar, ingresa el nombre del producto en el buscador ubicado en la parte inferior y luego
                    presiona la tecla
                    <b>ENTER</b> para iniciar la búsqueda.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12"> <br>
                                  <select name="branch"  class='form-control' >
                                      <option value="0">Bodega</option>
                                      <option value="1">Tienda</option>
                                  </select> 
                                <br>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <span>Código de pérdida: <b><?php echo $code;?></b></span>
                                <input type="hidden" name="code" value='<?php echo $code;?>'>
                                <input type="hidden" name="type" value='1'>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12"> <br>
                                        <div class="spinner-success spinner-left" id='spinnerPr'>
                                            <input autocomplete="off" type="text" class='form-control' id='name' onchange="search()"  placeholder='Ingrese el nombre o código' autofocus>
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
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th class='text-left'>Producto</th>
                                        <th class='text-right'>Cantidad disponible</th>
                                        <th class='text-right'>Costo de compra</th>
                                        <th class='text-right'>Cantidad de pérdida</th>
                                        <th class='text-right'>Pérdida</th>
                                        <th class='text-right'>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                                    <div class="position-absolute opacity-30 top-0 right-0">
                                        <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
                                                <g clip-path="url(#clip0)">
                                                    <path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
                                                    <path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
                                                    <path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
                                                    <path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
                                                    <path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
                                                    <path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
                                                    <path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                    <input type='hidden' id='descuentos' value='0'>
                                    <div class="font-weight-boldest font-size-h5">TOTAL</div>
                                    <div class="text-right d-flex flex-column">
    
                                        <span class="font-weight-boldest font-size-h3 line-height-sm" id='total'><?php echo $moneda.number_format(00,2,'.',',');?></span>
                                        <input type="hidden" name="ttl" id='ttl'>
    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                             <div class="col-sm-12"> <br>
                                <div class="spinner-success spinner-left" id='spinnerPr'>
                                    <textarea autocomplete="off" name="concept" class='form-control' id='concept' placeholder='Ingrese el concepto de la pérdida' required></textarea>
                                </div>
                                <br>
                            </div>   
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-rounded btn-success" id="submit2">Registrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var addProduct = false;

$(document).ready(function() {
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
    document.perdidaForm.submit();
}

function search(value) {
    let lenght_name = $('#name').val().length;
    var name = $('#name').val();

    if (lenght_name >= 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/search/productSale',
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
    let precio = Number($('#cost-' + i).val());
    let total = parseInt(cantidad) * parseFloat(precio);
    var total_format = custom_number_format(total, '2', );
    $('#sub-' + i).html(moneda + total_format);
    $('#subt-' + i).val(total.toFixed(2));

    $('#mensaje-' + i).queue(function(n) 
    {
        $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>admin/compare_stock',
              data: "c="+cantidad+'|'+i,
              dataType: "html",
              error: function(){
                    //alert("¡Error!");
              },
              success: function(data)
              { 
                if (data == "success")
                {            
                    $('#mensaje-' + i).hide(500);
                    $('#submit2').removeAttr('disabled');
                    //console.log(data);
                }
                else {
                    
                    //console.log(data);
                    $('#mensaje-' + i).show(500);
                    texto = '<td><small class="text-danger" id="ms-descuento">Error:  Cantidad no disponible en stock</small></td>';
                    $('#mensaje-' + i).html(texto);
                    $('#submit2').attr('disabled','true');
                }
                n();
              }
          });                           
     });
    
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
    console.log('estoy en addoption'+product_id);
    var productoss = $('#productoss-' + product_id).val();
    if (productoss == product_id) {
        var aumentar = parseFloat($('.aumentar-' + product_id).val());
        $('.aumentar-' + product_id).val(aumentar + 1);
        $('.aumentar-' + product_id).focus();
        sum(product_id);
    } else {
        if(!addProduct) {
            addProduct = true;
            var id = Math.floor(Math.random() * 300) + 10;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productos_perdida/' + product_id + '/' + id + '/',
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

$('#perdidaForm').on('keyup keypress', function(e) {
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
</script>
