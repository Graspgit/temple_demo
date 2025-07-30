<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            PURCHASE ORDER LIST
                            <a href="<?= base_url('purchase-orders/create') ?>" class="btn btn-primary pull-right">
                                <i class="material-icons">add</i> Create New PO
                            </a>
                        </h2>
                    </div>
                    <div class="body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert suc-alert">
                                <?= session()->getFlashdata('success') ?>
                                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert">
                                <?= session()->getFlashdata('error') ?>
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>PO Number</th>
                                        <th>PO Date</th>
                                        <th>Supplier</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($purchase_orders as $po): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $po['po_number'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($po['po_date'])) ?></td>
                                        <td><?= $po['supplier_name'] ?></td>
                                        <td style="text-align: right">
                                            <?= number_format($po['total_amount'], 2) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'draft' => 'label-default',
                                                'approved' => 'label-success',
                                                'partial' => 'label-warning',
                                                'completed' => 'label-info',
                                                'cancelled' => 'label-danger'
                                            ];
                                            ?>
                                            <span class="label <?= $statusClass[$po['status']] ?>">
                                                <?= ucfirst($po['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            
                                            <?php if ($po['status'] == 'draft'): ?>
                                                <a href="<?= base_url('purchase-orders/edit/' . $po['id']) ?>" 
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                
                                                <button onclick="approvePO(<?= $po['id'] ?>)" 
                                                        class="btn btn-sm btn-success" title="Approve">
                                                    <i class="material-icons">check</i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($po['status'], ['approved', 'partial'])): ?>
                                                <a href="<?= base_url('goods-receipts/create?po_id=' . $po['id']) ?>" 
                                                   class="btn btn-sm btn-warning" title="Create GRN">
                                                    <i class="material-icons">add_shopping_cart</i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="<?= base_url('purchase-orders/print/' . $po['id']) ?>" 
                                               target="_blank" class="btn btn-sm btn-default" title="Print">
                                                <i class="material-icons">print</i>
                                            </a>
                                            
                                            <?php if (!in_array($po['status'], ['completed', 'cancelled'])): ?>
                                                <button onclick="cancelPO(<?= $po['id'] ?>)" 
                                                        class="btn btn-sm btn-danger" title="Cancel">
                                                    <i class="material-icons">cancel</i>
                                                </button>
                                            <?php endif; ?>
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
function approvePO(id) {
    if (confirm('Are you sure you want to approve this Purchase Order?')) {
        window.location.href = '<?= base_url('purchase-orders/approve') ?>/' + id;
    }
}

function cancelPO(id) {
    if (confirm('Are you sure you want to cancel this Purchase Order?')) {
        window.location.href = '<?= base_url('purchase-orders/cancel') ?>/' + id;
    }
}
</script>