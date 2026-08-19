<?php $edit_data	=	$this->db->get_where('sales' , array('code' => $param2))->result_array();
	foreach ($edit_data as $row):
	    if(strlen($row['nit']) <= 5){
        $nit = 'CF';
        }else{
            $nit = $row['nit'];
        }
?>

<div class="onboarding-content with-gradient">

    <form class="form" action="<?php echo base_url().'admin/anulacionXML/'.$param2;?>" method="POST">

        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="alert alert-custom alert-default" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                            <div class="alert-text"> Anulación con código: <b><?php echo $param2;?></b> <br>
                                Esta sera una acción que no se podra desacer manejese con cuidado.
                            </div>
                        </div>
                    </div>
                </div>

                <input type='hidden' name="nit" value='<?php echo $nit;?>' />
                <input type='hidden' name="code_fel" value='<?php echo $row['code_fel'];?>' />
                <input type='hidden' name="date_fel" value='<?php echo $row['date_fel'];?>' />


                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Cliente <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input disabled type='text' class="form-control" 
                                value='<?php echo $row['name'];?>' />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label> NIT: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input disabled type='text' class="form-control" 
                                value='<?php echo $nit;?>' />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label> Código de autorización: <span class="text-danger">*</span></label>

                        <div class=" spinner-success spinner-left" id='spinnerCode'>
                            <input type="password" autocomplete="off" class='form-control' id='code'
                                placeholder='Ingresa el código de autorización' autofocus
                                onblur="getCodigo(this.value)">
                        </div>
                        <div id='mensajeError'></div>
                        <small class='text-info'>Para verificar el código utiliza la tecla <b>TAB</b></small>

                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Motivo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <textarea class="form-control" name="motivo" 
                                required></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-danger font-weight-bold" id='anular'>Anular</button>
        </div>
    </form>
</div>
<?php endforeach; ?>


<script type="text/javascript">
$(document).ready(function() {
    $('#anular').hide();
})

function getCodigo(code) {
    var leng_code = code.length;
    if (leng_code > 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/checkCodigos/',
            data: {
                code: code,
                valor: 'anulacion',
            },
            beforeSend: function() {
                $('#spinnerCode').addClass('spinner');
            },
            success: function(response) {
                if (response == 1) {
                    $('#anular').show(500);
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-success"> Código correcto </small>');

                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger"> Código incorrecto </small>');
                    $('#anular').hide(500);
                }

            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    }
}
</script>