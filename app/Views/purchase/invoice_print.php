<!DOCTYPE html>
<html>
<head>
    <title>Invoice - <?= $invoice['invoice_number'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 5px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
        }
        .signature-box {
            display: inline-block;
            width: 200px;
            text-align: center;
            margin-top: 50px;
        }
        .summary-table {
            width: 300px;
            float: right;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 5px;
        }
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>TAX INVOICE</h2>
        <h3><?= $invoice['invoice_number'] ?></h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>From:</strong><br>
                <strong><?= $invoice['supplier_name'] ?></strong><br>
                <?= nl2br($invoice['address1']) ?><br>
                Phone: <?= $invoice['phone'] ?><br>
                Email: <?= $invoice['email_id'] ?>
            </td>
            <td width="50%">
                <strong>Invoice Details:</strong><br>
                <strong>Invoice Date:</strong> <?= date('d-m-Y', strtotime($invoice['invoice_date'])) ?><br>
                <strong>Supplier Invoice:</strong> <?= $invoice['supplier_invoice_number'] ?><br>
                <strong>Supplier Inv Date:</strong> <?= $invoice['supplier_invoice_date'] ? date('d-m-Y', strtotime($invoice['supplier_invoice_date'])) : '-' ?><br>
                <strong>Due Date:</strong> <?= $invoice['due_date'] ? date('d-m-Y', strtotime($invoice['due_date'])) : '-' ?><br>
                <strong>GRN Ref:</strong> <?= $invoice['grn_id'] ? 'GRN-' . $invoice['grn_id'] : '-' ?>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">S.No</th>
                <th width="10%">Code</th>
                <th width="30%">Item Description</th>
                <th width="8%">Qty</th>
                <th width="8%">Rate</th>
                <th width="10%">Taxable</th>
                <th width="7%">Tax %</th>
                <th width="10%">Tax Amt</th>
                <th width="12%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach ($invoice_items as $item): ?>
            <?php 
                $taxable = $item['quantity'] * $item['unit_price'];
            ?>
            <tr>
                <td class="text-center"><?= $sno++ ?></td>
                <td><?= $item['item_code'] ?></td>
                <td><?= $item['item_name'] ?></td>
                <td class="text-right"><?= number_format($item['quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['unit_price'], 2) ?></td>
                <td class="text-right"><?= number_format($taxable, 2) ?></td>
                <td class="text-right"><?= number_format($item['tax_rate'], 2) ?></td>
                <td class="text-right"><?= number_format($item['tax_amount'], 2) ?></td>
                <td class="text-right"><?= number_format($item['total_amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="text-right"><strong>Subtotal:</strong></td>
            <td class="text-right">₹ <?= number_format($invoice['subtotal'], 2) ?></td>
        </tr>
        <tr>
            <td class="text-right"><strong>Total Tax:</strong></td>
            <td class="text-right">₹ <?= number_format($invoice['tax_amount'], 2) ?></td>
        </tr>
        <?php if ($invoice['discount_amount'] > 0): ?>
        <tr>
            <td class="text-right"><strong>Discount:</strong></td>
            <td class="text-right">₹ <?= number_format($invoice['discount_amount'], 2) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($invoice['other_charges'] > 0): ?>
        <tr>
            <td class="text-right"><strong>Other Charges:</strong></td>
            <td class="text-right">₹ <?= number_format($invoice['other_charges'], 2) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="text-right"><h3>Total:</h3></td>
            <td class="text-right"><h3>₹ <?= number_format($invoice['total_amount'], 2) ?></h3></td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <?php if ($invoice['notes']): ?>
    <div style="margin-top: 20px;">
        <strong>Notes:</strong><br>
        <?= nl2br($invoice['notes']) ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Terms & Conditions:</strong><br>
                    1. Payment should be made within the due date.<br>
                    2. Interest will be charged on overdue payments.<br>
                    3. Goods once sold will not be taken back.
                </td>
                <td width="50%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Authorized Signatory
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>