<?php
if (!isset($conteos) || !is_array($conteos)) $conteos = array();
if (!isset($categories) || !is_array($categories)) $categories = array();
if (!isset($providers) || !is_array($providers)) $providers = array();
$status_label = array(0 => 'Anulado', 1 => 'En captura', 2 => 'Pendiente autorización', 3 => 'Aplicado');
$status_badge = array(0 => 'secondary', 1 => 'info', 2 => 'warning', 3 => 'success');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder">Nuevo conteo</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-light-primary">
                        El conteo <b>no modifica el inventario</b> hasta que un administrador lo autorice.
                    </div>
                    <form method="POST" action="<?php echo base_url();?>admin/ajuste_inventario/create">
                        <div class="form-group">
                            <label><b>Ámbito</b></label>
                            <select class="form-control" name="scope_type" id="scope_type" required onchange="toggleScope()">
                                <option value="all">Todo el inventario</option>
                                <option value="category">Por categoría</option>
                                <option value="provider">Por proveedor</option>
                            </select>
                        </div>
                        <div class="form-group" id="wrap_scope_category" style="display:none;">
                            <label><b>Categoría</b></label>
                            <select class="form-control" name="scope_id_category" id="scope_id_category">
                                <option value="">Seleccionar</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category_id'];?>"><?php echo htmlspecialchars($cat['name']);?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id="wrap_scope_provider" style="display:none;">
                            <label><b>Proveedor</b></label>
                            <select class="form-control" name="scope_id_provider" id="scope_id_provider">
                                <option value="">Seleccionar</option>
                                <?php foreach ($providers as $prov): ?>
                                <option value="<?php echo $prov['provider_id'];?>"><?php echo htmlspecialchars($prov['name']);?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="scope_id" id="scope_id" value="0">
                        <div class="form-group">
                            <label>Notas / motivo</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Opcional"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bolder btn-block" onclick="return prepareScope();">
                            Crear encabezado de conteo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder">Historial de conteos</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Fecha</th>
                                    <th>Ámbito</th>
                                    <th>Productos</th>
                                    <th>Diffs</th>
                                    <th>Estado</th>
                                    <th>Creado por</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($conteos)): ?>
                                <tr><td colspan="8" class="text-muted text-center">Aún no hay conteos.</td></tr>
                                <?php else: foreach ($conteos as $c):
                                    $st = (int)$c['status'];
                                ?>
                                <tr>
                                    <td><b><?php echo $c['code'];?></b></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($c['datetime']));?></td>
                                    <td><?php echo htmlspecialchars($c['scope_label']);?></td>
                                    <td><?php echo (int)$c['num_products'];?></td>
                                    <td><?php echo (int)$c['diff_count'];?></td>
                                    <td>
                                        <span class="badge badge-<?php echo isset($status_badge[$st]) ? $status_badge[$st] : 'secondary';?>">
                                            <?php echo isset($status_label[$st]) ? $status_label[$st] : $st;?>
                                        </span>
                                    </td>
                                    <td><?php echo $this->crud_model->getName('admin', $c['created_by']);?></td>
                                    <td>
                                        <a class="btn btn-sm btn-light-primary" href="<?php echo base_url();?>admin/ajuste_inventario/detalle/<?php echo $c['code'];?>">Abrir</a>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function toggleScope() {
    var t = $('#scope_type').val();
    $('#wrap_scope_category').toggle(t === 'category');
    $('#wrap_scope_provider').toggle(t === 'provider');
}
function prepareScope() {
    var t = $('#scope_type').val();
    var id = 0;
    if (t === 'category') {
        id = $('#scope_id_category').val();
        if (!id) { alert('Selecciona una categoría'); return false; }
    } else if (t === 'provider') {
        id = $('#scope_id_provider').val();
        if (!id) { alert('Selecciona un proveedor'); return false; }
    }
    $('#scope_id').val(id || 0);
    return true;
}
$(document).ready(function(){ toggleScope(); });
</script>
