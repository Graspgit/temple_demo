<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Marriage Registration - <?= $booking['booking_no'] ?></title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            body {
                margin: 0;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: normal;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        
        .section {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
        }
        
        .couple-section {
            display: flex;
            gap: 20px;
        }
        
        .couple-info {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        
        .couple-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
            background: #f0f0f0;
            padding: 5px;
            border-radius: 3px;
        }
        
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .payment-table th,
        .payment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .payment-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        
        .payment-table .text-right {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            background: #f9f9f9;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .signature-section {
            display: flex;
            gap: 30px;
            margin-top: 50px;
        }
        
        .signature-box {
            flex: 1;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid { background: #4CAF50; color: white; }
        .status-partial { background: #FF9800; color: white; }
        .status-pending { background: #F44336; color: white; }
        
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close</button>
        </div>
        
        <div class="header">
            <?php if($_SESSION['logo_img']): ?>
                <img src="<?= base_url('uploads/main/'.$_SESSION['logo_img']) ?>" class="logo" alt="Logo">
            <?php endif; ?>
            <h1><?= $_SESSION['site_title'] ?></h1>
            <h2>Marriage Registration Certificate</h2>
            <p>Booking No: <strong><?= $booking['booking_no'] ?></strong></p>
        </div>
        
        <div class="section">
            <div class="section-title">EVENT DETAILS</div>
            <div class="info-row">
                <span class="info-label">Registration Date:</span>
                <span class="info-value"><?= date('l, d F Y', strtotime($booking['booking_date'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Time Slot:</span>
                <span class="info-value"><?= $booking['slot_name'] ?> (<?= date('h:i A', strtotime($booking['start_time'])) ?> - <?= date('h:i A', strtotime($booking['end_time'])) ?>)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Venue:</span>
                <span class="info-value"><?= $booking['venue_name'] ?> - <?= $booking['venue_type'] ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Booking Status:</span>
                <span class="info-value"><?= strtoupper($booking['booking_status']) ?></span>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">COUPLE INFORMATION</div>
            <div class="couple-section">
                <?php foreach($couple as $person): ?>
                <div class="couple-info">
                    <div class="couple-title"><?= strtoupper($person['person_type']) ?></div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value"><?= $person['name'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($person['dob'])) ?> (Age: <?= $person['age'] ?>)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nationality:</span>
                        <span class="info-value"><?= $person['nationality'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">IC/Passport:</span>
                        <span class="info-value"><?= $person['ic_passport_no'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?= $person['phone'] ?: '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Occupation:</span>
                        <span class="info-value"><?= $person['occupation'] ?: '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Father's Name:</span>
                        <span class="info-value"><?= $person['father_name'] ?: '-' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mother's Name:</span>
                        <span class="info-value"><?= $person['mother_name'] ?: '-' ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">PAYMENT INFORMATION</div>
            <table class="payment-table">
                <tr>
                    <td>Venue Charges</td>
                    <td class="text-right">RM <?= number_format($booking['total_amount'] - $booking['extra_charges'] - $booking['tax_amount'] + $booking['discount_amount'], 2) ?></td>
                </tr>
                <?php if($booking['extra_charges'] > 0): ?>
                <tr>
                    <td>Extra Charges</td>
                    <td class="text-right">RM <?= number_format($booking['extra_charges'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <?php if($booking['tax_amount'] > 0): ?>
                <tr>
                    <td>Tax</td>
                    <td class="text-right">RM <?= number_format($booking['tax_amount'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <?php if($booking['discount_amount'] > 0): ?>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">- RM <?= number_format($booking['discount_amount'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>Total Amount</td>
                    <td class="text-right">RM <?= number_format($booking['total_amount'], 2) ?></td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td class="text-right">RM <?= number_format($booking['paid_amount'], 2) ?></td>
                </tr>
                <tr>
                    <td>Balance Due</td>
                    <td class="text-right">RM <?= number_format($booking['total_amount'] - $booking['paid_amount'], 2) ?></td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td class="text-right">
                        <span class="status-badge status-<?= $booking['payment_status'] ?>">
                            <?= strtoupper($booking['payment_status']) ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php if($booking['remarks']): ?>
        <div class="section">
            <div class="section-title">REMARKS</div>
            <p><?= nl2br($booking['remarks']) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <p>Bride's Signature</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p>Groom's Signature</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p>Authorized Officer</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Printed on: <?= date('d F Y, h:i A') ?></p>
            <p><?= $_SESSION['site_title'] ?> | <?= $_SESSION['site_address'] ?? '' ?></p>
        </div>
    </div>
    
    <script>
        // Auto print on load if needed
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>