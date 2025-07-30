<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>BULK SALARY COMPONENT ASSIGNMENT</h2>
                        <small>Assign allowances or deductions to multiple staff members at once</small>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('salarycomponents/processBulk') ?>" onsubmit="return confirmBulkAssignment()">
                            <?= csrf_field() ?>
                            
                            <div class="row clearfix">
                                <!-- Component Selection -->
                                <div class="col-md-6">
                                    <h4>1. SELECT COMPONENT</h4>
                                    <div class="form-group">
                                        <label>Component Type</label>
                                        <select name="component_type" id="component_type" class="form-control" onchange="loadComponents()" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="allowance">Allowance</option>
                                            <option value="deduction">Deduction</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Component</label>
                                        <select name="component_id" id="component_id" class="form-control" required>
                                            <option value="">-- Select Component --</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Amount Type</label>
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
                                
                                <!-- Staff Selection Criteria -->
                                <div class="col-md-6">
                                    <h4>2. SELECT STAFF CRITERIA</h4>
                                    <div class="alert alert-info">
                                        <i class="material-icons">info</i> 
                                        Leave fields empty to include all staff. The component will be assigned to all staff matching the selected criteria.
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select name="department_id" class="form-control">
                                            <option value="">-- All Departments --</option>
                                            <?php foreach($departments as $dept): ?>
                                            <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <select name="designation_id" class="form-control">
                                            <option value="">-- All Designations --</option>
                                            <?php foreach($designations as $designation): ?>
                                            <option value="<?= $designation['id'] ?>"><?= $designation['designation_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Staff Type</label>
                                        <select name="staff_type" class="form-control">
                                            <option value="">-- All Staff Types --</option>
                                            <option value="local">Local Staff Only</option>
                                            <option value="foreigner">Foreign Staff Only</option>
                                        </select>
                                    </div>
                                    
                                    <div class="well">
                                        <p><strong>Note:</strong></p>
                                        <ul>
                                            <li>The component will be assigned to all active staff matching the criteria</li>
                                            <li>If a staff already has this component, they will be skipped</li>
                                            <li>This action cannot be undone - components must be removed individually</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary waves-effect">
                                        <i class="material-icons">check_circle</i> ASSIGN TO SELECTED STAFF
                                    </button>
                                    <a href="<?= base_url('salarycomponents') ?>" class="btn btn-lg btn-danger waves-effect">
                                        <i class="material-icons">cancel</i> CANCEL
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
var allowances = <?= json_encode($allowances) ?>;
var deductions = <?= json_encode($deductions) ?>;

function loadComponents() {
    var type = $('#component_type').val();
    var options = '<option value="">-- Select Component --</option>';
    
    if(type === 'allowance') {
        allowances.forEach(function(item) {
            options += '<option value="' + item.id + '">' + item.allowance_name + '</option>';
        });
    } else if(type === 'deduction') {
        deductions.forEach(function(item) {
            options += '<option value="' + item.id + '">' + item.deduction_name + '</option>';
        });
    }
    
    $('#component_id').html(options);
    $('#component_id').selectpicker('refresh');
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

function confirmBulkAssignment() {
    return confirm('Are you sure you want to assign this component to all selected staff? This action cannot be undone.');
}
</script>