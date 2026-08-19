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
        <link rel="shortcut icon" href="<?php echo base_url();?>public/assets/media/logos/favicon.ico" />
        <meta name="google-signin-client_id" content="1072535742840-3meebudl4s0vd1rhe5v9cvmssuqdpo8p.apps.googleusercontent.com">
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
                        <?php 
            		        
            		        $token = $_GET['auth'];
            		       
							$expire = strtotime($this->db->get_where('admin', array('auth_key' => $token))->row()->expire);   

                            $current = strtotime(date('d-m-Y H:i:s'));
							if($current <= $expire):
						?>
                        <div class="d-flex flex-column-fluid flex-column flex-center">
                            <div class="login-form login-signin py-11">
                                <?php echo form_open(base_url().'auth/new_password', array( 'novalidate' => 'novalidate', 'id' => 'kt_login_signin_form' ));?>
                                <input type="hidden" name="gm_id" value="<?php echo $row['gm_id']; ?>" id="gm_id">
                                <div class="text-center pb-8">
                                    <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Registra una nueva contraseña</h2>
                                    <?php if($this->session->flashdata('error') != ""):?>
                                    <span style="color:red"><?php echo $this->session->flashdata('error');?></span>
                                    <?php endif;?>
                                    <br>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mt-n5">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">Nueva Contraseña</label>
                                    </div>
                                    <input type="hidden" name="auth_key" value="<?php echo $token; ?>" />
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" required="" placeholder="Nueva contraseña" autocomplete="off" />
                                </div>
                                <div class="text-center pt-2">
                                    <button id="kt_login_signin_submit" class="btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3 lgbtn" type="submit">Actualizar contraseña</button>
                                </div>
                                <?php echo form_close();?>
                            </div>

                        </div>
                        <?php else:
						?>
                        <div class="d-flex flex-column-fluid flex-column flex-center">
                            <div class="login-form login-signin py-11">
                                <?php echo form_open(base_url().'auth/secure/login', array( 'novalidate' => 'novalidate', 'id' => 'kt_login_signin_form' ));?>
                                <input type="hidden" name="gm_id" value="<?php echo $row['gm_id']; ?>" id="gm_id">
                                <div class="text-center pb-8">
                                    <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">¡Oops!</h2>
                                    <?php if($this->session->flashdata('error') != ""):?>
                                    <span style="color:red"><?php echo $this->session->flashdata('error');?></span>
                                    <?php endif;?>
                                    <br>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mt-n5">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">
                                            <div class="alert alert-danger">
                                                Parece que el enlace que estás utilizando ha expirado. Solicita uno nuevo <a href="<?php echo base_url();?>">aquí</a>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <?php echo form_close();?>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
                    <div class="d-flex flex-column justify-content-center text-center pt-lg-40 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                        <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">El nuevo hogar para tu negocio en la nube</h3>
                        <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70" style="font-size: 1.35rem !important;font-weight: 500!important;">MSBox es el software CRM que se adapta a tu negocio <br>y agiliza su funcionamiento diario.</p>
                        <center><img src="<?php echo base_url();?>public/assets/media/bg/fondos.png" style="max-width:65%;padding-top:10%;"></center>
                    </div>
                </div>
            </div>
        </div>
        <script>
        //Validar correo electronico  
        function validateEmail() {

            var email = $('input[name ="email"]').val();
            console.log(email);

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


                        $('#errorm').show();
                        $('#successm').hide();
                        $('input[name ="email"]')[0].setCustomValidity('correo electronico no disponible');

                    } else {
                        $('#kt_login_forgot_form').submit();

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error: ' + errorThrown);
                }

            });

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
                    client_id: '1072535742840-3meebudl4s0vd1rhe5v9cvmssuqdpo8p.apps.googleusercontent.com',
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
