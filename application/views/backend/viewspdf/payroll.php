<!doctype html>
<?php ini_set("memory_limit","500M");?>
<html>
    <head> <meta charset="gb18030"> </head>
    <?php
    $moneda = $this->crud_model->get_info("moneda");
    $branch_id = $this->session->userdata('branch_id');
    $payroll_row = $this->db->get_where('payroll', array('payroll_id' => $ID, 'branch_id' => $branch_id))->row_array();
    $payroll_name = !empty($payroll_row['payroll_name']) ? $payroll_row['payroll_name'] : 'Oficial';
    $is_oficial = ($payroll_name === 'Oficial');
    $is_bonus = in_array($payroll_name, array('Bono 14', 'Aguinaldo'), true);
    $show_other_discount = in_array($payroll_name, array('Oficial', 'Interna'), true);
    $payroll_title = in_array($payroll_name, array('Oficial', 'Interna'), true)
        ? 'Planilla '.strtolower($payroll_name)
        : $payroll_name;
    $amount_label = $is_bonus ? 'Monto' : 'Sueldo';
    ?>
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
                        <td colspan="2">
                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                <tr>
                                    <td style="padding-bottom: 20px; vertical-align: top;">
                                    </td>
                                    <td style="padding-bottom: 20px; vertical-align: top;text-align:center;padding-top:5px;">
                                        <p></p>
                                        <p style="text-transform:uppercase"><b><u><?php echo htmlspecialchars($payroll_title, ENT_QUOTES, 'UTF-8'); ?></u></b></p>
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
                                                    style="font-weight:bold; text-transform:uppercase"><?php echo date('d/m/Y H:i a');?></small></b>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0"
                    style="border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;width: 100%;line-height: inherit;text-align: left;">
                    <tr>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="3">
                            Fecha de inicio
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Fecha de final
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Origen
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Empleado
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            <?php echo $amount_label; ?>
                        </td>
                        <?php if ($is_oficial): ?>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Descuento IGSS
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            ISR
                        </td>
                        <?php endif; ?>
                        <?php if ($show_other_discount): ?>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Descuentos
                        </td>
                        <?php endif; ?>
                        <?php if ($is_oficial): ?>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Bonificación decreto 
                        </td>
                        <?php endif; ?>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Total
                        </td>
                        <td style="border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #eee;font-style:italic; font-weight:bold;padding:5px;"
                            colspan="4">
                            Notas
                        </td>
                    </tr>
                    <?php $branch_id = $this->session->userdata('branch_id');
                    $datoss = $this->db->get_where('payroll', array('payroll_id'=>$ID, 'branch_id'=>$branch_id));
                        foreach($datoss->result_array() as $rows):
                            if ($rows['employee'] != "" || $rows['employee'] != null) {
                                $employee = json_decode($rows['employee'],true);
                            } else {
                                $employee = array();
                            }
                            // Calcular totales
                            $total_salary = 0;
                            $total_discount = 0;
                            $total_advance = 0;
                            $total_other_discount = 0;
                            $total_remuneration = 0;
                            $total_sub = 0;
                            foreach($employee as $emp) {
                                $total_salary += floatval($emp['salary'] ?? 0);
                                $total_discount += floatval($emp['discount'] ?? 0);
                                $total_advance += floatval($emp['advance'] ?? 0);
                                $total_other_discount += floatval($emp['other_discount'] ?? 0);
                                $total_remuneration += floatval($emp['remuneration'] ?? 0);
                                $total_sub += floatval($emp['sub'] ?? 0);
                            }
                            // Iterar sobre todos los empleados
                            foreach($employee as $i => $emp):
                    ?>
                    <tr>
                        <td colspan="3"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;padding-top:15px;font-size: 15px;">
                            <?php 
                            $row_date_start = !empty($emp['date_start']) ? $emp['date_start'] : $rows['date_start'];
                            $fecha = new DateTime($row_date_start);
                            echo $fecha->format('d-m-Y');
                            ?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php 
                            $row_date_end = !empty($emp['date_end']) ? $emp['date_end'] : $rows['date_end'];
                            $fecha = new DateTime($row_date_end);
                            echo $fecha->format('d-m-Y');
                            ?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php if ($rows['bank'] == 0){ 
                                    echo 'Caja Chica';
                                }
                                else {
                                    $name_account = $this->db->get_where('account_bank', array('account_bank_id'=>$rows['bank']))->row()->name_account;
                                    $bank_id = $this->db->get_where('account_bank', array('account_bank_id'=>$rows['bank']))->row()->bank_id;
                                    $bank_name = $this->db->get_where('bank', array('bank_id'=> $bank_id))->row()->name;
                                    echo '('.$bank_name.') - '.$name_account;
                                }
                            ?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $this->crud_model->getName('admin',$emp['employee']);?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.$emp['salary'];?>
                        </td>
                        <?php if ($is_oficial): ?>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.$emp['discount'];?>
                        </td>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.$emp['advance'];?>
                        </td>
                        <?php endif; ?>
                        <?php if ($show_other_discount): ?>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.number_format(floatval($emp['other_discount'] ?? 0), 2, '.', ',');?>
                        </td>
                        <?php endif; ?>
                        <?php if ($is_oficial): ?>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.$emp['remuneration'];?>
                        </td>
                        <?php endif; ?>
                        <td colspan="4"
                            style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 15px; text-align:center;">
                            <?php echo $moneda.$emp['sub'];?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:left"
                            colspan="4">
                            <?php echo $emp['note'];?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Fila de totales -->
                    <tr style="background-color: #f3f6f9; font-weight: bold;">
                        <td colspan="3" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #ddd;font-size: 14px;">
                            TOTALES
                        </td>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #ddd;font-size: 14px;">
                            
                        </td>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #ddd;font-size: 14px;">
                            
                        </td>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black;background: #ddd;font-size: 14px;">
                            
                        </td>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_salary,2,'.',',');?>
                        </td>
                        <?php if ($is_oficial): ?>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_discount,2,'.',',');?>
                        </td>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_advance,2,'.',',');?>
                        </td>
                        <?php endif; ?>
                        <?php if ($show_other_discount): ?>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_other_discount,2,'.',',');?>
                        </td>
                        <?php endif; ?>
                        <?php if ($is_oficial): ?>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_remuneration,2,'.',',');?>
                        </td>
                        <?php endif; ?>
                        <td colspan="4" style="padding:15px; border-right: 1px solid black;border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding-top:15px;font-size: 14px; text-align:center; background: #ddd;">
                            <?php echo $moneda.number_format($total_sub,2,'.',',');?>
                        </td>
                        <td style="padding:15px;font-size: 12px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;background: #ddd;"
                            colspan="4">
                            
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </main>
    </body>
</html>