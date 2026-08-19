<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8" />
        <title>Acceso administrativo | MSBox</title>
        <meta name="description" content="Login page example" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="canonical" href="https://mayansource.dev/msbox/" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="<?php echo base_url();?>public/assets/css/pages/login/login-2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>public/assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo base_url();?>public/assets/media/logos/<?php echo $this->db->get_where('settings', array('type'=>'favico'))->row()->description;?>" />
        <style>
        .lgbtn {
            background-color: #ffb319 !important;
            border: #ffb319;
        }

        .swal2-confirm {
            background: #ffb319 !important;
        }

        </style>
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        <div class="d-flex flex-column flex-root">
            <div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
                <div class="login-aside order-2 order-lg-1 d-flex flex-row-auto position-relative overflow-hidden">
                    <div class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13 px-lg-35">
                        <a href="#" class="text-center pt-2">
                            <img src="https://mayansource.com/front/img/core-img/logo.png" class="max-h-75px" alt="" />
                        </a>
                        <div class="d-flex flex-column-fluid flex-column flex-center">
                            <div class="login-form login-signin py-11">
                                <?php echo form_open(base_url().'auth/secure/login', array( 'novalidate' => 'novalidate', 'id' => 'kt_login_signin_form' ));?>
                                <input type="hidden" name="gm_id" value="<?php echo $row['gm_id']; ?>" id="gm_id">

                                <div class="text-center pb-8">
                                    <img alt="Logo" src="<?php echo base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description; ?>"
                                         style="max-width: 300px!important; height: auto; border-radius:10px;" />
                                    <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Accede a tu cuenta
                                    </h2>
                                    <?php if($this->session->flashdata('error') != ""):?>
                                    <span style="color:red"><?php echo $this->session->flashdata('error');?></span>
                                    <?php endif;?>
                                    <?php if($this->session->flashdata('success') != ""):?>
                                    <span style="color:green"><?php echo $this->session->flashdata('success');?></span>
                                    <?php endif;?>
                                </div>
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Código de cliente</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" name="username" autocomplete="off" placeholder="Ingresa tu código" required="" />
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mt-n5">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">PIN</label>
                                        <div href="javascript:void(0);" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot" style="color: #ffb319!important;    cursor: pointer;">
                                            ¿Olvidaste tu PIN?</div>
                                    </div>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" required="" placeholder="PIN de acceso" autocomplete="off" />
                                </div>
                                <div class="text-center pt-2">
                                    <button id="kt_login_signin_submit" class="btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3 lgbtn" type="submit">Ir a tu cuenta</button>
                                </div>
                                <?php echo form_close();?>
                            </div>
                            <div class="login-form login-forgot pt-11">
                                <form class="form" novalidate="novalidate" id="kt_login_forgot_form" action="<?php echo base_url();?>auth/request" method="post">
                                    <div class="text-center pb-8">
                                        <img alt="Logo" src="<?php echo ($this->db->get_where('settings', array('type'=>'logo'))->row()->description != '')?  base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description : base_url().'public/assets/media/users/blank.png' ;?>"
                                             style="max-width: 300px!important; height: auto;" />
                                        <br>
                                        <br>
                                        <br>
                                        <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">¿Olvidaste tu
                                            PIN?</h2>
                                        <p class="text-muted font-weight-bold font-size-h4">Ingresa el correo asociado a tu
                                            cuenta.</p>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Correo electrónico" name="email" autocomplete="off" />
                                        <span style="color:red; display:none" id="errorm">No se encuentra ningun usuario con
                                            este correo electrónico</span>
                                    </div>
                                    <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                                        <button type="button" onclick="validateEmail()" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4 lgbtn">Continuar</button>
                                        <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="text-center">
                            <button id="googleSignIn" type=" button" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-h6" style="background:#E1F0FF;color:#3699FF">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4" />
                                        <path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853" />
                                        <path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z"
                                              fill="#FBBC05" />
                                        <path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335" />
                                    </svg>
                                </span>Continuar con Google</button>

                        </div>

                    </div>
                </div>
                <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #e5d7ef;">
                    <div class="d-flex flex-column justify-content-center text-center pt-lg-40 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                        <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">El nuevo hogar para
                            tu negocio en la nube</h3>
                        <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70" style="font-size: 1.35rem !important;font-weight: 500!important;">MSBox es el software CRM que
                            se adapta a tu negocio <br>y agiliza su funcionamiento diario.</p>
                        <center><img src="<?php echo base_url();?>public/assets/media/bg/web.png" style="max-width:65%;padding-top:5%;"></center>
                    </div>
                </div>
            </div>
        </div>
        <script>
        //Validar correo electronico  
        function validateEmail() {

            var email = $('input[name ="email"]').val();

            if (email != "") {

                //validar correo electronico
                var validacion_email = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>auth/check_m",
                    data: {
                        'mail': email
                    },
                    success: function(data) {

                        console.log(data);
                        if (data == 0) {

                            $('#errorm').html('No se encuentra ningun usuario con este correo electrónico');
                            $('#errorm').show();
                            $('#successm').hide();

                        } else {
                            $('#kt_login_forgot_form').submit();

                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('error: ' + errorThrown);
                    }

                });

            } else {
                $('#errorm').html('Debe ingresar un correo electrónico');
                $('#errorm').show();
                $('#successm').hide();

            }


        };
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
        </script>
        <script src="<?php echo base_url();?>public/assets/plugins/global/plugins.bundle.js"></script>
        <script src="<?php echo base_url();?>public/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/scripts.bundle.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/pages/custom/login/login-general.js"></script>
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
                        console.log('Signed in: ' + googleUser.getBasicProfile().getId());
                        $('#gm_id').val(googleUser.getBasicProfile().getId());
                        $('#kt_login_signin_form').submit();
                    },
                    function(error) {
                        console.log('Sign-in error', error);
                    }
                );
            });

            element = document.getElementById('googleSignIn');
        }
        </script>
    </body>

</html>
