<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet - Print View</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 18pt;
            margin-bottom: 5px;
            text-transform: uppercase;
            color: #000;
        }

        .header h2 {
            font-size: 14pt;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .header .date-info {
            font-size: 10pt;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ccc;
            font-size: 10pt;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }

        /* Group headers */
        .group-header td {
            background-color: #e8f4f8;
            font-weight: bold;
            font-size: 10pt;
            border: 1px solid #ccc;
        }

        /* Subgroup headers */
        .subgroup-header td {
            background-color: #f0f7ff;
            font-weight: bold;
            padding-left: 20px;
            font-size: 9.5pt;
        }

        /* Ledger rows */
        .ledger-row td:first-child {
            padding-left: 40px;
        }

        /* Total rows */
        .group-total td, .subgroup-total td {
            background-color: #f5f5f5;
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }

        /* Amount columns */
        td:nth-child(2), td:nth-child(3), th:nth-child(2), th:nth-child(3) {
            text-align: right;
            width: 120px;
        }

        /* Page break control */
        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        
        /* No links in print */
        a {
            color: inherit !important;
            text-decoration: none !important;
            cursor: default !important;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1><?= $heading ?></h1>
        <h2>Statement of Financial Position</h2>
        <div class="date-info">
            As at <?= date('d F Y', strtotime($tdate)) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60%;">Account Description</th>
                <th style="width: 20%;">Current Year (RM)</th>
                <th style="width: 20%;">Previous Year (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list as $row): ?>
                <?= $row ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-line">Prepared By</div>
        </div>
        <div class="signature-block">
            <div class="signature-line">Reviewed By</div>
        </div>
        <div class="signature-block">
            <div class="signature-line">Approved By</div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on <?= date('d/m/Y h:i:s A') ?></p>
        <p>This is a computer generated document</p>
    </div>
</body>
</html>