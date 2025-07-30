<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>CONFIGURE ACCOUNT SETTINGS FOR PAYROLL</h2>
                        <small>Map statutory deductions and salary accounts to ledgers</small>
                    </div>
                    <div class="body">
                        <div class="alert alert-info">
                            <strong>Note:</strong> 
                            <ul>
                                <li>EPF, SOCSO, EIS, PCB, and Deduction accounts should be selected from <strong>Liability (2000)</strong> group accounts including:
                                    <ul>
                                        <li>Current Liabilities (2100)</li>
                                        <li>Long Term Liabilities (2200)</li>
                                        <li>Other Liabilities (2300)</li>
                                    </ul>
                                </li>
                                <li>Salary Expense account should be selected from:
                                    <ul>
                                        <li><strong>Direct Cost (5000)</strong> group accounts OR</li>
                                        <li><strong>Expenses (6000)</strong> group accounts</li>
                                    </ul>
                                </li>
                                <li>Each staff member will have their own payable ledger created automatically under the Accrual group</li>
                            </ul>
                        </div>
                        
                        <form method="POST" action="<?= base_url('statutorysettings/saveAccountSettings') ?>">
                            <?php if(isset($settings['id'])): ?>
                                <input type="hidden" name="id" value="<?= $settings['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>EPF Ledger Account <small class="text-muted">(Liability Account)</small></label>
                                        <select name="epf_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select EPF Ledger --</option>
                                            <?php foreach($liability_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['epf_ledger_id']) && $settings['epf_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>SOCSO Ledger Account <small class="text-muted">(Liability Account)</small></label>
                                        <select name="socso_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select SOCSO Ledger --</option>
                                            <?php foreach($liability_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['socso_ledger_id']) && $settings['socso_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>EIS Ledger Account <small class="text-muted">(Liability Account)</small></label>
                                        <select name="eis_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select EIS Ledger --</option>
                                            <?php foreach($liability_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['eis_ledger_id']) && $settings['eis_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PCB (Income Tax) Ledger Account <small class="text-muted">(Liability Account)</small></label>
                                        <select name="pcb_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select PCB Ledger --</option>
                                            <?php foreach($liability_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['pcb_ledger_id']) && $settings['pcb_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Deduction Ledger Account <small class="text-muted">(Liability Account)</small></label>
                                        <select name="deduction_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select Deduction Ledger --</option>
                                            <?php foreach($liability_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['deduction_ledger_id']) && $settings['deduction_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary Expense Ledger Account <small class="text-muted">(Expense/Direct Cost Account)</small></label>
                                        <select name="salary_expense_ledger_id" class="form-control select2" required>
                                            <option value="">-- Select Salary Expense Ledger --</option>
                                            <?php foreach($expense_ledgers as $ledger): ?>
                                                <option value="<?= $ledger['id'] ?>" 
                                                    <?= (isset($settings['salary_expense_ledger_id']) && $settings['salary_expense_ledger_id'] == $ledger['id']) ? 'selected' : '' ?>>
                                                    <?= $ledger['display_name'] ?> (<?= $ledger['group_name'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="checkbox" id="is_account_migrate" name="is_account_migrate" value="1" 
                                            <?= (isset($settings['is_account_migrate']) && $settings['is_account_migrate'] == 1) ? 'checked' : '' ?>>
                                        <label for="is_account_migrate">
                                            Enable Automatic Account Migration
                                            <small class="text-muted">(When enabled, approved payslips will automatically create journal entries, and paid payslips will create payment entries)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> SAVE SETTINGS
                                    </button>
                                    <a href="<?= base_url('statutorysettings/statutory') ?>" class="btn btn-default waves-effect">
                                        <i class="material-icons">arrow_back</i> BACK
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Initialize Select2 for better dropdown experience
    $('.select2').select2({
        placeholder: "Select a ledger",
        allowClear: true,
        width: '100%'
    });
});
</script>