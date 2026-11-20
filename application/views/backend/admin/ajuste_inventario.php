<?php
$code = $this->crud_model->getCodeVenta();
$moneda = $this->crud_model->get_info("moneda");
if (!isset($precio) || $precio == '') $precio = 1;
if (!isset($ajuste_products) || !is_array($ajuste_products)) {
    $ajuste_products = $this->crud_model->get_products_for_ajuste_inventario();
}
$num_products = count($ajuste_products);
?>
<style>
.producto-input { width: 100% !important; min-width: 200px; box-sizing: border-box; }
#preview-panel { position: sticky; top: 80px; max-height: calc(100vh - 120px); overflow: auto; }
#preview-body tr.preview-zero { display: none; }
.table-ajuste td, .table-ajuste th { vertical-align: middle !important; }
.diff-pos { color: #f64e60; font-weight: 700; }
.after-stock { color: #1bc5bd; font-weight: 700; }
.row-filtered-out { display: none !important; }
</style>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url().'admin/ajuste_inventario/create/'.$code;?>" method="POST" enctype="multipart/form-data" id="ajusteForm">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    Ingresa el <b>conteo físico</b> de cada producto. La diferencia (existencia − conteo) se descontará del inventario de bodega.
                    Por defecto el conteo es igual a la existencia (sin cambios). Usa la búsqueda para ubicar productos.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="code" value="<?php echo $code;?>">
                                <span>Código de ajuste: <b><?php echo $code;?></b>
                                    <span style="float:right"><b>Fecha:</b> <?php echo date('d/m/Y');?></span>
                                </span>
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Lista de precio</label>
                                            <select class="form-control" name="precio_t" id="precio_t" onchange="aplicarListaPrecio()">
                                                <option value="1" <?php if($precio==1) echo 'selected';?>>Público</option>
                                                <option value="2" <?php if($precio==2) echo 'selected';?>>Socio</option>
                                                <option value="3" <?php if($precio==3) echo 'selected';?>>Mayorista</option>
                                                <option value="4" <?php if($precio==4) echo 'selected';?>>Ferretero</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Venta al crédito</label>
                                            <span class="switch switch-sm d-block mt-2">
                                                <label>
                                                    <input type="checkbox" name="credito" id="credito" value="1" onchange="toggleCredito()" />
                                                    <span></span>
                                                </label>
                                            </span>
                                            <small class="text-muted">Si se activa, el ajuste queda como crédito del cliente.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="pago-box">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Método de pago <span class="text-danger pago-req">*</span></label>
                                                    <select class="form-control" id="metodo" name="metodo" required onchange="metodo_pago(this.value)">
                                                        <option value="Efectivo" selected>Efectivo</option>
                                                        <option value="Tarjeta">Tarjeta de crédito / débito</option>
                                                        <option value="Transferencia">Transferencia / Depósito</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 efectivo">
                                                <div class="form-group">
                                                    <label>Pago con:</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" name="pago" id="pago" oninput="cambio()" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 efectivo">
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
                                            <div class="col-lg-12 cheque">
                                                <div class="form-group">
                                                    <label>No. Cheque</label>
                                                    <input type="number" value="" min="0" step="any" class="form-control" placeholder="Ingrese el # de cheque" name="cheque" id="cheque" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="credito-abono" style="display:none;">
                                        <div class="alert alert-light-info mb-3">
                                            Crédito activado. Puedes registrar un <b>abono inicial</b> opcional; el resto queda pendiente.
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Método del abono</label>
                                                    <select class="form-control" id="metodo_credito" onchange="metodo_pago(this.value)">
                                                        <option value="Efectivo" selected>Efectivo</option>
                                                        <option value="Tarjeta">Tarjeta</option>
                                                        <option value="Transferencia">Transferencia</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Abono inicial</label>
                                                    <input type="number" value="0" min="0" step="any" class="form-control" name="pago_credito" id="pago_credito" oninput="syncPagoCredito()" />
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

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h3 class="card-label text-info mb-0">PRODUCTOS A AJUSTAR</h3>
                            <div class="d-flex flex-wrap" style="gap:8px;">
                                <input type="text" class="form-control" id="buscarProducto" placeholder="Buscar por nombre o código..." style="min-width:240px;">
                                <label class="checkbox checkbox-success mt-2 mb-0">
                                    <input type="checkbox" id="soloDiferencias" onchange="filtrarProductos()">
                                    <span></span> Solo con diferencia
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-padded table-ajuste">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Existencia</th>
                                        <th>Conteo físico</th>
                                        <th>Diferencia</th>
                                        <th>Después</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="products">
                                    <?php
                                    $i = 0;
                                    foreach ($ajuste_products as $pp):
                                        $i++;
                                        $stock_inventory = floatval($pp['stock']);
                                        $precio_p = $pp['price'];
                                        if ($precio == 2) $precio_p = $pp['farma'];
                                        elseif ($precio == 3) $precio_p = $pp['precio_mayorista'];
                                        elseif ($precio == 4) $precio_p = isset($pp['precio_ferretero']) ? $pp['precio_ferretero'] : $pp['price'];
                                        $search_key = strtolower($pp['name'].' '.$pp['code']);
                                    ?>
                                    <tr id="producto-<?php echo $i; ?>" class="producto-row"
                                        data-search="<?php echo htmlspecialchars($search_key, ENT_QUOTES); ?>"
                                        data-price1="<?php echo floatval($pp['price']); ?>"
                                        data-price2="<?php echo floatval($pp['farma']); ?>"
                                        data-price3="<?php echo floatval($pp['precio_mayorista']); ?>"
                                        data-price4="<?php echo floatval(isset($pp['precio_ferretero']) ? $pp['precio_ferretero'] : $pp['price']); ?>">
                                        <td style="min-width:220px;">
                                            <input class="form-control" type="text" id="prod-<?php echo $i;?>" name="productos[]" value="<?php echo htmlspecialchars($pp['name']);?>" readonly>
                                            <small class="text-muted"><?php echo htmlspecialchars($pp['code']);?></small>
                                            <input type="hidden" id="prodId-<?php echo $i;?>" name="product[]" value="<?php echo $pp['products_id'];?>">
                                        </td>
                                        <td>
                                            <span id="exist-label-<?php echo $i;?>"><?php echo number_format($stock_inventory, 2, '.', '');?></span>
                                            <input type="hidden" id="prodIdStock-<?php echo $i;?>" name="prodIdStock[]" value="<?php echo $stock_inventory;?>">
                                        </td>
                                        <td>
                                            <input min="0" max="<?php echo $stock_inventory;?>" class="form-control" type="number" style="width:90px" step="any" id="amount_desc-<?php echo $i; ?>" name="amount_desc[]" value="<?php echo $stock_inventory;?>" oninput="sum_act('<?php echo $i;?>')" onblur="sum_act('<?php echo $i;?>')">
                                        </td>
                                        <td>
                                            <input min="0" class="form-control" type="number" style="width:90px" step="any" id="amount-<?php echo $i; ?>" name="amount[]" value="0" readonly>
                                        </td>
                                        <td>
                                            <span class="after-stock" id="after-<?php echo $i;?>"><?php echo number_format($stock_inventory, 2, '.', '');?></span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
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
                        <div class="bg-primary rounded d-flex justify-content-between text-white p-7">
                            <div class="font-weight-boldest font-size-h5">TOTAL AJUSTE</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda;?>0.00</span>
                                <input type="hidden" name="ttl" id="ttl" value="0">
                                <input type="hidden" name="dsc" id="dsc" value="0">
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            <button class="btn btn-light-warning font-weight-bolder" type="submit" id="btnRegistrar" <?php if($num_products==0) echo 'disabled';?>>
                                Registrar ajuste
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card" id="preview-panel">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder text-dark">Previsualización</h3>
                    </div>
                    <div class="card-body pt-2">
                        <p class="text-muted mb-3">Así quedará el inventario de bodega después del ajuste.</p>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="text-muted font-size-sm">Productos afectados</div>
                                <div class="font-weight-bolder font-size-h4" id="previewCount">0</div>
                            </div>
                            <div class="text-right">
                                <div class="text-muted font-size-sm">Unidades a descontar</div>
                                <div class="font-weight-bolder font-size-h4 diff-pos" id="previewUnits">0</div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Antes</th>
                                        <th>Después</th>
                                        <th>Δ</th>
                                    </tr>
                                </thead>
                                <tbody id="preview-body">
                                    <tr id="preview-empty">
                                        <td colspan="4" class="text-center text-muted">Sin cambios todavía</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var previewTimer = null;

$(document).ready(function() {
    metodo_pago('Efectivo');
    $('#buscarProducto').on('input', filtrarProductos);
    actualizarPreview();
});

function toggleCredito() {
    var esCredito = $('#credito').is(':checked');
    if (esCredito) {
        $('#pago-box').hide(300);
        $('#credito-abono').show(300);
        $('#metodo').removeAttr('required');
        $('#pago, #voucher, #trans, #cheque').removeAttr('required');
        $('.pago-req').hide();
        syncPagoCredito();
    } else {
        $('#credito-abono').hide(300);
        $('#pago-box').show(300);
        $('#metodo').attr('required', true);
        $('.pago-req').show();
        metodo_pago($('#metodo').val());
        var ttl = $('#ttl').val();
        $('#pago').val(ttl);
        cambio();
    }
}

function syncPagoCredito() {
    var abono = parseFloat($('#pago_credito').val()) || 0;
    var metodo = $('#metodo_credito').val();
    $('#metodo').val(metodo);
    $('#pago').val(abono.toFixed(2));
    $('#changee').val('0');
    metodo_pago(metodo);
}

$('#ajusteForm').on('keydown', function(e) {
    if (e.key === 'Enter' && e.target.type !== 'textarea') {
        e.preventDefault();
    }
});

function aplicarListaPrecio() {
    var lista = $('#precio_t').val();
    $('.producto-row').each(function() {
        var $row = $(this);
        var id = $row.attr('id').replace('producto-', '');
        var precio = parseFloat($row.data('price' + lista)) || 0;
        $('#price-' + id).val(precio);
        sum(id);
    });
}

function filtrarProductos() {
    var q = ($('#buscarProducto').val() || '').toLowerCase().trim();
    var soloDiff = $('#soloDiferencias').is(':checked');
    $('.producto-row').each(function() {
        var $row = $(this);
        var id = $row.attr('id').replace('producto-', '');
        var matchSearch = !q || (($row.data('search') || '').indexOf(q) !== -1);
        var diff = parseFloat($('#amount-' + id).val()) || 0;
        var matchDiff = !soloDiff || diff > 0;
        $row.toggleClass('row-filtered-out', !(matchSearch && matchDiff));
    });
}

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
    $('#after-' + i).text((existencia - diferencia).toFixed(2));
    sum(i);
    schedulePreview();
    if ($('#soloDiferencias').is(':checked')) filtrarProductos();
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

function schedulePreview() {
    if (previewTimer) clearTimeout(previewTimer);
    previewTimer = setTimeout(actualizarPreview, 120);
}

function actualizarPreview() {
    var html = '';
    var count = 0;
    var units = 0;
    $('.producto-row').each(function() {
        var $row = $(this);
        var id = $row.attr('id').replace('producto-', '');
        var existencia = parseFloat($('#prodIdStock-' + id).val()) || 0;
        var diferencia = parseFloat($('#amount-' + id).val()) || 0;
        if (diferencia <= 0) return;
        var despues = existencia - diferencia;
        var nombre = $('#prod-' + id).val();
        count++;
        units += diferencia;
        html += '<tr>' +
            '<td>' + $('<div>').text(nombre).html() + '</td>' +
            '<td>' + existencia.toFixed(2) + '</td>' +
            '<td class="after-stock">' + despues.toFixed(2) + '</td>' +
            '<td class="diff-pos">-' + diferencia.toFixed(2) + '</td>' +
            '</tr>';
    });
    if (!html) {
        html = '<tr id="preview-empty"><td colspan="4" class="text-center text-muted">Sin cambios todavía</td></tr>';
    }
    $('#preview-body').html(html);
    $('#previewCount').text(count);
    $('#previewUnits').text(units.toFixed(2));
    $('#btnRegistrar').prop('disabled', count === 0 && <?php echo $num_products; ?> === 0);
}

function removeOption(i) {
    $('#producto-' + i).remove();
    var suma = 0;
    $('.total').each(function() {
        var v = parseFloat($(this).val());
        if (!isNaN(v)) suma += v;
    });
    $('#total').html(moneda + suma.toFixed(2));
    $('#ttl').val(suma.toFixed(2));
    schedulePreview();
    filtrarProductos();
}

function metodo_pago(value) {
    $('.efectivo, .tarjeta, .trans, .cheque').hide();
    $('#pago, #voucher, #trans, #cheque').removeAttr('required');
    var ttl = $('#ttl').val();

    if (value == 'Efectivo') {
        $('.efectivo').show(300);
        $('#pago').attr('required', true).val(ttl);
    } else if (value == 'Tarjeta') {
        $('.tarjeta').show(300);
        $('#voucher').attr('required', true);
        $('#pago').val(ttl);
    } else if (value == 'Transferencia') {
        $('.trans').show(300);
        $('#trans').attr('required', true);
        $('#pago').val(ttl);
    } else if (value == 'Cheque') {
        $('.cheque').show(300);
        $('#cheque').attr('required', true);
        $('#pago').val(ttl);
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
