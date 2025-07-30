<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Yearly Summary Cards -->
        <div class="row clearfix">
            <?php 
            $yearlyTotals = ['gross' => 0, 'net' => 0, 'epf' => 0, 'tax' => 0, 'staff' => 0];
            foreach($monthly_data as $month => $data) {
                if($data) {
                    $yearlyTotals['gross'] += $data['total_gross'];
                    $yearlyTotals['net'] += $data['total_net'];
                    $yearlyTotals['epf'] += $data['total_epf'];
                    $yearlyTotals['tax'] += $data['total_pcb'];
                    $yearlyTotals['staff'] = max($yearlyTotals['staff'], $data['staff_count']);
                }
            }
            ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL STAFF</div>
                        <div class="number"><?= $yearlyTotals['staff'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL GROSS</div>
                        <div class="number">RM <?= number_format($yearlyTotals['gross'], 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL NET</div>
                        <div class="number">RM <?= number_format($yearlyTotals['net'], 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL TAX</div>
                        <div class="number">RM <?= number_format($yearlyTotals['tax'], 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>MONTHLY PAYROLL TREND</h2>
                    </div>
                    <div class="body">
                        <canvas id="monthlyTrendChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>MONTHLY BREAKDOWN</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('payroll/export-yearly/' . $year) ?>" class="btn btn-success waves-effect">
                                    <i class="material-icons">file_download</i> EXPORT
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th class="text-center">Staff Count</th>
                                        <th class="text-right">Gross Salary</th>
                                        <th class="text-right">Total Deductions</th>
                                        <th class="text-right">Net Salary</th>
                                        <th class="text-right">EPF</th>
                                        <th class="text-right">SOCSO</th>
                                        <th class="text-right">PCB</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $months = ['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', 
                                               '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                               '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
                                    
                                    foreach($months as $monthNum => $monthName):
                                        $monthKey = $year . '-' . $monthNum;
                                        $data = $monthly_data[$monthKey] ?? null;
                                    ?>
                                    <tr>
                                        <td><?= $monthName ?></td>
                                        <?php if($data): ?>
                                            <td class="text-center"><?= $data['staff_count'] ?></td>
                                            <td class="text-right"><?= number_format($data['total_gross'], 2) ?></td>
                                            <td class="text-right"><?= number_format($data['total_deductions'], 2) ?></td>
                                            <td class="text-right"><strong><?= number_format($data['total_net'], 2) ?></strong></td>
                                            <td class="text-right"><?= number_format($data['total_epf'], 2) ?></td>
                                            <td class="text-right"><?= number_format($data['total_socso'], 2) ?></td>
                                            <td class="text-right"><?= number_format($data['total_pcb'], 2) ?></td>
                                            <td>
                                                <a href="<?= base_url('payroll/view/' . $monthKey) ?>" class="btn btn-xs btn-primary">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        <?php else: ?>
                                            <td colspan="7" class="text-center text-muted">No payroll generated</td>
                                            <td>-</td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Yearly Summary -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STAFF YEARLY SUMMARY</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" onclick="generateAllEA()">
                                    <i class="material-icons">description</i> GENERATE ALL EA FORMS
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
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th class="text-right">Total Basic</th>
                                        <th class="text-right">Total Gross</th>
                                        <th class="text-right">Total EPF</th>
                                        <th class="text-right">Total SOCSO</th>
                                        <th class="text-right">Total PCB</th>
                                        <th class="text-right">Total Net</th>
                                        <th>EA Form</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($staff_yearly as $staff): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $staff['staff_code'] ?></td>
                                        <td><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></td>
                                        <td class="text-right"><?= number_format($staff['total_basic'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_gross'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_epf'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_socso'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_pcb'], 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($staff['total_net'], 2) ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('payroll/ea-report?year=' . $year . '&staff_id=' . $staff['staff_id']) ?>" 
                                               class="btn btn-xs btn-info" target="_blank">
                                                <i class="material-icons">description</i> EA
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trend Chart
var ctx = document.getElementById('monthlyTrendChart').getContext('2d');
var monthlyData = <?= json_encode($monthly_data) ?>;
var labels = [];
var grossData = [];
var netData = [];

var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
for(var i = 0; i < 12; i++) {
    var monthKey = '<?= $year ?>-' + String(i + 1).padStart(2, '0');
    labels.push(months[i]);
    if(monthlyData[monthKey]) {
        grossData.push(monthlyData[monthKey].total_gross);
        netData.push(monthlyData[monthKey].total_net);
    } else {
        grossData.push(0);
        netData.push(0);
    }
}

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Gross Salary',
            data: grossData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Net Salary',
            data: netData,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'RM ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

function generateAllEA() {
    if(confirm('Generate EA forms for all staff?')) {
        window.location.href = '<?= base_url("payroll/generate-all-ea/" . $year) ?>';
    }
}
</script>