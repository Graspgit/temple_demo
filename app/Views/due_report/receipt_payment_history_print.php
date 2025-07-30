<!DOCTYPE html>
<html>
<head>
    <title>Receipt & Payment History - Print</title>
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
        .invoice-info {
            margin-bottom: 20px;
            background-color: #f5f5f5;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .invoice-info h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #333;
        }
        .invoice-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .invoice-detail-item {
            flex: 1;
            min-width: 200px;
        }
        .invoice-detail-item strong {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .invoice-detail-value {
            font-size: 14px;
            font-weight: bold;
        }
        .amount-paid { color: #28a745; }
        .amount-due { color: #dc3545; }
        .amount-total { color: #007bff; }
        .status-paid { 
            background-color: #d4edda; 
            color: #155724; 
            padding: 3px 8px; 
            border-radius: 3px; 
            font-size: 12px;
        }
        .status-outstanding { 
            background-color: #f8d7da; 
            color: #721c24; 
            padding: 3px 8px; 
            border-radius: 3px; 
            font-size: 12px;
        }
        .history-section {
            margin-top: 20px;
        }
        .history-section h3 {
            margin-bottom: 15px;
            color: #333;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
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
        .receipt-type {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .payment-type {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        @media print {
            body {
                margin: 10px;
            }
            .no-print {
                display: none;
            }
        }
        .summary-totals {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
        }
        .summary-totals h4 {
            margin-top: 0;
            margin-bottom: 15px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .summary-item.total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GRASP SOFTWARE</div>
        <div class="report-title"><?= ($invoice_data['invoice_type'] == 1) ? 'RECEIPT HISTORY' : 'PAYMENT HISTORY' ?></div>
        <div>Generated on: <?php echo date('d-m-Y H:i:s'); ?></div>
    </div>

    <!-- Invoice Information -->
    <?php if (!empty($invoice_data)) { ?>
    <div class="invoice-info">
        <h3>Invoice Details</h3>
        <div class="invoice-details">
            <div class="invoice-detail-item">
                <strong>Invoice No:</strong>
                <div class="invoice-detail-value"><?= esc($invoice_data['invoice_no']) ?></div>
            </div>
            <div class="invoice-detail-item">
                <strong>Date:</strong>
                <div class="invoice-detail-value"><?= date('d-m-Y', strtotime($invoice_data['date'])) ?></div>
            </div>
            <div class="invoice-detail-item">
                <strong><?= esc($invoice_data['entity_type']) ?>:</strong>
                <div class="invoice-detail-value"><?= esc($invoice_data['entity_name']) ?> (<?= esc($invoice_data['entity_code']) ?>)</div>
            </div>
            <div class="invoice-detail-item">
                <strong>Invoice Type:</strong>
                <div class="invoice-detail-value"><?= ($invoice_data['invoice_type'] == 1) ? 'Sales Invoice' : 'Purchase Invoice' ?></div>
            </div>
        </div>
        <div class="invoice-details" style="margin-top: 15px;">
            <div class="invoice-detail-item">
                <strong>Grand Total:</strong>
                <div class="invoice-detail-value amount-total"><?= number_format($invoice_data['grand_total'], 2) ?></div>
            </div>
            <div class="invoice-detail-item">
                <strong>Paid Amount:</strong>
                <div class="invoice-detail-value amount-paid"><?= number_format($invoice_data['paid_amount'], 2) ?></div>
            </div>
            <div class="invoice-detail-item">
                <strong>Due Amount:</strong>
                <div class="invoice-detail-value amount-due"><?= number_format($invoice_data['due_amount'], 2) ?></div>
            </div>
            <div class="invoice-detail-item">
                <strong>Status:</strong>
                <div class="invoice-detail-value">
                    <?php if ($invoice_data['due_amount'] <= 0) { ?>
                        <span class="status-paid">PAID</span>
                    <?php } else { ?>
                        <span class="status-outstanding">OUTSTANDING</span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Receipt & Payment History -->
    <div class="history-section">
        <h3><?= ($invoice_data['invoice_type'] == 1) ? 'Receipt History' : 'Payment History' ?></h3>
        <?php if (!empty($receipt_payment_history)) { ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Date</th>
                    <th>Voucher Number</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sno = 1;
                $total_receipts = 0;
                $total_payments = 0;
                
                foreach($receipt_payment_history as $row) { 
                    $amount = ($row['entry_type'] == 'Receipt') ? $row['dr_total'] : $row['cr_total'];
                    
                    if ($row['entry_type'] == 'Receipt') {
                        $total_receipts += $amount;
                    } else {
                        $total_payments += $amount;
                    }
                ?>
                <tr>
                    <td class="text-center"><?= $sno++ ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($row['entry_date'])) ?></td>
                    <td class="text-center"><?= esc($row['voucher_number'] ?: '-') ?></td>
                    <td class="text-right">
                        <strong><?= number_format($amount, 2) ?></strong>
                    </td>
                    <td class="text-center"><?= esc($row['payment_mode'] ?: '-') ?></td>
                    <td><?= esc($row['narration'] ?: '-') ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Summary Totals -->
        <div class="summary-totals">
            <h4>Summary</h4>
            <div class="summary-item total">
                <span>Total <?= ($invoice_data['invoice_type'] == 1) ? 'Receipts' : 'Payments' ?>:</span>
                <span class="amount-paid"><?= number_format($total_receipts + $total_payments, 2) ?></span>
            </div>
        </div>

        <?php } else { ?>
        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
            <strong>No <?= ($invoice_data['invoice_type'] == 1) ? 'Receipt' : 'Payment' ?> History Found!</strong><br>
            There are no <?= ($invoice_data['invoice_type'] == 1) ? 'receipt' : 'payment' ?> transactions recorded for this invoice.
        </div>
        <?php } ?>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>