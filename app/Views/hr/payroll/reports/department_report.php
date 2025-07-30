<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Department Summary Cards -->
        <div class="row clearfix">
            <?php foreach($departments as $dept): if($dept['total_staff'] > 0): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-<?= ['pink', 'cyan', 'light-green', 'orange', 'deep-purple', 'teal'][array_rand(['pink', 'cyan', 'light-green', 'orange', 'deep-purple', 'teal'])] ?>">
                        <div class="font-bold m-b--35"><?= strtoupper($dept['department_name']) ?></div>
                        <ul class="dashboard-stat-list">
                            <li>Staff: <?= $dept['total_staff'] ?></li>
                            <li>Gross: RM <?= number_format($dept['total_gross'], 0) ?></li>
                            <li>Net: RM <?= number_format($dept['total_net'], 0) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; endforeach; ?>
        </div>

        <!-- Department Comparison Chart -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PAYROLL DISTRIBUTION BY DEPARTMENT</h2>
                    </div>
                    <div class="body">
                        <canvas id="deptPieChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>AVERAGE SALARY BY DEPARTMENT</h2>
                    </div>
                    <div class="body">
                        <canvas id="avgSalaryChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Details -->
        <?php foreach($departments as $dept): if($dept['total_staff'] > 0): ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= strtoupper($dept['department_name']) ?>
                            <small>Month: <?= date('F Y', strtotime($month . '-01')) ?></small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Total Staff:</strong> <?= $dept['total_staff'] ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Total Gross:</strong> RM <?= number_format($dept['total_gross'], 2) ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Total Net:</strong> RM <?= number_format($dept['total_net'], 2) ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Average Salary:</strong> RM <?= $dept['total_staff'] > 0 ? number_format($dept['total_gross'] / $dept['total_staff'], 2) : '0.00' ?></p>
                            </div>
                        </div>
                        
                        <?php if(!empty($dept['staff'])): ?>
                        <div class="table-responsive m-t-20">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th class="text-right">Basic Salary</th>
                                        <th class="text-right">Allowances</th>
                                        <th class="text-right">Gross Salary</th>
                                        <th class="text-right">Deductions</th>
                                        <th class="text-right">Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dept['staff'] as $staff): ?>
                                    <tr>
                                        <td><?= $staff['staff_code'] ?></td>
                                        <td><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></td>
                                        <td class="text-right"><?= number_format($staff['basic_salary'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_allowances'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['gross_salary'], 2) ?></td>
                                        <td class="text-right"><?= number_format($staff['total_deductions'] + $staff['epf_employee'] + $staff['socso_employee'] + $staff['eis_employee'] + $staff['pcb'], 2) ?></td>
                                        <td class="text-right"><strong><?= number_format($staff['net_salary'], 2) ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; endforeach; ?>

        <!-- Export Options -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body text-center">
                        <a href="<?= current_url() ?>?month=<?= $month ?>&format=pdf" class="btn btn-primary waves-effect m-r-20">
                            <i class="material-icons">picture_as_pdf</i> EXPORT PDF
                        </a>
                        <a href="<?= current_url() ?>?month=<?= $month ?>&format=excel" class="btn btn-success waves-effect">
                            <i class="material-icons">grid_on</i> EXPORT EXCEL
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare data for charts
var departments = <?= json_encode($departments) ?>;
var deptNames = [];
var deptTotals = [];
var avgSalaries = [];

departments.forEach(function(dept) {
    if(dept.total_staff > 0) {
        deptNames.push(dept.department_name || 'No Department');
        deptTotals.push(parseFloat(dept.total_gross));
        avgSalaries.push(dept.total_gross / dept.total_staff);
    }
});

// Pie Chart - Payroll Distribution
new Chart(document.getElementById('deptPieChart'), {
    type: 'pie',
    data: {
        labels: deptNames,
        datasets: [{
            data: deptTotals,
            backgroundColor: [
                '#E91E63', '#00BCD4', '#8BC34A', '#FF9800', 
                '#673AB7', '#009688', '#3F51B5', '#FFEB3B'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        var label = context.label || '';
                        var value = 'RM ' + context.parsed.toLocaleString();
                        var percentage = ((context.parsed / context.dataset.data.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Bar Chart - Average Salary
new Chart(document.getElementById('avgSalaryChart'), {
    type: 'bar',
    data: {
        labels: deptNames,
        datasets: [{
            label: 'Average Salary',
            data: avgSalaries,
            backgroundColor: '#2196F3'
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
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Average: RM ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>