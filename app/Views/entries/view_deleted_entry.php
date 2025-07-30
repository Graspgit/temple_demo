<!-- File: app/Views/entries/view_deleted_entry.php -->

<style>
    .detail-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px 8px 0 0;
    }
    .info-row {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .audit-info {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 15px;
        margin: 15px 0;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2><i class="fa fa-eye"></i> Deleted Entry Details</h2>
                                <small>Viewing archived entry information</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?= base_url('/entries/deleted_entries_report') ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Back to Report
                                </a>
                                <a href="<?= base_url('/entries/print_deleted_entry/' . $deleted_entry['id']) ?>" 
                                   class="btn btn-info" target="_blank">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Entry Information -->
            <div class="col-md-8">
                <div class="detail-card">
                    <div class="detail-header">
                        <h4><i class="fa fa-file-text"></i> Entry Information</h4>
                        <p class="mb-0">Original Entry Code: <strong><?= esc($deleted_entry['entry_code']) ?></strong></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>Entry Type:</strong>
                                    <span class="badge badge-primary ml-2"><?= esc($deleted_entry['entry_type_name']) ?></span>
                                </div>
                                <div class="info-row">
                                    <strong>Original Entry ID:</strong> <?= $deleted_entry['original_entry_id'] ?>
                                </div>
                                <div class="info-row">
                                    <strong>Entry Date:</strong> <?= date('d-m-Y', strtotime($deleted_entry['entry_date'])) ?>
                                </div>
                                <div class="info-row">
                                    <strong>Payment Mode:</strong> <?= esc($deleted_entry['payment_mode']) ?: 'N/A' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>Debit Total:</strong> 
                                    <span class="text-success"><?= number_format($deleted_entry['dr_total'], 2) ?></span>
                                </div>
                                <div class="info-row">
                                    <strong>Credit Total:</strong> 
                                    <span class="text-danger"><?= number_format($deleted_entry['cr_total'], 2) ?></span>
                                </div>
                                <div class="info-row">
                                    <strong>Status:</strong>
                                    <?php if($deleted_entry['status'] == 'deleted'): ?>
                                        <span class="badge badge-danger">Deleted</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Restored</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <strong>Narration:</strong><br>
                            <p class="mt-2 p-2 bg-light rounded"><?= esc($deleted_entry['narration']) ?: 'No narration provided' ?></p>
                        </div>
                    </div>
                </div>

                <!-- Entry Items -->
                <div class="detail-card mt-4">
                    <div class="detail-header">
                        <h4><i class="fa fa-list"></i> Entry Items</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Ledger ID</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th>Dr/Cr</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($deleted_entry['entry_items_data_decoded'] as $item): ?>
                                        <tr>
                                            <td><?= $item['ledger_id'] ?></td>
                                            <td><?= esc($item['details']) ?: 'N/A' ?></td>
                                            <td class="text-right"><?= number_format($item['amount'], 2) ?></td>
                                            <td>
                                                <span class="badge <?= $item['dc'] == 'D' ? 'badge-success' : 'badge-danger' ?>">
                                                    <?= $item['dc'] == 'D' ? 'Debit' : 'Credit' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Information -->
            <div class="col-md-4">
                <div class="detail-card">
                    <div class="detail-header">
                        <h4><i class="fa fa-shield"></i> Audit Trail</h4>
                    </div>
                    <div class="card-body">
                        <div class="audit-info">
                            <h6><i class="fa fa-trash text-danger"></i> Deletion Information</h6>
                            <div class="info-row">
                                <strong>Deleted By:</strong> <?= esc($deleted_entry['deleted_by_name']) ?>
                            </div>
                            <div class="info-row">
                                <strong>Username:</strong> <?= esc($deleted_entry['deleted_by_username']) ?>
                            </div>
                            <div class="info-row">
                                <strong>User ID:</strong> <?= $deleted_entry['deleted_by'] ?>
                            </div>
                            <div class="info-row">
                                <strong>Deleted At:</strong> 
                                <?= date('d-m-Y H:i:s', strtotime($deleted_entry['deleted_at'])) ?>
                            </div>
                            <div class="info-row">
                                <strong>IP Address:</strong> <?= esc($deleted_entry['ip_address']) ?>
                            </div>
                            <div class="info-row">
                                <strong>Session ID:</strong> 
                                <small class="text-muted"><?= esc(substr($deleted_entry['session_id'], 0, 20)) ?>...</small>
                            </div>
                        </div>

                        <div class="audit-info">
                            <h6><i class="fa fa-comment text-warning"></i> Deletion Reason</h6>
                            <p class="mb-0"><?= esc($deleted_entry['deleted_reason']) ?: 'No reason provided' ?></p>
                        </div>

                        <?php if($deleted_entry['status'] == 'restored'): ?>
                            <div class="audit-info">
                                <h6><i class="fa fa-undo text-success"></i> Restoration Information</h6>
                                <div class="info-row">
                                    <strong>Restored At:</strong> 
                                    <?= date('d-m-Y H:i:s', strtotime($deleted_entry['restored_at'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="audit-info">
                            <h6><i class="fa fa-info-circle text-info"></i> Technical Details</h6>
                            <div class="info-row">
                                <strong>User Agent:</strong><br>
                                <small class="text-muted"><?= esc(substr($deleted_entry['user_agent'], 0, 50)) ?>...</small>
                            </div>
                            <div class="info-row">
                                <strong>Archive Date:</strong> 
                                <?= date('d-m-Y H:i:s', strtotime($deleted_entry['created_at'])) ?>
                            </div>
                        </div>

                        <?php if($deleted_entry['status'] == 'deleted'): ?>
                            <div class="mt-3">
                                <button class="btn btn-success btn-block" 
                                        onclick="restoreEntry(<?= $deleted_entry['id'] ?>)">
                                    <i class="fa fa-undo"></i> Restore Entry
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Raw Data (for debugging) -->
                <div class="detail-card mt-3">
                    <div class="card-header">
                        <h6><i class="fa fa-code"></i> Raw Data</h6>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-outline-primary" onclick="toggleRawData()">
                            <i class="fa fa-eye"></i> View JSON Data
                        </button>
                        <div id="raw-data" style="display: none;" class="mt-3">
                            <h6>Entry Data:</h6>
                            <pre class="bg-light p-2 small"><?= esc(json_encode($deleted_entry['entry_data_decoded'], JSON_PRETTY_PRINT)) ?></pre>
                            <h6>Entry Items Data:</h6>
                            <pre class="bg-light p-2 small"><?= esc(json_encode($deleted_entry['entry_items_data_decoded'], JSON_PRETTY_PRINT)) ?></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function restoreEntry(deletedEntryId) {
    if(confirm('Are you sure you want to restore this entry? This will make it active again in the system.')) {
        window.location.href = '<?= base_url('/entries/restore_entry/') ?>' + deletedEntryId;
    }
}

function toggleRawData() {
    const rawData = document.getElementById('raw-data');
    if (rawData.style.display === 'none') {
        rawData.style.display = 'block';
    } else {
        rawData.style.display = 'none';
    }
}
</script>