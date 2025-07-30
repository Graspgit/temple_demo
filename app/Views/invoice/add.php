<?php


if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
?>
<style>
#description
{
width: 100%;
min-width: 300px;
height: 109px;
}
#type
{
    width:120px;
}
#ledger_id
{
    width:200px;
}
#tax
{
   width:120px; 
}
#rate
{
    width: 81px;
}
#qty
{
    width: 81px;
}
#amount
{
    width: 81px;
}
table tr th
{
    text-align:center;
}
</style>
<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2>Account<small>Invoice / <b>Add</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
					<div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a id="invoice_type_list" href="<?php echo base_url(); ?>/invoice"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <div class="body">
                    <form action="<?php echo base_url(); ?>/invoice/store" method="POST" id="form_validation">
						<input type="hidden" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ""; ?>" name="id" id="updateid">
						<input type="hidden" value="" name="del_arr" id="del_arr">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select onchange="getCustOrSupp(this.value)" name="invoice_type" id="invoice_type" class="form-control" required <?php echo $disable; ?>>
                                        <option <?php echo ((isset($supplier['invoice_type']) && $supplier['invoice_type'] == 1)?"selected" :""); ?> value="1">Sales Invoice</option>
                                        <option <?php echo ((isset($supplier['invoice_type']) && $supplier['invoice_type'] == 2)?"selected" :""); ?> value="2">Purchase Invoice</option>
                                        </select>
                                        <label class="form-label">Invoice Type <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select onchange="getCSAmount()" name="customer_supplier_id" id="customer_supplier_id" class="form-control" required  <?php echo $disable; ?>>
                                        <option value="">Select Customer/Supplier <span style="color: red;">*</span></option>
                                        </select>
                                        <label id="customer_supplier_id_label" class="form-label"></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" readonly name="invoice_no" id="invoice_no" class="form-control" value="<?php echo isset($supplier['invoice_no']) ? $supplier['invoice_no'] : ""; ?>" required <?php echo $readonly; ?> >
                                        <label class="form-label">Reference No <span style="color: red;">*</span></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="date" name="date" id="date" class="form-control" value="<?php echo isset($supplier['date']) ? $supplier['date'] : date('Y-m-d'); ?>" required <?php echo $readonly; ?>>
                                        <label class="form-label"><!--Date <span style="color: red;">*</span>--></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea <?php echo $readonly; ?> name="remarks" id="remarks" class="form-control" placeholder="Remarks"><?php
                                        
                                        echo isset($supplier['remarks']) ? $supplier['remarks'] : ""; ?>
                                         
                                        </textarea>
                                        <label class="form-label">Remarks</label>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(isset($po_no) && $po_no != ""){ ?>
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input readonly name="po_no1" id="po_no1" class="form-control" placeholder="po_no" value="<?php echo $po_no; ?>" />
                                        <label class="form-label">PO No</label>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                        </div>
                        <div style="float:right;padding-right:5px;float: right;margin-right: 10px;" class="row clearfix">
                            <div  class="col-sm-12">
                                <?php if($view == false){ ?>
                                <div style="padding-top: 2px;cursor:pointer;border-radius:50%;background-color:green;color:white;width:25px;height:25px;display:flex;justify-content:center" onclick="add_col()">+</div>
                                <?php } ?>
                            </div>
                        </div>
                        <table>
                            <tr style="background-color:#a1a09f">
                                <th width="25%">Description</th>
                                <th width="10%">Type</th>
                                <th width="15%">Ledger Account <span style="color: red;">*</span></th>
                                <th width="8%">Rate</th>
                                <th width="8%">Qty</th>
                                
                                <?php /*
                                if(($supplier['invoice_type'] == 2 && $purchase_default_tax_ledger >0) ||
                                ($supplier['invoice_type'] == 1 && $sales_default_tax_ledger >0)
                                
                                ){ */?>
                                <th width="8%" class="tax_tr1">Tax %</th>
                                <?php //} ?>
                                <th width="10%">Amount</th>
                                <th width="5%">Action</th>
                            </tr>
                            <tbody id="dyn_table">
                                <?php if(isset($data_list)){
                                foreach($data_list as $iter)
                                {
                                ?>
                                <tr>
                                <td width="25%"><textarea <?php echo $readonly; ?>  id="description" name="description[]" ><?php echo $iter["description"]; ?></textarea></td>
                                <td width="10%">
                                    <select <?php echo $disable; ?> class="calc_tot" onchange="shtax(this)" id="type" name="type[]">
                                        <option value='0'>Select Type</option>
                                        <option <?php echo ($iter["type"] == 1?"selected":""); ?> value='1'>Service</option>
                                        <option <?php echo ($iter["type"] == 2?"selected":""); ?> value='2'>Product</option>
                                    </select>
                                </td>
                                <td width="15%">
                                    <select <?php echo $disable; ?> class="form-control" id="ledger_id" name="ledger_id[]" required>
                                        <option value="">Select Ledger</option>
                                        <?php 
                                        // Show appropriate ledgers based on invoice type
                                        $current_ledgers = isset($supplier['invoice_type']) && $supplier['invoice_type'] == 1 ? $sales_ledgers : $purchase_ledgers;
                                        foreach($current_ledgers as $ledger){ ?>
                                            <option <?php echo ($iter["ledger_id"] == $ledger["id"]?"selected":""); ?> value="<?php echo $ledger['id']; ?>"><?php echo $ledger['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td width="8%"><input <?php echo $readonly; ?> type="text" id="rate" name="rate[]" class="float_valid calc_tot" placeholder="Rate" value="<?php echo $iter["rate"]; ?>" /></td>
                                <td width="8%"><input <?php echo $readonly; ?> type="text" id="qty" name="qty[]" class="float_valid calc_tot" placeholder="Qty" value="<?php echo $iter["qty"]; ?>" /></td>
                                <?php
                                if(($supplier['invoice_type'] == 2 && $purchase_default_tax_ledger >0) ||
                                ($supplier['invoice_type'] == 1 && $sales_default_tax_ledger >0)
                                
                                ){ ?>
                                <td width="8%" class="tax_tr tax_tr1"><select <?php echo $disable; ?> id="tax" name="tax[]" class="calc_tot tax" ><option value="">No Tax</option><option value="6" <?php echo ($iter["tax"] == 6?"selected":""); ?>>6% GST</option></select></td>
                                <?php } ?>
                                <td width="10%"><input type="text" <?php echo $readonly; ?> readonly="true" id="amount" name="amount[]" class="float_valid calc_tot" placeholder="Amount" value="<?php echo $iter["amount"]; ?>" /></td>
                                <td>
                                    <?php if($view == false){ ?>
                                    <div style="padding-top: 2px;cursor:pointer;background-color:white;color:red;width:25px;height:25px;display:flex;justify-content:center" onclick="remove_col(this)">X</div>
                                    <?php } ?>
                                </td>
                                <input type="hidden" id="upd_id" name="upd_id[]" value="<?php echo $iter["id"]; ?>" />
                            </tr>
                                <?php }} ?>
                            </tbody>
                            <tfooter>
                                <tr><td colspan="6" align="right" style="padding-right:3px">
                                    Total
                                    </td>
                                    <td>
                                    <input type="text" name="total" id="total" readonly="true" placeholder="Total" class="form-control1 float_valid" value="<?php echo isset($supplier['total']) ? $supplier['total'] : ""; ?>" required <?php echo $readonly; ?>>
                                </td><td></td></tr>
                                <?php
                                if(($supplier['invoice_type'] == 2 && $purchase_default_discount_ledger >0) ||
                                ($supplier['invoice_type'] == 1 && $sales_default_discount_ledger >0)
                                
                                ){ ?>
                                <tr><td colspan="6" align="right" style="padding-right:3px">
                                    Discount
                                    </td>
                                    
                                    <td>
                                    <input type="text" name="discount" id="discount" onchange="calctotal()" placeholder="Discount" class="form-control1 float_valid" value="<?php echo isset($supplier['discount']) ? $supplier['discount'] : ""; ?>" <?php echo $readonly; ?>>
                                </td>
                                
                                <td></td></tr>
                                <?php } ?>
                                <tr><td colspan="6" align="right" style="padding-right:3px">
                                    <strong>Grand Total</strong>
                                    </td>
                                    <td>
                                    <input type="text" name="grand_total" id="grand_total" readonly="true" placeholder="Grand Total" class="form-control1 float_valid" value="<?php echo isset($supplier['grand_total']) ? $supplier['grand_total'] : ""; ?>" required <?php echo $readonly; ?>>
                                </td><td></td></tr>
                                <tr><td colspan="6" align="right" style="padding-right:3px">
                                    Paid Amount
                                    </td>
                                    <td>
                                    <input type="text" name="paid_amount" id="paid_amount" readonly="true" placeholder="Paid Amount" class="form-control1 float_valid" value="<?php echo isset($supplier['paid_amount']) ? $supplier['paid_amount'] : ""; ?>" <?php echo $readonly; ?>>
                                </td><td></td></tr>
                                <tr><td colspan="6" align="right" style="padding-right:3px">
                                    Due Amount
                                    </td>
                                    <td>
                                    <input type="text" name="due_amount" id="due_amount" readonly="true" placeholder="Due Amount" class="form-control1 float_valid" value="<?php echo isset($supplier['due_amount']) ? $supplier['due_amount'] : ""; ?>" <?php echo $readonly; ?>>
                                </td><td></td></tr>
                            </tfooter>
                        </table>
                        
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
var del_arr = [];

function remove_col(this1)
{
    var curtr = $(this1).closest("tr");
    if($(curtr).find("#upd_id").length>0)
    {
        del_arr.push($(curtr).find("#upd_id").val());
        $("#del_arr").val(del_arr.join(","));
    }
      
    $(curtr).remove();
    
    var dyn_table_tr = $("#dyn_table").find("tr").length|0;
    if(dyn_table_tr == 0)
        add_col();
        
    caltotal();
}

function add_col()
{
    
    var is_view = '<?php echo ($view == true?1:0) ?>';
    var disable = (is_view == 1?"disabled='true'":"");
    var readonly = (is_view == 1?"readonly='true'":"");
    
    var cl_icon ='';
    if(is_view == 0)
        cl_icon='<div style="padding-top: 2px;cursor:pointer;background-color:white;color:red;width:25px;height:25px;display:flex;justify-content:center" onclick="remove_col(this)">X</div>';

    // Get current invoice type to show appropriate ledgers
    var invoice_type = $("#invoice_type").val();
    var ledger_options = '<option value="">Select Ledger</option>';
    
     var had_default_tax = parseInt(invoice_type ==1?
    '<?php echo (($sales_default_tax_ledger >0)?1:0); ?>':
    '<?php echo (($purchase_default_tax_ledger >0)?1:0); ?>');
    
    var tax_rw = "";
    if(had_default_tax == 1)
    {
        tax_rw = `<td class="tax_tr"><select ${disable} id="tax" name="tax[]" class="calc_tot" ><option value="">No Tax</option><option value="6">6% GST</option></select></td>`;
    }
    
    <?php if(isset($sales_ledgers)){ ?>
    var sales_ledgers = <?php echo json_encode($sales_ledgers); ?>;
    <?php } ?>
    <?php if(isset($purchase_ledgers)){ ?>
    var purchase_ledgers = <?php echo json_encode($purchase_ledgers); ?>;
    <?php } ?>
    
    var purchase_default_ledger = ('<?php echo $purchase_default_ledger ?>')|0;
    var sales_default_ledger = ('<?php echo $sales_default_ledger ?>')|0;
    
    if(invoice_type == 1 && typeof sales_ledgers !== 'undefined') {
        sales_ledgers.forEach(function(ledger) {
            ledger_options += '<option '+(sales_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    } else if(invoice_type == 2 && typeof purchase_ledgers !== 'undefined') {
        purchase_ledgers.forEach(function(ledger) {
            ledger_options += '<option '+(purchase_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    }
        
    var str =`<tr>
                                <td><textarea ${readonly} id="description" name="description[]" placeholder="Description"></textarea></td>
                                <td>
                                    <select ${disable} onchange="shtax(this)" class="calc_tot" id="type" name="type[]">
                                        <option value='0'>Select Type</option>
                                        <option value='1'>Service</option>
                                        <option value='2'>Product</option>
                                    </select>
                                </td>
                                <td>
                                    <select ${disable} class="form-control" id="ledger_id" name="ledger_id[]" required>
                                        ${ledger_options}
                                    </select>
                                </td>
                                <td><input ${readonly} type="text" id="rate" name="rate[]" class="float_valid calc_tot" placeholder="Rate" /></td>
                                <td><input ${readonly} type="text" id="qty" name="qty[]" class="float_valid calc_tot" placeholder="Qty" value="1" /></td>
                                ${tax_rw}
                                <td><input ${readonly} type="text" readonly="true" id="amount" name="amount[]" class="float_valid calc_tot" placeholder="Amount" /></td>
                                <td>${cl_icon}</td>
                            </tr>`;
    $("#dyn_table").append(str);
    $("#ledger_id").selectpicker('refresh');
}

function calc_total(curtr)
{
    var rate = parseFloat($(curtr).find("#rate").val()) || 0;
    var qty = parseFloat($(curtr).find("#qty").val()) || 0;
    var amt = rate * qty;
    var tax = parseFloat($(curtr).find("#tax").val()) || 0;
    var tot = amt;
    
    if(tax > 0)
    {
        tot = amt + (amt * (tax/100));
    }
    
    $(curtr).find("#amount").val(tot.toFixed(2));
    caltotal();
}

function caltotal()
{
    var tot = 0;
    $("#dyn_table").find("tr").each((i,tr)=>{
        var amt = parseFloat($(tr).find("#amount").val()) || 0;
        if(amt > 0)
            tot += amt;
    });
    
    $("#total").val(tot.toFixed(2));
    calctotal();
}

function calctotal()
{
    var total = parseFloat($("#total").val()) || 0;
    var discount = parseFloat($("#discount").val()) || 0;
    var grand_total = total - discount;
    
    if(grand_total >= 0)
    {
        $("#grand_total").val(grand_total.toFixed(2));
        var paid_amount = parseFloat($("#paid_amount").val()) || 0;
        var due_amount = grand_total - paid_amount;
        $("#due_amount").val(due_amount.toFixed(2));
    }
}

$("#dyn_table").on("change keyup",".calc_tot",(evt)=>{
    calc_total($(evt.target).closest("tr"));
});

function shtax(this1)
{
    var curtr = $(this1).closest("tr");
    var type_val = $(curtr).find("#type").val();
    
    /*
    if(type_val == "2") // Product
    {
        $(curtr).find("#tax").val("");
        $(curtr).find("#tax").prop("disabled",true);
    }
    else // Service
    {
        $(curtr).find("#tax").removeProp("disabled");
        $(curtr).find("#tax").val("6");
    }
    */
    
    // Update ledger options based on invoice type
    updateRowLedgerOptions(curtr);
}

function updateRowLedgerOptions(curtr)
{
    
    var invoice_type = $("#invoice_type").val();
    var ledger_options = '<option value="">Select Ledger</option>';
    
    <?php if(isset($sales_ledgers)){ ?>
    var sales_ledgers = <?php echo json_encode($sales_ledgers); ?>;
    <?php } ?>
    <?php if(isset($purchase_ledgers)){ ?>
    var purchase_ledgers = <?php echo json_encode($purchase_ledgers); ?>;
    <?php } ?>
    
    if(invoice_type == 1 && typeof sales_ledgers !== 'undefined') {
        sales_ledgers.forEach(function(ledger) {
            ledger_options += '<option value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    } else if(invoice_type == 2 && typeof purchase_ledgers !== 'undefined') {
        purchase_ledgers.forEach(function(ledger) {
            ledger_options += '<option value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    }
    
    var current_value = $(curtr).find("#ledger_id").val();
    $(curtr).find("#ledger_id").html(ledger_options);
    $(curtr).find("#ledger_id").val(current_value);
}

function getCSAmount()
{
    var customer_supplier_id = $("#customer_supplier_id").val();
    var invoice_type = $("#invoice_type").val();
    if(customer_supplier_id && invoice_type) {
        $.post("<?php echo base_url() ?>/invoice/getCSAmount",{customer_supplier_id:customer_supplier_id,invoice_type:invoice_type},(res)=>{
            // Handle response if needed
        }); 
    }
}

function getCustOrSupp(invoice_type)
{
    var name = '<?php echo ((isset($supplier["customer_supplier_id"]))?intval($supplier["customer_supplier_id"]):0); ?>';
    $.post("<?php echo base_url() ?>/invoice/getCustOrSupp",{invoice_type:invoice_type,name:name},(res)=>{
        $("#customer_supplier_id").html(res);
        $('#customer_supplier_id').selectpicker('refresh');
        
        // Update label based on type
        if(invoice_type == 1) {
            $("#customer_supplier_id_label").text("Customer");
        } else {
            $("#customer_supplier_id_label").text("Supplier");
        }
        shtaxcol(invoice_type);
        // Update ledger options in all rows
        updateAllLedgerOptions(invoice_type);
    });  
    
}
function shtaxcol(invoice_type)
{
    var had_default_tax = parseInt(invoice_type ==1?
    '<?php echo (($sales_default_tax_ledger >0)?1:0); ?>':
    '<?php echo (($purchase_default_tax_ledger >0)?1:0); ?>');
    
    if(had_default_tax == 1)
    {
        $(".tax_tr1").show();
    }
    else
    {
        $(".tax_tr1").hide();
    }
}
function updateAllLedgerOptions(invoice_type)
{
    var ledger_options = '<option value="">Select Ledger</option>';
    
    <?php if(isset($sales_ledgers)){ ?>
    var sales_ledgers = <?php echo json_encode($sales_ledgers); ?>;
    <?php } ?>
    <?php if(isset($purchase_ledgers)){ ?>
    var purchase_ledgers = <?php echo json_encode($purchase_ledgers); ?>;
    <?php } ?>
    
    if(invoice_type == 1 && typeof sales_ledgers !== 'undefined') {
        sales_ledgers.forEach(function(ledger) {
            ledger_options += '<option value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    } else if(invoice_type == 2 && typeof purchase_ledgers !== 'undefined') {
        purchase_ledgers.forEach(function(ledger) {
            ledger_options += '<option value="'+ledger.id+'">'+ledger.name+'</option>';
        });
    }
    
    // Update all ledger dropdowns
    $("#dyn_table select[name='ledger_id[]']").each(function() {
        var current_value = $(this).val();
        $(this).html(ledger_options);
        $(this).val(current_value);
    });
}

$(document).ready(()=>{
    var req_typ = "<?php echo isset($_REQUEST['req_typ']) ? $_REQUEST['req_typ'] : '1'; ?>";
    if(req_typ == 2)
    {
        $("#invoice_type").val(2);
        var url = "<?php echo base_url(); ?>/invoice/invoice_purchase";
        $("#invoice_type_list").attr("href",url);
    }
    
    getCustOrSupp($("#invoice_type").val());
    
    // Add initial row if no existing data
    <?php if(!isset($data_list) || empty($data_list)){ ?>
    add_col();
    <?php } ?>
});

$('#form_validation').validate({
	rules: {
		"invoice_type": {
			required: true,
		},
		"customer_supplier_id": {
			required: true,
		},
		"invoice_no": {
			required: true,
		},
		"date": {
			required: true,
		},
		"grand_total": {
			required: true,
			min: 0.01
		}
	},
	messages: {
		"invoice_type": {
			required: "Invoice type is required"
		},
        "customer_supplier_id": {
			required: "Customer/Supplier is required"
		},
		"invoice_no": {
			required: "Invoice number is required"
		},
		"date": {
			required: "Date is required"
		},
		"grand_total": {
			required: "Grand total is required",
			min: "Grand total must be greater than 0"
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