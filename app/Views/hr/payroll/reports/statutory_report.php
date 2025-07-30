<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Summary Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance</i>
                    </div>
                    <div class="content">
                        <div class="text">EPF TOTAL</div>
                        <div class="number">RM <?= number_format($totals['epf_employee'] + $totals['epf_employer'], 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">security</i>
                    </div>
                    <div class="content">
                        <div class="text">SOCSO TOTAL</div>
                        <div class="number">RM <?= number_format($totals['socso_employee'] + $totals['socso_employer'], 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">work</i>
                    </div>
                    <div class="content">
                        <div class="text">EIS TOTAL</div>
                        <div class="number">RM <?= number_format($totals['eis_employee'] + $totals['eis_employer'], 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="content">
                        <div class="text">PCB TOTAL</div>
                        <div class="number">RM <?= number_format($totals['pcb'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            STATUTORY CONTRIBUTION DETAILS
                            <small><?= date('F Y', strtotime($month . '-01')) ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" onclick="window.print()">
                                    <i class="material-icons">print</i> PRINT
                                </button>
                            </li>
                            <li>
                                <a href="<?= current_url() ?>?type=<?= $type ?>&format=csv" class="btn btn-success waves-effect">
                                    <i class="material-icons">file_download</i> EXPORT CSV
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Staff Code</th>
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">IC/Passport</th>
                                        <th colspan="3" class="text-center">EPF</th>
                                        <th colspan="3" class="text-center">SOCSO</th>
                                        <th colspan="3" class="text-center">EIS</th>
                                        <th rowspan="2" class="text-center">PCB</th>
                                    </tr>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Employer</th>
                                        <th>Total</th>
                                        <th>Employee</th>
                                        <th>Employer</th>
                                        <th>Total</th>
                                        <th>Employee</th>
                                        <th>Employer</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($statutory_data as $row): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['staff_code'] ?></td>
                                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                        <td><?= $row['epf_number'] ?? '-' ?></td>
                                        <td class="text-right"><?= number_format($row['epf_employee'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['epf_employer'], 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($row['epf_employee'] + $row['epf_employer'], 2) ?></strong></td>
                                        <td class="text-right"><?= number_format($row['socso_employee'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['socso_employer'], 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($row['socso_employee'] + $row['socso_employer'], 2) ?></strong></td>
                                        <td class="text-right"><?= number_format($row['eis_employee'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['eis_employer'], 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($row['eis_employee'] + $row['eis_employer'], 2) ?></strong></td>
                                        <td class="text-right"><?= number_format($row['pcb'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-grey">
                                        <th colspan="4">TOTAL</th>
                                        <th class="text-right"><?= number_format($totals['epf_employee'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['epf_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['epf_employee'] + $totals['epf_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['socso_employee'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['socso_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['socso_employee'] + $totals['socso_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['eis_employee'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['eis_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['eis_employee'] + $totals['eis_employer'], 2) ?></th>
                                        <th class="text-right"><?= number_format($totals['pcb'], 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    .header-dropdown, .sidebar, .navbar { display: none !important; }
    .content { margin-left: 0 !important; }
}
</style>