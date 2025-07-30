<!-- hr/payroll/generate.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>GENERATE MONTHLY PAYROLL</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('payroll') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('payroll/process_generation') ?>" onsubmit="return confirmGeneration()">
                            <?= csrf_field() ?>
                            
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Month <span class="text-danger">*</span></label>
                                        <input type="month" name="payroll_month" class="form-control" value="<?= $current_month ?>" max="<?= $current_month ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="alert alert-info">
                                        <strong>Note:</strong>
                                        <ul>
                                            <li>Payroll will be generated for all active employees</li>
                                            <li>EPF, SOCSO, EIS, and PCB will be calculated automatically based on settings</li>
                                            <li>Commission for the month will be included if applicable</li>
                                            <li>Once generated, payroll cannot be regenerated for the same month</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary waves-effect">
                                        <i class="material-icons">check_circle</i> GENERATE PAYROLL
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>