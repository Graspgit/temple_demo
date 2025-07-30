<?php  global $lang; ?>
<style>
    .ui-autocomplete {
        padding: 0px !important;
    }

    .ui-autocomplete ul {
        background-color: #5d8dff;
        font-size: 14px;
    }

    li a {
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

    <?php if (empty($data['image'])) { ?>
        #img_pre {
            display: none;
        }

    <?php }
    if ($view == true) { ?>
        label.form-label span {
            display: none !important;
            color: transporant;
        }

    <?php } ?>
</style>
<style>
 span.form-control {
     height: 0px;
   }
   .dropdown-menu.open {
     max-height: none !important;
   }
</style>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <?php echo $lang->archanai;
                echo $lang->setting; ?><small>
                    <?php echo $lang->archanai; ?> / <b> Add
                        <?php echo $lang->archanai;
                        echo $lang->setting; ?>
                    </b>
                </small>
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"><!--<h2>Add Archanai Settings</h2>--></div>
                            <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/archanai"><button
                                        type="button" class="btn bg-deep-purple waves-effect">List</button></a></div>
                        </div>
                    </div>
                    <form id="form1" action="<?php echo base_url(); ?>/archanai/save" method="POST" enctype='multipart/form-data'>
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="name_eng" class="form-control"
                                                    value="<?php echo $data['name_eng']; ?>" <?php echo $readonly; ?>
                                                    required>
                                                <label class="form-label">
                                                    <?php echo $lang->archanai . " " . $lang->name; ?> In English <span
                                                        style="color: red;">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="name_tamil" class="form-control"
                                                    value="<?php echo $data['name_tamil']; ?>" <?php echo $readonly; ?>
                                                    required>
                                                <label class="form-label">
                                                    <?php echo $lang->archanai . " " . $lang->name; ?> In Tamil <span
                                                        style="color: red;">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" id="amount" step=".01" name="amount"
                                                    class="form-control" value="<?php echo $data['amount']; ?>" <?php echo $readonly; ?> required>
                                                <label class="form-label">
                                                    <?php echo $lang->amount; ?> <span style="color: red;">*</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" name="com_per" id="com_per" class="form-control"
                                                    value="<?php echo $data['commission_percentage']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">
                                                    <?php echo $lang->commission; ?> (%)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="number" step=".01" id="commission" name="commission"
                                                    class="form-control" value="<?php echo $data['commission']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">
                                                    <?php echo $lang->commission; ?> (
                                                    <?php echo $lang->amount; ?>)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select name="groupname" id="groupname" class="form-control">
                                                    <option value="">Select Group Name</option>
                                                    <?php foreach ($archanai_group as $row) { ?>
                                                        <option value="<?php echo $row['name']; ?>" <?php if ($data['groupname'] == $row['name']) {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo strtoupper($row['name']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <!--input type="text" name="groupname" id="groupname" class="form-control" value="<?php echo $data['groupname']; ?>" <?php echo $readonly; ?> >
                                        <label class="form-label">Group Name <span style="color: red;">*</span></label-->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="order_no" class="form-control"
                                                    value="<?php echo $data['order_no']; ?>" <?php echo $readonly; ?>>
                                                <!--<input type="hidden" name="order_no" class="form-control" value="<?php echo $data['order_no']; ?>" <?php echo $readonly; ?> >-->
                                                <label class="form-label">Order Number </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control" id="archanai_category"
                                                    name="archanai_category" <?php echo $disable; ?> required>
                                                    <option value="">Select
                                                        <?php echo $lang->archanai . " " . $lang->category; ?>
                                                    </option>
                                                    <?php
                                                    if (!empty($archanai_categories)) {
                                                        foreach ($archanai_categories as $archanai_categorie) {
                                                            ?>
                                                            <option value="<?php echo $archanai_categorie['id']; ?>" <?php if (!empty($data['archanai_category'])) {
                                                                   if ($data['archanai_category'] == $archanai_categorie['id']) {
                                                                       echo "selected";
                                                                   }
                                                               } ?>>
                                                                <?php echo $archanai_categorie['name']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <!--label class="form-label">Archanai Category</label-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <input type="checkbox" id="sep_grouping" name="sep_grouping" value="1" <?php if($data['is_sep_group'] == 1) echo 'checked' ?> class="form-control" <?php echo $disable; ?>>
                                                <label for="sep_grouping" class="form-label">Seperate Grouping</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group form-float" style="margin: 10px;">
                                            <div class="form-line">
                                                <!--<label>Comission To</label>-->
                                                <select class="form-control" name="comission_to">
                                                    <option value="0">Select Staff for Commission</option>
                                                    <?php if (is_array($staff) && !empty($staff)): ?>
                                                        <?php foreach ($staff as $row): ?>
                                                            <option value="<?php echo $row['id']; ?>" <?php echo (isset($data['comission_to']) && $data['comission_to'] == $row['id']) ? ' selected="selected"' : ''; ?>>
                                                                <?php echo $row['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($view != true) { ?>
                                        <div class="col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <label class="form-label" style="display: contents;">Image</label>
                                                    <input type="file" id="imgInp" name="archanai_image"
                                                        accept="image/png,image/jpeg,image/jpg" class="form-control" <?php echo $readonly; ?>>
                                                    <!--<label class="form-label">Image</label>-->
                                                    
                                                    </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <?php if (!empty($data['image'])) { ?>
                                                    <a target="_blank"
                                                        href="<?php echo base_url(); ?>/uploads/archanai/<?php echo $data['image']; ?>">
                                                        <img id="img_pre"
                                                            src="<?php echo base_url(); ?>/uploads/archanai/<?php echo $data['image']; ?>"
                                                            width="200" height="160"></img>
                                                    </a>
                                                    <button type="button" class="btn btn-warning btn-sm mt-1" onclick="removeSelectedFile('imgInp', 'img_pre', <?= $data['id']; ?>, 'image')">Remove</button>

                                                <?php } else { ?>

                                                    <!--<a id="img_anchor" target="_blank" href="#"> -->
                                                    <img id="img_pre" src="#" width="200" height="160"></img>
                                                    <!--</a>  -->
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($view != true) { ?>
                                        <div class="col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <label class="form-label" style="display: contents;">Watermark Image</label>
                                                    <input type="file" id="imgInp_watermark" name="watermark_image"
                                                        accept="image/jpeg,image/jpg" class="form-control" <?php echo $readonly; ?>>
                                                       
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <?php if (!empty($data['watermark_image'])) { ?>
                                                    <a target="_blank"
                                                        href="<?php echo base_url(); ?>/uploads/archanai/watermark/<?php echo $data['watermark_image']; ?>">
                                                        <img id="img_pre_watermark" src="<?php echo base_url(); ?>/uploads/archanai/watermark/<?php echo $data['watermark_image']; ?>" width="200" height="160"></img>  
                                                    </a>
                                                    <button type="button" class="btn btn-warning btn-sm mt-1" onclick="removeSelectedFile('imgInp_watermark', 'img_pre_watermark', <?= $data['id']; ?>, 'watermark')">Remove</button>


                                                <?php } else { ?>

                                                    <img id="img_pre_watermark" src="#" width="200" height="160"></img>
                                                  
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <!-- <label class="form-label"  style="display: contents;">Image</label> -->
                                                <input type="checkbox" id="alpha" name="view_archanai" class="form-control" <?php echo $disable; ?>>
                                                <label for="alpha" class="form-label">Hide Archanai</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row clearfix">
  <div class="col-sm-12" id="kalanji_option_container" style="display:none;">
    <div class="col-sm-4">
      <div class="form-group">
        <label>Kalanji Option:</label>
        <span>
          <input type="radio" name="settings[1][kazhanji_option]" id="kazhanji_text" value="text" <?php echo !empty($data['kazhanji_option']) ? ($data['kazhanji_option'] == 'text' ? 'checked="checked"' : '') : ''; ?>>
          <label for="kazhanji_text">Text</label>
        </span>
        <span>
          <input type="radio" name="settings[1][kazhanji_option]" id="kazhanji_image" value="image" <?php echo !empty($data['kazhanji_option']) ? ($data['kazhanji_option'] == 'image' ? 'checked="checked"' : '') : ''; ?>>
          <label for="kazhanji_image">Image</label>
        </span>
      </div>
    </div>
    <div class="col-sm-4 kazhanji_option_sec">
      <div class="kazhanji_option_text" <?php echo !empty($data['kazhanji_option']) ? ($data['kazhanji_option'] == 'text' ? 'style="display: block;"' : '') : ''; ?>>
        <input type="text" class="form-control" name="settings[1][kazhanji_option_text]" id="kazhanji_option_text" value="<?php echo !empty($data['kazhanji_option_text']) ? $data['kazhanji_option_text'] : ''; ?>">
      </div>
      <div class="kazhanji_option_image" <?php echo !empty($data['kazhanji_option']) ? ($data['kazhanji_option'] == 'image' ? 'style="display: block;"' : '') : ''; ?>>
        <input type="file" name="archanai_kazhanji_upload" id="archanai_kazhanji_upload" />
        <input type="hidden" name="settings[1][kazhanji_option_image]" id="kazhanji_option_image" value="<?php echo !empty($data['kazhanji_option_image']) ? $data['kazhanji_option_image'] : ''; ?>">
        <?php echo !empty($data['kazhanji_option_image']) ? '<p style="margin-top: 15px;"><img src="' . base_url() . '/uploads/kazhanji/' . $data['kazhanji_option_image'] . '" style="max-width:172px;"></p>' : ''; ?>
      </div>
    </div>
  </div>
</div>

<div class="row clearfix" id="queue_section" <?php echo !empty($data['is_limit']) ? ($data['is_limit'] == '1' ? 'style="display: block;"' : '') : ''; ?> style="display:none">
    <div class="col-sm-2">
        <div class="form-group form-float">
            <div class="">
                <input type="checkbox" id="queue_limit" name="is_limit"  class="form-control" <?php echo $disable; ?> value="1" <?php if($data['is_limit'] == 1){ echo "checked"; }?>>
                <label for="alpha_stock" class="form-label">Limit</label>
            </div>                                
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line">
                <select name="limit_type" id="limit_type" class="form-control" >
                    <option value="">Select Type</option>
                        <?php 
                            foreach($limit_type as $row) {
                                $explode_ar_type = explode(",",$data['limit_type']);
                                    if(in_array($row,$explode_ar_type)){
                                        $selected = "selected";
                                    }
                                    else {
                                        $selected = "";
                                    }
                        ?>
                        <option value="<?php echo $row; ?>"<?php echo $selected; ?>><?php echo strtoupper($row); ?></option>
                        <?php }?>
                </select>
            </div>
        </div>
    </div>

    
	<div class="col-sm-2">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" name="limit_count" id="limit_ct" class="form-control"
                                                    value="<?php echo $data['limit_count']; ?>">
                
            </div>
        </div>
    </div>
			
    
</div>

                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <input type="checkbox" id="alpha_stock" name="stock_archanai"  class="form-control" <?php echo $disable; ?> value="1" <?php if($data['dedection_from_stock'] == 1){ echo "checked"; }?>>
                                                <label for="alpha_stock" class="form-label">Dedection from Raw Material</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select name="diety_id[]" id="diety_id" class="form-control" multiple="multiple">
                                                    <option value="">Select Deity</option>
                                                    <?php 
                                                    foreach($archanai_diety as $row) {
                                                        $explode_ar_dt = explode(",",$data['deity_id']);
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
                                    
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <input type="checkbox" id="show_deity" name="show_deity"  class="form-control" <?php echo $disable; ?> value="1" <?php if($data['show_deity'] == 1){ echo "checked"; }?>>
                                                <label for="show_deity" class="form-label">Show Deity in Print</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                               <div class="row clearfix" id="special_section" style="display:none" >
                                   
                                  <div >
                                      <div style="margin-left:8px;float:left">Special Date <span style="color: red;">*</span></div>
                                      <div style="margin-left:9px;float:left"><input type="date" id="special_date" name="special_date" type="date" placeholder="Please Select Date" /></div>
                                  </div>
                                   
                               </div>
<div class="row clearfix"id="description_section" style="display: none;">
    <div class="col-sm-12" style="margin: 0px;">
        <h3 style="margin: 0px 0 10px 0;font-weight: 500;">Description</h3>
    </div>
    <div id="textarea-container-desc">
        <?php
        $hallEditorArray = json_decode($data['description'], true);
        if (empty($hallEditorArray)) {
            $hallEditorArray = [""];
        }

        foreach ($hallEditorArray as $index => $description) {
            ?>
            <div class="col-sm-12 textarea-wrapper" style="margin: 0px; position: relative;">
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea id="desc_editor_json" name="desc_editor[]" class="desc_editor" cols="150"
                            rows="5"><?php echo htmlspecialchars($description); ?><?php echo $readonly; ?></textarea>
                    </div>
                </div>
                <button type="button" class="removeTextareadesc btn btn-danger"
                    style="position: absolute; top: 0; right: 0;">X</button>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-12" align="center">
        <button type="button" id="addTextareadesc" class="btn btn-primary btn-lg waves-effect">+</button>
    </div>
</div>


                                <!--div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="">
                                        <input type="checkbox" id="alpha_stock" name="stock_archanai"  class="form-control" <?php echo $disable; ?> value="1" <?php if ($data['dedection_from_stock'] == 1) {
                                                echo "checked";
                                            } ?>>
                                        <label for="alpha_stock" class="form-label">Dedection from Raw Material</label>
                                    </div>
                                </div>
                            </div>
                        </div-->
                            </div>
                        </div>

                        <?php if ($view != true) { ?>
                            <div class="col-sm-12" align="center" style="background-color: white;padding-bottom: 1%;">
                                <input type="submit"  id="submit" class="btn btn-success btn-lg waves-effect" value="SAVE">
                                <button type="reset" id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                            </div>
                        <?php } ?>
                    </form>
                    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="dripicons-information h1 text-info"></i>
                                        <table>
                                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button
                                                    type="button" class="btn btn-info my-3" data-dismiss="modal">
                                                    &times;</button></tr>
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
<div id="add_edit_category_modal" class="modal custom-category-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F44336;color: #fff;padding: 10px;">
                <h5 class="modal-title">Add New Group Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="top: 20%;margin-top: -24px;opacity: 1.2;">
                    <span aria-hidden="true" style="font-size: 28px;font-weight: bold;color: #fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" action="<?php echo base_url(); ?>/archanai/category_store">

                    <div class="form-group">
                        <div class="form-line">
                            <label>Group Name <span class="text-danger">*</span></label>
                            <input type="text" name="groupname" id="groupname" class="form-control">
                        </div>
                    </div>
                    <div class="submit-section">
                        <input class="btn btn-primary" type="submit" name="category_submit" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
</link>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<?php if ($data['view_archanai'] != 0) { ?>
    <script>
        $('#alpha').prop('checked', false);
        //$('#alpha_stock').prop('checked', false);
    </script>
<?php } ?>
<script>
    function bt_submit()
    {
        if($("#archanai_category").val()=='6' && $("#special_date").val()=="")
        {
            alert("special date is required");
            return;
        }
        //$("#form1").submit();
        //alert();
    }
    $(document).ready(function () {
        $('#amount, #com_per').on('blur', commission_amount);
		$("#ledger_id").selectpicker("refresh");
    });

    function commission_amount() {
        var com_per = ($('#com_per').val() != '') ? $('#com_per').val() : 0;
        var amount = ($('#amount').val() != '') ? $('#amount').val() : 0;
        var com_amount = amount * (com_per / 100);
        $('#commission').val(com_amount.toFixed(2));
    }

    $("#clear").click(function () {

        $("input").val("");
    });

    function addCategory() {
        $('.custom-category-modal').modal();
    }

    $("#imgInp").change(function () {
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
    function validations() {
        $.ajax
            ({
                type: "POST",
                url: "<?php echo base_url(); ?>/master/archanai_validation",
                data: $("form").serialize(),
                success: function (data) {
                    obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if (obj.err != '') {
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text(obj.err);
                    } else {
                        $("#loader").show();
                        $("form").submit();
                    }
                }
            })

    }
</script>
<script type="text/javascript">

    $("#groupname").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo base_url(); ?>/archanai/get_group",
                type: 'post',
                data: { search: request.term },
                dataType: 'json',
                success: function (data) {
                    response(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("error handler!");
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            console.log("Selected: " + ui.item.name);
        }
    });
    $("form").on("submit", function () {
        $("#loader").show();
    });
</script>
<script>
    $(document).ready(function () {
        function toggleKazhanjiOption() {
            var selected = $('input[name="settings[1][kazhanji_option]"]:checked').val();
            if (selected === 'text') {
                $('.kazhanji_option_text').show();
                $('.kazhanji_option_image').hide();
            } else if (selected === 'image') {
                $('.kazhanji_option_text').hide();
                $('.kazhanji_option_image').show();
            } else {
                $('.kazhanji_option_text').hide();
                $('.kazhanji_option_image').hide();
            }
        }

        // Run on page load
        toggleKazhanjiOption();

        // Run on option change
        $('input[name="settings[1][kazhanji_option]"]').on('change', function () {
            toggleKazhanjiOption();
        });
    });
</script>
<script>
$(document).ready(function() {
    

  function toggleKalanjiOption() {
    var selectedCategory = $('#archanai_category').val();
    if (selectedCategory == '3') {
      $('#kalanji_option_container').show();
    } else {
      $('#kalanji_option_container').hide();
    }
    if (selectedCategory == '4') {
     
     $('#queue_section').show();
    } 
  }

  // Run on page load
  toggleKalanjiOption();

  // Run on category change
  $('#archanai_category').change(function() {
    $('#queue_section').hide();
    toggleKalanjiOption();
  });

  $('#limit_ct').on('blur', function(){

    if($('#limit_ct').val() == 300){
        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text('Please Token Limit Exceed');
    } 

  });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addTextareadesc').addEventListener('click', function() {
        var container = document.getElementById('textarea-container-desc');
        var newTextarea = document.createElement('div');
        newTextarea.className = 'col-sm-12 textarea-wrapper';
        newTextarea.style = 'margin: 0px; position: relative;';
        newTextarea.innerHTML = `
            <div class="form-group form-float">
                <div class="form-line">
                    <textarea name="desc_editor[]" class="desc_editor" cols="150" rows="5"><?php echo $hall; ?></textarea>
                </div>
            </div>
            <button type="button" class="removeTextareadesc btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
`;
        container.appendChild(newTextarea);
    });
    document.getElementById('textarea-container-desc').addEventListener('click', function(e) {
        if (e.target && e.target.className.includes('removeTextareadesc')) {
            e.target.closest('.textarea-wrapper').remove();
        }
    });
});
</script>
<script>
$(document).ready(function(){
    $("#special_date").removeProp("required");
    function toggleDescription() {
        $('#special_section').hide();
        $("#special_date").removeProp("required");
        var selectedCategory = $('#archanai_category').val();
        if (selectedCategory == '7') {
            $('#description_section').show();
        }
        else if (selectedCategory == '6') {
            $('#special_section').show();
            $("#special_date").prop("required","true");
        }
        else {
            $('#description_section').hide();
        }
    }

    // Run on page load
    toggleDescription();

    // Run on category change
    $('#archanai_category').change(function(){
        toggleDescription();
    });
});
</script>
<script>
   function removeSelectedFile(inputId, imgId, id, type) {
    if (!confirm("Are you sure you want to mark this file for removal?")) return;

    document.getElementById(inputId).value = "";
    document.getElementById(imgId).src = "#";

    // Set a hidden field to tell the backend to remove this image on submit
    const hiddenFieldId = 'remove_' + type;
    if (!document.getElementById(hiddenFieldId)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = hiddenFieldId;
        input.id = hiddenFieldId;
        input.value = 1;
        document.getElementById('form1').appendChild(input);
    }
}

</script>