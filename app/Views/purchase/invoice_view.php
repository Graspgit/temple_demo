<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <?php if ($invoice['status'] == 'draft'): ?>
                            <a href="<?= base_url('purchase-invoices/approve/' . $invoice['id']) ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Are you sure you want to approve this invoice?')">
                                <i class="fas fa-check"></i> Approve
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($invoice['status'] == 'approved' && $invoice['payment_status'] != 'paid'): ?>
                            <a href="<?= base_url('purchase-payments/create?supplier_id=' . $invoice['supplier_id']) ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-money-bill"></i> Make Payment
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($invoice['status'] != 'cancelled' && $invoice['paid_amount'] == 0): ?>
                            <a href="<?= base_url('purchase-invoices/cancel/' . $invoice['id']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to cancel this invoice?')">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('purchase-invoices/print/' . $invoice['id']) ?>" 
                           class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="<?= base_url('purchase-invoices') ?>" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Invoice Number:</strong></td>
                                    <td><?= $invoice['invoice_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Date:</strong></td>
                                    <td><?= date('d-m-Y', strtotime($invoice['invoice_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Due Date:</strong></td>
                                    <td><?= $invoice['due_date'] ? date('d-m-Y', strtotime($invoice['due_date'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if ($invoice['status'] == 'draft'): ?>
                                            <span class="badge badge-secondary">Draft</span>
                                        <?php elseif ($invoice['status'] == 'approved'): ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <?php if ($invoice['payment_status'] == 'unpaid'): ?>
                                            <span class="badge badge-danger">Unpaid</span>
                                        <?php elseif ($invoice['payment_status'] == 'partial'): ?>
                                            <span class="badge badge-warning">Partial</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td><?= $invoice['supplier_name'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier Invoice No:</strong></td>
                                    <td><?= $invoice['supplier_invoice_number'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier Invoice Date:</strong></td>
                                    <td><?= $invoice['supplier_invoice_date'] ? date('d-m-Y', strtotime($invoice['supplier_invoice_date'])) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>₹ <?= number_format($invoice['total_amount'], 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Balance Amount:</strong></td>
                                    <td><strong>₹ <?= number_format($invoice['balance_amount'], 2) ?></strong></td>
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
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sno = 1; foreach ($invoice_items as $item): ?>
                                <tr>
                                    <td><?= $sno++ ?></td>
                                    <td><?= $item['item_code'] ?></td>
                                    <td><?= $item['item_name'] ?></td>
                                    <td class="text-right"><?= number_format($item['quantity'], 3) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['unit_price'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['tax_amount'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['discount_amount'], 2) ?></td>
                                    <td class="text-right">₹ <?= number_format($item['total_amount'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-right">Subtotal:</th>
                                    <th class="text-right">₹ <?= number_format($invoice['subtotal'], 2) ?></th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">Tax:</th>
                                    <th class="text-right">₹ <?= number_format($invoice['tax_amount'], 2) ?></th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">Discount:</th>
                                    <th class="text-right">₹ <?= number_format($invoice['discount_amount'], 2) ?></th>
                                </tr>
                                <?php if ($invoice['other_charges'] > 0): ?>
                                <tr>
                                    <th colspan="7" class="text-right">Other Charges:</th>
                                    <th class="text-right">₹ <?= number_format($invoice['other_charges'], 2) ?></th>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="7" class="text-right">Total:</th>
                                    <th class="text-right">₹ <?= number_format($invoice['total_amount'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if (!empty($payments)): ?>
                    <hr>
                    <h5>Payment History</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Payment No</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment['payment_number'] ?></td>
                                <td><?= date('d-m-Y', strtotime($payment['payment_date'])) ?></td>
                                <td class="text-right">₹ <?= number_format($payment['allocated_amount'], 2) ?></td>
                                <td><?= $payment['reference_number'] ?? '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Paid:</th>
                                <th class="text-right">₹ <?= number_format($invoice['paid_amount'], 2) ?></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Balance:</th>
                                <th class="text-right">₹ <?= number_format($invoice['balance_amount'], 2) ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <?php endif; ?>

                    <?php if ($invoice['notes']): ?>
                    <div class="mt-3">
                        <strong>Notes:</strong><br>
                        <?= nl2br($invoice['notes']) ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($invoice['status'] == 'approved'): ?>
                    <div class="mt-3">
                        <small class="text-muted">
                            Approved by User ID: <?= $invoice['approved_by'] ?> on 
                            <?= date('d-m-Y H:i:s', strtotime($invoice['approved_date'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>