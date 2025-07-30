

<!-- Enhanced Delete Modal - Replace the existing modal in list.php -->
<div id="enhanced-delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Entry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-warning h1 text-warning"></i>
                    <h5 class="mt-2">Are you sure you want to delete this entry?</h5>
                    <p class="text-muted">This action cannot be undone. The entry will be permanently removed from the system but archived for audit purposes.</p>
                    
                    <div class="form-group mt-3">
                        <label for="delete_reason">Reason for Deletion <span class="text-danger">*</span></label>
                        <select class="form-control" id="delete_reason" name="delete_reason" required>
                            <option value="">Select a reason...</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="delete_comment">Additional Comments (Optional)</label>
                        <textarea class="form-control" id="delete_comment" name="delete_comment" rows="3" placeholder="Provide additional details..."></textarea>
                    </div>
                    
                    <div class="form-group text-left">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="confirm_delete" required>
                            <label class="custom-control-label" for="confirm_delete">
                                I understand this action will permanently delete the entry
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm_delete_btn" disabled>
                    <i class="fa fa-trash"></i> Delete Entry
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let entryToDelete = null;

function confirm_modal(id) {
    entryToDelete = id;
    alert();
    loadDeletionReasons();
    $('#enhanced-delete-modal').modal('show');
}

function loadDeletionReasons() {
    $.ajax({
        url: '<?php echo base_url(); ?>/entries/get_deletion_reasons',
        type: 'GET',
        success: function(response) {
            const reasons = JSON.parse(response);
            let options = '<option value="">Select a reason...</option>';
            reasons.forEach(function(reason) {
                options += `<option value="${reason.reason}">${reason.reason}</option>`;
            });
            $('#delete_reason').html(options);
            $("#delete_reason").selectpicker("refresh");
        },
        error: function() {
            console.error('Failed to load deletion reasons');
        }
    });
}

// Enable/disable delete button based on form validation
$('#delete_reason, #confirm_delete').on('change', function() {
    const reasonSelected = $('#delete_reason').val() !== '';
    const confirmChecked = $('#confirm_delete').is(':checked');
    $('#confirm_delete_btn').prop('disabled', !(reasonSelected && confirmChecked));
});

// Handle delete confirmation
$('#confirm_delete_btn').on('click', function() {
    if (!entryToDelete) return;
    
    const reason = $('#delete_reason').val();
    const comment = $('#delete_comment').val();
    const finalReason = comment ? `${reason} - ${comment}` : reason;
    
    // Show loading state
    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
    
    // Create form and submit
    const form = $('<form>', {
        'method': 'POST',
        'action': `<?php echo base_url(); ?>/entries/delete_page_enhanced/${entryToDelete}`
    });
    
    form.append($('<input>', {
        'type': 'hidden',
        'name': 'delete_reason',
        'value': finalReason
    }));
    
    // Add CSRF token if you're using it
    // form.append($('<input>', {
    //     'type': 'hidden',
    //     'name': 'csrf_token',
    //     'value': '<?php echo csrf_hash(); ?>'
    // }));
    
    $('body').append(form);
    form.submit();
});

// Reset modal when closed
$('#enhanced-delete-modal').on('hidden.bs.modal', function() {
    $('#delete_reason').val('');
    $('#delete_comment').val('');
    $('#confirm_delete').prop('checked', false);
    $('#confirm_delete_btn').prop('disabled', true).html('<i class="fa fa-trash"></i> Delete Entry');
    entryToDelete = null;
});
</script>

<!-- Update the delete button in your list.php to use the new modal -->
<script>
// Replace the existing confirm_modal function with this:
// The confirm_modal function is already defined above
</script>