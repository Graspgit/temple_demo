<?php 
if($view == true){
    $readonly = 'readonly';
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Archanai<small>Archanai / <b>Diety</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                            <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/archanai/diety_list"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <form action="<?php echo base_url(); ?>/archanai/save_diety" method="POST" >
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-diety form-float">
                                            <div class="form-line">
                                                <input type="text"  class="form-control" name="name" value="<?php echo strtoupper($data['name']);?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Name English</label>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-diety form-float">
                                            <div class="form-line">
                                                <input type="text"  class="form-control" name="name_tamil" value="<?php echo strtoupper($data['name_tamil']);?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Name Tamil</label>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-diety form-float">
                                            <div class="form-line">
                                                <input type="text"  class="form-control" name="code" value="<?php echo strtoupper($data['code']);?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Deity Code</label>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-diety form-float">
                                            <div class="form-line focused">
                                                <input name="image" id="image" class="form-control" type="file" accept="image/png, image/gif, image/jpeg">
                                                <label class="form-label">Diety Image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php $img_url = !empty($data['image']) ? base_url() . '/uploads/diety/' . $data['image'] : ''; ?>
                                        <img id="img_pre" src="<?php echo $img_url; ?>" class="img-responsive" style="width:100px;">
                                    </div>
                                </div><hr>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <h4>Temple Event Section: </h4>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-check" style="margin-top:15px;">
                                            <input class="form-check-input" id="abi_status" name="abi_status" <?php if($data['abishegam_status'] == '1'){ echo "checked"; } ?> type="checkbox" value="1">
                                            <label class="form-check-label" for="abi_status">
                                                Enable Abisheagm
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" id="abi_amount" class="form-control" name="abi_amount" value="<?php echo $data['abishegam_amount'];?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Abishegam Amount <span id="req" style="color: red;"> *</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-check" style="margin-top:15px;">
                                            <input class="form-check-input" id="hom_status" name="hom_status" <?php if($data['homam_status'] == '1'){ echo "checked"; } ?> type="checkbox" value="1">
                                            <label class="form-check-label" for="hom_status">
                                            Enable  Homam
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" id="hom_amount" class="form-control" name="hom_amount" value="<?php echo $data['homam_amount'];?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Homam Amount <span id="req1" style="color: red;"> *</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <select class="form-control search_box" data-live-search="true" data-live-search-style="startsWith" name="ledger_id" id="ledger_id">
                                                <option value="">Select Ledger</option>
                                                <?php
                                                if(!empty($ledgers))
                                                {
                                                    foreach($ledgers as $ledger)
                                                    {
                                                ?>
                                                    <option value="<?php echo $ledger["id"]; ?>"<?php if(!empty($data['ledger_id'])){ if($data['ledger_id'] == $ledger["id"]){ echo "selected"; }} ?>><?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - ".$ledger["name"]; ?> </option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <?php if($view != true) { ?>
                                    <div class="col-sm-12" align="center">
                                        <button type="submit" class="btn btn-success btn-lg waves-effect">SAVE</button>
                                        <button type="button" id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
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
</section>
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

<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<script>
$(document).ready(function(){
	$("#image").change(function(){
		// alert (0);
		readURL(this);		
	});
			
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				//alert (URL.createObjectURL(e.target.files[0]))
				$('#img_pre').attr('src', e.target.result);
				$('#img_pre').show();
				//$('#img_anchor').attr("href", e.target.result)				
			}            
			reader.readAsDataURL(input.files[0]);
		}
	}
});
</script>
<script>
	$("#clear").click(function(){
	   $("input").val("");
	});
	// $("form").on("submit", function(){
    //     $('input[type=submit]').prop('disabled', true);
    //     $("#loader").show();
    // });

    $(function () {
        $('#req').hide();
        $('#abi_status').change(function () {
            if ($(this).is(':checked')) {
                $('#abi_amount').attr('required', 'true');
                $('#req').show();
            } else {
                $('#abi_amount').removeAttr('required'); 
                $('#req').hide();
            }
        });
    });

    $(function () {
        $('#req1').hide();
        $('#hom_status').change(function () {
            if ($(this).is(':checked')) {
                $('#hom_amount').attr('required', 'true');
                $('#req1').show();
            } else {
                $('#hom_amount').removeAttr('required'); 
                $('#req1').hide();
            }
        });
    });

    $(document).ready(function() {
        $("form").on("submit", function(e) {
            e.preventDefault(); // Stop form from submitting immediately

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/archanai/deity_validation",
                data: $(this).serialize(),
                success: function(data) {
                    var obj = jQuery.parseJSON(data);

                    if (obj.err !== '') {
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text(obj.err);
                    } else {
                        $('input[type=submit]').prop('disabled', true);
                        $("#loader").show();
                        // Submit form only if validation passed
                        $("form")[0].submit(); // native submit to avoid re-triggering the jQuery handler
                    }
                },
                error: function() {
                    alert("Server error. Please try again.");
                }
            });
        });
    });

</script>