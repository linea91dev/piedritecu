<!DOCTYPE html>
<html>

    <head>
        <title>Asistente de instalación | MSBox</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>public/assets/media/logos/Isologo.png" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/assets/installer/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <style>
        .bold {
            font-weight: bold !important;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #8950fc;
            color: white;
            text-align: center;
        }

        </style>
        <?php
        function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
            return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
            }

        $error = 0;
        ?>
    </head>

    <body>
        <div style="height: 50px;"></div>
        <center><img src="<?php echo base_url();?>public/assets/installer/images/logo.jpg" width="120px" /></center>
        <div style="height: 30px;"></div>
        <div class="container">
            <h3 class="text-center bold">Asistente de instalación | MSBox</h3>
            <div style="height: 10px;"></div>
            <?php if($type_install == 0): ?>
            <div class="row">
                <div class="col-sm-6" style="float: none; margin: 0 auto;">
                    <form action="<?php echo base_url();?>installer/install" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="type_install" value="0">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if(isLocalhost()):?>
                                <div class="alert alert-warning" style="text-align: justify;">
                                    Parece que estás realizando una instalación <b>local</b>, por favor asegúrate de tener una conexión a internet estable y tu licencia lista. Si necesitas ayuda durante la instalación contacta con nuestro equipo.
                                </div>
                                <?php else:?>
                                <div class="alert alert-info" style="text-align: justify;">
                                    Parece que estás realizando una instalación <b>web</b>, por favor asegúrate de tener una conexión a internet estable y tu licencia lista. Si necesitas ayuda durante la instalación contacta con nuestro equipo.
                                </div>
                                <?php endif;?>
                                <?php if($_SESSION['error'] != ""):?>
                                <div class="alert alert-danger" style="text-align: justify;">
                                    <?php echo $_SESSION['error'];?>
                                </div>
                                <?php endif;?>
                                <div style="height: 10px;"></div>
                            </div>
                            <div class="col-sm-12">
                                <?php 
                                    if(in_array('curl', get_loaded_extensions())):
                                        $error = 0;
                                ?>
                                - <span class="bold" style="color:#99bf2d">cURL está habilitado.</span>
                                <input type="hidden" name="curl" value="1">
                                <?php 
                                    else:
                                    $error = 1;
                                ?>
                                - <span class="bold" style="color:#e13c38">cURL está inhabilitado y es requerido, por favor habilitalo para continuar.</span>
                                <input type="hidden" name="curl" value="0">
                                <?php endif;?>
                                <br><br>
                                <?php
                                     if(in_array('ionCube Loader', get_loaded_extensions())):
                                        $error = 0;
                                ?>
                                - <span class="bold" style="color:#99bf2d">ionCube está habilitado.</span>
                                <input type="hidden" name="ioncube" value="1">
                                <?php 
                                    else:
                                        $error = 1;
                                ?>
                                - <span class="bold" style="color:#e13c38">ionCube está inhabilitado y es requerido, por favor habilitalo para continuar.</span>
                                <input type="hidden" name="ioncube" value="0">
                                <?php endif;?>
                                <br><br>
                                <?php 
                                    if(phpversion() >= 7.2):
                                        $error = 0;
                                ?>
                                - <span class="bold" style="color:#99bf2d">Version de PHP <?php echo phpversion(); ?></span>
                                <input type="hidden" name="phpVersion" value="1">
                                <?php 
                                    else:
                                        $error = 1;
                                ?>
                                - <span class="bold" style="color:#e13c38">La versión de php debe ser igual a 7.2 versión detectada <b><?php echo phpversion(); ?></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php endif;?>

                                <br><br>
                            </div>
                            <div class="col-sm-12"><br>
                                <div style="border-top:1px solid #000;width:100%;padding-bottom:15px;"></div>
                                <label class="bold">ACCESO A LA BASEDE DATOS</label><br><br>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Host:</label>
                                    <input type="text" class="form-control" name="hostname" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Nombre de la base de datos:</label>
                                    <input type="text" class="form-control" name="database" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Usuario:</label>
                                    <input type="text" class="form-control" name="dbusername" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Contraseña:</label>
                                    <input type="text" class="form-control" required name="dbpassword">
                                </div>
                            </div>
                            <div class="col-sm-12"><br>
                                <div style="border-top:1px solid #000;width:100%;padding-bottom:15px;"></div>
                                <label class="bold">DATOS DEL SISTEMA</label><br><br>
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Logotipo:</label>
                                    <input type="file" name="logo" required="" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Nombre del comercio:</label>
                                    <input type="text" name="title_cm" placeholder="Ej: MSBox" required="" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Título del comercio:</label>
                                    <input type="text" name="name_cm" placeholder="Ej: De todo en hierros de construcción." required="" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Teléfono del comercio:</label>
                                    <input type="number" name="phone_cm" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Correo del comercio:</label>
                                    <input type="email" name="email_cm" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="bold">Tipo de instalación:</label><br>
                                    <?php if(isLocalhost()):?>
                                    <span class="bold" style="color:#99bf2d">Instalación Local.</span>
                                    <input type="hidden" name="instalation_tp" class="form-control" value="1">
                                    <?php else:?>
                                    <span class="bold" style="color:#00a1c4">Instalación Web.</span>
                                    <input type="hidden" name="instalation_tp" class="form-control" value="2">
                                    <?php endif;?>
                                </div>
                            </div>
                            <input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']?>" />

                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Licencia:</label>
                                    <input type="text" required="" class="form-control" placeholder="B9GN2-DXXQC-9DHKT-GGWCR-4X6X1" name="key" value="<?php echo $key;?>">
                                    <small>(Puedes obtenerla haciendo clic <a target="_blank" href="https://customer.mayansource.com/">aquí</a>)</small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Dirección del comercio:</label>
                                    <textarea class="form-control" id="exampleTextarea" rows="2" name="address"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12"><br>
                                <div style="border-top:1px solid #000;width:100%;padding-bottom:15px;"></div>
                                <label class="bold">DATOS DEL ADMINISTRADOR</label><br><br>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Nombres:</label>
                                    <input type="text" class="form-control" name="name_ad" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Apellidos:</label>
                                    <input type="text" class="form-control" name="last_name_ad" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Correo:</label>
                                    <input type="email" class="form-control" name="email_ad" required>
                                    <small>(Enviaremos los accesos a esta cuenta)</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="staticEmail" class="bold">Teléfono:</label>
                                    <input type="number" class="form-control" required name="phone_ad">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="border-radius:25px;" <?php if($error != 0){ echo 'disabled';};?>>Completar instalación</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php else:?>

            <div class="row">
                <div class="col-sm-6" style="float: none; margin: 0 auto;">
                    <form action="<?php echo base_url();?>installer/complete_install" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="alert alert-info" style="text-align: justify;">
                                    Parece que ya hay una instalación previa con este código si desea actualizar su dominio y accesos puede continuar, esto desactivará las instalaciones previas, sin embargo siempre serán visibles desde el panel de compradores.
                                </div>
                                <div style="height: 10px;"></div>
                            </div>
                            <div class="col-sm-12">
                            </div>
                            <button type="submit" class="btn btn-primary" style="border-radius:25px;" <?php if($error != 0){ echo 'disabled';};?>>Continuar instalación</button>
                            </fieldset>
                    </form>
                </div>
            </div>

            <?php endif;?>
        </div>
        <div style="height: 150px;"></div>
        <div class="footer">
            <p style="padding-top:15px">MSBox - Un producto desarrollado y distribuido bajo licencia <b>Software Propietario</b> por <a href="https://mayansource.com/" target="_blank"><b style="color:white">MAYANSOURCE</b></a>.</p>
        </div>
        <script src="<?php echo base_url();?>public/assets/installer/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/jquery.min.js"></script>
    </body>
