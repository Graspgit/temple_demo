<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h2 class="page-title"><?= $title ?></h2>
                <p class="text-muted">Configure Register of Marriage (ROM) accounting settings and ledger mappings</p>
            </div>
        </div>
    </div>

    <!-- Settings Cards -->
    <div class="row">
        <!-- ROM Ledger Configuration -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ROM Ledger Configuration</h4>
                    <p class="card-text">Select which income ledger account should be credited for marriage registration fees (RM 1200)</p>
                </div>
                <div class="card-body">
                    <?= form_open('rom-settings/update-rom-ledger') ?>
                    
                    <div class="form-group">
                        <label for="rom_ledger_id">ROM Income Ledger Account *</label>
                        <select name="rom_ledger_id" id="rom_ledger_id" class="form-control" required>
                            <option value="">-- Select ROM Ledger Account --</option>
                            <?php 
                            $current_group = '';
                            foreach ($ledgers as $ledger): 
                                if ($current_group !== $ledger['group_name']):
                                    if ($current_group !== '') echo '</optgroup>';
                                    echo '<optgroup label="' . htmlspecialchars($ledger['group_name']) . '">';
                                    $current_group = $ledger['group_name'];
                                endif;
                            ?>
                                <option value="<?= $ledger['id'] ?>" 
                                    <?= ($current_rom_ledger && $current_rom_ledger['id'] == $ledger['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ledger['name']) ?> (<?= htmlspecialchars($ledger['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                            <?php if ($current_group !== '') echo '</optgroup>'; ?>
                        </select>
                        <small class="form-text text-muted">
                            Only income ledger accounts are shown. Marriage registration fees will be credited to this account.
                        </small>
                    </div>

                    <?php if ($current_rom_ledger): ?>
                    <div class="alert alert-info">
                        <strong>Currently Selected:</strong><br>
                        <?= htmlspecialchars($current_rom_ledger['name']) ?> (<?= htmlspecialchars($current_rom_ledger['code']) ?>)<br>
                        <small>Group: <?= htmlspecialchars($current_rom_ledger['group_name']) ?></small>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update ROM Ledger
                    </button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- Payment Mode Configuration -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Mode Configuration</h4>
                    <p class="card-text">Select which payment modes are available for ROM transactions</p>
                </div>
                <div class="card-body">
                    <?= form_open('rom-settings/update-payment-modes') ?>
                    
                    <div class="form-group">
                        <label>Enable ROM for Payment Modes:</label>
                        <?php foreach ($payment_modes as $mode): ?>
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="pm_<?= $mode['id'] ?>"
                                   name="rom_payment_modes[]" 
                                   value="<?= $mode['id'] ?>"
                                   <?= $mode['rom'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="pm_<?= $mode['id'] ?>">
                                <?= htmlspecialchars($mode['name']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Payment Modes
                    </button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ROM Summary Dashboard -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ROM Accounting Summary</h4>
                    <div class="card-actions">
                        <a href="/rom-settings/accounting-report" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chart-bar"></i> View Full Report
                        </a>
                        <button id="testAccounting" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-cog"></i> Test Configuration
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="statistic-item">
                                <div class="statistic-value text-primary">
                                    <?= number_format($rom_summary['total_collections']['total_registrations'] ?? 0) ?>
                                </div>
                                <div class="statistic-label">Total Registrations</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistic-item">
                                <div class="statistic-value text-success">
                                    RM <?= number_format($rom_summary['total_collections']['total_amount'] ?? 0, 2) ?>
                                </div>
                                <div class="statistic-label">Total Collections</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistic-item">
                                <div class="statistic-value text-info">
                                    <?= count($rom_summary['monthly_collections']) ?>
                                </div>
                                <div class="statistic-label">Active Months</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistic-item">
                                <div class="statistic-value text-warning">
                                    RM <?= number_format(($rom_summary['total_collections']['total_amount'] ?? 0) / max(1, count($rom_summary['monthly_collections'])), 2) ?>
                                </div>
                                <div class="statistic-label">Avg Monthly</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <?php if (!empty($rom_summary['recent_transactions'])): ?>
                    <hr>
                    <h6>Recent ROM Transactions</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Entry Number</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($rom_summary['recent_transactions'], 0, 5) as $transaction): ?>
                                <tr>
                                    <td><?= htmlspecialchars($transaction['number']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($transaction['date'])) ?></td>
                                    <td>RM <?= number_format($transaction['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($transaction['narration']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>No ROM transactions found</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Guide -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> ROM Accounting Guide
                    </h5>
                </div>
                <div class="card-body">
                    <h6>How ROM Accounting Works:</h6>
                    <ol>
                        <li><strong>Registration Fee:</strong> Each marriage registration has a fixed fee of RM 1,200</li>
                        <li><strong>Double Entry:</strong> 
                            <ul>
                                <li>Debit: Payment Mode Ledger (RM 1,200)</li>
                                <li>Credit: ROM Income Ledger (RM 1,200)</li>
                            </ul>
                        </li>
                        <li><strong>Entry Type:</strong> All ROM transactions use entry type 20 in the accounting system</li>
                        <li><strong>Settings:</strong> ROM ledger ID is stored in settings table with type 11</li>
                    </ol>

                    <h6 class="mt-3">Configuration Steps:</h6>
                    <ol>
                        <li>Select the appropriate income ledger account for ROM fees</li>
                        <li>Enable ROM support for relevant payment modes</li>
                        <li>Test the configuration using the "Test Configuration" button</li>
                        <li>Monitor transactions through the accounting report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Test Configuration -->
<script>
document.getElementById('testAccounting').addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    button.disabled = true;
    
    fetch('/rom-settings/test-accounting')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('✓ Configuration Test Passed\n\n' + 
                      'ROM Ledger ID: ' + data.rom_ledger_id + '\n' +
                      'Test Payment Mode ID: ' + data.test_payment_mode_id + '\n\n' +
                      'Your ROM accounting setup is working correctly!');
            } else {
                alert('✗ Configuration Test Failed\n\n' + data.message);
            }
        })
        .catch(error => {
            alert('✗ Test Error\n\n' + error.message);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
});
</script>

<style>
.statistic-item {
    text-align: center;
    padding: 1rem;
}

.statistic-value {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.statistic-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.form-check {
    margin-bottom: 0.5rem;
}

.page-header {
    margin-bottom: 2rem;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 1rem;
}
</style>

<?= $this->endSection() ?>