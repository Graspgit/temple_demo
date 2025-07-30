<!-- hr/payroll/epf_report.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EPF Report - <?= date('F Y', strtotime($month . '-01')) ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            @page { size: landscape; margin: 10mm; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        
        .header h2 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }
        
        .company-info {
            margin-bottom: 20px;
        }
        
        .company-info table {
            width: 100%;
        }
        
        .company-info td {
            padding: 5px 0;
        }
        
        .company-info .label {
            font-weight: bold;
            width: 150px;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .report-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        
        .report-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        
        .report-table .text-center {
            text-align: center;
        }
        
        .report-table .text-right {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .footer {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        
        .footer table {
            width: 100%;
        }
        
        .signature-box {
            width: 300px;
            text-align: center;
            padding-top: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Report
        </button>
        <button onclick="exportToCSV()" style="padding: 10px 20px; font-size: 16px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Export to CSV
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>BORANG A</h1>
            <h2>PENYATA CARUMAN BULANAN KWSP</h2>
            <h2>EPF MONTHLY CONTRIBUTION STATEMENT</h2>
            <p style="margin: 10px 0;">
                <strong>BULAN / MONTH:</strong> <?= strtoupper(date('F Y', strtotime($month . '-01'))) ?>
            </p>
        </div>

        <!-- Company Information -->
        <div class="company-info">
            <table>
                <tr>
                    <td class="label">NAMA MAJIKAN / EMPLOYER NAME:</td>
                    <td><?= $_SESSION['site_title'] ?? 'COMPANY NAME' ?></td>
                    <td class="label" style="padding-left: 50px;">NO. MAJIKAN / EMPLOYER NO:</td>
                    <td><?= $_SESSION['epf_employer_no'] ?? 'EPF0000000' ?></td>
                </tr>
                <tr>
                    <td class="label">ALAMAT / ADDRESS:</td>
                    <td colspan="3"><?= $_SESSION['address'] ?? 'Company Address' ?></td>
                </tr>
                <tr>
                    <td class="label">NO. TELEFON / PHONE NO:</td>
                    <td><?= $_SESSION['phone'] ?? '' ?></td>
                    <td class="label" style="padding-left: 50px;">E-MEL / EMAIL:</td>
                    <td><?= $_SESSION['email'] ?? '' ?></td>
                </tr>
            </table>
        </div>

        <!-- EPF Contribution Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th rowspan="2" width="30">BIL<br>NO</th>
                    <th rowspan="2" width="120">NO. AHLI KWSP<br>EPF MEMBER NO</th>
                    <th rowspan="2" width="100">NO. K/P<br>I.C. NO</th>
                    <th rowspan="2">NAMA PEKERJA<br>EMPLOYEE NAME</th>
                    <th rowspan="2" width="80">GAJI/UPAH<br>WAGES (RM)</th>
                    <th colspan="3">CARUMAN / CONTRIBUTION (RM)</th>
                </tr>
                <tr>
                    <th width="80">PEKERJA<br>EMPLOYEE</th>
                    <th width="80">MAJIKAN<br>EMPLOYER</th>
                    <th width="80">JUMLAH<br>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                $totalWages = 0;
                $totalEmployee = 0;
                $totalEmployer = 0;
                $totalContribution = 0;
                ?>
                <?php foreach($epf_data as $row): ?>
                <?php 
                    $totalWages += $row['gross_salary'];
                    $totalEmployee += $row['epf_employee'];
                    $totalEmployer += $row['epf_employer'];
                    $totalContribution += ($row['epf_employee'] + $row['epf_employer']);
                ?>
                <tr>
                    <td class="text-center"><?= $i++ ?></td>
                    <td class="text-center"><?= $row['epf_number'] ?? '-' ?></td>
                    <td class="text-center"><?= $row['ic_number'] ?? '-' ?></td>
                    <td><?= strtoupper($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td class="text-right"><?= number_format($row['gross_salary'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['epf_employee'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['epf_employer'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['epf_employee'] + $row['epf_employer'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="4" class="text-right">JUMLAH / TOTAL</td>
                    <td class="text-right"><?= number_format($totalWages, 2) ?></td>
                    <td class="text-right"><?= number_format($totalEmployee, 2) ?></td>
                    <td class="text-right"><?= number_format($totalEmployer, 2) ?></td>
                    <td class="text-right"><?= number_format($totalContribution, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Summary -->
        <div style="margin-top: 30px;">
            <table style="width: 400px;">
                <tr>
                    <td><strong>BILANGAN PEKERJA / NO. OF EMPLOYEES:</strong></td>
                    <td style="text-align: right; padding-left: 20px;"><strong><?= count($epf_data) ?></strong></td>
                </tr>
                <tr>
                    <td><strong>JUMLAH CARUMAN / TOTAL CONTRIBUTION:</strong></td>
                    <td style="text-align: right; padding-left: 20px;"><strong>RM <?= number_format($totalContribution, 2) ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        <div class="notes">
            <p><strong>NOTA / NOTES:</strong></p>
            <ol>
                <li>Sila pastikan maklumat yang diberi adalah tepat / Please ensure that the information given is accurate</li>
                <li>Caruman hendaklah dibayar sebelum 15hb bulan berikutnya / Contribution must be paid before 15th of the following month</li>
                <li>Denda lewat bayar akan dikenakan mengikut peraturan KWSP / Late payment penalty will be imposed according to EPF regulations</li>
            </ol>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table>
                <tr>
                    <td class="signature-box">
                        <div class="signature-line">
                            <strong>DISEDIAKAN OLEH / PREPARED BY</strong><br>
                            NAMA / NAME: _____________________<br>
                            TARIKH / DATE: <?= date('d/m/Y') ?>
                        </div>
                    </td>
                    <td class="signature-box">
                        <div class="signature-line">
                            <strong>DISAHKAN OLEH / VERIFIED BY</strong><br>
                            NAMA / NAME: _____________________<br>
                            JAWATAN / POSITION: _______________
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
    function exportToCSV() {
        var csv = [];
        var rows = document.querySelectorAll("table.report-table tr");
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (var j = 0; j < cols.length; j++) {
                var text = cols[j].innerText.replace(/,/g, '');
                row.push('"' + text + '"');
            }
            
            csv.push(row.join(","));        
        }
        
        var csvContent = csv.join("\n");
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement("a");
        
        if (link.download !== undefined) {
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "EPF_Report_<?= $month ?>.csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    </script>
</body>
</html>