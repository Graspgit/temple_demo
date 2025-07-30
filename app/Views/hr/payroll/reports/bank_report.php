<!-- hr/payroll/reports/bank_report.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Summary -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>
                            BANK TRANSFER SUMMARY
                            <small>Total Amount: RM <?= number_format($total_amount, 2) ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-warning waves-effect" onclick="exportBankFile()">
                                    <i class="material-icons">file_download</i> EXPORT BANK FILE
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Month:</strong> <?= date('F Y', strtotime($month . '-01')) ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Total Recipients:</strong> <?= count($bank_transfers) ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Processing Date:</strong> <?= date('d-M-Y') ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Status:</strong> <span class="label label-warning">Pending Transfer</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Transfer Details -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TRANSFER DETAILS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Bank</th>
                                        <th>Account Number</th>
                                        <th>Account Holder</th>
                                        <th class="text-right">Amount (RM)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($bank_transfers as $transfer): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $transfer['staff_code'] ?></td>
                                        <td><?= $transfer['first_name'] . ' ' . $transfer['last_name'] ?></td>
                                        <td><?= $transfer['bank_name'] ?? 'Not Set' ?></td>
                                        <td><?= $transfer['account_number'] ?? 'Not Set' ?></td>
                                        <td><?= $transfer['account_holder_name'] ?? $transfer['first_name'] . ' ' . $transfer['last_name'] ?></td>
                                        <td class="text-right"><strong><?= number_format($transfer['net_salary'], 2) ?></strong></td>
                                        <td>
                                            <?php if(empty($transfer['bank_name']) || empty($transfer['account_number'])): ?>
                                                <span class="label label-danger">Missing Bank Info</span>
                                            <?php else: ?>
                                                <span class="label label-success">Ready</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary" onclick="editBankDetails(<?= $transfer['staff_id'] ?>)">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">TOTAL</th>
                                        <th class="text-right"><?= number_format($total_amount, 2) ?></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Summary by Bank -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SUMMARY BY BANK</h2>
                    </div>
                    <div class="body">
                        <?php
                        $bankSummary = [];
                        foreach($bank_transfers as $transfer) {
                            $bankName = $transfer['bank_name'] ?? 'Not Set';
                            if(!isset($bankSummary[$bankName])) {
                                $bankSummary[$bankName] = ['count' => 0, 'amount' => 0];
                            }
                            $bankSummary[$bankName]['count']++;
                            $bankSummary[$bankName]['amount'] += $transfer['net_salary'];
                        }
                        ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th class="text-center">Number of Transfers</th>
                                    <th class="text-right">Total Amount (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($bankSummary as $bank => $data): ?>
                                <tr>
                                    <td><?= $bank ?></td>
                                    <td class="text-center"><?= $data['count'] ?></td>
                                    <td class="text-right"><?= number_format($data['amount'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bank Details Modal -->
<div class="modal fade" id="bankDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Bank Details</h4>
            </div>
            <div class="modal-body">
                <form id="bankDetailsForm">
                    <input type="hidden" id="staff_id" name="staff_id">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <select name="bank_name" class="form-control" required>
                            <option value="">-- Select Bank --</option>
                            <option value="Maybank">Maybank</option>
                            <option value="CIMB Bank">CIMB Bank</option>
                            <option value="Public Bank">Public Bank</option>
                            <option value="RHB Bank">RHB Bank</option>
                            <option value="Hong Leong Bank">Hong Leong Bank</option>
                            <option value="AmBank">AmBank</option>
                            <option value="Bank Islam">Bank Islam</option>
                            <option value="Bank Rakyat">Bank Rakyat</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Account Holder Name</label>
                        <input type="text" name="account_holder_name" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" onclick="saveBankDetails()">SAVE</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script>
function exportBankFile() {
    if(confirm('Generate bank transfer file for all ready transfers?')) {
        window.location.href = '<?= base_url("payroll/export-bank-file/") . $month ?>';
    }
}

function editBankDetails(staffId) {
    $('#staff_id').val(staffId);
    $('#bankDetailsModal').modal('show');
}

function saveBankDetails() {
    // Add AJAX call to save bank details
    alert('Bank details update functionality to be implemented');
    $('#bankDetailsModal').modal('hide');
}
</script>