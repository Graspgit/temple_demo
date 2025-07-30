<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - <?= date('F Y', strtotime($month . '-01')) ?></h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">hourglass_empty</i>
                    </div>
                    <div class="content">
                        <div class="text">AWAITING PAYMENT</div>
                        <div class="number"><?= count($payrolls) ?> <small>employees</small></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL AMOUNT</div>
                        <div class="number">RM <?= number_format(array_sum(array_column($payrolls, 'net_salary')), 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">date_range</i>
                    </div>
                    <div class="content">
                        <div class="text">PAYROLL MONTH</div>
                        <div class="number"><?= date('F Y', strtotime($month . '-01')) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Processing -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>APPROVED PAYROLLS - PENDING PAYMENT</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('payroll/view/' . $month) ?>" class="btn btn-info waves-effect">
                                    <i class="material-icons">arrow_back</i> Back to Payroll
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <?php if(empty($payrolls)): ?>
                            <div class="alert alert-info">
                                <i class="material-icons">info</i> No approved payrolls pending payment for this month.
                            </div>
                        <?php else: ?>
                        <form id="paymentForm" method="POST" action="<?= base_url('payroll/processPayment') ?>">
                            <?= csrf_field() ?>
                            
                            <!-- Payment Details Section -->
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Mode <span class="text-danger">*</span></label>
                                        <select name="payment_mode_id" class="form-control" required>
                                            <option value="">-- Select Payment Mode --</option>
                                            <?php foreach($payment_modes as $mode): ?>
                                                <option value="<?= $mode['id'] ?>"><?= $mode['name'] ?> (<?= $mode['paid_through'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="button" class="btn btn-primary" onclick="selectAllPayrolls()">
                                            <i class="material-icons">check_box</i> Select All
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="deselectAllPayrolls()">
                                            <i class="material-icons">check_box_outline_blank</i> Deselect All
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll" class="filled-in">
                                                <label for="checkAll"></label>
                                            </th>
                                            <th>#</th>
                                            <th>Staff Code</th>
                                            <th>Name</th>
                                            <th>Bank</th>
                                            <th>Account No</th>
                                            <th>Net Salary</th>
                                            <th>Approved Date</th>
                                            <th>Days Pending</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach($payrolls as $payroll): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="payroll_ids[]" value="<?= $payroll['id'] ?>" 
                                                       id="pay_<?= $payroll['id'] ?>" class="filled-in payment-checkbox"
                                                       data-amount="<?= $payroll['net_salary'] ?>">
                                                <label for="pay_<?= $payroll['id'] ?>"></label>
                                            </td>
                                            <td><?= $i++ ?></td>
                                            <td><?= $payroll['staff_code'] ?></td>
                                            <td><?= $payroll['first_name'] . ' ' . $payroll['last_name'] ?></td>
                                            <td><?= $payroll['bank_name'] ?? 'N/A' ?></td>
                                            <td><?= $payroll['account_number'] ?? 'N/A' ?></td>
                                            <td><strong>RM <?= number_format($payroll['net_salary'], 2) ?></strong></td>
                                            <td><?= date('d/m/Y', strtotime($payroll['approved_date'])) ?></td>
                                            <td>
                                                <?php 
                                                $days = floor((strtotime('now') - strtotime($payroll['approved_date'])) / (60 * 60 * 24));
                                                ?>
                                                <span class="label <?= $days > 3 ? 'label-danger' : 'label-warning' ?>">
                                                    <?= $days ?> days
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">Selected Total:</th>
                                            <th colspan="3">
                                                <span id="selectedTotal">RM 0.00</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-success waves-effect" onclick="return confirmPayment()">
                                        <i class="material-icons">payment</i> PROCESS PAYMENT
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Check all functionality
$('#checkAll').click(function() {
    $('.payment-checkbox').prop('checked', this.checked);
    updateSelectedTotal();
});

// Update total when individual checkbox is clicked
$('.payment-checkbox').click(function() {
    updateSelectedTotal();
});

// Select all payrolls
function selectAllPayrolls() {
    $('.payment-checkbox').prop('checked', true);
    $('#checkAll').prop('checked', true);
    updateSelectedTotal();
}

// Deselect all payrolls
function deselectAllPayrolls() {
    $('.payment-checkbox').prop('checked', false);
    $('#checkAll').prop('checked', false);
    updateSelectedTotal();
}

// Update selected total
function updateSelectedTotal() {
    var total = 0;
    $('.payment-checkbox:checked').each(function() {
        total += parseFloat($(this).data('amount'));
    });
    $('#selectedTotal').text('RM ' + numberFormat(total, 2));
}

// Number format
function numberFormat(number, decimals) {
    return parseFloat(number).toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Confirm payment
function confirmPayment() {
    var checkedCount = $('.payment-checkbox:checked').length;
    if (checkedCount == 0) {
        alert('Please select at least one payroll for payment');
        return false;
    }
    
    var paymentMode = $('[name="payment_mode_id"]').val();
    if (!paymentMode) {
        alert('Please select a payment mode');
        return false;
    }
    
    var total = $('#selectedTotal').text();
    return confirm('Are you sure you want to process payment for ' + checkedCount + ' employees?\nTotal Amount: ' + total);
}
</script>