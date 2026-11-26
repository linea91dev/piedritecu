<!DOCTYPE html>
<html lang="es">

<head>
    <title>Cotización y asignación |
        <?php echo strtoupper($this->db->get_where('settings', array('type'=>'name'))->row()->description);?> </title>
    <meta charset="utf-8">
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include 'topcss.php';?>
    <style>
        .alloc-row td { vertical-align: middle !important; }
        .qty-ok { color: #1bc5bd; font-weight: 700; }
        .qty-bad { color: #f64e60; font-weight: 700; }
        #preview-providers { position: sticky; top: 20px; }
        .product-block { border: 1px solid #ebedf3; border-radius: 6px; padding: 12px; margin-bottom: 16px; background: #fcfcfd; }
    </style>
</head>
<?php
$moneda = $this->crud_model->get_info("moneda");
$this->crud_model->ensure_shopping_parent_code();
$data = $this->db->get_where('shopping', array('code' => $code));
$shopping_row = $data->row();
$is_admin = ($this->session->userdata('admin_login') == 1);
$providers = $this->db->order_by('name', 'ASC')->get_where('provider', array('status' => 1))->result_array();
$provider_options = '';
foreach ($providers as $prov) {
    $provider_options .= '<option value="'.$prov['provider_id'].'">'.htmlspecialchars($prov['name'], ENT_QUOTES).'</option>';
}
$pro = array();
if ($shopping_row && ($shopping_row->products != "" && $shopping_row->products != null)) {
    $pro = json_decode($shopping_row->products, true);
    if (!is_array($pro)) $pro = array();
}
$children = $shopping_row ? $this->crud_model->get_shopping_children($code) : array();
$already_split = !empty($children);
?>
<body id="kt_body" class="header-fixed header-mobile-fixed sidebar-enabled page-loading">
    <?php include 'mobile.php';?>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <?php include 'aside.php';?>
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <br><br>
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="d-flex flex-column-fluid">
                        <div class="container-fluid">
                            <?php if (!$shopping_row): ?>
                            <div class="alert alert-danger">Solicitud no encontrada.</div>
                            <?php else: ?>

                            <h3 class="mb-2">Cotización y asignación de compra</h3>
                            <p class="text-muted mb-4">
                                Asigna proveedor, cantidad y precio por producto. Puedes partir cantidades entre varios proveedores.
                                Al confirmar se generan <b>subórdenes</b> y se conserva el historial de la solicitud madre.
                            </p>

                            <?php if ($this->session->flashdata('flash_message')): ?>
                            <div class="alert alert-success"><?php echo $this->session->flashdata('flash_message');?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error_message')): ?>
                            <div class="alert alert-danger"><?php echo $this->session->flashdata('error_message');?></div>
                            <?php endif; ?>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4"><b>Código:</b> <?php echo $code;?></div>
                                        <div class="col-md-4"><b>Encargado:</b> <?php echo ucwords($this->crud_model->getName('admin',$shopping_row->responsable));?></div>
                                        <div class="col-md-4"><b>Fecha solicitud:</b>
                                            <?php
                                            setlocale(LC_TIME, "spanish");
                                            $Nueva_Fecha = date("d-m-Y", strtotime($shopping_row->date_request));
                                            echo strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($already_split): ?>
                            <div class="alert alert-warning">
                                Esta solicitud ya fue dividida. Subórdenes:
                                <?php foreach ($children as $ch): ?>
                                <a href="<?php echo base_url().'admin/detalles_compra/'.$ch['code'];?>" class="font-weight-bold mr-2">
                                    <?php echo $ch['code'];?>
                                </a>
                                <?php endforeach; ?>
                                <div class="mt-2">
                                    <a href="<?php echo base_url().'admin/detalles_compra/'.$code;?>" class="btn btn-sm btn-light-primary">Ver solicitud madre</a>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($is_admin && (int)$shopping_row->type !== 1): ?>
                            <form method="POST" action="<?php echo base_url();?>provider/detalles_compra/split_confirm" id="splitForm" onsubmit="return validateSplit();">
                                <input type="hidden" name="code" value="<?php echo $code;?>">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-label text-info">Productos a asignar</h3>
                                                <div class="alert alert-info">
                                                    La suma de cantidades por producto debe igualar exactamente la cantidad solicitada.
                                                </div>

                                                <?php
                                                for ($i = 0; $i < count($pro); $i++):
                                                    $prod_id = $pro[$i]['product'];
                                                    $prod_row = $this->db->get_where('products', array('products_id' => $prod_id))->row();
                                                    $prod_name = $prod_row ? $prod_row->name : ('Producto '.$prod_id);
                                                    $req_qty = floatval($pro[$i]['amount']);
                                                    $default_price = isset($pro[$i]['price_buy']) ? floatval($pro[$i]['price_buy']) : 0;
                                                ?>
                                                <div class="product-block" data-product-index="<?php echo $i;?>" data-product-id="<?php echo $prod_id;?>" data-req="<?php echo $req_qty;?>">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <b><?php echo htmlspecialchars($prod_name);?></b>
                                                            <div class="text-muted">Solicitado: <span class="req-qty"><?php echo number_format($req_qty, 2, '.', '');?></span></div>
                                                        </div>
                                                        <div>
                                                            Asignado: <span class="assigned-qty qty-bad" id="assigned-<?php echo $i;?>">0.00</span>
                                                            <button type="button" class="btn btn-sm btn-light-primary ml-2" onclick="addAlloc(<?php echo $i;?>)">+ Asignación</button>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Proveedor</th>
                                                                    <th style="width:110px">Cantidad</th>
                                                                    <th style="width:120px">P/U</th>
                                                                    <th style="width:120px">Subtotal</th>
                                                                    <th style="width:50px">-</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="alloc-body" id="alloc-body-<?php echo $i;?>">
                                                                <tr class="alloc-row">
                                                                    <td>
                                                                        <input type="hidden" name="product[]" value="<?php echo $prod_id;?>">
                                                                        <select class="form-control form-control-sm provider-select" name="provider[]" required>
                                                                            <option value="">Seleccionar</option>
                                                                            <?php echo $provider_options; ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="any" min="0.01" class="form-control form-control-sm amount-input" name="amount[]" value="<?php echo number_format($req_qty, 2, '.', '');?>" required oninput="recalcProduct(<?php echo $i;?>)">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm price-input" name="price_buy[]" value="<?php echo number_format($default_price, 2, '.', '');?>" required oninput="recalcProduct(<?php echo $i;?>)">
                                                                    </td>
                                                                    <td>
                                                                        <span class="sub-label"><?php echo $moneda;?>0.00</span>
                                                                        <input type="hidden" class="sub-input" name="sub[]" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-light-danger" onclick="removeAlloc(this, <?php echo $i;?>)">x</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <?php endfor; ?>

                                                <?php if (count($pro) == 0): ?>
                                                <div class="text-center p-5 text-muted">Sin productos en la solicitud.</div>
                                                <?php endif; ?>

                                                <div class="text-right mt-4">
                                                    <button type="submit" class="btn btn-warning font-weight-bolder" <?php if($already_split) echo 'onclick="return confirm(\'Ya existen subórdenes. Se regenerarán las no completadas. ¿Continuar?\');"';?>>
                                                        Confirmar y generar subórdenes
                                                    </button>
                                                    <a href="<?php echo base_url().'admin/detalles_compra/'.$code;?>" class="btn btn-light font-weight-bolder">Cancelar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card" id="preview-providers">
                                            <div class="card-header border-0 pt-5">
                                                <h3 class="card-title font-weight-bolder">Previsualización subórdenes</h3>
                                            </div>
                                            <div class="card-body pt-2">
                                                <p class="text-muted">Totales agrupados por proveedor.</p>
                                                <div id="preview-body">
                                                    <div class="text-muted">Sin asignaciones aún</div>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <b>Total general</b>
                                                    <b id="preview-total"><?php echo $moneda;?>0.00</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php elseif (!$is_admin): ?>
                            <div class="alert alert-warning">Debes iniciar sesión como administrador/encargado de compras para asignar proveedores y generar subórdenes.</div>
                            <?php else: ?>
                            <div class="alert alert-secondary">Esta compra ya está completada.</div>
                            <?php endif; ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php include 'footer.php';?>
            </div>
            <?php include 'sidebar-left.php';?>
        </div>
    </div>
    <?php include 'notifications.php';?>
    <?php include 'scripts.php';?>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';
var providerOptionsHtml = <?php echo json_encode('<option value="">Seleccionar</option>'.$provider_options); ?>;

function addAlloc(productIndex) {
    var $block = $('.product-block[data-product-index="'+productIndex+'"]');
    var productId = $block.data('product-id');
    var html = '<tr class="alloc-row">' +
        '<td><input type="hidden" name="product[]" value="'+productId+'">' +
        '<select class="form-control form-control-sm provider-select" name="provider[]" required>'+providerOptionsHtml+'</select></td>' +
        '<td><input type="number" step="any" min="0.01" class="form-control form-control-sm amount-input" name="amount[]" value="0" required oninput="recalcProduct('+productIndex+')"></td>' +
        '<td><input type="number" step="0.01" min="0" class="form-control form-control-sm price-input" name="price_buy[]" value="0" required oninput="recalcProduct('+productIndex+')"></td>' +
        '<td><span class="sub-label">'+moneda+'0.00</span><input type="hidden" class="sub-input" name="sub[]" value="0"></td>' +
        '<td><button type="button" class="btn btn-sm btn-light-danger" onclick="removeAlloc(this, '+productIndex+')">x</button></td>' +
        '</tr>';
    $('#alloc-body-'+productIndex).append(html);
    recalcProduct(productIndex);
}

function removeAlloc(btn, productIndex) {
    var $body = $('#alloc-body-'+productIndex);
    if ($body.find('.alloc-row').length <= 1) {
        alert('Debe quedar al menos una asignación por producto.');
        return;
    }
    $(btn).closest('tr').remove();
    recalcProduct(productIndex);
}

function recalcProduct(productIndex) {
    var $block = $('.product-block[data-product-index="'+productIndex+'"]');
    var req = parseFloat($block.data('req')) || 0;
    var assigned = 0;
    $block.find('.alloc-row').each(function() {
        var qty = parseFloat($(this).find('.amount-input').val()) || 0;
        var price = parseFloat($(this).find('.price-input').val()) || 0;
        var sub = qty * price;
        assigned += qty;
        $(this).find('.sub-label').text(moneda + sub.toFixed(2));
        $(this).find('.sub-input').val(sub.toFixed(2));
    });
    var $lab = $('#assigned-'+productIndex);
    $lab.text(assigned.toFixed(2));
    if (Math.abs(assigned - req) < 0.0001) {
        $lab.removeClass('qty-bad').addClass('qty-ok');
    } else {
        $lab.removeClass('qty-ok').addClass('qty-bad');
    }
    updatePreview();
}

function updatePreview() {
    var map = {};
    var grand = 0;
    $('.alloc-row').each(function() {
        var provid = $(this).find('.provider-select').val();
        var pname = $(this).find('.provider-select option:selected').text();
        var sub = parseFloat($(this).find('.sub-input').val()) || 0;
        if (!provid) return;
        if (!map[provid]) map[provid] = { name: pname, total: 0 };
        map[provid].total += sub;
        grand += sub;
    });
    var html = '';
    Object.keys(map).forEach(function(k) {
        html += '<div class="d-flex justify-content-between mb-2"><span>'+map[k].name+'</span><b>'+moneda+map[k].total.toFixed(2)+'</b></div>';
    });
    if (!html) html = '<div class="text-muted">Sin asignaciones aún</div>';
    $('#preview-body').html(html);
    $('#preview-total').text(moneda + grand.toFixed(2));
}

function validateSplit() {
    var ok = true;
    var msg = '';
    $('.product-block').each(function() {
        var req = parseFloat($(this).data('req')) || 0;
        var idx = $(this).data('product-index');
        var assigned = 0;
        $(this).find('.amount-input').each(function() {
            assigned += parseFloat($(this).val()) || 0;
        });
        if (Math.abs(assigned - req) > 0.0001) {
            ok = false;
            msg = 'Hay productos cuya cantidad asignada no coincide con la solicitada.';
        }
        $(this).find('.provider-select').each(function() {
            if (!$(this).val()) {
                ok = false;
                msg = 'Selecciona proveedor en todas las asignaciones.';
            }
        });
    });
    if (!ok) {
        alert(msg);
        return false;
    }
    return confirm('¿Generar las subórdenes por proveedor?');
}

$(document).ready(function() {
    $('.product-block').each(function() {
        recalcProduct($(this).data('product-index'));
    });
    $(document).on('change', '.provider-select', updatePreview);
});
</script>
</body>
</html>
