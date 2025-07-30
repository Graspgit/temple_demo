<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - Year <?= $year ?></h2>
        </div>

        <!-- Bulk Allocation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-blue">
                        <h2>BULK LEAVE ALLOCATION</h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('leave/bulk_allocation') ?>">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select name="year" class="form-control">
                                            <option value="<?= date('Y') ?>" <?= $year == date('Y') ? 'selected' : '' ?>><?= date('Y') ?></option>
                                            <option value="<?= date('Y')+1 ?>" <?= $year == date('Y')+1 ? 'selected' : '' ?>><?= date('Y')+1 ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Leave Type</label>
                                        <select name="leave_type_id" class="form-control" required>
                                            <option value="">-- Select Leave Type --</option>
                                            <?php foreach($leave_types as $type): ?>
                                                <option value="<?= $type['id'] ?>"><?= $type['leave_name'] ?> (<?= $type['days_per_year'] ?> days)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block waves-effect">
                                            ALLOCATE TO ALL ACTIVE STAFF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Allocation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>INDIVIDUAL LEAVE ALLOCATION</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Staff Code</th>
                                        <th rowspan="2">Name</th>
                                        <?php foreach($leave_types as $type): ?>
                                        <th colspan="3" class="text-center"><?= $type['leave_name'] ?></th>
                                        <?php endforeach; ?>
                                        <th rowspan="2">Action</th>
                                    </tr>
                                    <tr>
                                        <?php foreach($leave_types as $type): ?>
                                        <th>Entitled</th>
                                        <th>Used</th>
                                        <th>Balance</th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($staff_allocations as $staff): ?>
                                    <tr>
                                        <td><?= $staff['staff_code'] ?></td>
                                        <td><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></td>
                                        <?php foreach($leave_types as $type): 
                                            $allocation = null;
                                            foreach($staff['allocations'] as $alloc) {
                                                if($alloc['leave_type_id'] == $type['id']) {
                                                    $allocation = $alloc;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <td><?= $allocation ? $allocation['entitled_days'] : '0' ?></td>
                                        <td><?= $allocation ? $allocation['used_days'] : '0' ?></td>
                                        <td><?= $allocation ? $allocation['balance_days'] : '0' ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary allocate-leave" 
                                                data-staff-id="<?= $staff['id'] ?>"
                                                data-staff-name="<?= $staff['first_name'] . ' ' . $staff['last_name'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
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
    </div>
</section>

<!-- Individual Allocation Modal -->
<div class="modal fade" id="allocationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="allocationModalLabel">Allocate Leave</h4>
            </div>
            <div class="modal-body">
                <form id="allocationForm">
                    <input type="hidden" id="modal_staff_id" name="staff_id">
                    <input type="hidden" id="modal_year" name="year" value="<?= $year ?>">
                    
                    <div class="form-group">
                        <label>Staff Name</label>
                        <input type="text" class="form-control" id="modal_staff_name" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Leave Type</label>
                        <select name="leave_type_id" id="modal_leave_type" class="form-control" required>
                            <option value="">-- Select Leave Type --</option>
                            <?php foreach($leave_types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= $type['leave_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Entitled Days</label>
                        <input type="number" name="entitled_days" id="modal_entitled_days" class="form-control" min="0" step="0.5" required>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> This will update the leave allocation for the selected staff member.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary waves-effect" id="saveAllocation">SAVE</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Store current allocations for reference
    var currentAllocations = <?= json_encode($staff_allocations) ?>;
    var leaveTypes = <?= json_encode($leave_types) ?>;
    
    // Handle edit button click
    $('.allocate-leave').on('click', function() {
        var staffId = $(this).data('staff-id');
        var staffName = $(this).data('staff-name');
        
        // Set modal values
        $('#modal_staff_id').val(staffId);
        $('#modal_staff_name').val(staffName);
        $('#allocationModalLabel').text('Allocate Leave - ' + staffName);
        
        // Reset form
        $('#modal_leave_type').val('');
        $('#modal_entitled_days').val('');
        
        // Show modal
        $('#allocationModal').modal('show');
    });
    
    // Handle leave type selection
    $('#modal_leave_type').on('change', function() {
        var staffId = $('#modal_staff_id').val();
        var leaveTypeId = $(this).val();
        
        if (leaveTypeId) {
            // Find current allocation if exists
            var staff = currentAllocations.find(s => s.id == staffId);
            if (staff && staff.allocations) {
                var allocation = staff.allocations.find(a => a.leave_type_id == leaveTypeId);
                if (allocation) {
                    $('#modal_entitled_days').val(allocation.entitled_days);
                } else {
                    // Set default from leave type
                    var leaveType = leaveTypes.find(t => t.id == leaveTypeId);
                    if (leaveType) {
                        $('#modal_entitled_days').val(leaveType.days_per_year);
                    }
                }
            }
        }
    });
    
    // Handle save button
    $('#saveAllocation').on('click', function() {
        var form = $('#allocationForm');
        
        if (form[0].checkValidity()) {
            var formData = form.serialize();
            
            $.ajax({
                url: '<?= base_url('leave/saveAllocation') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('#saveAllocation').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Show success message
                        showNotification('bg-green', 'Leave allocation saved successfully', 'top', 'right');
                        
                        // Close modal
                        $('#allocationModal').modal('hide');
                        
                        // Reload page to show updated data
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('bg-red', 'Error saving allocation', 'top', 'right');
                    }
                },
                error: function() {
                    showNotification('bg-red', 'Error saving allocation', 'top', 'right');
                },
                complete: function() {
                    $('#saveAllocation').prop('disabled', false).text('SAVE');
                }
            });
        } else {
            showNotification('bg-red', 'Please fill all required fields', 'top', 'right');
        }
    });
    
    // Simple notification function using Bootstrap alerts
    function showNotification(colorName, text, placementFrom, placementAlign) {
        // Create alert div
        var alertClass = colorName.replace('bg-', 'alert-');
        if (alertClass === 'alert-green') alertClass = 'alert-success';
        if (alertClass === 'alert-red') alertClass = 'alert-danger';
        
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible" role="alert" style="position: fixed; top: 70px; right: 20px; z-index: 9999; min-width: 300px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        text +
                        '</div>';
        
        // Add to body
        var $alert = $(alertHtml).appendTo('body');
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            $alert.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 4000);
    }
});
</script>