<?php $moneda = $this->crud_model->get_info("moneda"); $regimen = $this->crud_model->get_info("regimen");
$sales = $this->db->get_where('sales', array('code'=>$code));
if($sales->num_rows() > 0){
    $data = $sales;
}else{
    $data = $this->db->get_where('quotes', array('code'=>$code));
}
;?>
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
    <form class="form" action="<?php echo base_url();?>admin/cotizaciones/create" method="POST" enctype="multipart/form-data" id="quote_form" name='quote_form'>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value='<?php echo $code;?>'>
                                <span>Código de cotización: <b><?php echo $code;?></b> <span style="float:right"><b>Fecha de cotización:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( date('Y-m-d') ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                        <?php echo date('H:i:s');?></span></span>
                                <br><br>
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><b>NIT:</b></label>
                                            <div class=" spinner-success spinner-left" id='spinnerNit'>
                                                <input type="text" placeholder="Ingrese el nit sin guiones" class="form-control " name="nit" id="nit" min='0' minlength="7" maxlength="12" onblur="clients(this.value)" onblur="clients(this.value)" autocomplete="off" required>
                                            </div>
                                            <div id='errorNit'></div>
                                            <small class="text-info">Presiona la tecla <b>TAB</b> para buscar al contribuyente </small>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 new_client">
                                        <div class="form-group">
                                            <label><b>Nombre:</b></label>
                                            <div class="spinner-primary spinner-left" id='spinnerName'>
                                                <input type="text" placeholder="Ingrese el nombre completo" class="form-control" name='c_name' id='c_name'>
                                            </div>
                                            <div id='msClient'></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style='display:none;'>
                                        <div class="form-group">
                                            <label><b>Tipo de cliente:</b></label>
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" id="my" name="type" value="1" onclick="client_type('1')">
                                                    <span></span>Mayorista
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" id="mn" name="type" value="2" onclick="client_type('2')">
                                                    <span></span>Minorista
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" id="fm" name="type" value="3" onclick="client_type('3')">
                                                    <span></span>Farmacia
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" id="type_client" style='display:none'>
                                    <input type="hidden" name="responsable" value="<?php echo $this->session->userdata('login_user_id');?>" />
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Responsable:</b></label>
                                            <span class="form-control"><?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Fecha de vencimiento:</b></label>
                                            <input type="date" class="form-control" name="date_end" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-custom">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="alert alert-warning">
                                            Para comenzar, ingresa el nombre del producto en el buscador ubicado en la
                                            parte inferior y luego
                                            preciona la tecla
                                            <b>TAB</b> para iniciar la búsqueda.
                                        </div>
                                        <h5> <b> Producto: </b></h5>
                                        <div class=" spinner-success spinner-left" id='spinnerPr'>
                                            <input type="text" autocomplete="off" class='form-control' id='name_pr' placeholder='Ingrese el nombre o código' onchange="search(this.value)" onblur="">
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
                                        <th class="client-my">Precio Mayoristas</th>
                                        <th class="client-farma">Precio farmacia</th>
                                        <th>Descuento (%)</th>
                                        <th class="client-mn">Subtotal</th>
                                        <th class="client-my">Subtotal Mayoristas</th>
                                        <th class="client-farma">Subtotal farmacia</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                    <?php  ;for($i=0; $i < $data->row()->num_products ; $i++):  
                                    if ($data->row()->products != "" || $data->row()->products != null) {
                                        $pro = json_decode($data->row()->products,true);
                                    } else {
                                        $pro = array();
                                    }?>
                                    <tr id='producto-<?php echo $i; ?>'>
                                        <td>
                                            <?php echo $pro[$i]['product'];?>
                                            <input type="hidden" name="product[]" value='<?php echo $pro[$i]['product'];?>'>
                                        </td>
                                        <td>
                                            <input min="1" class="form-control" type="number" style="width:70px" step="any" id="amount-<?php echo $i; ?>" name="amount[]" value="<?php echo $pro[$i]['amount']?>" onblur="sum('<?php echo $i;?>')">
                                        </td>
                                        <td>
                                            <?php echo $moneda.number_format($pro[$i]['price'],2,'.',',');?>
                                            <input min="1" class="form-control" type="hidden" style="width:110px" step="any" id="price-<?php echo $i;?>" name="price[]" value="<?php echo $pro[$i]['price']?>">
                                        </td>

                                        <td>
                                            <input min="0" class="form-control" type="number" style="width:70px" step="any" id="discount-<?php echo $i;?>" name="discount[]" value="<?php echo $pro[$i]['discount']?>" onblur="sum('<?php echo $i;?>')">
                                        </td>

                                        <td><span class="text-success" id='sub-<?php echo $i;?>'><?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?></span>
                                            <input type="hidden" class='total' name="sub[]" id='subt-<?php echo $i;?>'>
                                        </td>
                                        <td>
                                            <a class="badge badge-danger" style="padding:3px;" onclick="removeOption('<?php echo $i;?>')" href="javascript:;">
                                                <span class="svg-icon svg-icon-white svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
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
                        <div class="col-sm-12">
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
                                <div class="font-weight-boldest font-size-h5">TOTAL</div>
                                <div class="text-right d-flex flex-column">

                                    <span class="font-weight-boldest font-size-h3 line-height-sm" id='total'><?php echo $moneda.number_format($data->row()->total,2,'.',',');?></span>
                                    <input type="hidden" name="ttl" id='bttl'>

                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="new-client" id="new-client" value='0'>
                        <input type="hidden" name="client_id" id='client_id' value="">

                        <div class="col-sm-12">
                            <hr>
                            <div class="form-group" id='codigoAuth'>
                                <label> Código de autorización para aplicar descuentos: <span class="text-danger">*</span></label>
                                <div class=" spinner-success spinner-left" id='spinnerCode'>
                                    <input type="password" autocomplete="off" class='form-control' id='code' placeholder='Ingresa el código de autorización' autofocus onblur="getCodigo(this.value)">
                                </div>
                                <div id='mensajeError'></div>
                                <small class='text-info'>Presionar la tecla TAB para verificar tu código</small>
                            </div>

                            <input type='hidden' id='descuentos' value='0'>
                            <a href="javascript: submitform()" class="btn btn-light-warning font-weight-bolder mr-3 nueva_venta" hidden type='submit' id='submit' style='float: right;'>
                                Generar cotización
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#codigoAuth').hide();
})
var moneda = '<?php echo $moneda; ?>';
var addProduct = false;

function submitform() {
    var nit = $('#nit').val();
    if (nit != '') {
        document.quote_form.submit();
        $('#ms_error').html('');
    } else {
        $('#errorNit').html('<small class="text-info" >Ingrese un NIT </small>');
        $('#nit').focus();
    }
}

function print_recipe() {


    var nit = $('#nit').val();
    if (nit == '') {
        nit = 'Consumidor Final';
    }
    var pago = $('#pago').val();
    var nombre = $('#c_name').val();
    var name = nombre;

    var vencimiento = $('.date_end').val();


    var nombreComercial = '<?php echo $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;?>';
    var direccionemisor = '<?php echo $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;?>';
    var codigoPostal = '<?php echo $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;?>';
    var municipio = '<?php echo $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;?>';
    var departamento = '<?php echo $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;?>';
    var nitemisor = '<?php echo $this->db->get_where('settings', array('type'=>'nit'))->row()->description;?>';

    if (nit != '') {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');

        var html = `<!DOCTYPE html>
                <html>
                    <head>

                    <style>

                    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&display=swap');
                    
                        @page {
                            size: auto;   /* auto is the initial value */ margin: 0mm;
                        }

                        body {
                            margin: 0px;
                            margin-left: 8px;
                        }
                        * {
                            font-size: 12px;
                            font-family: 'Poppins';
                            font-weight: bold;
                        }

                        td,
                        th,
                        tr,
                        table {
                            font-size: 10px;
                            border-collapse: collapse;
                            margin-left: 3px;
                        }

                        .centrado {
                            text-align: center;
                            align-content: center;
                            margin: 0px;
                        }

                        .ticket {
                            width: 200px;
                            max-width: 200px;
                        }
                        img {
                            max-width: 80px;
                            width: 80px;
                        }
                        p.ex1 {
                            margin-left: 3px;
                        }
                    </style>
                    </head>
                    <body>
                        <div class="ticket">
                            <p class="centrado">
                                <img src="<?php echo base_url();?>uploads/img/<?php echo $this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" alt="Logotipo">
                            </p>
                            <br>
                            <br>
                            <p class="centrado">${nombreComercial}</p>
                            <p class="centrado">NIT: ${nitemisor} </p>
                            <p class="centrado">${direccionemisor} </p>
                            <p class="centrado">${municipio} , ${departamento}  </p>
                            <br>
                            <p class="centrado">COTIZACIÓN</p>
                            <p class="centrado"> Fecha de vencimiento: ${vencimiento}</p>
                            <br>
                            <p class="centrado">----DATOS DEL CLIENTE-----</p>
                            <p class="centrado">NIT:${nit}</p>
                            <p class="centrado" style="font-size:12px">${name}</p>
                            <br>
                            <p class="centrado">----Descripción de la cotización----</p>
                            <br>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 250px;">DESCRIPCIÓN</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>`;
        var products = document.getElementsByClassName("product_name");
        var amount = document.getElementsByName('amount[]');
        var price = document.getElementsByName("price[]");
        var sbtl = document.getElementsByName('sub[]');
        var price_my = document.getElementsByName("price_my[]");
        var sub_my = document.getElementsByName('sub_my[]');
        var total = 0;
        var totalImpuesto = 0;
        var regimen = '<?php echo $this->db->get_where('settings',array('type'=>'regimen'))->row()->description/100 ;?>';
        var montoGravable = 0;


        for (var i = 0; i < products.length; i++) {


            html += '<td style="text-align: left;">' + products.item(i).textContent +  ' (' + amount.item(i).value + 'x';

            if (mayorista) {
                html += 'Q.' + price_my.item(i).value + ')</td>';
                html += '<td style="white-space: nowrap;">Q. ' + sub_my.item(i).value + '</td></tr>';

                total += parseFloat(sub_my.item(i).value);
                montoGravable = parseFloat(total) / (parseFloat(regimen) + 1);

            } else {
                html += 'Q. ' + price.item(i).value + ')</td>';
                html += '<td style="white-space: nowrap;">Q. ' + sbtl.item(i).value + '</td></tr>';

                total += parseFloat(sbtl.item(i).value);
                montoGravable = parseFloat(total) / (parseFloat(regimen) + 1);

            }

            var montoImpuesto = parseFloat(montoGravable) * parseFloat(regimen);
            totalImpuesto += parseFloat(montoImpuesto);


        }

        html += `<tr>
    <td></td>
    <td>---------</td>
</tr>
<tr>
    <td><b>TOTAL</b></td>
    <td>Q.${total.toFixed(2)}</td>
</tr>
                            </tbody>
                            </table>
                            <br>
                            <p class="ex1">Atendido por : <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></p>
                        </div>
                    </body>
                </html>`;


        mywindow.document.write(html);


        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();
        document.quote_form.submit();

    } else {
        console.log('error');
    }


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
        sum();
    } else if(value == 3) {
        mayorista = false;
        cl_farma = true;
        $('.client-farma').css('display', 'block');
        $('.client-my').css('display', 'none');
        $('.client-mn').css('display', 'none');
        $('.discount').prop("readonly", true);
        $('.discount').val(0);
        sum();
    } else if(value == 1) {
        mayorista = true;
        cl_farma = false;
        $('.client-my').css('display', 'block');
        $('.client-farma').css('display', 'none');
        $('.client-mn').css('display', 'none');
        $('.discount').prop("readonly", true);
        $('.discount').val(0);
        sum();
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
                console.log("Response clients:", response);
                if (response == '0') {
                    $('#new-client').val(1);
                    getNit();
                } else {
                    var data = JSON.parse(response);
                    $('#c_name').val(data[0].name + ' , ' + data[0].last_name);
                    $('#c_phone').val(data[0].phone);
                    $('#c_email').val(data[0].email);
                    $('#address').val(data[0].address);
                    $('#client_id').val(data[0].client_id);

                    jQuery('input[name=new_client]').val(0);
                    if (data[0].type == 1) {
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
                    } else {
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
                console.log("Error clients:", e);
                $('#spinnerNit').removeClass('spinner');
                $('#c_name').val('Consumidor Final');
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
        $('#c_name').val('Consumidor Final');
        $('#address').val('ciudad');
        $('#errorNit').html('');
        $('#mn').attr('checked', 'checked');
        $('#my').removeAttr('checked');
        client_type(2);
        $('#msClient').html('');


    } else {
        $('#spinnerNit').removeClass('spinner');
        $('#c_name').val('');
        $('#errorNit').html('<small class="text-danger" >Ingrese un NIT Válido </small>');
        $('#mn').attr('checked', 'checked');
        $('#my').removeAttr('checked');
        client_type(2);
        $('#msClient').html('');

    }
}


function search(value) {

    var name = value;

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/productSaleCot',
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
        },

        error: function(e) {
            console.log("ERROR : ", e);
        }
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
            var id = Math.floor(Math.random() * 300) + 10;
            addProduct = true;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productos/' + $product_id + '/' + id,
                success: function(response) {
                    addProduct = false;
                    $('#products').append(response);
                    $('#mensaje-' + id).hide();
                    sum($product_id,$product_id);
                    //sum(id); esto cambie XD 
                    clients($('#nit').val());
                },
                beforeSend: function(){
                    addProduct = true;
                    // $('#resultado').html('');
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
                console.log("Response getNit:", response);
                $('#phone').val('');
                $('#c_email').val('');
                $('#address').val('');

                jQuery('#my').removeAttr('checked');
                jQuery('#mn').attr('checked', 'checked');
                client_type(2);
                $('#msClient').html('');


                var data = JSON.parse(response);
                console.log("Data getNit:", data);
                if (data == 'NIT no encontrado') {
                    $('#c_name').val('NIT no encontrado');
                    $('#spinnerName').removeClass('spinner');
                } else {

                    if (data.length == 2) {
                        var data1 = data['1'].replace(',', ' ');
                        var data0 = data['0'].replace(',', ' ');
                        $('#c_name').val(data1 + ' , ' + data0);
                    } else {

                        $('#c_name').val(data['0']);
                    }

                    $('#spinnerName').removeClass('spinner');

                    jQuery('input[name=new_client]').val(1);
                    jQuery('#my').removeAttr('checked');
                    jQuery('#mn').attr('checked', 'checked');
                    client_type(2);
                }
            },
            error: function(e) {
                console.log("ERROR getNit:", e);
            }
        });
    } else {
        console.log('datos no encontrados');
        $('#spinnerNit').removeClass('spinner');
        $('#c_name').val('Consumidor Final');
        $('#address').val('ciudad');
        $('#errorNit').html('');
        jQuery('#mn').attr('checked', 'checked');
        jQuery('#my').removeAttr('checked');
        client_type(2);
        $('#msClient').html('');
    }

}


function searchEmail(value) {
    var email = value;
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
                $('#msg_email').html(" ");
            } else if (response == 0) {
                $('#msg_email').html("");
            } else if (response == 2) {
                $('#msg_email').html(" ");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
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
    4719867213084

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

        var suma = 0;
        $('.total').each(function() {
            suma += parseFloat($(this).val());
        });


        $('#total').html(moneda + suma.toFixed(2));
        $('#total_a').html(moneda + suma.toFixed(2));
        $('#bttl').val(suma.toFixed(2));

    } else if(cl_farma) {
        //alert('Entro a farma');
        var suma = 0;
        $('.total_farma').each(function() {
            suma += parseFloat($(this).val());
        });

        $('#total').html(moneda + suma.toFixed(2));
        $('#total_a').html(moneda + suma.toFixed(2));
        $('#bttl').val(suma.toFixed(2));

    } else {

        var suma = 0;
        $('.total_my').each(function() {
            suma += parseFloat($(this).val());
        });

        $('#total').html(moneda + suma.toFixed(2));
        $('#total_a').html(moneda + suma.toFixed(2));
        $('#bttl').val(suma.toFixed(2));
    }


    if (total == 0) {
        $(".nueva_venta").hide(500);
    } else {
        $(".nueva_venta").show(500);
        $('#list_products').show(500);

    }

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
</script>
