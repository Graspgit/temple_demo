<!-- hr/leave/applications.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Leave Stats -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">pending_actions</i>
                    </div>
                    <div class="content">
                        <div class="text">PENDING APPROVAL</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($applications, function($a) { return $a['status'] == 'pending'; })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">event_available</i>
                    </div>
                    <div class="content">
                        <div class="text">APPROVED THIS MONTH</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($applications, function($a) { return $a['status'] == 'approved' && date('Y-m', strtotime($a['from_date'])) == date('Y-m'); })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">people_outline</i>
                    </div>
                    <div class="content">
                        <div class="text">ON LEAVE TODAY</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($applications, function($a) { return $a['status'] == 'approved' && date('Y-m-d') >= $a['from_date'] && date('Y-m-d') <= $a['to_date']; })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">event_busy</i>
                    </div>
                    <div class="content">
                        <div class="text">REJECTED</div>
                        <div class="number count-to" data-from="0" data-to="<?= count(array_filter($applications, function($a) { return $a['status'] == 'rejected'; })) ?>" data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Applications Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>LEAVE APPLICATIONS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter: <?= ucfirst($status) ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= base_url('leave/applications?status=all') ?>">All</a></li>
                                        <li><a href="<?= base_url('leave/applications?status=pending') ?>">Pending</a></li>
                                        <li><a href="<?= base_url('leave/applications?status=approved') ?>">Approved</a></li>
                                        <li><a href="<?= base_url('leave/applications?status=rejected') ?>">Rejected</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="<?= base_url('leave/apply') ?>" class="btn btn-success waves-effect">
                                    <i class="material-icons">add</i> Apply Leave
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Application Date</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Leave Type</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($applications as $app): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= date('d-M-Y', strtotime($app['created_at'])) ?></td>
                                        <td><?= $app['staff_code'] ?></td>
                                        <td><?= $app['first_name'] . ' ' . $app['last_name'] ?></td>
                                        <td><span class="label label-info"><?= $app['leave_name'] ?></span></td>
                                        <td><?= date('d-M-Y', strtotime($app['from_date'])) ?></td>
                                        <td><?= date('d-M-Y', strtotime($app['to_date'])) ?></td>
                                        <td><strong><?= $app['days'] ?></strong></td>
                                        <td><?= $app['reason'] ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'label-warning',
                                                'approved' => 'label-success',
                                                'rejected' => 'label-danger',
                                                'cancelled' => 'label-default'
                                            ];
                                            ?>
                                            <span class="label <?= $statusClass[$app['status']] ?>">
                                                <?= ucfirst($app['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($app['status'] == 'pending'): ?>
                                            <button type="button" class="btn btn-xs btn-success approve-leave" 
                                                data-id="<?= $app['id'] ?>" 
                                                data-staff="<?= $app['first_name'] . ' ' . $app['last_name'] ?>"
                                                data-days="<?= $app['days'] ?>">
                                                <i class="material-icons">check</i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger reject-leave" 
                                                data-id="<?= $app['id'] ?>"
                                                data-staff="<?= $app['first_name'] . ' ' . $app['last_name'] ?>">
                                                <i class="material-icons">close</i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-xs btn-info view-details" 
                                                data-id="<?= $app['id'] ?>"
                                                data-approved-by="<?= $app['approved_by'] ?? '-' ?>"
                                                data-approved-date="<?= $app['approved_date'] ?? '-' ?>"
                                                data-remarks="<?= $app['remarks'] ?? '-' ?>">
                                                <i class="material-icons">info</i>
                                            </button>
                                            <?php endif; ?>
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

<!-- Approve/Reject Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="statusModalLabel">Update Leave Status</h4>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="leave_id" name="leave_id">
                    <input type="hidden" id="leave_status" name="status">
                    
                    <div class="form-group">
                        <label>Staff Name</label>
                        <input type="text" class="form-control" id="staff_name" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Number of Days</label>
                        <input type="text" class="form-control" id="leave_days" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Remarks <span class="text-danger">*</span></label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" required placeholder="Enter remarks..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-primary waves-effect" id="confirmStatus">CONFIRM</button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Leave Application Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Approved By:</strong> <span id="detail_approved_by"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Approved Date:</strong> <span id="detail_approved_date"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Remarks:</strong></p>
                        <p id="detail_remarks" style="background-color: #f5f5f5; padding: 10px; border-radius: 4px;"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    
    // Handle approve button click
    $('.approve-leave').on('click', function() {
        var leaveId = $(this).data('id');
        var staffName = $(this).data('staff');
        var days = $(this).data('days');
        
        $('#leave_id').val(leaveId);
        $('#leave_status').val('approved');
        $('#staff_name').val(staffName);
        $('#leave_days').val(days + ' day(s)');
        $('#remarks').val('');
        $('#statusModalLabel').text('Approve Leave Application');
        $('#confirmStatus').removeClass('btn-danger').addClass('btn-success').text('APPROVE');
        
        $('#statusModal').modal('show');
    });
    
    // Handle reject button click
    $('.reject-leave').on('click', function() {
        var leaveId = $(this).data('id');
        var staffName = $(this).data('staff');
        
        $('#leave_id').val(leaveId);
        $('#leave_status').val('rejected');
        $('#staff_name').val(staffName);
        $('#leave_days').val('-');
        $('#remarks').val('');
        $('#statusModalLabel').text('Reject Leave Application');
        $('#confirmStatus').removeClass('btn-success').addClass('btn-danger').text('REJECT');
        
        $('#statusModal').modal('show');
    });
    
    // Handle confirm status button
    $('#confirmStatus').on('click', function() {
        var leaveId = $('#leave_id').val();
        var status = $('#leave_status').val();
        var remarks = $('#remarks').val();
        
        if (!remarks.trim()) {
            alert('Please enter remarks');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('leave/updateStatus') ?>/' + leaveId,
            type: 'POST',
            data: {
                status: status,
                remarks: remarks
            },
            dataType: 'json',
            beforeSend: function() {
                $('#confirmStatus').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Show success notification
                    showNotification('bg-green', response.message, 'top', 'right');
                    
                    // Close modal
                    $('#statusModal').modal('hide');
                    
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification('bg-red', response.message || 'Error updating status', 'top', 'right');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showNotification('bg-red', 'Error updating leave status', 'top', 'right');
            },
            complete: function() {
                $('#confirmStatus').prop('disabled', false);
                if ($('#leave_status').val() === 'approved') {
                    $('#confirmStatus').text('APPROVE');
                } else {
                    $('#confirmStatus').text('REJECT');
                }
            }
        });
    });
    
    // Handle view details button
    $('.view-details').on('click', function() {
        var approvedBy = $(this).data('approved-by');
        var approvedDate = $(this).data('approved-date');
        var remarks = $(this).data('remarks');
        
        $('#detail_approved_by').text(approvedBy || '-');
        $('#detail_approved_date').text(approvedDate || '-');
        $('#detail_remarks').text(remarks || 'No remarks');
        
        $('#detailsModal').modal('show');
    });
    
    // Simple notification function
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
        }, 3000);
    }
});
</script>