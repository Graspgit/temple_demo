<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>RECEIPT & PAYMENT HISTORY</h2>
        </div>
        
        <!-- Invoice Information -->
        <?php if (!empty($invoice_data)) { ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>INVOICE DETAILS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Invoice No:</strong><br>
                                <?= esc($invoice_data['invoice_no']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Date:</strong><br>
                                <?= date('d-m-Y', strtotime($invoice_data['date'])) ?>
                            </div>
                            <div class="col-md-3">
                                <strong><?= esc($invoice_data['entity_type']) ?>:</strong><br>
                                <?= esc($invoice_data['entity_name']) ?> (<?= esc($invoice_data['entity_code']) ?>)
                            </div>
                            <div class="col-md-3">
                                <strong>Invoice Type:</strong><br>
                                <?= ($invoice_data['invoice_type'] == 1) ? 'Sales Invoice' : 'Purchase Invoice' ?>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-3">
                                <strong>Grand Total:</strong><br>
                                <span style="font-size: 16px; color: #007bff;"><?= number_format($invoice_data['grand_total'], 2) ?></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Paid Amount:</strong><br>
                                <span style="font-size: 16px; color: #28a745;"><?= number_format($invoice_data['paid_amount'], 2) ?></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Due Amount:</strong><br>
                                <span style="font-size: 16px; color: #dc3545;"><?= number_format($invoice_data['due_amount'], 2) ?></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                <?php if ($invoice_data['due_amount'] <= 0) { ?>
                                    <span class="label label-success">PAID</span>
                                <?php } else { ?>
                                    <span class="label label-danger">OUTSTANDING</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Receipt & Payment History -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2><?= ($invoice_data['invoice_type'] == 1) ? 'RECEIPT HISTORY' : 'PAYMENT HISTORY' ?></h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <button type="button" class="btn btn-primary waves-effect" onclick="printReceiptPaymentHistory(<?= $invoice_data['id'] ?>)" style="margin-right: 10px;">
                                    <i class="material-icons">print</i> PRINT ALL
                                </button>
                                <button type="button" class="btn btn-default waves-effect" onclick="window.close();">
                                    <i class="material-icons">close</i> CLOSE
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <?php if (!empty($receipt_payment_history)) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Voucher Number</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Narration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sno = 1;
                                    $total_receipts = 0;
                                    $total_payments = 0;
                                    
                                    foreach($receipt_payment_history as $row) { 
                                        $amount = ($row['entry_type'] == 'Receipt') ? $row['dr_total'] : $row['cr_total'];
                                        
                                        if ($row['entry_type'] == 'Receipt') {
                                            $total_receipts += $amount;
                                        } else {
                                            $total_payments += $amount;
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $sno++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['entry_date'])) ?></td>
                                        <td><?= esc($row['voucher_number'] ?: '-') ?></td>
                                        <td style="text-align: right;">
                                            <strong style="color: <?= ($row['entry_type'] == 'Receipt') ? '#28a745' : '#ffc107' ?>;">
                                                <?= number_format($amount, 2) ?>
                                            </strong>
                                        </td>
                                        <td><?= esc($row['payment_mode'] ?: '-') ?></td>
                                        <td><?= esc($row['narration'] ?: '-') ?></td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-primary btn-sm waves-effect" onclick="printVoucher(<?= $row['entry_id'] ?>, '<?= strtolower($row['entry_type']) ?>')">
                                                <i class="material-icons">print</i> Print
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                                        <td colspan="4" style="text-align: right;"><strong>TOTAL <?= strtoupper(($invoice_data['invoice_type'] == 1) ? 'RECEIPTS' : 'PAYMENTS') ?>:</strong></td>
                                        <td style="text-align: right; color: #28a745;"><strong><?= number_format($total_receipts + $total_payments, 2) ?></strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-info">
                            <strong>No <?= ($invoice_data['invoice_type'] == 1) ? 'Receipt' : 'Payment' ?> History Found!</strong><br>
                            There are no <?= ($invoice_data['invoice_type'] == 1) ? 'receipt' : 'payment' ?> transactions recorded for this invoice.
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Function to print voucher - you can customize this URL based on your print voucher route
function printVoucher(entryId, entryType) {
    // Replace this with your actual print voucher URL
    let printUrl = '<?= base_url('entries/print_page') ?>/' + entryId;
    window.open(printUrl, '_blank');
}

// Function to print overall receipt and payment history
function printReceiptPaymentHistory(invoiceId) {
    let printUrl = '<?= base_url('due_report/printReceiptPaymentHistory') ?>/' + invoiceId;
    window.open(printUrl, '_blank');
}
</script>

<style>
.label-success { background-color: #5cb85c; }
.label-warning { background-color: #f0ad4e; }
.label-danger { background-color: #d9534f; }
</style>