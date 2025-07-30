<!-- File: app/Views/entries/print_deleted_entry.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Deleted Entry - <?= esc($deleted_entry['entry_code']) ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .entry-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .entry-info td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .audit-section {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .deletion-warning {
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px;">
            Print Document
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>DELETED ENTRY RECORD</h1>
        <?php if(isset($temple_details)): ?>
            <div class="company-info">
                <h2><?= esc($temple_details['name']) ?? 'Company Name' ?></h2>
                <p><?= esc($temple_details['address']) ?? 'Company Address' ?></p>
                <p>Phone: <?= esc($temple_details['phone']) ?? 'N/A' ?> | Email: <?= esc($temple_details['email']) ?? 'N/A' ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Deletion Warning -->
    <div class="deletion-warning">
        ⚠️ THIS IS A DELETED ENTRY RECORD - FOR AUDIT PURPOSES ONLY ⚠️<br>
        This entry has been permanently removed from the active system
    </div>

    <!-- Entry Information -->
    <table class="entry-info">
        <tr>
            <td><strong>Entry Code:</strong></td>
            <td><?= esc($deleted_entry['entry_code']) ?></td>
            <td><strong>Entry Type:</strong></td>
            <td><?= esc($deleted_entry['entry_type_name']) ?></td>
        </tr>
        <tr>
            <td><strong>Original Entry ID:</strong></td>
            <td><?= $deleted_entry['original_entry_id'] ?></td>
            <td><strong>Entry Date:</strong></td>
            <td><?= date('d-m-Y', strtotime($deleted_entry['entry_date'])) ?></td>
        </tr>
        <tr>
            <td><strong>Payment Mode:</strong></td>
            <td><?= esc($deleted_entry['payment_mode']) ?: 'N/A' ?></td>
            <td><strong>Status:</strong></td>
            <td><?= strtoupper($deleted_entry['status']) ?></td>
        </tr>
        <tr>
            <td><strong>Debit Total:</strong></td>
            <td class="text-right"><?= number_format($deleted_entry['dr_total'], 2) ?></td>
            <td><strong>Credit Total:</strong></td>
            <td class="text-right"><?= number_format($deleted_entry['cr_total'], 2) ?></td>
        </tr>
    </table>

    <!-- Narration -->
    <?php if($deleted_entry['narration']): ?>
        <div style="margin: 20px 0;">
            <strong>Narration:</strong><br>
            <div style="border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                <?= esc($deleted_entry['narration']) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Entry Items -->
    <h3>Entry Items Details</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Ledger ID</th>
                <th>Details</th>
                <th>Debit Amount</th>
                <th>Credit Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sr_no = 1;
            $total_debit = 0;
            $total_credit = 0;
            foreach($deleted_entry['entry_items_data_decoded'] as $item): 
                if($item['dc'] == 'D') $total_debit += $item['amount'];
                else $total_credit += $item['amount'];
            ?>
                <tr>
                    <td class="text-center"><?= $sr_no++ ?></td>
                    <td><?= $item['ledger_id'] ?></td>
                    <td><?= esc($item['details']) ?: 'N/A' ?></td>
                    <td class="text-right"><?= $item['dc'] == 'D' ? number_format($item['amount'], 2) : '-' ?></td>
                    <td class="text-right"><?= $item['dc'] == 'C' ? number_format($item['amount'], 2) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong><?= number_format($total_debit, 2) ?></strong></td>
                <td class="text-right"><strong><?= number_format($total_credit, 2) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Audit Information -->
    <div class="audit-section">
        <h3>🔍 Audit Trail Information</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>Deleted By:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd;"><?= esc($deleted_entry['deleted_by_name']) ?> (<?= esc($deleted_entry['deleted_by_username']) ?>) - ID: <?= $deleted_entry['deleted_by'] ?></td>
            </tr>
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>Deletion Date & Time:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd;"><?= date('d-m-Y H:i:s', strtotime($deleted_entry['deleted_at'])) ?></td>
            </tr>
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>IP Address:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd;"><?= esc($deleted_entry['ip_address']) ?></td>
            </tr>
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>Session ID:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd; font-family: monospace; font-size: 12px;"><?= esc($deleted_entry['session_id']) ?></td>
            </tr>
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>Deletion Reason:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd;"><?= $deleted_entry['deleted_reason'] ?: 'No reason provided' ?></td>
            </tr>
            <tr>
                <td style="padding: 5px; border: 1px solid #ddd;"><strong>Archive Date:</strong></td>
                <td style="padding: 5px; border: 1px solid #ddd;"><?= date('d-m-Y H:i:s', strtotime($deleted_entry['created_at'])) ?></td>
            </tr>
        </table>

        <?php if($deleted_entry['status'] == 'restored'): ?>
            <div style="margin-top: 15px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
                <strong>✅ Restoration Information:</strong><br>
                Restored At: <?= date('d-m-Y H:i:s', strtotime($deleted_entry['restored_at'])) ?>
            </div>
        <?php endif; ?>