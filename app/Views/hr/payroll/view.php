<!-- hr/payroll/view.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
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
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL EMPLOYEES</div>
                        <div class="number"><?= $summary['total_staff'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <div class="content">
                        <div class="text">GROSS SALARY</div>
                        <div class="number">RM <?= number_format($summary['total_gross'], 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">trending_down</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL DEDUCTIONS</div>
                        <div class="number">RM <?= number_format($summary['total_epf_employee'] + $summary['total_socso_employee'] + $summary['total_eis_employee'] + $summary['total_pcb'], 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">NET SALARY</div>
                        <div class="number">RM <?= number_format($summary['total_net'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Summary -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PAYMENT STATUS SUMMARY</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box-4 hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons col-orange">hourglass_empty</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">PENDING APPROVAL</div>
                                        <div class="number"><?= $status_summary['pending']['count'] ?> <small>employees</small></div>
                                        <div class="text">Amount: RM <?= number_format($status_summary['pending']['total'] ?? 0, 2) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-box-4 hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons col-blue">done</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">APPROVED (UNPAID)</div>
                                        <div class="number"><?= $status_summary['approved']['count'] ?> <small>employees</small></div>
                                        <div class="text">Amount: RM <?= number_format($status_summary['approved']['total'] ?? 0, 2) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-box-4 hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons col-green">check_circle</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">PAID</div>
                                        <div class="number"><?= $status_summary['paid']['count'] ?> <small>employees</small></div>
                                        <div class="text">Amount: RM <?= number_format($status_summary['paid']['total'] ?? 0, 2) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="info-box-4 hover-expand-effect">
                                    <div class="icon">
                                        <i class="material-icons col-red">cancel</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">CANCELLED</div>
                                        <div class="number"><?= $status_summary['cancelled']['count'] ?> <small>employees</small></div>
                                        <div class="text">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($status_summary['approved']['count'] > 0): ?>
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Info!</strong> There are <?= $status_summary['approved']['count'] ?> approved payslips awaiting payment.
                                    <a href="<?= base_url('payroll/payment/' . $month) ?>" class="btn btn-sm btn-info pull-right">Process Payments</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Details -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PAYROLL DETAILS - <?= date('F Y', strtotime($month . '-01')) ?></h2>
                        <ul class="header-dropdown m-r--5">
                            <?php if($status_summary['pending']['count'] > 0): ?>
                            <li>
                                <button type="button" class="btn btn-warning waves-effect" onclick="showBulkApprovalModal()">
                                    <i class="material-icons">done_all</i> Approve Selected
                                </button>
                            </li>
                            <?php endif; ?>
                            <?php if($status_summary['approved']['count'] > 0): ?>
                            <li>
                                <a href="<?= base_url('payroll/payment/' . $month) ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">payment</i> Process Payments
                                </a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?= base_url('payroll/export/' . $month) ?>" class="btn btn-success waves-effect">
                                    <i class="material-icons">file_download</i> Export
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('payroll/epfReport?month=' . $month) ?>" class="btn btn-info waves-effect">
                                    <i class="material-icons">description</i> EPF Report
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form id="bulkApprovalForm" method="POST" action="<?= base_url('payroll/bulkApprove') ?>">
                            <?= csrf_field() ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll" class="filled-in">
                                                <label for="checkAll"></label>
                                            </th>
                                            <th>#</th>
                                            <th>Staff Code</th>
                                            <th>Name</th>
                                            <th>Basic</th>
                                            <th>Allowance</th>
                                            <th>Gross</th>
                                            <th>EPF</th>
                                            <th>SOCSO</th>
                                            <th>EIS</th>
                                            <th>PCB</th>
                                            <th>Deduction</th>
                                            <th>Net Salary</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach($payroll as $row): ?>
                                        <tr>
                                            <td>
                                                <?php if($row['payment_status'] == 'pending'): ?>
                                                <input type="checkbox" name="payroll_ids[]" value="<?= $row['id'] ?>" id="check_<?= $row['id'] ?>" class="filled-in payroll-checkbox">
                                                <label for="check_<?= $row['id'] ?>"></label>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['staff_code'] ?></td>
                                            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                            <td>RM <?= number_format($row['basic_salary'], 2) ?></td>
                                            <td>RM <?= number_format($row['total_allowances'], 2) ?></td>
                                            <td>RM <?= number_format($row['gross_salary'], 2) ?></td>
                                            <td>RM <?= number_format($row['epf_employee'], 2) ?></td>
                                            <td>RM <?= number_format($row['socso_employee'], 2) ?></td>
                                            <td>RM <?= number_format($row['eis_employee'], 2) ?></td>
                                            <td>RM <?= number_format($row['pcb'], 2) ?></td>
                                            <td>RM <?= number_format($row['total_deductions'], 2) ?></td>
                                            <td><strong>RM <?= number_format($row['net_salary'], 2) ?></strong></td>
                                            <td>
                                                <?php if($row['payment_status'] == 'paid'): ?>
                                                    <span class="label label-success">Paid</span>
                                                    <?php if($row['payment_date']): ?>
                                                        <br><small><?= date('d/m/Y', strtotime($row['payment_date'])) ?></small>
                                                    <?php endif; ?>
                                                <?php elseif($row['payment_status'] == 'approved'): ?>
                                                    <span class="label label-primary">Approved</span>
                                                    <?php if($row['approved_date']): ?>
                                                        <br><small><?= date('d/m/Y', strtotime($row['approved_date'])) ?></small>
                                                    <?php endif; ?>
                                                <?php elseif($row['payment_status'] == 'cancelled'): ?>
                                                    <span class="label label-danger">Cancelled</span>
                                                <?php else: ?>
                                                    <span class="label label-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('payroll/payslip/' . $row['id']) ?>" class="btn btn-xs btn-info" target="_blank" title="View Payslip">
                                                    <i class="material-icons">print</i>
                                                </a>
                                                
                                                <?php if($row['payment_status'] == 'pending'): ?>
                                                    <button type="button" class="btn btn-xs btn-success" onclick="showApprovalModal(<?= $row['id'] ?>, '<?= $row['staff_code'] ?>', '<?= $row['first_name'] . ' ' . $row['last_name'] ?>')" title="Approve">
                                                        <i class="material-icons">done</i>
                                                    </button>
                                                    <a href="<?= base_url('payroll/reject/' . $row['id']) ?>" class="btn btn-xs btn-danger" title="Cancel" onclick="return confirm('Cancel this payslip?')">
                                                        <i class="material-icons">close</i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Single Approval Modal -->
<div class="modal fade" id="singleApprovalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Approve Payslip</h4>
            </div>
            <form method="POST" action="<?= base_url('payroll/approve') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="payroll_id" id="single_payroll_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Staff Details</label>
                        <input type="text" class="form-control" id="single_staff_display" readonly>
                    </div>
                    <div class="form-group">
                        <label>Approval Date <span class="text-danger">*</span></label>
                        <input type="date" name="approval_date" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">APPROVE</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Approval Modal -->
<div class="modal fade" id="bulkApprovalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bulk Approve Payslips</h4>
            </div>
            <div class="modal-body">
                <form id="bulkApprovalModalForm">
                    <div class="form-group">
                        <label>Number of Selected Payslips</label>
                        <input type="text" class="form-control" id="selected_count" readonly>
                    </div>
                    <div class="form-group">
                        <label>Approval Date <span class="text-danger">*</span></label>
                        <input type="date" name="approval_date" id="bulk_approval_date" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect" onclick="submitBulkApproval()">APPROVE ALL</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCEL</button>
            </div>
        </div>
    </div>
</div>

<script>
// Check all functionality
$('#checkAll').click(function() {
    $('.payroll-checkbox').prop('checked', this.checked);
});

// Show single approval modal
function showApprovalModal(payrollId, staffCode, staffName) {
    $('#single_payroll_id').val(payrollId);
    $('#single_staff_display').val(staffCode + ' - ' + staffName);
    $('#singleApprovalModal').modal('show');
}

// Show bulk approval modal
function showBulkApprovalModal() {
    var checkedCount = $('.payroll-checkbox:checked').length;
    if (checkedCount == 0) {
        alert('Please select at least one payslip to approve');
        return;
    }
    $('#selected_count').val(checkedCount + ' payslips selected');
    $('#bulkApprovalModal').modal('show');
}

// Submit bulk approval
function submitBulkApproval() {
    var approvalDate = $('#bulk_approval_date').val();
    if (!approvalDate) {
        alert('Please select approval date');
        return;
    }
    
    // Add approval date to the form
    var dateInput = $('<input>').attr({
        type: 'hidden',
        name: 'approval_date',
        value: approvalDate
    });
    
    $('#bulkApprovalForm').append(dateInput);
    $('#bulkApprovalForm').submit();
}
</script>