<?php
$status_label = array(0 => 'Anulado', 1 => 'En captura', 2 => 'Pendiente autorización', 3 => 'Aplicado');
$status_badge = array(0 => 'secondary', 1 => 'info', 2 => 'warning', 3 => 'success');
$st = (int)$conteo->status;
$editable = ($st === 1 || $st === 2);
$is_admin = ((int)$this->session->userdata('login_user_type') === 1);
$counted = 0;
$pending = 0;
$diffs = 0;
foreach ($detalle as $d) {
    if ((int)$d['counted'] === 1) {
        $counted++;
        if (abs(floatval($d['diferencia'])) > 0.0001) $diffs++;
    } else {
        $pending++;
    }
}
?>
<style>
.diff-neg { color:#f64e60; font-weight:700; }
.diff-pos { color:#1bc5bd; font-weight:700; }
.row-ok td { background:#f3faf8; }
.row-diff td { background:#fff5f5; }
</style>
<div class="container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <a href="<?php echo base_url();?>admin/ajuste_inventario/" class="btn btn-light-primary font-weight-bolder mb-2">Regresar</a>
            <h3 class="mb-1">Conteo <?php echo $conteo->code;?></h3>
            <div class="text-muted">
                <?php echo htmlspecialchars($conteo->scope_label);?> ·
                <?php echo date('d/m/Y H:i', strtotime($conteo->datetime));?> ·
                <span class="badge badge-<?php echo $status_badge[$st];?>"><?php echo $status_label[$st];?></span>
            </div>
        </div>
        <div class="text-right">
            <div><b><?php echo $counted;?></b> / <?php echo count($detalle);?> contados</div>
            <div class="text-muted"><?php echo $pending;?> pendientes · <?php echo $diffs;?> con diferencia</div>
        </div>
    </div>

    <?php if ($st === 2): ?>
    <div class="alert alert-warning">
        Este conteo está <b>pendiente de autorización</b>. El inventario aún no se ha modificado.
        <?php if ($is_admin): ?>Un administrador puede autorizarlo abajo.<?php endif; ?>
    </div>
    <?php elseif ($st === 3): ?>
    <div class="alert alert-success">
        Conteo aplicado el <?php echo $conteo->authorized_at ? date('d/m/Y H:i', strtotime($conteo->authorized_at)) : '-';?>
        por <?php echo $conteo->authorized_by ? $this->crud_model->getName('admin', $conteo->authorized_by) : '-';?>.
    </div>
    <?php elseif ($st === 0): ?>
    <div class="alert alert-secondary">Conteo anulado. No afecta inventario.</div>
    <?php else: ?>
    <div class="alert alert-info">
        Captura el conteo físico. Guarda borrador cuantas veces necesites. Al enviar, un administrador deberá autorizar para que afecte el stock.
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo base_url();?>admin/ajuste_inventario/save/<?php echo $conteo->code;?>" id="conteoForm">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><b>Notas del encabezado</b></label>
                        <textarea class="form-control" name="notes" rows="2" <?php if(!$editable) echo 'readonly';?>><?php echo htmlspecialchars($conteo->notes);?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Buscar producto</label>
                        <input type="text" class="form-control" id="filter_prod" placeholder="Código o nombre..." oninput="filterRows()">
                        <div class="mt-2">
                            <label class="checkbox">
                                <input type="checkbox" id="only_diff" onchange="filterRows()">
                                <span></span> Solo con diferencia
                            </label>
                            <label class="checkbox ml-4">
                                <input type="checkbox" id="only_pending" onchange="filterRows()">
                                <span></span> Solo pendientes
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tabla_conteo">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th class="text-center">Sistema</th>
                                <th class="text-center" style="min-width:120px">Contado</th>
                                <th class="text-center">Diferencia</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle as $d):
                                $diff = floatval($d['diferencia']);
                                $is_counted = ((int)$d['counted'] === 1);
                                $row_class = '';
                                if ($is_counted && abs($diff) > 0.0001) $row_class = 'row-diff';
                                elseif ($is_counted) $row_class = 'row-ok';
                            ?>
                            <tr class="conteo-row <?php echo $row_class;?>"
                                data-name="<?php echo htmlspecialchars(strtolower($d['product_code'].' '.$d['product_name']));?>"
                                data-pending="<?php echo $is_counted ? '0' : '1';?>"
                                data-diff="<?php echo (abs($diff) > 0.0001) ? '1' : '0';?>">
                                <td><?php echo htmlspecialchars($d['product_code']);?></td>
                                <td><?php echo htmlspecialchars($d['product_name']);?></td>
                                <td class="text-center">
                                    <?php echo number_format($d['stock_sistema'], 2, '.', '');?>
                                    <input type="hidden" name="detalle_id[]" value="<?php echo $d['detalle_id'];?>">
                                </td>
                                <td class="text-center">
                                    <?php if ($editable): ?>
                                    <input type="number" step="any" min="0" class="form-control form-control-sm text-center contado-input"
                                        name="stock_contado[]"
                                        value="<?php echo $is_counted ? number_format($d['stock_contado'], 2, '.', '') : '';?>"
                                        data-sistema="<?php echo floatval($d['stock_sistema']);?>"
                                        oninput="recalcRow(this)"
                                        placeholder="—">
                                    <?php else: ?>
                                    <?php echo $is_counted ? number_format($d['stock_contado'], 2, '.', '') : '—';?>
                                    <input type="hidden" name="stock_contado[]" value="<?php echo $is_counted ? number_format($d['stock_contado'], 2, '.', '') : '';?>">
                                    <?php endif; ?>
                                </td>
                                <td class="text-center diff-cell">
                                    <?php if ($is_counted): ?>
                                        <span class="<?php echo $diff < 0 ? 'diff-neg' : ($diff > 0 ? 'diff-pos' : '');?>">
                                            <?php echo ($diff > 0 ? '+' : '').number_format($diff, 2, '.', '');?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($editable): ?>
                                    <input type="text" class="form-control form-control-sm" name="det_notes[]" value="<?php echo htmlspecialchars($d['notes']);?>" maxlength="255">
                                    <?php else: ?>
                                    <?php echo htmlspecialchars($d['notes']);?>
                                    <input type="hidden" name="det_notes[]" value="<?php echo htmlspecialchars($d['notes']);?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap">
            <?php if ($st === 1): ?>
            <button type="submit" class="btn btn-light-primary font-weight-bolder mr-2 mb-2">Guardar borrador</button>
            <button type="submit" formaction="<?php echo base_url();?>admin/ajuste_inventario/enviar/<?php echo $conteo->code;?>"
                class="btn btn-warning font-weight-bolder mr-2 mb-2"
                onclick="return confirm('¿Enviar a autorización? Debes haber contado todos los productos.');">
                Enviar a autorización
            </button>
            <?php elseif ($st === 2 && $editable): ?>
            <button type="submit" class="btn btn-light-primary font-weight-bolder mr-2 mb-2">Guardar cambios</button>
            <?php endif; ?>

            <?php if ($st === 2 && $is_admin): ?>
            <button type="submit" formaction="<?php echo base_url();?>admin/ajuste_inventario/autorizar/<?php echo $conteo->code;?>"
                class="btn btn-success font-weight-bolder mr-2 mb-2"
                onclick="return confirm('¿Autorizar y aplicar este conteo al inventario? Esta acción no se puede deshacer fácilmente.');">
                Autorizar y aplicar
            </button>
            <?php endif; ?>

            <?php if (in_array($st, array(1, 2), true)): ?>
            <a href="<?php echo base_url();?>admin/ajuste_inventario/anular/<?php echo $conteo->code;?>"
                class="btn btn-light-danger font-weight-bolder mb-2"
                onclick="return confirm('¿Anular este conteo?');">Anular</a>
            <?php endif; ?>
        </div>
    </form>
</div>
<script type="text/javascript">
function recalcRow(el) {
    var $row = $(el).closest('tr');
    var sistema = parseFloat($(el).data('sistema')) || 0;
    var raw = $(el).val();
    var $cell = $row.find('.diff-cell');
    if (raw === '') {
        $cell.html('<span class="text-muted">—</span>');
        $row.removeClass('row-ok row-diff').attr('data-pending','1').attr('data-diff','0');
        return;
    }
    var contado = parseFloat(raw);
    if (isNaN(contado)) contado = 0;
    var diff = contado - sistema;
    var cls = diff < 0 ? 'diff-neg' : (diff > 0 ? 'diff-pos' : '');
    var txt = (diff > 0 ? '+' : '') + diff.toFixed(2);
    $cell.html('<span class="'+cls+'">'+txt+'</span>');
    $row.attr('data-pending','0');
    if (Math.abs(diff) > 0.0001) {
        $row.removeClass('row-ok').addClass('row-diff').attr('data-diff','1');
    } else {
        $row.removeClass('row-diff').addClass('row-ok').attr('data-diff','0');
    }
}
function filterRows() {
    var q = ($('#filter_prod').val() || '').toLowerCase();
    var onlyDiff = $('#only_diff').is(':checked');
    var onlyPending = $('#only_pending').is(':checked');
    $('#tabla_conteo tbody tr.conteo-row').each(function(){
        var name = $(this).data('name') || '';
        var ok = !q || name.indexOf(q) !== -1;
        if (onlyDiff && String($(this).attr('data-diff')) !== '1') ok = false;
        if (onlyPending && String($(this).attr('data-pending')) !== '1') ok = false;
        $(this).toggle(ok);
    });
}
</script>
