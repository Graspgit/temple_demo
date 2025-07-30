<!-- File: app/Views/entries/deleted_entries_report.php -->

<style>
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .entry-type-badge {
        font-size: 11px;
        padding: 4px 8px;
    }
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 8px;
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
                                <h2><i class="fa fa-trash"></i> Deleted Entries Report</h2>
                                <small>Track and manage deleted accounting entries</small>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?= base_url('/entries/list') ?>" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> Back to Entries
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3><?= count($deleted_entries) ?></h3>
                    <p>Total Deleted Entries</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3><?= count(array_filter($deleted_entries, function($e) { return $e['entry_type_id'] == 1; })) ?></h3>
                    <p>Deleted Receipts</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3><?= count(array_filter($deleted_entries, function($e) { return $e['entry_type_id'] == 2; })) ?></h3>
                    <p>Deleted Payments</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3><?= count(array_filter($deleted_entries, function($e) { return $e['entry_type_id'] == 4; })) ?></h3>
                    <p>Deleted Journals</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="filter-card">
                    <h5><i class="fa fa-filter"></i> Filters</h5>
                    <form method="GET" action="<?= base_url('/entries/deleted_entries_report') ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date Range</label>
                                <div class="input-group">
                                    <input type="date" name="start_date" class="form-control" 
                                           value="<?= esc($request->getGet('start_date')) ?>" placeholder="Start Date">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text">to</span>
                                    </div>
                                    <input type="date" name="end_date" class="form-control" 
                                           value="<?= esc($request->getGet('end_date')) ?>" placeholder="End Date">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Entry Type</label>
                                <select name="entry_type" class="form-control">
                                    <option value="">All Types</option>
                                    <?php foreach($entry_types as $type): ?>
                                        <option value="<?= $type['id'] ?>" 
                                                <?= $request->getGet('entry_type') == $type['id'] ? 'selected' : '' ?>>
                                            <?= esc($type['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Deleted By</label>
                                <select name="deleted_by" class="form-control">
                                    <option value="">All Users</option>
                                    <?php foreach($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" 
                                                <?= $request->getGet('deleted_by') == $user['id'] ? 'selected' : '' ?>>
                                            <?= esc($user['name']) ?> (<?= esc($user['username']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                                <a href="<?= base_url('/entries/deleted_entries_report') ?>" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                                <a href="<?= base_url('/entries/export_deleted_entries') ?>?<?= http_build_query($request->getGet()) ?>" 
                                   class="btn btn-success">
                                    <i class="fa fa-download"></i> Export
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="body">
                        <?php if (session()->getFlashdata('succ')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= esc(session()->getFlashdata('succ')) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('fail')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= esc(session()->getFlashdata('fail')) ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="deleted_entries_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Entry Code</th>
                                        <th>Type</th>
                                        <th>Original Date</th>
                                        <th>Amount</th>
                                        <th>Narration</th>
                                        <th>Deleted By</th>
                                        <th>Deleted At</th>
                                        <th>Reason</th>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($deleted_entries as $entry): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($entry['entry_code']) ?></strong><br>
                                                <small class="text-muted">ID: <?= $entry['original_entry_id'] ?></small>
                                            </td>
                                            <td>
                                                <?php 
                                                $badge_class = [
                                                    1 => 'badge-success',
                                                    2 => 'badge-danger', 
                                                    3 => 'badge-warning',
                                                    4 => 'badge-info',
                                                    5 => 'badge-primary'
                                                ];
                                                ?>
                                                <span class="badge entry-type-badge <?= $badge_class[$entry['entry_type_id']] ?? 'badge-secondary' ?>">
                                                    <?= esc($entry['entry_type_name']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($entry['entry_date'])) ?></td>
                                            <td class="text-right">
                                                <strong><?= number_format($entry['dr_total'], 2) ?></strong>
                                            </td>
                                            <td>
                                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                    <?= esc(substr($entry['narration'], 0, 50)) ?><?= strlen($entry['narration']) > 50 ? '...' : '' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fa fa-user"></i> <?= esc($entry['deleted_by_name']) ?><br>
                                                <small class="text-muted"><?= esc($entry['deleted_by_username']) ?> (ID: <?= $entry['deleted_by'] ?>)</small>
                                            </td>
                                            <td>
                                                <?= date('d-m-Y H:i:s', strtotime($entry['deleted_at'])) ?>
                                            </td>
                                            <td>
                                                <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;" 
                                                     title="<?= esc($entry['deleted_reason']) ?>">
                                                    <?= esc(substr($entry['deleted_reason'], 0, 30)) ?><?= strlen($entry['deleted_reason']) > 30 ? '...' : '' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= esc($entry['ip_address']) ?></small>
                                            </td>
                                            <td>
                                                <?php if($entry['status'] == 'deleted'): ?>
                                                    <span class="badge badge-danger">Deleted</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Restored</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('/entries/view_deleted_entry/' . $entry['id']) ?>" 
                                                       class="btn btn-sm btn-primary" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('/entries/print_deleted_entry/' . $entry['id']) ?>" 
                                                       class="btn btn-sm btn-info" title="Print" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <?php if($entry['status'] == 'deleted' && $permission['create_p'] == 1): ?>
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="restoreEntry(<?= $entry['id'] ?>)" 
                                                                title="Restore Entry">
                                                            <i class="fa fa-undo"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#deleted_entries_table').DataTable({
        responsive: true,
        order: [[6, 'desc']], // Order by deleted_at column
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});

function restoreEntry(deletedEntryId) {
    if(confirm('Are you sure you want to restore this entry? This will make it active again in the system.')) {
        window.location.href = '<?= base_url('/entries/restore_entry/') ?>' + deletedEntryId;
    }
}
</script>