<?php 
    $moneda = $this->crud_model->get_info("moneda"); $client = array(); $client_type = 2; 
    $rol = $this->db->get_where('admin',array('admin_id'=>$this->session->userdata('login_user_id')))->row()->job;
    log_message("error", "Rol: $rol");
    $sale = $this->db->get_where('sales',array('code'=>$code))->result_array();
    foreach($sale as $rw):
    $client_type = $rw['my'];
?>
<style>
    <?php if($client_type == 1):?>
    .client-mn {
        display: none;
    }
    .client-farma {
        display: none;
    }
    <?php elseif($client_type == 2):?>
    .client-my {
        display: none;
    }
    .client-farma {
        display: none;
    }
    <?php elseif($client_type == 3):?>
    .client-mn {
        display: none;
    }
    .client-my {
        display: none;
    }
    <?php endif;?>
    .resultado td:hover {
        background: #8950fc2b;
    }
</style>
<div class="container-fluid">
    <a href="<?php echo base_url();?>admin/ventas/" class="btn btn-warning">< Regresar</a>
    <br><br>
    <form class="form" action="<?php echo base_url().'admin/venta_editar/'; if($this->session->userdata('login_user_type') == '1'||$rol==4 ) echo 'generar'; else echo 'editar'; echo '/'.$code;?>" id="sale_form" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-custom">
                            <div class="card-body">
                                <h5>Resumen de la orden:</h5>
                                <div class="border-bottom"></div><br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Venta:</b></label><br>
                                            <?php echo $code;?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Vendedor:</b></label><br>
                                            <?php echo $this->crud_model->getName('admin',$rw['responsable']) ;?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Cliente:</b></label><br>
                                            <?php if($sale['client_id'] == 0):?>
                                            <?php echo $rw['name'];?>
                                            <?php elseif($rw['client_id'] > 0): $cliente = $this->db->get_where('client', array('client_id' => $rw['client_id']));?>
                                                <?php if ($cliente->num_rows() > 0):?>
                                                <?php echo $this->crud_model->getName('client',$rw['client_id']);?>
                                                <?php else: ?>
                                                <span class="label label-lg font-weight-bold label-light-danger label-inline">Eliminado</span>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </div>
                                        <input type="hidden" id="client_type" value="<?php echo $client_type?>" />
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>NIT:</b></label>
                                            <br><?php echo $rw['nit'];?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Celular:</b></label>
                                            <br><?php echo $rw['phone'];?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Dirección de facturación:</b></label><br>
                                            <?php echo $rw['address'];?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Tipo cliente:</b></label><br>
                                            <?php if($rw['my']==1){echo "Mayorista";}elseif($rw['my']==3){echo "Farmacia";}else{echo "Publico";};?>
                                        </div>
                                    </div>
            
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Entrega:</b></label><br>
                                            <?php if($rw['shipping_cost'] > 0) {echo $moneda.number_format($rw['shipping_cost'],2,'.',',');} ?><br>
                                            <?php echo $rw['delivery'];?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Detalles:</b></label><br>
                                            <?php echo $rw['details'];?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Estado:</b></label><br>
                                            <span
                                                class="label label-lg font-weight-bold label-light-<?php if($rw['estado'] == 1){ echo 'warning';}elseif($rw['estado'] == 2){echo 'success';}elseif($rw['estado'] == 3){echo 'danger';}elseif($rw['estado'] == 4){echo 'info';}?> label-inline">
                                                <?php if($rw['estado'] == 1){ echo 'Crédito'; }elseif($rw['estado'] == 2){ echo 'Completados' ; }elseif($rw['estado'] == 3){echo 'Anulado';}elseif($rw['estado'] == 4){echo 'Cambio';}elseif($rw['estado'] == 5){echo 'Aplicado/Guardado';} ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><b>Fecha:</b></label><br>
                                            <span><?php echo $rw['date'];?> | <?php echo $rw['time'];?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="row" id="resumen" style="<?php if($user_type != 1 && $rol != '4') echo "display:none;";?>">
                                            <div class="col-sm-12">
                                                <span><b id="resumen_title">Forma de pago:</b> <span class="text-danger">*</span></span>
                                                <div class="border-bottom"></div><br>
                                            </div>
                                            <div class="col-lg-12 col-xxl-12">
                                                <div class="form-group">
                                                    <label>Método:</label>
                                                    <select class="form-control" id='metodo' name="metodo" onchange="metodo_pago(this.value)" <?php if($user_type == 1 || $rol == 4) echo "required";?>>
                                                        <option value="">Seleccionar</option>
                                                        <option value='Efectivo'>Efectivo</option>
                                                        <option value='Tarjeta'>Tarjeta de crédito / débito</option>
                                                        <option value='Transferencia'>Transferencia / Depósito</option>
                                                        <option value='Cheque'>Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
            
            
                                            <div class="col-lg-6 col-xxl-6 efectivo">
                                                <div class="form-group">
                                                    <label>Pago con:</label>
                                                    <input type="number"  value="" min='0' step="any" class="form-control" name='pago' id='pago' oninput="cambio()" />
                                                </div>
                                            </div>
            
                                            <div class="col-lg-6 col-xxl-6 efectivo">
                                                <div class="form-group">
                                                    <label>Cambio:</label><br>
                                                    <span class="font-weight-boldest font-size-h6 line-height-sm" id='change'><?php echo $moneda;?>0.00</span>
                                                    <input type="hidden" value="0" class="form-control" id='changee' name='change' />
                                                </div>
                                            </div>
            
            
                                            <div class="col-lg-12 col-xxl-12 tarjeta">
                                                <div class="form-group">
                                                    <label>Voucher</label>
                                                    <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el voucher' name='voucher' id='voucher' />
                                                </div>
                                            </div>
            
                                            <div class="col-lg-12 col-xxl-12 trans">
                                                <div class="form-group">
                                                    <label>No. Transferencia</label>
                                                    <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de boleta' name='trans' id='tans' />
                                                </div>
                                            </div>
            
                                            <div class="col-lg-12 col-xxl-12 cheque">
                                                <div class="form-group">
                                                    <label>No. Cheque</label>
                                                    <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de cheque' name='cheque' id='cheque' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="<?php if($user_type != 1 && $rol != '4') echo "display:none;";?>"><br>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>Venta al crédito</label>
                                                        <span class="switch switch-sm">
                                                            <label>
                                                                <input type="checkbox" name="credito" value="1" />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>CUI</label>
                                                        <span class="switch switch-sm">
                                                            <label>
                                                                <input type="checkbox" name="cui" value="1" <?php if($rw['cui'] == 1) echo "checked";?> />
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos aplicados</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th class="client-mn">Precio</th>
                                        <th class="client-my">Precio mayorista</th>
                                        <th class="client-farma">Precio farmacia</th>
                                        <th class="client-mn">Subtotal</th>
                                        <th class="client-my">Subtotal mayorista</th>
                                        <th class="client-farma">Subtotal farmacia</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datosP = json_decode($rw['products'],true);
                                        foreach($datosP as $pr):
                                            $subX = 0; $product_id = $pr['product'];
                                            log_message("error", "Products ID: ".number_format($product_id));
                                            $prod = $this->db->get_where('products', array('products_id'=>$product_id))->row_array();
                                            if ($prod['presentation'] == 'Caja') $product_id = $prod['id_prod_matriz'];
                                            log_message("error", "Products ID: ".number_format($product_id));
                                            $validity = $this->db->get_where('product_details', array('products_id'=>$product_id, 'activity_ref'=>$code, 'estado'=>1))->num_rows();?>
                                        <?php if($validity > 0):
                                            $subX = $pr['amount']*$pr['price'];
                                            $subX_my = $pr['amount']*$pr['price_my'];
                                            $subX_farma = $pr['amount']*$pr['price_farma'];?>
                                    <tr>
                                        <td><?php echo $this->db->get_where('products', array('products_id' => $pr['product']))->row()->code;?></td>
                                        <td>
                                            <span class="product_name">
                                                <?php echo $this->db->get_where('products', array('products_id' => $pr['product']))->row()->name;
                                                    if(!$prod['iva']) echo " (Exento)"; else echo " (Afecto)";?>
                                            </span> 
                                            <input type="hidden" id="productoss-1" name="product_id[]" value="<?php echo $pr['product'];?>">  
                                        </td>
                                        <td>
                                            <?php echo $pr['amount'];?>
                                            <input type="hidden" class="amount" name="amountx[]" value="<?php echo $pr['amount'];?>" />
                                            <input type="hidden" name="costx[]" value="<?php echo $pr['cost'];?>" />
                                            <input type="hidden" name="discountx[]" value="<?php echo $pr['discount'];?>" />
                                        </td>
                                        <td class="client-mn"><?php echo $pr['price'];?></td>
                                        <td class="client-mn">
                                            <span class="text-success" id="subs_<?php echo $pr['products_id'];?>">Q<?php echo $subX;?></span> 
                                            <span class="totalx" style="display:none;"><?php echo $subX;?></span>
                                            <input type="hidden" name="pricex[]" value="<?php echo $pr['price'];?>" />
                                            <input type="hidden" class="total" name="subx[]" value="<?php echo $subX;?>" />
                                        </td>
                                        <td class="client-my"><?php echo number_format($pr['price_my'],2,'.','');?></td>
                                        <td class="client-my">
                                            <span class="text-success" id="subs_<?php echo $pr['products_id'];?>">Q<?php echo $subX_my;?></span> 
                                            <span class="totalx" style="display:none;"><?php echo $subX_my;?></span>
                                            <input type="hidden" name="pricex_my[]" value="<?php echo $pr['price_my'];?>" />
                                            <input type="hidden" class="total_my" name="subx_my[]" value="<?php echo $subX_my;?>" />
                                        </td>
                                        <td class="client-farma"><?php echo number_format($pr['price_farma'],2,'.','');?></td>
                                        <td class="client-farma">
                                            <span class="text-success" id="subs_<?php echo $pr['products_id'];?>">Q<?php echo $subX_farma;?></span> 
                                            <span class="totalx" style="display:none;"><?php echo $subX_farma;?></span>
                                            <input type="hidden" name="pricex_farma[]" value="<?php echo $pr['price_farma'];?>" />
                                            <input type="hidden" class="total_farma" name="subx_farma[]" value="<?php echo $subX_farma;?>" />
                                        </td>
                                        <td>
                                            <a class="badge badge-danger" style="padding:3px;" onclick="deletProdEdit('<?php echo $pr['product'];?>','<?php echo $code;?>',this)" href="javascript:void(0)">
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
                                    <?php endif; endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <br>
                <div class="card card-custom">
                    <div class="card-body">
                        <h3 class="card-label text-info">Agregar productos</h3>
                        <div class="alert alert-warning">
                            Para comenzar, ingresa el nombre del producto en el buscador ubicado en la parte inferior y luego
                            presiona la tecla
                            <b>ENTER</b> para iniciar la búsqueda.
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5> <b> Producto: </b></h5>
                                        <div class=" spinner-success spinner-left" id='spinnerPr'>
                                            <input type="text" autocomplete="off" class='form-control' id='name_pr' placeholder='Ingrese el nombre o código del producto' onchange="search()" autofocus value='<?php echo $this->db->get_where('products', array('products_id'=>$pro))->row()->name;?>'>
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
                        <div class="table-responsive" id="list_products" style="display:none">
                            <h3 class="card-label text-info">Productos</h3>
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
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="client-mn"></th>
                                        <th class="client-farma"></th>
                                        <th class="client-my"></th>
                                        <th></th>
                                        <th class="client-mn"></th>
                                        <th class="client-farma"></th>
                                        <th class="client-my"></th>
                                        <th>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-sm-12 text-right" style="font-size: 20px; font-weight: bold;">
                            Total: Q<span id="spnTotal"><?php echo number_format($rw['total'],2,'.',',');?></span>
                            <input type="hidden" name="total_pagado" id="total_pagado" value="<?php echo $rw['total_pagado'];?>" />
                            <input type="hidden" name="ttl" id="ttl" value="<?php echo $rw['total'];?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <br>
                    <?php if($this->session->userdata('login_user_type') == '1'||$rol==4 ):?>
                    <!--<button class="btn btn-success nueva_venta" type="button" onclick="submitform(0)" id="submit-form" style="margin-right: 10px">
                    Generar venta-->
                    <button class="btn btn-info nueva_venta" type="button" onclick="submitform(1)" id="submit-form">
                    Editar venta
                    <button class="btn btn-info nueva_venta" type="button" onclick="submitform(2)" id="submit-form">
                    Facturar venta
                    <?php else:?>
                    <button class="btn btn-info nueva_venta" type="button" onclick="submitform(1)" id="submit-form">
                    Editar venta
                    <?php endif;?>
                </button>
                <button style="display:none;" class="btn btn-rounded" id="submit4">Enviar</button>
            </div>
        </div>
    </form>
</div>
<?php endforeach;?>

<script src="<?php echo base_url(); ?>public/assets/js/impresora.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js"></script>
<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var user_type = '<?php echo $user_type;?>';
var rol = '<?php echo $rol;?>';
var addProduct = false;
var credito = false;

 $(document).ready(function () {
            $('input[name="credito"]').change(function () {
                if ($(this).is(':checked')) {
                    $('#metodo').removeAttr('required');
                     $('#resumen').hide();
                     credito = true;
                } else {
                    $('#metodo').attr('required', 'required');
                    $('#resumen').show();
                    credito = false;
                }
            });
        });
        
$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    $('#ms_error').hide();
    $('.add').hide();
    $('#thPrecioUni').hide();
    $('#codigoAuth').hide();
    <?php if($pro != ''):?>
    search();
    addOption('<?php echo $pro;?>');
    <?php endif;?>
    $('.efectivo').hide();
    $('.tarjeta').hide();
    $('.trans').hide();
    $('.cheque').hide();
    <?php if($rw['method'] != ''):?>
    metodo_pago(<?php echo $rw['method'];?>);
    <?php endif;?>
});

function search() {
    var name = $('#name_pr').val();
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/productSaleP',
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

function deletProdEdit(id,code,element) {
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/venta_editar/eliminar_producto/'+id+'/'+code,
        success: function(response) {
        $(element).parent().parent().remove();
         sum();
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

function addOption($product_id) 
{
    var productoss = $('#productoss-' + $product_id).val();
    if (productoss == $product_id) {
        var aumentar = parseFloat($('.aumentar-' + $product_id).val());
        if (aumentar < $('#max_vendidos-' + $product_id).val()) {
            $('.aumentar-' + $product_id).val(aumentar + 1);
            $('.aumentar-' + $product_id).focus();
            sum($product_id,$product_id);
        }
    } else {
        $('#list_products').show(500);
        if (!addProduct) {
            addProduct = true;
            var id = Math.floor(Math.random() * 300) + 10;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>admin/get_productosP/' + $product_id + '/' + id,
                success: function(response) {
                    addProduct = false;
                    $('#products').append(response);
                    $('#mensaje-' + $product_id).hide();
                    sum($product_id,$product_id);
                },
                beforeSend: function(){
                    addProduct = true;
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
    var pago = Number($('#pago').val());
    var cancelar = $('#ttl').val();
    $("#total_pagado").val(pago.toFixed(2));
    var total = pago - cancelar;
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

function sum(id, i, v) 
{
    $('#metodo').val('');
    var cantidad = $('#amount-' + i).val();
    var precio = $('#price-' + i).val();
    var precio_my = $('#price_my-' + i).val();
    var precio_farma = $('#price_farma-' + i).val();
    var descuento = $('#discount-' + i).val();
    var prPrice = $('#prPrice-' + i).val();
    $('#mensaje-' + i).queue(function(n) 
    {
        $.ajax({
              type: "POST",
              url: '<?php echo base_url();?>admin/compare_stock',
              data: "c="+cantidad+'|'+id,
              dataType: "html",
              error: function(){
              },
              success: function(data)
              { 
                if (data == "success")
                {            
                    $('#mensaje-' + i).hide(500);
                    $('#submit2').removeAttr('disabled');
                }
                else {
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
        if (parseFloat(precio) <= parseFloat(prPrice)) 
        {
            var diferencia      = parseFloat(prPrice) - parseFloat(precio);
            var newPorcentaje   = (diferencia / parseFloat(prPrice)) * 100;
            $('#discount-' + i).val(newPorcentaje.toFixed(2));
            descuento = newPorcentaje.toFixed(2);
        }
    }
    if(v == 3) 
    {
        if (parseFloat(descuento) >= 0) 
        {
            var des = parseFloat(prPrice) - (parseFloat(descuento) * parseFloat(prPrice)) / 100;
            $('#price-' + i).val(des.toFixed(2));
            precio = des.toFixed(2)
        }
    }
    
    var mul             = (parseFloat(cantidad) * parseFloat(precio));
    var des             = mul * (descuento / 100);
    var total           = mul;
    var precio_producto = $('#precioProducto-' + i).val();
    var pu = parseFloat(precio) - (parseFloat(precio) * (descuento / 100));
    var descuentos = $('#descuentos').val();

    var sumaDescuento = 0;
    $('.discount').each(function() 
    {
        sumaDescuento += parseFloat($(this).val());
    });

    if (sumaDescuento > 0 && descuentos == 0) 
    {
        $('.nueva_venta').attr('hidden', true);
        $('#codigoAuth').show(500);
    } 
    else 
    {
        $('.nueva_venta').removeAttr('hidden');
    }
    if (total < precio_producto) 
    {
        $('#mensaje-' + i).show(500);
        var COSTO = parseFloat(precio_producto);
        var PRECIO = parseFloat(prPrice);

        var TOTAL = ((PRECIO - COSTO) / COSTO) * 100;
        
        var ms =`<td><small class="text-danger" id="ms-descuento"> El costo del producto es  <b>${moneda}${precio_producto}</b> y el descuento es <b>${descuento}%</b> el cual te dará una ganancia negativa </small></td>`;
        $('#mensaje-' + i).html(ms);
    }
    else 
    {
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
    $('#txtx-' + i).html(total.toFixed(2));
 
    var mul_farma = (parseFloat(cantidad) * parseFloat(precio_farma));
    var des_farma = mul_farma * (descuento / 100);
    var total_farma = mul_farma - des_farma;

    $('#sub_farma-' + i).html(moneda + ' ' + total_farma.toFixed(2));
    $('#subt_farma-' + i).val(total_farma.toFixed(2));
    
    var suma = 0;
    var client_type = $("#client_type").val();
    if (client_type == '2') {
        $('.total').each(function() {
            suma += parseFloat($(this).val());
        });
    } else if (client_type == '1') {
        $('.total_my').each(function() {
            suma += parseFloat($(this).val());
        });
    } else if (client_type == '3') {
        $('.total_farma').each(function() {
            suma += parseFloat($(this).val());
        });
    }
    total = suma;
    sumTotal();
    /*var act = Number($('#precio_combo').val());
    $('#precio_combo').val(act+total);
    */
    aplicar();
    $('#total').html(moneda + total.toFixed(2));
    $('#total_a').html(moneda + total.toFixed(2));
    $('#ttl').val(total.toFixed(2));
    $('#pago').val(total.toFixed(2));
    $('#total_pagado').val(total.toFixed(2));
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
    
    function aplicar()
    {
        var vari = '';
        var all = $(".totalx").map(function() {
            return this.innerHTML;
        }).get();
        var totalxs = all.join();
        var salida = totalxs.split(',');
        var impresion = 0;
        for(i = 0; i < salida.length; i++)
        {
            impresion += Number(salida[i]);
        }
        $('#precio_combo').val('');
        $('#precio_combo').val(impresion);
        // console.log('Hola: '+impresion);   
    }
    
    function sumTotal() {
        var suma = 0; var total = 0; var amount = 0;
        var client_type = $("#client_type").val();
        $('.amount').each(function() {
            amount += parseFloat($(this).val());
        });
        if (client_type == '2') {
            $('.total').each(function() {
                suma += parseFloat($(this).val());
            });
        } else if (client_type == '1') {
            $('.total_my').each(function() {
                suma += parseFloat($(this).val());
            });
        } else if (client_type == '3') {
            $('.total_farma').each(function() {
                suma += parseFloat($(this).val());
            });
        }
        total = suma;
        $("#spnTotal").text(total.toFixed(2));
    }
    
    function submitform(value) {
        
        var fPago = $('#metodo').val();
        
        var elementos = document.getElementsByClassName("product_name").length;
        //console.log("fPago:", fPago, "elementos:", elementos, "User type:", user_type, "Rol:", rol, "URL:", url);
        if ($("#sale_form")[0].checkValidity()) {
           if(credito==false){
            if ((fPago != '' && (user_type == '1' || rol == '4') && elementos > 0) || (fPago == '' && user_type != '1' && (rol == '8'||rol == '10') && elementos > 0)) {
                $('.nueva_venta').attr('disabled', true);
                print_recibo_voucher(value);
            } else {
                $('#ms_error').show(500);
            }   
           }else{
             if (((user_type == '1' || rol == '4') && elementos > 0) || (user_type != '1' && (rol == '8'||rol == '10') && elementos > 0)) {
                $('.nueva_venta').attr('disabled', true);
                print_recibo_voucher(value);
            } else {
                $('#ms_error').show(500);
            }  
           }
        } else {
            $("#sale_form").attr("action", "javascript:void(0);");
            $("#submit4").click();
        }
    }

    function print_recibo_voucher(value) {
    <?php if($this->session->userdata('login_user_type') == '1'||$rol == 4 ):?>
    var url = '<?php echo base_url().'admin/venta_editar/generar_venta/'.$code;?>';
    var url2 = '<?php echo base_url().'admin/venta_editar/editar_venta/'.$code;?>';
    var url3 = '<?php echo base_url().'admin/venta_editar/facturar_venta/'.$code;?>';
    <?php else:?>
    var url = '<?php echo base_url().'admin/venta_editar/editar_venta/'.$code;?>';
    var url2 = '<?php echo base_url().'admin/venta_editar/editar_venta/'.$code;?>';
    <?php endif;?>
    
    if(value == 1){
        action_url = url2;
    }
    else if(value == 2){
        action_url = url3;
    }
    else{
        action_url = url
    }    
    //action_url = value == 1 ? url2 : url;
     console.log(action_url);
     $("#sale_form").attr("action", url);
        $.ajax({
            type: "POST",
            url: action_url,
            data: $("#sale_form").serialize(),
            beforeSend: function() {
                
            },
            success: function(response) {
                console.log("Response save sale:", response);
                //alert(response);
                if (response==0) {
                    window.open("<?php echo base_url();?>admin/export_pdf/voucher_sale/<?php echo $code;?>");
                }
                window.location.href = '<?php echo base_url();?>admin/ventas/';
            },
            error: function(e) {
                //alert(e);
            }
        });
    }

</script>

<script>
$(".form").bind("button", function() {
    $(this).find(':a[class=button]').prop('disabled', true);
});
</script>
