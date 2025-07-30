<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <?php if ($grn['status'] == 'draft'): ?>
                            <a href="<?= base_url('goods-receipts/approve/' . $grn['id']) ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Are you sure you want to approve this GRN? Stock will be updated.')">
                                <i class="fas fa-check"></i> Approve
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('goods-receipts/print/' . $grn['id']) ?>" 
                           class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="<?= base_url('goods-receipts') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>GRN Number:</strong></td>
                                    <td><?= $grn['grn_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>GRN Date:</strong></td>
                                    <td><?= date('d-m-Y', strtotime($grn['grn_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>PO Number:</strong></td>
                                    <td><?= $grn['po_number'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if ($grn['status'] == 'draft'): ?>
                                            <span class="badge badge-warning">Draft</span>
                                        <?php elseif ($grn['status'] == 'approved'): ?>
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
                                    <td><?= $grn['supplier_name'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Number:</strong></td>
                                    <td><?= $grn['invoice_number'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td><?= $grn['invoice_date'] ? date('d-m-Y', strtotime($grn['invoice_date'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Delivery Note:</strong></td>
                                    <td><?= $grn['delivery_note_number'] ?? '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h5>Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Ordered</th>
                                    <th>Received</th>
                                    <th>Accepted</th>
                                    <th>Rejected</th>
                                    <th>Unit Price</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Batch No</th>
                                    <th>Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sno = 1; foreach ($grn_items as $item): ?>
                                <tr>
                                    <td><?= $sno++ ?></td>
                                    <td><?= $item['item_code'] ?></td>
                                    <td><?= $item['item_name'] ?></td>
                                    <td class="text-right"><?= number_format($item['ordered_quantity'], 3) ?></td>
                                    <td class="text-right"><?= number_format($item['received_quantity'], 3) ?></td>
                                    <td class="text-right"><?= number_format($item['accepted_quantity'], 3) ?></td>
                                    <td class="text-right"><?= number_format($item['rejected_quantity'], 3) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['unit_price'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['tax_amount'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['discount_amount'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['total_amount'], 2) ?></td>
                                    <td><?= $item['batch_number'] ?? '-' ?></td>
                                    <td><?= $item['expiry_date'] ? date('d-m-Y', strtotime($item['expiry_date'])) : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="10" class="text-right">Subtotal:</th>
                                    <th class="text-right">₹ <?= number_format($grn['subtotal'], 2) ?></th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <th colspan="10" class="text-right">Tax:</th>
                                    <th class="text-right">₹ <?= number_format($grn['tax_amount'], 2) ?></th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <th colspan="10" class="text-right">Discount:</th>
                                    <th class="text-right">₹ <?= number_format($grn['discount_amount'], 2) ?></th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <th colspan="10" class="text-right">Total:</th>
                                    <th class="text-right">₹ <?= number_format($grn['total_amount'], 2) ?></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if ($grn['notes']): ?>
                    <div class="mt-3">
                        <strong>Notes:</strong><br>
                        <?= nl2br($grn['notes']) ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($grn['status'] == 'approved'): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            Approved by User ID: <?= $grn['approved_by'] ?> on 
                            <?= date('d-m-Y H:i:s', strtotime($grn['approved_date'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>