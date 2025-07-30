<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - <?= $po['po_number'] ?></title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 10px 0 0 0;
            font-size: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        
        .company-info {
            margin-top: 10px;
            font-size: 12px;
        }
        
        .po-details {
            margin-bottom: 30px;
        }
        
        .po-details table {
            width: 100%;
        }
        
        .po-details td {
            padding: 5px 0;
        }
        
        .supplier-section {
            margin-bottom: 30px;
        }
        
        .supplier-box {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .totals {
            margin-left: auto;
            width: 300px;
        }
        
        .totals table {
            width: 100%;
        }
        
        .totals td {
            padding: 5px 8px;
        }
        
        .totals .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }
        
        .terms-section {
            margin-top: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }
        
        .terms-section h4 {
            margin-top: 0;
        }
        
        .signature-section {
            margin-top: 50px;
        }
        
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .print-button:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print</button>
    
    <div class="header">
        <?php if (!empty($_SESSION['logo_img'])): ?>
            <img src="<?= base_url('uploads/main/' . $_SESSION['logo_img']) ?>" class="logo" alt="Logo">
        <?php endif; ?>
        <h1><?= $_SESSION['site_title'] ?></h1>
        <div class="company-info">
            <?php if (!empty($_SESSION['address'])): ?>
                <?= $_SESSION['address'] ?><br>
            <?php endif; ?>
            <?php if (!empty($_SESSION['phone'])): ?>
                Phone: <?= $_SESSION['phone'] ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['email'])): ?>
                | Email: <?= $_SESSION['email'] ?>
            <?php endif; ?>
        </div>
        <h2>PURCHASE ORDER</h2>
    </div>
    
    <div class="po-details">
        <table>
            <tr>
                <td width="50%">
                    <strong>PO Number:</strong> <?= $po['po_number'] ?><br>
                    <strong>PO Date:</strong> <?= date('d-m-Y', strtotime($po['po_date'])) ?><br>
                    <?php if ($po['delivery_date']): ?>
                        <strong>Expected Delivery:</strong> <?= date('d-m-Y', strtotime($po['delivery_date'])) ?><br>
                    <?php endif; ?>
                </td>
                <td width="50%" style="text-align: right;">
                    <?php if ($po['reference_number']): ?>
                        <strong>Reference:</strong> <?= $po['reference_number'] ?><br>
                    <?php endif; ?>
                    <strong>Status:</strong> <?= ucfirst($po['status']) ?><br>
                    <strong>Printed On:</strong> <?= date('d-m-Y h:i A') ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="supplier-section">
        <h3>Supplier Details</h3>
        <div class="supplier-box">
            <strong><?= $po['supplier_name'] ?></strong><br>
            <?php if ($po['address1']): ?>
                <?= $po['address1'] ?><br>
            <?php endif; ?>
            <?php if ($po['phone']): ?>
                Phone: <?= $po['phone'] ?><br>
            <?php endif; ?>
            <?php if ($po['email_id']): ?>
                Email: <?= $po['email_id'] ?>
            <?php endif; ?>
        </div>
    </div>
    
    <h3>Order Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">S.No</th>
                <th width="10%">Item Code</th>
                <th width="25%">Item Name</th>
                <th width="20%">Description</th>
                <th width="8%" class="text-center">Qty</th>
                <th width="10%" class="text-right">Unit Price</th>
                <th width="7%" class="text-center">Tax %</th>
                <th width="7%" class="text-center">Disc %</th>
                <th width="8%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1;
            foreach ($po_items as $item): 
                $line_total = $item['quantity'] * $item['unit_price'];
                $item_total = $line_total + $item['tax_amount'] - $item['discount_amount'];
            ?>
            <tr>
                <td class="text-center"><?= $sno++ ?></td>
                <td><?= $item['item_code'] ?></td>
                <td><?= $item['item_name'] ?></td>
                <td><?= $item['description'] ?></td>
                <td class="text-center"><?= number_format($item['quantity'], 3) ?></td>
                <td class="text-right"><?= number_format($item['unit_price'], 2) ?></td>
                <td class="text-center"><?= number_format($item['tax_rate'], 2) ?></td>
                <td class="text-center"><?= number_format($item['discount_rate'], 2) ?></td>
                <td class="text-right"><?= number_format($item_total, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right"><?= number_format($po['subtotal'], 2) ?></td>
            </tr>
            <?php if ($po['tax_amount'] > 0): ?>
            <tr>
                <td><strong>Tax:</strong></td>
                <td class="text-right"><?= number_format($po['tax_amount'], 2) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($po['discount_amount'] > 0): ?>
            <tr>
                <td><strong>Discount:</strong></td>
                <td class="text-right"><?= number_format($po['discount_amount'], 2) ?></td>
            </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td><strong>Total Amount:</strong></td>
                <td class="text-right"><?= number_format($po['total_amount'], 2) ?></td>
            </tr>
        </table>
    </div>
    
    <?php if ($po['terms_conditions']): ?>
    <div class="terms-section">
        <h4>Terms & Conditions</h4>
        <p><?= nl2br($po['terms_conditions']) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if ($po['notes']): ?>
    <div class="terms-section">
        <h4>Notes</h4>
        <p><?= nl2br($po['notes']) ?></p>
    </div>
    <?php endif; ?>
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                <strong>Prepared By</strong><br>
                <?php 
                // You can fetch the creator's name from user table if needed
                echo "Purchase Department";
                ?>
            </div>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                <strong>Authorized Signatory</strong><br>
                For <?= $_SESSION['site_title'] ?>
            </div>
        </div>
    </div>
    
    <div style="clear: both;"></div>
    
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>