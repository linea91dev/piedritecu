<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
$moneda = $this->crud_model->get_info("moneda");
$branch_id = $this->session->userdata('branch_id');
$payroll = $this->db->get_where('payroll', array('payroll_id' => $ID, 'branch_id' => $branch_id))->row_array();

if (!$payroll) {
    echo '<p style="font-family:Arial,Helvetica,sans-serif;">Pago no encontrado.</p>';
} else {
    $payroll_name = !empty($payroll['payroll_name']) ? $payroll['payroll_name'] : 'Bono 14';
    $employees = !empty($payroll['employee']) ? json_decode($payroll['employee'], true) : array();
    if (!is_array($employees)) {
        $employees = array();
    }

    $origin_label = 'Caja Chica';
    if (!empty($payroll['bank'])) {
        $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $payroll['bank']))->row_array();
        if ($cuenta) {
            $parts = array();
            if (!empty($cuenta['no_account'])) {
                $parts[] = $cuenta['no_account'];
            }
            if (!empty($cuenta['type'])) {
                $parts[] = $cuenta['type'];
            }
            if (!empty($cuenta['name_account'])) {
                $parts[] = $cuenta['name_account'];
            }
            $parts[] = 'GTQ';
            $origin_label = implode('-', $parts);
        }
    }

    $transfer_dt = !empty($payroll['datetime'])
        ? date('d/m/Y H:i:s', strtotime($payroll['datetime']))
        : date('d/m/Y H:i:s');

    $year_label = !empty($payroll['date_end']) ? date('Y', strtotime($payroll['date_end'])) : date('Y');
    $default_desc = ($payroll_name === 'Aguinaldo')
        ? 'PAGO AGUINALDO '.$year_label
        : 'PAGO BONO 14 '.$year_label;

    $company = $this->crud_model->get_info('name');
    $printed = 0;
    $total_rows = 0;
    foreach ($employees as $emp_count) {
        if ((float) ($emp_count['sub'] ?? $emp_count['salary'] ?? 0) > 0) {
            $total_rows++;
        }
    }

    foreach ($employees as $emp):
        $amount = (float) ($emp['sub'] ?? $emp['salary'] ?? 0);
        if ($amount <= 0) {
            continue;
        }
        $printed++;

        $admin = $this->db->get_where('admin', array('admin_id' => $emp['employee']))->row_array();
        $alias = $admin ? trim($admin['name'].' '.$admin['last_name']) : 'Empleado';
        $account_number = !empty($admin['account_number']) ? $admin['account_number'] : 'Sin cuenta registrada';
        $account_type = !empty($admin['bank_reference']) ? $admin['bank_reference'] : 'Cuenta Monetaria';
        $description = !empty($emp['note']) ? $emp['note'] : $default_desc;
        $page_break = ($printed < $total_rows) ? 'page-break-after:always;' : '';
?>
    <div style="font-family:Arial,Helvetica,sans-serif;color:#222;font-size:12px;padding:18px 10px;<?php echo $page_break; ?>">
        <div style="text-align:center;font-size:11px;color:#666;margin-bottom:18px;">
            <?php echo htmlspecialchars($company, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <p style="text-align:center;font-size:18px;font-weight:bold;margin:0 0 8px;">Pagar Bono</p>
        <p style="text-align:center;font-size:12px;margin:0 0 22px;">Fecha: <?php echo $transfer_dt; ?></p>

        <table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:28px;">
            <tr>
                <td style="width:50%;vertical-align:top;padding:6px 10px;">
                    <div style="margin-bottom:10px;"><b>Número de Cuenta:</b> <?php echo htmlspecialchars($account_number, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div style="margin-bottom:10px;"><b>Alias:</b> <?php echo htmlspecialchars(strtoupper($alias), ENT_QUOTES, 'UTF-8'); ?></div>
                    <div style="margin-bottom:10px;"><b>Tipo de Cuenta:</b> <?php echo htmlspecialchars($account_type, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div style="margin-bottom:10px;"><b>Moneda:</b> Quetzal</div>
                </td>
                <td style="width:50%;vertical-align:top;padding:6px 10px;">
                    <div style="margin-bottom:10px;"><b>Cuenta origen:</b> <?php echo htmlspecialchars($origin_label, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div style="margin-bottom:10px;"><b>Monto débito:</b> <?php echo $moneda.' '.number_format($amount, 2, '.', ','); ?></div>
                    <div style="margin-bottom:10px;"><b>Descripción:</b> <?php echo htmlspecialchars(strtoupper($description), ENT_QUOTES, 'UTF-8'); ?></div>
                </td>
            </tr>
        </table>

        <div style="font-size:10px;color:#444;line-height:1.45;margin-top:30px;">
            Nota: Este documento es un comprobante electrónico de la transacción realizada. Todas las operaciones
            efectuadas a través del sistema son responsabilidad del usuario conforme a los términos del servicio.
        </div>
    </div>
<?php
    endforeach;

    if ($printed === 0) {
        echo '<p style="font-family:Arial,Helvetica,sans-serif;">No hay montos para imprimir.</p>';
    }
}
?>
</body>
</html>
