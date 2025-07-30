<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2>PROFILE<small>Payment Mode Setting / <b>Add</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/paymentmodesetting"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <div class="body">
                    <form action="<?php echo base_url(); ?>/paymentmodesetting/store" method="POST" id="form_validation">
						<input type="hidden" value="<?php echo isset($payment_mode['id']) ? $payment_mode['id'] : ""; ?>" name="id" id="updateid">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($payment_mode['name']) ? $payment_mode['name'] : ""; ?>" required oninput="this.value = this.value.toUpperCase();">
                                        <label class="form-label">Name <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
										<textarea name="description" id="description" class="form-control" required><?php echo isset($payment_mode['description']) ? $payment_mode['description'] : ""; ?></textarea>
                                        <label class="form-label">Description</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div>
                                    <label class="floating-label" for="Text">Modules <span style="color:red;">*</span></label>
                                </div>    
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['archanai']) && $payment_mode['archanai'] == 1) echo "checked"; ?> name="archanai" type="checkbox" value="1" id="archanai">
                                    <label class="form-check-label" for="archanai">
                                        Archanai
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['prasadam']) && $payment_mode['prasadam'] == 1) echo "checked"; ?> name="prasadam" type="checkbox" value="1" id="prasadam">
                                    <label class="form-check-label" for="prasadam">
                                        Prasadam
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['annathanam']) && $payment_mode['annathanam'] == 1) echo "checked"; ?> name="annathanam" type="checkbox" value="1" id="annathanam">
                                    <label class="form-check-label" for="annathanam">
                                        Annathanam
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['donation']) && $payment_mode['donation'] == 1) echo "checked"; ?> name="donation" type="checkbox" value="1" id="donation">
                                    <label class="form-check-label" for="donation">
                                        Cash Donation
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['hall_booking']) && $payment_mode['hall_booking'] == 1) echo "checked"; ?> name="hall_booking" type="checkbox" value="1" id="hall_booking">
                                    <label class="form-check-label" for="hall_booking">
                                        Hall Booking
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['ubayam']) && $payment_mode['ubayam'] == 1) echo "checked"; ?> name="ubayam" type="checkbox" value="1" id="ubayam">
                                    <label class="form-check-label" for="ubayam">
                                        Ubayam
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['kattalai_archanai']) && $payment_mode['kattalai_archanai'] == 1) echo "checked"; ?> name="kattalai_archanai" type="checkbox" value="1" id="kattalai_archanai">
                                    <label class="form-check-label" for="kattalai_archanai">
                                        Kattalai Archanai
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['outdoor']) && $payment_mode['outdoor'] == 1) echo "checked"; ?> name="outdoor" type="checkbox" value="1" id="outdoor">
                                    <label class="form-check-label" for="outdoor">
                                        Outdoor Services
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['catering']) && $payment_mode['catering'] == 1) echo "checked"; ?> name="catering" type="checkbox" value="1" id="catering">
                                    <label class="form-check-label" for="catering">
                                        Catering
                                    </label>
                                </div>
                                <div class="form-check" style="width:180px; float:left;">
                                    <input class="form-check-input" <?php if (!empty($payment_mode['expenses']) && $payment_mode['expenses'] == 1) echo "checked"; ?> name="expenses" type="checkbox" value="1" id="expenses">
                                    <label class="form-check-label" for="expenses">
                                        Expenses
                                    </label>
                                </div>

                            </div>
							<div style="clear:both"></div>
                            <div class="col-sm-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select name="ledger_name" id="ledger_name" class="form-control" required>
											<option value="">select ledger</option>
											<?php
											foreach($ledgers as $ledger)
											{
											?>
											<option value="<?php echo $ledger['id']; ?>" <?php if(isset($payment_mode['ledger_id'])){ if($payment_mode['ledger_id'] == $ledger['id']){ echo "selected"; } }?>><?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?></option>
											<?php
											}
											?>
										</select>
                                        <!--label class="form-label">Ledger<span style="color: red;">*</span></label-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control" name="status" >
                                            <option>-- Select Status --</option>
                                            <option value="1" <?php if($payment_mode['status'] == '1'){ echo "selected"; } ?>>Active</option>
                                            <option value="0" <?php if($payment_mode['status'] == '0'){ echo "selected"; } ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-2">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" min="0" name="order" id="order" class="form-control" value="<?php echo isset($payment_mode['menu_order']) ? $payment_mode['menu_order'] : ""; ?>">
                                        <label class="form-label">Order</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control" name="paid_through" >
                                            <option>-- Paid Through --</option>
                                            <option value="DIRECT" <?php if($payment_mode['paid_through'] == 'DIRECT'){ echo "selected"; } ?>>DIRECT</option>
                                            <option value="COUNTER" <?php if($payment_mode['paid_through'] == 'COUNTER'){ echo "selected"; } ?>>COUNTER</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" align="center" style="background-color: white;padding-bottom: 1%;">
                            <button type="submit" class="btn btn-success btn-lg waves-effect">SUBMIT</button>
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
$('#form_validation').validate({
	rules: {
		"name": {
			required: true,
		},
		"ledger_name": {
			required: true,
			/*remote: {
				url: "<?php echo base_url(); ?>/paymentmodesetting/findledgernameExists",
				data: {
					update_id: function() {
						return $("#updateid").val();
					},
					ledger_name: $(this).data('ledger_name')
				},
				type: "post",
			},*/
		},
	},
	messages: {
		"name": {
			required: "name is required"
		},
		"ledger_name": {
			required: "ledger name is required"
			//remote: "already ledger name exist"
		}
	},
	highlight: function (input) {
		$(input).parents('.form-line').addClass('error');
	},
	unhighlight: function (input) {
		$(input).parents('.form-line').removeClass('error');
	},
	errorPlacement: function (error, element) {
		$(element).parents('.form-group').append(error);
	}
});


</script>