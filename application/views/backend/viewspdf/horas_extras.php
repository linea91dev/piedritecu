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

if (!$payroll || ($payroll['payroll_name'] ?? '') !== 'Horas extras') {
    echo '<p style="font-family:Arial,Helvetica,sans-serif;">Planilla no encontrada.</p>';
} else {
    $employees = !empty($payroll['employee']) ? json_decode($payroll['employee'], true) : array();
    if (!is_array($employees)) {
        $employees = array();
    }
    $company = $this->crud_model->get_info('name');
    $slogan = $this->crud_model->get_info('slogan');
    $phone = $this->crud_model->get_info('phone');
    $email = $this->crud_model->get_info('email');
    $logo = $this->crud_model->get_info('logo');
    $period_note = !empty($payroll['note']) ? $payroll['note'] : '';
    $origin = 'Caja Chica';
    if (!empty($payroll['bank'])) {
        $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $payroll['bank']))->row_array();
        if ($cuenta) {
            $bank_name = '';
            if (!empty($cuenta['bank_id'])) {
                $bank_row = $this->db->get_where('bank', array('bank_id' => $cuenta['bank_id']))->row();
                $bank_name = $bank_row ? $bank_row->name : '';
            }
            $origin = trim(($bank_name ? '('.$bank_name.') ' : '').$cuenta['name_account']);
        }
    }
?>
<div style="font-family:Arial,Helvetica,sans-serif;color:#333;font-size:11px;">
    <div style="text-align:center;margin-bottom:10px;">
        <?php if (!empty($logo)): ?>
        <img src="<?php echo base_url().'uploads/img/'.$logo; ?>" style="width:60px;height:auto;margin-bottom:4px;" />
        <?php endif; ?>
        <div style="font-size:14px;font-weight:bold;"><?php echo htmlspecialchars($company, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:10px;color:#666;"><?php echo htmlspecialchars($slogan, ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="font-size:10px;color:#666;"><?php echo htmlspecialchars($phone.' | '.$email, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <div style="text-align:center;margin:8px 0 14px;">
        <div style="font-size:15px;font-weight:bold;text-transform:uppercase;text-decoration:underline;">
            Planilla de horas extras y viáticos
        </div>
        <div style="margin-top:4px;">
            <?php echo date('d/m/Y', strtotime($payroll['date_start'])).' - '.date('d/m/Y', strtotime($payroll['date_end'])); ?>
            <?php if ($period_note !== ''): ?>
            · <?php echo htmlspecialchars($period_note, ENT_QUOTES, 'UTF-8'); ?>
            <?php endif; ?>
        </div>
        <div style="margin-top:2px;">Origen: <?php echo htmlspecialchars($origin, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>

    <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">#</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Empleado</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Horas</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Costo hora</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Total horas</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Días viáticos</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Costo viático</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Total viático</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Total a pagar</td>
            <td style="border:1px solid #000;background:#eee;font-weight:bold;padding:5px;">Notas</td>
        </tr>
        <?php
        $n = 1;
        $sum_hours = 0;
        $sum_overtime = 0;
        $sum_viatico_days = 0;
        $sum_viatico = 0;
        $sum_sub = 0;
        foreach ($employees as $emp):
            $sum_hours += (float)($emp['hours'] ?? 0);
            $sum_overtime += (float)($emp['overtime_total'] ?? 0);
            $sum_viatico_days += (float)($emp['viatico_days'] ?? 0);
            $sum_viatico += (float)($emp['viatico_total'] ?? 0);
            $sum_sub += (float)($emp['sub'] ?? 0);
        ?>
        <tr>
            <td style="border:1px solid #000;padding:5px;"><?php echo $n++; ?></td>
            <td style="border:1px solid #000;padding:5px;"><?php echo htmlspecialchars($this->crud_model->getName('admin', $emp['employee']), ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo number_format((float)($emp['hours'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo $moneda.number_format((float)($emp['hour_cost'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo $moneda.number_format((float)($emp['overtime_total'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo number_format((float)($emp['viatico_days'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo $moneda.number_format((float)($emp['viatico_cost'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;"><?php echo $moneda.number_format((float)($emp['viatico_total'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;text-align:right;font-weight:bold;"><?php echo $moneda.number_format((float)($emp['sub'] ?? 0), 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;"><?php echo !empty($emp['note']) ? htmlspecialchars($emp['note'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2" style="border:1px solid #000;padding:5px;background:#eee;font-weight:bold;">TOTALES</td>
            <td style="border:1px solid #000;padding:5px;background:#eee;text-align:right;font-weight:bold;"><?php echo number_format($sum_hours, 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;"></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;text-align:right;font-weight:bold;"><?php echo $moneda.number_format($sum_overtime, 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;text-align:right;font-weight:bold;"><?php echo number_format($sum_viatico_days, 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;"></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;text-align:right;font-weight:bold;"><?php echo $moneda.number_format($sum_viatico, 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;text-align:right;font-weight:bold;"><?php echo $moneda.number_format($sum_sub, 2, '.', ','); ?></td>
            <td style="border:1px solid #000;padding:5px;background:#eee;"></td>
        </tr>
    </table>

    <div style="margin-top:18px;font-size:10px;color:#777;">
        Generado el <?php echo date('d/m/Y H:i'); ?>
        por <?php echo htmlspecialchars($this->crud_model->getName('admin', $this->session->userdata('login_user_id')), ENT_QUOTES, 'UTF-8'); ?>
    </div>
</div>
<?php } ?>
</body>
</html>
