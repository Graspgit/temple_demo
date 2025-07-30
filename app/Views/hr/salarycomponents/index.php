<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STAFF SALARY COMPONENTS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('salarycomponents/bulk') ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">group_add</i> Bulk Assignment
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Basic Salary</th>
                                        <th>Allowances</th>
                                        <th>Deductions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($staff as $employee): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $employee['staff_code'] ?></td>
                                        <td><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></td>
                                        <td><?= $employee['department_name'] ?? '-' ?></td>
                                        <td>RM <?= number_format($employee['basic_salary'], 2) ?></td>
                                        <td>
                                            <span class="badge bg-green"><?= $employee['allowance_count'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-red"><?= $employee['deduction_count'] ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('salarycomponents/manage/' . $employee['id']) ?>" 
                                               class="btn btn-sm btn-primary" title="Manage Components">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="<?= base_url('salarycomponents/breakdown/' . $employee['id']) ?>" 
                                               class="btn btn-sm btn-info" title="View Breakdown">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="<?= base_url('salarycomponents/history/' . $employee['id']) ?>" 
                                               class="btn btn-sm btn-warning" title="History">
                                                <i class="material-icons">history</i>
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