<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="margin:0px; background: #f8f8f8; ">
<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
  <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
      <tbody>
        <tr>
          <td style="vertical-align: top; padding-bottom:30px;" align="center"><a href="<?php echo base_url();?>" target="_blank"><img src="<?php echo base_url();?>uploads/logo_color.png" style="border:none"><br/>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
      <tbody>
        <tr>
          <td style="background:#1e88e5; padding:20px; color:#fff; text-align:center;"> <?php echo $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;?> </td>
        </tr>
      </tbody>
    </table>
    <div style="padding: 40px; background: #fff;">
      <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tbody>
          <tr>
            <td><b><?php echo $cname;?></b>
              <p style="margin-top:0px;"><?php echo $email_msg;?></p></td>
            <td align="right" width="100"> <?php echo date('d M, Y');?> </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:20px 0; border-top:1px solid #f6f6f6;"><div>
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tbody>
                   <?php for($x = 0; $x < count($ids); $x++):?>
                    <tr>
                      <td style="font-family: 'arial'; font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0;"><?php echo $this->db->get_where('business', array('slug' => $ids[$x]))->row()->title;?></td>
                      <td style="font-family: 'arial'; font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0;"  align="right"><a href="<?php echo base_url();?>place/<?php echo $this->db->get_where('business', array('slug' => $ids[$x]))->row()->slug;?>/" style="padding: 11px 30px; margin: 20px 0px 30px; display: inline-block; font-size: 15px; color: #fff; background: #ff214f; border-radius: 60px; text-decoration:none;">Detalles</a></td>
                    </tr>
                    <?php endfor;?>
                  </tbody>
                </table>
              </div></td>
          </tr>
          <tr>
            <td colspan="2"><center>
                <a href="<?php echo base_url();?>" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #1e88e5; border-radius: 60px; text-decoration:none;">Ir al sitio</a>
              </center>
              <b>- Thanks (Admin team)</b> </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
      <p>
        <a href="<?php echo base_url();?>unsubscribe/<?php echo $email;?>" style="color: #b2b2b5; text-decoration: underline;">Eliminar suscripción</a> </p>
    </div>
  </div>
</div>
</body>
</html>
