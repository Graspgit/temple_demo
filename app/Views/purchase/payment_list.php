<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('purchase-payments/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Payment
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <table class="table table-bordered table-striped" id="paymentTable">
                        <thead>
                            <tr>
                                <th>Payment No</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Payment Mode</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment['payment_number'] ?></td>
                                <td><?= date('d-m-Y', strtotime($payment['payment_date'])) ?></td>
                                <td><?= $payment['supplier_name'] ?></td>
                                <td><?= $payment['payment_mode_name'] ?? 'N/A' ?></td>
                                <td class="text-right">₹ <?= number_format($payment['amount'], 2) ?></td>
                                <td><?= $payment['reference_number'] ?? '-' ?></td>
                                <td>
                                    <?php if ($payment['status'] == 'draft'): ?>
                                        <span class="badge badge-secondary">Draft</span>
                                    <?php elseif ($payment['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('purchase-payments/view/' . $payment['id']) ?>" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($payment['status'] == 'draft'): ?>
                                        <a href="<?= base_url('purchase-payments/approve/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Are you sure you want to approve this payment?')"
                                           title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($payment['status'] != 'cancelled'): ?>
                                        <a href="<?= base_url('purchase-payments/cancel/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to cancel this payment?')"
                                           title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= base_url('purchase-payments/print/' . $payment['id']) ?>" 
                                       class="btn btn-sm btn-secondary" title="Print" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
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

<script>
$(document).ready(function() {
    $('#paymentTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[0, 'desc']]
    });
});
</script>