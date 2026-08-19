<meta name="google-signin-client_id" content="1072535742840-881cva9qvf26amdrj7pgbvknb9t9ddui.apps.googleusercontent.com">

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Perfil de usuario</h3>
                    </div><br><br>
                    <div class="alert alert-blue">
                        <span class="d-block pt-2 font-size-sm">Mantente al día de todo lo que sucede dentro del sistema,
                            si tus datos de contacto han cambiado no te olvides de actualizarlos en esta sección para recibir las notificaciones importantes.
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <form class="form" action="<?php echo base_url().'admin/admins/update/'.$this->session->userdata('login_user_id').'/1';?>" method="POST" enctype="multipart/form-data">
                                <?php $data = $this->db->limit(1)->get_where('admin', array('admin_id'=>$this->session->userdata('login_user_id')))->result_array(); foreach ($data as $row):?>
                                <div class="row center">
                                    <div class="col-sm-12">
                                        <center>
                                            <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-color: #fff">
                                                <div class="image-input-wrapper" <?php $initial = strtoupper($this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name[0]);?>
                                                     style="background-image: url(<?php echo ($row['img']!='')?  base_url().'uploads/img/'.$row['img'] : base_url().'uploads/avatars/'.$initial.'.svg' ;?>);background-size:contain; background-position: center;">
                                                </div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar imagen">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="img" accept=".png, .jpg, .jpeg" onchange="onLoadImage(event.target.files)" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar cambio" onclick="ocultarlabel()">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div><br>
                                            <label id="imgLabel" style="display: none;">Archivo seleccionado: <b><span id="imgName">Niguno</span></b></label>
                                            <span class="form-text text-muted">Formatos permitidos: png, jpg,
                                                jpeg.</span>
                                        </center>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nombres <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" value='<?php echo $row['name'];?>' name='name' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Apellidos <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" value='<?php echo $row['last_name'];?>' name='last_name' required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Celular</label>
                                            <div class="input-group">
                                                <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" min='0' aria-label="Text input with checkbox" pattern="[0-9]{8}" value='<?php echo $row['phone'];?>' name='phone' />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Código de acceso <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" value='<?php echo $row['username'];?>' name='username' required readonly="true" />
                                            </div>
                                            <small>Será con el que iniciarás sesión.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Puesto</label>
                                            <div class="input-group">
                                                <input type="text" disabled class='form-control' value='<?php echo ($row['type']==1)? 'Administrador':'Empleado';?>'>
                                                <input type="hidden" name="type" value='<?php echo $row['type'];?>'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Salario</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" aria-label="Text input with checkbox" min='0' value='<?php echo $row['salary'];?>' name='salary' />
                                            </div>
                                            <small>Si no aplica dejar vacío o ingresar 0.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label> Sucursal </label>
                                            <select name="branch[]" class="form-control" id='selected-0' multiple required>
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
                                            <small>Sucursales a las que tendrá acceso.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Contraseña actual</label>
                                            <div class="input-group">
                                                <input type="password" autocomplete="off" class="form-control" placeholder='Ingresa tu contraseña actual' aria-label="Text input with checkbox" name='password' id='password' onkeyup="comparar_pass()" />
                                            </div>
                                            <label id="mensgg" class="control-label text-danger" style="display: block;"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nueva contraseña</label>
                                            <div class="input-group">
                                                <input type="password" autocomplete="off" class="form-control" aria-label="Text input with checkbox" name='new_password' />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Correo electrónico</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name='email' id='email' aria-label="Text input with checkbox" value='<?php echo $row['email'];?>' oninput="searchEmail()" onblur="searchEmail()" required="true" />
                                            </div>
                                            <label id="ms_error" class="control-label text-danger"></label>

                                            <small>Recibirás notificaciones y podrás recuperar tu
                                                contraseña.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="gmail" <?php echo $row['gm_id'] == "" ? "":"style='display:none'";?>>
                                        <br>
                                        <div class="form-group">
                                            <a href="javascript:;" id="googleSignIn" style="background:#eee5ff;color:#8950FC; width:100%;" class="btn btn-light-primary font-weight-bolder">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641) " />
                                                            <path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z"
                                                                  fill="#f64e8b" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359) " />
                                                            <path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146) " />
                                                            <path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961) " />
                                                        </g>
                                                    </svg>
                                                </span> <span>Vincular cuenta de GMAIL</span></a><br>
                                            <small>Vincula tu cuenta de GMAIL para poder iniciar sesión.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="not_gmail" <?php echo $row['gm_id'] != "" ? "":"style='display:none'";?>>
                                        <br>
                                        <div class="form-group">
                                            <a href="javascript:;" onclick="desvinGM()" style="background:#eee5ff;color:#8950FC; width:100%;" class="btn btn-light-primary font-weight-bolder">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641) " />
                                                            <path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z"
                                                                  fill="#f64e8b" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359) " />
                                                            <path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146) " />
                                                            <path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z"
                                                                  fill="#f64e8b" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961) " />
                                                        </g>
                                                    </svg>
                                                </span> <span id="gm_text">Desvincular
                                                    <?php echo $row['gm_email'];?></a><br>
                                            <input type="hidden" name="gm_id" value="<?php echo $row['gm_id']; ?>" id="gm_id">
                                            <input type="hidden" name="gm_email" value="<?php echo $row['gm_email']; ?>" id="gm_email">
                                            <small>Vincula tu cuenta de GMAIL para poder iniciar sesión.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <div class="input-group">
                                                <textarea class="form-control" name='address' aria-label="Text input with checkbox"><?php echo $row['address'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary font-weight-bolder" data-dismiss="modal" id="submit">Aplicar cambios</button>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function searchEmail() {
    var email = $('#email').val();
    var ID = '<?php echo $row['admin_id']?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#ms_error').html(" ");
                $('#submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#ms_error').html("Correo eléctronico no disponible");
                $('#submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#ms_error').html(" ");
                $('#submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });


}

function comparar_pass() {
    var in_pass = $('#password').val();
    var new_pass = SHA1(in_pass);
    var conf_pass =
        '<?php echo $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->password;?>';
    if (new_pass == conf_pass || in_pass == '') {
        $('#mensaje_error').show();
        $('#mensgg').html(" ");
        $('#submit').removeAttr('disabled');
    } else {
        $('#mensaje_error').hide();
        $('#mensgg').html("NO COINCIDE LA CONTRASEÑA");
        $('#submit').attr('disabled', 'disabled');
    }
}

function SHA1(msg) {
    function rotate_left(n, s) {
        var t4 = (n << s) | (n >>> (32 - s));
        return t4;
    };

    function lsb_hex(val) {
        var str = '';
        var i;
        var vh;
        var vl;
        for (i = 0; i <= 6; i += 2) {
            vh = (val >>> (i * 4 + 4)) & 0x0f;
            vl = (val >>> (i * 4)) & 0x0f;
            str += vh.toString(16) + vl.toString(16);
        }
        return str;
    };

    function cvt_hex(val) {
        var str = '';
        var i;
        var v;
        for (i = 7; i >= 0; i--) {
            v = (val >>> (i * 4)) & 0x0f;
            str += v.toString(16);
        }
        return str;
    };

    function Utf8Encode(string) {
        string = string.replace(/\r\n/g, '\n');
        var utftext = '';
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    };
    var blockstart;
    var i, j;
    var W = new Array(80);
    var H0 = 0x67452301;
    var H1 = 0xEFCDAB89;
    var H2 = 0x98BADCFE;
    var H3 = 0x10325476;
    var H4 = 0xC3D2E1F0;
    var A, B, C, D, E;
    var temp;
    msg = Utf8Encode(msg);
    var msg_len = msg.length;
    var word_array = new Array();
    for (i = 0; i < msg_len - 3; i += 4) {
        j = msg.charCodeAt(i) << 24 | msg.charCodeAt(i + 1) << 16 |
            msg.charCodeAt(i + 2) << 8 | msg.charCodeAt(i + 3);
        word_array.push(j);
    }
    switch (msg_len % 4) {
        case 0:
            i = 0x080000000;
            break;
        case 1:
            i = msg.charCodeAt(msg_len - 1) << 24 | 0x0800000;
            break;
        case 2:
            i = msg.charCodeAt(msg_len - 2) << 24 | msg.charCodeAt(msg_len - 1) << 16 | 0x08000;
            break;
        case 3:
            i = msg.charCodeAt(msg_len - 3) << 24 | msg.charCodeAt(msg_len - 2) << 16 | msg.charCodeAt(msg_len - 1) <<
                8 | 0x80;
            break;
    }
    word_array.push(i);
    while ((word_array.length % 16) != 14) word_array.push(0);
    word_array.push(msg_len >>> 29);
    word_array.push((msg_len << 3) & 0x0ffffffff);
    for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
        for (i = 0; i < 16; i++) W[i] = word_array[blockstart + i];
        for (i = 16; i <= 79; i++) W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
        A = H0;
        B = H1;
        C = H2;
        D = H3;
        E = H4;
        for (i = 0; i <= 19; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 20; i <= 39; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 40; i <= 59; i++) {
            temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        for (i = 60; i <= 79; i++) {
            temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
            E = D;
            D = C;
            C = rotate_left(B, 30);
            B = A;
            A = temp;
        }
        H0 = (H0 + A) & 0x0ffffffff;
        H1 = (H1 + B) & 0x0ffffffff;
        H2 = (H2 + C) & 0x0ffffffff;
        H3 = (H3 + D) & 0x0ffffffff;
        H4 = (H4 + E) & 0x0ffffffff;
    }
    var temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);

    return temp.toLowerCase();
}
</script>
<script src="https://apis.google.com/js/platform.js?onload=onLoadGoogleCallback" async defer></script>
<script>
function onLoadGoogleCallback() {
    gapi.load('auth2', function() {
        auth2 = gapi.auth2.init({
            client_id: '1072535742840-881cva9qvf26amdrj7pgbvknb9t9ddui.apps.googleusercontent.com',
            cookiepolicy: 'single_host_origin',
            scope: 'profile'
        });

        auth2.attachClickHandler(element, {},
            function(googleUser) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: 'Correo vincualdo corectamente!'
                })
                $("#gmail").hide();
                $("#not_gmail").show();

                $('#gm_id').val(googleUser.getBasicProfile().getId());
                $('#gm_email').val(googleUser.getBasicProfile().getEmail());
                $('#gm_text').html("Desvincular " + googleUser.getBasicProfile().getEmail());
            },
            function(error) {
                console.log('Sign-in error', error);
            }
        );
    });

    element = document.getElementById('googleSignIn');
}
</script>
<script type="text/javascript">
function onLoadImage(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName')
            .innerHTML = files[0].name;
        $('#imgLabel').show(500);
    } else {
        document
            .getElementById('imgName')
            .innerHTML = 'Ninguno';
        $('#imgLabel').hide(500);
    }
}

function ocultarlabel() {
    document
        .getElementById('imgName')
        .innerHTML = 'Ninguno';
    $('#imgLabel').hide(500);
}

let timerInterval

function desvinGM(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podra iniciar sesión con este correo nuevamente.",
        type: 'info',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, desvincular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: 'Correo desvinculado corectamente!'
            })
            $('#gm_id').val("");

            $("#gmail").show();
            $("#not_gmail").hide();
        }
    })
}
</script>
