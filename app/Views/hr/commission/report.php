<!-- hr/commissionhr/report.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Report Filter -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>COMMISSION REPORT FILTER</h2>
                    </div>
                    <div class="body">
                        <form method="GET" action="<?= base_url('commissionhr/report') ?>">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="from_date" class="form-control" value="<?= $from_date ?>" required>
                                            <label class="form-label">From Date</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="to_date" class="form-control" value="<?= $to_date ?>" required>
                                            <label class="form-label">To Date</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-effect">
                                            <i class="material-icons">search</i> GENERATE REPORT
                                        </button>
                                        <a href="<?= base_url('commission') ?>" class="btn btn-danger waves-effect">
                                            <i class="material-icons">arrow_back</i> BACK
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL COMMISSION</div>
                        <div class="number">RM <?= number_format($total_commission, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">STAFF COUNT</div>
                        <div class="number"><?= count(array_unique(array_column($report, 'staff_code'))) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="content">
                        <div class="text">TRANSACTIONS</div>
                        <div class="number"><?= array_sum(array_column($report, 'transaction_count')) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">trending_up</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL SALES</div>
                        <div class="number">RM <?= number_format(array_sum(array_column($report, 'total_base')), 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Details -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            COMMISSION REPORT
                            <small><?= date('d M Y', strtotime($from_date)) ?> to <?= date('d M Y', strtotime($to_date)) ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('commissionhr/export?from_date='.$from_date.'&to_date='.$to_date) ?>" 
                                   class="btn btn-success waves-effect">
                                    <i class="material-icons">file_download</i> Export
                                </a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-info waves-effect" onclick="window.print()">
                                    <i class="material-icons">print</i> Print
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover data-js-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Commission Type</th>
                                        <th>Transactions</th>
                                        <th>Total Base Amount</th>
                                        <th>Total Commission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($report as $row): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['staff_code'] ?></td>
                                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                        <td><span class="label label-info"><?= $row['commission_type'] ?></span></td>
                                        <td class="text-center"><?= $row['transaction_count'] ?></td>
                                        <td>RM <?= number_format($row['total_base'], 2) ?></td>
                                        <td><strong>RM <?= number_format($row['total_commission'], 2) ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('commissionhr/history?staff_id='.$row['staff_id'].'&from_date='.$from_date.'&to_date='.$to_date) ?>" 
                                               class="btn btn-xs btn-primary" 
                                               title="View Details">
                                                <i class="material-icons">visibility</i> Details
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">GRAND TOTAL:</th>
                                        <th class="text-center"><?= array_sum(array_column($report, 'transaction_count')) ?></th>
                                        <th>RM <?= number_format(array_sum(array_column($report, 'total_base')), 2) ?></th>
                                        <th><strong>RM <?= number_format($total_commission, 2) ?></strong></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Type Summary -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>COMMISSION BY TYPE</h2>
                    </div>
                    <div class="body">
                        <canvas id="commissionTypeChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>TOP 10 EARNERS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Staff</th>
                                        <th>Total Commission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $topEarners = $report;
                                    usort($topEarners, function($a, $b) {
                                        return $b['total_commission'] <=> $a['total_commission'];
                                    });
                                    $topEarners = array_slice($topEarners, 0, 10);
                                    $rank = 1;
                                    ?>
                                    <?php foreach($topEarners as $earner): ?>
                                    <tr>
                                        <td><?= $rank++ ?></td>
                                        <td><?= $earner['staff_code'] ?> - <?= $earner['first_name'] ?></td>
                                        <td><strong>RM <?= number_format($earner['total_commission'], 2) ?></strong></td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function() {
    $('.data-js-table').DataTable({
        responsive: true,
        pageLength: 50,
        order: [[6, 'desc']]
    });

    // Commission Type Chart
    var commissionTypes = {};
    <?php foreach($report as $row): ?>
        if (!commissionTypes['<?= $row['commission_type'] ?>']) {
            commissionTypes['<?= $row['commission_type'] ?>'] = 0;
        }
        commissionTypes['<?= $row['commission_type'] ?>'] += <?= $row['total_commission'] ?>;
    <?php endforeach; ?>

    var ctx = document.getElementById('commissionTypeChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(commissionTypes),
            datasets: [{
                data: Object.values(commissionTypes),
                backgroundColor: [
                    '#2196F3',
                    '#4CAF50',
                    '#FF9800',
                    '#F44336',
                    '#9C27B0',
                    '#00BCD4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': RM ' + context.parsed.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<style>
@media print {
    .header-dropdown, .btn, .form-group, .card .header ul {
        display: none !important;
    }
}
</style>