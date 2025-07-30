<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = "disabled";
}
$invoice_type =  (!empty($supplier['invoice_type']) ? $supplier['invoice_type'] : (!empty($_REQUEST['req_typ']) ? $_REQUEST['req_typ'] : 1) );
?>

<style>
/* Enhanced styles that integrate with your existing design */
.form-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    position: relative;
    overflow: hidden;
}

.form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.form-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.form-title {
    flex: 1;
}

.form-title h2 {
    font-size: 2rem;
    font-weight: 300;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-title p {
    font-size: 1rem;
    opacity: 0.9;
}

.form-actions {
    display: flex;
    gap: 10px;
}

.btn-modern {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modern.btn-primary {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.btn-modern.btn-success {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
}

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    text-decoration: none;
    color: white;
}

.form-body {
    padding: 40px;
}

.enhanced-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.enhanced-form-group {
    position: relative;
}

.enhanced-form-group .form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.enhanced-form-group .required {
    color: #e74c3c;
}

.enhanced-form-control {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #ecf0f1;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.enhanced-form-control:focus {
    outline: none;
    border-color: #3498db;
    background: white;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

/* Items section styling */
.items-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    margin: 30px 0;
}

.items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.items-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.add-item-btn {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
}

.add-item-btn:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

/* Enhanced table styling that works with your existing classes */
.enhanced-items-table {
    width: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border-collapse: collapse;
}

.enhanced-items-table thead {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
}

.enhanced-items-table th {
    padding: 20px 15px;
    text-align: center;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
    border: none;
}

.enhanced-items-table td {
    padding: 15px;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    border-top: none;
}

.enhanced-items-table tbody tr:hover {
    background: #f8f9fa;
}

/* Item input styling */
.item-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.item-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
}

.item-textarea {
    min-height: 80px;
    resize: vertical;
}

.remove-btn {
    background: #e74c3c;
    color: white;
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
}

.remove-btn:hover {
    background: #c0392b;
    transform: scale(1.1);
}

/* Totals section */
.totals-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin: 30px 0;
    border: 2px solid #ecf0f1;
}

.totals-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 30px;
    align-items: end;
}

.totals-table {
    width: 400px;
}

.totals-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #ecf0f1;
}

.totals-row:last-child {
    border-bottom: none;
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    margin: 10px -20px -20px;
    padding: 20px;
    border-radius: 10px;
}

.totals-label {
    font-weight: 600;
    color: #2c3e50;
}

.totals-row:last-child .totals-label {
    color: white;
}

.totals-value {
    font-weight: 700;
    font-size: 18px;
    color: #27ae60;
}

.totals-row:last-child .totals-value {
    color: white;
}

/* Submit section */
.submit-section {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 15px;
    margin-top: 30px;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-body {
        padding: 20px;
    }

    .enhanced-form-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .items-section {
        padding: 20px;
    }

    .enhanced-items-table {
        font-size: 12px;
    }

    .enhanced-items-table th,
    .enhanced-items-table td {
        padding: 10px 5px;
    }

    .totals-grid {
        grid-template-columns: 1fr;
    }

    .totals-table {
        width: 100%;
    }

    .form-header-content {
        flex-direction: column;
        text-align: center;
    }
}

/* Integration with your existing alert styles */
.alert-enhanced {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
    border: none;
    position: relative;
    overflow: hidden;
}

.alert-enhanced::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: currentColor;
}

.alert-enhanced.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-enhanced.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

/* Override default form styles to integrate better */
.form-group .form-line {
    position: relative;
    margin-bottom: 0;
}

.form-group .form-line .form-control {
    border: 2px solid #ecf0f1;
    border-radius: 10px;
    padding: 15px 20px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.form-group .form-line.focused .form-control,
.form-group .form-line .form-control:focus {
    border-color: #3498db;
    background: white;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

/* Container fixes for table */
.enhanced-items-table {
    overflow: visible !important;
    position: relative !important;
}

.enhanced-items-table td {
    overflow: visible !important;
    position: relative !important;
}

.table-responsive {
    overflow: visible !important;
}

.items-section {
    overflow: visible !important;
    position: relative !important;
}

/* Disabled state */
.bootstrap-select.disabled,
.bootstrap-select > .disabled {
    cursor: not-allowed !important;
}

.bootstrap-select.disabled > .dropdown-toggle,
.bootstrap-select > .disabled {
    background-color: #e9ecef !important;
    opacity: 1 !important;
    color: #6c757d !important;
    pointer-events: none !important;
}


/* Force proper rendering */
.bootstrap-select * {
    box-sizing: border-box !important;
}
</style>

<section class="content">
    <div class="container-fluid">
        <!-- Enhanced Header -->
        <div class="form-container">
            <div class="form-header">
                <div class="form-header-content">
                    <div class="form-title">
                        <h2><i class="material-icons">&#xE85C;</i> <?php echo ($invoice_type == 1 ? 'Sales': 'Purchase'); ?> Order</h2>
                        <p>Create and manage <?php echo ($invoice_type == 1 ? 'sales': 'purchase'); ?>  orders efficiently</p>
                    </div>
                    <div class="form-actions">
                        <a href="<?php echo base_url(); ?>/purchase_order/<?php echo ($invoice_type == 1 ? 'index': 'purchase_order_purchase'); ?>" class="btn-modern btn-secondary">
                            <i class="material-icons">&#xE8EF;</i> List
                        </a>
                        <button type="button" class="btn-modern btn-primary" onclick="saveDraft()">
                            <i class="material-icons">&#xE161;</i> Save Draft
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <form action="<?php echo base_url(); ?>/purchase_order/store" method="POST" id="form_validation">
                    <input type="hidden" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ""; ?>" name="id" id="updateid">
                    <input type="hidden" value="" name="del_arr" id="del_arr">

                    <!-- Basic Information -->
                    <div class="enhanced-form-grid">
                        <input type="hidden" name="invoice_type" id="invoice_type" value="<?php echo $invoice_type; ?>" />

                        <div class="enhanced-form-group">
                            <label class="form-label" for="customer_supplier_id"><?php echo ($invoice_type == 1 ? 'Customer' : 'Supplier'); ?> <span class="required">*</span></label>
                            <div class="form-line">
                                <select onchange="getCSAmount()" name="customer_supplier_id" id="customer_supplier_id" class="form-control show-tick" required data-live-search="true" <?php echo $disable; ?>>
                                    <option value="">Select <?php echo ($invoice_type == 1 ? 'Customer' : 'Supplier'); ?> <span style="color: red;">*</span></option>
                                </select>
                            </div>
                        </div>

                        <div class="enhanced-form-group">
                            <label class="form-label" for="po_no">PO Number <span class="required">*</span></label>
                            <div class="form-line">
                                <input type="text" name="po_no" id="po_no" class="form-control" value="<?php echo isset($supplier['po_no']) ? $supplier['po_no'] : ""; ?>" required readonly <?php echo $readonly; ?>>
                            </div>
                        </div>

                        <div class="enhanced-form-group">
                            <label class="form-label" for="date">Date <span class="required">*</span></label>
                            <div class="form-line">
                                <input type="date" name="date" id="date" class="form-control" value="<?php echo isset($supplier['date']) ? $supplier['date'] : date('Y-m-d'); ?>" required <?php echo $readonly; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="enhanced-form-group">
                        <label class="form-label" for="remarks">Remarks</label>
                        <div class="form-line">
                            <textarea <?php echo $readonly; ?> name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter any remarks or special instructions..."><?php echo isset($supplier['remarks']) ? $supplier['remarks'] : ""; ?></textarea>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="items-section">
                        <div class="items-header">
                            <h3 class="items-title">
                                <i class="material-icons">&#xE8CC;</i> Order Items
                            </h3>
                            <?php if($view == false){ ?>
                            <button type="button" class="add-item-btn" onclick="add_col()">
                                <i class="material-icons">&#xE145;</i>
                            </button>
                            <?php } ?>
                        </div>

                        <div class="table-responsive">
                            <table class="enhanced-items-table">
                                <thead>
                                    <tr>
                                        <th width="25%">Description</th>
                                        <th width="10%">Type</th>
                                        <th width="15%">Ledger Account <span class="required">*</span></th>
                                        <th width="8%">Rate (RM)</th>
                                        <th width="8%">Qty</th>
                                        <?php
                                        if(($invoice_type == 2 && !empty($purchase_settings['purchase_default_tax_ledger'])) ||
                                        ($invoice_type == 1 && !empty($sales_settings['sales_default_tax_ledger']))
                                        ){?>
                                        <th width="8%" class="tax_tr1">Tax (%)</th>
                                        <?php } ?>
                                        <th width="10%">Amount (RM)</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="dyn_table">
                                    <?php if(isset($data_list)){
                                    foreach($data_list as $iter)
                                    {
                                    ?>
                                    <tr>
                                        <td><textarea <?php echo $readonly; ?> class="item-input item-textarea" name="description[]"><?php echo $iter["description"]; ?></textarea></td>
                                        <td>
                                            <select <?php echo $disable; ?> class="item-input calc_tot" onchange="shtax(this)" name="type[]">
                                                <option value='0'>Select Type</option>
                                                <option <?php echo ($iter["type"] == 1?"selected":""); ?> value='1'>Service</option>
                                                <option <?php echo ($iter["type"] == 2?"selected":""); ?> value='2'>Product</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select <?php echo $disable; ?> class="item-input" name="ledger_id[]" required data-live-search="true">
                                                <option value="">Select Ledger</option>
                                                <?php 
                                                $current_ledgers = isset($supplier['invoice_type']) && $supplier['invoice_type'] == 1 ? $sales_ledgers : $purchase_ledgers;
                                                foreach($current_ledgers as $ledger){ ?>
                                                    <option <?php echo ($iter["ledger_id"] == $ledger["id"]?"selected":""); ?> value="<?php echo $ledger['id']; ?>"><?php echo '(' . $ledger['left_code'] . '/' . $ledger['right_code'] . ')' . $ledger['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><input <?php echo $readonly; ?> type="number" class="item-input calc_tot" name="rate[]" placeholder="0.00" step="0.01" value="<?php echo $iter["rate"]; ?>" onchange="calculateRowAmount(this)"></td>
                                        <td><input <?php echo $readonly; ?> type="number" class="item-input calc_tot" name="qty[]" placeholder="1" value="<?php echo $iter["qty"]; ?>" min="0" step="1" onchange="calculateRowAmount(this)"></td>
                                        <?php if(($invoice_type == 2 && !empty($purchase_settings['purchase_default_tax_ledger'])) ||
                                        ($invoice_type == 1 && !empty($sales_settings['sales_default_tax_ledger']))
                                        ){?>
                                        <td class="tax_tr tax_tr1">
                                            <select <?php echo $disable; ?> class="item-input calc_tot tax" name="tax[]" onchange="calculateRowAmount(this)">
                                                <option value="">No Tax</option>
                                                <option value="6" <?php echo ($iter["tax"] == 6?"selected":""); ?>>6% GST</option>
                                            </select>
                                        </td>
                                        <?php } ?>
                                        <td><input type="number" <?php echo $readonly; ?> readonly="true" class="item-input calc_tot" name="amount[]" placeholder="0.00" step="0.01" value="<?php echo $iter["amount"]; ?>"></td>
                                        <td style="text-align: center;">
                                            <?php if($view == false){ ?>
                                            <button type="button" class="remove-btn" onclick="remove_col(this)">
                                                <i class="material-icons">&#xE14C;</i>
                                            </button>
                                            <?php } ?>
                                        </td>
                                        <input type="hidden" name="upd_id[]" value="<?php echo $iter["id"]; ?>">
                                    </tr>
                                    <?php }} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals Section -->
                    <div class="totals-section">
                        <div class="totals-grid">
                            <div>
                                <h4>Order Summary</h4>
                                <p>Review the calculation details below</p>
                            </div>
                            <div class="totals-table">
                                <div class="totals-row">
                                    <span class="totals-label">Subtotal:</span>
                                    <span class="totals-value">RM <span id="subtotalDisplay"><?php echo isset($supplier['total']) ? number_format($supplier['total'], 2) : "0.00"; ?></span></span>
                                    <input type="hidden" name="total" id="total" value="<?php echo isset($supplier['total']) ? $supplier['total'] : "0"; ?>">
                                </div>
                                <?php if(($supplier['invoice_type'] == 2 && $purchase_default_discount_ledger >0) ||
                                ($supplier['invoice_type'] == 1 && $sales_default_discount_ledger >0)){ ?>
                                <div class="totals-row">
                                    <span class="totals-label">Discount:</span>
                                    <span>RM <input type="number" name="discount" id="discount" class="item-input" style="width:100px; display:inline;" value="<?php echo isset($supplier['discount']) ? $supplier['discount'] : "0"; ?>" step="0.01" onchange="calctotal()" <?php echo $readonly; ?>></span>
                                </div>
                                <?php } ?>
                                <div class="totals-row">
                                    <span class="totals-label">Grand Total:</span>
                                    <span class="totals-value">RM <span id="grandTotalDisplay"><?php echo isset($supplier['grand_total']) ? number_format($supplier['grand_total'], 2) : "0.00"; ?></span></span>
                                    <input type="hidden" name="grand_total" id="grand_total" value="<?php echo isset($supplier['grand_total']) ? $supplier['grand_total'] : "0"; ?>">
                                </div>
                                <div class="totals-row">
                                    <span class="totals-label">Paid Amount:</span>
                                    <span class="totals-value">RM <span id="paidAmountDisplay"><?php echo isset($supplier['paid_amount']) ? number_format($supplier['paid_amount'], 2) : "0.00"; ?></span></span>
                                    <input type="hidden" name="paid_amount" id="paid_amount" value="<?php echo isset($supplier['paid_amount']) ? $supplier['paid_amount'] : "0"; ?>">
                                </div>
                                <div class="totals-row">
                                    <span class="totals-label">Due Amount:</span>
                                    <span class="totals-value">RM <span id="dueAmountDisplay"><?php echo isset($supplier['due_amount']) ? number_format($supplier['due_amount'], 2) : "0.00"; ?></span></span>
                                    <input type="hidden" name="due_amount" id="due_amount" value="<?php echo isset($supplier['due_amount']) ? $supplier['due_amount'] : "0"; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <?php if($view != true) { ?>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-success btn-lg waves-effect" style="font-size: 18px; padding: 15px 40px;">
                            <i class="material-icons">&#xE5CA;</i> CREATE <?php echo ($invoice_type == 1 ? 'SALES': 'PURCHASE'); ?>  ORDER
                        </button>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
var del_arr = [];

function remove_col(this1) {
    var curtr = $(this1).closest("tr");
    if($(curtr).find("input[name='upd_id[]']").length > 0)
        del_arr.push($(curtr).find("input[name='upd_id[]']").val());
        
    $(curtr).remove();
    $("#del_arr").val(del_arr.join(","));
    
    var dyn_table_tr = $("#dyn_table").find("tr").length || 0;
    if(dyn_table_tr == 0)
        add_col();
        
    caltotal();
}

function add_col() {
    var is_view = '<?php echo ($view == true?1:0) ?>';
    var disable = (is_view == 1?"disabled='true'":"");
    var readonly = (is_view == 1?"readonly='true'":"");
    
    var cl_icon ='';
    if(is_view == 0)
        cl_icon='<button type="button" class="remove-btn" onclick="remove_col(this)"><i class="material-icons">&#xE14C;</i></button>';

    var invoice_type = $("#invoice_type").val();
    var ledger_options = '<option value="">Select Ledger</option>';
    
    var had_default_tax = parseInt('<?php echo (($invoice_type == 2 && !empty($purchase_settings['purchase_default_tax_ledger'])) ? 1 : (($invoice_type == 1 && !empty($sales_settings['sales_default_tax_ledger'])) ? 1 : 0) ); ?>');
    
    var tax_rw = "";
    if(had_default_tax == 1) {
        tax_rw = `<td class="tax_tr"><select ${disable} class="item-input calc_tot" name="tax[]" onchange="calculateRowAmount(this)"><option value="">No Tax</option><option value="6">6% GST</option></select></td>`;
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
            ledger_options += '<option '+(sales_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">'+ '(' + ledger['left_code'] + '/' + ledger['right_code'] + ')' + ledger.name+'</option>';
        });
    } else if(invoice_type == 2 && typeof purchase_ledgers !== 'undefined') {
        purchase_ledgers.forEach(function(ledger) {
            ledger_options += '<option '+(purchase_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">'+ '(' + ledger['left_code'] + '/' + ledger['right_code'] + ')' +ledger.name+'</option>';
        });
    }
        
    var str =`<tr>
                <td><textarea ${readonly} class="item-input item-textarea" name="description[]" placeholder="Item description..."></textarea></td>
                <td>
                    <select ${disable} onchange="shtax(this)" class="item-input selectpicker calc_tot" name="type[]">
                        <option value='0'>Select Type</option>
                        <option value='1'>Service</option>
                        <option value='2'>Product</option>
                    </select>
                </td>
                <td>
                    <select ${disable} class="item-input selectpicker" name="ledger_id[]" required data-live-search="true">
                        ${ledger_options}
                    </select>
                </td>
                <td><input ${readonly} type="number" class="item-input calc_tot" name="rate[]" placeholder="0.00" step="0.01" onchange="calculateRowAmount(this)"></td>
                <td><input ${readonly} type="number" class="item-input calc_tot" name="qty[]" placeholder="1" value="1" min="0" step="1" onchange="calculateRowAmount(this)"></td>
                ${tax_rw}
                <td><input ${readonly} type="number" readonly="true" class="item-input calc_tot" name="amount[]" placeholder="0.00" step="0.01"></td>
                <td style="text-align: center;">${cl_icon}</td>
            </tr>`;
    $("#dyn_table").append(str);
    
    // Re-initialize bootstrap-select for new dropdowns
    $('.selectpicker').selectpicker('refresh');
}

function calculateRowAmount(element) {
    var row = $(element).closest('tr');
    var rate = parseFloat(row.find('input[name="rate[]"]').val()) || 0;
    var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
    var tax = parseFloat(row.find('select[name="tax[]"]').val()) || 0;
    
    var amount = rate * qty;
    if(tax > 0) {
        amount = amount + (amount * (tax/100));
    }
    
    row.find('input[name="amount[]"]').val(amount.toFixed(2));
    caltotal();
}

function caltotal() {
    var tot = 0;
    $("#dyn_table").find("tr").each((i,tr)=>{
        var amt = parseFloat($(tr).find('input[name="amount[]"]').val()) || 0;
        if(amt > 0)
            tot += amt;
    });
    
    $("#total").val(tot.toFixed(2));
    $("#subtotalDisplay").text(tot.toFixed(2));
    calctotal();
}

function calctotal() {
    var total = parseFloat($("#total").val()) || 0;
    var discount = parseFloat($("#discount").val()) || 0;
    var grand_total = total - discount;
    
    if(grand_total >= 0) {
        $("#grand_total").val(grand_total.toFixed(2));
        $("#grandTotalDisplay").text(grand_total.toFixed(2));
        $("#paid_amount").val("0.00");
        $("#paidAmountDisplay").text("0.00");
        $("#due_amount").val(grand_total.toFixed(2));
        $("#dueAmountDisplay").text(grand_total.toFixed(2));
    }
}

$("#dyn_table").on("change keyup",".calc_tot",(evt)=>{
    calculateRowAmount($(evt.target));
});

function shtax(this1) {
    // Tax logic can be implemented here if needed
}

function getCSAmount() {
    var customer_supplier_id = $("#customer_supplier_id").val();
    var invoice_type = $("#invoice_type").val();
    if(customer_supplier_id && invoice_type) {
        $.post("<?php echo base_url() ?>/purchase_order/getCSAmount",{customer_supplier_id:customer_supplier_id,invoice_type:invoice_type},(res)=>{
            // Handle response if needed
        }); 
    }
}

function getCustOrSupp(invoice_type) {
    var name = '<?php echo ((isset($supplier["customer_supplier_id"]))?intval($supplier["customer_supplier_id"]):0); ?>';
    
    $.post("<?php echo base_url() ?>/purchase_order/getCustOrSupp",{invoice_type:invoice_type,name:name},(res)=>{
        $("#customer_supplier_id").html(res);
        $('#customer_supplier_id').selectpicker('refresh');
        
        shtaxcol(invoice_type);
        updateLedgerOptions(invoice_type);
        getPONoType(invoice_type);
    });  
}

function shtaxcol(invoice_type) {
    var had_default_tax = parseInt('<?php echo (($invoice_type == 2 && !empty($purchase_settings['purchase_default_tax_ledger'])) ? 1 : (($invoice_type == 1 && !empty($sales_settings['sales_default_tax_ledger'])) ? 1 : 0) ); ?>');
    
    if(had_default_tax == 1) {
        $(".tax_tr1").show();
    } else {
        $(".tax_tr1").hide();
    }
}

function updateLedgerOptions(invoice_type) {
    var ledger_options = '<option value="">Select Ledger</option>';
    
    var purchase_default_ledger = ('<?php echo $purchase_default_ledger ?>')|0;
    var sales_default_ledger = ('<?php echo $sales_default_ledger ?>')|0;
    
    <?php if(isset($sales_ledgers)){ ?>
    var sales_ledgers = <?php echo json_encode($sales_ledgers); ?>;
    <?php } ?>
    <?php if(isset($purchase_ledgers)){ ?>
    var purchase_ledgers = <?php echo json_encode($purchase_ledgers); ?>;
    <?php } ?>
    
    if(invoice_type == 1 && typeof sales_ledgers !== 'undefined') {
        sales_ledgers.forEach(function(ledger) {
            ledger_options += '<option '+(sales_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">' + '(' +  ledger.left_code + '/' + ledger.right_code + ')' + ledger.name + '</option>';
        });
    } else if(invoice_type == 2 && typeof purchase_ledgers !== 'undefined') {
        purchase_ledgers.forEach(function(ledger) {
            ledger_options += '<option '+(purchase_default_ledger == ledger.id?"selected":"")+' value="'+ledger.id+'">'+ '(' +  ledger.left_code + '/' + ledger.right_code + ')' + ledger.name + '</option>';
        });
    }
    
    $("#dyn_table select[name='ledger_id[]']").each(function() {
        var current_value = ($(this).val()==""?(invoice_type == 1?sales_default_ledger:purchase_default_ledger):$(this).val());
        $(this).html(ledger_options);
        $(this).val(current_value);
        $(this).selectpicker('refresh');
    });
}

function getPONoType(invoice_type) {
     $.post("<?php echo base_url() ?>/purchase_order/getPONoType",{invoice_type:invoice_type},(res)=>{
         $("#po_no").val(res);
     });
}

function saveDraft() {
    alert('Draft saved successfully!');
}

$(document).ready(()=>{   
    getCustOrSupp($("#invoice_type").val());
    
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
        "po_no": {
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
            required: "Order type is required"
        },
        "customer_supplier_id": {
            required: "Customer/Supplier is required"
        },
        "po_no": {
            required: "PO number is required"
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