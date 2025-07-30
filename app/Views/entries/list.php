<style>
    /*.thead{
        color: #fff;
        background-color: red;
    }
    a:hover { text-decoration: none; }
    body { background:#fff; }
    .content { max-width: 1009%; padding: 0 .2rem; }*/
</style>
<section class="content">
    <div class="container-fluid">
        <!--<div class="block-header">
            <h2> ENTRIES <small>Account / <a href="#" target="_blank">Entries</a></small></h2>
        </div>-->
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-4">
                                <h2>List Of Entries</h2>
                            </div>
                            <?php if ($permission['create_p'] == 1) { ?>
                                <div class="col-md-8" align="right">
                                    <a href="<?php echo base_url(); ?>/entries/receipt_add"><button type="button"
                                            class="btn bg-deep-purple waves-effect">Receipt</button></a>
                                    <a href="<?php echo base_url(); ?>/entries/payment_add"><button type="button"
                                            class="btn bg-deep-purple waves-effect">Payment</button></a>
                                    <!-- <a href="<?php echo base_url(); ?>/entries/add_entries/3"><button type="button" class="btn bg-deep-purple waves-effect">Contra</button></a> -->
                                    <a href="<?php echo base_url(); ?>/entries/journal_add"><button type="button"
                                            class="btn bg-deep-purple waves-effect">Journal</button></a>
                                    <a href="<?php echo base_url(); ?>/entries/credit_note_add"><button type="button" 
                                            class="btn bg-deep-purple waves-effect">Credit Note</button></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="body">
                        <?php if ($_SESSION['succ'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="suc-alert">
                                    <span class="suc-closebtn"
                                        onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['succ']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($_SESSION['fail'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="alert">
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['fail']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                            <table class="table table-striped dataTable" id="custom_datatable">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">Date</th>
                                        <th style="width:10%;">Payment Mode</th>
                                        <th style="width:32%;">Particulrs</th>
                                        <th style="width:8%;">Type</th>
                                        <th align="right" style="width:13%; text-align:right !important;">Debit</th>
                                        <th align="right" style="width:13%; text-align:right !important;">Credit</th>
                                        <?php if ($permission['view'] == 1 || $permission['edit'] == 1 || $permission['delete_p'] == 1 || $permission['print_p'] == 1) { ?>
                                            <th style="width:12%;">Actions</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php //echo '<pre>';  //print_r($data); ?>
                                        <?php foreach($data as $row) { //$disable = ""; $edit_icon="&#xE3C9;";
											$edit_url = '/entries/';
                                            if($row['entrytype_id'] == 1){
												$type = 'Receipt';
												$edit_url .= 'receipt_edit/';
                                            }else if($row['entrytype_id'] == 2){
												$type = 'Payment';
												$edit_url .= 'payment_edit/';
                                            }else if($row['entrytype_id'] == 3){
												$type = 'Contra';
												$edit_url .= 'journal_edit/';
                                            }else if($row['entrytype_id'] == 4){
												$type = 'Journal';
												$edit_url .= 'journal_edit/';
												$row['payment'] = '-';
                                            }else if($row['entrytype_id'] == 5){
												$type = 'Credit Note';
												$edit_url .= 'credit_note_edit/';
												$row['payment'] = '-';
                                            }else{
												$type = '';
												$edit_url .= 'journal_edit/';
											}
											$edit_url .= $row['id'];
                                            if(!empty($row['dr_total'])) $damt = $row['dr_total'];
                                            else $damt = 0;
                                            if(!empty($row['cr_total'])) $camt = $row['cr_total'];
                                            else $camt = 0;
                                            ?>
                                            <tr>
                                                <td ><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                                                <td ><?php echo $row['payment']; ?></td>
                                                <td ><?php echo $row['narration']; ?></td>
                                                <td ><?php echo $type; ?></td>
                                                <td align="right"><?php echo ''.number_format($damt, '2','.',','); ?></td>
                                                <td align="right"><?php echo ''.number_format($camt, '2','.',','); ?></td>
                                                <?php if($permission['view'] == 1 ||  $permission['edit'] == 1 ||  $permission['delete_p'] == 1) { ?>
                                                    <td>
                                                        <?php if($permission['view'] == 1) { ?>
                                                                <a class="btn btn-success btn-rad" title="View" href="<?= base_url()?>/entries/view_page/<?php echo $row['id'];?>"><i class="material-icons">&#xE417;</i></a>
                                                            <?php } if($permission['delete_p'] == 1) { ?>
                                                                <a class="btn btn-info btn-rad" title="Print" href="<?= base_url()?>/entries/print_page/<?php echo $row['id'];?>" target="_blank"><i class="material-icons">print</i></a>
                                                            <?php } if(empty($row['inv_id'])) {	 if($permission['edit'] == 1) { 
																	
																	//$disable = 'pointer-events: none';
																		//$edit_icon="  ";
																		//echo $row['inv_id'];																	
																		?> 
																		<!--a class="btn btn-primary btn-rad"  title="Edit" href="<?= base_url()?>/entries/edit_page/<?= $row['id'] ?>"><i class="material-icons">&#xE3C9;</i></a--> 
																		<a class="btn btn-primary btn-rad" style="<?= $disable ?>" title="Edit" href="<?= base_url() . $edit_url;?>"><i class="material-icons">&#xE3C9;</i></a>
															<?php }   if($permission['delete_p'] == 1) { ?>
                                                                <a class="btn btn-danger btn-rad" title="Delete" onclick="confirm_modal(<?php echo $row['id'];?>)"><i class="material-icons">&#xE872;</i></a>
                                                            <?php }}  ?>
															
                                                    </td>	
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2">Delete Entries</h4>
                        <table>

                            <tr><span id="spndeddelid"><b></b></span></tr>
                        </table>

                        <a href="#" id="del" class="btn btn-danger my-3" data-dismiss="modal">Yes</a> &nbsp;
                        <button type="button" class="btn btn-info my-3" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <div id=delete-form style="display: none">

    </div>
</section>
<script>

    function confirm_modal(id) {
        $('#alert-modal').modal('show', { backdrop: 'static' });
        document.getElementById('del').setAttribute('onclick', 'dedDel(' + id + ')');
        $("#spndeddelid").text("Are you sure You want to Delete this Entries?");

    }

    function dedDel(id) {
        var act = "<?php echo base_url(); ?>/entries/delete_page/" + id;
        $("#delete-form").append("<form action='" + act + "'><button type='submit' id='delete" + id + "' >submit</button></form>");
        $("#delete" + id).trigger("click");
    }
    $(function () {
        $('#custom_datatable').DataTable({
            //dom: 'Bfrtip',
            responsive: true,
            order: [[0, 'asc']]
            /*buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]*/
        });
    });
</script>





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
    loadDeletionReasons();
    $('#enhanced-delete-modal').modal('show');
}

function loadDeletionReasons() {
    $.ajax({
        url: '<?php echo base_url(); ?>/entries/get_deletion_reasons',
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select a reason...</option>';
            response.forEach(function(reason) {
                options += `<option value="${reason.reason}">${reason.reason}</option>`;
            });
            $('#delete_reason').html(options);
            $('#delete_reason').selectpicker("refresh");
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