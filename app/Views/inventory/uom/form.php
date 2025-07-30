<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><?= isset($record) ? 'Edit' : 'Add' ?> Unit of Measure</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory') ?>">Inventory</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory/uom') ?>">Units of Measure</a></li>
                        <li class="breadcrumb-item active"><?= isset($record) ? 'Edit' : 'Add' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Unit Information</h4>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('inventory/uom/' . (isset($record) ? 'update/' . $record['id'] : 'store')) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Unit Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name', isset($record) ? $record['name'] : '') ?>" 
                                           placeholder="e.g., Kilogram" required>
                                    <small class="text-muted">Full name of the unit of measure</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="abbreviation" class="form-label">Abbreviation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="abbreviation" name="abbreviation" 
                                           value="<?= old('abbreviation', isset($record) ? $record['abbreviation'] : '') ?>" 
                                           placeholder="e.g., kg" required>
                                    <small class="text-muted">Short form to display in forms and reports</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $key => $label): ?>
                                            <option value="<?= $key ?>" 
                                                    <?= old('category', isset($record) ? $record['category'] : '') == $key ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Group similar units together</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="description" name="description" 
                                           value="<?= old('description', isset($record) ? $record['description'] : '') ?>" 
                                           placeholder="Optional description">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Conversion Settings</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="base_unit_id" class="form-label">Base Unit</label>
                                    <select class="form-control" id="base_unit_id" name="base_unit_id">
                                        <option value="">This is a base unit</option>
                                        <?php if (isset($baseUnits)): ?>
                                            <?php foreach ($baseUnits as $unit): ?>
                                                <option value="<?= $unit['id'] ?>" 
                                                        <?= old('base_unit_id', isset($record) ? $record['base_unit_id'] : '') == $unit['id'] ? 'selected' : '' ?>>
                                                    <?= $unit['name'] ?> (<?= $unit['abbreviation'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">Select if this unit converts from another unit</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="conversion_factor" class="form-label">Conversion Factor</label>
                                    <input type="number" step="any" class="form-control" id="conversion_factor" 
                                           name="conversion_factor" 
                                           value="<?= old('conversion_factor', isset($record) ? $record['conversion_factor'] : '') ?>" 
                                           placeholder="e.g., 1000" disabled>
                                    <small class="text-muted">How many base units equal 1 of this unit</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_fractional" 
                                               name="is_fractional" value="1"
                                               <?= old('is_fractional', isset($record) ? $record['is_fractional'] : 0) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_fractional">
                                            Allow Fractional Values
                                        </label>
                                    </div>
                                    <small class="text-muted">Check if this unit can have decimal values (e.g., 1.5 kg)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="decimal_places_group" style="display: none;">
                                    <label for="decimal_places" class="form-label">Decimal Places</label>
                                    <input type="number" class="form-control" id="decimal_places" name="decimal_places" 
                                           value="<?= old('decimal_places', isset($record) ? $record['decimal_places'] : 2) ?>" 
                                           min="0" max="4" placeholder="2">
                                    <small class="text-muted">Number of decimal places to display</small>
                                </div>
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

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-md">
                                <i class="bx bx-save me-1"></i> <?= isset($record) ? 'Update' : 'Save' ?>
                            </button>
                            <a href="<?= base_url('inventory/uom') ?>" class="btn btn-secondary w-md">
                                <i class="bx bx-x me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (!isset($record)): ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Common Units</h4>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Temple-Specific Units</h6>
                        <small>Consider adding these units commonly used in temples:</small>
                        <ul class="mb-0 mt-2">
                            <li><strong>Weight:</strong> Kilogram (kg), Gram (g), Tola</li>
                            <li><strong>Volume:</strong> Litre (L), Millilitre (ml)</li>
                            <li><strong>Count:</strong> Numbers (nos), Pieces (pcs), Dozen, Packet</li>
                            <li><strong>Prasadam:</strong> Plate, Cup, Bowl, Laddu</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Conversion Example</h6>
                        <small>
                            If creating "Gram" with "Kilogram" as base unit:<br>
                            - Base Unit: Kilogram<br>
                            - Conversion Factor: 0.001<br>
                            (Because 1 gram = 0.001 kilogram)
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Toggle conversion factor based on base unit selection
    $('#base_unit_id').on('change', function() {
        if ($(this).val()) {
            $('#conversion_factor').prop('disabled', false).prop('required', true);
        } else {
            $('#conversion_factor').prop('disabled', true).prop('required', false).val('');
        }
    });

    // Toggle decimal places based on fractional checkbox
    $('#is_fractional').on('change', function() {
        if ($(this).is(':checked')) {
            $('#decimal_places_group').show();
        } else {
            $('#decimal_places_group').hide();
            $('#decimal_places').val(0);
        }
    });

    // Initialize on page load
    $('#base_unit_id').trigger('change');
    $('#is_fractional').trigger('change');
});
</script>