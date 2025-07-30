<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <?php if ($payment['status'] == 'draft'): ?>
                            <a href="<?= base_url('purchase-payments/approve/' . $payment['id']) ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Are you sure you want to approve this payment?')">
                                <i class="fas fa-check"></i> Approve
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($payment['status'] != 'cancelled'): ?>
                            <a href="<?= base_url('purchase-payments/cancel/' . $payment['id']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to cancel this payment?')">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('purchase-payments/print/' . $payment['id']) ?>" 
                           class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="<?= base_url('purchase-payments') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Payment Number:</strong></td>
                                    <td><?= $payment['payment_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Date:</strong></td>
                                    <td><?= date('d-m-Y', strtotime($payment['payment_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Mode:</strong></td>
                                    <td><?= $payment['payment_mode_name'] ?? 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td><strong>₹ <?= number_format($payment['amount'], 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if ($payment['status'] == 'draft'): ?>
                                            <span class="badge badge-secondary">Draft</span>
                                        <?php elseif ($payment['status'] == 'approved'): ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td><?= $payment['supplier_name'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td><?= $payment['reference_number'] ?? '-' ?></td>
                                </tr>
                                <?php if ($payment['bank_name']): ?>
                                <tr>
                                    <td><strong>Bank Name:</strong></td>
                                    <td><?= $payment['bank_name'] ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($payment['cheque_number']): ?>
                                <tr>
                                    <td><strong>Cheque Number:</strong></td>
                                    <td><?= $payment['cheque_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cheque Date:</strong></td>
                                    <td><?= $payment['cheque_date'] ? date('d-m-Y', strtotime($payment['cheque_date'])) : '-' ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h5>Invoice Allocations</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Allocated Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sno = 1; foreach ($allocations as $allocation): ?>
                                <tr>
                                    <td><?= $sno++ ?></td>
                                    <td>
                                        <a href="<?= base_url('purchase-invoices/view/' . $allocation['invoice_id']) ?>">
                                            <?= $allocation['invoice_number'] ?>
                                        </a>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($allocation['invoice_date'])) ?></td>
                                    <td class="text-right">₹ <?= number_format($allocation['allocated_amount'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total:</th>
                                    <th class="text-right">₹ <?= number_format($payment['amount'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if ($payment['notes']): ?>
                    <div class="mt-3">
                        <strong>Notes:</strong><br>
                        <?= nl2br($payment['notes']) ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($payment['status'] == 'approved'): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            Approved by User ID: <?= $payment['approved_by'] ?> on 
                            <?= date('d-m-Y H:i:s', strtotime($payment['approved_date'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>

                    <?php if ($payment['entry_id']): ?>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-link"></i> Accounting Entry ID: <?= $payment['entry_id'] ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>