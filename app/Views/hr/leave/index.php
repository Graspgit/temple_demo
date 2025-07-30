<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Leave Stats -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">pending_actions</i>
                    </div>
                    <div class="content">
                        <div class="text">PENDING APPROVAL</div>
                        <div class="number count-to" data-from="0" data-to="<?= $pending_leaves ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">event_available</i>
                    </div>
                    <div class="content">
                        <div class="text">APPROVED THIS MONTH</div>
                        <div class="number count-to" data-from="0" data-to="<?= $approved_leaves ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people_outline</i>
                    </div>
                    <div class="content">
                        <div class="text">ON LEAVE TODAY</div>
                        <div class="number count-to" data-from="0" data-to="5" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">calendar_today</i>
                    </div>
                    <div class="content">
                        <div class="text">UPCOMING LEAVES</div>
                        <div class="number count-to" data-from="0" data-to="12" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LEAVE MANAGEMENT ACTIONS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('leave/apply') ?>" class="btn btn-block btn-lg btn-primary waves-effect">
                                    <i class="material-icons">add_circle</i> APPLY LEAVE
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('leave/applications') ?>" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">list</i> APPLICATIONS
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('leave/allocation') ?>" class="btn btn-block btn-lg btn-warning waves-effect">
                                    <i class="material-icons">assignment</i> ALLOCATION
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('leave/balance_report') ?>" class="btn btn-block btn-lg btn-success waves-effect">
                                    <i class="material-icons">assessment</i> REPORTS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leave Applications -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>RECENT LEAVE APPLICATIONS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('leave/applications') ?>" class="btn btn-primary waves-effect">
                                    View All
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date Applied</th>
                                        <th>Staff</th>
                                        <th>Leave Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_applications as $app): ?>
                                    <tr>
                                        <td><?= date('d-M-Y', strtotime($app['created_at'])) ?></td>
                                        <td><?= $app['first_name'] . ' ' . $app['last_name'] ?></td>
                                        <td><span class="label label-info"><?= $app['leave_name'] ?></span></td>
                                        <td><?= date('d-M-Y', strtotime($app['from_date'])) ?></td>
                                        <td><?= date('d-M-Y', strtotime($app['to_date'])) ?></td>
                                        <td><?= $app['days'] ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'label-warning',
                                                'approved' => 'label-success',
                                                'rejected' => 'label-danger',
                                                'cancelled' => 'label-default'
                                            ];
                                            ?>
                                            <span class="label <?= $statusClass[$app['status']] ?>">
                                                <?= ucfirst($app['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('leave/applications?status='.$app['status']) ?>" class="btn btn-xs btn-info">
                                                <i class="material-icons">visibility</i>
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

        <!-- Leave Summary Charts -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LEAVE TYPE DISTRIBUTION</h2>
                    </div>
                    <div class="body">
                        <canvas id="leaveTypeChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>MONTHLY LEAVE TREND</h2>
                    </div>
                    <div class="body">
                        <canvas id="monthlyTrendChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>