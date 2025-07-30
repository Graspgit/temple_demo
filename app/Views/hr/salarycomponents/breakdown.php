<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - <?= $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SALARY BREAKDOWN</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('salarycomponents/manage/' . $staff['id']) ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">edit</i> Manage Components
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>EARNINGS</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Basic Salary</strong></td>
                                        <td class="text-right">RM <?= number_format($staff['basic_salary'], 2) ?></td>
                                    </tr>
                                    <?php foreach($allowances as $allowance): ?>
                                    <?php 
                                        $amount = $allowance['percentage'] 
                                            ? ($staff['basic_salary'] * $allowance['percentage'] / 100) 
                                            : $allowance['amount'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $allowance['allowance_name'] ?>
                                            <?php if($allowance['percentage']): ?>
                                                <small class="text-muted">(<?= $allowance['percentage'] ?>%)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">RM <?= number_format($amount, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-light-green">
                                        <td><strong>Total Earnings</strong></td>
                                        <td class="text-right"><strong>RM <?= number_format($gross_salary, 2) ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>DEDUCTIONS</h4>
                                <table class="table table-bordered">
                                    <?php foreach($deductions as $deduction): ?>
                                    <?php 
                                        $amount = $deduction['percentage'] 
                                            ? ($staff['basic_salary'] * $deduction['percentage'] / 100) 
                                            : $deduction['amount'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $deduction['deduction_name'] ?>
                                            <?php if($deduction['percentage']): ?>
                                                <small class="text-muted">(<?= $deduction['percentage'] ?>%)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">RM <?= number_format($amount, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-red">
                                        <td><strong>Total Deductions</strong></td>
                                        <td class="text-right"><strong>RM <?= number_format($total_deductions, 2) ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="well text-center">
                                    <h3>Estimated Net Salary: <span class="text-primary">RM <?= number_format($net_salary, 2) ?></span></h3>
                                    <p class="text-muted">*This is before statutory deductions (EPF, SOCSO, EIS, PCB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>