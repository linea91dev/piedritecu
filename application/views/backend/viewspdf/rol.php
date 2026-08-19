<!doctype html>
<?php ini_set("memory_limit","500M"); ?>
<html>
    <head> <meta charset="gb18030"> </head>
    <?php $moneda = $this->crud_model->get_info("moneda");?>
    <body>
        <header style="text-align: center; margin-top: -25px !important;">
            <img src="<?php echo base_url().'uploads/img/'.$this->crud_model->get_info('logo');?>" style="width: 75px; height: auto; border-radius: 15px;" />
            <div style="position:relative;">
                <b style="font-size: 12px;"><span><?php echo $this->crud_model->get_info('name');?></span></b>
                <p style="font-size: 12px; margin-top: 0px;">
                    <b><?php echo $this->crud_model->get_info('slogan');?></b><br>
                    <b><?php echo $this->crud_model->get_info('phone');?></b><br>
                    <b><?php echo $this->crud_model->get_info('email');?></b>
                </p>
            </div>
        </header>
        <main>
            <div style="width:100%; font-size: 16px; line-height: 24px; font-family: 'nunito'; color: #555;">

                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                    <tr>
                        <td colspan="4">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u>Reporte de Compra</u></b></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 12px;">Generado por:
                                            <span><?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?></span></b>
                                        <p style="font-size: 12px;">Fecha: <b><small
                                                    style="font-weight:bold; text-transform:uppercase"><?php echo date('d/m/Y h:i a');?></small></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php $datoss = $this->db->get_where('job', array('job_id'=>$ID))->row_array();?>
                    <tr>
                        <td colspan="4">
                            <table style="width: 100%;line-height: inherit;text-align: center;">
                                <tr style="">
                                    <td style="padding-top:15px;padding-bottom: 15px;">
                                        <b style="font-size: 15px;">Rol: <span><?php echo $datoss['name'];?></span></b>
                                        <p style="font-size: 15px;">Descripción:
                                            <b><?php echo ($datoss['description'] != null || $datoss['description'] != '') ? $datoss['description'] : 'Sin descripción'; ?></b>
                                        </p>
                                        <?php if($datoss['status']==1 ){
                                                $stado = 'Activo';
                                            }else{
                                                $stado = 'Inactivo';
                                            }?>
                                        <p style="font-size: 15px;">Estado:
                                            <b><?php echo $stado; ?></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0"
                    style="border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;width: 100%;line-height: inherit;">
                    <tr>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            No.
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;text-align: left;">
                            Permiso
                        </td>
                    </tr>
                    <?php $permisos = unserialize($datoss['permissions']); $count = count($permisos); $n = 1; ?>
                    <?php foreach ($permisos as $per):?>
                    <tr>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                            <?php echo $n++;?>
                        </td>
                        <td style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 12px;">
                            <span style="font-weight:bold; text-transform:uppercase"><?php echo $per;?></span>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </main>
    </body>
</html>