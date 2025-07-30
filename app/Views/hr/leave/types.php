<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LEAVE TYPE SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#leaveTypeModal">
                                    <i class="material-icons">add</i> Add Leave Type
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Code</th>
                                        <th>Leave Name</th>
                                        <th>Days Per Year</th>
                                        <th>Carry Forward</th>
                                        <th>Max Carry Forward</th>
                                        <th>Is Paid</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($leave_types as $type): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $type['leave_code'] ?></td>
                                        <td><?= $type['leave_name'] ?></td>
                                        <td><?= $type['days_per_year'] ?></td>
                                        <td><?= $type['carry_forward'] ? 'Yes' : 'No' ?></td>
                                        <td><?= $type['max_carry_forward'] ?></td>
                                        <td><?= $type['is_paid'] ? 'Yes' : 'No' ?></td>
                                        <td>
                                            <span class="label <?= $type['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $type['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-leave-type" 
                                                data-id="<?= $type['id'] ?>"
                                                data-code="<?= $type['leave_code'] ?>"
                                                data-name="<?= $type['leave_name'] ?>"
                                                data-days="<?= $type['days_per_year'] ?>"
                                                data-carry="<?= $type['carry_forward'] ?>"
                                                data-max-carry="<?= $type['max_carry_forward'] ?>"
                                                data-paid="<?= $type['is_paid'] ?>"
                                                data-status="<?= $type['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
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

<!-- Leave Type Modal -->
<div class="modal fade" id="leaveTypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('leave/save-type') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="leave_type_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Leave Type</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="leave_code" id="leave_code" class="form-control" required>
                                    <label class="form-label">Leave Code</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="leave_name" id="leave_name" class="form-control" required>
                                    <label class="form-label">Leave Name</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="days_per_year" id="days_per_year" class="form-control" required>
                                    <label class="form-label">Days Per Year</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="max_carry_forward" id="max_carry_forward" class="form-control" value="0">
                                    <label class="form-label">Max Carry Forward Days</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="checkbox" name="carry_forward" id="carry_forward" value="1" class="filled-in">
                                <label for="carry_forward">Allow Carry Forward</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="checkbox" name="is_paid" id="is_paid" value="1" class="filled-in" checked>
                                <label for="is_paid">Is Paid Leave</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
$(function () {
    $('.count-to').countTo();
    $('.js-basic-example').DataTable({
        responsive: true
    });

    // Leave Type Chart
    var ctx = document.getElementById('leaveTypeChart').getContext('2d');
    var leaveTypeChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Annual Leave', 'Medical Leave', 'Emergency Leave', 'Unpaid Leave'],
            datasets: [{
                data: [45, 30, 15, 10],
                backgroundColor: ['#2196F3', '#4CAF50', '#FF9800', '#F44336']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Monthly Trend Chart
    var ctx2 = document.getElementById('monthlyTrendChart').getContext('2d');
    var monthlyTrendChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Leave Applications',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#2196F3',
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Edit leave type
    $('.edit-leave-type').on('click', function() {
        var data = $(this).data();
        $('#leave_type_id').val(data.id);
        $('#leave_code').val(data.code);
        $('#leave_name').val(data.name);
        $('#days_per_year').val(data.days);
        $('#carry_forward').prop('checked', data.carry == 1);
        $('#max_carry_forward').val(data.maxCarry);
        $('#is_paid').prop('checked', data.paid == 1);
        $('#status').val(data.status);
        
        $('#leaveTypeModal').modal('show');
    });
});
</script>