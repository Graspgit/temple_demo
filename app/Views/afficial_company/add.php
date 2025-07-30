<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<style>
<?php 
if($view == true) { ?>
label.form-label span { display:none !important; color:transporant; }
<?php } ?>

.table-responsive{
        overflow-x: hidden;
    }
</style>

<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2> General <small>Slider / <b>Add Slider</b></small></h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="row"><div class="col-md-8"><!--<h2>Cash Donation</h2>--></div>
                            <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/afficial_company"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                        </div>
                        <form id="sessionblock_form" action="<?php echo base_url(); ?>/afficial_company/save" method="post" enctype="multipart/form-data">
                            <div class="body">
                                <input type="hidden" name="id" value="<?php echo $data['name'] ?? ''; ?>">
                                <div class="container-fluid">
                                    <div class="row clearfix">
                                        <!--
                                        <div class="col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="upload_type" id="upload_type" onchange="updateFormFields()">
                                                        <option value="">Select Type</option>
                                                        <option value="1" <?php echo (isset($data['type']) && $data['type'] == '1') ? 'selected' : ''; ?>>Image</option>
                                                        <option value="2" <?php echo (isset($data['type']) && $data['type'] == '2') ? 'selected' : ''; ?>>Video</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                   
                                        <div class="col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="active_status" id="active_status" required>
                                                        <option value="">Status</option>
                                                        <option value="1" <?php echo (isset($data['status']) && $data['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                                                        <option value="2" <?php echo (isset($data['status']) && $data['status'] == '2') ? 'selected' : ''; ?>>InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                       
                                    </div>
                                    <div class="row clearfix"> 

<!-- Image Upload Section -->
<div class="col-sm-4" id="imageField" style="display: block;">
    <div class="form-group form-float">
        <div class="form-line">
            <label>Upload Image</label>
            <input type="file" class="form-control" name="image_upload" id="image_upload">
        </div>
    </div>
    <!-- Show the saved image in edit mode -->
    <?php if (isset($data['url'])): ?>
        <img src="<?php echo $data['url']; ?>" height="80" alt="Uploaded Image" width="100">
    <?php endif; ?>
</div>

</div>
                                    <div class="">    
                                    <div id="">
                                        
                                    </div>
                                    

                                        <?php if($view != true) { ?>
                                            <div class="col-sm-12" align="center">
                                                <button type="button" onclick="bt_submit()" id="submit_all" class="btn btn-success btn-lg waves-effect">SAVE</button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>

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
                        <table>
                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                        </table>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    </section>
    <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>


<script>
function bt_submit()
{
    var is_edit = "<?php echo (isset($edit)?1:0);  ?>";
    console.log(is_edit);
    if (is_edit == 1 && $("#image_upload")[0].files.length == 0) {
        $("#sessionblock_form").attr("action","<?php echo base_url(); ?>/afficial_company/save_edit");
        $("#sessionblock_form")[0].submit();
    }
    else if ($("#image_upload")[0].files.length > 0) {
        $("#sessionblock_form")[0].submit();
    }
    else
        alert("Please Upload Image");
}
// Add new image field
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('imagePreview');
        output.src = reader.result;
        output.style.display = 'block'; // Display the image preview
    };
    reader.readAsDataURL(event.target.files[0]);
}

function updateFormFields() {
    var uploadType = document.getElementById('upload_type').value;
    
    // Show/Hide Image Field
    if (uploadType == '1') {
        document.getElementById('imageField').style.display = 'block';
        document.getElementById('videoField').style.display = 'none';
    }
    // Show/Hide Video URL Field
    else if (uploadType == '2') {
        document.getElementById('imageField').style.display = 'none';
        document.getElementById('videoField').style.display = 'block';
    } else {
        document.getElementById('imageField').style.display = 'none';
        document.getElementById('videoField').style.display = 'none';
    }
}

// Call the function on page load to handle edit form data
window.onload = updateFormFields;

</script>








