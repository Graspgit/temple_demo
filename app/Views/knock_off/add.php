<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<style>
table tr th
{
    text-align:center;
}
.payment-table {
    margin-top: 20px;
}
.payment-table table {
    width: 100%;
    border-collapse: collapse;
}
.payment-table th, .payment-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
.payment-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}
.form-section {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
</style>
<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2>Account<small>Knock-off / <b>Add</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a id="knock_off_type_list" href="<?php echo base_url(); ?>/Knock_off"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <div class="body">
                    <form action="<?php echo base_url(); ?>/Knock_off/store" method="POST" id="form_validation">
						<input type="hidden" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ""; ?>" name="id" id="updateid">
						<input type="hidden" value="" name="hid_amount" id="hid_amount">
						<input type="hidden" value="" name="inv_detail_ids" id="inv_detail_ids">
                        
                        <div class="form-section">
                            <h4>Knock-off Details</h4>
                            <div class="container-fluid">
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select onchange="getCustOrSupp(this.value)" name="type" id="type" class="form-control" required <?php echo $disable; ?>>
                                                <option <?php echo ((isset($supplier['type']) && $supplier['type'] == 1)?"selected" :""); ?> value="1">Sales Receipt</option>
                                                <option <?php echo ((isset($supplier['type']) && $supplier['type'] == 2)?"selected" :""); ?> value="2">Purchase Payment</option>
                                            </select>
                                            <label class="form-label">Knock-off Type <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select onchange="getInvoices()" name="supplier_id" id="supplier_id" class="form-control" required <?php echo $disable; ?>>
                                                <option value="">Select Customer/Supplier <span style="color: red;">*</span></option>
                                            </select>
                                            <label id="customer_supplier_id_label" class="form-label">Customer/Supplier</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select onchange="getAmt()" name="invoice_id" id="invoice_id" class="form-control" required <?php echo $disable; ?>>
                                                <option value="">Select Invoice <span style="color: red;">*</span></option>
                                            </select>
                                            <label class="form-label">Invoice</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" id="due_amount" name="due_amount" class="form-control float_valid" readonly="true" value="0.00" placeholder="Due Amount" />
                                            <label class="form-label">Due Amount</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h4>Available Payments/Receipts</h4>
                            <p class="text-muted">Select the payments/receipts you want to knock-off against the selected invoice:</p>
                            <div id="res" class="payment-table">
                                <!-- Payment/Receipt list will be loaded here -->
                            </div>
                        </div>
                        
                        <?php if($view != true) { ?>
                        <div class="col-sm-12" align="center" style="background-color: white;padding-bottom: 1%;">
                            <button type="submit" class="btn btn-success btn-lg waves-effect" id="submit_btn" disabled>SUBMIT KNOCK-OFF</button>
                        </div>
                        <?php } ?>
                    </form>
                    
                    </div>
             
            </div>
        </div>
    </div>
    </div>

</section>
<script>
var inv_detail_ids = [];

function getAmt()
{
    var invoice_id = $("#invoice_id").val();
    var type = $("#type").val();
    
    if(invoice_id && type) {
        $.post("<?php echo base_url() ?>/Knock_off/getAmt",{type:type,invoice_id:invoice_id},(res)=>{
            $("#due_amount").val(parseFloat(res).toFixed(2));
            getList(); // Load available payments/receipts
        });
    }
}

function getCustOrSupp(invoice_type)
{
    var name = ("<?php echo (isset($supplier["supplier_id"])?$supplier["supplier_id"]:0); ?>")|0;
    $.post("<?php echo base_url() ?>/invoice/getCustOrSupp",{invoice_type:invoice_type,name:name},(res)=>{
        $("#supplier_id").html(res);
        $('#supplier_id').selectpicker('refresh');
        
        // Update label based on type
        if(invoice_type == 1) {
            $("#customer_supplier_id_label").text("Customer");
        } else {
            $("#customer_supplier_id_label").text("Supplier");
        }
        
        if(name > 0) {
            getInvoices();
        }
    });   
}

function getInvoices()
{
    var invoice_id = ("<?php echo (isset($supplier["invoice_id"])?$supplier["invoice_id"]:0); ?>")|0;
    var knock_off_id = ("<?php echo (isset($supplier["id"])?$supplier["id"]:0); ?>")|0;
    var supplier_id = $("#supplier_id").val();
    var type = $("#type").val();
    
    if(supplier_id && type) {
        $.post("<?php echo base_url() ?>/Knock_off/getInvoices",{type:type,invoice_id:invoice_id,supplier_id:supplier_id,knock_off_id:knock_off_id},(res)=>{
            try {
                res = (typeof res.list == "undefined" ? JSON.parse(res) : res);
                var list = res.list;
                var invoice = res.invoice;
                
                $("#res").html(list);
                $("#invoice_id").html(invoice);
                $('#invoice_id').selectpicker('refresh');
                
                if(invoice_id > 0) {
                    $("#invoice_id").val(invoice_id);
                    getAmt();
                }
                
                calculateTotal();
            } catch(e) {
                console.error("Error parsing response:", e);
                $("#res").html("<p class='text-danger'>Error loading payments/receipts</p>");
            }
        });
    }
}

function getList()
{
    var invoice_id = $("#invoice_id").val();
    var knock_off_id = ("<?php echo (isset($supplier["id"])?$supplier["id"]:0); ?>")|0;
    var supplier_id = $("#supplier_id").val();
    var type = $("#type").val();
    
    if(invoice_id && supplier_id && type) {
        $.post("<?php echo base_url() ?>/Knock_off/getList",{type:type,invoice_id:invoice_id,supplier_id:supplier_id,knock_off_id:knock_off_id},(res)=>{
            try {
                res = (typeof res.list == "undefined" ? JSON.parse(res) : res);
                var list = res.list;
                $("#res").html(list);
                calculateTotal();
            } catch(e) {
                console.error("Error parsing response:", e);
                $("#res").html("<p class='text-danger'>Error loading payments/receipts</p>");
            }
        });
    }
}

// Handle head checkbox click
$("#res").on("click","#head_checkbox",(evt)=>{
    var isChecked = $(evt.target).is(":checked");
    $(".sub_checkbox").each((i,ele)=>{
        $(ele).prop("checked", isChecked);
    });
    calculateTotal();
});

// Handle individual checkbox click
$("#res").on("click",".sub_checkbox",(evt)=>{
    calculateTotal();
    
    // Update head checkbox state
    var totalCheckboxes = $(".sub_checkbox").length;
    var checkedCheckboxes = $(".sub_checkbox:checked").length;
    
    if(checkedCheckboxes === totalCheckboxes) {
        $("#head_checkbox").prop("checked", true);
    } else {
        $("#head_checkbox").prop("checked", false);
    }
});

function calculateTotal()
{
    var due_amount = parseFloat($("#due_amount").val()) || 0;
    var total_selected = 0;
    inv_detail_ids = [];
    
    $(".sub_checkbox:checked").each((i,ele)=>{
        var curtr = $(ele).closest("tr");
        var amount = parseFloat($(curtr).find("#amt").text().replace(/,/g, '')) || 0;
        total_selected += amount;
        
        var entry_id = $(curtr).find("#entry_id").val();
        if(entry_id) {
            inv_detail_ids.push(entry_id);
        }
    });
    
    // Validate amount doesn't exceed due amount
    if(total_selected > due_amount) {
        alert("Selected amount (" + total_selected.toFixed(2) + ") cannot exceed due amount (" + due_amount.toFixed(2) + ")");
        $(".sub_checkbox").last().prop("checked", false);
        calculateTotal(); // Recalculate
        return;
    }
    
    $("#hid_amount").val(total_selected.toFixed(2));
    $("#inv_detail_ids").val(inv_detail_ids.join(","));
    $("#total").text(total_selected.toFixed(2));
    
    // Enable/disable submit button
    if(total_selected > 0 && inv_detail_ids.length > 0) {
        $("#submit_btn").prop("disabled", false);
    } else {
        $("#submit_btn").prop("disabled", true);
    }
}

$(document).ready(()=>{
    var req_typ = "<?php echo isset($_REQUEST['req_typ']) ? $_REQUEST['req_typ'] : '1'; ?>";
    if(req_typ == 2) {
        $("#type").val(2);
        var url = "<?php echo base_url(); ?>/Knock_off/index_purchase";
        $("#knock_off_type_list").attr("href",url);
    }
    
    // Initialize with pre-filled data if editing
    var supplier_id = ("<?php echo (isset($supplier["supplier_id"])?$supplier["supplier_id"]:0); ?>")|0;
    if(supplier_id > 0) {
        getCustOrSupp($("#type").val());
    } else {
        getCustOrSupp($("#type").val());
    }
});

$('#form_validation').validate({
	rules: {
		"type": {
			required: true,
		},
		"supplier_id": {
			required: true,
		},
		"invoice_id": {
			required: true,
		}
	},
	messages: {
		"type": {
			required: "Knock-off type is required"
		},
        "supplier_id": {
			required: "Customer/Supplier is required"
		},
		"invoice_id": {
			required: "Invoice is required"
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
	},
	submitHandler: function(form) {
		var selectedAmount = parseFloat($("#hid_amount").val()) || 0;
		var entryIds = $("#inv_detail_ids").val();
		
		if(selectedAmount <= 0 || !entryIds) {
			alert("Please select at least one payment/receipt to knock-off");
			return false;
		}
		
		if(confirm("Are you sure you want to proceed with this knock-off?")) {
			form.submit();
		}
	}
});
</script>