<?php if($param2 == "configuracion"){ $valor = 'Configuración';} elseif($param2 == 'nueva_compra'){ $valor = 'Compras';}elseif($param2 == 'nueva_solicitud'){ $valor = 'Compras';}elseif($param2 == 'nueva_venta'){ $valor = 'Nueva venta';}elseif($param2 == 'nueva_cotizacion'){ $valor = 'Nueva cotización';}elseif($param2 == 'nueva_venta_c'){ $valor = 'Nueva venta';}?>
<div class="onboarding-content with-gradient">
    <form class="form" action="<?php echo base_url().'admin/anulacionXML/'.$param2;?>" method="POST">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="alert alert-custom alert-default" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                            <div class="alert-text">
                                Esta acción requiere de un nivel superior de autenticación, para confirmar por favor ingresa tu código de autorización. Si no tienes uno, consulta al encargado de la tienda o tu supervisor.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label> Código de autorización: <span class="text-danger">*</span></label>

                        <div class=" spinner-success spinner-left" id='spinnerCode'>
                            <input type="password" autocomplete="off" class='form-control' id='code'
                                placeholder='Ingresa el código de autorización' autofocus
                                onchange="getCodigo(this.value)" onblur="getCodigo(this.value)">
                        </div>
                        <div id='mensajeError'>
                        <small class='text-info'>Para verificar el código utiliza la tecla <b>TAB</b></small>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <a href="javascript:submitForm();" class="btn btn-danger font-weight-bold"
                <?php if($param2 == 'nueva_venta' || $param2 == 'nueva_cotizacion' || $param2 == 'nueva_venta_c') {echo 'data-dismiss="modal"';} ; ?>
                id='anular'>Guardar</a>

        </div>
    </form>
</div>


<script type="text/javascript">
$(document).ready(function() {
    $('#anular').hide();
    $('#code').focus();
    var param = '<?php echo $param2;?>';
    if (param == 'nueva_venta') {
        $('.nueva_venta').attr('hidden', true);
    }
});


function getCodigo(code) {
    var leng_code = code.length;
    var valor = '';
    <?php if($param2 == 'configuracion'):?>
    valor = '<?php echo $param2?>';
    <?php endif; if($param2 == 'nueva_compra'):?>
    valor = 'compras';
    <?php endif; if($param2 == 'nueva_solicitud'):?>
    valor = 'compras';
    <?php endif; if($param2 == 'nueva_venta'):?>
    valor = 'descuentos';
    <?php endif; if($param2 == 'nueva_cotizacion'):?>
    valor = 'descuentos';
    <?php endif; if($param2 == 'nueva_venta_c'):?>
    valor = 'descuentos';
    <?php endif;?>
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
                    $('#anular').show(500);
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-success" >Código aceptado</small>');
                    if (valor == 'descuentos') {
                        $('.nueva_venta').removeAttr('hidden');
                        $('#descuentos').val('1');
                    }

                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    $('#anular').hide(500);
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