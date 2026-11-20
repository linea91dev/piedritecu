<?php
$moneda = $this->crud_model->get_info("moneda");
$caja_banco = $this->crud_model->total_caja();
$sesion = $this->crud_model->get_caja_abierta();
$resumen = $this->crud_model->get_resumen_caja_sesion($sesion);

// Totales del día (referencia) además de la sesión
$ingresos = $this->crud_model->ingresos_caja();
$ingresos_credito = $this->crud_model->ingresos_credito();
$transaccion = $this->crud_model->total_transf();
$total_tarjeta = $this->crud_model->total_tarjeta();
$total_compras = $this->crud_model->total_compras();
$total_compras_creditos = $this->crud_model->total_compras_creditos(date('Y-m-d'));
$egresos = $this->crud_model->egresos_caja();
?>
<div class="onboarding-content with-gradient">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-6">
               <a href="<?php echo base_url().'admin/corte_caja_filtro/'?>" class="navi-link">
                    <span class="btn btn-rounded btn-success">Otra fecha</span>
                </a>
            </div>
            <div class="col-sm-6 text-right">
                <span class="badge badge-<?php echo $sesion ? 'success' : 'warning';?> font-size-lg">
                    <?php echo $sesion ? 'Caja abierta' : 'Caja cerrada';?>
                </span>
            </div>
        </div>
    </div>

    <?php if (!$sesion): ?>
    <form class="form" action="<?php echo base_url().'admin/caja/abrir';?>" method="POST">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-custom alert-light-primary" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Debes <b>abrir tu caja</b> para registrar cobros en efectivo, abonos, gastos y compras en tu sesión. Cada usuario maneja su propia caja.
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder mb-0">Saldo actual en cuenta caja</h3>
                        <div class="font-weight-boldest font-size-h1 text-info mt-3">
                            <?php echo $moneda.number_format($caja_banco,2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Monto de apertura</b> <span class="text-danger">*</span></h3>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-boldest font-size-h1"><?php echo $moneda;?></span>
                        </div>
                        <input type="number" name="monto_apertura" value="<?php echo number_format($caja_banco,2,'.','');?>" step="0.01" min="0" required
                            class="form-control font-weight-boldest font-size-h1">
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Notas de apertura</b></h3>
                    <textarea class="form-control" name="notes_open" rows="2" placeholder="Opcional"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success font-weight-bold">Abrir caja</button>
        </div>
    </form>

    <?php else: ?>
    <?php
        $esperado = floatval($resumen['esperado']);
        $opened_lbl = date('d/m/Y H:i', strtotime($sesion->opened_at));
    ?>
    <form class="form" action="<?php echo base_url().'admin/create_box_cut/';?>" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Sesión abierta desde <b><?php echo $opened_lbl;?></b> por
                        <b><?php echo $this->crud_model->getName('admin', $sesion->user_open);?></b>.
                        Los campos con * son obligatorios.
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Apertura</h3>
                        <div class="font-weight-boldest font-size-h1 text-info">
                            <?php echo $moneda.number_format($resumen['apertura'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Cobros efectivo / ventas</h3>
                        <div class="font-weight-boldest font-size-h1 text-success">
                            <?php echo $moneda.number_format($resumen['venta_efectivo'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Abonos a crédito (efectivo)</h3>
                        <div class="font-weight-boldest font-size-h1 text-success">
                            <?php echo $moneda.number_format($resumen['abono_credito'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Tarjeta / Transferencias</h3>
                        <div class="font-weight-boldest font-size-h1 text-warning">
                            <?php echo $moneda.number_format($resumen['venta_tarjeta'] + $resumen['venta_transferencia'],2,'.',',');?>
                        </div>
                        <small class="text-muted">No afectan efectivo en caja</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Gastos (efectivo)</h3>
                        <div class="font-weight-boldest font-size-h1 text-danger">
                            <?php echo $moneda.number_format($resumen['gasto'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Compras / pagos proveedor</h3>
                        <div class="font-weight-boldest font-size-h1 text-danger">
                            <?php echo $moneda.number_format($resumen['compra'],2,'.',',');?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Monto esperado en caja</h3>
                        <div class="font-weight-boldest font-size-h1 text-success">
                            <?php echo $moneda.number_format($esperado,2,'.',',');?>
                        </div>
                        <small class="text-muted">Apertura + ingresos efectivo − egresos efectivo</small>
                    </div>
                </div>
                <input type="hidden" name="caja_actual" value="<?php echo floatval($sesion->monto_apertura);?>">
                <input type="hidden" name="caja_nueva" id="caja_nueva" value="<?php echo number_format($esperado,2,'.','');?>">
                <input type="hidden" name="diferencia_day" value="<?php echo number_format($resumen['ingresos_efectivo'] - $resumen['egresos_efectivo'],2,'.','');?>">
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Total en la Caja</b> <span class="text-danger">*</span></h3>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-boldest font-size-h1"><?php echo $moneda;?></span>
                        </div>
                        <input type="number" name="ver_caja" id="ver_caja" value="0.00" step="0.01" required min="0"
                            class="form-control font-weight-boldest font-size-h1" oninput="verificar()">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <h3 class="font-weight-bolder">Diferencia</h3>
                        <div class="font-weight-boldest font-size-h1 text-info">
                            <span id="lbl_diferencia"><?php echo $moneda;?>0.00</span>
                        </div>
                        <small id="message" class="text-danger"></small>
                    </div>
                </div>
                <input type="hidden" id="diferencia" name="diferencia_cash" value="0">
            </div>

            <div class="col-sm-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">Movimientos de la sesión</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive" style="max-height:260px; overflow:auto;">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Detalle</th>
                                        <th>Método</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($resumen['movimientos'])): ?>
                                    <tr><td colspan="5" class="text-center text-muted">Sin movimientos aún</td></tr>
                                    <?php else: foreach ($resumen['movimientos'] as $mov): ?>
                                    <tr>
                                        <td><?php echo date('H:i', strtotime($mov['created_at']));?></td>
                                        <td>
                                            <span class="badge badge-<?php
                                                echo $mov['tipo']==='ingreso' ? 'success' : ($mov['tipo']==='egreso' ? 'danger' : 'warning');
                                            ?>"><?php echo htmlspecialchars($mov['categoria']);?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($mov['detalle'] ? $mov['detalle'] : $mov['referencia']);?></td>
                                        <td><?php echo htmlspecialchars($mov['metodo']);?></td>
                                        <td class="text-right"><?php echo $moneda.number_format($mov['amount'],2,'.',',');?></td>
                                    </tr>
                                    <?php endforeach; endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <h3 class="font-weight-bolder"><b>Notas de cierre</b></h3>
                    <textarea class="form-control" name="notes" rows="2">Ninguna</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group" id="codigoAuth">
                    <label>Código de autorización para cierre: <span class="text-danger">*</span></label>
                    <div class="spinner-success spinner-left" id="spinnerCode">
                        <input type="password" autocomplete="off" class="form-control" id="code"
                            placeholder="Ingresa el código de autorización" autofocus
                            onblur="getCodigo(this.value)">
                    </div>
                    <div id="mensajeError"></div>
                    <small class="text-info">Presionar TAB para verificar tu código</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success font-weight-bold" id="Guardar" disabled>Cerrar caja</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

function getCodigo(code) {
    var leng_code = code.length;
    if (leng_code > 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/checkCodigos/',
            data: { code: code, valor: 'cierre' },
            beforeSend: function() { $('#spinnerCode').addClass('spinner'); },
            success: function(response) {
                $('#spinnerCode').removeClass('spinner');
                if (response == 1) {
                    $('#mensajeError').html('<small class="text-success">Código aceptado</small>');
                    $('#Guardar').removeAttr('disabled');
                } else {
                    $('#mensajeError').html('<small class="text-danger">Código incorrecto</small>');
                    $('#Guardar').attr('disabled', true);
                }
            }
        });
    } else {
        $('#mensajeError').html('<small class="text-info">Ingrese un código</small>');
    }
}

function verificar() {
    var msg = '';
    var ver_caja = $("#ver_caja").val();
    var caja_nueva = $("#caja_nueva").val();
    var diferencia = parseFloat(ver_caja) - parseFloat(caja_nueva);
    if (isNaN(diferencia)) diferencia = 0;

    if (diferencia > 0) {
        msg = "Diferencia sobrante";
        $("#message").attr('class', 'text-warning');
    } else if (diferencia < 0) {
        msg = "Diferencia faltante";
        $("#message").attr('class', 'text-danger');
    } else {
        msg = "Monto equivalente";
        $("#message").attr('class', 'text-success');
    }
    if (ver_caja === '') {
        diferencia = 0;
        msg = '';
    }
    var absDiff = Math.abs(diferencia);
    $("#lbl_diferencia").html(moneda + absDiff.toFixed(2));
    $("#diferencia").val(absDiff.toFixed(2));
    $("#message").html(msg);
}
</script>
