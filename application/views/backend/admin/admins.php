<?php $data = $this->db->get_where('admin', array('type'=>1 , 'admin_id !='=> $this->session->userdata('login_user_id')));   ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar administradores
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona quienes podrán tener acceso
                                administrativo a tu sistema.</span>
                        </h3>
                    </div>
                    <?php if($user_type == 1 || $permisos['crear_admins'] == 1):?>
                    <div class="card-toolbar">
                        <a href="javascript:;" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#exampleModalSizeLg">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                              fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Nuevo Administrador
                        </a>
                    </div>
                    <?php endif;?>
                </div>
                <?php if($data->num_rows() > 0): ?>
                <div class="card-body">
                    <form class="mb-15">
                        <div class="row mb-6">

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Nombre:</label>
                                <input type="text" class="form-control datatable-input" placeholder="Nombres" data-col-index="1">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Código:</label>
                                <input type="text" class="form-control datatable-input" data-col-index="4" placeholder="Ejemplo: 312768">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Estado:</label>
                                <select class=" form-control datatable-input" data-col-index="6">
                                    <option value="">Seleccionar</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Buscar</span>
                                    </span>
                                </button>&nbsp;&nbsp;
                                <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Limpiar</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Celular</th>
                                    <th>CUI</th>
                                    <th>Código de acceso</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n = 1; 
								foreach($data->result_array() as $row):?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td>
                                        <?php if($row["img"] != ""):?>
                                        <img class="h-75 align-self-end" width="35px" style="border-radius:50%;" src="<?php echo base_url().'uploads/img/'.$row["img"];?>" alt="photo">
                                        <?php else: ?>
                                        <?php $initial = strtoupper($this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name[0]);?>
                                        <img class="h-75 align-self-end" width="35px" style="border-radius:50%;" src="<?php echo base_url().'uploads/avatars/'.$initial.'.svg'; ?>" alt="photo">
                                        <?php endif; ?>
                                        <?php echo $row['name'].' '.$row['last_name'];?>
                                    </td>
                                    <td>
                                        <span class="text-warning font-weight-bolder"><a style="color: #FFA800 !important;" href="tel:+502<?php echo $row['phone'];?>">
                                                <?php echo ($row['phone']=='') ? 'Sin datos':'(+502)'.$row['phone'] ;?></a>
                                        </span>
                                    </td>
                                    <td><?php echo ($row['cui']=='') ? 'Sin datos':$row['cui'] ;?></td>
                                    <td><span class="font-weight-bold"><?php echo $row['username'];?></span></td>
                                    <td><a style='color:#3F4254;' href="mailto:<?php echo $row['email'];?>">
                                            <?php echo $row['email'];?></a></td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-<?php echo ($row['status']==1) ? 'success':'danger' ;?> label-inline"><?php echo ($row['status']==1) ? 'Activo':'Inactivo' ;?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url().'admin/perfil_admin/'.$row['admin_id'];?>" data-toggle="tooltip" data-original-title="Perfil del administrador" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['estado_admins'] == 1):?>
                                            <?php if($row['status']==1):?>
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Desactivar administrador" onclick="executeExample('<?php echo $row['admin_id'];?>')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                  fill="#000000" opacity="0.3" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php else:?>
                                            <a href="<?php echo base_url().'admin/admins/active/'.$row['admin_id'];?>" data-toggle="tooltip" data-original-title="Re-activar administrador" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                                                  fill="#000000" opacity="0.3" />
                                                            <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                                                  fill="#000000" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            <?php endif;?>
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
</div>

<form class="form" action="<?php echo base_url();?>admin/admins/create" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-dialog  modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo administrador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
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
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='name' required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" name='last_name' required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" id="kt_datepicker" required name='birthday' placeholder="mm/dd/aaaa" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de contratación</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" id="kt_datepicker_1" name='hiring' value='<?php echo date('m/d/Y');?>' placeholder="mm/dd/aaaa" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Celular</label>
                                <div class="input-group">
                                    <input type="number" min='0' oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" aria-label="Text input with checkbox" name='phone' />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>CUI:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" pattern="[0-9]{8}" aria-label="Text input with checkbox" maxlength="13" name='cui' oninput="if(value.length>13)value=value.slice(0,13)" onkeyup="validateDPI(this.value);" />
                                </div>
                                <div id='errorCUI'></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Correo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="email" class='form-control' name='email' id='email_admin_add' aria-label="Text input with checkbox" oninput="searchEmail()" onblur="searchEmail()" required="true">
                                </div>
                                <label id="msg_email_admin_add" class="control-label text-danger"></label>
                                <small>Recibirás notificaciones y podrás recuperar tu
                                    contraseña.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Salario</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" aria-label="Text input with checkbox" name='salary' min='0' max='9999' />
                                </div>
                                <small>Si no aplica dejar vacío o ingresar 0.</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fotografía</label>
                                <div class="input-group">
                                    <input class="uppy-FileInput-input uppy-input-control" type="file" name="foto" accept="image/*" id="kt_uppy_5_input_control" style='display:none' onchange="onLoadImage_s(event.target.files)">
                                    <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold" for="kt_uppy_5_input_control">Seleccionar Imagen</label>
                                </div>
                                <label>Archivo seleccionado: <b><span id="imgName_s">Niguno</span></b></label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Sucursales</label>
                                <div class="input-group">
                                    <select name="branch[]" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        
                                        $sucursales = unserialize($row['sucursal']); 
                                        $sucursal = $this->db->get_where('branch', array('status'=>1))->result_array(); 
                                        foreach ($sucursal as $sc):
                                        ?>
                                        <option value="<?php echo $sc['branch_id'];?>" <?php echo (in_array($sc['branch_id'], $sucursales)) ? 'selected':'' ;?>>
                                            <?php echo $sc['name'];?>
                                        </option>
                                        <?php  endforeach ;?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox" name='address'></textarea>
                                </div>
                            </div>
                            <span class="text-danger"><b>* Las credenciales de acceso se envirán al correo que
                                    ingresaste.</b></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id='add_admin_submit' class="btn btn-primary font-weight-bold">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
    $('#selected-d0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
})

function cuiIsValid(cui) {
    var console = window.console;

    if (!cui) {
        return true;
    }

    var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

    if (!cuiRegExp.test(cui)) {
        return false;
    }

    cui = cui.replace(/\s/, '');
    var depto = parseInt(cui.substring(9, 11), 10);
    var muni = parseInt(cui.substring(11, 13));
    var numero = cui.substring(0, 8);
    var verificador = parseInt(cui.substring(8, 9));

    // Se asume que la codificación de Municipios y 
    // departamentos es la misma que esta publicada en 
    // http://goo.gl/EsxN1a

    // Listado de municipios actualizado segun:
    // http://goo.gl/QLNglm

    // Este listado contiene la cantidad de municipios
    // existentes en cada departamento para poder 
    // determinar el código máximo aceptado por cada 
    // uno de los departamentos.
    var munisPorDepto = [
        /* 01 - Guatemala tiene:      */
        17 /* municipios. */ ,
        /* 02 - El Progreso tiene:    */
        8 /* municipios. */ ,
        /* 03 - Sacatepéquez tiene:   */
        16 /* municipios. */ ,
        /* 04 - Chimaltenango tiene:  */
        16 /* municipios. */ ,
        /* 05 - Escuintla tiene:      */
        13 /* municipios. */ ,
        /* 06 - Santa Rosa tiene:     */
        14 /* municipios. */ ,
        /* 07 - Sololá tiene:         */
        19 /* municipios. */ ,
        /* 08 - Totonicapán tiene:    */
        8 /* municipios. */ ,
        /* 09 - Quetzaltenango tiene: */
        24 /* municipios. */ ,
        /* 10 - Suchitepéquez tiene:  */
        21 /* municipios. */ ,
        /* 11 - Retalhuleu tiene:     */
        9 /* municipios. */ ,
        /* 12 - San Marcos tiene:     */
        30 /* municipios. */ ,
        /* 13 - Huehuetenango tiene:  */
        32 /* municipios. */ ,
        /* 14 - Quiché tiene:         */
        21 /* municipios. */ ,
        /* 15 - Baja Verapaz tiene:   */
        8 /* municipios. */ ,
        /* 16 - Alta Verapaz tiene:   */
        17 /* municipios. */ ,
        /* 17 - Petén tiene:          */
        14 /* municipios. */ ,
        /* 18 - Izabal tiene:         */
        5 /* municipios. */ ,
        /* 19 - Zacapa tiene:         */
        11 /* municipios. */ ,
        /* 20 - Chiquimula tiene:     */
        11 /* municipios. */ ,
        /* 21 - Jalapa tiene:         */
        7 /* municipios. */ ,
        /* 22 - Jutiapa tiene:        */
        17 /* municipios. */
    ];

    if (depto === 0 || muni === 0) {
        console.log("CUI con código de municipio o departamento inválido.");
        return false;
    }

    if (depto > munisPorDepto.length) {
        console.log("CUI con código de departamento inválido.");
        return false;
    }

    if (muni > munisPorDepto[depto - 1]) {
        console.log("CUI con código de municipio inválido.");
        return false;
    }

    // Se verifica el correlativo con base 
    // en el algoritmo del complemento 11.
    var total = 0;

    for (var i = 0; i < numero.length; i++) {
        total += numero[i] * (i + 2);
    }

    var modulo = (total % 11);

    console.log("CUI con módulo: " + modulo);
    return modulo === verificador;
};

function validateDPI(ddd) {
    var $this = $(this);
    var $parent = $this.parent();
    var $next = $this.next();
    var cui = ddd;

    if (cui && cuiIsValid(cui)) {
        $('#errorCUI').html('<p class="text-success"> DPI válido</p>');
    } else if (cui) {
        $('#errorCUI').html('<p class="text-danger">Debe ingresar un DPI válido</p>');
    } else {
        $('#errorCUI').html('<p class="text-danger">DPI no válido</p>');
    }
}

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
        text: "Se desactivará toda la información del administrador",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, desactivar',
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
