<style>

    .ui-autocomplete{
        padding: 0px !important;
    }
    .ui-autocomplete ul{
        background-color: #5d8dff; font-size:14px;
    }
    li a{
        color: #fff;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    <?php if(empty($data['image'])) {?>
        #img_pre {
            display : none;
        }
    <?php }
    if($view == true) { ?>
        label.form-label span { display:none !important; color:transporant; }
    <?php } ?>
            
    span.form-control {
        height: 0px;
    }
    .dropdown-menu.open {
        max-height: none !important;
    }
</style>


<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2> Prasadam Setting<small>Prasadam / <b> Add Prasadam Setting</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"><!--<h2>Add Prasadam Settings</h2>--></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/prasadamsetting"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <form action="<?php echo base_url(); ?>/prasadamsetting/save" method="POST" enctype='multipart/form-data'>
                    <div class="body">
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-5">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="name_eng"  class="form-control" value="<?php echo $data['name_eng'];?>" <?php echo $readonly; ?> >
                                        <label class="form-label"> Name For English <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="name_tamil"  class="form-control" value="<?php echo $data['name_tamil'];?>" <?php echo $readonly; ?> >
                                        <label class="form-label"> Name for Tamil <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input maxlength="4" minlength="4" type="text" name="shortcode" id="shortcode" class="form-control"
                                                    value="<?php echo $data['shortcode']; ?>" <?php echo $readonly; ?>
                                                    required>
                                                    
                                                <label class="form-label">
                                                    <?php echo "Shortcode" ?>
                                                     <span
                                                        style="color: red;">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div style="margin-left: 19px;">
                                        <input type="checkbox" checked="true" disabled style="postion:fixed;left:0px;opacity:1;margin-left:17px" id="indoor" name="indoor" value="1" /> Indoor <span style="color: red;">*</span>
                                       
                                    </div>
                                    <div>
                                        <input type="text" class="float_valid" value="<?php echo $data['amount']; ?>" id="amount" name="amount" placeholder="Rate" /> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group"> 
                                    <div style="margin-left: 19px;">
                                        <input onclick="shout_rate()" <?php echo ((isset($data['outdoor']) && $data['outdoor'] == 1)?'checked':''); ?> type="checkbox" style="postion:fixed;left:0px;opacity:1;margin-left:17px" id="outdoor" name="outdoor" value="1" /> Outdoor 
                                        <span id="out_rate_err_div" style="color: red;display:none">*</span>
                                    </div>
                                    <div id="outdoor_div" <?php echo ((isset($data['outdoor']) && $data['outdoor'] == 1)?'style="display:block"':'style="display:none"'); ?>>
                                        <input type="text" value="<?php echo $data['out_rate']; ?>" class="float_valid" id="out_rate" name="out_rate" placeholder="Rate" /> 
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" step=".01" name="amount" class="form-control" value="<?php //echo $data['amount'];?>" <?php //echo $readonly; ?> >
                                        <label class="form-label">Amount <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>-->
                            <!-- <div class="col-sm-3">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select name="groupname" class="form-control">
                                            <option value="">-- Select Prasadam Group --</option>
                                            <?php //foreach ($group as $row) { ?>
                                                <option value="<?php echo $row['name']; ?>" <?php echo ($row['name'] == $data['groupname']) ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                                            <?php //} ?>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-sm-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select name="groupid[]" id="groupid" class="form-control" >
                                            <option value="">-- Select Prasadam Group --</option>
                                            <?php 
                                            foreach($group as $row) {
                                                $explode_ar_dt = explode(",",$data['group_id']);
                                                if(in_array($row['id'],$explode_ar_dt)){
                                                    $selected = "selected";
                                                }
                                                else {
                                                    $selected = "";
                                                }
                                                ?>
                                            <option value="<?php echo $row['id']; ?>"<?php echo $selected; ?>><?php echo strtoupper($row['name']); ?></option>
                                            <?php 
                                            } 
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php if ($view != true) { ?>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="form-label" style="display: contents;">Image</label>
                                        <input type="file" id="imgInp" name="prasadam_image" accept="image/png,image/jpeg,image/jpg" class="form-control" <?php echo $readonly; ?> >
                                        <!--<label class="form-label">Image</label>-->
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="">
											<?php if(!empty($data['image'])) {?>
                                                <a target="_blank" href="/uploads/prasadam_setting/<?php echo $data['image']; ?>">
                                                    <img id="img_pre" src="/uploads/prasadam_setting/<?php echo $data['image']; ?>" width="200" height="160"></img>
                                                </a>
											<?php } else { ?> 
												
												<!--<a id="img_anchor" target="_blank" href="#"> -->
                                                    <img id="img_pre" src="#" width="200" height="160"></img>
                                                <!--</a>  -->
											<?php }?>												
                                            </div>
                                        </div>
                            </div> 
                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control search_box" data-live-search="true" name="ledger_id" id="ledger_id">
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

                        <!--div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="">
                                        <input type="checkbox" id="alpha_stock" name="stock_prasadam"  class="form-control" <?php echo $disable; ?> value="1" <?php if($data['dedection_from_stock'] == 1){ echo "checked"; }?>>
                                        <label for="alpha_stock" class="form-label">Dedection from Raw Material</label>
                                    </div>
                                </div>
                            </div>
                        </div-->
                    </div>
                    </div>
                    </form>
                    <?php if($view != true) { ?>
                    <div class="col-sm-12" align="center" style="background-color: white;padding-bottom: 1%;">
                        <button type="submit" onclick="validations()" class="btn btn-success btn-lg waves-effect">SAVE</button>
                        <button type="button" id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                    </div>
                    <?php } ?>
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
                </div>
            </div>
        </div>
    </div>
</section> 
<script>
function shout_rate()
{
    if($("#outdoor").is(":checked"))
    {
        $("#outdoor_div").show();
        $("#out_rate_err_div").show();
    }
    else
    {
        $("#outdoor_div").hide();   
        $("#out_rate_err_div").hide();
    }
}
    $("#clear").click(function(){
		
       $("input").val("");
    });
		
		$("#imgInp").change(function(){
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
function validations(){
    $.ajax
        ({
        type:"POST",
        url: "<?php echo base_url(); ?>/prasadamsetting/prasadam_setting_validation",
        data: $("form").serialize(),
        success:function(data)
        {
            obj = jQuery.parseJSON(data);
            console.log(obj);
            if(obj.err != ''){
                $('#alert-modal').modal('show', {backdrop: 'static'});
                $("#spndeddelid").text(obj.err);
            }else{
                $('input[type=submit]').prop('disabled', true);
                $("#loader").show();
                $("form").submit();
            }
        }
    })
        
}
</script>
