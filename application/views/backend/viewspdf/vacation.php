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
$vacation = $this->db->get_where('vacations', array(
    'vacation_id' => $ID,
    'branch_id' => $branch_id,
))->row_array();

if (!$vacation) {
    echo '<p style="font-family:Arial,Helvetica,sans-serif;">Registro de vacaciones no encontrado.</p>';
} else {
    $admin = $this->db->get_where('admin', array('admin_id' => $vacation['employee_id']))->row_array();
    $employee_name = $admin ? trim($admin['name'].' '.$admin['last_name']) : 'Empleado';
    $job_name = '-';
    if (!empty($admin['job'])) {
        $job = $this->db->get_where('job', array('job_id' => $admin['job']))->row();
        if ($job) {
            $job_name = $job->name;
        }
    }

    $hiring = (!empty($admin['hiring']) && $admin['hiring'] != '0000-00-00')
        ? date('Y-m-d', strtotime($admin['hiring']))
        : null;
    $eff_start = $vacation['date_start'];
    if ($hiring && $hiring > $eff_start) {
        $eff_start = $hiring;
    }
    $worked_days = $this->crud_model->calculate_vacation_worked_days($eff_start, $vacation['date_end']);
    $accrued_days = $worked_days > 0 ? round(($worked_days * 15) / 365, 3) : 0;

    $company = $this->crud_model->get_info('name');
    $slogan = $this->crud_model->get_info('slogan');
    $phone = $this->crud_model->get_info('phone');
    $email = $this->crud_model->get_info('email');
    $logo = $this->crud_model->get_info('logo');
    $responsable = $this->crud_model->getName('admin', $vacation['responsable']);
    $generated_by = $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));

    $type = !empty($vacation['type']) ? $vacation['type'] : 'Gozada';
    $days = (float) $vacation['days'];
    $amount = (float) $vacation['amount'];
    $note = isset($vacation['note']) ? $vacation['note'] : '';
    $title = ($type === 'Pagada') ? 'Comprobante de vacaciones pagadas' : 'Comprobante de vacaciones gozadas';
?>
<div style="font-family:Arial,Helvetica,sans-serif;color:#333;font-size:12px;padding:10px;">
    <div style="text-align:center;margin-bottom:12px;">
        <?php if (!empty($logo)): ?>
        <img src="<?php echo base_url().'uploads/img/'.$logo; ?>" style="width:70px;height:auto;margin-bottom:6px;" />
        <?php endif; ?>
        <div style="font-size:14px;font-weight:bold;"><?php echo htmlspecialchars($company, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:11px;color:#666;"><?php echo htmlspecialchars($slogan, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:11px;color:#666;"><?php echo htmlspecialchars($phone.' | '.$email, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <div style="text-align:center;margin:12px 0 18px;">
        <div style="font-size:16px;font-weight:bold;text-transform:uppercase;text-decoration:underline;">
            <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div style="font-size:12px;margin-top:4px;color:#555;">
            No. <?php echo (int) $vacation['vacation_id']; ?>
        </div>
    </div>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:14px;">
        <tr>
            <td style="width:50%;vertical-align:top;padding:4px 0;">
                <b>Empleado:</b> <?php echo htmlspecialchars($employee_name, ENT_QUOTES, 'UTF-8'); ?><br>
                <b>Puesto:</b> <?php echo htmlspecialchars($job_name, ENT_QUOTES, 'UTF-8'); ?><br>
                <?php if ($hiring): ?>
                <b>Contratación:</b> <?php echo date('d/m/Y', strtotime($hiring)); ?><br>
                <?php endif; ?>
            </td>
            <td style="width:50%;vertical-align:top;padding:4px 0;">
                <b>Período:</b>
                <?php echo date('d/m/Y', strtotime($vacation['date_start'])).' - '.date('d/m/Y', strtotime($vacation['date_end'])); ?><br>
                <b>Forma:</b> <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?><br>
                <b>Responsable:</b> <?php echo htmlspecialchars($responsable, ENT_QUOTES, 'UTF-8'); ?>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:16px;">
        <tr>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:7px;">Concepto</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:7px;text-align:right;width:140px;">Valor</td>
        </tr>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Días trabajados del período</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;"><?php echo (int) $worked_days; ?></td>
        </tr>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Días acumulados ((días × 15) / 365)</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;"><?php echo number_format($accrued_days, 3, '.', ','); ?></td>
        </tr>
        <tr>
            <td style="border:1px solid #000;padding:7px;">Días de este comprobante</td>
            <td style="border:1px solid #000;padding:7px;text-align:right;"><?php echo number_format($days, 3, '.', ','); ?></td>
        </tr>
        <?php if ($type === 'Pagada'): ?>
        <tr>
            <td style="border:1px solid #000;padding:8px;font-weight:bold;background:#f7f7f7;">Monto a pagar</td>
            <td style="border:1px solid #000;padding:8px;text-align:right;font-weight:bold;background:#f7f7f7;color:#c00;">
                <?php echo $moneda.number_format($amount, 2, '.', ','); ?>
            </td>
        </tr>
        <?php else: ?>
        <tr>
            <td style="border:1px solid #000;padding:8px;font-weight:bold;background:#f7f7f7;">Estado</td>
            <td style="border:1px solid #000;padding:8px;text-align:right;font-weight:bold;background:#f7f7f7;">
                Vacaciones gozadas
            </td>
        </tr>
        <?php endif; ?>
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
        &nbsp;|&nbsp; Registro #<?php echo (int) $vacation['vacation_id']; ?>
        <?php if ((int) $vacation['status'] !== 1): ?>
        &nbsp;|&nbsp; <b>ANULADO</b>
        <?php endif; ?>
    </div>
</div>
<?php } ?>
</body>
</html>
