<?php $edit_data	=	$this->db->get_where('admin' , array('admin_id' => $ID))->result_array();
	foreach ($edit_data as $row):
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            <a href="<?php echo base_url().'admin/empleados/';?>">
                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
                                                x="14" y="7" width="2" height="10" rx="1" />
                                            <path
                                                d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                                                fill="#000000" fill-rule="nonzero"
                                                transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) " />
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            Gestionar empleados
                            <span class="d-block text-muted pt-2 font-size-sm">Aquí podrás encontrar a todo tu equipo de
                                trabajo.</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url().'admin/empleados/update/'.$ID;?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="alert alert-custom alert-default" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i>
                                        </div>
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
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            name='name' required value='<?php echo $row['name'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Apellidos <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            name='last_name' required value='<?php echo $row['last_name'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" aria-label="Text input with checkbox"
                                            required name='birthday' value='<?php echo $row['birthday'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fecha de contratación</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" aria-label="Text input with checkbox"
                                            name='hiring' value='<?php echo $row['hiring'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Celular</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" aria-label="Text input with checkbox"
                                            pattern="[0-9]{8}" name='phone' value='<?php echo $row['phone'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>CUI:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            maxlength="13" name='cui' value='<?php echo $row['cui'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Correo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="email" class='form-control' name='email' id="email_emp_modal"
                                            oninput="searchEmail()" onblur="searchEmail()"
                                            aria-label="Text input with checkbox" value='<?php echo $row['email'];?>'
                                            required="true">
                                    </div>
                                    <span id="msg_email_emp_modal" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Salario</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox"
                                            min='0' max='9999' name='salary' value='<?php echo $row['salary'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> Puesto </label>
                                    <select name="job" class="form-control">
                                        <option value="Vendedor"
                                            <?php echo ($row['job']=='Vendedor') ? 'selected':'' ;?>>Vendedor</option>
                                        <option value="Cobrador"
                                            <?php echo ($row['job']=='Cobrador') ? 'selected':'' ;?>>Cobrador</option>
                                        <option value="Cajero" <?php echo ($row['job']=='Cajero') ? 'selected':'' ;?>>
                                            Cajero</option>
                                        <option value="Diseñador"
                                            <?php echo ($row['job']=='Diseñador') ? 'selected':'' ;?>>Diseñador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> Sucursal </label>
                                    <select name="branch[]" class="form-control" id='selected-0' multiple required>
                                        <option value="">Seleccionar</option>
                                        <?php
                                        
                                        $sucursales = unserialize($row['sucursal']); 
                                        $sucursal = $this->db->get_where('branch', array('status'=>1))->result_array(); 
                                        foreach ($sucursal as $sc):
                                        ?>
                                        <option value="<?php echo $sc['branch_id'];?>"
                                            <?php echo (in_array($sc['branch_id'], $sucursales)) ? 'selected':'' ;?>>
                                            <?php echo $sc['name'];?>
                                        </option>
                                        <?php  endforeach ;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox"
                                            name='address'><?php echo $row['address'] ;?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <span class="text-primary"><b>* Permisos </b></span>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Usuarios </label>
                                                    <input type="checkbox" name="usuarios" value='1'
                                                        <?php echo ($row['usuarios']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Productos </label>
                                                    <input type="checkbox" name="productos" value='1'
                                                        <?php echo ($row['productos']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Herramientas </label>
                                                    <input type="checkbox" name="herramientas" value='1'
                                                        <?php echo ($row['herramientas']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Reportes </label>
                                                    <input type="checkbox" name="reportes" value='1'
                                                        <?php echo ($row['reportes']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Contabilidad </label>
                                                    <input type="checkbox" name="contabilidad" value='1'
                                                        <?php echo ($row['contabilidad']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Sucursales </label>
                                                    <input type="checkbox" name="sucursales" value='1'
                                                        <?php echo ($row['sucursales']==1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <span class="text-danger"><b>* Las credenciales de acceso se envirán al correo que
                                        ingresaste.</b></span>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bold"
                            id="edit_emp_submit">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#selected-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
})


function searchEmail() {
    var email = $('#email_emp_modal').val();
    var ID = '<?php echo $ID?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_emp_modal').html(" ");
                $('#edit_emp_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_emp_modal').html("Correo eléctronico no disponible");
                $('#edit_emp_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_emp_modal').html(" ");
                $('#edit_emp_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
<?php endforeach; ?>