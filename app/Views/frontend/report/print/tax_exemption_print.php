<!DOCTYPE html>
<html>

<head>
    <title>Tax Exemption Receipt</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        
        .header h2 {
            margin: 10px 0;
            font-size: 20px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .tax-info {
            background: #f0f0f0;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #ccc;
            text-align: center;
            font-weight: bold;
        }
        
        .receipt-details {
            margin: 30px 0;
        }
        
        .detail-row {
            display: flex;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .detail-label {
            flex: 0 0 200px;
            font-weight: bold;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .amount-section {
            background: #f9f9f9;
            padding: 20px;
            margin: 30px 0;
            border: 2px solid #333;
            text-align: center;
        }
        
        .amount-section h3 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #000;
        }
        
        .amount-words {
            font-style: italic;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .declaration {
            margin: 30px 0;
            padding: 20px;
            background: #fffbe0;
            border: 1px solid #e0d800;
        }
        
        .declaration h4 {
            margin-top: 0;
            color: #333;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        .print-btn {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background: #45a049;
        }
        
        .tax-exempt-stamp {
            position: absolute;
            top: 20px;
            right: 20px;
            color: red;
            font-size: 18px;
            font-weight: bold;
            transform: rotate(-15deg);
            border: 3px solid red;
            padding: 10px;
            background: rgba(255,255,255,0.9);
        }
    </style>
</head>
<body>
    <div class="print-btn no-print">
        <button class="btn" onclick="window.print()">Print Receipt</button>
        <button class="btn" onclick="window.close()">Close</button>
    </div>

    <div class="receipt-container">
        <div class="tax-exempt-stamp">TAX EXEMPT</div>
        
        <div class="header">
            <h1><?php echo $donation['temple_name']; ?></h1>
            <p><?php echo $donation['temple_address']; ?></p>
            <h2>TAX EXEMPTION RECEIPT</h2>
            <p>Under Section 44(6) of Income Tax Act 1967</p>
        </div>

        <div class="tax-info">
            <p>TAX REFERENCE NO: <?php echo $donation['tax_no']; ?></p>
        </div>

        <div class="receipt-details">
            <div class="detail-row">
                <div class="detail-label">Receipt No:</div>
                <div class="detail-value"><strong><?php echo $donation['ref_no']; ?></strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value"><?php echo date('d F Y', strtotime($donation['date'])); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Received From:</div>
                <div class="detail-value"><strong><?php echo strtoupper($donation['name']); ?></strong></div>
            </div>
            <?php if ($donation['ic_number']): ?>
                <div class="detail-row">
                    <div class="detail-label">IC/Passport No:</div>
                    <div class="detail-value"><?php echo $donation['ic_number']; ?></div>
                </div>
            <?php endif; ?>
            <?php if ($donation['address']): ?>
                <div class="detail-row">
                    <div class="detail-label">Address:</div>
                    <div class="detail-value"><?php echo $donation['address']; ?></div>
                </div>
            <?php endif; ?>
            <?php if ($donation['mobile']): ?>
                <div class="detail-row">
                    <div class="detail-label">Contact No:</div>
                    <div class="detail-value"><?php echo $donation['mobile']; ?></div>
                </div>
            <?php endif; ?>
            <div class="detail-row">
                <div class="detail-label">Donation For:</div>
                <div class="detail-value"><?php echo $donation['donation_name']; ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value"><?php echo $donation['payment_method_name'] ?: 'Cash'; ?></div>
            </div>
        </div>

        <div class="amount-section">
            <h3>AMOUNT: RM <?php echo number_format($donation['amount'], 2); ?></h3>
            <div class="amount-words">
                (RINGGIT MALAYSIA: <?php echo $amount_in_words; ?>)
            </div>
        </div>

        <div class="declaration">
            <h4>DECLARATION</h4>
            <p>This is to certify that the above donation has been received and will be used for religious purposes only. This receipt is issued under Section 44(6) of the Income Tax Act 1967 and is eligible for tax exemption.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Prepared By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated receipt and is valid without signature if system-validated.</p>
            <p>Receipt generated on: <?php echo date('d-m-Y H:i:s'); ?></p>
            <p><strong>Important:</strong> Please keep this receipt for your income tax filing purposes.</p>
        </div>
    </div>
</body>
</html>