<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('purchase-invoices/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Invoice
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

                    <table class="table table-bordered table-striped" id="invoiceTable">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Supplier Inv No</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                            <tr>
                                <td><?= $invoice['invoice_number'] ?></td>
                                <td><?= date('d-m-Y', strtotime($invoice['invoice_date'])) ?></td>
                                <td><?= $invoice['supplier_name'] ?></td>
                                <td><?= $invoice['supplier_invoice_number'] ?></td>
                                <td class="text-right">₹ <?= number_format($invoice['total_amount'], 2) ?></td>
                                <td class="text-right">₹ <?= number_format($invoice['paid_amount'], 2) ?></td>
                                <td class="text-right">₹ <?= number_format($invoice['balance_amount'], 2) ?></td>
                                <td>
                                    <?php if ($invoice['payment_status'] == 'unpaid'): ?>
                                        <span class="badge badge-danger">Unpaid</span>
                                    <?php elseif ($invoice['payment_status'] == 'partial'): ?>
                                        <span class="badge badge-warning">Partial</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($invoice['status'] == 'draft'): ?>
                                        <span class="badge badge-secondary">Draft</span>
                                    <?php elseif ($invoice['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('purchase-invoices/view/' . $invoice['id']) ?>" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($invoice['status'] == 'draft'): ?>
                                        <a href="<?= base_url('purchase-invoices/approve/' . $invoice['id']) ?>" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Are you sure you want to approve this invoice?')"
                                           title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($invoice['status'] == 'approved' && $invoice['payment_status'] != 'paid'): ?>
                                        <a href="<?= base_url('purchase-payments/create?supplier_id=' . $invoice['supplier_id']) ?>" 
                                           class="btn btn-sm btn-primary" title="Make Payment">
                                            <i class="fas fa-money-bill"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($invoice['status'] != 'cancelled' && $invoice['paid_amount'] == 0): ?>
                                        <a href="<?= base_url('purchase-invoices/cancel/' . $invoice['id']) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to cancel this invoice?')"
                                           title="Cancel">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= base_url('purchase-invoices/print/' . $invoice['id']) ?>" 
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
    $('#invoiceTable').DataTable({
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