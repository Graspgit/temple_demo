<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <form action="<?= base_url('purchase-payments/store') ?>" method="post" id="paymentForm">
                    <?= csrf_field() ?>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Number <span class="text-danger">*</span></label>
                                    <input type="text" name="payment_number" class="form-control" 
                                           value="<?= $payment_number ?>" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" class="form-control" 
                                           value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier <span class="text-danger">*</span></label>
                                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?= $supplier['id'] ?>"
                                                <?= isset($selected_supplier) && $selected_supplier['id'] == $supplier['id'] ? 'selected' : '' ?>>
                                                <?= $supplier['supplier_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Mode <span class="text-danger">*</span></label>
                                    <select name="payment_mode_id" id="payment_mode_id" class="form-control" required>
                                        <option value="">Select Payment Mode</option>
                                        <?php foreach ($payment_modes as $mode): ?>
                                            <option value="<?= $mode['id'] ?>"><?= $mode['payment_mode'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="payment_amount" class="form-control" 
                                           step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Reference Number</label>
                                    <input type="text" name="reference_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 bank-details" style="display:none;">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 cheque-details" style="display:none;">
                                <div class="form-group">
                                    <label>Cheque Number</label>
                                    <input type="text" name="cheque_number" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row cheque-details" style="display:none;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cheque Date</label>
                                    <input type="date" name="cheque_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5>Invoice Allocation</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Select supplier to load pending invoices. Total allocated amount must equal payment amount.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Invoice No</th>
                                        <th>Invoice Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Balance</th>
                                        <th>Allocate Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="invoicesBody">
                                    <?php if (isset($pending_invoices) && !empty($pending_invoices)): ?>
                                        <?php foreach ($pending_invoices as $index => $invoice): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="invoice-check" 
                                                       data-balance="<?= $invoice['balance_amount'] ?>">
                                            </td>
                                            <td>
                                                <input type="hidden" name="allocations[<?= $index ?>][invoice_id]" 
                                                       value="<?= $invoice['id'] ?>">
                                                <?= $invoice['invoice_number'] ?>
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($invoice['invoice_date'])) ?></td>
                                            <td class="text-right">₹ <?= number_format($invoice['total_amount'], 2) ?></td>
                                            <td class="text-right">₹ <?= number_format($invoice['paid_amount'], 2) ?></td>
                                            <td class="text-right balance">₹ <?= number_format($invoice['balance_amount'], 2) ?></td>
                                            <td>
                                                <input type="number" name="allocations[<?= $index ?>][amount]" 
                                                       class="form-control allocate-amount" 
                                                       max="<?= $invoice['balance_amount'] ?>"
                                                       step="0.01" value="0" disabled>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Please select a supplier to load invoices</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">Total Allocated:</th>
                                        <th class="text-right">₹ <span id="totalAllocated">0.00</span></th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right">Remaining:</th>
                                        <th class="text-right">₹ <span id="remainingAmount">0.00</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="form-group mt-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Save Payment
                        </button>
                        <a href="<?= base_url('purchase-payments') ?>" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var invoiceIndex = <?= isset($pending_invoices) ? count($pending_invoices) : 0 ?>;
    
    // Payment mode change
    $('#payment_mode_id').change(function() {
        var mode = $(this).find('option:selected').text().toLowerCase();
        if (mode.includes('cheque')) {
            $('.cheque-details').show();
            $('.bank-details').show();
        } else if (mode.includes('bank') || mode.includes('neft') || mode.includes('rtgs')) {
            $('.bank-details').show();
            $('.cheque-details').hide();
        } else {
            $('.bank-details').hide();
            $('.cheque-details').hide();
        }
    });
    
    // Supplier selection change
    $('#supplier_id').change(function() {
        var supplier_id = $(this).val();
        if (supplier_id) {
            $.ajax({
                url: '<?= base_url('purchase-payments/get-supplier-invoices') ?>',
                type: 'GET',
                data: { supplier_id: supplier_id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#invoicesBody').empty();
                        invoiceIndex = 0;
                        
                        if (response.invoices.length > 0) {
                            response.invoices.forEach(function(invoice) {
                                addInvoiceRow(invoice);
                            });
                        } else {
                            $('#invoicesBody').html('<tr><td colspan="7" class="text-center">No pending invoices found</td></tr>');
                        }
                        
                        calculateAllocations();
                    }
                }
            });
        } else {
            $('#invoicesBody').html('<tr><td colspan="7" class="text-center">Please select a supplier to load invoices</td></tr>');
        }
    });
    
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.invoice-check').prop('checked', $(this).prop('checked')).trigger('change');
    });
    
    // Individual checkbox change
    $(document).on('change', '.invoice-check', function() {
        var row = $(this).closest('tr');
        var allocateInput = row.find('.allocate-amount');
        
        if ($(this).prop('checked')) {
            allocateInput.prop('disabled', false);
            var balance = parseFloat($(this).data('balance'));
            var remaining = parseFloat($('#payment_amount').val() || 0) - getCurrentAllocated() + parseFloat(allocateInput.val() || 0);
            
            if (remaining >= balance) {
                allocateInput.val(balance.toFixed(2));
            } else if (remaining > 0) {
                allocateInput.val(remaining.toFixed(2));
            }
        } else {
            allocateInput.val(0).prop('disabled', true);
        }
        
        calculateAllocations();
    });
    
    // Payment amount change
    $('#payment_amount').on('input', function() {
        calculateAllocations();
    });
    
    // Allocation amount change
    $(document).on('input', '.allocate-amount', function() {
        var max = parseFloat($(this).attr('max'));
        var val = parseFloat($(this).val()) || 0;
        
        if (val > max) {
            $(this).val(max);
        }
        
        calculateAllocations();
    });
    
    // Form submission validation
    $('#paymentForm').submit(function(e) {
        var paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        var totalAllocated = getCurrentAllocated();
        
        if (Math.abs(paymentAmount - totalAllocated) > 0.01) {
            e.preventDefault();
            alert('Total allocated amount must equal payment amount');
            return false;
        }
        
        if (totalAllocated == 0) {
            e.preventDefault();
            alert('Please allocate payment to at least one invoice');
            return false;
        }
    });
});

function addInvoiceRow(invoice) {
    var html = `
        <tr>
            <td>
                <input type="checkbox" class="invoice-check" 
                       data-balance="${invoice.balance_amount}">
            </td>
            <td>
                <input type="hidden" name="allocations[${invoiceIndex}][invoice_id]" 
                       value="${invoice.id}">
                ${invoice.invoice_number}
            </td>
            <td>${formatDate(invoice.invoice_date)}</td>
            <td class="text-right">₹ ${parseFloat(invoice.total_amount).toFixed(2)}</td>
            <td class="text-right">₹ ${parseFloat(invoice.paid_amount).toFixed(2)}</td>
            <td class="text-right balance">₹ ${parseFloat(invoice.balance_amount).toFixed(2)}</td>
            <td>
                <input type="number" name="allocations[${invoiceIndex}][amount]" 
                       class="form-control allocate-amount" 
                       max="${invoice.balance_amount}"
                       step="0.01" value="0" disabled>
            </td>
        </tr>
    `;
    
    $('#invoicesBody').append(html);
    invoiceIndex++;
}

function calculateAllocations() {
    var paymentAmount = parseFloat($('#payment_amount').val()) || 0;
    var totalAllocated = getCurrentAllocated();
    var remaining = paymentAmount - totalAllocated;
    
    $('#totalAllocated').text(totalAllocated.toFixed(2));
    $('#remainingAmount').text(remaining.toFixed(2));
    
    if (Math.abs(remaining) < 0.01) {
        $('#remainingAmount').parent().parent().removeClass('text-danger').addClass('text-success');
    } else {
        $('#remainingAmount').parent().parent().removeClass('text-success').addClass('text-danger');
    }
}

function getCurrentAllocated() {
    var total = 0;
    $('.allocate-amount:not(:disabled)').each(function() {
        total += parseFloat($(this).val()) || 0;
    });
    return total;
}

function formatDate(dateString) {
    var date = new Date(dateString);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    return day + '-' + month + '-' + year;
}
</script>