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
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>DEPARTMENT LIST</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#departmentModal">
                                    <i class="material-icons">add</i> Add Department
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover data-js-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Department Code</th>
                                        <th>Department Name</th>
                                        <th>Total Staff</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($departments as $dept): 
                                        // Count staff in department
                                        $staffCount = $db->table('staff_details')
                                            ->where('department_id', $dept['id'])
                                            ->where('status', 'active')
                                            ->countAllResults();
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><strong><?= $dept['department_code'] ?? '-' ?></strong></td>
                                        <td><?= $dept['department_name'] ?></td>
                                        <td>
                                            <span class="badge bg-blue"><?= $staffCount ?> Staff</span>
                                        </td>
                                        <td>
                                            <span class="label <?= $dept['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $dept['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d-M-Y', strtotime($dept['created_at'])) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-department" 
                                                data-id="<?= $dept['id'] ?>"
                                                data-code="<?= $dept['department_code'] ?>"
                                                data-name="<?= $dept['department_name'] ?>"
                                                data-status="<?= $dept['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <?php if($staffCount == 0): ?>
                                            <a href="<?= base_url('statutorysettings/delete/departments/'.$dept['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this department?')">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-xs btn-danger" disabled title="Cannot delete department with active staff">
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

            <!-- Department Statistics -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>DEPARTMENT STATISTICS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-xs-6">
                                <h4><?= count($departments) ?></h4>
                                <span>Total Departments</span>
                            </div>
                            <div class="col-xs-6">
                                <h4><?= count(array_filter($departments, function($d) { return $d['status'] == 1; })) ?></h4>
                                <span>Active Departments</span>
                            </div>
                        </div>
                        <hr>
                        <h5>Staff Distribution:</h5>
                        <?php foreach($departments as $dept): 
                            if($dept['status'] == 1):
                                $staffCount = $db->table('staff_details')
                                    ->where('department_id', $dept['id'])
                                    ->where('status', 'active')
                                    ->countAllResults();
                                $totalStaff = $db->table('staff_details')
                                    ->where('status', 'active')
                                    ->countAllResults();
                                $percentage = $totalStaff > 0 ? ($staffCount / $totalStaff) * 100 : 0;
                        ?>
                        <div class="progress-container">
                            <span class="progress-badge"><?= $dept['department_name'] ?></span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     aria-valuenow="<?= $percentage ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100" 
                                     style="width: <?= $percentage ?>%;">
                                    <?= $staffCount ?> (<?= number_format($percentage, 1) ?>%)
                                </div>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Department Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveDepartment') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="department_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Department</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="department_code" id="department_code" class="form-control" maxlength="20">
                            <label class="form-label">Department Code (Optional)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="department_name" id="department_name" class="form-control" required>
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="department_status" class="form-control show-tick">
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
    $('.data-js-table').DataTable({
        responsive: true,
        order: [[1, 'asc']]
    });

    // Edit department
    $('.edit-department').on('click', function() {
        var data = $(this).data();
        $('#department_id').val(data.id);
        $('#department_code').val(data.code);
        $('#department_name').val(data.name);
        $('#department_status').val(data.status).selectpicker('refresh');
        
        $('#departmentModal').modal('show');
    });

    // Clear form on modal close
    $('#departmentModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#department_id').val('');
        $('#department_status').selectpicker('refresh');
    });

    $('.show-tick').selectpicker();
});
</script>

<style>
.progress-container {
    margin-bottom: 15px;
}
.progress-badge {
    font-size: 12px;
    color: #666;
}
.progress {
    height: 20px;
    margin-top: 5px;
}
.progress-bar {
    font-size: 11px;
    line-height: 20px;
    color: #fff;
}
</style>