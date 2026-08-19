<?php
    $moneda = $this->crud_model->get_info("moneda");
	$sale = $this->db->get_where('sales',array('code'=>$code))->row();
	$sale_id = $sale->sales_id;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <br>
                                    <form method="post" action="<?php echo base_url(); ?>admin/nuevo_cambio/">
                                        <label>Ingrese el código de orden:</label>
                                        <?php if($sale_id == "" && $code != ""):?>
                                        <div class="alert alert-danger" role="alert">
                                            El código ingresado no es válido o no existe.
                                        </div>
                                        <?php endif;?>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" placeholder=""
                                                aria-describedby="basic-addon2" value="<?php echo $sale->code;?>"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="padding: 5px;">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <path
                                                                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                <path
                                                                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" style='float: right;'
                                                class="btn btn-primary font-weight-bold">Buscar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php if($sale_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span><b>Forma de pago:</b></span>
                            <div class="border-bottom"></div><br>
                        </div>
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Método:</label>
                                <select class="form-control" disabled>
                                    <option <?php echo $sale->sales_id == "Efectivo" ? "selected" :""; ?>>Efectivo
                                    </option>
                                    <option <?php echo $sale->sales_id == "Tarjeta" ? "selected" :""; ?>>Tarjeta de
                                        crédito/débito</option>
                                    <option <?php echo $sale->sales_id == "Transferencia" ? "selected" :""; ?>>
                                        Transferencia</option>
                                    <option <?php echo $sale->sales_id == "Cheque" ? "selected" :""; ?>>Cheque</option>
                                    <option <?php echo $sale->sales_id == "Deposito" ? "selected" :""; ?>>Depósito
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" value="<?php echo $moneda.$sale->total?>" disabled
                                    class="form-control" />
                                <input type="hidden" value="<?php echo $sale->total?>" disabled id="prevTotal"
                                    class="form-control" />
                            </div>
                        </div>
                        <!--
                            <div class="col-lg-6 col-xxl-6">
                                <div class="form-group">
                                    <label>Pago con:</label>
                                    <input type="number" value="Q. <?php echo $sale->total?>" disabled step="0.01" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-xxl-6">
                                <div class="form-group">
                                    <label>Cambio:</label>
                                    <input type="text" disabled value="Q. <?php echo $sale->total?>" class="form-control" />
                                </div>
                            </div>
                            -->
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Motivo del cambio</label>
                                <textarea class="form-control" required="" name="reason"
                                    oninput="reason(this.value)"></textarea>
                            </div>
                            <span>* El total del cambio no debe ser menor al total original.</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-sm-8">
            <?php if($sale_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <h5>Resumen de la orden:</h5>
                    <div class="border-bottom"></div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Vendedor:</b></label><br>
                                <?php echo $this->crud_model->getName('admin',$sale->responsable) ;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Cliente:</b></label>
                                <br>
                                <?php echo $sale->client_id != 0 ? $this->crud_model->getName('client',$sale->client_id): $sale->name; ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>NIT:</b></label>
                                <br><?php echo $sale->nit;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Celular:</b></label>
                                <br><?php echo $sale->phone;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Dirección de facturación:</b></label><br>
                                <?php echo $sale->address;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Dirección de entrega:</b></label><br>
                                <?php echo $sale->delivery;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Detalles:</b></label><br>
                                <?php echo $sale->details;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Estado:</b></label><br>
                                <span
                                    class="label label-lg font-weight-bold label-light-success label-inline">Completada</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Fecha:</b></label><br>
                                <span><?php echo $sale->date;?> | <?php echo $sale->time;?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <form method="post" action="<?php echo base_url(); ?>admin/cambios/create">
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-left">Cantidad</th>
                                        <th class="text-left">P/U</th>
                                        <th class="text-left">Descuento</th>
                                        <th class="text-left">Subtotal</th>
                                        <th class='text-right'>-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <input type="hidden" name="sale_code" value="<?php echo $code?>">
                                    <input type="hidden" name="reason" id="reason">
                                    <?php 
                                        $products = json_decode($sale->products,true); 
                                        foreach($products as $product):
                                        $pr = $this->db->get_where('products',array('products_id'=>$product['product']))->row();
                                    ?>
                                    <tr id='producto-<?php echo $product['product']; ?>'
                                        data-id="<?php echo $product['product']; ?>">
                                        <td>
                                            <?php echo $this->db->get_where('products',array('products_id'=>$product['product']))->row()->name;?>
                                            <input type="hidden" name="product[]"
                                                value='<?php echo $product['product'];?>'>
                                            <input type="hidden" name="change[]" value='1'
                                                id="changeProducto-<?php echo $product['product']; ?>">
                                        </td>
                                        <td class='text-center'>
                                            <input min="1" max="999" class="form-control" type="number"
                                                style="width:70px" step="any"
                                                id="amount-<?php echo $product['product']; ?>" name="amount[]"
                                                value="<?php echo $product['amount']?>"
                                                onblur="sum('<?php echo $product['product'];?>')">
                                        </td>
                                        <td>
                                            <?php echo $moneda.number_format($product['price'],2,'.',',');?>
                                            <input min="1" max="999" class="form-control" type="hidden"
                                                style="width:110px" step="any"
                                                id="price-<?php echo $product['product'];?>" name="price[]"
                                                value="<?php echo $product['price']?>">
                                        </td>
                                        <td>
                                            <input min="0" max="100" class="form-control" type="number"
                                                style="width:70px" step="any"
                                                id="discount-<?php echo $product['product'];?>" name="discount[]"
                                                value="<?php echo $product['discount']?>"
                                                onblur="sum('<?php echo $product['product'];?>')">
                                        </td>
                                        <td><span class="text-success"
                                                id='sub-<?php echo $product['product'];?>'><?php echo $moneda.number_format($product['sub'],2,'.',',');?></span>
                                            <input type="hidden" class='total' name="sub[]"
                                                id='subt-<?php echo $product['product'];?>'
                                                value="<?php echo $product['sub']?>">
                                        </td>
                                        <td class='text-right'>
                                            <a id="btnadd-<?php echo $product['product']; ?>" class="badge badge-info"
                                                style="padding:3px;"
                                                onclick="addrow('<?php echo $product['product'];?>')"
                                                href="javascript:;">
                                                <span class="svg-icon svg-icon-white svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                            <rect fill="#000000" opacity="0.3"
                                                                transform="translate(13.000000, 6.000000) rotate(-450.000000) translate(-13.000000, -6.000000) "
                                                                x="12" y="8.8817842e-16" width="2" height="12" rx="1">
                                                            </rect>
                                                            <path
                                                                d="M9.79289322,3.79289322 C10.1834175,3.40236893 10.8165825,3.40236893 11.2071068,3.79289322 C11.5976311,4.18341751 11.5976311,4.81658249 11.2071068,5.20710678 L8.20710678,8.20710678 C7.81658249,8.59763107 7.18341751,8.59763107 6.79289322,8.20710678 L3.79289322,5.20710678 C3.40236893,4.81658249 3.40236893,4.18341751 3.79289322,3.79289322 C4.18341751,3.40236893 4.81658249,3.40236893 5.20710678,3.79289322 L7.5,6.08578644 L9.79289322,3.79289322 Z"
                                                                fill="#000000" fill-rule="nonzero"
                                                                transform="translate(7.500000, 6.000000) rotate(-270.000000) translate(-7.500000, -6.000000) ">
                                                            </path>
                                                            <rect fill="#000000" opacity="0.3"
                                                                transform="translate(11.000000, 18.000000) scale(1, -1) rotate(90.000000) translate(-11.000000, -18.000000) "
                                                                x="10" y="12" width="2" height="12" rx="1"></rect>
                                                            <path
                                                                d="M18.7928932,15.7928932 C19.1834175,15.4023689 19.8165825,15.4023689 20.2071068,15.7928932 C20.5976311,16.1834175 20.5976311,16.8165825 20.2071068,17.2071068 L17.2071068,20.2071068 C16.8165825,20.5976311 16.1834175,20.5976311 15.7928932,20.2071068 L12.7928932,17.2071068 C12.4023689,16.8165825 12.4023689,16.1834175 12.7928932,15.7928932 C13.1834175,15.4023689 13.8165825,15.4023689 14.2071068,15.7928932 L16.5,18.0857864 L18.7928932,15.7928932 Z"
                                                                fill="#000000" fill-rule="nonzero"
                                                                transform="translate(16.500000, 18.000000) scale(1, -1) rotate(270.000000) translate(-16.500000, -18.000000) ">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <span id="total"></span>
                        <hr>
                        <input type="hidden" id='codigo' value='0'>
                        <?php if($sale->estado != 4 && $sale->estado != 3):?>
                        <div class="form-group" id='codigoAuth'>
                                <label> Código de autorización para aplicar cambios: <span
                                        class="text-danger">*</span></label>
                                <div class=" spinner-success spinner-left" id='spinnerCode'>
                                    <input type="password" autocomplete="off" class='form-control' id='code'
                                        placeholder='Ingresa el código de autorización' autofocus
                                        onblur="getCodigo(this.value)">
                                </div>
                                <div id='mensajeError'>
                                </div>
                        </div>
                        <button type="submit" class="btn btn-light-success font-weight-bolder float-right cambio">
                            Confirmar cambio
                        </button>
                        <?php else:?>
                        <span class="text-danger">Esta venta ya fue cambiada.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            <?php endif;?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.cambio').attr('disabled',true);
})

function getCodigo(code) {
    var leng_code = code.length;
    var valor = 'cambios';
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
                    $('.cambio').removeAttr('disabled');
                    $('#codigo').val('1');

                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    $('.cambio').attr('disabled',true);
                    $('#codigo').val('0');
                    
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
var moneda = '<?php echo $moneda; ?>';

function reason(value) {
    document.getElementById('reason').value = value;
}

$(function() {
    sum()
});

function addrow(row) {
    var id = Math.floor(Math.random() * 300) + 10;
    var newrow = $(`<tr id="producto-${id}" data-id="${id}">
            <td><select class="js-example-basic-single-0 form-control" name="productos[]"  onchange="new_change(this.value,'${id}')">
					<option value="">Seleccionar</option>
						<?php $products = $this->db->get_where('products', array('status'=>1)); 
					foreach ( $products->result_array() as $product):
						$stock = $this->crud_model->get_stock($product['products_id'], $this->session->userdata('branch_id'));
						if($stock > 0 ):?>
						<option value='<?php echo $product['products_id'];?>'><?php echo $product['name'].'('.$stock.')';?></option>
						<?php endif; 
					endforeach;
					?>
				</select>
			</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
        </tr>`);

    $('#producto-' + row).css("text-decoration", "line-through");
    $('#producto-' + row).after(newrow);
    $('#changeProducto-' + row).val(0);
    $('#subt-' + row).val(0);

}

function new_change($product_id, $row_id) {

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_productos_change/' + $product_id + '/' + $row_id,
        success: function(response) {
            $('#producto-' + $row_id).html(response);
            sum()
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function removeOption(i) {
    id = $('#producto-' + i).prev().data('id');

    $('#producto-' + i).prev().css("text-decoration", "");
    $('#producto-' + i).remove();
    sum()
}

function subtotal(i) {
    let cantidad = $('#amount-' + i).val();
    let precio = $('#price-' + i).val();
    let descuento = $('#discount-' + i).val();

    let mul = (parseFloat(cantidad) * parseFloat(precio));
    let des = mul * (descuento / 100);
    let total = mul - des;
    var total_format = custom_number_format(total, '2', );
    $('#sub-' + i).html(moneda + total_format);
    $('#subt-' + i).val(total.toFixed(2));

    sum()
}

function sum(i) {

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });

    if ($('#prevTotal').val() <= suma) {

        tl = suma - $('#prevTotal').val();
        var tl_format = custom_number_format(tl, '2', );
        $('#total').html('<b class="text-info">El Cliente debe pagar una diferencia de: ' + moneda + tl_format +
            '</b>');

    } else {
        $('#total').html('<b class="text-info"> El cambio debe ser por un producto de igual o mayor valor</b>');
        //$('button[type=submit]').prop('disabled', true);

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