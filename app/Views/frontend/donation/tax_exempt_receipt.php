<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tax Exempt Receipt</title>
    <style>
        @page {
            size: A5;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin-top: 30px;
            padding: 0;
        }

        .receipt-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
        }

        .temple-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #000;
        }

        .address {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .receipt-subtitle {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .receipt-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-weight: bold;
            color: red;
            font-size: 14px;
        }

        .date-field {
            text-align: right;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .content-section {
            margin-bottom: 20px;
        }

        .field-row {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .field-label {
            width: 120px;
            font-size: 11px;
        }

        .field-value {
            flex: 1;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            font-weight: bold;
        }

        .amount-section {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }

        .amount-number {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .amount-words {
            font-size: 11px;
            text-align: center;
            font-style: italic;
        }

        .purpose-section {
            margin: 15px 0;
        }

        .tax-info {
            background-color: #e6f3ff;
            padding: 8px;
            margin: 15px 0;
            border: 1px solid #0066cc;
            font-size: 10px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-section {
            text-align: center;
            width: 150px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 40px;
            margin-bottom: 5px;
        }

        .blessing {
            font-style: italic;
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 11px;
        }

        .note {
            font-size: 9px;
            text-align: center;
            margin-top: 15px;
            font-style: italic;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="receipt-container">
       <div class="receipt-number">No: <?php echo $admin_profile['tax_no']; ?></div>
        <!-- <div class="receipt-number">No: <?php echo $receipt_no; ?></div> -->

        <div class="header">
            <div class="temple-name">
                TABUNG PENGURUSAN RUMAH IBADAT<br>
                KUIL SRI KANDASWAMY, KUALA LUMPUR
            </div>
            <div class="address">
                No 3, Lorong Scott, Off Jalan Sambanthan, 50470 Kuala Lumpur.<br>
                Tel No: 03-2274 2987<br>
                Website: www.srikandaswamykovil.org &nbsp; e-mail: enquiries@srikandaswamykovil.org
            </div>
        </div>

        <div class="receipt-title">TPRI - RESIT PENGECUALIAN CUKAI</div>
        <div class="receipt-subtitle">TPRI - TAX EXEMPT RECEIPT</div>

        <div class="date-field">
            Tarikh/Date: <strong><?php echo date('d.m.Y', strtotime($donation['date'])); ?></strong>
        </div>

        <div class="content-section">
            <div class="field-row">
                <?php
                // Display tax receipt number if available, otherwise show regular receipt number
                $receipt_no = !empty($donation['tax_receipt_no']) ? $donation['tax_receipt_no'] : $donation['ref_no'];
                ?>
                <span class="field-label">Tax Receipt No:<br>No. Resit Cukai:</span>
                <span class="field-value"><?php echo $receipt_no; ?></span>
            </div>

            <div class="field-row">
                <span class="field-label">Diterima daripada<br>Received from:</span>
                <span class="field-value"><?php echo strtoupper($donation['name']); ?></span>
            </div>

            <div class="field-row">
                <span class="field-label">No. Kad Pengenalan/No. Syarikat:<br>NRIC/Co Reg No:</span>
                <span class="field-value"><?php echo strtoupper($donation['ic_number'] ?: 'N/A'); ?></span>
            </div>

            <div class="field-row">
                <span class="field-label">Alamat:<br>Address:</span>
                <span class="field-value"><?php echo strtoupper($donation['address'] ?: 'N/A'); ?></span>
            </div>
        </div>

        <div class="amount-section">
            <div class="amount-number">
                Ringgit Malaysia: RM <?php echo number_format($donation['amount'], 2); ?>
            </div>
            <div class="amount-words">
                <?php echo $amount_in_words; ?>
            </div>
        </div>

        <div class="purpose-section">
            <div class="field-row">
                <span class="field-label">Derma untuk<br>Being donation for:</span>
                <span
                    class="field-value"><?php echo strtoupper($donation['donation_name'] ? $donation['donation_name'] : 'GENERAL DONATION'); ?></span>
            </div>
        </div>

        <div class="tax-info">
            <strong>Potongan di bawah Subseksyen 44(6) Akta Cukai Pendapatan 1967;</strong><br>
            <strong>No. Rujukan: LHDN 01/35/42/51/179-6.8493 Tempoh kuatkuasa/urusan: 15.09.2024 – 31.12.2028</strong>
        </div>

        <div class="blessing">
            May Lord Muruga shower His Grace & Blessing onto you and your family.
        </div>

        <div class="footer">
            <div class="signature-section">
                <div class="signature-line"></div>
                <div>Tunai / No Cek<br>Cash / Cheque No</div>
            </div>

            <div class="signature-section">
                <div class="signature-line"></div>
                <div>Bendahari Kehormat<br>Honorary Treasurer</div>
            </div>
        </div>

        <div class="note">
            Sila menyimpan resit ini untuk kemukakan ke LHDN<br>
            Please retain this tax exempt receipt for submission to the tax authorities.
        </div>
    </div>
</body>

</html>