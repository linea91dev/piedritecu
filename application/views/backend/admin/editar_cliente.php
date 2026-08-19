<?php 
    $edit_data	=	$this->db->get_where('client' , array('client_id' => $param2))->result_array();
  	foreach ($edit_data as $row):
?>
<form class="form" action="<?php echo base_url().'admin/clientes/update/'.$param2;?>" method="POST"
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
                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='name' required
                        value='<?php echo $row['name'];?>' />
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
                <label>Celular o WhatsApp</label>
                <div class="input-group">
                    <input type="number" class="form-control" aria-label="Text input with checkbox" name='phone'
                        pattern="[0-9]{8}" value='<?php echo $row['phone'];?>' />
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label> Sucursal <span class="text-danger">*</span> </label>
                <select name="branch" class="form-control">
                    <option value="">Seleccionar</option>
                    <?php $sucursal = $this->db->get_where('branch', array('status'=>1))->result_array(); foreach ($sucursal as $sc):
                                        ?>
                    <option value="<?php echo $sc['branch_id'];?>"
                        <?php echo ($row['branch_id'] == $sc['branch_id']) ? 'selected':'' ;?>>
                        <?php echo $sc['name'];?>
                    </option>
                    <?php  endforeach ;?>
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Tipo de cliente <span class="text-danger">*</span></label>
                <div class="input-group">
                    <select class="form-control" name='type' required>
                        <option>Seleccionar</option>
                        <option value="1" <?php echo ($row['type']==1)? 'selected':'' ?>>Mayorista</option>
                        <option value="2" <?php echo ($row['type']==2)? 'selected':'' ?>>Publico</option>
                        <option value="3" <?php echo ($row['type']==3)? 'selected':'' ?>>Socio</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Límite de crédito <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" aria-label="Text input with checkbox" name='limite'
                        min='0' value='<?php echo $row['limite'];?>' required />
                </div>
                <small>(Establezca 0 para ilimitado)</small>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>NIT/Código </label>
                <div class="input-group">
                    <input type="text" class="form-control" aria-label="Text input with checkbox" min='0' name='nit'
                         value='<?php echo $row['nit'];?>' />
                </div>
                <small>Presiona TAB para obtener los datos del contribuyente.</small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Correo</label>
                <div class="input-group">
                    <input type="email" class="form-control" aria-label="Text input with checkbox" name='email' value='<?php echo $row['email'];?>' />
                </div>
                <span id="msg_email_client_modal" class="text-danger"></span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Dirección</label>
                <div class="input-group">
                    <textarea class="form-control" name='address'
                        aria-label="Text input with checkbox"><?php echo $row['address'];?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="edit_client_submit">Continuar</button>
    </div>
</form>
<script type="text/javascript">
function search_email_modal() {
    var email = $('#email_client_modal').val();
    var ID = '<?php echo $param2; ?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/client',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_client_modal').html(" ");
                $('#edit_client_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_client_modal').html("Correo eléctronico no disponible");
                $('#edit_client_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_client_modal').html(" ");
                $('#edit_client_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
<?php endforeach; ?>