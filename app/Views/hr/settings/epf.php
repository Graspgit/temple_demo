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
                        <h2>EPF (EMPLOYEES PROVIDENT FUND) SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#epfModal">
                                    <i class="material-icons">add</i> Add New Rate
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success waves-effect" data-toggle="modal" data-target="#importModal">
                                    <i class="material-icons">file_upload</i> Import Rates
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
                            <strong>EPF Contribution Rates:</strong>
                            <ul>
                                <li>Employee: 11% of wages (can be percentage or fixed amount based on salary range)</li>
                                <li>Employer: 13% of wages for monthly salary RM 5,000 and below</li>
                                <li>Employer: 12% of wages for monthly salary exceeding RM 5,000</li>
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
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($epf_settings as $epf): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>RM <?= number_format($epf['salary_from'], 2) ?></td>
                                        <td>RM <?= number_format($epf['salary_to'], 2) ?></td>
                                        <td><?= $epf['employee_percentage'] ?>%</td>
                                        <td><?= $epf['employer_percentage'] ?>%</td>
                                        <td><?= $epf['employee_amount'] ? 'RM ' . number_format($epf['employee_amount'], 2) : '-' ?></td>
                                        <td><?= $epf['employer_amount'] ? 'RM ' . number_format($epf['employer_amount'], 2) : '-' ?></td>
                                        <td><?= date('d-M-Y', strtotime($epf['effective_date'])) ?></td>
                                        <td>
                                            <span class="label <?= $epf['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $epf['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-epf" 
                                                data-id="<?= $epf['id'] ?>"
                                                data-salary-from="<?= $epf['salary_from'] ?>"
                                                data-salary-to="<?= $epf['salary_to'] ?>"
                                                data-employee-percentage="<?= $epf['employee_percentage'] ?>"
                                                data-employer-percentage="<?= $epf['employer_percentage'] ?>"
                                                data-employee-amount="<?= $epf['employee_amount'] ?>"
                                                data-employer-amount="<?= $epf['employer_amount'] ?>"
                                                data-effective-date="<?= $epf['effective_date'] ?>"
                                                data-status="<?= $epf['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/epf_settings/'.$epf['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this EPF rate?')">
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
                        <h2>EPF CALCULATION EXAMPLE</h2>
                    </div>
                    <div class="body">
                        <h5>For Salary: RM 3,000</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Employee Contribution (11%)</td>
                                <td>RM 330.00</td>
                            </tr>
                            <tr>
                                <td>Employer Contribution (13%)</td>
                                <td>RM 390.00</td>
                            </tr>
                            <tr class="bg-light-blue">
                                <td><strong>Total EPF</strong></td>
                                <td><strong>RM 720.00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2>SPECIAL CASES</h2>
                    </div>
                    <div class="body">
                        <ul>
                            <li><strong>Age 60 and above:</strong> Employee 0%, Employer 4%</li>
                            <li><strong>Age 55-59:</strong> Employee 0%, Employer 6.5%</li>
                            <li><strong>Foreign workers:</strong> RM 5 per month (both employee and employer)</li>
                            <li><strong>Domestic servants:</strong> RM 5 per month (both employee and employer)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- EPF Modal -->
<div class="modal fade" id="epfModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveEpf') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="epf_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit EPF Rate</h4>
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
                            <h5>Option 1: Percentage Based (Leave Amount fields empty)</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employee_percentage" id="employee_percentage" class="form-control" step="0.01" value="11">
                                    <label class="form-label">Employee Percentage (%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employer_percentage" id="employer_percentage" class="form-control" step="0.01" value="13">
                                    <label class="form-label">Employer Percentage (%)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <h5>Option 2: Fixed Amount (Overrides percentage)</h5>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/import-epf') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Import EPF Rates</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select CSV File</label>
                        <input type="file" name="epf_file" class="form-control" accept=".csv" required>
                        <small class="text-muted">
                            CSV Format: salary_from, salary_to, employee_percentage, employer_percentage, employee_amount, employer_amount
                        </small>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Note:</strong> This will add new rates. Existing rates will not be affected.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">IMPORT</button>
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

    // Edit EPF
    $('.edit-epf').on('click', function() {
        var data = $(this).data();
        $('#epf_id').val(data.id);
        $('#salary_from').val(data.salaryFrom);
        $('#salary_to').val(data.salaryTo);
        $('#employee_percentage').val(data.employeePercentage);
        $('#employer_percentage').val(data.employerPercentage);
        $('#employee_amount').val(data.employeeAmount);
        $('#employer_amount').val(data.employerAmount);
        $('#effective_date').val(data.effectiveDate);
        $('#status').val(data.status).selectpicker('refresh');
        
        $('#epfModal').modal('show');
    });

    // Clear form on modal close
    $('#epfModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#epf_id').val('');
        $('#status').selectpicker('refresh');
    });
});
</script>