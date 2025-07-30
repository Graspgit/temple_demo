<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2>Inventory<small>Receipt and Invoice / <b>Add</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/Receipt_and_Voucher"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <div class="body">
                    <form action="<?php echo base_url(); ?>/Receipt_and_Voucher/store" method="POST" id="form_validation">
						<input type="hidden" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ""; ?>" name="id" id="updateid">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select onchange="getCustOrSupp(this.value)" name="rv_type" id="rv_type" class="form-control" value="<?php echo isset($supplier['rv_type']) ? $supplier['rv_type'] : ""; ?>" required <?php echo $readonly; ?>>
                                        <!--<option value="">Select Invoice Type</option>-->
                                        <option value="1">Receipt</option>
                                        <option value="2">Paymnet Voucher</option>
                                        </select>
                                        <label class="form-label">Type <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="date" name="rv_date" id="rv_date" class="form-control" value="<?php echo isset($supplier['rv_date']) ? $supplier['rv_date'] : ""; ?>" required <?php echo $readonly; ?>>
                                        <label class="form-label"></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select onchange="getCSAmount()" name="name" id="name" class="form-control" required  <?php echo $readonly; ?>>
                                        <option value="">Customer <span style="color: red;">*</span></option>
                                        </select>
                                        <label id="customer_supplier_id_label" class="form-label"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="amount" id="amount" class="form-control float_valid" value="<?php echo isset($supplier['amount']) ? $supplier['amount'] : ""; ?>" required <?php echo $readonly; ?>>
                                        <label class="form-label">Amount</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="balance_amount" id="balance_amount" class="form-control float_valid" value="" readonly <?php echo $readonly; ?>>
                                        <label class="form-label">Balance Amount</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="remarks" id="remarks" class="form-control" value="<?php echo isset($supplier['remarks']) ? $supplier['remarks'] : ""; ?>" <?php echo $readonly; ?> ><?php echo isset($supplier['remarks']) ? $supplier['remarks'] : ""; ?></textarea>
                                        <label class="form-label">Remarks </label>
                                    </div>
                                </div>
                            </div>
                           
                            <div style="clear:both"></div>
                            
							<div style="clear:both"></div>
                        </div>
                        <?php if($view != true) { ?>
                        <div class="col-sm-12" align="center" style="background-color: white;padding-bottom: 1%;">
                            <button type="submit" class="btn btn-success btn-lg waves-effect">SUBMIT</button>
                        </div>
                        <?php } ?>
                    </div>
                    </form>
                    
                    </div>
             
            </div>
        </div>
    </div>
    </div>

</section>
<script>
function getCSAmount()
{
    var customer_supplier_id = $("#name").val();
    var invoice_type = $("#rv_type").val();
    $.post("<?php echo base_url() ?>/invoice/getCSBalAmount",{customer_supplier_id:customer_supplier_id,invoice_type:invoice_type},(res)=>{
     $("#balance_amount").val(res|0);
 }) 
}
function getCustOrSupp(invoice_type)
{
 var name = ("<?php echo (isset($supplier["name"])?$supplier["name"]:0); ?>")|0;
 $.post("<?php echo base_url() ?>/invoice/getCustOrSupp",{invoice_type:invoice_type,name:name},(res)=>{
     //console.log("rr",res);
     $("#name").html(res);
     $('#name').selectpicker('refresh');
     
     if(name > 0)
        getCSAmount();
 })   
}
$(document).ready(()=>{
    getCustOrSupp($("#rv_type").val());
})

$('#form_validation').validate({
	rules: {
		"name": {
			required: true,
		},
		"contact_person": {
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
        "contact_person": {
			required: "contact person is required"
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