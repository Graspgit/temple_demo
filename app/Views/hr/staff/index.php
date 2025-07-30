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
                        <h2>STAFF LIST</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('staff/create') ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">add</i> Add New Staff
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('staff/export') ?>" class="btn btn-success waves-effect">
                                    <i class="material-icons">file_download</i> Export
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('staff/expiry_report') ?>" class="btn btn-warning waves-effect">
                                    <i class="material-icons">warning</i> Document Expiry Report
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
                                        <th>Type</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($staff as $row): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['staff_code'] ?></td>
                                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                        <td>
                                            <span class="label <?= $row['staff_type'] == 'local' ? 'label-success' : 'label-warning' ?>">
                                                <?= ucfirst($row['staff_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= $row['department_name'] ?? '-' ?></td>
                                        <td><?= $row['designation_name'] ?? '-' ?></td>
                                        <td><?= $row['email'] ?? '-' ?></td>
                                        <td><?= $row['phone'] ?? '-' ?></td>
                                        <td><?= date('d-M-Y', strtotime($row['join_date'])) ?></td>
                                        <td>
                                            <span class="label <?= $row['status'] == 'active' ? 'label-success' : 'label-danger' ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('staff/view/'.$row['id']) ?>" class="btn btn-xs btn-info" title="View">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="<?= base_url('staff/edit/'.$row['id']) ?>" class="btn btn-xs btn-primary" title="Edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <?php if($row['status'] == 'active'): ?>
                                            <a href="<?= base_url('staff/delete/'.$row['id']) ?>" class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to deactivate this staff?')" title="Deactivate">
                                                <i class="material-icons">block</i>
                                            </a>
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

        <!-- Quick Stats -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL STAFF</div>
                        <div class="number count-to" data-from="0" data-to="<?= count($staff) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">location_city</i>
                    </div>
                    <div class="content">
                        <div class="text">LOCAL STAFF</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($staff, function($s) { return $s['staff_type'] == 'local'; })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">flight</i>
                    </div>
                    <div class="content">
                        <div class="text">FOREIGN STAFF</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($staff, function($s) { return $s['staff_type'] == 'foreigner'; })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">group_add</i>
                    </div>
                    <div class="content">
                        <div class="text">NEW THIS MONTH</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($staff, function($s) { return date('Y-m', strtotime($s['join_date'])) == date('Y-m'); })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function () {
    $('.js-basic-example').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search staff..."
        }
    });

    // Animated Count To
    $('.count-to').countTo();
});

// Close alert after 5 seconds
setTimeout(function() {
    $('.suc-alert, .alert').fadeOut('slow');
}, 5000);
</script>