<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .summary-box { background-color: #f9f9f9; padding: 10px; margin-bottom: 20px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #e9e9e9; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>MONTHLY PAYROLL REPORT</h2>
        <h3><?= date('F Y', strtotime($month . '-01')) ?></h3>
        <p>Generated on: <?= date('d-M-Y H:i:s') ?></p>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Total Employees:</strong></td>
                <td><?= count($payroll) ?></td>
                <td><strong>Total Gross Salary:</strong></td>
                <td>RM <?= number_format($summary['total_gross'] ?? 0, 2) ?></td>
            </tr>
            <tr>
                <td><strong>Total Deductions:</strong></td>
                <td>RM <?= number_format($summary['total_deductions'] ?? 0, 2) ?></td>
                <td><strong>Total Net Salary:</strong></td>
                <td>RM <?= number_format($summary['total_net'] ?? 0, 2) ?></td>
            </tr>
        </table>
    </div>

    <!-- Department Summary -->
    <h4>Department Summary</h4>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th class="text-center">Staff Count</th>
                <th class="text-right">Total Gross (RM)</th>
                <th class="text-right">Total Net (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($department_summary as $dept): ?>
            <tr>
                <td><?= $dept['department_name'] ?? 'No Department' ?></td>
                <td class="text-center"><?= $dept['staff_count'] ?></td>
                <td class="text-right"><?= number_format($dept['total_gross'], 2) ?></td>
                <td class="text-right"><?= number_format($dept['total_net'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Detailed Payroll -->
    <h4>Detailed Payroll Information</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Staff Code</th>
                <th>Name</th>
                <th class="text-right">Basic</th>
                <th class="text-right">Allowances</th>
                <th class="text-right">Gross</th>
                <th class="text-right">EPF</th>
                <th class="text-right">SOCSO</th>
                <th class="text-right">PCB</th>
                <th class="text-right">Other</th>
                <th class="text-right">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            $totalBasic = 0;
            $totalAllowances = 0;
            $totalGross = 0;
            $totalEPF = 0;
            $totalSOCSO = 0;
            $totalPCB = 0;
            $totalOther = 0;
            $totalNet = 0;
            
            foreach($payroll as $row): 
                $totalBasic += $row['basic_salary'];
                $totalAllowances += $row['total_allowances'];
                $totalGross += $row['gross_salary'];
                $totalEPF += $row['epf_employee'];
                $totalSOCSO += $row['socso_employee'] + $row['eis_employee'];
                $totalPCB += $row['pcb'];
                $totalOther += $row['total_deductions'];
                $totalNet += $row['net_salary'];
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['staff_code'] ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td class="text-right"><?= number_format($row['basic_salary'], 2) ?></td>
                <td class="text-right"><?= number_format($row['total_allowances'], 2) ?></td>
                <td class="text-right"><?= number_format($row['gross_salary'], 2) ?></td>
                <td class="text-right"><?= number_format($row['epf_employee'], 2) ?></td>
                <td class="text-right"><?= number_format($row['socso_employee'] + $row['eis_employee'], 2) ?></td>
                <td class="text-right"><?= number_format($row['pcb'], 2) ?></td>
                <td class="text-right"><?= number_format($row['total_deductions'], 2) ?></td>
                <td class="text-right"><strong><?= number_format($row['net_salary'], 2) ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">TOTAL</td>
                <td class="text-right"><?= number_format($totalBasic, 2) ?></td>
                <td class="text-right"><?= number_format($totalAllowances, 2) ?></td>
                <td class="text-right"><?= number_format($totalGross, 2) ?></td>
                <td class="text-right"><?= number_format($totalEPF, 2) ?></td>
                <td class="text-right"><?= number_format($totalSOCSO, 2) ?></td>
                <td class="text-right"><?= number_format($totalPCB, 2) ?></td>
                <td class="text-right"><?= number_format($totalOther, 2) ?></td>
                <td class="text-right"><?= number_format($totalNet, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="no-print" style="margin-top: 30px;">
        <button onclick="window.print()">Print Report</button>
        <button onclick="window.close()">Close</button>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>