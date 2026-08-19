<!doctype html>
<?php
ini_set("memory_limit", "500M");
$moneda = $this->crud_model->get_info("moneda");
$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$job_name = 'Sin puesto';
if (!empty($employee['job'])) {
    $job = $this->db->get_where('job', array('job_id' => $employee['job']))->row_array();
    if ($job) {
        $job_name = $job['name'];
    }
}

$branch_names = array();
$branch_ids = !empty($employee['sucursal']) ? @unserialize($employee['sucursal']) : array();
if (is_array($branch_ids)) {
    foreach ($branch_ids as $branch_id) {
        $branch = $this->db->get_where('branch', array('branch_id' => $branch_id))->row_array();
        if ($branch) {
            $branch_names[] = $branch['name'];
        }
    }
}

$format_date = function ($date) {
    if (empty($date) || $date === '0000-00-00') {
        return 'Sin datos';
    }
    return date('d/m/Y', strtotime($date));
};

$cell_label = 'width: 24%; padding: 9px; border: 1px solid #999; background: #eee; font-weight: bold;';
$cell_value = 'width: 26%; padding: 9px; border: 1px solid #999;';
?>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <header style="text-align: center; margin-top: -25px;">
            <img src="<?php echo base_url().'uploads/img/'.$this->crud_model->get_info('logo');?>"
                style="width: 75px; height: auto; border-radius: 15px;" />
            <div>
                <b style="font-size: 12px;"><?php echo $escape($this->crud_model->get_info('name'));?></b>
                <p style="font-size: 12px; margin-top: 0;">
                    <b><?php echo $escape($this->crud_model->get_info('slogan'));?></b><br>
                    <b><?php echo $escape($this->crud_model->get_info('phone'));?></b><br>
                    <b><?php echo $escape($this->crud_model->get_info('email'));?></b>
                </p>
            </div>
        </header>

        <main style="font-family: 'nunito'; color: #555;">
            <h2 style="text-align: center; text-transform: uppercase; margin-top: 15px;">
                <u>Perfil de empleado</u>
            </h2>

            <table style="width: 100%; margin: 18px 0; font-size: 12px;">
                <tr>
                    <td>
                        <b>Generado por:</b>
                        <?php echo $escape($this->crud_model->getName('admin', $this->session->userdata('login_user_id')));?>
                    </td>
                    <td style="text-align: right;">
                        <b>Fecha:</b> <?php echo date('d/m/Y h:i a');?>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; margin-bottom: 18px;">
                <?php if (!empty($employee['img'])): ?>
                    <img src="<?php echo base_url().'uploads/img/'.$escape($employee['img']);?>"
                        style="width: 90px; height: 90px; object-fit: cover; border: 1px solid #aaa; border-radius: 8px;" />
                <?php endif; ?>
                <h3 style="margin: 8px 0 0;">
                    <?php echo $escape(trim($employee['name'].' '.$employee['last_name']));?>
                </h3>
                <span style="font-size: 12px;">
                    <?php echo ((int) $employee['status'] === 1) ? 'Empleado activo' : 'Empleado inactivo';?>
                </span>
            </div>

            <h3 style="margin-bottom: 5px;">Información personal</h3>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tr>
                    <td style="<?php echo $cell_label;?>">CUI</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape(!empty($employee['cui']) ? $employee['cui'] : 'Sin datos');?></td>
                    <td style="<?php echo $cell_label;?>">Fecha de nacimiento</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $format_date($employee['birthday']);?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Celular</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape(!empty($employee['phone']) ? $employee['phone'] : 'Sin datos');?></td>
                    <td style="<?php echo $cell_label;?>">Teléfono de emergencia</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape(!empty($employee['emergency_phone'] ?? '') ? $employee['emergency_phone'] : 'Sin datos');?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Correo electrónico</td>
                    <td style="<?php echo $cell_value;?>" colspan="3"><?php echo $escape(!empty($employee['email']) ? $employee['email'] : 'Sin datos');?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Dirección</td>
                    <td style="<?php echo $cell_value;?>" colspan="3"><?php echo $escape(!empty($employee['address']) ? $employee['address'] : 'Sin datos');?></td>
                </tr>
            </table>

            <h3 style="margin: 20px 0 5px;">Información laboral</h3>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tr>
                    <td style="<?php echo $cell_label;?>">Puesto</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape($job_name);?></td>
                    <td style="<?php echo $cell_label;?>">Fecha de contratación</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $format_date($employee['hiring']);?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Sucursal</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape($branch_names ? implode(', ', $branch_names) : 'Sin datos');?></td>
                    <td style="<?php echo $cell_label;?>">Número de cuenta</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape(!empty($employee['account_number'] ?? '') ? $employee['account_number'] : 'Sin datos');?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Referencia bancaria</td>
                    <td style="<?php echo $cell_value;?>" colspan="3"><?php echo $escape(!empty($employee['bank_reference'] ?? '') ? $employee['bank_reference'] : 'Sin datos');?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Salario</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape($moneda).number_format((float) $employee['salary'], 2, '.', ',');?></td>
                    <td style="<?php echo $cell_label;?>">Bonificación</td>
                    <td style="<?php echo $cell_value;?>"><?php echo $escape($moneda).number_format((float) $employee['bonus'], 2, '.', ',');?></td>
                </tr>
                <tr>
                    <td style="<?php echo $cell_label;?>">Complemento</td>
                    <td style="<?php echo $cell_value;?>" colspan="3"><?php echo $escape($moneda).number_format((float) ($employee['complemento'] ?? 0), 2, '.', ',');?></td>
                </tr>
            </table>
        </main>
    </body>
</html>
