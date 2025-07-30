<?php
$db = \Config\Database::connect();
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            SELECT EMPLOYEE FOR EA FORM
                            <small>Year <?= $year ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" onclick="generateAllEA()">
                                    <i class="material-icons">description</i> GENERATE ALL EA FORMS
                                </button>
                            </li>
                            <li>
                                <a href="<?= base_url('payroll/reports') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
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
                                        <th>Department</th>
                                        <th>Tax Number</th>
                                        <th>Total Income</th>
                                        <th>Total EPF</th>
                                        <th>Total PCB</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1; 
                                    foreach($staff_list as $staff): 
                                        // Get yearly totals for this staff
                                        $yearlyData = $db->table('payroll')
                                            ->select('SUM(gross_salary) as total_income, SUM(epf_employee) as total_epf, SUM(pcb) as total_pcb')
                                            ->where('staff_id', $staff['id'])
                                            ->where("payroll_month LIKE '{$year}%'")
                                            ->get()->getRowArray();
                                            
                                        // Get additional staff info
                                        $staffInfo = $db->table('staff_details')
                                            ->select('staff_statutory_details.income_tax_number, departments.department_name')
                                            ->join('staff_statutory_details', 'staff_statutory_details.staff_id = staff_details.id', 'left')
                                            ->join('departments', 'departments.id = staff_details.department_id', 'left')
                                            ->where('staff_details.id', $staff['id'])
                                            ->get()->getRowArray();
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $staff['staff_code'] ?></td>
                                        <td><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></td>
                                        <td><?= $staffInfo['department_name'] ?? '-' ?></td>
                                        <td><?= $staffInfo['income_tax_number'] ?? '-' ?></td>
                                        <td class="text-right">RM <?= number_format($yearlyData['total_income'] ?? 0, 2) ?></td>
                                        <td class="text-right">RM <?= number_format($yearlyData['total_epf'] ?? 0, 2) ?></td>
                                        <td class="text-right">RM <?= number_format($yearlyData['total_pcb'] ?? 0, 2) ?></td>
                                        <td>
                                            <a href="<?= base_url('payroll/eaReport?year=' . $year . '&staff_id=' . $staff['id']) ?>" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                <i class="material-icons">description</i> Generate EA
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-grey">
                                        <th colspan="5" class="text-right">TOTAL</th>
                                        <th class="text-right">
                                            <?php
                                            $totalIncome = $db->table('payroll')
                                                ->selectSum('gross_salary')
                                                ->where("payroll_month LIKE '{$year}%'")
                                                ->get()->getRowArray();
                                            echo 'RM ' . number_format($totalIncome['gross_salary'] ?? 0, 2);
                                            ?>
                                        </th>
                                        <th class="text-right">
                                            <?php
                                            $totalEPF = $db->table('payroll')
                                                ->selectSum('epf_employee')
                                                ->where("payroll_month LIKE '{$year}%'")
                                                ->get()->getRowArray();
                                            echo 'RM ' . number_format($totalEPF['epf_employee'] ?? 0, 2);
                                            ?>
                                        </th>
                                        <th class="text-right">
                                            <?php
                                            $totalPCB = $db->table('payroll')
                                                ->selectSum('pcb')
                                                ->where("payroll_month LIKE '{$year}%'")
                                                ->get()->getRowArray();
                                            echo 'RM ' . number_format($totalPCB['pcb'] ?? 0, 2);
                                            ?>
                                        </th>
                                        <th></th>
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

<script>
function generateAllEA() {
    if(confirm('This will generate EA forms for all employees. Continue?')) {
        window.open('<?= base_url("payroll/generateAllEa/" . $year) ?>', '_blank');
    }
}

</script>