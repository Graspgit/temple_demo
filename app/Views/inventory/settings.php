<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>INVENTORY GENERAL SETTINGS</h2>
                    </div>
                    <div class="body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert suc-alert">
                                <?= session()->getFlashdata('success') ?>
                                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert">
                                <?= session()->getFlashdata('error') ?>
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('inventory-settings/update') ?>">
                            <?= csrf_field() ?>
                            
                            <h4>Accounting Integration</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Enable Automatic Account Migration *</label>
                                            <select name="is_account_migration" class="form-control show-tick" required>
                                                <option value="0" <?= (!$settings || $settings['is_account_migration'] == 0) ? 'selected' : '' ?>>
                                                    Disabled
                                                </option>
                                                <option value="1" <?= ($settings && $settings['is_account_migration'] == 1) ? 'selected' : '' ?>>
                                                    Enabled
                                                </option>
                                            </select>
                                            <small>When enabled, purchase invoices and payments will automatically create accounting entries</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="accounting_settings" style="display: <?= ($settings && $settings['is_account_migration'] == 1) ? 'block' : 'none' ?>;">
                                <h4>Default Ledger Groups</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Default Purchase Ledger Group</label>
                                                <select name="purchase_ledger_group_id" class="form-control show-tick">
                                                    <option value="">-- Select Ledger Group --</option>
                                                    <?php foreach ($ledger_groups as $group): ?>
                                                        <option value="<?= $group['id'] ?>" 
                                                                <?= ($settings && $settings['purchase_ledger_group_id'] == $group['id']) ? 'selected' : '' ?>>
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Default Sales Ledger Group</label>
                                                <select name="sales_ledger_group_id" class="form-control show-tick">
                                                    <option value="">-- Select Ledger Group --</option>
                                                    <?php foreach ($ledger_groups as $group): ?>
                                                        <option value="<?= $group['id'] ?>" 
                                                                <?= ($settings && $settings['sales_ledger_group_id'] == $group['id']) ? 'selected' : '' ?>>
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Parent Ledger for Products</label>
                                                <select name="product_ledger_parent_id" class="form-control show-tick">
                                                    <option value="">-- Select Parent Ledger --</option>
                                                    <?php foreach ($ledger_groups as $group): ?>
                                                        <option value="<?= $group['id'] ?>" 
                                                                <?= ($settings && $settings['product_ledger_parent_id'] == $group['id']) ? 'selected' : '' ?>>
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small>Products will be created under: Assets → Current Assets → Products</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Parent Ledger for Raw Materials</label>
                                                <select name="raw_material_ledger_parent_id" class="form-control show-tick">
                                                    <option value="">-- Select Parent Ledger --</option>
                                                    <?php foreach ($ledger_groups as $group): ?>
                                                        <option value="<?= $group['id'] ?>" 
                                                                <?= ($settings && $settings['raw_material_ledger_parent_id'] == $group['id']) ? 'selected' : '' ?>>
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small>Raw materials will be created under: Assets → Current Assets → Raw Materials</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">save</i> Save Settings
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

<script>
$(document).ready(function() {
    $('select[name="is_account_migration"]').on('change', function() {
        if ($(this).val() == '1') {
            $('#accounting_settings').slideDown();
        } else {
            $('#accounting_settings').slideUp();
        }
    });
});
</script>