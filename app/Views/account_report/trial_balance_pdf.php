<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trial Balance</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .period {
            font-size: 10pt;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th {
            background-color: #e0e0e0;
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }
        
        td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 9pt;
        }
        
        th:nth-child(3),
        th:nth-child(4),
        td:nth-child(3),
        td:nth-child(4) {
            text-align: right;
        }
        
        .group-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .ledger-row {
            background-color: #fff;
        }
        
        .level-0 { padding-left: 0px; }
        .level-1 { padding-left: 15px; }
        .level-2 { padding-left: 30px; }
        .level-3 { padding-left: 45px; }
        
        .total-row {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        
        .total-row td {
            border: 1px solid #333;
            padding: 8px;
        }
        
        .amount {
            font-family: monospace;
        }
        
        .summary {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #999;
            background-color: #f9f9f9;
            font-size: 10pt;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?php echo $company_name; ?></div>
        <div class="report-title">TRIAL BALANCE</div>
        <div class="period">From <?php echo $from_date; ?> to <?php echo $to_date; ?></div>
    </div>
    
    <div class="summary">
        <span class="summary-item">
            <strong>Total Debit:</strong> <?php echo number_format($totals['debit'], 2); ?>
        </span>
        <span class="summary-item">
            <strong>Total Credit:</strong> <?php echo number_format($totals['credit'], 2); ?>
        </span>
        <span class="summary-item">
            <strong>Difference:</strong> <?php echo number_format(abs($totals['debit'] - $totals['credit']), 2); ?>
        </span>
    </div>
    
    <table>
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
            function printGroupPDF($groups, $level = 0) {
                foreach ($groups as $group) {
                    $hasData = ($group['total_debit'] > 0 || $group['total_credit'] > 0) || 
                              count($group['ledgers']) > 0 || count($group['children']) > 0;
                    
                    if ($hasData) {
                        ?>
                        <tr class="group-row">
                            <td><?php echo htmlspecialchars($group['code']); ?></td>
                            <td class="level-<?php echo $level; ?>">
                                <strong><?php echo htmlspecialchars($group['name']); ?></strong>
                            </td>
                            <td class="amount"><?php echo number_format($group['total_debit'], 2); ?></td>
                            <td class="amount"><?php echo number_format($group['total_credit'], 2); ?></td>
                        </tr>
                        <?php
                        
                        // Print child groups
                        if (!empty($group['children'])) {
                            printGroupPDF($group['children'], $level + 1);
                        }
                        
                        // Print ledgers
                        foreach ($group['ledgers'] as $ledger) {
                            ?>
                            <tr class="ledger-row">
                                <td><?php echo htmlspecialchars($ledger['code']); ?></td>
                                <td class="level-<?php echo ($level + 1); ?>">
                                    <?php echo htmlspecialchars($ledger['name']); ?>
                                </td>
                                <td class="amount"><?php echo number_format($ledger['debit'], 2); ?></td>
                                <td class="amount"><?php echo number_format($ledger['credit'], 2); ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
            }
            
            printGroupPDF($trial_balance_data);
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
</body>
</html>