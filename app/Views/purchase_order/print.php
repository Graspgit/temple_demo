<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales Order Print' : 'Purchase Order Print') ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .print-break { page-break-before: always; }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .company-logo {
            flex: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        
        .document-title {
            text-align: center;
            flex: 1;
            margin: 0 20px;
        }
        
        .document-title h1 {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .document-subtitle {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .po-info {
            text-align: right;
            flex: 1;
        }
        
        .po-number {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }
        
        .po-date {
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .parties-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }
        
        .party-box {
            flex: 1;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .party-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .party-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .party-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        
        .items-section {
            margin: 30px 0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-left: 4px solid #667eea;
            padding-left: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #2c3e50;
        }
        
        .items-table thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .items-table th {
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        .items-table th:last-child {
            border-right: none;
        }
        
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ecf0f1;
            border-right: 1px solid #ecf0f1;
            font-size: 11px;
            vertical-align: top;
        }
        
        .items-table td:last-child {
            border-right: none;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .totals-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-table {
            width: 350px;
            border-collapse: collapse;
            border: 2px solid #2c3e50;
        }
        
        .totals-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 12px;
        }
        
        .totals-table .label {
            background: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
            width: 60%;
            border-right: 1px solid #ecf0f1;
        }
        
        .totals-table .amount {
            text-align: right;
            font-weight: bold;
            color: #27ae60;
        }
        
        .grand-total {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            color: white !important;
        }
        
        .grand-total .label,
        .grand-total .amount {
            background: transparent !important;
            color: white !important;
            font-size: 14px;
            font-weight: bold;
        }
        
        .remarks-section {
            margin-top: 30px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .remarks-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remarks-content {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
            min-height: 60px;
        }
        
        .approval-section {
            margin-top: 40px;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 25px;
        }
        
        .approval-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 25px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .approval-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .approval-box {
            text-align: center;
        }
        
        .approval-box-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .signature-line {
            border-bottom: 2px solid #2c3e50;
            height: 50px;
            margin-bottom: 10px;
            position: relative;
        }
        
        .signature-label {
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }
        
        @media (max-width: 768px) {
            .print-container {
                width: 100%;
                padding: 15mm;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .parties-section {
                flex-direction: column;
                gap: 20px;
            }
            
            .approval-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .items-table {
                font-size: 10px;
            }
            
            .items-table th,
            .items-table td {
                padding: 6px 4px;
            }
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: -1;
            text-transform: uppercase;
        }

        .enhanced-info-box {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .enhanced-info-box strong {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="no-print print-controls">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="material-icons">print</i> Print
        </button>
        <a href="<?php echo base_url(); ?>/purchase_order/generate-pdf/<?php echo isset($purchaseOrder['id']) ? $purchaseOrder['id'] : '1'; ?>" class="btn btn-success">
            <i class="material-icons">picture_as_pdf</i> Download PDF
        </a>
        <a href="<?php echo base_url(); ?>/purchase_order/<?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'index' : 'purchase_order_purchase'); ?>" class="btn btn-secondary">
            <i class="material-icons">arrow_back</i> Back to List
        </a>
    </div>
    
    <div class="print-container">
        <!-- Watermark -->
        <div class="watermark"><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'SALES ORDER' : 'PURCHASE ORDER'); ?></div>
        
        <!-- Header Section -->
        <div class="header">
            <div class="company-logo">
                <div class="company-name"><?= esc($companySettings['company_name'] ?? 'Your Company Name') ?></div>
                <?php if (!empty($companySettings['company_name_tamil'])): ?>
                    <div style="font-size: 14px; margin-bottom: 8px; color: #7f8c8d;"><?= esc($companySettings['company_name_tamil']) ?></div>
                <?php endif; ?>
                <div class="company-details">
                    <?php if (!empty($companySettings['address1'])): ?>
                        <?= esc($companySettings['address1']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($companySettings['address2'])): ?>
                        <?= esc($companySettings['address2']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($companySettings['city'])): ?>
                        <?= esc($companySettings['city']) ?>
                        <?php if (!empty($companySettings['postcode'])): ?>
                            <?= esc($companySettings['postcode']) ?>
                        <?php endif; ?>
                        <br>
                    <?php endif; ?>
                    <?php if (!empty($companySettings['telephone']) || !empty($companySettings['mobile'])): ?>
                        <strong>Tel:</strong> 
                        <?= esc($companySettings['telephone'] ?? $companySettings['mobile'] ?? '+60-3-1234-5678') ?><br>
                    <?php endif; ?>
                    <?php if (!empty($companySettings['email'])): ?>
                        <strong>Email:</strong> <?= esc($companySettings['email']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($companySettings['gstno'])): ?>
                        <strong>GST No:</strong> <?= esc($companySettings['gstno']) ?>
                    <?php else: ?>
                        <strong>GST No:</strong> 123456789012
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="document-title">
                <h1><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales Order' : 'Purchase Order'); ?></h1>
                <div class="document-subtitle">Official <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales' : 'Purchase'); ?> Request</div>
            </div>
            
            <div class="po-info">
                <div class="po-number"><?= esc($purchaseOrder['po_no'] ?? 'PO-00001') ?></div>
                <div class="po-date">Date: <?= date('d-m-Y', strtotime($purchaseOrder['date'] ?? date('Y-m-d'))) ?></div>
                <div class="status-badge <?= ($purchaseOrder['is_approved'] ?? 0) == 1 ? 'status-approved' : 'status-pending' ?>">
                    <?= ($purchaseOrder['is_approved'] ?? 0) == 1 ? 'Approved' : 'Pending Approval' ?>
                </div>
            </div>
        </div>

        <!-- Parties Section -->
        <div class="parties-section">
            <div class="party-box">
                <div class="party-title">
                    <i class="material-icons" style="font-size: 16px;">business</i> 
                    <?= ($entityType ?? 'supplier') == 'supplier' ? 'Supplier Details' : 'Customer Details' ?>
                </div>
                <div class="party-name"><?= esc($supplierCustomer[($entityType ?? 'supplier') . '_name'] ?? 'ABC Supplies Sdn Bhd') ?></div>
                <div class="party-details">
                    <?php if (!empty($supplierCustomer['address1'])): ?>
                        <?= esc($supplierCustomer['address1']) ?><br>
                    <?php else: ?>
                        456 Industrial Road<br>
                    <?php endif; ?>
                    <?php if (!empty($supplierCustomer['address2'])): ?>
                        <?= esc($supplierCustomer['address2']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($supplierCustomer['city'])): ?>
                        <?= esc($supplierCustomer['city']) ?>
                        <?php if (!empty($supplierCustomer['state'])): ?>
                            , <?= esc($supplierCustomer['state']) ?>
                        <?php endif; ?>
                        <?php if (!empty($supplierCustomer['zipcode'])): ?>
                            <?= esc($supplierCustomer['zipcode']) ?>
                        <?php endif; ?>
                        <br>
                    <?php else: ?>
                        Shah Alam, Selangor 40150<br>
                    <?php endif; ?>
                    <?php if (!empty($supplierCustomer['country'])): ?>
                        <?= esc($supplierCustomer['country']) ?><br>
                    <?php else: ?>
                        Malaysia<br>
                    <?php endif; ?>
                    <br>
                    <strong>Contact:</strong> <?= esc($supplierCustomer['contact_person'] ?? $supplierCustomer['contact'] ?? 'Mr. Ahmad Rahman') ?><br>
                    <strong>Phone:</strong> <?= esc($supplierCustomer['phone'] ?? $supplierCustomer['mobile_no'] ?? '+60-3-9876-5432') ?><br>
                    <?php if (!empty($supplierCustomer['email_id'])): ?>
                        <strong>Email:</strong> <?= esc($supplierCustomer['email_id']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($supplierCustomer['vat_no'])): ?>
                        <strong>GST No:</strong> <?= esc($supplierCustomer['vat_no']) ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="party-box">
                <div class="party-title">
                    <i class="material-icons" style="font-size: 16px;">info</i> Order Information
                </div>
                <div class="enhanced-info-box">
                    <strong><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales' : 'Purchase'); ?> Order No:</strong> <?= esc($purchaseOrder['po_no'] ?? 'PO-00001') ?><br>
                    <strong>Date:</strong> <?= date('jS F Y', strtotime($purchaseOrder['date'] ?? date('Y-m-d'))) ?><br>
                    <strong>Delivery Date:</strong> <?= date('jS F Y', strtotime('+14 days', strtotime($purchaseOrder['date'] ?? date('Y-m-d')))) ?><br>
                    <strong>Payment Terms:</strong> Net 30 Days<br>
                    <strong>Delivery Terms:</strong> FOB Destination
                </div>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <strong>Prepared By:</strong> <?= esc($purchaseOrder['created_name'] ?? 'System User') ?><br>
                    <strong>Department:</strong> <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales' : 'Procurement'); ?><br>
                    <strong>Reference:</strong> <?= esc($purchaseOrder['po_no'] ?? 'REQ-2025-001') ?>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items-section">
            <h3 class="section-title">
                <i class="material-icons" style="font-size: 18px;">inventory_2</i> Order Details
            </h3>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="35%">Description / Specification</th>
                        <th width="8%">Type</th>
                        <th width="8%">Qty</th>
                        <th width="8%">Unit</th>
                        <th width="12%">Unit Price (RM)</th>
                        <th width="8%">Tax (%)</th>
                        <th width="16%">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orderDetails)): ?>
                        <?php foreach ($orderDetails as $index => $item): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td class="text-left">
                                <strong><?= esc($item['description']) ?></strong>
                            </td>
                            <td class="text-center"><?= ($item['type'] ?? 2) == 1 ? 'Service' : 'Product' ?></td>
                            <td class="text-center"><?= esc($item['qty'] ?? 1) ?></td>
                            <td class="text-center">Units</td>
                            <td class="text-right"><?= number_format($item['rate'] ?? 0, 2) ?></td>
                            <td class="text-center"><?= number_format($item['tax'] ?? 0, 2) ?>%</td>
                            <td class="text-right"><?= number_format($item['amount'] ?? 0, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Sample data if no items -->
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-left">
                                <strong>Office Chair - Ergonomic Design</strong><br>
                                <small>Model: EC-2025, Black Color with lumbar support and adjustable height mechanism</small>
                            </td>
                            <td class="text-center">Product</td>
                            <td class="text-center">10</td>
                            <td class="text-center">Units</td>
                            <td class="text-right">450.00</td>
                            <td class="text-center">6%</td>
                            <td class="text-right">4,770.00</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="text-left">
                                <strong>Desktop Computer Setup</strong><br>
                                <small>Intel i7, 16GB RAM, 512GB SSD, 24" Monitor included</small>
                            </td>
                            <td class="text-center">Product</td>
                            <td class="text-center">5</td>
                            <td class="text-center">Sets</td>
                            <td class="text-right">2,800.00</td>
                            <td class="text-center">6%</td>
                            <td class="text-right">14,840.00</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">RM <?= number_format($purchaseOrder['total'] ?? 18500, 2) ?></td>
                </tr>
                <?php if (($purchaseOrder['discount'] ?? 0) > 0): ?>
                <tr>
                    <td class="label">Discount:</td>
                    <td class="amount">RM <?= number_format($purchaseOrder['discount'] ?? 0, 2) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td class="label">TOTAL AMOUNT:</td>
                    <td class="amount">RM <?= number_format($purchaseOrder['grand_total'] ?? 18500, 2) ?></td>
                </tr>
            </table>
        </div>

        <!-- Remarks Section -->
        <div class="remarks-section">
            <div class="remarks-title">
                <i class="material-icons" style="font-size: 16px;">comment</i> Special Instructions & Remarks
            </div>
            <div class="remarks-content">
                <?= esc($purchaseOrder['remarks'] ?? 
                    '1. All items must be delivered to our main office at the address shown above.<br>
                    2. Delivery must be completed within 14 working days.<br>
                    3. All equipment must come with manufacturer warranty (minimum 1 year).<br>
                    4. Invoice and delivery order must be submitted for payment processing.') ?>
            </div>
        </div>

        <!-- Approval Section -->
        <div class="approval-section">
            <div class="approval-title">
                <i class="material-icons" style="font-size: 18px;">verified</i> Authorization & Approval
            </div>
            
            <div class="approval-grid">
                <div class="approval-box">
                    <div class="approval-box-title">Requested By</div>
                    <div class="signature-line"></div>
                    <div class="signature-label"><?= esc($purchaseOrder['created_name'] ?? 'System User') ?><br><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Sales' : 'Procurement'); ?> Officer<br>Date: _______________</div>
                </div>
                
                <div class="approval-box">
                    <div class="approval-box-title">Approved By</div>
                    <div class="signature-line"></div>
                    <div class="signature-label">Manager Signature<br>Department Head<br>Date: _______________</div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
                <strong><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Customer' : 'Supplier'); ?> Acknowledgment:</strong><br>
                I acknowledge receipt of this <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'sales' : 'purchase'); ?> order and agree to <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'deliver' : 'supply'); ?> the items as specified above.
                <br><br>
                <div style="display: inline-block; border-bottom: 2px solid #2c3e50; width: 300px; height: 40px;"></div><br>
                <small><?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'Customer' : 'Supplier'); ?> Signature & Company Stamp</small>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terms & Conditions:</strong></p>
            <p>1. This <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'sales' : 'purchase'); ?> order is subject to our standard terms and conditions of <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'sale' : 'purchase'); ?>.</p>
            <p>2. Payment will be made within 30 days of <?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'delivery' : 'receipt'); ?> of goods and valid invoice.</p>
            <p>3. Any changes to this order must be authorized in writing.</p>
            <br>
            <p><em>This is a computer-generated document and does not require a signature when printed.</em></p>
            <p><strong>Generated on:</strong> <?= date('d-m-Y H:i:s') ?> | <strong>Page 1 of 1</strong></p>
        </div>
    </div>

    <script>
        // Auto-print functionality
        function autoPrint() {
            if (window.location.search.includes('auto_print=true')) {
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            autoPrint();
        });

        // Print function
        function printDocument() {
            window.print();
        }

        // Back to previous page
        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = '<?= base_url() ?>/purchase_order/<?php echo (($purchaseOrder['invoice_type'] ?? 2) == 1 ? 'index' : 'purchase_order_purchase'); ?>';
            }
        }
    </script>
</body>
</html>