<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Report Actions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SOCSO CONTRIBUTION STATEMENT</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="window.print()"><i class="material-icons">print</i> Print</a></li>
                                    <li><a href="<?= base_url('payroll/socso-report?month=' . $month . '&format=csv') ?>"><i class="material-icons">grid_on</i> Export CSV</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= base_url('payroll/reports') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <!-- Company Info -->
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3>BORANG 8A - PERKESO</h3>
                                <h4>PENYATA CARUMAN BULANAN</h4>
                                <p>Bulan: <?= date('F Y', strtotime($month . '-01')) ?></p>
                            </div>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive m-t-20">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center">Bil</th>
                                        <th rowspan="2" class="text-center">No. PERKESO</th>
                                        <th rowspan="2" class="text-center">Nama Pekerja</th>
                                        <th rowspan="2" class="text-center">Gaji (RM)</th>
                                        <th colspan="3" class="text-center">Caruman (RM)</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Pekerja</th>
                                        <th class="text-center">Majikan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    $totalWages = 0;
                                    $totalEmployee = 0;
                                    $totalEmployer = 0;
                                    
                                    foreach($socso_data as $row): 
                                        $totalWages += $row['gross_salary'];
                                        $totalEmployee += $row['socso_employee'];
                                        $totalEmployer += $row['socso_employer'];
                                        $total = $row['socso_employee'] + $row['socso_employer'];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row['socso_number'] ?? '-' ?></td>
                                        <td><?= $row['staff_code'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                        <td class="text-right"><?= number_format($row['gross_salary'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['socso_employee'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['socso_employer'], 2) ?></td>
                                        <td class="text-right"><?= number_format($total, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-grey">
                                        <th colspan="3" class="text-right">JUMLAH BESAR</th>
                                        <th class="text-right"><?= number_format($totalWages, 2) ?></th>
                                        <th class="text-right"><?= number_format($totalEmployee, 2) ?></th>
                                        <th class="text-right"><?= number_format($totalEmployer, 2) ?></th>
                                        <th class="text-right"><?= number_format($totalEmployee + $totalEmployer, 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Summary Info -->
                        <div class="row m-t-30">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Bilangan Pekerja</td>
                                        <td class="text-right"><?= count($socso_data) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Gaji</td>
                                        <td class="text-right">RM <?= number_format($totalWages, 2) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Caruman Pekerja</td>
                                        <td class="text-right">RM <?= number_format($totalEmployee, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Caruman Majikan</td>
                                        <td class="text-right">RM <?= number_format($totalEmployer, 2) ?></td>
                                    </tr>
                                    <tr class="bg-grey">
                                        <th>Jumlah Caruman</th>
                                        <th class="text-right">RM <?= number_format($totalEmployee + $totalEmployer, 2) ?></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media print {
    .header-dropdown, .btn { display: none !important; }
    .card { border: none !important; }
}
</style>