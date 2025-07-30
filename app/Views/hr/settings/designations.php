<?php
$db = \Config\Database::connect();
?>
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
                        <h2>DESIGNATION LIST</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#designationModal">
                                    <i class="material-icons">add</i> Add Designation
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
                        <!-- Department Filter -->
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Filter by Department:</label>
                                    <select id="deptFilter" class="form-control show-tick">
                                        <option value="">All Departments</option>
                                        <?php foreach($departments as $dept): ?>
                                            <option value="<?= $dept['department_name'] ?>"><?= $dept['department_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Designation Name</th>
                                        <th>Department</th>
                                        <th>Salary Range</th>
                                        <th>Total Staff</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($designations as $desig): 
                                        // Count staff in designation
                                        $staffCount = $db->table('staff_details')
                                            ->where('designation_id', $desig['id'])
                                            ->where('status', 'active')
                                            ->countAllResults();
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $desig['designation_name'] ?></td>
                                        <td>
                                            <span class="label label-info"><?= $desig['department_name'] ?? 'All Departments' ?></span>
                                        </td>
                                        <td>
                                            <?php if($desig['min_salary'] > 0 || $desig['max_salary'] > 0): ?>
                                                RM <?= number_format($desig['min_salary'], 0) ?> - RM <?= number_format($desig['max_salary'], 0) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Not Set</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue"><?= $staffCount ?> Staff</span>
                                        </td>
                                        <td>
                                            <span class="label <?= $desig['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $desig['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-designation" 
                                                data-id="<?= $desig['id'] ?>"
                                                data-name="<?= $desig['designation_name'] ?>"
                                                data-department="<?= $desig['department_id'] ?>"
                                                data-min-salary="<?= $desig['min_salary'] ?>"
                                                data-max-salary="<?= $desig['max_salary'] ?>"
                                                data-status="<?= $desig['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <?php if($staffCount == 0): ?>
                                            <a href="<?= base_url('statutorysettings/delete/designations/'.$desig['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this designation?')">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-xs btn-danger" disabled title="Cannot delete designation with active staff">
                                                <i class="material-icons">delete</i>
                                            </button>
                                            <?php endif; ?>
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

        <!-- Common Designations Reference -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header bg-red">
                        <h2>MANAGEMENT</h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled">
                            <li>• Chief Executive Officer</li>
                            <li>• Chief Operating Officer</li>
                            <li>• Chief Financial Officer</li>
                            <li>• General Manager</li>
                            <li>• Department Head</li>
                            <li>• Manager</li>
                            <li>• Assistant Manager</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>PROFESSIONAL</h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled">
                            <li>• Senior Engineer</li>
                            <li>• Engineer</li>
                            <li>• Senior Accountant</li>
                            <li>• Accountant</li>
                            <li>• HR Executive</li>
                            <li>• IT Specialist</li>
                            <li>• Analyst</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2>ADMINISTRATIVE</h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled">
                            <li>• Executive Secretary</li>
                            <li>• Administrative Officer</li>
                            <li>• Senior Clerk</li>
                            <li>• Clerk</li>
                            <li>• Receptionist</li>
                            <li>• Data Entry Operator</li>
                            <li>• Office Assistant</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header bg-orange">
                        <h2>OPERATIONAL</h2>
                    </div>
                    <div class="body">
                        <ul class="list-unstyled">
                            <li>• Supervisor</li>
                            <li>• Team Leader</li>
                            <li>• Technician</li>
                            <li>• Operator</li>
                            <li>• Driver</li>
                            <li>• Security Guard</li>
                            <li>• General Worker</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Designation Modal -->
<div class="modal fade" id="designationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveDesignation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="designation_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Designation</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="designation_name" id="designation_name" class="form-control" required>
                            <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" id="department_id" class="form-control show-tick">
                            <option value="">All Departments</option>
                            <?php foreach($departments as $dept): 
                                if($dept['status'] == 1):
                            ?>
                                <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="min_salary" id="min_salary" class="form-control" step="100">
                                    <label class="form-label">Minimum Salary (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="max_salary" id="max_salary" class="form-control" step="100">
                                    <label class="form-label">Maximum Salary (RM)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="designation_status" class="form-control show-tick">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
    var table = $('.js-basic-example').DataTable({
        responsive: true,
        order: [[1, 'asc']]
    });

    // Department filter
    $('#deptFilter').on('change', function() {
        var department = $(this).val();
        table.column(2).search(department).draw();
    });

    // Edit designation
    $('.edit-designation').on('click', function() {
        var data = $(this).data();
        $('#designation_id').val(data.id);
        $('#designation_name').val(data.name);
        $('#department_id').val(data.department).selectpicker('refresh');
        $('#min_salary').val(data.minSalary);
        $('#max_salary').val(data.maxSalary);
        $('#designation_status').val(data.status).selectpicker('refresh');
        
        $('#designationModal').modal('show');
    });

    // Clear form on modal close
    $('#designationModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#designation_id').val('');
        $('#department_id').selectpicker('refresh');
        $('#designation_status').selectpicker('refresh');
    });

    // Validate salary range
    $('#min_salary, #max_salary').on('change', function() {
        var min = parseFloat($('#min_salary').val()) || 0;
        var max = parseFloat($('#max_salary').val()) || 0;
        
        if (min > 0 && max > 0 && min > max) {
            alert('Minimum salary cannot be greater than maximum salary');
            $('#min_salary').val('');
            $('#max_salary').val('');
        }
    });

    $('.show-tick').selectpicker();
});
</script>