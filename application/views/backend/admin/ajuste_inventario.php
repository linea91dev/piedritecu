<?php
$code = $this->crud_model->getCodeVenta();
$moneda = $this->crud_model->get_info("moneda");
if (!isset($precio) || $precio == '') $precio = 1;
?>
<style>
.producto-input {
    width: 100% !important;
    min-width: 200px;
    box-sizing: border-box;
}
</style>
<div class="container-fluid">
    <form method="POST" action="<?php echo base_url();?>admin/ajuste_inventario/">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <br>
                        <div class="form-group">
                            <span><b>Lista de precio</b></span>
                            <select class="form-control" name="precio_t" required id="precio_t">
                                <option value="1" <?php if($precio==1) echo 'selected';?>>Público</option>
                                <option value="2" <?php if($precio==2) echo 'selected';?>>Socio</option>
                                <option value="3" <?php if($precio==3) echo 'selected';?>>Mayorista</option>
                                <option value="4" <?php if($precio==4) echo 'selected';?>>Ferretero</option>
                            </select>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" class="btn btn-rounded btn-success" value="Aplicar lista" />
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form class="form" action="<?php echo base_url().'admin/ajuste_inventario/create/'.$code;?>" method="POST" enctype="multipart/form-data" id="ajusteForm">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    Ingresa el <b>conteo físico</b> de cada producto. La diferencia (existencia − conteo) se descontará del inventario de bodega y se registrará como ajuste.
                    Por defecto el conteo es igual a la existencia (sin cambios).
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value="<?php echo $code;?>">
                                <span>Código de ajuste: <b><?php echo $code;?></b>
                                    <span style="float:right"><b>Fecha:</b>
                                        <?php echo date('d/m/Y');?>
                                    </span>
                                </span>
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Cliente <span class="text-danger">*</span></label>
                                            <select class="form-control" name="client_id" id="selected-2" required>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                $providers = $this->db->order_by('name', 'ASC')->get_where('client', array('status'=>1,'branch_id'=>$this->session->userdata('branch_id')));
                                                foreach ($providers->result_array() as $provider):?>
                                                <option value="<?php echo $provider['client_id'];?>">
                                                    <?php echo $provider['name'].' '.$provider['last_name'];?>
                                                </option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row" id="resumen">
                                            <div class="col-sm-12">
                                                <span><b>Forma de pago:</b> <span class="text-danger">*</span></span>
                                                <div class="border-bottom"></div><br>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Método:</label>
                                                    <select class="form-control" id="metodo" name="metodo" required onchange="metodo_pago(this.value)">
                                                        <option value="Efectivo" selected>Efectivo</option>
                                                        <option value="Tarjeta">Tarjeta de crédito / débito</option>
                                                        <option value="Transferencia">Transferencia / Depósito</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 efectivo">
                                                <div class="form-group">
                                                    <label>Pago con:</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" name="pago" id="pago" oninput="cambio()" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 efectivo">
                                                <div class="form-group">
                                                    <label>Cambio:</label><br>
                                                    <span class="font-weight-boldest font-size-h6 line-height-sm" id="change"><?php echo $moneda;?>0.00</span>
                                                    <input type="hidden" value="0" class="form-control" id="changee" name="change" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 tarjeta">
                                                <div class="form-group">
                                                    <label>Voucher</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" placeholder="Ingrese el voucher" name="voucher" id="voucher" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 trans">
                                                <div class="form-group">
                                                    <label>No. Transferencia</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" placeholder="Ingrese el # de boleta" name="trans" id="trans" />
                                                </div>
                                                <div class="form-group">
                                                    <label><b>Origen:</b></label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="origin" id="origin">
                                                            <?php $cuentas = $this->crud_model->get_accounts_to_pay();
                                                            foreach($cuentas->result_array() as $cts):?>
                                                            <option value="<?php echo $cts['account_bank_id'];?>">
                                                                <?php if($cts['bank_id'] > 0) echo '('.$this->db->get_where('bank', array('bank_id' => $cts['bank_id']))->row()->name.') - '; echo $cts['name_account'];?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 cheque">
                                                <div class="form-group">
                                                    <label>No. Cheque</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" placeholder="Ingrese el # de cheque" name="cheque" id="cheque" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Responsable:</b></label>
                                            <input type="text" class="form-control" name="responsable" required value="<?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><b>Fecha:</b></label>
                                            <input type="text" class="form-control" required name="fecha" readonly id="kt_datepicker" value="<?php echo date('m/d/Y');?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label><b>Nota:</b></label>
                                            <textarea class="form-control" name="nota" required placeholder="Motivo del ajuste de inventario"></textarea>
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
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-label text-info text-center">PRODUCTOS A AJUSTAR</h3>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Existencia</th>
                                        <th>Conteo físico</th>
                                        <th>Diferencia</th>
                                        <th>Precio unitario</th>
                                        <th>Subtotal</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="products">
                                    <?php
                                    $productos = $this->db
                                        ->order_by('code', 'ASC')
                                        ->get_where('products', array('status' => 1, 'presentation' => 'Unidad'))
                                        ->result_array();
                                    $num_products = 0;
                                    $i = 0;
                                    foreach ($productos as $pp):
                                        $stock_inventory = $this->crud_model->get_stock($pp['products_id'], 0);
                                        if ($stock_inventory <= 0) continue;
                                        $i++;
                                        $num_products = $i;
                                        if ($precio == 1) $precio_p = $pp['price'];
                                        elseif ($precio == 2) $precio_p = $pp['farma'];
                                        elseif ($precio == 3) $precio_p = $pp['precio_mayorista'];
                                        else $precio_p = isset($pp['precio_ferretero']) ? $pp['precio_ferretero'] : $pp['price'];
                                    ?>
                                    <tr id="producto-<?php echo $i; ?>">
                                        <td style="width: 260px;">
                                            <input class="form-control" type="text" id="prod-<?php echo $i;?>" name="productos[]" value="<?php echo htmlspecialchars($pp['name']);?>" readonly>
                                            <input type="hidden" id="prodId-<?php echo $i;?>" name="product[]" value="<?php echo $pp['products_id'];?>">
                                        </td>
                                        <td>
                                            <span><?php echo number_format($stock_inventory, 2, '.', '');?></span>
                                            <input type="hidden" id="prodIdStock-<?php echo $i;?>" name="prodIdStock[]" value="<?php echo $stock_inventory;?>">
                                        </td>
                                        <td>
                                            <input min="0" max="<?php echo $stock_inventory;?>" class="form-control" type="number" style="width:90px" step="any" id="amount_desc-<?php echo $i; ?>" name="amount_desc[]" value="<?php echo $stock_inventory;?>" oninput="sum_act('<?php echo $i;?>')" onblur="sum_act('<?php echo $i;?>')">
                                        </td>
                                        <td>
                                            <input min="0" class="form-control" type="number" style="width:90px" step="any" id="amount-<?php echo $i; ?>" name="amount[]" value="0" readonly>
                                        </td>
                                        <td>
                                            <input min="0" class="form-control" type="number" style="width:110px" step="any" id="price-<?php echo $i;?>" name="price[]" value="<?php echo $precio_p;?>" oninput="sum('<?php echo $i;?>')" onblur="sum('<?php echo $i;?>')">
                                            <input type="hidden" name="discount[]" id="discount-<?php echo $i;?>" value="0">
                                        </td>
                                        <td>
                                            <span class="text-success" id="sub-<?php echo $i;?>"><?php echo $moneda;?>0.00</span>
                                            <input type="hidden" class="total" name="sub[]" id="subt-<?php echo $i;?>" value="0">
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
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($num_products == 0):?>
                        <div class="text-center p-5">
                            <h4>No hay productos con existencia en bodega</h4>
                        </div>
                        <?php endif;?>
                        <hr>
                        <div class="col-md-12">
                            <div class="bg-primary rounded d-flex justify-content-between text-white position-relative ml-auto p-7">
                                <div class="font-weight-boldest font-size-h5">TOTAL AJUSTE</div>
                                <div class="text-right d-flex flex-column">
                                    <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda;?>0.00</span>
                                    <input type="hidden" name="ttl" id="ttl" value="0">
                                    <input type="hidden" name="dsc" id="dsc" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <hr>
                            <button class="btn btn-light-warning font-weight-bolder" type="submit" style="float: right;" <?php if($num_products==0) echo 'disabled';?>>
                                Registrar ajuste
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$(document).ready(function() {
    metodo_pago('Efectivo');
    <?php for ($j=1; $j <= $num_products ; $j++):?>
    sum_act('<?php echo $j;?>');
    <?php endfor;?>
});

$('#ajusteForm').on('keydown', function(e) {
    if (e.key === 'Enter' && e.target.type !== 'textarea') {
        e.preventDefault();
    }
});

function sum_act(i) {
    var existencia = parseFloat($('#prodIdStock-' + i).val()) || 0;
    var conteo = parseFloat($('#amount_desc-' + i).val());
    if (isNaN(conteo)) conteo = existencia;
    if (conteo < 0) conteo = 0;
    if (conteo > existencia) {
        conteo = existencia;
        $('#amount_desc-' + i).val(existencia);
    }
    var diferencia = existencia - conteo;
    if (diferencia < 0) diferencia = 0;
    $('#amount-' + i).val(diferencia.toFixed(2));
    sum(i);
}

function sum(i) {
    var cantidad = parseFloat($('#amount-' + i).val()) || 0;
    var precio = parseFloat($('#price-' + i).val()) || 0;
    var total = cantidad * precio;

    $('#sub-' + i).html(moneda + total.toFixed(2));
    $('#subt-' + i).val(total.toFixed(2));

    var suma = 0;
    $('.total').each(function() {
        var v = parseFloat($(this).val());
        if (!isNaN(v)) suma += v;
    });
    $('#total').html(moneda + suma.toFixed(2));
    $('#ttl').val(suma.toFixed(2));
    if ($('#metodo').val() == 'Efectivo') {
        $('#pago').val(suma.toFixed(2));
        cambio();
    }
}

function removeOption(i) {
    $('#producto-' + i).remove();
    sum(i);
}

function metodo_pago(value) {
    $('.efectivo').hide();
    $('.tarjeta').hide();
    $('.trans').hide();
    $('.cheque').hide();
    var ttl = $('#ttl').val();

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
    var pago = parseFloat($('#pago').val()) || 0;
    var cancelar = parseFloat($('#ttl').val()) || 0;
    var total = pago - cancelar;
    if (pago > 0 && total >= 0) {
        $('#changee').val(total.toFixed(2));
        $('#change').html(moneda + total.toFixed(2));
    } else {
        $('#changee').val('0');
        $('#change').html(moneda + '0.00');
    }
}
</script>
