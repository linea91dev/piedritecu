<?php $edit_data	=	$this->db->get_where('admin' , array('admin_id' => $param2))->result_array();
	foreach ($edit_data as $row):
?>
<div class="onboarding-content with-gradient">

    <form class="form" action="<?php echo base_url().'admin/admins/update/'.$param2;?>" method="POST"
        enctype="multipart/form-data">
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
                    <label>Nombres <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='name'
                            required value='<?php echo $row['name'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Apellidos <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='last_name'
                            required value='<?php echo $row['last_name'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="date" class="form-control" aria-label="Text input with checkbox" required
                            name='birthday' value='<?php echo $row['birthday'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Fecha de contratación</label>
                    <div class="input-group">
                        <input type="date" class="form-control" aria-label="Text input with checkbox" name='hiring'
                            value='<?php echo $row['hiring'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Celular</label>
                    <div class="input-group">
                        <input type="tel" class="form-control" aria-label="Text input with checkbox" pattern="[0-9]{8}"
                            name='phone' value='<?php echo $row['phone'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>CUI:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input with checkbox" maxlength="13"
                            name='cui' value='<?php echo $row['cui'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Correo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" class='form-control' name='email' id='email_admin_edit'
                            aria-label="Text input with checkbox" value='<?php echo $row['email'];?>'
                            onkeyup="searchEmail()" onblur="searchEmail()" required="true">
                    </div>
                    <label id="msg_email_admin_edit" class="control-label text-danger"></label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Salario</label>
                    <div class="input-group">
                        <input type="number" class="form-control" aria-label="Text input with checkbox" name='salary'
                            value='<?php echo $row['salary'];?>' />
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Dirección</label>
                    <div class="input-group">
                        <textarea class="form-control" aria-label="Text input with checkbox"
                            name='address'><?php echo $row['address'];?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cerrar</button>
            <button type="submit" id='edit_admin_submit' class="btn btn-primary font-weight-bold">Guardar</button>
        </div>
    </form>
</div>
<?php endforeach; ?>

<script type="text/javascript">
function searchEmail() {
    var email = $('#email_admin_edit').val();
    var ID = '<?php echo $param2?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_admin_edit').html(" ");
                $('#edit_admin_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_admin_edit').html("Correo eléctronico no disponible");
                $('#edit_admin_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_admin_edit').html(" ");
                $('#edit_admin_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>