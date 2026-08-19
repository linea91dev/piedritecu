<?php $moneda = $this->crud_model->get_info("moneda"); $data= $this->db->get_where('quotes', array('quotes_id'=> $ID))->result_array(); foreach ($data as $row):
    $clien = $this->db->get_where("client", array('client_id'=>$row['client_id']))->row_array();
    $type = $clien['type'];
    log_message("error", "Client type: ".$type);
    if($type == '1' || $type == '3') log_message("error", "No minorista");
    if($type != '1') log_message("error", "No mayorista");
    if($type != '3') log_message("error", "No farmacia");?>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url().'admin/cotizaciones/update/'.$ID;?>" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value='<?php echo $row['code'];?>'>
                                <span>Código de cotización: <b><?php echo $row['code'];?></b> <span
                                        style="float:right"><b>Fecha de cotización:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date_start'] ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                    </span></span>
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Cliente:</b></label>
                                            <input type="text" class='form-control' readonly
                                                value='<?php if($row['client_id'] > 0) echo $this->crud_model->getName('client', $row['client_id']); else echo "Consumidor Final";?>'>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Válido hasta:</b></label>
                                            <input type="text" class="form-control" required name='date_end' readonly id="kt_datepicker"
                                                value='<?php echo date('m/d/Y', strtotime($row['date_end']));?>' />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><b>Responsable:</b></label>
                                            <input type="text" class='form-control' readonly
                                                value='<?php echo $this->crud_model->getName('admin', $row['responsable']);?>'>
                                        </div>
                                    </div>
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
                        <h3 class="card-label text-info text-center"> <a onclick="addOption()" href="javascript:;"><i
                                    class="text-dark-50 flaticon2-plus-1"></i></a> PRODUCTOS A COTIZAR</h3>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th class="client_mn" style="<?php if($clien['type'] == '1' || $clien['type'] == '3') echo "display:none;";?>">Precio unitario</th>
                                        <th class="client_my" style="<?php if($clien['type'] != '1') echo "display:none;";?>">Precio mayorista</th>
                                        <th class="client_farma" style="<?php if($clien['type'] != '3') echo "display:none;";?>">Precio farmacia</th>
                                        <th>Descuento</th>
                                        <th class="client_mn" style="<?php if($clien['type'] == '1' || $clien['type'] == '3') echo "display:none;";?>">Subtotal</th>
                                        <th class="client_my" style="<?php if($clien['type'] != '1') echo "display:none;";?>">Subtotal mayorista</th>
                                        <th class="client_farma" style="<?php if($clien['type'] != '3') echo "display:none;";?>">Precio farmacia</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                    <?php for ($i=0; $i < $row['num_products'] ; $i++):  
                                    if ($row['products'] != "" || $row['products'] != null) {
                                        $pro = json_decode($row['products'],true);
                                    } else {
                                        $pro = array();
                                    }?>
                                    <tr id='producto-<?php echo $i; ?>'>
                                        <td>
                                            <select class="js-example-basic-single-<?php echo $i;?> form-control product-select"
                                                onchange="selected(this.value, <?php echo $i;?>)" name="productos[]"
                                                required style="width: 90%">
                                                <?php if(isset($pro[$i]['product']) && $pro[$i]['product'] > 0): 
                                                    $selected_product = $this->db->get_where('products', array('products_id' => $pro[$i]['product']))->row();
                                                ?>
                                                <option value='<?php echo $pro[$i]['product']; ?>' selected><?php echo $selected_product->name; ?></option>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td><input min="1" max="999" class="form-control" type="number"
                                                style="width:70px" step="any" id="amount-<?php echo $i; ?>"
                                                name="amount[]" value="<?php echo $pro[$i]['amount']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        </td>
                                        <td class="client_mn" style="<?php if($clien['type'] == '2' || $clien['type'] == '3') echo "display:none;";?>"><input min="1" max="999" class="form-control" type="number"
                                                style="width:110px" step="any" id="price-<?php echo $i;?>"
                                                name="price[]" value="<?php echo $pro[$i]['price']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        <td class="client_my" style="<?php if($clien['type'] != '1') echo "display:none;";?>"><input min="1" max="999" class="form-control" type="number"
                                                style="width:110px" step="any" id="price_my-<?php echo $i;?>"
                                                name="price_my[]" value="<?php echo $pro[$i]['price_my']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        <td class="client_farma" style="<?php if($clien['type'] != '3') echo "display:none;";?>"><input min="1" max="999" class="form-control" type="number"
                                                style="width:110px" step="any" id="price_farma-<?php echo $i;?>"
                                                name="price_farma[]" value="<?php echo $pro[$i]['price_farma']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        </td>
                                        <td><input min="0" max="999" class="form-control discount" type="number"
                                                style="width:110px" step="any" id="discount-<?php echo $i;?>"
                                                name="discount[]" value="<?php echo $pro[$i]['discount']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        </td>
                                        <td  class="client_mn" style="<?php if($clien['type'] == '1' || $clien['type'] == '3') echo "display:none;";?>"><span class="text-success"
                                                id='sub-<?php echo $i;?>'><?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?></span>
                                            <input type="hidden" class='total' name="sub[]" id='subt-<?php echo $i;?>'>
                                            <input type="hidden" class='descuento' name="desc[]" id='desc-<?php echo $i;?>'>
                                        </td>
                                        <td class="client_my" style="<?php if($clien['type'] != '1') echo "display:none;";?>"><span class="text-success"
                                                id='sub_my-<?php echo $i;?>'><?php echo $moneda.number_format($pro[$i]['sub_my'],2,'.',',');?></span>
                                            <input type="hidden" class='total_my' name="sub_my[]" id='subt_my-<?php echo $i;?>'>
                                            <input type="hidden" class='descuento' name="desc[]" id='desc-<?php echo $i;?>'>
                                        </td>
                                        <td class="client_farma" style="<?php if($clien['type'] != '3') echo "display:none;";?>"><span class="text-success"
                                                id='sub_farma-<?php echo $i;?>'><?php echo $moneda.number_format($pro[$i]['sub_farma'],2,'.',',');?></span>
                                            <input type="hidden" class='total_farma' name="sub_farma[]" id='subt_farma-<?php echo $i;?>'>
                                            <input type="hidden" class='descuento' name="desc[]" id='desc-<?php echo $i;?>'>
                                        </td>
                                        <td>
                                            <a class="badge badge-danger" style="padding:3px;"
                                                onclick="removeOption('<?php echo $i;?>')" href="javascript:;">
                                                <span class="svg-icon svg-icon-white svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path
                                                                d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                                                fill="#000000" fill-rule="nonzero"></path>
                                                            <path
                                                                d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                fill="#000000" opacity="0.3"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endfor;?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div
                                class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                                <div class="position-absolute opacity-30 top-0 right-0">
                                    <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165"
                                            viewBox="0 0 176 165" fill="none">
                                            <g clip-path="url(#clip0)">
                                                <path
                                                    d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z"
                                                    fill="#AD84FF"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div class="font-weight-boldest font-size-h5">TOTAL COTIZADO</div>
                                <div class="text-right d-flex flex-column">
                                    <span class="font-weight-boldest font-size-h3 line-height-sm"
                                        id='total'><?php echo $moneda.number_format($row['total'],2,'.',',');?></span>
                                    <input type="hidden" name="ttl" id='ttl'>
                                    <input type="hidden" name="dsc" id='dsc'>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <hr>
                            <button class="btn btn-light-warning font-weight-bolder" type='submit'
                                style='float: right;'>
                                Actualizar cotización
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endforeach;?>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var mayorista = <?php if($clien['type'] != '1') echo "false"; else echo "true";?>;
var cl_farma = <?php if($clien['type'] != '3') echo "false"; else echo "true";?>;

$(document).ready(function() {
    <?php for ($i=0; $i < $row['num_products'] ; $i++):?>
    var i = '<?php echo $i;?>'
    $('.js-example-basic-single-' + i).select2({
        language: "es",
        placeholder: 'Buscar producto',
        allowClear: true,
        ajax: {
            url: '<?php echo base_url();?>admin/search_products/',
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
    sum(i)
    <?php endfor;?>
});

function selected(value, i) {

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_price/',
        data: {
            product_id: value,
        },
        success: function(response) {
            $('#price-' + i).val(response);
            sum(i)
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function sum(i, v) {
    var cantidad = $('#amount-' + i).val();
    var precio = $('#price-' + i).val();
    var precio_my = $('#price_my-' + i).val();
    var precio_farma = $('#price_farma-' + i).val();
    var descuento = $('#discount-' + i).val();
    var prPrice = $('#prPrice-' + i).val();

    if (v == 2) {
        if (parseFloat(precio) <= parseFloat(prPrice)) {
            var diferencia = parseFloat(prPrice) - parseFloat(precio);
            var newPorcentaje = (diferencia / parseFloat(prPrice)) * 100;
            $('#discount-' + i).val(newPorcentaje.toFixed(2));
            descuento = newPorcentaje.toFixed(2);
        }
    }

    if (v == 3) {
        if (parseFloat(descuento) >= 0) {
            var des = parseFloat(prPrice) - (parseFloat(descuento) * parseFloat(prPrice)) / 100;
            $('#price-' + i).val(des.toFixed(2));
            precio = des.toFixed(2)
        }
    }

    var mul = (parseFloat(cantidad) * parseFloat(precio));
    var des = mul * (descuento / 100);
    var total = mul;
    var precio_producto = $('#precioProducto-' + i).val();

    var pu = parseFloat(precio) - (parseFloat(precio) * (descuento / 100));
    var descuentos = $('#descuentos').val();


    var sumaDescuento = 0;
    $('.discount').each(function() {
        sumaDescuento += parseFloat($(this).val());
    });

    if (sumaDescuento > 0 && descuentos == 0) {
        $('#submit').attr('hidden', true);
        $('#codigoAuth').show(500);
    } else {
        $('#submit').removeAttr('hidden');
        $('#codigoAuth').hide(500);
    }

    if (total < precio_producto) {
        $('#mensaje-' + i).show(500);
        var COSTO = parseFloat(precio_producto);
        var PRECIO = parseFloat(prPrice);

        var TOTAL = ((PRECIO - COSTO) / COSTO) * 100;

        var ms =
            `<td><small class="text-danger" id="ms-descuento"> El costo del producto es  <b>${moneda}${precio_producto}</b> y el descuento es <b>${descuento}%</b> el cual te dará una ganancia negativa </small></td>`;
        $('#mensaje-' + i).html(ms);
    } else {
        $('#mensaje-' + i).html('');
        $('#mensaje-' + i).hide(500);
    }


    $('#sub-' + i).html(moneda + total.toFixed(2));
    $('#subt-' + i).val(total.toFixed(2));

    var mul_my = (parseFloat(cantidad) * parseFloat(precio_my));
    var des_my = mul_my * (descuento / 100);
    var total_my = mul_my - des_my;

    $('#sub_my-' + i).html(moneda + ' ' + total_my.toFixed(2));
    $('#subt_my-' + i).val(total_my.toFixed(2));

    var mul_farma = (parseFloat(cantidad) * parseFloat(precio_farma));
    var des_farma = mul_farma * (descuento / 100);
    var total_farma = mul_farma - des_farma;

    $('#sub_farma-' + i).html(moneda + ' ' + total_farma.toFixed(2));
    $('#subt_farma-' + i).val(total_farma.toFixed(2));

    if (!mayorista && cl_farma==false) {
        console.log("Entra a minorista");
        var suma = 0;
        $('.total').each(function() {
            suma += parseFloat($(this).val());
        });


        $('#total').html(moneda + suma.toFixed(2));
        $('#ttl').val(suma.toFixed(2));

    } else if(cl_farma) {
        console.log("Entra a farma");
        var suma = 0;
        $('.total_farma').each(function() {
            suma += parseFloat($(this).val());
        });

        $('#total').html(moneda + suma.toFixed(2));
        $('#ttl').val(suma.toFixed(2));

    } else {
        console.log("Entra a mayorista");
        var suma = 0;
        $('.total_my').each(function() {
            suma += parseFloat($(this).val());
        });

        $('#total').html(moneda + suma.toFixed(2));
        $('#ttl').val(suma.toFixed(2));
    }

    var sum_desc = 0;
    $('.discount').each(function() {
        sum_desc += parseFloat($(this).val());
    });
    $('#dsc').val(sum_desc.toFixed(2));
}

function removeOption(i) {
    $('#producto-' + i).remove();
    sum()
}

function addOption() {
    id = Math.floor(Math.random() * 300) + 10;
    var html = `
    <tr id='producto-${id}'>
        <td>
            <select class="js-example-basic-single-${id} form-control product-select" name="productos[]" required onchange="selected(this.value, ${id})"
                style="width: 90%">
            </select>
        </td>
        <td><input min="1" max="999" class="form-control" type="number" style="width:70px"
                step="any" id="amount-${id}" name="amount[]" value="1" onblur="sum(${id})"></td>
        <td><input min="1" max="999" class="form-control" type="number" style="width:110px"
                step="any" id="price-${id}" name="price[]" value="1" onblur="sum(${id})"></td>
        <td><input min="0" max="999" class="form-control" type="number"
                style="width:110px" step="any" id="discount-${id}"
                name="discount[]" value="0"
                onblur="sum(${id})">
        </td>
        <td><span class="text-success" id='sub-${id}'>${moneda}1.00</span> <input type="hidden"
                class='total' name="sub[]" id='subt-${id}'></td>
        <td>
            <a class="badge badge-danger" style="padding:3px;" onclick="removeOption('${id}')"
                href="javascript:;">
                <span class="svg-icon svg-icon-white svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path
                                d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                fill="#000000" fill-rule="nonzero"></path>
                            <path
                                d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                </span>
            </a>
        </td>
    </tr>`;
    $('#products').append(html);
    $('.js-example-basic-single-' + id).select2({
        language: "es",
        placeholder: 'Buscar producto',
        allowClear: true,
        ajax: {
            url: '<?php echo base_url();?>admin/search_products/',
            type: 'POST',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
    sum(id);
}
</script>