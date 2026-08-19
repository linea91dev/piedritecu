<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
ini_set('memory_limit', '500M');
$moneda = $this->crud_model->get_info('moneda');
$branch_id = $this->session->userdata('branch_id');
$payroll = $this->db->get_where('payroll', array('payroll_id' => $ID, 'branch_id' => $branch_id))->row_array();

if (!$payroll) {
    echo '<p style="font-family:Arial,Helvetica,sans-serif;">Planilla no encontrada.</p>';
} else {
    $payroll_name = !empty($payroll['payroll_name']) ? $payroll['payroll_name'] : 'Oficial';
    $is_bonus = in_array($payroll_name, array('Bono 14', 'Aguinaldo'), true);
    $is_regular = in_array($payroll_name, array('Oficial', 'Interna'), true);
    $show_other_discount = $is_regular;
    $payroll_title = $is_regular ? ('Planilla '.strtolower($payroll_name)) : $payroll_name;

    $employees = !empty($payroll['employee']) ? json_decode($payroll['employee'], true) : array();
    if (!is_array($employees)) {
        $employees = array();
    }

    $filter_employee = isset($post) ? $post : 0;
    if ($filter_employee !== '' && $filter_employee !== null && (int) $filter_employee > 0) {
        $filtered = array();
        foreach ($employees as $emp) {
            if ((int) $emp['employee'] === (int) $filter_employee) {
                $filtered[] = $emp;
            }
        }
        if (!empty($filtered)) {
            $employees = $filtered;
        }
    }

    $origin_label = 'Caja Chica';
    if (!empty($payroll['bank'])) {
        $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $payroll['bank']))->row_array();
        if ($cuenta) {
            $bank_name = '';
            if (!empty($cuenta['bank_id'])) {
                $bank_row = $this->db->get_where('bank', array('bank_id' => $cuenta['bank_id']))->row();
                $bank_name = $bank_row ? $bank_row->name : '';
            }
            $origin_label = trim(($bank_name ? '('.$bank_name.') ' : '').$cuenta['name_account']);
        }
    }

    $company = $this->crud_model->get_info('name');
    $slogan = $this->crud_model->get_info('slogan');
    $phone = $this->crud_model->get_info('phone');
    $email = $this->crud_model->get_info('email');
    $logo = $this->crud_model->get_info('logo');
    $responsable = $this->crud_model->getName('admin', $payroll['responsable']);
    $generated_by = $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));

    $valid = array();
    foreach ($employees as $emp) {
        if ((float) ($emp['sub'] ?? 0) != 0 || (float) ($emp['salary'] ?? 0) != 0) {
            $valid[] = $emp;
        }
    }

    $total_boletas = count($valid);
    $index = 0;

    if ($total_boletas === 0) {
        echo '<p style="font-family:Arial,Helvetica,sans-serif;">No hay empleados para imprimir.</p>';
    }

    foreach ($valid as $emp):
        $index++;
        $admin = $this->db->get_where('admin', array('admin_id' => $emp['employee']))->row_array();
        $employee_name = $admin ? trim($admin['name'].' '.$admin['last_name']) : 'Empleado';
        $job_name = '-';
        if (!empty($admin['job'])) {
            $job = $this->db->get_where('job', array('job_id' => $admin['job']))->row();
            if ($job) {
                $job_name = $job->name;
            }
        }

        $date_start = !empty($emp['date_start']) ? $emp['date_start'] : $payroll['date_start'];
        $date_end = !empty($emp['date_end']) ? $emp['date_end'] : $payroll['date_end'];
        $salary = (float) ($emp['salary'] ?? 0);
        $discount = (float) ($emp['discount'] ?? 0);
        $advance = (float) ($emp['advance'] ?? 0);
        $other_discount = (float) ($emp['other_discount'] ?? 0);
        $remuneration = (float) ($emp['remuneration'] ?? 0);
        $sub = (float) ($emp['sub'] ?? 0);
        $note = isset($emp['note']) ? $emp['note'] : '';
        $page_break = ($index < $total_boletas) ? 'page-break-after:always;' : '';
?>
<div style="font-family:Arial,Helvetica,sans-serif;color:#333;font-size:12px;padding:10px;<?php echo $page_break; ?>">
    <div style="text-align:center;margin-bottom:12px;">
        <?php if (!empty($logo)): ?>
        <img src="<?php echo base_url().'uploads/img/'.$logo;?>" style="width:70px;height:auto;margin-bottom:6px;" />
        <?php endif; ?>
        <div style="font-size:14px;font-weight:bold;"><?php echo htmlspecialchars($company, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:11px;color:#666;"><?php echo htmlspecialchars($slogan, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:11px;color:#666;"><?php echo htmlspecialchars($phone.' | '.$email, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <div style="text-align:center;margin:12px 0 18px;">
        <div style="font-size:16px;font-weight:bold;text-transform:uppercase;text-decoration:underline;">
            Boleta de pago
        </div>
        <div style="font-size:13px;margin-top:4px;font-weight:bold;">
            <?php echo htmlspecialchars($payroll_title, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:14px;">
        <tr>
            <td style="width:50%;vertical-align:top;padding:4px 0;">
                <b>Empleado:</b> <?php echo htmlspecialchars($employee_name, ENT_QUOTES, 'UTF-8'); ?><br>
                <b>Puesto:</b> <?php echo htmlspecialchars($job_name, ENT_QUOTES, 'UTF-8'); ?><br>
                <?php if (!empty($admin['account_number'])): ?>
                <b>No. cuenta:</b> <?php echo htmlspecialchars($admin['account_number'], ENT_QUOTES, 'UTF-8'); ?><br>
                <?php endif; ?>
            </td>
            <td style="width:50%;vertical-align:top;padding:4px 0;">
                <b>Período:</b>
                <?php echo date('d/m/Y', strtotime($date_start)).' - '.date('d/m/Y', strtotime($date_end)); ?><br>
                <b>Origen:</b> <?php echo htmlspecialchars($origin_label, ENT_QUOTES, 'UTF-8'); ?><br>
                <b>Responsable:</b> <?php echo htmlspecialchars($responsable, ENT_QUOTES, 'UTF-8'); ?>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:16px;">
        <tr>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:7px;">Concepto</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:7px;text-align:right;width:140px;">Monto</td>
        </tr>
        <tr>
            <td style="border:1px solid #000;padding:7px;"><?php echo $is_bonus ? 'Monto calculado' : 'Salario proporcional'; ?></td>
            <td style="border:1px solid #000;padding:7px;text-align:right;"><?php echo $moneda.number_format($salary, 2, '.', ','); ?></td>
        </tr>
        <?php if ($is_regular): ?>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Descuento IGSS</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;">- <?php echo $moneda.number_format($discount, 2, '.', ','); ?></td>
        </tr>
        <tr>
            <td style="border:1px solid #000;padding:7px;">ISR</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;">- <?php echo $moneda.number_format($advance, 2, '.', ','); ?></td>
        </tr>
        <?php if ($show_other_discount): ?>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Descuentos</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;">- <?php echo $moneda.number_format($other_discount, 2, '.', ','); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Bonificación decreto</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;"><?php echo $moneda.number_format($remuneration, 2, '.', ','); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="border:1px solid #000;padding:8px;font-weight:bold;background:#f7f7f7;">Total a recibir</td>
            <td style="border:1px solid #000;padding:8px;text-align:right;font-weight:bold;background:#f7f7f7;color:#c00;">
                <?php echo $moneda.number_format($sub, 2, '.', ','); ?>
            </td>
        </tr>
    </table>

    <div style="margin-bottom:28px;">
        <b>Notas:</b> <?php echo $note !== '' ? htmlspecialchars($note, ENT_QUOTES, 'UTF-8') : '-'; ?>
    </div>

    <table cellpadding="0" cellspacing="0" style="width:100%;margin-top:40px;">
        <tr>
            <td style="width:45%;text-align:center;vertical-align:top;">
                <div style="border-top:1px solid #000;margin:0 20px;padding-top:6px;">Firma empleado</div>
            </td>
            <td style="width:10%;"></td>
            <td style="width:45%;text-align:center;vertical-align:top;">
                <div style="border-top:1px solid #000;margin:0 20px;padding-top:6px;">Firma responsable</div>
            </td>
        </tr>
    </table>

    <div style="margin-top:24px;font-size:10px;color:#777;">
        Generado por <?php echo htmlspecialchars($generated_by, ENT_QUOTES, 'UTF-8'); ?>
        el <?php echo date('d/m/Y H:i'); ?>
        &nbsp;|&nbsp; Boleta <?php echo $index; ?> de <?php echo $total_boletas; ?>
    </div>
</div>
<?php
    endforeach;
}
?>
</body>
</html>
