<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Perfil de usuario
                            <span class="d-block text-muted pt-2 font-size-sm">Para evitar problemas de comunicación
                                entre el sistema y tú, manten tus datos actualizados.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/admins' ;?>" class="btn btn-light-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                              fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Regresar
                        </a>&nbsp;&nbsp;
                        <a href="javascript:;" onclick="executeExample('<?php echo $admin_id;?>')" class="btn btn-light-danger font-weight-bolder">
                            <span class="svg-icon svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M4,16 L5,16 C5.55228475,16 6,16.4477153 6,17 C6,17.5522847 5.55228475,18 5,18 L4,18 C3.44771525,18 3,17.5522847 3,17 C3,16.4477153 3.44771525,16 4,16 Z M1,11 L5,11 C5.55228475,11 6,11.4477153 6,12 C6,12.5522847 5.55228475,13 5,13 L1,13 C0.44771525,13 6.76353751e-17,12.5522847 0,12 C-6.76353751e-17,11.4477153 0.44771525,11 1,11 Z M3,6 L5,6 C5.55228475,6 6,6.44771525 6,7 C6,7.55228475 5.55228475,8 5,8 L3,8 C2.44771525,8 2,7.55228475 2,7 C2,6.44771525 2.44771525,6 3,6 Z"
                                              fill="#000000" opacity="0.3" />
                                        <path d="M10,6 L22,6 C23.1045695,6 24,6.8954305 24,8 L24,16 C24,17.1045695 23.1045695,18 22,18 L10,18 C8.8954305,18 8,17.1045695 8,16 L8,8 C8,6.8954305 8.8954305,6 10,6 Z M21.0849395,8.0718316 L16,10.7185839 L10.9150605,8.0718316 C10.6132433,7.91473331 10.2368262,8.02389331 10.0743092,8.31564728 C9.91179228,8.60740125 10.0247174,8.9712679 10.3265346,9.12836619 L15.705737,11.9282847 C15.8894428,12.0239051 16.1105572,12.0239051 16.294263,11.9282847 L21.6734654,9.12836619 C21.9752826,8.9712679 22.0882077,8.60740125 21.9256908,8.31564728 C21.7631738,8.02389331 21.3867567,7.91473331 21.0849395,8.0718316 Z"
                                              fill="#000000" />
                                    </g>
                                </svg>
                            </span> Reenvíar accesos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <form class="form" action="<?php echo base_url().'admin/admins/update/'.$admin_id;?>" method="POST" enctype="multipart/form-data">
                                <?php $data = $this->db->limit(1)->get_where('admin', array('admin_id'=>$admin_id))->result_array(); foreach ($data as $row):?>
                                <div class="row center">
                                    <div class="col-sm-12">
                                        <center>
                                            <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-color: #fff">
                                                <div class="image-input-wrapper" <?php $initial = strtoupper($this->db->get_where('admin', array('admin_id' => $admin_id))->row()->name[0]);?>
                                                     style="background-image: url(<?php echo ($row['img']!='')?  base_url().'uploads/img/'.$row['img'] : base_url().'uploads/avatars/'.$initial.'.svg';?>);background-size:contain; background-position: center;">
                                                </div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar foto">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="img" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="profile_avatar_remove" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar cambio">
                                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                </span>
                                            </div>
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
                                                <input type="number" min='0' class="form-control" aria-label="Text input with checkbox" oninput="if(value.length>8)value=value.slice(0,8)" value='<?php echo $row['phone'];?>' name='phone' />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Código de acceso</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" value='<?php echo $row['username'];?>' disabled />
                                                <input type="hidden" name='username' value='<?php echo $row['username'];?>'>
                                            </div>
                                            <small>Será con el que iniciará sesión.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="kt_datepicker" readonly aria-label="Text input with checkbox" required name='birthday' value='<?php echo date('m/d/Y', strtotime($row['birthday']));?>' />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fecha de contratación</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="kt_datepicker_!" readonly aria-label="Text input with checkbox" name='hiring' value='<?php echo date('m/d/Y', strtotime($row['hiring']));?>' />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Salario</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" aria-label="Text input with checkbox" min='0' value='<?php echo $row['salary'];?>' name='salary' />
                                            </div>
                                            <small>Si no aplica dejar vacío o ingresar 0.</small>
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

                                            <label>Correo electrónico <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name='email' id='email' aria-label="Text input with checkbox" value='<?php echo $row['email'];?>' oninput="searchEmail()" onblur="searchEmail()" required="true" />
                                            </div>
                                            <label id="ms_error" class="control-label text-danger"></label>
                                            <small>Recibirás notificaciones y podrás recuperar tu
                                                contraseña.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nueva contraseña</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" name='new_password' />
                                            </div>
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

                                    <?php if($user_type == 1 || $permisos['editar_admins'] == 1):?>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary font-weight-bolder" style='float: left;' id="submit" data-dismiss="modal">Aplicar
                                            cambios</button>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <?php endforeach;?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <h4><a href="<?php echo base_url().'admin/actividad/'.$admin_id;?>"> Actividades </a>
                            </h4>
                            <?php $actividades = $this->db->limit(10)->order_by('binnacle_id', 'DESC')->get_where('binnacle', array('user_id'=>$admin_id)); if($actividades-> num_rows()>0): foreach ($actividades->result_array() as $ac):?>
                            <div class="col-sm-12">
                                <p class='timeline-label text-dark-75 font-size-lg'>
                                    <b><?php  setlocale(LC_TIME, "spanish"); $Nueva_Fecha = date("d-m-Y", strtotime( $ac['date'])); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));  echo $Mes_Anyo; ?></b>
                                </p>
                            </div>
                            <div class="col-sm-12">
                                <p class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                                    <?php echo $ac['message'];?>
                                </p>
                            </div>
                            <?php endforeach; else:?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
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
    var email = $('#email_admin_profile').val();
    var ID = '<?php echo $admin_id?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_admin_profile').html(" ");
                $('#profile_admin_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_admin_profile').html("Correo eléctronico no disponible");
                $('#profile_admin_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_admin_profile').html(" ");
                $('#profile_admin_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

var avatar4 = new KTImageInput('kt_image_4');
avatar4.on('cancel', function(imageInput) {
    swal.fire({
        title: 'Image successfully canceled !',
        type: 'success',
        buttonsStyling: false,
        confirmButtonText: 'Awesome!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

avatar4.on('change', function(imageInput) {
    swal.fire({
        title: 'Image successfully changed !',
        type: 'success',
        buttonsStyling: false,
        confirmButtonText: 'Awesome!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

avatar4.on('remove', function(imageInput) {
    swal.fire({
        title: 'Image successfully removed !',
        type: 'error',
        buttonsStyling: false,
        confirmButtonText: 'Got it!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

function comparar_pass() {
    var in_pass = $('#password').val();
    var new_pass = SHA1(in_pass);
    var conf_pass = '<?php echo $this->db->get_where('admin', array('admin_id' => $ID))->row()->password;?>';
    if (new_pass == conf_pass || in_pass == '') {
        $('#mensaje_error').show();
        $('#mensgg').html(" ");
        $('#profile_admin_submit').removeAttr('disabled');
    } else {
        $('#mensaje_error').hide();
        $('#mensgg').html("NO COINCIDE LA CONTRASEÑA");
        $('#profile_admin_submit').attr('disabled', 'disabled');
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
let timerInterval

function executeExample(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Enviaremos el código y contraseña al correo del usuario",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Enviando información',
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
            location.href = "<?php echo base_url();?>admin/admins/credenciales/" + _id + "/1";
        }
    })
}
</script>
