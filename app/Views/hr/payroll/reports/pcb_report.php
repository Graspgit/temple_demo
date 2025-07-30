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
                        <h2>PCB DEDUCTION STATEMENT</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="window.print()"><i class="material-icons">print</i> Print</a></li>
                                    <li><a href="<?= base_url('payroll/pcb-report?month=' . $month . '&format=csv') ?>"><i class="material-icons">grid_on</i> Export CSV</a></li>
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
                                <h3>CP39 - CUKAI PENDAPATAN</h3>
                                <h4>PENYATA POTONGAN CUKAI BULANAN</h4>
                                <p>Bulan: <?= date('F Y', strtotime($month . '-01')) ?></p>
                            </div>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive m-t-20">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">No. Cukai Pendapatan</th>
                                        <th class="text-center">Nama Pekerja</th>
                                        <th class="text-center">Kod PCB</th>
                                        <th class="text-center">Gaji Kasar (RM)</th>
                                        <th class="text-center">Potongan PCB (RM)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    $totalGross = 0;
                                    $totalPCB = 0;
                                    
                                    foreach($pcb_data as $row): 
                                        $totalGross += $row['gross_salary'];
                                        $totalPCB += $row['pcb'];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row['income_tax_number'] ?? '-' ?></td>
                                        <td><?= $row['staff_code'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                        <td class="text-center"><?= $row['pcb_code'] ?? '-' ?></td>
                                        <td class="text-right"><?= number_format($row['gross_salary'], 2) ?></td>
                                        <td class="text-right"><?= number_format($row['pcb'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-grey">
                                        <th colspan="4" class="text-right">JUMLAH BESAR</th>
                                        <th class="text-right"><?= number_format($totalGross, 2) ?></th>
                                        <th class="text-right"><?= number_format($totalPCB, 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Summary Info -->
                        <div class="row m-t-30">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong>Ringkasan:</strong>
                                    <ul class="m-t-10">
                                        <li>Bilangan Pekerja: <strong><?= count($pcb_data) ?></strong></li>
                                        <li>Jumlah Gaji Kasar: <strong>RM <?= number_format($totalGross, 2) ?></strong></li>
                                        <li>Jumlah Potongan PCB: <strong>RM <?= number_format($totalPCB, 2) ?></strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Arahan Pembayaran</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p>Sila pastikan pembayaran dibuat sebelum atau pada 15hb bulan berikutnya.</p>
                                        <p>Kaedah pembayaran: Online Banking / Cek / Tunai di cawangan LHDN</p>
                                    </div>
                                </div>
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