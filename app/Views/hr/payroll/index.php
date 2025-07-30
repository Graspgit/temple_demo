<!-- hr/payroll/index.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Payroll Stats -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <div class="content">
                        <div class="text">CURRENT MONTH PAYROLL</div>
                        <div class="number">RM <?= number_format($monthly_summary[date('Y-m')]['total_gross'] ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL EMPLOYEES</div>
                        <div class="number"><?= $monthly_summary[date('Y-m')]['total_staff'] ?? 0 ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">hourglass_empty</i>
                    </div>
                    <div class="content">
                        <div class="text">PENDING PAYMENTS</div>
                        <div class="number"><?= $monthly_summary[date('Y-m')]['approved_count'] ?? 0 ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="content">
                        <div class="text">PAID THIS MONTH</div>
                        <div class="number">RM <?= number_format($monthly_summary[date('Y-m')]['paid_amount'] ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PAYROLL ACTIONS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('payroll/generate') ?>" class="btn btn-block btn-lg btn-primary waves-effect">
                                    <i class="material-icons">add_circle</i> GENERATE PAYROLL
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('payroll/payment') ?>" class="btn btn-block btn-lg btn-success waves-effect">
                                    <i class="material-icons">payment</i> PROCESS PAYMENTS
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('payroll/reports') ?>" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">assessment</i> PAYROLL REPORTS
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('statutorysettings/statutory') ?>" class="btn btn-block btn-lg btn-warning waves-effect">
                                    <i class="material-icons">settings</i> STATUTORY SETTINGS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Payroll Summary -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>MONTHLY PAYROLL SUMMARY</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total Staff</th>
                                        <th>Gross Salary</th>
                                        <th>Net Salary</th>
                                        <th>Pending</th>
                                        <th>Approved</th>
                                        <th>Paid</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($monthly_summary as $month => $summary): ?>
                                    <tr>
                                        <td><?= date('F Y', strtotime($month . '-01')) ?></td>
                                        <td><?= $summary['total_staff'] ?? 0 ?></td>
                                        <td>RM <?= number_format($summary['total_gross'] ?? 0, 2) ?></td>
                                        <td>RM <?= number_format($summary['total_net'] ?? 0, 2) ?></td>
                                        <td>
                                            <?php if(($summary['pending_count'] ?? 0) > 0): ?>
                                                <span class="label label-warning"><?= $summary['pending_count'] ?></span>
                                            <?php else: ?>
                                                <span class="label label-default">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(($summary['approved_count'] ?? 0) > 0): ?>
                                                <span class="label label-primary"><?= $summary['approved_count'] ?></span>
                                            <?php else: ?>
                                                <span class="label label-default">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(($summary['paid_count'] ?? 0) > 0): ?>
                                                <span class="label label-success"><?= $summary['paid_count'] ?></span>
                                            <?php else: ?>
                                                <span class="label label-default">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($summary['total_staff'] > 0): ?>
                                                <a href="<?= base_url('payroll/view/' . $month) ?>" class="btn btn-sm btn-info">
                                                    <i class="material-icons">visibility</i> View
                                                </a>
                                                <?php if(($summary['approved_count'] ?? 0) > 0): ?>
                                                    <a href="<?= base_url('payroll/payment/' . $month) ?>" class="btn btn-sm btn-success">
                                                        <i class="material-icons">payment</i> Pay
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="label label-default">Not Generated</span>
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
    </div>
</section>