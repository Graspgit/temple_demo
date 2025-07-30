<?php 
if($view == true){
    $readonly = 'readonly';
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Archanai<small>Archanai / <b>Group</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                            <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/archanai/group_list"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <form action="<?php echo base_url(); ?>/archanai/save_group" method="POST">
                    <div class="body">
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" name="name" value="<?php echo strtoupper($data['name']);?>" <?php echo $readonly; ?>>
                                        <label class="form-label">Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number"  class="form-control" name="order_no" value="<?php echo strtoupper($data['order_no']);?>" <?php echo $readonly; ?>>
                                        <label class="form-label">Order No</label>
                                    </div>
                                </div>
                            </div>
                            <?php /* <div class="col-sm-6">
								<div class="form-group form-float">
									<div class="">
										<br><input type="checkbox" id="rasi" value="1" name="rasi"  <?php if($data['rasi'] == 1){ echo "checked"; }?> class="form-control" >
										<label for="rasi" class="form-label">Rasi & Natchathram </label>
									</div>
								</div>
							</div> */ ?>
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
<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<script>
	$("#clear").click(function(){
	   $("input").val("");
	});
	$("form").on("submit", function(){
        $('input[type=submit]').prop('disabled', true);
        $("#loader").show();
    });
</script>