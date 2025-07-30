<!DOCTYPE html>
<html>
<head>
    <title>EA Forms - Year <?= $year ?></title>
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .container { width: 100%; max-width: 210mm; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 5px 0; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; background-color: #f0f0f0; padding: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 5px; }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .page-break { page-break-after: always; }
        .signature-section { margin-top: 40px; }
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <h2>Batch EA Forms for Year <?= $year ?></h2>
        <p>Total Forms: <?= count($ea_forms) ?></p>
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; margin: 10px;">
            Print All EA Forms
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; margin: 10px;">
            Close Window
        </button>
    </div>

    <?php foreach($ea_forms as $index => $ea_data): ?>
    <div class="container <?= $index < count($ea_forms) - 1 ? 'page-break' : '' ?>">
        <!-- Header -->
        <div class="header">
            <h2>BORANG EA</h2>
            <h3>PENYATA SARAAN DARIPADA PENGGAJIAN</h3>
            <h3>BAGI TAHUN BERAKHIR 31 DISEMBER <?= $ea_data['year'] ?></h3>
            <p><strong>LEMBAGA HASIL DALAM NEGERI MALAYSIA</strong></p>
        </div>

        <!-- Part A: Employee Information -->
        <div class="section">
            <div class="section-title">A. BUTIR-BUTIR PEKERJA / PENERIMA PENCEN</div>
            <table>
                <tr>
                    <td width="30%">Nama Penuh Pekerja:</td>
                    <td><strong><?= strtoupper($ea_data['staff']['first_name'] . ' ' . $ea_data['staff']['last_name']) ?></strong></td>
                </tr>
                <tr>
                    <td>No. Cukai Pendapatan:</td>
                    <td><?= $ea_data['staff']['income_tax_number'] ?? 'TIADA' ?></td>
                </tr>
                <tr>
                    <td>No. Pekerja:</td>
                    <td><?= $ea_data['staff']['staff_code'] ?></td>
                </tr>
                <tr>
                    <td>No. KWSP:</td>
                    <td><?= $ea_data['staff']['epf_number'] ?? 'TIADA' ?></td>
                </tr>
            </table>
        </div>

        <!-- Part B: Employment Details -->
        <div class="section">
            <div class="section-title">B. BUTIR-BUTIR PENGGAJIAN</div>
            <table>
                <tr>
                    <td width="70%">Tempoh Penggajian Dalam Tahun <?= $ea_data['year'] ?></td>
                    <td class="text-center">
                        <?php
                        if (!empty($ea_data['monthly'])) {
                            $firstMonth = substr($ea_data['monthly'][0]['payroll_month'], 5, 2);
                            $lastMonth = substr(end($ea_data['monthly'])['payroll_month'], 5, 2);
                            echo "01/{$firstMonth}/{$ea_data['year']} - 31/{$lastMonth}/{$ea_data['year']}";
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Part C: Remuneration -->
        <div class="section">
            <div class="section-title">C. PENDAPATAN PENGGAJIAN</div>
            <table>
                <tr>
                    <th width="70%">BUTIRAN</th>
                    <th class="text-center">RM</th>
                </tr>
                <tr>
                    <td>1. Gaji, Upah, Elaun, Overtime, Bonus, Komisen</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_gross'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">a) Gaji Pokok</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_basic'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">b) Elaun</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_allowances'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">c) Komisen</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_commission'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td>2. Manfaat Berupa Barangan (BIK)</td>
                    <td class="text-right">0.00</td>
                </tr>
                <tr>
                    <td><strong>JUMLAH PENDAPATAN KASAR</strong></td>
                    <td class="text-right"><strong><?= number_format($ea_data['summary']['total_gross'] ?? 0, 2) ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Part D: Deductions -->
        <div class="section">
            <div class="section-title">D. POTONGAN YANG DIBENARKAN</div>
            <table>
                <tr>
                    <th width="70%">BUTIRAN</th>
                    <th class="text-center">RM</th>
                </tr>
                <tr>
                    <td>1. Caruman KWSP Pekerja</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_epf'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td>2. Caruman PERKESO Pekerja</td>
                    <td class="text-right"><?= number_format($ea_data['summary']['total_socso'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td><strong>JUMLAH POTONGAN</strong></td>
                    <td class="text-right"><strong><?= number_format(($ea_data['summary']['total_epf'] ?? 0) + ($ea_data['summary']['total_socso'] ?? 0), 2) ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Part E: Tax Deduction -->
        <div class="section">
            <div class="section-title">E. POTONGAN CUKAI BULANAN (PCB)</div>
            <table>
                <tr>
                    <td width="70%">Jumlah PCB Yang Telah Dipotong</td>
                    <td class="text-right"><strong><?= number_format($ea_data['summary']['total_pcb'] ?? 0, 2) ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="no-border">
                <tr>
                    <td class="no-border" width="50%">
                        <p><strong>Disediakan Oleh:</strong></p>
                        <br><br>
                        <p>_______________________________</p>
                        <p>Nama:</p>
                        <p>Jawatan:</p>
                        <p>Tarikh:</p>
                    </td>
                    <td class="no-border" width="50%">
                        <p><strong>Disahkan Oleh:</strong></p>
                        <br><br>
                        <p>_______________________________</p>
                        <p>Nama:</p>
                        <p>Jawatan:</p>
                        <p>Tarikh:</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
</body>
</html>