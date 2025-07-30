<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>ADD COMMISSION ENTRY</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('commissionhr') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('commissionhr/save') ?>" id="commission_form">
                            <?= csrf_field() ?>
                            
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Staff <span class="text-danger">*</span></label>
                                        <select name="staff_id" id="staff_id" class="form-control show-tick" required data-live-search="true">
                                            <option value="">-- Select Staff --</option>
                                            <?php foreach($staff as $emp): ?>
                                                <option value="<?= $emp['id'] ?>">
                                                    <?= $emp['staff_code'] ?> - <?= $emp['first_name'] . ' ' . $emp['last_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Commission Type <span class="text-danger">*</span></label>
                                        <select name="commission_type" id="commission_type" class="form-control show-tick" required>
                                            <option value="">-- Select Type --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="commission_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                                            <label class="form-label">Commission Date <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="base_amount" id="base_amount" class="form-control" step="0.01" required>
                                            <label class="form-label">Base/Sales Amount (RM) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control">
                                            <label class="form-label">Reference No (Invoice/Order)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="remarks" class="form-control">
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission Preview -->
                            <div class="row clearfix" id="commission_preview" style="display:none;">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="header bg-blue">
                                            <h2>COMMISSION CALCULATION</h2>
                                        </div>
                                        <div class="body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td width="50%">Base Amount</td>
                                                    <td>RM <span id="preview_base">0.00</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Commission Rate</td>
                                                    <td><span id="preview_rate">-</span></td>
                                                </tr>
                                                <tr class="bg-light-blue">
                                                    <td><strong>Commission Amount</strong></td>
                                                    <td><strong>RM <span id="preview_amount">0.00</span></strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> SAVE COMMISSION
                                    </button>
                                    <button type="reset" class="btn btn-danger waves-effect">
                                        <i class="material-icons">clear</i> RESET
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
$(function() {
    // Load commission types when staff is selected
    $('#staff_id').on('change', function() {
        var staffId = $(this).val();
        if (staffId) {
            $.get('<?= base_url("commissionhr/staff-settings/") ?>' + staffId, function(data) {
                var options = '<option value="">-- Select Type --</option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.commission_type + '" ' +
                              'data-percentage="' + (item.commission_percentage || 0) + '" ' +
                              'data-amount="' + (item.commission_amount || 0) + '">' +
                              item.commission_type + '</option>';
                });
                $('#commission_type').html(options).selectpicker('refresh');
            });
        }
    });

    // Calculate commission preview
    $('#base_amount, #commission_type').on('change keyup', function() {
        var baseAmount = parseFloat($('#base_amount').val()) || 0;
        var selected = $('#commission_type option:selected');
        var percentage = parseFloat(selected.data('percentage')) || 0;
        var fixedAmount = parseFloat(selected.data('amount')) || 0;
        
        if (baseAmount > 0 && $('#commission_type').val()) {
            var commissionAmount = 0;
            var rateText = '';
            
            if (percentage > 0) {
                commissionAmount = (baseAmount * percentage) / 100;
                rateText = percentage + '%';
            } else if (fixedAmount > 0) {
                commissionAmount = fixedAmount;
                rateText = 'Fixed RM ' + fixedAmount.toFixed(2);
            }
            
            $('#preview_base').text(baseAmount.toFixed(2));
            $('#preview_rate').text(rateText);
            $('#preview_amount').text(commissionAmount.toFixed(2));
            $('#commission_preview').show();
        } else {
            $('#commission_preview').hide();
        }
    });

    $('.show-tick').selectpicker();
});
</script>