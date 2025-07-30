<!-- hr/payroll/payslip.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip - <?= $payslip['staff_code'] ?></title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        
        .payslip-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            border-bottom: 3px solid #2196F3;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .company-info {
            text-align: center;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2196F3;
            margin: 0;
        }
        
        .company-address {
            color: #666;
            margin: 5px 0;
        }
        
        .payslip-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            color: #333;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .info-box {
            flex: 1;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #666;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .earnings-deductions {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .earnings-box, .deductions-box {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
        }
        
        .box-title {
            font-size: 16px;
            font-weight: bold;
            color: #2196F3;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .item-label {
            color: #666;
        }
        
        .item-amount {
            font-weight: bold;
            color: #333;
        }
        
        .total-row {
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .summary-section {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .summary-label {
            color: #666;
        }
        
        .summary-amount {
            font-weight: bold;
            color: #333;
        }
        
        .net-pay {
            background: #2196F3;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .net-pay-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
        }
        
        .statutory-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .statutory-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .statutory-table th {
            background: #f5f5f5;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .statutory-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        
        @page {
            size: A4;
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Payslip
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="payslip-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1 class="company-name"><?= $_SESSION['site_title'] ?? 'Company Name' ?></h1>
                <p class="company-address">
                    <?= $_SESSION['address'] ?? 'Company Address' ?><br>
                    Tel: <?= $_SESSION['phone'] ?? '' ?> | Email: <?= $_SESSION['email'] ?? '' ?>
                </p>
            </div>
        </div>

        <!-- Payslip Title -->
        <h2 class="payslip-title">PAYSLIP FOR <?= strtoupper(date('F Y', strtotime($payslip['payroll_month'] . '-01'))) ?></h2>

        <!-- Employee Information -->
        <div class="info-section">
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Employee Code:</span>
                    <span class="info-value"><?= $payslip['staff_code'] ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value"><?= $payslip['first_name'] . ' ' . $payslip['last_name'] ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value"><?= $payslip['department_name'] ?? '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Designation:</span>
                    <span class="info-value"><?= $payslip['designation_name'] ?? '-' ?></span>
                </div>
            </div>
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Pay Period:</span>
                    <span class="info-value"><?= date('01/m/Y', strtotime($payslip['payroll_month'] . '-01')) ?> - <?= date('t/m/Y', strtotime($payslip['payroll_month'] . '-01')) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Date:</span>
                    <span class="info-value"><?= $payslip['payment_date'] ? date('d/m/Y', strtotime($payslip['payment_date'])) : 'Pending' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value"><?= $payslip['payment_method'] ?? 'Bank Transfer' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bank Account:</span>
                    <span class="info-value">****<?= substr($payslip['bank_account'] ?? '0000', -4) ?></span>
                </div>
            </div>
        </div>

        <!-- Earnings and Deductions -->
        <div class="earnings-deductions">
            <div class="earnings-box">
                <div class="box-title">EARNINGS</div>
                <div class="item-row">
                    <span class="item-label">Basic Salary</span>
                    <span class="item-amount">RM <?= number_format($payslip['basic_salary'], 2) ?></span>
                </div>
                <?php 
                $totalEarnings = $payslip['basic_salary'];
                foreach($payslip['details'] as $detail): 
                    if($detail['component_type'] == 'earning'):
                        $totalEarnings += $detail['amount'];
                ?>
                <div class="item-row">
                    <span class="item-label"><?= $detail['component_name'] ?></span>
                    <span class="item-amount">RM <?= number_format($detail['amount'], 2) ?></span>
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
                <?php if($payslip['total_commission'] > 0): ?>
                <div class="item-row">
                    <span class="item-label">Commission</span>
                    <span class="item-amount">RM <?= number_format($payslip['total_commission'], 2) ?></span>
                </div>
                <?php $totalEarnings += $payslip['total_commission']; endif; ?>
                <div class="item-row total-row">
                    <span class="item-label">Total Earnings</span>
                    <span class="item-amount">RM <?= number_format($totalEarnings, 2) ?></span>
                </div>
            </div>

            <div class="deductions-box">
                <div class="box-title">DEDUCTIONS</div>
                <?php 
                $totalDeductions = 0;
                foreach($payslip['details'] as $detail): 
                    if($detail['component_type'] == 'deduction' || $detail['component_type'] == 'statutory'):
                        $totalDeductions += $detail['amount'];
                ?>
                <div class="item-row">
                    <span class="item-label"><?= $detail['component_name'] ?></span>
                    <span class="item-amount">RM <?= number_format($detail['amount'], 2) ?></span>
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
                <div class="item-row total-row">
                    <span class="item-label">Total Deductions</span>
                    <span class="item-amount">RM <?= number_format($totalDeductions, 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">Gross Salary</span>
                <span class="summary-amount">RM <?= number_format($payslip['gross_salary'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Deductions</span>
                <span class="summary-amount">RM <?= number_format($totalDeductions, 2) ?></span>
            </div>
            <div class="net-pay">
                <div class="net-pay-row">
                    <span>NET PAY</span>
                    <span>RM <?= number_format($payslip['net_salary'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Statutory Contributions -->
        <?php if($payslip['staff_type'] == 'local'): ?>
        <div class="statutory-section">
            <div class="box-title">STATUTORY CONTRIBUTIONS</div>
            <table class="statutory-table">
                <thead>
                    <tr>
                        <th>Contribution</th>
                        <th>Employee No.</th>
                        <th>Employee (RM)</th>
                        <th>Employer (RM)</th>
                        <th>Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>EPF</td>
                        <td><?= $payslip['statutory']['epf_number'] ?? '-' ?></td>
                        <td><?= number_format($payslip['epf_employee'], 2) ?></td>
                        <td><?= number_format($payslip['epf_employer'], 2) ?></td>
                        <td><?= number_format($payslip['epf_employee'] + $payslip['epf_employer'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>SOCSO</td>
                        <td><?= $payslip['statutory']['socso_number'] ?? '-' ?></td>
                        <td><?= number_format($payslip['socso_employee'], 2) ?></td>
                        <td><?= number_format($payslip['socso_employer'], 2) ?></td>
                        <td><?= number_format($payslip['socso_employee'] + $payslip['socso_employer'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>EIS</td>
                        <td><?= $payslip['statutory']['eis_number'] ?? '-' ?></td>
                        <td><?= number_format($payslip['eis_employee'], 2) ?></td>
                        <td><?= number_format($payslip['eis_employer'], 2) ?></td>
                        <td><?= number_format($payslip['eis_employee'] + $payslip['eis_employer'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Income Tax (PCB)</td>
                        <td><?= $payslip['statutory']['income_tax_number'] ?? '-' ?></td>
                        <td><?= number_format($payslip['pcb'], 2) ?></td>
                        <td>-</td>
                        <td><?= number_format($payslip['pcb'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Employee Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated on: <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
</body>
</html>