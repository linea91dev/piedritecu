<?php $moneda = $this->crud_model->get_info("moneda"); ?>
<div class="container-fluid">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-toolbar">
                    <div class="alert alert-blue">
                        <span class="d-block pt-2 font-size-sm">Busca el producto a trasladar, cuando se despliegue la lista de los productos, selecciona uno o varios para trasladar, y escoje la sucursal de destino (No puedes trasladar productos hacia la sucursal en la que estas actualmente).
                        </span>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" method="post" id="form-traslado" action="<?php echo base_url(); ?>admin/traslados/create">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3"> <br>
                        <div class="form-group">
                           <span><b>Recibe</b></span>
                            <input type="text" autocomplete="off" class='form-control' placeholder='Ejemplo: Pedro, Juan' onchange="" name="recibe"
                                autofocus>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-3"> <br>
                        <div class="form-group">
                            <span><b>Desde</b></span>
                            <select class="form-control" name="" id="desde" required  onchange="sucursales()">
                                <option value="">Seleccionar</option>';
                               <?php 
                                echo '<option value="0">Bodega</option>';
                                $products = $this->db->get_where('branch', array('status'=>1));
                                foreach ($products->result_array() as $product):
                                echo '<option value="'.$product['branch_id'].'" >'.$product['name'].'</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="branch_envia" id="branch_envia" value="" />
                        <br>
                    </div>
                    <div class="col-sm-3"> <br>
                        <div class="form-group">
                            <span><b>Hacia</b></span>
                            <select class="form-control" name="branch_recibe" required id="hacia"  onchange="sucursales()">
                                <option value="">Seleccionar</option>';
                               <?php 
                                echo '<option value="0">Bodega</option>';
                                
                                $products = $this->db->get_where('branch', array('status'=>1));
                                foreach ($products->result_array() as $product):
                                echo '<option value="'.$product['branch_id'].'" >'.$product['name'].'</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-xxl-4">
                        <span><b>Productos</b></span>
                        <br>
                        <div class="row">
                            <div class="col-sm-12"> <br>
                                <div class="input-group">
                                    <input type="text" autocomplete="off" class='form-control' id='name_pr'
                                        placeholder='Ingrese el nombre o código' onchange="search(this.value)"
                                        autofocus>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class=" table-responsive">
                            <table class="table table-padded">
                                <tbody id="resultado" class="mostly-customized-scrollbars col-sm-12"
                                    style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xxl-8">
                        
                            <h3 class="card-label text-info">Productos a trasladar</h3>
                            <div class="table-responsive">
                                <table class="table table-padded" id="traslados">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>-</th>
                                        </tr>
                                    </thead>
                                    <tbody id='products'>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
								<label for="exampleTextarea"><b> Motivo del traslado </b> <span class="text-danger">*</span></label>
								<textarea class="form-control" rows="3" name='motivo' required="true"></textarea>
							</div>
                            <hr>
                            <div class="form-group">
                                <button type="submit" style="float: right;" id="submit_traslado"
                                    class="btn btn-primary font-weight-bold" onclick="">Guardar</button>
                            </div>
                        
                    </div>
                </div>
            </div>
            </form>
        </div>
        <br>
    </div>
</div>

<script type="text/javascript">
/*
var row_count = $('#traslados tr').length;
$('#form-traslado').submit(function (e){
    if(row_count>=1){
    e.preventDefault();    
    return;
    }
});*/

var moneda = '<?php echo $moneda; ?>';
var addProduct = false;

function cambio() {
    let pago = $('#pago').val();
    let cancelar = $('#ttl').val();
    let total = pago - cancelar;
    if (pago > 0) {
        $('#changee').val(total.toFixed(2));
        $('#change').html(moneda + total.toFixed(2));
    } else {
        $('#changee').val('0');
        $('#change').html(moneda + '0.0');
    }
    if (total < 0) {
        $('#changee').val('0');
        $('#change').html(moneda + '0.0');
    }
}

function desactivar(){
    
        $('#submit_traslado').attr('disabled', 'disabled');
}


function search(value) {
    var desde = $("#desde").val();
    let name = value;
    if (desde != '') {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/search/productTras',
            data: {
                name: name,
                desde: desde
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('#resultado').html(data.table);
                if (data.scan > 0) {
                    $('#click').trigger("click");
                }
                $('#name_pr').val('');
                $('#name_pr').focus();
            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        alert("No has elegido una desde donde se realizará el traslado");
    }
}

function sum(i) {

    let cantidad = $('#amount-' + i).val();
    let precio = $('#price-' + i).val();
    let descuento = $('#discount-' + i).val();

    let mul = (parseInt(cantidad) * parseInt(precio));
    let des = mul * (descuento / 100);
    let total = mul - des;

    $('#sub-' + i).html(moneda + total.toFixed(2));
    $('#subt-' + i).val(total.toFixed(2));

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#total').html(moneda + suma.toFixed(2));
    $('#total_a').html(moneda + suma.toFixed(2));
    $('#ttl').val(suma.toFixed(2));
}

function removeOption(i) {
    $('#producto-' + i).remove();
    sum()
}

function stock(branch_id, product_id, id) {
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_productos_stock/' + product_id + '/' + branch_id,
        success: function(response) {
            $("#stock_" + id).val(response);
            $("#send_" + id).prop('max', response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function addOption($product_id) {
    var productoss = $('#productoss-' + $product_id).val();
    var desde = $('#desde').val();

    if (productoss == $product_id) {
        var aumentar = parseInt($('.aumentar-' + $product_id).val());
        if (aumentar < $('#max_vendidos-' + $product_id).val()) {
            $('.aumentar-' + $product_id).val(aumentar + 1);
            $('.aumentar-' + $product_id).focus();
        }

    } else {
        if (desde != '') {
            $('#list_products').show(500);
            if (!addProduct) {
                var id = Math.floor(Math.random() * 300) + 10;
                addProduct = true;
                // alert(desde);
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url();?>admin/get_productos_move2/' + $product_id + '/' + id + '/' + desde,
                    beforeSend: function () {
                        addProduct = true;
                    },
                    success: function(response) {
                        addProduct = false;
                        jQuery('#products').append(response);
                        sum(id);
                    },
                    error: function(e) {
                        addProduct = false;
                        console.log("ERROR : ", e);
                    }
                });
            } else {
                console.log("Espera a que se agregue el producto");
            }
        } else {
            alert("No has elegido una desde donde se realizará el traslado");
        }
    }
}

function sucursales(){
    var desde = $('#desde').val();
    var hacia = $('#hacia').val();
    if (desde != '') {
        $("#branch_envia").val(desde);
        $("#desde").attr("disabled", "disabled");
    }
    if(desde == hacia ){
        desactivar();
    }else{
        $('#submit_traslado').removeAttr('disabled');
    }
    
}
function ver_sucursales(value, i , b, id) { 
    //verificacion de stock CREO! XD
    
    $.ajax({
        type: "GET",
        url: '<?php echo base_url();?>admin/get_stock_lote/' + value,
        beforeSend: function () {
            $('#submit_traslado').attr('disabled', 'disabled');
        },
        success: function(response) {
            console.log("Stock lote:", response);
            $('#send_'+id).attr('max', response);
            $('#submit_traslado').removeAttr('disabled');
        },
        error: function(e) {
            console.log("ERROR : ", e);
            $('#submit_traslado').removeAttr('disabled');
        }
    });
    

    /* let count = 0;
    $('.producto').each(function(){
        var id = $(this).val();
        var from = $('#from_'+id).val();
        var to = $('#to_'+id).val();
        if (from == to) {
            count++;
        }
    });
    if (count > 0) {
        $('#submit_traslado').attr('disabled', 'disabled');
    }
    else{
        $('#submit_traslado').removeAttr('disabled');
    } */
}
</script>