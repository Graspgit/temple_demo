<!DOCTYPE html>
<html>
<head>
    <title>EA Form - <?= $ea_data['staff']['staff_code'] ?></title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 { margin: 5px 0; }
        .section {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            margin: -10px -10px 10px -10px;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 40%; }
        .value { width: 60%; }
        .income-table { margin-top: 10px; }
        .income-table th, .income-table td { 
            border: 1px solid #000; 
            padding: 5px; 
            text-align: left;
        }
        .income-table th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
        .signature-box {
            margin-top: 50px;
            display: inline-block;
            width: 45%;
        }
        .no-print { display: none; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BORANG EA</h2>
        <h3>PENYATA SARAAN DARIPADA PENGGAJIAN BAGI TAHUN <?= $year ?></h3>
        <p>INCOME TAX ACT 1967</p>
    </div>

    <!-- Part A: Employee Information -->
    <div class="section">
        <div class="section-title">BAHAGIAN A - MAKLUMAT PEKERJA / PART A - EMPLOYEE INFORMATION</div>
        <table>
            <tr>
                <td class="label">Nama Pekerja / Employee Name:</td>
                <td class="value"><strong><?= strtoupper($ea_data['staff']['first_name'] . ' ' . $ea_data['staff']['last_name']) ?></strong></td>
            </tr>
            <tr>
                <td class="label">No. Pekerja / Employee No:</td>
                <td class="value"><?= $ea_data['staff']['staff_code'] ?></td>
            </tr>
            <tr>
                <td class="label">No. Kad Pengenalan / IC No:</td>
                <td class="value"><?= $ea_data['staff']['ic_number'] ?? '-' ?></td>
            </tr>
            <tr>
                <td class="label">No. Cukai Pendapatan / Income Tax No:</td>
                <td class="value"><?= $ea_data['staff']['income_tax_number'] ?? '-' ?></td>
            </tr>
            <tr>
                <td class="label">No. KWSP / EPF No:</td>
                <td class="value"><?= $ea_data['staff']['epf_number'] ?? '-' ?></td>
            </tr>
        </table>
    </div>

    <!-- Part B: Employment Details -->
    <div class="section">
        <div class="section-title">BAHAGIAN B - MAKLUMAT PENGGAJIAN / PART B - EMPLOYMENT DETAILS</div>
        <table class="income-table">
            <thead>
                <tr>
                    <th>Butiran / Details</th>
                    <th class="text-right">Amaun / Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1. Gaji, Upah, Elaun, Bonus dll / Salary, Wages, Allowances, Bonus etc</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_gross'], 2) ?></td>
                </tr>
                <tr>
                    <td>2. Komisen / Commission</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_commission'], 2) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>JUMLAH PENDAPATAN / TOTAL INCOME</strong></td>
                </tr>
                <tr style="background-color: #f0f0f0;">
                    <td><strong>JUMLAH SARAAN / TOTAL REMUNERATION</strong></td>
                    <td class="text-right"><strong><?= number_format($ea_data['summary']['total_gross'] + $ea_data['summary']['total_commission'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Part C: Deductions -->
    <div class="section">
        <div class="section-title">BAHAGIAN C - POTONGAN / PART C - DEDUCTIONS</div>
        <table class="income-table">
            <thead>
                <tr>
                    <th>Butiran / Details</th>
                    <th class="text-right">Amaun / Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Caruman KWSP / EPF Contribution</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_epf'], 2) ?></td>
                </tr>
                <tr>
                    <td>Potongan Cukai Berjadual (PCB) / Scheduled Tax Deduction</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_pcb'], 2) ?></td>
                </tr>
                <tr style="background-color: #f0f0f0;">
                    <td><strong>JUMLAH POTONGAN / TOTAL DEDUCTIONS</strong></td>
                    <td class="text-right"><strong><?= number_format($ea_data['summary']['total_epf'] + $ea_data['summary']['total_pcb'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Monthly Breakdown -->
    <div class="section">
        <div class="section-title">PECAHAN BULANAN / MONTHLY BREAKDOWN</div>
        <table class="income-table">
            <thead>
                <tr>
                    <th>Bulan / Month</th>
                    <th class="text-right">Gaji Kasar / Gross</th>
                    <th class="text-right">EPF</th>
                    <th class="text-right">PCB</th>
                    <th class="text-right">Gaji Bersih / Net</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Mac', '04' => 'April', 
                           '05' => 'Mei', '06' => 'Jun', '07' => 'Julai', '08' => 'Ogos',
                           '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Disember'];
                
                foreach($ea_data['monthly'] as $payroll): 
                    $monthNum = date('m', strtotime($payroll['payroll_month']));
                ?>
                <tr>
                    <td><?= $months[$monthNum] ?></td>
                    <td class="text-right"><?= number_format($payroll['gross_salary'], 2) ?></td>
                    <td class="text-right"><?= number_format($payroll['epf_employee'], 2) ?></td>
                    <td class="text-right"><?= number_format($payroll['pcb'], 2) ?></td>
                    <td class="text-right"><?= number_format($payroll['net_salary'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Employer Information -->
    <div class="section">
        <div class="section-title">MAKLUMAT MAJIKAN / EMPLOYER INFORMATION</div>
        <table>
            <tr>
                <td class="label">Nama Majikan / Employer Name:</td>
                <td class="value"><strong>YOUR COMPANY NAME</strong></td>
            </tr>
            <tr>
                <td class="label">No. Majikan / Employer No:</td>
                <td class="value">E12345678</td>
            </tr>
            <tr>
                <td class="label">Alamat / Address:</td>
                <td class="value">Company Address Line 1<br>Company Address Line 2</td>
            </tr>
        </table>
    </div>

    <!-- Signature Section -->
    <div class="footer">
        <div class="signature-box">
            <p>Disahkan Oleh / Certified By:</p>
            <br><br>
            <p>_______________________________</p>
            <p>Tandatangan & Cop Majikan<br>Employer's Signature & Stamp</p>
            <p>Tarikh / Date: <?= date('d/m/Y') ?></p>
        </div>
        <div class="signature-box" style="float: right;">
            <p>Disahkan Diterima / Acknowledged By:</p>
            <br><br>
            <p>_______________________________</p>
            <p>Tandatangan Pekerja<br>Employee's Signature</p>
            <p>Tarikh / Date: _______________</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">Print EA Form</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px;">Close</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>