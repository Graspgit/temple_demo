<!DOCTYPE html>
<html>
<head>
    <title>GRN - <?= $grn['grn_number'] ?></title>
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
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>GOODS RECEIPT NOTE</h2>
        <h3><?= $grn['grn_number'] ?></h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Supplier Details:</strong><br>
                <?= $grn['supplier_name'] ?><br>
                <?= nl2br($grn['address1']) ?><br>
                Phone: <?= $grn['phone'] ?><br>
                Email: <?= $grn['email_id'] ?>
            </td>
            <td width="50%">
                <strong>GRN Date:</strong> <?= date('d-m-Y', strtotime($grn['grn_date'])) ?><br>
                <strong>PO Number:</strong> <?= $grn['po_number'] ?? '-' ?><br>
                <strong>Invoice No:</strong> <?= $grn['invoice_number'] ?? '-' ?><br>
                <strong>Invoice Date:</strong> <?= $grn['invoice_date'] ? date('d-m-Y', strtotime($grn['invoice_date'])) : '-' ?><br>
                <strong>Delivery Note:</strong> <?= $grn['delivery_note_number'] ?? '-' ?>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">S.No</th>
                <th width="10%">Code</th>
                <th width="25%">Item Description</th>
                <th width="8%">Ordered</th>
                <th width="8%">Received</th>
                <th width="8%">Accepted</th>
                <th width="8%">Rejected</th>
                <th width="8%">Rate</th>
                <th width="10%">Amount</th>
                <th width="10%">Batch/Expiry</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach ($grn_items as $item): ?>
            <tr>
                <td class="text-center"><?= $sno++ ?></td>
                <td><?= $item['item_code'] ?></td>
                <td><?= $item['item_name'] ?></td>
                <td class="text-right"><?= number_format($item['ordered_quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['received_quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['accepted_quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['rejected_quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['unit_price'], 2) ?></td>
                <td class="text-right"><?= number_format($item['total_amount'], 2) ?></td>
                <td>
                    <?= $item['batch_number'] ?? '-' ?><br>
                    <?= $item['expiry_date'] ? date('d-m-Y', strtotime($item['expiry_date'])) : '' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right"><strong><?= number_format($grn['subtotal'], 2) ?></strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><strong>Tax:</strong></td>
                <td class="text-right"><strong><?= number_format($grn['tax_amount'], 2) ?></strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><strong>Discount:</strong></td>
                <td class="text-right"><strong><?= number_format($grn['discount_amount'], 2) ?></strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong>₹ <?= number_format($grn['total_amount'], 2) ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <?php if ($grn['notes']): ?>
    <div>
        <strong>Notes:</strong><br>
        <?= nl2br($grn['notes']) ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="33%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Prepared By
                    </div>
                </td>
                <td width="33%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Checked By
                    </div>
                </td>
                <td width="33%" class="text-center">
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