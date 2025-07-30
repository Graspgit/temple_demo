<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Income and Expenditure Statement - Print</title>
    <style>
        @media print {
            body {
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .page-break {
                page-break-after: always;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #555;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .report-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        
        .report-table td:not(:first-child) {
            text-align: right;
        }
        
        .group-header-0 {
            font-weight: bold;
            font-size: 14px;
            background-color: #f0f0f0;
        }
        
        .group-header-1 {
            font-weight: bold;
            padding-left: 20px;
            background-color: #f8f8f8;
        }
        
        .group-header-2 {
            font-weight: bold;
            padding-left: 40px;
            background-color: #fcfcfc;
        }
        
        .ledger-row {
            padding-left: 60px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        
        .grand-total-row {
            font-weight: bold;
            background-color: #e0e0e0;
            font-size: 14px;
        }
        
        .amount-negative {
            color: #d9534f;
        }
        
        .summary {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f5f5f5;
            border: 2px solid #ddd;
        }
        
        .summary h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        
        .summary p {
            margin: 10px 0 0;
            font-size: 16px;
            color: #666;
        }
        
        @media screen {
            .print-button {
                text-align: center;
                margin: 20px 0;
                no-print;
            }
            
            .print-button button {
                padding: 10px 20px;
                font-size: 16px;
                background-color: #337ab7;
                color: white;
                border: none;
                cursor: pointer;
                border-radius: 4px;
            }
            
            .print-button button:hover {
                background-color: #286090;
            }
        }
        
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">Print Report</button>
        <button onclick="window.close()">Close</button>
    </div>
    
    <div class="header">
        <?php if (!empty($temple_logo)): ?>
            <img src="<?= base_url() ?>/uploads/main/<?= $temple_logo ?>" style="max-height: 60px; margin-bottom: 10px;">
        <?php endif; ?>
        <h1><?= $temple_name ?></h1>
        <h2>Income and Expenditure Statement</h2>
        <p>
            Period: 
            <?php if ($breakdown == 'monthly'): ?>
                <?= date('F Y', strtotime($from_date)) ?> to <?= date('F Y', strtotime($to_date)) ?>
            <?php else: ?>
                <?= date('d-M-Y', strtotime($from_date)) ?> to <?= date('d-M-Y', strtotime($to_date)) ?>
            <?php endif; ?>
        </p>
    </div>
    
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 40%; text-align: left;">Account Name</th>
                <?php if ($breakdown == 'monthly'): ?>
                    <?php foreach ($getMonthsInRange as $month): ?>
                        <th><?= date('M Y', strtotime($month['date'])) ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Revenue Section
            if ($reportData['revenue']):
                echo printGroupSection($reportData['revenue'], $breakdown, $getMonthsInRange);
            endif;
            
            // Direct Cost Section
            if ($reportData['direct_cost']):
                echo printGroupSection($reportData['direct_cost'], $breakdown, $getMonthsInRange);
            endif;
            
            // Gross Profit Row
            echo printCalculationRow('Gross Surplus/Deficit', $calculations, 'gross_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
            
            // Incomes Section
            if ($reportData['incomes']):
                echo printGroupSection($reportData['incomes'], $breakdown, $getMonthsInRange);
            endif;
            
            // Expenses Section
            if ($reportData['expenses']):
                echo printGroupSection($reportData['expenses'], $breakdown, $getMonthsInRange);
            endif;
            
            // Net Profit Row
            echo printCalculationRow('Surplus/(Deficit) Before Taxation', $calculations, 'net_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
            
            // Taxation Section
            if ($reportData['taxation']):
                echo printGroupSection($reportData['taxation'], $breakdown, $getMonthsInRange);
            endif;
            
            // Final Profit Row
            echo printCalculationRow('Surplus/(Deficit) After Taxation', $calculations, 'final_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
            ?>
        </tbody>
    </table>
    
    <div class="summary">
        <h3>
            <?php
            $finalProfit = $calculations['total']['final_profit'];
            echo $finalProfit >= 0 ? 'Total Surplus' : 'Total Deficit';
            ?>
        </h3>
        <p><?= number_format(abs($finalProfit), 2) ?></p>
    </div>
    
    <script>
        // Auto-print on load
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

<?php
// Print helper functions
function printGroupSection($groupData, $breakdown, $monthsRange, $level = 0) {
    $html = '';
    
    // Group header
    $html .= '<tr class="group-header-' . $level . '">';
    $html .= '<td>' . $groupData['group']['name'] . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $html .= '<td></td>';
        }
    }
    $html .= '<td></td>';
    $html .= '</tr>';
    
    // Render ledgers
    foreach ($groupData['ledgers'] as $ledgerData) {
        $html .= printLedgerRow($ledgerData, $breakdown, $monthsRange);
    }
    
    // Render subgroups
    foreach ($groupData['subgroups'] as $subgroup) {
        $html .= printSubgroup($subgroup, $breakdown, $monthsRange, $level + 1);
    }
    
    // Group total
    $html .= printTotalRow('Total ' . $groupData['group']['name'], $groupData['totals'], $breakdown, $monthsRange);
    
    return $html;
}

function printSubgroup($subgroupData, $breakdown, $monthsRange, $level = 1) {
    $html = '';
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    
    // Subgroup header
    $html .= '<tr class="group-header-' . $level . '">';
    $html .= '<td>' . $indent . $subgroupData['group']['name'] . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $html .= '<td></td>';
        }
    }
    $html .= '<td></td>';
    $html .= '</tr>';
    
    // Render ledgers
    foreach ($subgroupData['ledgers'] as $ledgerData) {
        $html .= printLedgerRow($ledgerData, $breakdown, $monthsRange, $level);
    }
    
    // Render nested subgroups
    foreach ($subgroupData['subgroups'] as $nestedSubgroup) {
        $html .= printSubgroup($nestedSubgroup, $breakdown, $monthsRange, $level + 1);
    }
    
    // Subgroup total
    if (count($subgroupData['ledgers']) > 0 || count($subgroupData['subgroups']) > 0) {
        $html .= printTotalRow('Total ' . $subgroupData['group']['name'], $subgroupData['totals'], $breakdown, $monthsRange, $level);
    }
    
    return $html;
}

function printLedgerRow($ledgerData, $breakdown, $monthsRange, $level = 0) {
    $ledger = $ledgerData['ledger'];
    $code = '(' . $ledger['left_code'] . '/' . $ledger['right_code'] . ')';
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level + 1);
    
    $html = '<tr>';
    $html .= '<td class="ledger-row">' . $indent . $code . ' ' . $ledger['name'] . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $ledgerData['amounts'][$month['date']] ?? 0;
            $html .= '<td>' . formatPrintAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td>' . formatPrintAmount($ledgerData['total']) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function printTotalRow($label, $totals, $breakdown, $monthsRange, $level = 0) {
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    
    $html = '<tr class="total-row">';
    $html .= '<td>' . $indent . $label . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $totals[$month['date']] ?? 0;
            $html .= '<td>' . formatPrintAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td>' . formatPrintAmount($totals['total'] ?? 0) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function printCalculationRow($label, $calculations, $key, $breakdown, $monthsRange, $class = '') {
    $html = '<tr class="' . $class . '">';
    $html .= '<td>' . $label . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $calculations[$month['date']][$key] ?? 0;
            $html .= '<td>' . formatPrintAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td>' . formatPrintAmount($calculations['total'][$key] ?? 0) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function formatPrintAmount($amount) {
    if ($amount == 0) {
        return '0.00';
    } elseif ($amount < 0) {
        return '<span class="amount-negative">(' . number_format(abs($amount), 2) . ')</span>';
    } else {
        return number_format($amount, 2);
    }
}
?>