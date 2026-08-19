<?php 
    $edit_data	=	$this->db->get_where('provider' , array('provider_id' => $param2))->result_array();
  	foreach ($edit_data as $row):
?>
<form class="form" action="<?php echo base_url().'admin/proveedores/update/'.$param2.'/1';?>" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Los campos marcados con * son obligatorios.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>NIT <span class="text-danger">*</span></label>
                <div class=" spinner-success spinner-left" id='spinnerNit'>
                    <input type="text" placeholder="Ej: 89907865 sin guiones" class="form-control " name="nit" id="nit" min='0' minlength="2" maxlength="12" onblur="getNit(this.value)" onblur="getNit(this.value)" autocomplete="off"  value='<?php echo $row['nit']?>'>
                </div>
                <div id='errorNit'></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Empresa <span class="text-danger">*</span></label>
                <div class="spinner-primary spinner-left" id='spinnerName'>
                    <input type="text" class="form-control" aria-label="Text input with checkbox" required name='name' id='c_name' value='<?php echo $row['name'];?>' />
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Teléfono <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" min='0' class="form-control" aria-label="Text input with checkbox" name='phone' value='<?php echo $row['phone'];?>' required />
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Encargado <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='manager' required value='<?php echo $row['manager'];?>' />
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>WhatsApp</label>
                <div class="input-group">
                    <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" min='0' class="form-control" aria-label="Text input with checkbox" name="whatsapp" value='<?php echo $row['whatsapp'];?>' />
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Correo</label> <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='email' value='<?php echo $row['email'];?>' id="email_prov_modal"  />
                </div>
                <span id="msg_email_prov_modal" class="text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Dirección</label>
                <div class="input-group">
                    <textarea class="form-control" aria-label="Text input with checkbox" name='address'><?php echo $row['address'];?></textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Detalles adicionales <small>(Como servicios que ofrecen)</small></label>
                <div class="input-group">
                    <textarea class="form-control" aria-label="Text input with checkbox" name='detail'><?php echo $row['detail'];?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="edit_prov_submit">Continuar</button>
    </div>
</form>
<script type="text/javascript">
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
                $('#c_phone').val('');
                $('#c_email').val('');
                var data = JSON.parse(response);
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
                    $('#new-client').val('1');

                }


            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        console.log('datos no encontrados');
    }

}

function search_email_modal() {
    var email = $('#email_prov_modal').val();
    var ID = '<?php echo $param2?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/provider',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_prov_modal').html(" ");
                $('#edit_prov_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_prov_modal').html("Correo eléctronico no disponible");
                $('#edit_prov_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_prov_modal').html(" ");
                $('#edit_prov_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
<?php endforeach; ?>
