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
            <div class="row">
                <div class="col-sm-6" style="float: none; margin: 0 auto;">
                    <form action="<?php echo base_url();?>installer/continue" method="post" enctype="multipart/form-data">
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
                                - <span class="bold" style="color:#99bf2d">Versión de PHP <?php echo phpversion(); ?></span>
                                <input type="hidden" name="phpVersion" value="1">
                                <?php 
                                    else:
                                        $error = 1;
                                ?>
                                - <span class="bold" style="color:#e13c38">La versión de php debe ser igual a 7.2 versión detectada <b><?php echo phpversion(); ?></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php endif;?>
                                <br><br>
                                - <span class="bold" style="color:#99bf2d">La subida máxima de archivos es de <b><?php  echo $maxFileSize = ini_get('upload_max_filesize');; ?></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <br><br>
                                - <span class="bold" style="color:#99bf2d">El tiempo de subida por archivo es correcto <b><?php echo $maxInputSime = ini_get('max_input_time'); ?></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <br><br>
                                - <span class="bold" style="color:#99bf2d">La memoria límite por archivos es de <b><?php echo $memoryLimit = ini_get('memory_limit'); ?></b></span>
                                <input type="hidden" name="phpVersion" value="0">

                                <br><br>
                                <?php
                                if (is_writable('./application/config/database.php')):?>
                                - <span class="bold" style="color:#99bf2d">El archivo config/database.php si puede ser editado. </b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php else:?>
                                - <span class="bold" style="color:#e13c38">El archivo config/database.php debe poder editarse. <b></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php endif;?>
                                <br><br>
                                <?php
                                if (is_writable('./application/config/routes.php')):?>
                                - <span class="bold" style="color:#99bf2d">El archivo config/ruotes.php si puede ser editado. </b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php else:?>
                                - <span class="bold" style="color:#e13c38">El archivo config/ruotes.php debe poder editarse. <b></b></span>
                                <input type="hidden" name="phpVersion" value="0">
                                <?php endif;?>
                                <br><br>
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
                                    <input type="text" required="" class="form-control" placeholder="B9GN2-DXXQC-9DHKT-GGWCR-4X6X1" name="key">
                                    <small>(Se te entregó al momento de tu compra)</small>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="border-radius:25px;" <?php if($error != 0){ echo 'disabled';};?>>Continuar instalación</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div style="height: 150px;"></div>
        <div class="footer">
            <p style="padding-top:15px">MSBox - Un producto desarrollado y distribuido bajo licencia <b>Software Propietario</b> por <a href="https://mayansource.com/" target="_blank"><b style="color:white">MAYANSOURCE</b></a>.</p>
        </div>
        <script src="<?php echo base_url();?>public/assets/installer/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/jquery.min.js"></script>
    </body>
