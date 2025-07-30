<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Commission Stats -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="content">
                        <div class="text">CURRENT MONTH</div>
                        <div class="number">RM <?= number_format($current_month_total, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="content">
                        <div class="text">THIS YEAR</div>
                        <div class="number">RM <?= number_format($current_month_total * 12, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">STAFF ON COMMISSION</div>
                        <div class="number">12</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="content">
                        <div class="text">PENDING APPROVAL</div>
                        <div class="number">5</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>COMMISSION ACTIONS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('commissionhr/add') ?>" class="btn btn-block btn-lg btn-primary waves-effect">
                                    <i class="material-icons">add_circle</i> ADD COMMISSION
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('commissionhr/bulk') ?>" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">file_upload</i> BULK UPLOAD
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('commissionhr/settings') ?>" class="btn btn-block btn-lg btn-warning waves-effect">
                                    <i class="material-icons">settings</i> COMMISSION SETTINGS
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('commissionhr/report') ?>" class="btn btn-block btn-lg btn-success waves-effect">
                                    <i class="material-icons">assessment</i> REPORTS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>RECENT COMMISSION ENTRIES</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('commissionhr/history') ?>" class="btn btn-primary waves-effect">
                                    View All History
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Base Amount</th>
                                        <th>Commission %</th>
                                        <th>Commission Amount</th>
                                        <th>Reference</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_commissions as $comm): ?>
                                    <tr>
                                        <td><?= date('d-M-Y', strtotime($comm['commission_date'])) ?></td>
                                        <td><?= $comm['staff_code'] ?></td>
                                        <td><?= $comm['first_name'] . ' ' . $comm['last_name'] ?></td>
                                        <td><span class="label label-info"><?= $comm['commission_type'] ?></span></td>
                                        <td>RM <?= number_format($comm['base_amount'], 2) ?></td>
                                        <td><?= $comm['commission_percentage'] ? $comm['commission_percentage'] . '%' : '-' ?></td>
                                        <td><strong>RM <?= number_format($comm['commission_amount'], 2) ?></strong></td>
                                        <td><?= $comm['reference_no'] ?? '-' ?></td>
                                        <td>
                                            <a href="<?= base_url('commissionhr/edit/'.$comm['id']) ?>" class="btn btn-xs btn-primary" title="Edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="<?= base_url('commissionhr/delete/'.$comm['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure?')" 
                                               title="Delete">
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