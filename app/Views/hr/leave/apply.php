<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LEAVE APPLICATION FORM</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('leave/applications') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('leave/save_application') ?>" id="leave_form">
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
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select name="leave_type_id" id="leave_type_id" class="form-control show-tick" required>
                                            <option value="">-- Select Leave Type --</option>
                                            <?php foreach($leave_types as $type): ?>
                                                <option value="<?= $type['id'] ?>"><?= $type['leave_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Available Balance</label>
                                        <input type="text" id="leave_balance" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="from_date" id="from_date" class="form-control" required>
                                            <label class="form-label">From Date <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" name="to_date" id="to_date" class="form-control" required>
                                            <label class="form-label">To Date <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Number of Days</label>
                                        <input type="text" id="num_days" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                                            <label class="form-label">Reason for Leave <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">send</i> SUBMIT APPLICATION
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
    // Function to fetch leave balance
    function fetchLeaveBalance() {
        var staffId = $('#staff_id').val();
        var leaveTypeId = $('#leave_type_id').val();
        
        if (staffId && leaveTypeId) {
            $.ajax({
                url: '<?= base_url('leave/getBalance') ?>',
                type: 'POST',
                data: {
                    staff_id: staffId,
                    leave_type_id: leaveTypeId,
                    year: new Date().getFullYear()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#leave_balance').val(response.balance + ' days');
                    } else {
                        $('#leave_balance').val('0 days');
                    }
                },
                error: function() {
                    $('#leave_balance').val('Error fetching balance');
                }
            });
        } else {
            $('#leave_balance').val('');
        }
    }
    
    // Function to calculate number of days
    function calculateDays() {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        
        if (fromDate && toDate) {
            var from = new Date(fromDate);
            var to = new Date(toDate);
            
            // Check if dates are valid
            if (from > to) {
                $('#num_days').val('Invalid date range');
                $('#to_date').addClass('error');
                return;
            } else {
                $('#to_date').removeClass('error');
            }
            
            // Calculate difference in days
            var timeDiff = to.getTime() - from.getTime();
            var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both days
            
            $('#num_days').val(daysDiff + ' day' + (daysDiff > 1 ? 's' : ''));
            
            // Validate against available balance
            var balanceText = $('#leave_balance').val();
            if (balanceText) {
                var availableBalance = parseFloat(balanceText);
                if (daysDiff > availableBalance) {
                    $('#num_days').val(daysDiff + ' days (Exceeds balance!)').css('color', 'red');
                } else {
                    $('#num_days').css('color', '');
                }
            }
        } else {
            $('#num_days').val('');
        }
    }
    
    // Event handlers
    $('#staff_id, #leave_type_id').on('change', function() {
        fetchLeaveBalance();
    });
    
    $('#from_date, #to_date').on('change', function() {
        calculateDays();
    });
    
    // Form validation before submit
    $('#leave_form').on('submit', function(e) {
        var numDaysText = $('#num_days').val();
        var balanceText = $('#leave_balance').val();
        
        // Check if balance is loaded
        if (!balanceText || balanceText === 'Error fetching balance') {
            e.preventDefault();
            alert('Please wait for leave balance to load');
            return false;
        }
        
        // Check if days are calculated
        if (!numDaysText) {
            e.preventDefault();
            alert('Please select valid dates');
            return false;
        }
        
        // Check if exceeds balance
        if (numDaysText.includes('Exceeds balance')) {
            e.preventDefault();
            alert('Leave days exceed available balance');
            return false;
        }
        
        // Check date validity
        var fromDate = new Date($('#from_date').val());
        var toDate = new Date($('#to_date').val());
        
        if (fromDate > toDate) {
            e.preventDefault();
            alert('From date cannot be later than To date');
            return false;
        }
    });
    
    // Initialize Bootstrap Select if using it
    if ($.fn.selectpicker) {
        $('.show-tick').selectpicker();
    }
});
</script>