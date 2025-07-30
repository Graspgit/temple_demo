<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>EDIT COMMISSION ENTRY</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('commissionhr/history') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('commissionhr/update/' . $commission['id']) ?>" id="edit_commission_form">
                            <?= csrf_field() ?>
                            
                            <!-- Staff Information (Read-only) -->
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <h4>Staff Information</h4>
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Staff Code:</strong> <?= $commission['staff_code'] ?? '-' ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Name:</strong> <?= ($commission['first_name'] ?? '') . ' ' . ($commission['last_name'] ?? '') ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Commission Type:</strong> <span class="label label-info"><?= $commission['commission_type'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="commission_date" class="form-control" 
                                                   value="<?= $commission['commission_date'] ?>" required>
                                            <label class="form-label">Commission Date <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="base_amount" id="base_amount" 
                                                   class="form-control" step="0.01" 
                                                   value="<?= $commission['base_amount'] ?>" required>
                                            <label class="form-label">Base/Sales Amount (RM) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="commission_amount" id="commission_amount" 
                                                   class="form-control" step="0.01" 
                                                   value="<?= $commission['commission_amount'] ?>" required>
                                            <label class="form-label">Commission Amount (RM) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control" 
                                                   value="<?= $commission['reference_no'] ?>">
                                            <label class="form-label">Reference No (Invoice/Order)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="remarks" class="form-control" 
                                                   value="<?= $commission['remarks'] ?>">
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission Details -->
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="header bg-blue">
                                            <h2>COMMISSION CALCULATION</h2>
                                        </div>
                                        <div class="body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td width="50%">Original Commission Rate</td>
                                                    <td>
                                                        <?php if($commission['commission_percentage']): ?>
                                                            <?= $commission['commission_percentage'] ?>%
                                                        <?php else: ?>
                                                            Fixed Amount
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Base Amount</td>
                                                    <td>RM <span id="display_base"><?= number_format($commission['base_amount'], 2) ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Commission Amount</td>
                                                    <td>RM <span id="display_commission"><?= number_format($commission['commission_amount'], 2) ?></span></td>
                                                </tr>
                                                <tr class="bg-light-blue">
                                                    <td><strong>Effective Rate</strong></td>
                                                    <td><strong><span id="effective_rate">-</span></strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Information -->
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="well">
                                        <small>
                                            <strong>Created:</strong> <?= date('d-M-Y H:i:s', strtotime($commission['created_at'])) ?>
                                            <?php if(isset($commission['updated_at']) && $commission['updated_at']): ?>
                                                | <strong>Last Updated:</strong> <?= date('d-M-Y H:i:s', strtotime($commission['updated_at'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> UPDATE COMMISSION
                                    </button>
                                    <a href="<?= base_url('commissionhr/history') ?>" class="btn btn-danger waves-effect">
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
$(function() {
    calculateEffectiveRate();
    
    // Update display values and calculate effective rate
    $('#base_amount, #commission_amount').on('change keyup', function() {
        var baseAmount = parseFloat($('#base_amount').val()) || 0;
        var commissionAmount = parseFloat($('#commission_amount').val()) || 0;
        
        $('#display_base').text(baseAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('#display_commission').text(commissionAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        
        calculateEffectiveRate();
    });
    
    function calculateEffectiveRate() {
        var baseAmount = parseFloat($('#base_amount').val()) || 0;
        var commissionAmount = parseFloat($('#commission_amount').val()) || 0;
        
        if (baseAmount > 0) {
            var effectiveRate = (commissionAmount / baseAmount) * 100;
            $('#effective_rate').text(effectiveRate.toFixed(2) + '%');
        } else {
            $('#effective_rate').text('-');
        }
    }
    
    // Form validation
    $('#edit_commission_form').on('submit', function(e) {
        var baseAmount = parseFloat($('#base_amount').val()) || 0;
        var commissionAmount = parseFloat($('#commission_amount').val()) || 0;
        
        if (baseAmount <= 0) {
            e.preventDefault();
            alert('Base amount must be greater than 0');
            return false;
        }
        
        if (commissionAmount < 0) {
            e.preventDefault();
            alert('Commission amount cannot be negative');
            return false;
        }
        
        return confirm('Are you sure you want to update this commission entry?');
    });
});
</script>