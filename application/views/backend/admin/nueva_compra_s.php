<?php $data = $this->db->get_where('request', array('code'=>$code)); $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card ">
                <div class="card-body">
                    <h3 class="card-label text-info">Productos a registrar</h3>
                    <div class="table-responsive">
                        <table class="table table-padded">
                            <thead>
                                <tr>
                                    <th class='text-left'>Disponible</th>
                                    <th class='text-left'>Producto</th>
                                    <th class='text-center'>Cantidad Solicitada</th>
                                    <th class='text-center'>Cantidad Ofrecida</th>
                                    <th class='text-center'>Precio por unidad</th>
                                    <th class='text-center'>Fecha de Vencimiento</th>
                                    <th class='text-right'>Subtotal</th>
                                </tr>
                            </thead>
                            <form class="form" action="<?php echo base_url().'admin/compras/complete/'.$code;?>" method="POST"
                            enctype="multipart/form-data">
                            <tbody id='products'>
                                <?php for ($i=0; $i < $data->row()->num_products ; $i++): 
                                        if ($data->row()->products != "" || $data->row()->products != null) {
                                            $pro = json_decode($data->row()->products,true);
                                        } else {
                                            $pro = array();
                                        }
                                    ?>
                                <tr>
                                    <td>
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox" id="available-<?php echo $i;?>" name="available[]" value='1' <?php if($pro[$i]['available'] == 1) echo "checked";?> disabled><span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td><?php echo $this->db->get_where('products', array('products_id'=>$pro[$i]['product']))->row()->name ;?>
                                        <input type="hidden" name="name[]" value='<?php echo $pro[$i]['product'];?>'>
                                    </td>
                                    <td class='text-center'>
                                        <?php echo $pro[$i]['amount_request'];?>
                                        <input type="hidden" name="amount_request[]"
                                            value='<?php echo $pro[$i]['amount_request'];?>'>
                                    </td>
                                    <td class='text-center'>
                                        <?php echo $pro[$i]['amount_give'];?>
                                        <input type="hidden" class="form-control" step="any" name='amount_give[]'
                                            id='amount-<?php echo $i;?>' onblur="sum(<?php echo $i;?>)"
                                            value="<?php echo $pro[$i]['amount_give'];?>" min='0'>
                                    </td>
                                    <td class='text-center'>
                                        <?php echo $moneda; echo ($pro[$i]['price_buy'] != '') ? number_format($pro[$i]['price_buy'],2,'.',',') : '0.00';?>
                                        <input type="hidden" class="form-control" step="any" name='price_buy[]'
                                            id='price_buy-<?php echo $i;?>' onblur="sum('<?php echo $i ;?>')"
                                            value="<?php echo $pro[$i]['price_buy'];?>" min='0'>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name='expiration[]'
                                            id='expiration-<?php echo $i;?>'
                                            value="<?php echo date('Y-m-d')?>" >
                                    </td>
                                    <td class='text-right'><span class="text-success"
                                            id='sub-<?php echo $i;?>'><b><?php echo $moneda; ?>0.00</b></span>
                                        <input type="hidden" class='total' name="sub[]" id='subt-<?php echo $i;?>'>
                                    </td>
                                </tr>
                                <?php endfor;?>
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

$(document).ready(function() {
    <?php for ($i=0; $i <$data->row()->num_products ; $i++):?>
    sum(<?php echo $i;?>)
    <?php endfor;?>
});

function sum(i) {

    let cantidad = $('#amount-' + i).val();
    let precio = $('#price_buy-' + i).val();
    if (precio == "" || precio == NaN) {
        precio = 0;
    }
    let total = parseInt(cantidad) * parseFloat(precio);
    var total_format = custom_number_format(total, '2',);
    $('#sub-' + i).html(moneda + total_format);
    $('#subt-' + i).val(total.toFixed(2));

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    var suma_format = custom_number_format(suma, '2',);
    $('#total').html(moneda + suma_format);
    $('#ttl').val(suma.toFixed(2));
    if (suma == 0) {
        $('#confirmar_solicitud').hide();
    }
}

function custom_number_format( number_input, decimals, dec_point, thousands_sep ) {
    var number       = ( number_input + '' ).replace( /[^0-9+\-Ee.]/g, '' );
    var finite_number   = !isFinite( +number ) ? 0 : +number;
    var finite_decimals = !isFinite( +decimals ) ? 0 : Math.abs( decimals );
    var seperater     = ( typeof thousands_sep === 'undefined' ) ? ',' : thousands_sep;
    var decimal_pont   = ( typeof dec_point === 'undefined' ) ? '.' : dec_point;
    var number_output   = '';
    var toFixedFix = function ( n, prec ) {
        if( ( '' + n ).indexOf( 'e' ) === -1 ) {
            return +( Math.round( n + 'e+' + prec ) + 'e-' + prec );
        } else {
            var arr = ( '' + n ).split( 'e' );
            let sig = '';
            if ( +arr[1] + prec > 0 ) {
                sig = '+';
            }
            return ( +(Math.round( +arr[0] + 'e' + sig + ( +arr[1] + prec ) ) + 'e-' + prec ) ).toFixed( prec );
        }
    }
    number_output = ( finite_decimals ? toFixedFix( finite_number, finite_decimals ).toString() : '' + Math.round( finite_number ) ).split( '.' );
    if( number_output[0].length > 3 ) {
        number_output[0] = number_output[0].replace( /\B(?=(?:\d{3})+(?!\d))/g, seperater );
    }
    if( ( number_output[1] || '' ).length < finite_decimals ) {
        number_output[1] = number_output[1] || '';
        number_output[1] += new Array( finite_decimals - number_output[1].length + 1 ).join( '0' );
    }
    return number_output.join(decimal_pont );
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
                $('#confirmar_solicitud').removeAttr("disabled");
            }
            else if(response == 2) {
                $('#msg_error').html('El pago se realizará, pero la cuenta quedara en cero');
                $('#confirmar_solicitud').removeAttr("disabled");
            }
            else if(response == 3) {
                $('#msg_error').html('La cuenta no tiene los fondos suficientes');
                $('#confirmar_solicitud').attr("disabled", "true");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>