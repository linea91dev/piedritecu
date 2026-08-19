<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css?family=Rubik" rel="stylesheet">
        <meta name="viewport" content="width=device-width" />
    </head>

    <body style="background-color: #222533; padding: 20px; font-family: font-size: 14px; line-height: 1.43; font-family: 'Rubik', sans-serif;">
        <div width="100%" style="background: #222533; padding: 0px; line-height:28px; height:100%;  width: 100%; color: #606060; ">
            <div style="max-width: 600px; margin: 0px auto; background-color: #fff; box-shadow: 0px 20px 50px rgba(0,0,0,0.05);">
                <table style="width: 100%;">
                    <tr>
                        <td style="background-color: #fff;">
                            <center><img alt="" src="<?php echo base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description;?>" style="width: 250px; padding: 20px"></center>
                        </td>
                    </tr>
                </table>
                <div style="padding: 60px 70px; border-top: 1px solid rgba(0,0,0,0.05);">
                    <h1 style="margin-top: 0px;"><?php echo $asuntico;?>.</h1>
                    <div style="color: #636363; font-size: 14px;">
                        <p><?php echo $email_msg;?></p>
                    </div><br>
                    <h4 style="margin-bottom: 10px;">¿Necesitas ayuda?</h4>
                    <div style="color: #A5A5A5; font-size: 12px;">
                        <p>Si tienes alguna pregunta, escríbe a contacto@mayansource.com</p>
                    </div>
                </div>
                <div style="background-color: #F5F5F5; padding: 40px; text-align: center;">
                    <div style="margin-bottom: 20px;">
                        <a href="https://www.facebook.com/msbox.gt/" style="display: inline-block; margin: 0px 10px;"><img alt="" src="<?php echo base_url();?>assets/theme/img/facebook.png" style="width: 28px;"></a>
                        <a href="https://www.facebook.com/msbox.gt/" style="display: inline-block; margin: 0px 10px;"><img alt="" src="<?php echo base_url();?>assets/theme/img/instagram.png" style="width: 28px;"></a>
                    </div>
                    <div style="color: #A5A5A5; font-size: 12px; margin-bottom: 20px; padding: 0px 50px;">
                        Recibes este correo porque han contactado a través del sitio web <b>https://msbox.gt</b>.
                    </div>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
                        <div style="color: #A5A5A5; font-size: 10px; margin-bottom: 5px;">
                            Torre Pradera Xela noveno nivel oficina 908. Quetzaltenango, Guatemala 09001
                        </div>
                        <div style="color: #A5A5A5; font-size: 10px;">
                            Copyright <?php echo date('Y');?> <b>MsBox</b>. Todos los derechos reservados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
