<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SOCSO (SOCIAL SECURITY) SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#socsoModal">
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
                            <strong>SOCSO Contribution Categories:</strong>
                            <ul>
                                <li>Employment Injury Scheme - All employees</li>
                                <li>Invalidity Scheme - For employees earning RM 5,000 and below</li>
                            </ul>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Salary From (RM)</th>
                                        <th>Salary To (RM)</th>
                                        <th>Category</th>
                                        <th>Employee Amount</th>
                                        <th>Employer Amount</th>
                                        <th>Total</th>
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($socso_settings as $socso): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>RM <?= number_format($socso['salary_from'], 2) ?></td>
                                        <td>RM <?= number_format($socso['salary_to'], 2) ?></td>
                                        <td><?= $socso['category'] ?? 'Standard' ?></td>
                                        <td>RM <?= number_format($socso['employee_amount'] ?? 0, 2) ?></td>
                                        <td>RM <?= number_format($socso['employer_amount'] ?? 0, 2) ?></td>
                                        <td>RM <?= number_format(($socso['employee_amount'] ?? 0) + ($socso['employer_amount'] ?? 0), 2) ?></td>
                                        <td><?= date('d-M-Y', strtotime($socso['effective_date'])) ?></td>
                                        <td>
                                            <span class="label <?= $socso['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $socso['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-socso" 
                                                data-id="<?= $socso['id'] ?>"
                                                data-salary-from="<?= $socso['salary_from'] ?>"
                                                data-salary-to="<?= $socso['salary_to'] ?>"
                                                data-employee-percentage="<?= $socso['employee_percentage'] ?>"
                                                data-employer-percentage="<?= $socso['employer_percentage'] ?>"
                                                data-employee-amount="<?= $socso['employee_amount'] ?>"
                                                data-employer-amount="<?= $socso['employer_amount'] ?>"
                                                data-category="<?= $socso['category'] ?>"
                                                data-effective-date="<?= $socso['effective_date'] ?>"
                                                data-status="<?= $socso['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/socso_settings/'.$socso['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure?')">
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
    </div>
</section>
<!-- SOCSO Modal -->
<div class="modal fade" id="socsoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveSocso') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="socso_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit SOCSO Rate</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="salary_from" id="socso_salary_from" class="form-control" step="0.01" required>
                                    <label class="form-label">Salary From (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="salary_to" id="socso_salary_to" class="form-control" step="0.01" required>
                                    <label class="form-label">Salary To (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="category" id="socso_category" class="form-control">
                                    <label class="form-label">Category</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employee_amount" id="socso_employee_amount" class="form-control" step="0.01">
                                    <label class="form-label">Employee Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="employer_amount" id="socso_employer_amount" class="form-control" step="0.01">
                                    <label class="form-label">Employer Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" name="effective_date" id="socso_effective_date" class="form-control" required>
                                    <label class="form-label">Effective Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="socso_status" class="form-control">
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
    // Edit SOCSO
    $('.edit-socso').on('click', function() {
        var data = $(this).data();
        $('#socso_id').val(data.id);
        $('#socso_salary_from').val(data.salaryFrom);
        $('#socso_salary_to').val(data.salaryTo);
        $('#socso_employee_amount').val(data.employeeAmount);
        $('#socso_employer_amount').val(data.employerAmount);
        $('#socso_category').val(data.category);
        $('#socso_effective_date').val(data.effectiveDate);
        $('#socso_status').val(data.status);
        
        $('#socsoModal').modal('show');
    });

});
</script>