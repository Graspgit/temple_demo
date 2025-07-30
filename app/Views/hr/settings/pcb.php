<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PCB (MONTHLY TAX DEDUCTION) SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#pcbModal">
                                    <i class="material-icons">add</i> Add New Rate
                                </button>
                            </li>
                            <li>
                                <a href="https://www.hasil.gov.my" target="_blank" class="btn btn-info waves-effect">
                                    <i class="material-icons">link</i> LHDN Website
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('statutorysettings/statutory') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="alert alert-info">
                            <strong>PCB Categories:</strong>
                            <ul>
                                <li><strong>Category 1:</strong> Single</li>
                                <li><strong>Category 2:</strong> Married, spouse not working</li>
                                <li><strong>Category 3:</strong> Married, spouse working</li>
                            </ul>
                            <small>*PCB rates are based on annual income and calculated monthly</small>
                        </div>

                        <!-- Category Filter -->
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Filter by Category:</label>
                                    <select id="categoryFilter" class="form-control show-tick">
                                        <option value="">All Categories</option>
                                        <option value="1">Category 1 - Single</option>
                                        <option value="2">Category 2 - Married (Spouse Not Working)</option>
                                        <option value="3">Category 3 - Married (Spouse Working)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Annual Income From (RM)</th>
                                        <th>Annual Income To (RM)</th>
                                        <th>Tax Amount (RM)</th>
                                        <th>Tax Rate (%)</th>
                                        <th>Monthly PCB (RM)</th>
                                        <th>Effective Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($pcb_settings as $pcb): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <span class="label label-info">Category <?= $pcb['category'] ?></span>
                                        </td>
                                        <td>RM <?= number_format($pcb['income_from'], 2) ?></td>
                                        <td>RM <?= number_format($pcb['income_to'], 2) ?></td>
                                        <td>RM <?= number_format($pcb['tax_amount'], 2) ?></td>
                                        <td><?= $pcb['tax_percentage'] ? $pcb['tax_percentage'] . '%' : '-' ?></td>
                                        <td><strong>RM <?= number_format($pcb['tax_amount'] / 12, 2) ?></strong></td>
                                        <td><?= $pcb['effective_year'] ?></td>
                                        <td>
                                            <span class="label <?= $pcb['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $pcb['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-pcb" 
                                                data-id="<?= $pcb['id'] ?>"
                                                data-category="<?= $pcb['category'] ?>"
                                                data-income-from="<?= $pcb['income_from'] ?>"
                                                data-income-to="<?= $pcb['income_to'] ?>"
                                                data-tax-amount="<?= $pcb['tax_amount'] ?>"
                                                data-tax-percentage="<?= $pcb['tax_percentage'] ?>"
                                                data-effective-year="<?= $pcb['effective_year'] ?>"
                                                data-status="<?= $pcb['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/pcb_settings/'.$pcb['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this PCB rate?')">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PCB Calculator -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-orange">
                        <h2>PCB CALCULATOR</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Monthly Salary (RM)</label>
                                    <input type="number" id="calc_salary" class="form-control" step="0.01" placeholder="Enter monthly salary">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="calc_category" class="form-control">
                                        <option value="1">Category 1 - Single</option>
                                        <option value="2">Category 2 - Married (Spouse Not Working)</option>
                                        <option value="3">Category 3 - Married (Spouse Working)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-block btn-primary waves-effect" onclick="calculatePCB()">
                                        CALCULATE PCB
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Monthly PCB</label>
                                    <h3 id="pcb_result" class="text-primary">RM 0.00</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PCB Modal -->
<div class="modal fade" id="pcbModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/savePcb') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="pcb_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit PCB Rate</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control show-tick" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="1">Category 1 - Single</option>
                                    <option value="2">Category 2 - Married (Spouse Not Working)</option>
                                    <option value="3">Category 3 - Married (Spouse Working)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="income_from" id="income_from" class="form-control" step="0.01" required>
                                    <label class="form-label">Annual Income From (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="income_to" id="income_to" class="form-control" step="0.01" required>
                                    <label class="form-label">Annual Income To (RM)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="tax_amount" id="tax_amount" class="form-control" step="0.01" required>
                                    <label class="form-label">Annual Tax Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="tax_percentage" id="tax_percentage" class="form-control" step="0.01">
                                    <label class="form-label">Tax Rate (%) - Optional</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Monthly PCB</label>
                                <h4 id="monthly_pcb_preview">RM 0.00</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="effective_year" id="effective_year" class="form-control" required value="<?= date('Y') ?>">
                                    <label class="form-label">Effective Year</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control show-tick">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    var table = $('.js-basic-example').DataTable({
        responsive: true,
        order: [[1, 'asc'], [2, 'asc']]
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        var category = $(this).val();
        if (category) {
            table.column(1).search('Category ' + category).draw();
        } else {
            table.column(1).search('').draw();
        }
    });

    // Edit PCB
    $('.edit-pcb').on('click', function() {
        var data = $(this).data();
        $('#pcb_id').val(data.id);
        $('#category').val(data.category).selectpicker('refresh');
        $('#income_from').val(data.incomeFrom);
        $('#income_to').val(data.incomeTo);
        $('#tax_amount').val(data.taxAmount);
        $('#tax_percentage').val(data.taxPercentage);
        $('#effective_year').val(data.effectiveYear);
        $('#status').val(data.status).selectpicker('refresh');
        
        updateMonthlyPreview();
        $('#pcbModal').modal('show');
    });

    // Update monthly preview
    $('#tax_amount').on('keyup change', function() {
        updateMonthlyPreview();
    });

    function updateMonthlyPreview() {
        var taxAmount = parseFloat($('#tax_amount').val()) || 0;
        var monthlyPCB = taxAmount / 12;
        $('#monthly_pcb_preview').text('RM ' + monthlyPCB.toFixed(2));
    }

    // Clear form on modal close
    $('#pcbModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#pcb_id').val('');
        $('#category').selectpicker('refresh');
        $('#status').selectpicker('refresh');
        $('#monthly_pcb_preview').text('RM 0.00');
    });

    $('.show-tick').selectpicker();
});

// PCB Calculator
function calculatePCB() {
    var monthlySalary = parseFloat($('#calc_salary').val()) || 0;
    var category = $('#calc_category').val();
    
    if (monthlySalary <= 0) {
        alert('Please enter a valid monthly salary');
        return;
    }
    
    var annualSalary = monthlySalary * 12;
    
    // This would typically make an AJAX call to calculate based on database rates
    // For now, showing a simple calculation
    var pcb = 0;
    
    // Basic calculation (simplified)
    if (annualSalary > 35000) {
        if (category == '1') {
            pcb = (annualSalary - 35000) * 0.08 / 12;
        } else if (category == '2') {
            pcb = (annualSalary - 35000) * 0.06 / 12;
        } else {
            pcb = (annualSalary - 35000) * 0.07 / 12;
        }
    }
    
    $('#pcb_result').text('RM ' + pcb.toFixed(2));
}
</script>