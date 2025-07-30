<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><?= isset($record) ? 'Edit' : 'Add' ?> Warehouse</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory') ?>">Inventory</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory/warehouses') ?>">Warehouses</a></li>
                        <li class="breadcrumb-item active"><?= isset($record) ? 'Edit' : 'Add' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('inventory/warehouses/' . (isset($record) ? 'update/' . $record['id'] : 'store')) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title mb-4">Basic Information</h4>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name', isset($record) ? $record['name'] : '') ?>" 
                                           placeholder="e.g., Main Temple Store" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Warehouse Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="code" name="code" 
                                                   value="<?= old('code', isset($record) ? $record['code'] : '') ?>" 
                                                   placeholder="e.g., MTS-001" required>
                                            <small class="text-muted">Unique identifier (auto-generated if left empty)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Warehouse Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="">Select Type</option>
                                                <?php foreach ($warehouseTypes as $key => $label): ?>
                                                    <option value="<?= $key ?>" 
                                                            <?= old('type', isset($record) ? $record['type'] : '') == $key ? 'selected' : '' ?>>
                                                        <?= $label ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="<?= old('location', isset($record) ? $record['location'] : '') ?>" 
                                           placeholder="e.g., Ground Floor, North Wing">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Full Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" 
                                              placeholder="Complete address of the warehouse"><?= old('address', isset($record) ? $record['address'] : '') ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="card-title mb-4">Contact & Settings</h4>
                                
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                           value="<?= old('contact_person', isset($record) ? $record['contact_person'] : '') ?>" 
                                           placeholder="Name of warehouse in-charge">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" id="contact_number" name="contact_number" 
                                                   value="<?= old('contact_number', isset($record) ? $record['contact_number'] : '') ?>" 
                                                   placeholder="Mobile/Phone number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= old('email', isset($record) ? $record['email'] : '') ?>" 
                                                   placeholder="warehouse@temple.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="security_level" class="form-label">Security Level</label>
                                    <select class="form-control" id="security_level" name="security_level">
                                        <option value="">Select Security Level</option>
                                        <?php foreach ($securityLevels as $key => $label): ?>
                                            <option value="<?= $key ?>" 
                                                    <?= old('security_level', isset($record) ? $record['security_level'] : '') == $key ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Set high security for warehouses storing valuables</small>
                                </div>

                                <div class="mb-3">
                                    <label for="capacity_info" class="form-label">Capacity Information</label>
                                    <textarea class="form-control" id="capacity_info" name="capacity_info" rows="2" 
                                              placeholder="e.g., 500 sq.ft, 20 racks, cold storage available"><?= old('capacity_info', isset($record) ? $record['capacity_info'] : '') ?></textarea>
                                </div>

                                <hr>

                                <h5 class="mb-3">Warehouse Features</h5>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_default" 
                                               name="is_default" value="1"
                                               <?= old('is_default', isset($record) ? $record['is_default'] : 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_default">
                                            Set as Default Warehouse
                                        </label>
                                        <small class="text-muted d-block">New items will be added to this warehouse by default</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="temperature_controlled" 
                                               name="temperature_controlled" value="1"
                                               <?= old('temperature_controlled', isset($record) ? $record['temperature_controlled'] : 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="temperature_controlled">
                                            Temperature Controlled
                                        </label>
                                        <small class="text-muted d-block">For storing perishable items like prasadam ingredients</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_negative_stock" 
                                               name="allow_negative_stock" value="1"
                                               <?= old('allow_negative_stock', isset($record) ? $record['allow_negative_stock'] : 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="allow_negative_stock">
                                            Allow Negative Stock
                                        </label>
                                        <small class="text-muted d-block">Useful for tracking items given before recording receipt</small>
                                    </div>
                                </div>

                                <?php if (isset($record)): ?>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" 
                                                   name="is_active" value="1"
                                                   <?= old('is_active', $record['is_active']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_active">
                                                Active Status
                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="<?= base_url('inventory/warehouses') ?>" class="btn btn-secondary w-md">
                                <i class="bx bx-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary w-md">
                                <i class="bx bx-save me-1"></i> <?= isset($record) ? 'Update' : 'Save' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (!isset($record)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3">Temple Warehouse Guidelines</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Main Store</h6>
                            <ul class="small">
                                <li>Central storage for all non-perishable items</li>
                                <li>General supplies and equipment</li>
                                <li>Usually set as default warehouse</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Kitchen Store</h6>
                            <ul class="small">
                                <li>Food ingredients and cooking supplies</li>
                                <li>Should be temperature controlled</li>
                                <li>May allow negative stock for daily use items</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Pooja Items Store</h6>
                            <ul class="small">
                                <li>Sacred items, flowers, incense</li>
                                <li>High security for valuable items</li>
                                <li>Track expiry for flowers and perishables</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Auto-generate code based on name and type
    $('#name, #type').on('change', function() {
        if (!$('#code').val() && $('#name').val() && $('#type').val()) {
            var prefix = $('#type').val().substring(0, 3).toUpperCase();
            var namePart = $('#name').val().replace(/[^A-Za-z0-9]/g, '').substring(0, 5).toUpperCase();
            $('#code').val(prefix + '-' + namePart);
        }
    });

    // Show warning when changing default warehouse
    $('#is_default').on('change', function() {
        if ($(this).is(':checked')) {
            if (!confirm('This will remove the default status from other warehouses. Continue?')) {
                $(this).prop('checked', false);
            }
        }
    });
});
</script>