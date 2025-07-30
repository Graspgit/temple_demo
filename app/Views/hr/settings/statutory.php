<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>MALAYSIAN STATUTORY SETTINGS</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/epf') ?>" class="btn btn-block btn-lg btn-primary waves-effect">
                                    <i class="material-icons">account_balance</i>
                                    <span>EPF SETTINGS</span>
                                    <small>Employees Provident Fund</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/socso') ?>" class="btn btn-block btn-lg btn-info waves-effect">
                                    <i class="material-icons">security</i>
                                    <span>SOCSO SETTINGS</span>
                                    <small>Social Security Organization</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/eis') ?>" class="btn btn-block btn-lg btn-warning waves-effect">
                                    <i class="material-icons">work</i>
                                    <span>EIS SETTINGS</span>
                                    <small>Employment Insurance System</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/pcb') ?>" class="btn btn-block btn-lg btn-success waves-effect">
                                    <i class="material-icons">receipt</i>
                                    <span>PCB SETTINGS</span>
                                    <small>Monthly Tax Deduction</small>
                                </a>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/allowances') ?>" class="btn btn-block btn-lg bg-purple waves-effect">
                                    <i class="material-icons">add_circle</i>
                                    <span>ALLOWANCES</span>
                                    <small>Allowance Types</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/deductions') ?>" class="btn btn-block btn-lg bg-pink waves-effect">
                                    <i class="material-icons">remove_circle</i>
                                    <span>DEDUCTIONS</span>
                                    <small>Deduction Types</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/departments') ?>" class="btn btn-block btn-lg bg-cyan waves-effect">
                                    <i class="material-icons">business</i>
                                    <span>DEPARTMENTS</span>
                                    <small>Department Management</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/designations') ?>" class="btn btn-block btn-lg bg-orange waves-effect">
                                    <i class="material-icons">assignment_ind</i>
                                    <span>DESIGNATIONS</span>
                                    <small>Position Management</small>
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= base_url('statutorysettings/accountSettings') ?>" class="btn btn-block btn-lg bg-deep-purple waves-effect">
                                    <i class="material-icons">account_balance_wallet</i>
                                    <span>ACCOUNT SETTINGS</span>
                                    <small>Payroll Account Mapping</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">info</i>
                    </div>
                    <div class="content">
                        <div class="text">EPF RATES</div>
                        <div class="number">Employee: 11%<br>Employer: 13%</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">info</i>
                    </div>
                    <div class="content">
                        <div class="text">SOCSO CATEGORY</div>
                        <div class="number">Employment Injury<br>Invalidity Scheme</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">info</i>
                    </div>
                    <div class="content">
                        <div class="text">EIS CONTRIBUTION</div>
                        <div class="number">Employee: 0.2%<br>Employer: 0.2%</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">info</i>
                    </div>
                    <div class="content">
                        <div class="text">PCB CATEGORIES</div>
                        <div class="number">Category 1, 2, 3<br>Based on Status</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>