<!DOCTYPE html>
<html>
<head>
    <title>Due Report - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .filter-info {
            margin-bottom: 20px;
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .summary-section {
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .summary-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .due-amount {
            color: #d32f2f;
            font-weight: bold;
        }
        .paid-amount {
            color: #388e3c;
        }
        .status-current {
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .status-overdue {
            background-color: #f8d7da;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .status-critical {
            background-color: #d32f2f;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .status-paid {
            background-color: #d4edda;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .entity-customer {
            background-color: #cce5ff;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .entity-supplier {
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
        }
        @media print {
            body {
                margin: 10px;
            }
            .no-print {
                display: none;
            }
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GRASP SOFTWARE</div>
        <div class="report-title">DUE REPORT</div>
        <div>Generated on: <?php echo date('d-m-Y H:i:s'); ?></div>
    </div>

    <div class="filter-info">
        <h3>Report Filters:</h3>
        <table style="width: 100%;">
            <tr>
                <td><strong>Report Type:</strong> <?php echo ucfirst($filters['report_type']); ?></td>
                <td><strong>Entity Type:</strong> <?php echo ucfirst($filters['entity_type']); ?></td>
            </tr>
            <tr>
                <td><strong>Date From:</strong> <?php echo !empty($filters['from_date']) ? date('d-m-Y', strtotime($filters['from_date'])) : 'All'; ?></td>
                <td><strong>Date To:</strong> <?php echo !empty($filters['to_date']) ? date('d-m-Y', strtotime($filters['to_date'])) : 'All'; ?></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($summary)) { ?>
    <div class="summary-section">
        <h3>Summary:</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Entity Type</th>
                    <th>Total Invoices</th>
                    <th>Total Amount</th>
                    <th>Total Paid</th>
                    <th>Total Due</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Customer</td>
                    <td class="text-right"><?php echo number_format($summary['customer']['total_invoices'] ?? 0); ?></td>
                    <td class="text-right"><?php echo number_format($summary['customer']['total_amount'] ?? 0, 2); ?></td>
                    <td class="text-right"><?php echo number_format($summary['customer']['total_paid'] ?? 0, 2); ?></td>
                    <td class="text-right due-amount"><?php echo number_format($summary['customer']['total_due'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td class="text-right"><?php echo number_format($summary['supplier']['total_invoices'] ?? 0); ?></td>
                    <td class="text-right"><?php echo number_format($summary['supplier']['total_amount'] ?? 0, 2); ?></td>
                    <td class="text-right"><?php echo number_format($summary['supplier']['total_paid'] ?? 0, 2); ?></td>
                    <td class="text-right due-amount"><?php echo number_format($summary['supplier']['total_due'] ?? 0, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>

    <?php if (!empty($due_data)) { ?>
    <div class="data-section">
        <h3>Detailed Report:</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Grand Total</th>
                    <th>Paid Amount</th>
                    <th>Due Amount</th>
                    <th>Days Overdue</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sno = 1;
                $total_grand_total = 0;
                $total_paid_amount = 0;
                $total_due_amount = 0;
                
                foreach($due_data as $row) { 
                    $total_grand_total += $row['grand_total'];
                    $total_paid_amount += $row['paid_amount'];
                    $total_due_amount += $row['due_amount'];
                    
                    $status_class = '';
                    $status_text = '';
                    
                    if ($row['due_amount'] <= 0) {
                        $status_class = 'status-paid';
                        $status_text = 'PAID';
                    } elseif ($row['days_overdue'] <= 30) {
                        $status_class = 'status-current';
                        $status_text = 'CURRENT';
                    } elseif ($row['days_overdue'] <= 60) {
                        $status_class = 'status-overdue';
                        $status_text = 'OVERDUE';
                    } else {
                        $status_class = 'status-critical';
                        $status_text = 'CRITICAL';
                    }
                    
                    $entity_class = ($row['entity_type'] == 'Customer') ? 'entity-customer' : 'entity-supplier';
                ?>
                <tr>
                    <td class="text-center"><?php echo $sno++; ?></td>
                    <td><span class="<?php echo $entity_class; ?>"><?php echo $row['entity_type']; ?></span></td>
                    <td><?php echo $row['entity_name'] . ' (' . $row['entity_code'] . ')'; ?></td>
                    <td><?php echo $row['invoice_no']; ?></td>
                    <td class="text-center"><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                    <td class="text-right"><?php echo number_format($row['grand_total'], 2); ?></td>
                    <td class="text-right paid-amount"><?php echo number_format($row['paid_amount'], 2); ?></td>
                    <td class="text-right <?php echo ($row['due_amount'] > 0) ? 'due-amount' : 'paid-amount'; ?>">
                        <?php echo number_format($row['due_amount'], 2); ?>
                    </td>
                    <td class="text-center">
                        <?php echo ($row['due_amount'] > 0) ? $row['days_overdue'] . ' days' : '-'; ?>
                    </td>
                    <td class="text-center">
                        <span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong><?php echo number_format($total_grand_total, 2); ?></strong></td>
                    <td class="text-right"><strong><?php echo number_format($total_paid_amount, 2); ?></strong></td>
                    <td class="text-right due-amount"><strong><?php echo number_format($total_due_amount, 2); ?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>