<?php $moneda = $this->crud_model->get_info("moneda"); $regimen = $this->crud_model->get_info("regimen");
    $rol = $this->db->get_where('admin',array('admin_id'=>$this->session->userdata('login_user_id')))->row()->job;?>
<style>
.client-my {
    display: none;
}
.client-farma {
    display: none;
}

.resultado td:hover {
    background: #8950fc2b;
}

</style>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url().'admin/ventas/create/'.$code;?>" method="POST" enctype="multipart/form-data" id="sale_form" name='sale_form'>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    Para comenzar, ingresa el nombre del producto en el buscador ubicado en la parte inferior y luego
                    presiona la tecla <b>ENTER</b> para iniciar la búsqueda.
                </div>
                <div class="card card-custom">

                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <span style='float:right'>Código de orden: <b><?php echo $code ;?></b></span>
                            </div>

                            <div class="col-lg-12 col-xxl-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5> <b> Producto: </b></h5>
                                        <div class=" spinner-success spinner-left" id='spinnerPr'>
                                            <input type="text" autocomplete="off" class='form-control' id='name_pr' placeholder='Ingrese el nombre o código' onchange="search()" autofocus value='<?php echo $this->db->get_where('products', array('products_id'=>$pro))->row()->name;?>'>
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
            <div class="col-sm-12" id="list_products" style="display:none">
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th class="client-mn">Precio</th>
                                        <th class="client-farma">Precio Farmacia</th>
                                        <th class="client-my">Precio Mayoristas</th>
                                        <th>Descuento <small>(%)</small></th>
                                        <th class="client-mn">Subtotal</th>
                                        <th class="client-farma">Subtotal farmacia</th>
                                        <th class="client-my">Subtotal Mayoristas</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                </tbody>
                            </table>
                        </div>
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
                    </div>
                </div>
            </div>
        </div>
</div>


<script src="<?php echo base_url(); ?>public/assets/js/impresora.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js" integrity="sha512-/fgTphwXa3lqAhN+I8gG8AvuaTErm1YxpUjbdCvwfTMyv8UZnFyId7ft5736xQ6CyQN4Nzr21lBuWWA9RTCXCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var regimen = <?php echo $regimen; ?>;
var user_type = '<?php echo $user_type;?>';
var rol = '<?php echo $rol;?>';
var addProduct = false;

$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    $('.efectivo').hide();
    $('.tarjeta').hide();
    $('.trans').hide();
    $('.cheque').hide();
    $('#ms_error').hide();
    $('.add').hide();
    $('#thPrecioUni').hide();
    $('#codigoAuth').hide();
    <?php if($pro != ''):?>
    search();
    addOption('<?php echo $pro;?>');
    <?php endif;?>

});

$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});

nw_credito = false;

function new_credito() {
    if (!nw_credito) {
        $('#pago').removeAttr('required');
        $('#metodo').removeAttr('required');
        $('.factura').attr('hidden', true);
        nw_credito = true;
        $('#resumen').hide(500);

    } else {
        if (user_type == '1' || rol == '4') {
            $('#metodo').attr('required', true);
            $('#resumen').show(500);
            $('#pago').attr('required', true);
        }
        nw_credito = false;
        $('.factura').removeAttr('hidden');
    }
    verificarCodigo();
}

var vAdd = true;

function addInfo() {
    if (vAdd == true) {
        $('.add').show(500);
        vAdd = false;
    } else {
        $('.add').hide(500);
        vAdd = true;
    }

}



function getNit() {
    var str = $('#nit').val();
    var nit = str.replace(/-/g, "");
    var leng_nit = nit.length;
    if (leng_nit >= 7) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/getNit/',
            data: {
                nit: nit,
            },
            beforeSend: function() {
                $('#spinnerName').addClass('spinner');
            },
            success: function(response) {
                $('#phone').val('');
                $('#email_new_client').val('');
                $('#address').val('');

                $('#prueba').attr('value', '2');
                jQuery('#my').removeAttr('checked');
                jQuery('#mn').attr('checked', 'checked');
                client_type(2);
                $('#msClient').html('');


                var data = JSON.parse(response);
                if (data == 'NIT no encontrado') {
                    $('#nombre_cliente').val('NIT no encontrado');
                    $('#spinnerName').removeClass('spinner');
                    $('#prueba').attr('value', '2');
                } else {

                    if (data.length == 2) {
                        var data1 = data['1'].replace(',', ' ');
                        var data0 = data['0'].replace(',', ' ');
                        $('#nombre_cliente').val(data1 + ' , ' + data0);
                    } else {

                        $('#nombre_cliente').val(data['0']);
                    }

                    $('#spinnerName').removeClass('spinner');
                    $('#prueba').attr('value', '2');
                    jQuery('input[name=new_client]').val(1);
                    jQuery('#my').removeAttr('checked');
                    jQuery('#mn').attr('checked', 'checked');
                    client_type(2);

                }


            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        console.log('datos no encontrados');
        $('#spinnerNit').removeClass('spinner');
        $('#nombre_cliente').val('Consumidor Final');
        $('#address').val('ciudad');
        $('#errorNit').html('');
        jQuery('#mn').attr('checked', 'checked');
        jQuery('#my').removeAttr('checked');
        $('#prueba').attr('value', '2');
        client_type(2);
        $('#msClient').html('');
    }

}

function metodo_pago(value) {
    $('.efectivo').hide();
    $('.tarjeta').hide();
    $('.trans').hide();
    $('.cheque').hide();
    let ttl = $('#ttl').val();

    if (value == 'Efectivo') {
        $('.efectivo').show(500);
        $('#pago').attr('required', true);
        $('#pago').val(ttl);
    } else {
        $('.efectivo').hide(500);
        $('#pago').removeAttr('required');
    }

    if (value == 'Tarjeta') {
        $('.tarjeta').show(500);
        $('#voucher').attr('required', true);
        $('#pago').val(ttl);
    } else {
        $('.tarjeta').hide(500);
        $('#voucher').removeAttr('required');
    }

    if (value == 'Transferencia') {
        $('.trans').show(500);
        $('#trans').attr('required', true);
        $('#pago').val(ttl);
    } else {
        $('.trans').hide(500);
        $('#trans').removeAttr('required');
    }

    if (value == 'Cheque') {
        $('.cheque').show(500);
        $('#cheque').attr('required', true);
        $('#pago').val(ttl);
    } else {
        $('.cheque').hide(500);
        $('#cheque').removeAttr('required');
    }
}

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

function clients(value) {
    var str = value;
    var nit = str.replace(/-/g, "");
    if (nit.length >= 1) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/search/clients',
            data: {
                nit: nit,
            },
            beforeSend: function() {
                $('#spinnerNit').addClass('spinner');
            },
            success: function(response) {
                if (response == '0') {
                    getNit();
                } else {
                    var data = JSON.parse(response);
                    $('#nombre_cliente').val(data[0].name + ' , ' + data[0].last_name);
                    $('#phone').val(data[0].phone);
                    $('#email_new_client').val(data[0].email);
                    $('#address').val(data[0].address);
                    $('#client_id').val(data[0].client_id);

                    jQuery('input[name=new_client]').val(0);
                    if (data[0].type == 1) {
                        $('#prueba').attr('value', '1');
                        jQuery('#mn').removeAttr('checked');
                        jQuery('#farma').removeAttr('checked');
                        jQuery('#my').attr('checked', 'checked');
                        client_type(1);
                        $('#msClient').html(
                            '<label class="text-info" > <b> Cliente mayorista </b> </label>');
                    }
                    else if(data[0].type == 3) {
                        $('#prueba').attr('value', '1');
                        jQuery('#mn').removeAttr('checked');
                        jQuery('#my').removeAttr('checked');
                        jQuery('#farma').attr('checked', 'checked');
                        client_type(3);
                        $('#msClient').html(
                            '<label class="text-info" > <b> Cliente farmacia </b> </label>');
                    }else {
                        $('#prueba').attr('value', '2');
                        jQuery('#mn').attr('checked', 'checked');
                        jQuery('#my').removeAttr('checked');
                        jQuery('#farma').removeAttr('checked');
                        client_type(2);
                        $('#msClient').html('');
                    }

                }
                $('#errorNit').html('');
                $('#spinnerNit').removeClass('spinner');
            },
            error: function(e) {
                $('#spinnerNit').removeClass('spinner');
                $('#nombre_cliente').val('Consumidor Final');
                $('#address').val('ciudad');
                $('#errorNit').html('');
                jQuery('#mn').attr('checked', 'checked');
                jQuery('#my').removeAttr('checked');
                client_type(2);
                $('#msClient').html('');
                console.log("ERROR : ", e);
            }
        });

    } else if (nit == 'c/f' || nit == 'cf' || nit == 'C/F' || nit == 'CF') {
        $('#spinnerNit').removeClass('spinner');
        $('#nombre_cliente').val('Consumidor Final');
        $('#address').val('ciudad');
        $('#errorNit').html('');
        $('#mn').attr('checked', 'checked');
        $('#my').removeAttr('checked');
        client_type(2);
        $('#msClient').html('');


    } else {
        $('#spinnerNit').removeClass('spinner');
        $('#nombre_cliente').val('');
        $('#errorNit').html('<small class="text-danger" >Ingrese un NIT Válido </small>');
        $('#mn').attr('checked', 'checked');
        $('#my').removeAttr('checked');
        client_type(2);
        $('#msClient').html('');

    }
}


function search() {

    var name = $('#name_pr').val();

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
            }
            $('#name_pr').val('');
            $('#name_pr').focus();
        },

        error: function(e) {
            console.log("ERROR : ", e);
        }
    });

}
var mayorista = false;
var cl_farma = false;

function client_type(value) {
    if (value == 2) {
        mayorista = false;
        cl_farma  = false;
        $('.client-my').css('display', 'none');
        $('.client-farma').css('display', 'none');
        $('.client-mn').css('display', 'block');
        $('.discount').prop("readonly", false);
        // sum();
    } else if(value == 3) {
        mayorista = false;
        cl_farma = true;
        $('.client-farma').css('display', 'block');
        $('.client-my').css('display', 'none');
        $('.client-mn').css('display', 'none');
        $('.discount').prop("readonly", true);
        $('.discount').val(0);
        // sum();
    } else if(value == 1) {
        mayorista = true;
        cl_farma = false;
        $('.client-my').css('display', 'block');
        $('.client-farma').css('display', 'none');
        $('.client-mn').css('display', 'none');
        $('.discount').prop("readonly", true);
        $('.discount').val(0);
        // sum();
    }
    // console.log("Products", $("input[name='product[]']").length);
    $("input[name='product[]']").each(function() {
        var prod_id = $(this).val();
        // console.log("Product ID: ", prod_id);
        sum(prod_id, prod_id)
    });
}

function removeOption(i) {
    $('#producto-' + i).remove();
    $('#mensaje-' + i).remove();
    sum()
    if ($('.total').length == 0)
        $('#list_products').hide(500);
}

function addOption($product_id) {

    var productoss = $('#productoss-' + $product_id).val();

    if (productoss == $product_id) {
        var aumentar = parseFloat($('.aumentar-' + $product_id).val());
        if (aumentar < $('#max_vendidos-' + $product_id).val()) {
            $('.aumentar-' + $product_id).val(aumentar + 1);
            $('.aumentar-' + $product_id).focus();
            sum($product_id,$product_id);
        }
       // $('#resultado').html('');

    } else {
        $('#list_products').show(500);
        if (!addProduct) {
            addProduct = true;
            var id = Math.floor(Math.random() * 300) + 10;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productos/' + $product_id + '/' + id,
                success: function(response) {
                    addProduct = false;
                    $('#products').append(response);
                    $('#mensaje-' + $product_id).hide();
                    sum($product_id,$product_id);
                    clients($('#nit').val());
                },
                beforeSend: function(){
                addProduct = true;
                //    $('#resultado').html('');
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

function searchEmail() {
    var email = $('#email_new_client').val();
    var ID = '0';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/client',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {

            if (response == 1) {
                $('#msg_new_client').html(" ");
            } else if (response == 0) {
                $('#msg_new_client').html("");
            } else if (response == 2) {
                $('#msg_new_client').html(" ");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function submitform(value) {
    var fPago = $('#metodo').val();
    var elementos = document.getElementsByClassName("product_name").length;
    if ($("#sale_form")[0].checkValidity()) {
        $("#sale_form").attr("action", "<?php echo base_url().'admin/ventas/create/'.$code;?>");
        if (nw_credito && elementos > 0) {
            $('.nueva_venta').attr('disabled', true);
            print_recibo_voucher();
        } else if ((fPago != '' && (user_type == '1' || rol == '4') && elementos > 0) || (fPago == '' && user_type != '1' && rol == '8' && elementos > 0) || (fPago == '' && user_type != '1' && rol == '10' && elementos > 0)) {
            $('.nueva_venta').attr('disabled', true);
            print_recibo_voucher();
        } else {
            $('#ms_error').show(500);
        }
    } else {
        $("#sale_form").attr("action", "javascript:void(0);");
        $("#submit4").click();
    }
}

function print_recibo() {
        
        $.ajax({
            type: "POST",
            url: '<?php echo base_url().'admin/ventas/create/'.$code;?>',
            data: $("#sale_form").serialize(),
            beforeSend: function() {
                
            },
            success: function(response) {
                if(response == 1){
                    window.open("<?php echo base_url();?>admin/reciboDirecto/<?php echo $code;?>");
                    window.location.href = '<?php echo base_url();?>admin/ventas/';
                }
                if(response == 2){
                    window.open("<?php echo base_url();?>admin/reciboDirecto/<?php echo $code;?>");
                    window.location.href = '<?php echo base_url();?>admin/creditos/';
                }
            },
            error: function(e) {
                //alert(e);
            }
        });
    }
    
    function print_recibo_voucher() {
        
        $.ajax({
            type: "POST",
            url: '<?php echo base_url().'admin/ventas/create/'.$code;?>',
            data: $("#sale_form").serialize(),
            beforeSend: function() {
                
            },
            success: function(response) {
                if(response == 1){
                    window.open("<?php echo base_url();?>admin/export_pdf/voucher_sale/<?php echo $code;?>");
                    window.location.href = '<?php echo base_url();?>admin/ventas/';
                }
                if(response == 2){
                    window.open("<?php echo base_url();?>admin/reciboDirecto/<?php echo $code;?>");
                    window.location.href = '<?php echo base_url();?>admin/creditos/';
                }
            },
            error: function(e) {
                //alert(e);
            }
        });
    }


function saveform(value) {
    var fPago = $('#metodo').val();
    var elementos = document.getElementsByClassName("product_name").length;
    // console.log("Nw_credito:", nw_credito, "fPago:", fPago, "elementos:", elementos, "User type:", user_type, "Rol:", rol);
    if ($("#sale_form")[0].checkValidity()) {
        $("#sale_form").attr("action", "<?php echo base_url().'admin/ventas/create/'.$code;?>");
        if (nw_credito && elementos > 0) {
            // console.log("Con credito");
            $('.nueva_venta').attr('disabled', true);
            guardar_recibo();
        } else if ((fPago != '' && (user_type == '1' || rol == '4') && elementos > 0) || (fPago == '' && user_type != '1' && rol == '8' && elementos > 0) || (fPago == '' && user_type != '1' && rol == '10' && elementos > 0)) {
            //console.log("Sin credito");
            $('.nueva_venta').attr('disabled', true);
            guardar_recibo();
        } else {
            // console.log("No válido");
            $('#ms_error').show(500);
        }
    } else {
        $("#sale_form").attr("action", "javascript:void(0);");
        $("#submit4").click();
    }
}

function guardar_recibo() {
        
        $.ajax({
            type: "POST",
            url: '<?php echo base_url().'admin/ventas/apply/'.$code;?>',
            data: $("#sale_form").serialize(),
            beforeSend: function() {
                
            },
            success: function(response) {
                window.location.href = '<?php echo base_url();?>admin/ventas/';
            },
            error: function(e) {
                //alert(e);
            }
        });
    }




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
                    if (valor == 'descuentos') {
                        $('.nueva_venta').removeAttr('hidden');
                        $('#descuentos').val('1');
                    }
                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    if (valor == 'descuentos') {
                        $('.nueva_venta').attr('hidden', true);
                        $('#descuentos').val('0');
                    }
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

function verificarCodigo () {
    var descuentos = $('#descuentos').val();
    var sumaDescuento = 0;
    $('.discount').each(function() 
    {
        sumaDescuento += parseFloat($(this).val());
    });

    if ((sumaDescuento > 0  || nw_credito) && descuentos == 0) {
        $('#codigoAuth').show(500);
        $('.nueva_venta').attr('hidden', true);
    } else {
        $('.nueva_venta').removeAttr('hidden');
    }
}

function sum(id, i, v) {
    $('#metodo').val('');

    var cantidad = $('#amount-' + i).val();
    var precio = $('#price-' + i).val();
    var precio_my = $('#price_my-' + i).val();
    var precio_farma = $('#price_farma-' + i).val();
    var descuento = $('#discount-' + i).val();
    var prPrice = $('#prPrice-' + i).val();
    //alert(precio_farma);
    
    $('#mensaje-' + i).queue(function(n) 
    {
        $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>admin/compare_stock',
              data: "c="+cantidad+'|'+id,
              dataType: "html",
              error: function(){
                    //alert("¡Error!");
              },
              success: function(data)
              { 
                if (data == "success")
                {            
                    // $('#mensaje-' + i).hide(500);
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
    
    
    if(v == 2) 
    {
        if (parseFloat(precio) <= parseFloat(prPrice) && !mayorista) 
        {
            //alert('minorista');
            var diferencia      = parseFloat(prPrice) - parseFloat(precio);
            var newPorcentaje   = (diferencia / parseFloat(prPrice)) * 100;
            $('#discount-' + i).val(newPorcentaje.toFixed(2));
            descuento = newPorcentaje.toFixed(2);
        } else {
            $("#discount-"+i).val(0);
        }
    }

    if(v == 3) 
    {
        if (parseFloat(descuento) >= 0 && !mayorista) 
        {
            var des = parseFloat(prPrice) - (parseFloat(descuento) * parseFloat(prPrice)) / 100;
            $('#price-' + i).val(des.toFixed(2));
            precio = des.toFixed(2)
        }
    }


    var delivery = 0;
    if ($("#delivery_cost").val() != "")
        delivery = parseFloat($("#delivery_cost").val());

    var mul             = (parseFloat(cantidad) * parseFloat(precio));
    var des             = mul * (descuento / 100);
    var total           = mul;
    var precio_producto = $('#precioProducto-' + i).val();

    var pu = parseFloat(precio) - (parseFloat(precio) * (descuento / 100));
    
    var descuentos = $('#descuentos').val();
    
    /* var sumaDescuento = 0;
    $('.discount').each(function() {
        sumaDescuento += parseFloat($(this).val());
    });

    if (sumaDescuento > 0 && descuentos == 0) {
        // $('#codigoAuth').show(500);
        $('.nueva_venta').attr('hidden', true);
    } else {
        $('.nueva_venta').removeAttr('hidden');
    } */
    
    verificarCodigo();
    // console.log("Total: ", total, "Precio producto:", Number(precio_producto), "Farma:", Number(precio_farma), "Mayorista:", Number(precio_my), "Val may:", mayorista, "Cl farma:", cl_farma);
    if (total < precio_producto && !mayorista && cl_farma==false) 
    {
        $('#mensaje-' + i).show(500);
        var COSTO = parseFloat(precio_producto);
        var PRECIO = parseFloat(prPrice);

        var TOTAL = ((PRECIO - COSTO) / COSTO) * 100;
        
        var ms =`<td><small class="text-danger" id="ms-descuento"> El costo del producto es  <b>${moneda}${precio_producto}</b> y el descuento es <b>${moneda}${descuento}%</b> el cual te dará una ganancia negativa </small></td>`;
        $('#mensaje-' + i).html(ms);
    } else if (Number(precio_farma) < Number(precio_producto) && cl_farma) {
        $('#mensaje-' + i).show(500);
        var ms =`<td><small class="text-danger" id="ms-descuento"> El costo del producto es  <b>${moneda}${precio_producto}</b> y el precio de venta es <b>${moneda}${precio_farma}</b> el cual te dará una ganancia negativa </small></td>`;
        $('#mensaje-' + i).html(ms);
    } else if (Number(precio_my) < Number(precio_producto) && mayorista) {
        $('#mensaje-' + i).show(500);
        var ms =`<td><small class="text-danger" id="ms-descuento"> El costo del producto es  <b>${moneda}${precio_producto}</b> y el precio de venta es <b>${moneda}${precio_my}</b> el cual te dará una ganancia negativa </small></td>`;
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
    
    var mul_farma = (parseFloat(cantidad) * parseFloat(precio_farma));
    var des_farma = mul_farma * (descuento / 100);
    var total_farma = mul_farma - des_farma;

    $('#sub_my-' + i).html(moneda + ' ' + total_my.toFixed(2));
    $('#subt_my-' + i).val(total_my.toFixed(2));
    
    $('#sub_farma-' + i).html(moneda + ' ' + total_farma.toFixed(2));
    $('#subt_farma-' + i).val(total_farma.toFixed(2));

    if (!mayorista && cl_farma==false) 
    {
        //alert('Entro a minorista');
        var suma = 0;
        $('.total').each(function() {
            suma += parseFloat($(this).val());
        });

        total = suma + delivery;
        $('#total').html(moneda + total.toFixed(2));
        $('#total_a').html(moneda + total.toFixed(2));
        $('#ttl').val(total.toFixed(2));
        $('#pago').val(total.toFixed(2));
    }
    else if(cl_farma) 
    {
        //alert('Entro a farma');
        var suma = 0;
        $('.total_farma').each(function() {
            suma += parseFloat($(this).val());
        });

        total = suma + delivery;
        $('#total').html(moneda + total.toFixed(2));
        $('#total_a').html(moneda + total.toFixed(2));
        $('#ttl').val(total.toFixed(2));
        $('#pago').val(total.toFixed(2));
    }
    else 
    {
        //alert('Entro a mayorista');
        var suma = 0;
        $('.total_my').each(function() {
            suma += parseFloat($(this).val());
        });

        total = suma + parseFloat(delivery);
        $('#total').html(moneda + total.toFixed(2));
        $('#total_a').html(moneda + total.toFixed(2));
        $('#ttl').val(total.toFixed(2));
        $('#pago').val(total.toFixed(2));
    }
    cambio();


    if (total == 0) 
    {
        $(".nueva_venta").hide(500);
    } 
    else 
    {
        $(".nueva_venta").show(500);
        $('#list_products').show(500);

    }

}
</script>

<script>
$(".form").bind("button", function() {
    $(this).find(':a[class=button]').prop('disabled', true);
});
</script>
