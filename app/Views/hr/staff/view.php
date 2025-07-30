<!-- hr/staff/view.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <!-- Staff Profile Card -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card profile-card">
                    <div class="profile-header bg-<?= $staff['status'] == 'active' ? 'green' : 'red' ?>">
                        <h2><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
                        <h4><?= $staff['designation']['designation_name'] ?? 'N/A' ?></h4>
                        <h5><?= $staff['department']['department_name'] ?? 'N/A' ?></h5>
                        <span class="label label-default"><?= ucfirst($staff['status']) ?></span>
                    </div>
                    <div class="profile-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <small>Staff Code</small>
                                <h5><?= $staff['staff_code'] ?></h5>
                            </div>
                            <div class="col-xs-6">
                                <small>Staff Type</small>
                                <h5><?= ucfirst($staff['staff_type']) ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <small>Join Date</small>
                                <h5><?= date('d-M-Y', strtotime($staff['join_date'])) ?></h5>
                            </div>
                            <div class="col-xs-6">
                                <small>Service Years</small>
                                <h5><?= round((time() - strtotime($staff['join_date'])) / (365*24*60*60), 1) ?> years</h5>
                            </div>
                        </div>
                        <div class="profile-footer">
                            <a href="<?= base_url('staff/edit/'.$staff['id']) ?>" class="btn btn-primary btn-lg waves-effect btn-block">EDIT PROFILE</a>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card">
                    <div class="header">
                        <h2>CONTACT INFORMATION</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-xs-12">
                                <p><i class="material-icons">email</i> <?= $staff['email'] ?? 'N/A' ?></p>
                                <p><i class="material-icons">phone</i> <?= $staff['phone'] ?? 'N/A' ?></p>
                                <p><i class="material-icons">location_on</i> <?= $staff['address'] ?? 'N/A' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next of Kin -->
                <div class="card">
                    <div class="header">
                        <h2>NEXT OF KIN</h2>
                    </div>
                    <div class="body">
                        <?php if(!empty($staff['next_of_kin'])): ?>
                            <?php foreach($staff['next_of_kin'] as $nok): ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h5><?= $nok['name'] ?> <?= $nok['is_primary'] ? '<span class="label label-primary">Primary</span>' : '' ?></h5>
                                        <p><small>Relationship:</small> <?= $nok['relationship'] ?></p>
                                        <p><small>Phone:</small> <?= $nok['phone'] ?? 'N/A' ?></p>
                                        <p><small>Email:</small> <?= $nok['email'] ?? 'N/A' ?></p>
                                        <hr>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No next of kin information available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Staff Details -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation" class="active"><a href="#personal" data-toggle="tab">PERSONAL</a></li>
                            <li role="presentation"><a href="#employment" data-toggle="tab">EMPLOYMENT</a></li>
                            <?php if($staff['staff_type'] == 'foreigner'): ?>
                            <li role="presentation"><a href="#immigration" data-toggle="tab">IMMIGRATION</a></li>
                            <?php else: ?>
                            <li role="presentation"><a href="#statutory" data-toggle="tab">STATUTORY</a></li>
                            <?php endif; ?>
                            <li role="presentation"><a href="#salary" data-toggle="tab">SALARY</a></li>
                            <li role="presentation"><a href="#documents" data-toggle="tab">DOCUMENTS</a></li>
                            <li role="presentation"><a href="#leave" data-toggle="tab">LEAVE</a></li>
                            <li role="presentation"><a href="#payslips" data-toggle="tab">PAYSLIPS</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="personal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Date of Birth:</strong> <?= $staff['date_of_birth'] ? date('d-M-Y', strtotime($staff['date_of_birth'])) : 'N/A' ?></p>
                                        <p><strong>Age:</strong> <?= $staff['date_of_birth'] ? (date('Y') - date('Y', strtotime($staff['date_of_birth']))) . ' years' : 'N/A' ?></p>
                                        <p><strong>Gender:</strong> <?= ucfirst($staff['gender'] ?? 'N/A') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Marital Status:</strong> <?= ucfirst($staff['marital_status'] ?? 'N/A') ?></p>
                                        <p><strong>Created Date:</strong> <?= date('d-M-Y', strtotime($staff['created_at'])) ?></p>
                                        <p><strong>Last Updated:</strong> <?= $staff['updated_at'] ? date('d-M-Y', strtotime($staff['updated_at'])) : 'Never' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="employment">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Department:</strong> <?= $staff['department']['department_name'] ?? 'N/A' ?></p>
                                        <p><strong>Designation:</strong> <?= $staff['designation']['designation_name'] ?? 'N/A' ?></p>
                                        <p><strong>Join Date:</strong> <?= date('d-M-Y', strtotime($staff['join_date'])) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Employment Status:</strong> <?= ucfirst($staff['status']) ?></p>
                                        <p><strong>Staff Type:</strong> <?= ucfirst($staff['staff_type']) ?></p>
                                        <p><strong>Basic Salary:</strong> RM <?= number_format($staff['basic_salary'], 2) ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if($staff['staff_type'] == 'foreigner'): ?>
                            <div role="tabpanel" class="tab-pane fade" id="immigration">
                                <?php if(!empty($staff['foreigner_details'])): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Passport Number:</strong> <?= $staff['foreigner_details']['passport_number'] ?? 'N/A' ?></p>
                                        <p><strong>Passport Expiry:</strong> <?= $staff['foreigner_details']['passport_expiry'] ? date('d-M-Y', strtotime($staff['foreigner_details']['passport_expiry'])) : 'N/A' ?></p>
                                        <p><strong>Visa Number:</strong> <?= $staff['foreigner_details']['visa_number'] ?? 'N/A' ?></p>
                                        <p><strong>Visa Type:</strong> <?= $staff['foreigner_details']['visa_type'] ?? 'N/A' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Visa Category:</strong> <?= $staff['foreigner_details']['visa_category'] ?? 'N/A' ?></p>
                                        <p><strong>Visa Expiry:</strong> <?= $staff['foreigner_details']['visa_expiry'] ? date('d-M-Y', strtotime($staff['foreigner_details']['visa_expiry'])) : 'N/A' ?></p>
                                        <p><strong>Visa Renewal Date:</strong> <?= $staff['foreigner_details']['visa_renewal_date'] ? date('d-M-Y', strtotime($staff['foreigner_details']['visa_renewal_date'])) : 'N/A' ?></p>
                                        <p><strong>Country of Origin:</strong> <?= $staff['foreigner_details']['country_of_origin'] ?? 'N/A' ?></p>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p>No immigration details available.</p>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div role="tabpanel" class="tab-pane fade" id="statutory">
                                <?php if(!empty($staff['statutory_details'])): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>EPF Number:</strong> <?= $staff['statutory_details']['epf_number'] ?? 'N/A' ?></p>
                                        <p><strong>SOCSO Number:</strong> <?= $staff['statutory_details']['socso_number'] ?? 'N/A' ?></p>
                                        <p><strong>EIS Number:</strong> <?= $staff['statutory_details']['eis_number'] ?? 'N/A' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Income Tax Number:</strong> <?= $staff['statutory_details']['income_tax_number'] ?? 'N/A' ?></p>
                                        <p><strong>PCB Code:</strong> <?= $staff['statutory_details']['pcb_code'] ?? 'N/A' ?></p>
                                        <p><strong>EA Form Submitted:</strong> <?= ($staff['statutory_details']['ea_form_submitted'] ?? 0) == 1 ? 'Yes' : 'No' ?></p>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p>No statutory details available.</p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div role="tabpanel" class="tab-pane fade" id="salary">
                                <h4>Basic Salary: RM <?= number_format($staff['basic_salary'], 2) ?></h4>
                                
                                <h5>Allowances</h5>
                                <?php if(!empty($staff['allowances'])): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Allowance</th>
                                            <th>Amount</th>
                                            <th>Effective Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($staff['allowances'] as $allowance): ?>
                                        <tr>
                                            <td><?= $allowance['allowance_name'] ?></td>
                                            <td>RM <?= number_format($allowance['amount'], 2) ?></td>
                                            <td><?= date('d-M-Y', strtotime($allowance['effective_date'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No allowances configured.</p>
                                <?php endif; ?>

                                <h5>Deductions</h5>
                                <?php if(!empty($staff['deductions'])): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Deduction</th>
                                            <th>Amount</th>
                                            <th>Effective Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($staff['deductions'] as $deduction): ?>
                                        <tr>
                                            <td><?= $deduction['deduction_name'] ?></td>
                                            <td>RM <?= number_format($deduction['amount'], 2) ?></td>
                                            <td><?= date('d-M-Y', strtotime($deduction['effective_date'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No deductions configured.</p>
                                <?php endif; ?>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="documents">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                                    <i class="material-icons">cloud_upload</i> Upload Document
                                </button>
                                <hr>
                                <?php if(!empty($staff['documents'])): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Document Name</th>
                                            <th>Expiry Date</th>
                                            <th>Uploaded Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($staff['documents'] as $doc): ?>
                                        <tr>
                                            <td><?= $doc['document_type'] ?></td>
                                            <td><?= $doc['document_name'] ?></td>
                                            <td><?= $doc['expiry_date'] ? date('d-M-Y', strtotime($doc['expiry_date'])) : 'N/A' ?></td>
                                            <td><?= date('d-M-Y', strtotime($doc['uploaded_date'])) ?></td>
                                            <td>
                                                <a href="<?= base_url($doc['file_path']) ?>" target="_blank" class="btn btn-xs btn-info">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No documents uploaded.</p>
                                <?php endif; ?>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="leave">
                                <h5>Leave Balance for <?= date('Y') ?></h5>
                                <?php if(!empty($leave_balance)): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Entitled</th>
                                            <th>Used</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($leave_balance as $leave): ?>
                                        <tr>
                                            <td><?= $leave['leave_name'] ?></td>
                                            <td><?= $leave['entitled_days'] ?></td>
                                            <td><?= $leave['used_days'] ?></td>
                                            <td><strong><?= $leave['balance_days'] ?></strong></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No leave allocation for current year.</p>
                                <?php endif; ?>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="payslips">
                                <h5>Recent Payslips</h5>
                                <?php if(!empty($recent_payslips)): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Gross Salary</th>
                                            <th>Net Salary</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recent_payslips as $payslip): ?>
                                        <tr>
                                            <td><?= date('F Y', strtotime($payslip['payroll_month'] . '-01')) ?></td>
                                            <td>RM <?= number_format($payslip['gross_salary'], 2) ?></td>
                                            <td>RM <?= number_format($payslip['net_salary'], 2) ?></td>
                                            <td>
                                                <span class="label <?= $payslip['payment_status'] == 'paid' ? 'label-success' : 'label-warning' ?>">
                                                    <?= ucfirst($payslip['payment_status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('payroll/payslip/'.$payslip['id']) ?>" target="_blank" class="btn btn-xs btn-info">
                                                    <i class="material-icons">print</i> View
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No payslips available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Upload Document</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Document Type</label>
                        <select name="document_type" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="Passport">Passport</option>
                            <option value="Visa">Visa</option>
                            <option value="Work Permit">Work Permit</option>
                            <option value="IC">IC</option>
                            <option value="Resume">Resume</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date (Optional)</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Select File</label>
                        <input type="file" name="document" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">UPLOAD</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function() {
    // Upload document
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url("staff/upload-document/".$staff["id"]) ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>