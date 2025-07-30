<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance - Print</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        
        .logo img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .period {
            font-size: 14px;
            color: #666;
        }
        
        .trial-balance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .trial-balance-table th {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .trial-balance-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .trial-balance-table th:nth-child(3),
        .trial-balance-table th:nth-child(4),
        .trial-balance-table td:nth-child(3),
        .trial-balance-table td:nth-child(4) {
            text-align: right;
        }
        
        .group-row {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        
        .ledger-row {
            background-color: #fff;
        }
        
        .level-0 { padding-left: 0px; }
        .level-1 { padding-left: 20px; }
        .level-2 { padding-left: 40px; }
        .level-3 { padding-left: 60px; }
        
        .total-row {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        
        .total-row td {
            border: 1px solid #333;
            padding: 10px;
        }
        
        .amount {
            font-family: 'Courier New', monospace;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        .summary-box {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
        
        .summary-label {
            font-weight: bold;
            color: #666;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print</button>
    
    <div class="container">
        <div class="header">
            <?php if (!empty($logo)): ?>
            <div class="logo">
                <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $logo; ?>" alt="Logo">
            </div>
            <?php endif; ?>
            
            <div class="company-name"><?php echo $company_name; ?></div>
            <div class="report-title">TRIAL BALANCE</div>
            <div class="period">From <?php echo $from_date; ?> to <?php echo $to_date; ?></div>
        </div>
        
        <div class="summary-box">
            <div class="summary-item">
                <span class="summary-label">Total Debit:</span>
                <span class="summary-value amount"><?php echo number_format($totals['debit'], 2); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Credit:</span>
                <span class="summary-value amount"><?php echo number_format($totals['credit'], 2); ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Difference:</span>
                <span class="summary-value amount"><?php echo number_format(abs($totals['debit'] - $totals['credit']), 2); ?></span>
            </div>
        </div>
        
        <table class="trial-balance-table">
            <thead>
                <tr>
                    <th style="width: 15%;">A/C No</th>
                    <th style="width: 55%;">Description</th>
                    <th style="width: 15%;">Debit</th>
                    <th style="width: 15%;">Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                function printGroup($groups, $level = 0) {
                    foreach ($groups as $group) {
                        $hasData = ($group['total_debit'] > 0 || $group['total_credit'] > 0) || 
                                  count($group['ledgers']) > 0 || count($group['children']) > 0;
                        
                        if ($hasData) {
                            ?>
                            <tr class="group-row">
                                <td><?php echo $group['code']; ?></td>
                                <td class="level-<?php echo $level; ?>">
                                    <strong><?php echo $group['name']; ?></strong>
                                </td>
                                <td class="amount"><?php echo number_format($group['total_debit'], 2); ?></td>
                                <td class="amount"><?php echo number_format($group['total_credit'], 2); ?></td>
                            </tr>
                            <?php
                            
                            // Print child groups
                            if (!empty($group['children'])) {
                                printGroup($group['children'], $level + 1);
                            }
                            
                            // Print ledgers
                            foreach ($group['ledgers'] as $ledger) {
                                ?>
                                <tr class="ledger-row">
                                    <td><?php echo $ledger['code']; ?></td>
                                    <td class="level-<?php echo ($level + 1); ?>">
                                        <?php echo $ledger['name']; ?>
                                    </td>
                                    <td class="amount"><?php echo number_format($ledger['debit'], 2); ?></td>
                                    <td class="amount"><?php echo number_format($ledger['credit'], 2); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                
                printGroup($trial_balance_data);
                ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">TOTAL</td>
                    <td class="amount"><?php echo number_format($totals['debit'], 2); ?></td>
                    <td class="amount"><?php echo number_format($totals['credit'], 2); ?></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="footer">
            <p>Generated on <?php echo date('d-m-Y h:i A'); ?></p>
            <p>This is a computer generated report</p>
        </div>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function() {
            // Optional: automatically print when page loads
            window.print();
        }
        
        // Close window after print
        window.onafterprint = function() {
            // Optional: close window after printing
            // window.close();
        }
    </script>
</body>
</html>