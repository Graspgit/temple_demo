<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - <?= $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row clearfix">
            <!-- Staff Info -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>STAFF INFORMATION</h2>
                    </div>
                    <div class="body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <strong>Staff Code:</strong> <?= $staff['staff_code'] ?>
                            </div>
                            <div class="list-group-item">
                                <strong>Name:</strong> <?= $staff['first_name'] . ' ' . $staff['last_name'] ?>
                            </div>
                            <div class="list-group-item">
                                <strong>Basic Salary:</strong> RM <?= number_format($staff['basic_salary'], 2) ?>
                            </div>
                            <div class="list-group-item">
                                <strong>Staff Type:</strong> <?= ucfirst($staff['staff_type']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Allowances -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2>ALLOWANCES</h2>
                    </div>
                    <div class="body">
                        <!-- Current Allowances -->
                        <?php if(!empty($current_allowances)): ?>
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>Allowance</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($current_allowances as $allowance): ?>
                                    <tr>
                                        <td><?= $allowance['allowance_name'] ?></td>
                                        <td>
                                            <?php if($allowance['percentage']): ?>
                                                <?= $allowance['percentage'] ?>%
                                            <?php else: ?>
                                                RM <?= number_format($allowance['amount'], 2) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-warning" onclick="editComponent(<?= $allowance['id'] ?>, 'allowance')" title="Edit">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('salarycomponents/remove/' . $allowance['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Remove this allowance?')" title="Remove">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">No allowances assigned</p>
                        <?php endif; ?>

                        <!-- Add New Allowance -->
                        <hr>
                        <button class="btn btn-success btn-block" onclick="showAddModal('allowance')">
                            <i class="material-icons">add</i> Add Allowance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-red">
                        <h2>DEDUCTIONS</h2>
                    </div>
                    <div class="body">
                        <!-- Current Deductions -->
                        <?php if(!empty($current_deductions)): ?>
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>Deduction</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($current_deductions as $deduction): ?>
                                    <tr>
                                        <td><?= $deduction['deduction_name'] ?></td>
                                        <td>
                                            <?php if($deduction['percentage']): ?>
                                                <?= $deduction['percentage'] ?>%
                                            <?php else: ?>
                                                RM <?= number_format($deduction['amount'], 2) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-warning" onclick="editComponent(<?= $deduction['id'] ?>, 'deduction')" title="Edit">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('salarycomponents/remove/' . $deduction['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Remove this deduction?')" title="Remove">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">No deductions assigned</p>
                        <?php endif; ?>

                        <!-- Add New Deduction -->
                        <hr>
                        <button class="btn btn-danger btn-block" onclick="showAddModal('deduction')">
                            <i class="material-icons">add</i> Add Deduction
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Component Modal -->
<div class="modal fade" id="addComponentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add <span id="componentTypeTitle"></span></h4>
            </div>
            <form method="POST" action="<?= base_url('salarycomponents/add') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                <input type="hidden" name="component_type" id="component_type">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select <span class="componentTypeLabel"></span></label>
                        <select name="component_id" id="component_select" class="form-control" required>
                            <option value="">-- Select --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Type</label>
                        <select id="amount_type" class="form-control" onchange="toggleAmountFields()">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="amount_field">
                        <label>Amount (RM)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0">
                    </div>
                    
                    <div class="form-group" id="percentage_field" style="display: none;">
                        <label>Percentage (%)</label>
                        <input type="number" name="percentage" class="form-control" step="0.01" min="0" max="100">
                    </div>
                    
                    <div class="form-group">
                        <label>Effective Date</label>
                        <input type="date" name="effective_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>End Date (Optional)</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">SAVE</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAddModal(type) {
    $('#component_type').val(type);
    $('#componentTypeTitle').text(type.charAt(0).toUpperCase() + type.slice(1));
    $('.componentTypeLabel').text(type.charAt(0).toUpperCase() + type.slice(1));
    
    // Populate select options
    var options = '<option value="">-- Select --</option>';
    <?php if($allowances): ?>
        <?php foreach($allowances as $allowance): ?>
            if(type === 'allowance') {
                options += '<option value="<?= $allowance['id'] ?>"><?= $allowance['allowance_name'] ?></option>';
            }
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if($deductions): ?>
        <?php foreach($deductions as $deduction): ?>
            if(type === 'deduction') {
                options += '<option value="<?= $deduction['id'] ?>"><?= $deduction['deduction_name'] ?></option>';
            }
        <?php endforeach; ?>
    <?php endif; ?>
    
    $('#component_select').html(options);
    $('#component_select').selectpicker('refresh');
    $('#addComponentModal').modal('show');
}

function toggleAmountFields() {
    var type = $('#amount_type').val();
    if(type === 'fixed') {
        $('#amount_field').show();
        $('#percentage_field').hide();
        $('input[name="percentage"]').val('');
    } else {
        $('#amount_field').hide();
        $('#percentage_field').show();
        $('input[name="amount"]').val('');
    }
}

function editComponent(id, type) {
    // Implementation for edit functionality
    alert('Edit functionality to be implemented');
}
</script>