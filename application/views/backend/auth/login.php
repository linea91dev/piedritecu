<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" />
        <title>Autenticarse</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
		<link href="<?php echo base_url();?>acceso/login-3.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>acceso/plugins.bundle.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>acceso/prismjs.bundle.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>acceso/style.bundle.css" rel="stylesheet" type="text/css"/>
	</head>
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<div class="d-flex flex-column flex-root">
			<div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
                <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid" style="background-image: url(<?php echo base_url();?>acceso/bg-login.png);">
                    <div class="login-form text-center text-white p-7 position-relative overflow-hidden">
                        <div id="sign-in" class="login-signin">
                            <?php if($this->session->flashdata('error') != ''):?>
                            <div class="alert alert-danger mb-10">
                                <?php echo $this->session->flashdata('error');?>
                            </div>
                            <?php endif;?>
                            <div class="mb-10">
                                <h2>Autenticarse</h2>
                            </div>
                            <?php echo form_open(base_url().'logueo/access/login');?>
                                <div class="form-group">
                                    <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5" placeholder="Usuario" type="text" name="username" id="usuario" autocomplete="off" required/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5" placeholder="Contraseña" type="password" name="password" id="password" autocomplete="off" required/>
                                </div>
                                <div class="form-group d-flex flex-wrap justify-content-center align-items-center px-8">
                                    <div class="checkbox-inline"></div>
                                    <a href="javascript:void(0);" id="kt_login_forgot" class="text-white font-weight-bold">¿Olvidaste tu contraseña?</a>
                                </div>
                                <div class="form-group text-center mt-10">
                                    <button id="kt_login_signin_submit" type="submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3">Acceder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="d-flex justify-content-center align-items-center text-white py-3" style="position: absolute; bottom: 10px; width: 100%; font-size: 12px; opacity: 0.7;">
			<b>&copy; <?php echo date('Y');?> todos los derechos reservados.</b>
		</div>
	</body>
</html>