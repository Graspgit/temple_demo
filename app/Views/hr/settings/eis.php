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
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>EIS (EMPLOYMENT INSURANCE SYSTEM) SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#eisModal">
                                    <i class="material-icons">add</i> Add New Rate
                                </button>
                            </li>
                            <li>
                                <a href="<?= base_url('statutorysettings/statutory') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="alert alert-info">
                            <strong>EIS Contribution Rates:</strong>
                            <ul>
                                <li>Employee: 0.2% of monthly wages</li>
                                <li>Employer: 0.2% of monthly wages</li>
                                <li>Maximum insurable wage: RM 4,000 per month</li>
                                <li>Maximum contribution: RM 7.90 (employee) + RM 7.90 (employer)</li>
                            </ul>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover data-js-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Salary From (RM)</th>
                                        <th>Salary To (RM)</th>
                                        <th>Employee %</th>
                                        <th>Employer %</th>
                                        <th>Employee Amount</th>
                                        <th>Employer Amount</th>
                                        <th>Total</th>
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($eis_settings as $eis): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>RM <?= number_format($eis['salary_from'], 2) ?></td>
                                        <td>RM <?= number_format($eis['salary_to'], 2) ?></td>
                                        <td><?= $eis['employee_percentage'] ?>%</td>
                                        <td><?= $eis['employer_percentage'] ?>%</td>
                                        <td><?= $eis['employee_amount'] ? 'RM ' . number_format($eis['employee_amount'], 2) : '-' ?></td>
                                        <td><?= $eis['employer_amount'] ? 'RM ' . number_format($eis['employer_amount'], 2) : '-' ?></td>
                                        <td>RM <?= number_format(($eis['employee_amount'] ?? 0) + ($eis['employer_amount'] ?? 0), 2) ?></td>
                                        <td><?= date('d-M-Y', strtotime($eis['effective_date'])) ?></td>
                                        <td>
                                            <span class="label <?= $eis['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $eis['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-eis" 
                                                data-id="<?= $eis['id'] ?>"
                                                data-salary-from="<?= $eis['salary_from'] ?>"
                                                data-salary-to="<?= $eis['salary_to'] ?>"
                                                data-employee-percentage="<?= $eis['employee_percentage'] ?>"
                                                data-employer-percentage="<?= $eis['employer_percentage'] ?>"
                                                data-employee-amount="<?= $eis['employee_amount'] ?>"
                                                data-employer-amount="<?= $eis['employer_amount'] ?>"
                                                data-effective-date="<?= $eis['effective_date'] ?>"
                                                data-status="<?= $eis['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/eis_settings/'.$eis['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this EIS rate?')">
                                                <i class="material-icons">delete</i>
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

        <!-- Quick Reference -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>EIS CALCULATION EXAMPLE</h2>
                    </div>
                    <div class="body">
                        <h5>For Salary: RM 3,000</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Employee Contribution (0.2%)</td>
                                <td>RM 6.00</td>
                            </tr>
                            <tr>
                                <td>Employer Contribution (0.2%)</td>
                                <td>RM 6.00</td>
                            </tr>
                            <tr class="bg-light-blue">
                                <td><strong>Total EIS</strong></td>
                                <td><strong>RM 12.00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2>EIS BENEFITS</h2>
                    </div>
                    <div class="body">
                        <ul>
                            <li><strong>Job Search Allowance:</strong> Up to 6 months</li>
                            <li><strong>Reduced Income Allowance:</strong> Up to 6 months</li>
                            <li><strong>Training Allowance:</strong> Up to 6 months</li>
                            <li><strong>Early Re-employment Allowance:</strong> 25% of balance</li>
                            <li><strong>Career Counselling:</strong> Free service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- EIS Modal -->
<div class="modal fade" id="eisModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveEis') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="eis_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit EIS Rate</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="salary_from" id="salary_from" class="form-control" step="0.01" required>
                                    <label class="form-label">Salary From (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="salary_to" id="salary_to" class="form-control" step="0.01" required>
                                    <label class="form-label">Salary To (RM)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <h5>Option 1: Percentage Based (Standard 0.2% each)</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employee_percentage" id="employee_percentage" class="form-control" step="0.01" value="0.2">
                                    <label class="form-label">Employee Percentage (%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employer_percentage" id="employer_percentage" class="form-control" step="0.01" value="0.2">
                                    <label class="form-label">Employer Percentage (%)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <h5>Option 2: Fixed Amount (For wages above RM 4,000)</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employee_amount" id="employee_amount" class="form-control" step="0.01">
                                    <label class="form-label">Employee Fixed Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employer_amount" id="employer_amount" class="form-control" step="0.01">
                                    <label class="form-label">Employer Fixed Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" name="effective_date" id="effective_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                                    <label class="form-label">Effective Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control show-tick">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    $('.data-js-table').DataTable({
        responsive: true,
        order: [[1, 'asc']]
    });

    // Edit EIS
    $('.edit-eis').on('click', function() {
        var data = $(this).data();
        $('#eis_id').val(data.id);
        $('#salary_from').val(data.salaryFrom);
        $('#salary_to').val(data.salaryTo);
        $('#employee_percentage').val(data.employeePercentage);
        $('#employer_percentage').val(data.employerPercentage);
        $('#employee_amount').val(data.employeeAmount);
        $('#employer_amount').val(data.employerAmount);
        $('#effective_date').val(data.effectiveDate);
        $('#status').val(data.status).selectpicker('refresh');
        
        $('#eisModal').modal('show');
    });

    // Clear form on modal close
    $('#eisModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#eis_id').val('');
        $('#status').selectpicker('refresh');
    });
});
</script>