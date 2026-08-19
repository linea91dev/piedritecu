<?php $data = $this->db->get_where('admin', array('status'=>1));   ?>
<div class="container-fluid">
    <form class="form" action="<?php echo base_url().'admin/codigos/update/';?>"
        method="POST" enctype="multipart/form-data">
        <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar códigos
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona todos los códigos de tus colaboradores.</span>
                        </h3>
                    </div>
                    <?php if($user_type == 1 || $permisos['guardar_codigos'] == 1):?>
                    <div class="card-toolbar">
                        <button type="submit" class="btn btn-light-primary font-weight-bolder">Guardar</button>
                    </div>
                    <?php endif;?>
                </div>
                <?php if($data->num_rows() > 0): ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Código de autorización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n = 1; 
								foreach($data->result_array() as $row): $id = $row['admin_id']?>
                                <tr>
                                    <td style='text-align: end !important;'><?php echo $n++;?></td>
                                    <td>
                                        <?php if($row["img"] != ""):?>
                                        <img class="h-75 align-self-end" width="35px" style="border-radius:50%;"
                                            src="<?php echo base_url().'uploads/img/'.$row["img"];?>" alt="photo">
                                        <?php else: ?>
                                        <?php $initial = strtoupper($this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name[0]);?>
                                        <img class="h-75 align-self-end" width="35px" style="border-radius:50%;"
                                            src="<?php echo base_url().'uploads/avatars/'.$initial.'.svg'; ?>"
                                            alt="photo">
                                        <?php endif; ?>
                                        <?php echo $row['name'].' '.$row['last_name'];?>
                                        <input type='hidden' value ="<?php echo $row['admin_id']?>" name='admin_id[]'>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name='code_authorization<?php echo $id;?>' autocomplete="off"
                                                    value='<?php echo $row['code_authorization'];?>' placeholder='Ingrese el  código de autorización '>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Anulación de facturas </label>
                                                    <input type="checkbox" name="anulacion<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['anulacion']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Descuentos</label>
                                                    <input type="checkbox" name="descuentos<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['descuentos']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Compras a proveedores</label>
                                                    <input type="checkbox" name="compras<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['compras']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Cambios de productos </label>
                                                    <input type="checkbox" name="cambios<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['cambios']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Cierre de caja </label>
                                                    <input type="checkbox" name="cierre<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['cierre']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Configuración </label>
                                                    <input type="checkbox" name="configuracion<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['configuracion']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> Eliminar pagos </label>
                                                    <input type="checkbox" name="eliminar_pagos<?php echo $id;?>" value="1" 
                                                        <?php echo ($row['eliminar_pagos']== 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                    <center>
                        <h3>Sin datos</h3><br>
                        <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                    </center>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </form>
</div>



<script type="text/javascript">
$(document).ready(function() {
    $('#selected-d0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
})

function searchEmail() {
    var email = $('#email_admin_add').val();
    var ID = '0';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_admin_add').html(" ");
                $('#add_admin_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_admin_add').html("Correo eléctronico no disponible");
                $('#add_admin_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_admin_add').html(" ");
                $('#add_admin_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

let timerInterval

function executeExample(admin_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará toda la información del administrador",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Eliminando información',
                type: 'success',
                icon: 'success',
                titleTextColor: '#000',
                html: 'Esta ventana se cerrará en <strong></strong>.',
                timer: 2000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong').textContent = Swal
                            .getTimerLeft()
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            })
            location.href = "<?php echo base_url();?>admin/admins/delete/" + admin_id;
        }
    })
}
</script>