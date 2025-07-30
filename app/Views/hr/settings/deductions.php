<!-- hr/statutorysettings/deductions.php -->
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
                        <h2>DEDUCTION SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#deductionModal">
                                    <i class="material-icons">add</i> Add Deduction
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
                        <div class="alert alert-warning">
                            <strong>Note:</strong> These deductions will be applied to staff salaries in addition to statutory deductions (EPF, SOCSO, EIS, PCB).
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover data-js-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Deduction Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($deductions as $deduction): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $deduction['deduction_name'] ?></td>
                                        <td>
                                            <span class="label label-info"><?= ucfirst($deduction['deduction_type']) ?></span>
                                        </td>
                                        <td>
                                            <span class="label <?= $deduction['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $deduction['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d-M-Y', strtotime($deduction['created_at'])) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-deduction" 
                                                data-id="<?= $deduction['id'] ?>"
                                                data-name="<?= $deduction['deduction_name'] ?>"
                                                data-type="<?= $deduction['deduction_type'] ?>"
                                                data-status="<?= $deduction['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/deductions/'.$deduction['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure? This will affect all staff with this deduction.')">
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

        <!-- Common Deductions Reference -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-pink">
                        <h2>COMMON DEDUCTION TYPES</h2>
                    </div>
                    <div class="body">
                        <h5>Fixed Amount Deductions:</h5>
                        <ul>
                            <li>Loan Repayment</li>
                            <li>Advance Salary Recovery</li>
                            <li>Uniform Deduction</li>
                            <li>Insurance Premium</li>
                            <li>Club Membership</li>
                            <li>Parking Fees</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-cyan">
                        <h2>PERCENTAGE BASED DEDUCTIONS</h2>
                    </div>
                    <div class="body">
                        <h5>Percentage of Basic Salary:</h5>
                        <ul>
                            <li>Cooperative Contribution</li>
                            <li>Union Fees</li>
                            <li>Savings Scheme</li>
                            <li>Welfare Fund</li>
                            <li>Performance Penalty</li>
                            <li>Late Coming Deduction</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Deduction Modal -->
<div class="modal fade" id="deductionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveDeduction') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="deduction_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Deduction</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="deduction_name" id="deduction_name" class="form-control" required>
                            <label class="form-label">Deduction Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deduction Type <span class="text-danger">*</span></label>
                        <select name="deduction_type" id="deduction_type" class="form-control show-tick" required>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage of Basic Salary</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="deduction_status" class="form-control show-tick">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> After creating this deduction type, you can assign it to individual staff members with specific amounts or percentages.
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
        order: [[0, 'asc']]
    });

    // Edit deduction
    $('.edit-deduction').on('click', function() {
        var data = $(this).data();
        $('#deduction_id').val(data.id);
        $('#deduction_name').val(data.name);
        $('#deduction_type').val(data.type).selectpicker('refresh');
        $('#deduction_status').val(data.status).selectpicker('refresh');
        
        $('#deductionModal').modal('show');
    });

    // Clear form on modal close
    $('#deductionModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#deduction_id').val('');
        $('#deduction_type').selectpicker('refresh');
        $('#deduction_status').selectpicker('refresh');
    });

    $('.show-tick').selectpicker();
});
</script>