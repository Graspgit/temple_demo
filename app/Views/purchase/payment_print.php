<!DOCTYPE html>
<html>
<head>
    <title>Payment Voucher - <?= $payment['payment_number'] ?></title>
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
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
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
        .amount-box {
            border: 2px solid #333;
            padding: 10px;
            margin: 20px 0;
            font-size: 16px;
            font-weight: bold;
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
        <h2>PAYMENT VOUCHER</h2>
        <h3><?= $payment['payment_number'] ?></h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="70%">
                <strong>Paid To:</strong><br>
                <strong><?= $payment['supplier_name'] ?></strong><br>
                <?= nl2br($payment['address1']) ?><br>
            </td>
            <td width="30%">
                <strong>Date:</strong> <?= date('d-m-Y', strtotime($payment['payment_date'])) ?><br>
                <strong>Mode:</strong> <?= $payment['payment_mode_name'] ?? 'Cash' ?><br>
                <?php if ($payment['reference_number']): ?>
                    <strong>Ref No:</strong> <?= $payment['reference_number'] ?><br>
                <?php endif; ?>
                <?php if ($payment['cheque_number']): ?>
                    <strong>Cheque No:</strong> <?= $payment['cheque_number'] ?><br>
                    <strong>Cheque Date:</strong> <?= date('d-m-Y', strtotime($payment['cheque_date'])) ?><br>
                <?php endif; ?>
                <?php if ($payment['bank_name']): ?>
                    <strong>Bank:</strong> <?= $payment['bank_name'] ?><br>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="amount-box">
        <table width="100%">
            <tr>
                <td width="30%"><strong>AMOUNT PAID:</strong></td>
                <td width="70%"><strong>₹ <?= number_format($payment['amount'], 2) ?></strong></td>
            </tr>
            <tr>
                <td><strong>IN WORDS:</strong></td>
                <td><?= ucwords(numberToWords($payment['amount'])) ?> Only</td>
            </tr>
        </table>
    </div>

    <h4>Invoice Details:</h4>
    <table class="details-table">
        <thead>
            <tr>
                <th width="10%">S.No</th>
                <th width="30%">Invoice Number</th>
                <th width="20%">Invoice Date</th>
                <th width="20%">Invoice Amount</th>
                <th width="20%">Paid Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1; foreach ($allocations as $allocation): ?>
            <tr>
                <td class="text-center"><?= $sno++ ?></td>
                <td><?= $allocation['invoice_number'] ?></td>
                <td class="text-center"><?= date('d-m-Y', strtotime($allocation['invoice_date'])) ?></td>
                <td class="text-right">-</td>
                <td class="text-right">₹ <?= number_format($allocation['allocated_amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL:</th>
                <th class="text-right">₹ <?= number_format($payment['amount'], 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <?php if ($payment['notes']): ?>
    <div style="margin-top: 20px;">
        <strong>Narration:</strong><br>
        <?= nl2br($payment['notes']) ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="25%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Prepared By
                    </div>
                </td>
                <td width="25%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Checked By
                    </div>
                </td>
                <td width="25%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Approved By
                    </div>
                </td>
                <td width="25%" class="text-center">
                    <div class="signature-box">
                        <br><br><br>
                        _______________________<br>
                        Receiver's Signature
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
// Helper function to convert number to words
function numberToWords($number) {
    $number = number_format($number, 2, '.', '');
    $whole = floor($number);
    $fraction = round(($number - $whole) * 100);
    
    $ones = array(
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
        5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
        14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
        18 => 'eighteen', 19 => 'nineteen'
    );
    
    $tens = array(
        2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
        6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
    );
    
    $hundreds = array(
        'hundred', 'thousand', 'lakh', 'crore'
    );
    
    $result = '';
    
    // Convert crores
    if ($whole >= 10000000) {
        $crores = floor($whole / 10000000);
        $result .= convertTwoDigit($crores, $ones, $tens) . ' crore ';
        $whole = $whole % 10000000;
    }
    
    // Convert lakhs
    if ($whole >= 100000) {
        $lakhs = floor($whole / 100000);
        $result .= convertTwoDigit($lakhs, $ones, $tens) . ' lakh ';
        $whole = $whole % 100000;
    }
    
    // Convert thousands
    if ($whole >= 1000) {
        $thousands = floor($whole / 1000);
        $result .= convertTwoDigit($thousands, $ones, $tens) . ' thousand ';
        $whole = $whole % 1000;
    }
    
    // Convert hundreds
    if ($whole >= 100) {
        $hundreds = floor($whole / 100);
        $result .= $ones[$hundreds] . ' hundred ';
        $whole = $whole % 100;
    }
    
    // Convert remaining
    if ($whole > 0) {
        $result .= convertTwoDigit($whole, $ones, $tens) . ' ';
    }
    
    $result .= 'rupees';
    
    // Add paise if exists
    if ($fraction > 0) {
        $result .= ' and ' . convertTwoDigit($fraction, $ones, $tens) . ' paise';
    }
    
    return $result;
}

function convertTwoDigit($number, $ones, $tens) {
    if ($number < 20) {
        return $ones[$number];
    } else {
        $ten = floor($number / 10);
        $one = $number % 10;
        return $tens[$ten] . ($one > 0 ? ' ' . $ones[$one] : '');
    }
}
?>