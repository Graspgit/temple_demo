<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('goods-receipts/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create GRN
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

                    <table class="table table-bordered table-striped" id="grnTable">
                        <thead>
                            <tr>
                                <th>GRN No</th>
                                <th>Date</th>
                                <th>PO No</th>
                                <th>Supplier</th>
                                <th>Invoice No</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grns as $grn): ?>
                            <tr>
                                <td><?= $grn['grn_number'] ?></td>
                                <td><?= date('d-m-Y', strtotime($grn['grn_date'])) ?></td>
                                <td><?= $grn['po_number'] ?? '-' ?></td>
                                <td><?= $grn['supplier_name'] ?></td>
                                <td><?= $grn['invoice_number'] ?? '-' ?></td>
                                <td class="text-right">₹ <?= number_format($grn['total_amount'], 2) ?></td>
                                <td>
                                    <?php if ($grn['status'] == 'draft'): ?>
                                        <span class="badge badge-warning">Draft</span>
                                    <?php elseif ($grn['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('goods-receipts/view/' . $grn['id']) ?>" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($grn['status'] == 'draft'): ?>
                                        <a href="<?= base_url('goods-receipts/approve/' . $grn['id']) ?>" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Are you sure you want to approve this GRN? Stock will be updated.')"
                                           title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= base_url('goods-receipts/print/' . $grn['id']) ?>" 
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
    $('#grnTable').DataTable({
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