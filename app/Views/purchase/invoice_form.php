<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <form action="<?= base_url('purchase-invoices/store') ?>" method="post" id="invoiceForm">
                    <?= csrf_field() ?>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice Number <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_number" class="form-control" 
                                           value="<?= $invoice_number ?>" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" class="form-control" 
                                           value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GRN Reference</label>
                                    <select name="grn_id" id="grn_id" class="form-control">
                                        <option value="">Select GRN (Optional)</option>
                                        <?php foreach ($grns as $grn): ?>
                                            <option value="<?= $grn['id'] ?>" 
                                                data-supplier="<?= $grn['supplier_id'] ?>"
                                                <?= isset($selected_grn) && $selected_grn['id'] == $grn['id'] ? 'selected' : '' ?>>
                                                <?= $grn['grn_number'] ?> - <?= $grn['supplier_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier <span class="text-danger">*</span></label>
                                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?= $supplier['id'] ?>"
                                                <?= isset($selected_grn) && $selected_grn['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>>
                                                <?= $supplier['supplier_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier Invoice No <span class="text-danger">*</span></label>
                                    <input type="text" name="supplier_invoice_number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier Invoice Date</label>
                                    <input type="date" name="supplier_invoice_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PO Reference</label>
                                    <input type="hidden" name="po_id" id="po_id">
                                    <input type="text" id="po_number" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th width="35%">Item</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Tax %</th>
                                        <th>Tax Amount</th>
                                        <th>Discount %</th>
                                        <th>Discount Amount</th>
                                        <th>Total</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <?php if (isset($grn_items) && !empty($grn_items)): ?>
                                        <?php foreach ($grn_items as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[<?= $index ?>][grn_item_id]" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][item_type]" value="<?= $item['item_type'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][item_id]" value="<?= $item['item_id'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][description]" value="<?= $item['description'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][uom_id]" value="<?= $item['uom_id'] ?>">
                                                <?= $item['item_name'] ?> (<?= $item['item_code'] ?>)
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][quantity]" 
                                                       class="form-control quantity" value="<?= $item['accepted_quantity'] ?>" 
                                                       step="0.001" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][unit_price]" 
                                                       class="form-control unit-price" value="<?= $item['unit_price'] ?>" 
                                                       step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][tax_rate]" 
                                                       class="form-control tax-rate" value="<?= $item['tax_rate'] ?>" 
                                                       step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control tax-amount" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][discount_rate]" 
                                                       class="form-control discount-rate" value="<?= $item['discount_rate'] ?>" 
                                                       step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control discount-amount" readonly>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-total" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-item">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary" id="addItem">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-right"><strong>Subtotal:</strong></td>
                                        <td class="text-right" width="150">₹ <span id="subtotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Tax:</strong></td>
                                        <td class="text-right">₹ <span id="tax">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Discount:</strong></td>
                                        <td class="text-right">₹ <span id="discount">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Other Charges:</strong></td>
                                        <td class="text-right">
                                            ₹ <input type="number" name="other_charges" id="other_charges" 
                                                    class="form-control form-control-sm d-inline-block" 
                                                    style="width: 100px;" value="0" step="0.01">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><h5>Total:</h5></td>
                                        <td class="text-right"><h5>₹ <span id="total">0.00</span></h5></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Invoice
                        </button>
                        <a href="<?= base_url('purchase-invoices') ?>" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Item Type</label>
                    <select id="modalItemType" class="form-control">
                        <option value="product">Product</option>
                        <option value="raw_material">Raw Material</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Item</label>
                    <select id="modalItemId" class="form-control">
                        <option value="">Select Item</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmAddItem">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
var itemIndex = <?= isset($grn_items) ? count($grn_items) : 0 ?>;

$(document).ready(function() {
    // Calculate totals on page load
    calculateTotals();
    
    // GRN selection change
    $('#grn_id').change(function() {
        var grn_id = $(this).val();
        var supplier_id = $(this).find(':selected').data('supplier');
        
        if (grn_id) {
            $('#supplier_id').val(supplier_id).prop('readonly', true);
            
            $.ajax({
                url: '<?= base_url('purchase-invoices/get-grn-details') ?>',
                type: 'GET',
                data: { grn_id: grn_id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#itemsBody').empty();
                        itemIndex = 0;
                        
                        if (response.grn.po_id) {
                            $('#po_id').val(response.grn.po_id);
                            $('#po_number').val(response.grn.po_number);
                        }
                        
                        response.items.forEach(function(item) {
                            addItemRow(item);
                        });
                        
                        calculateTotals();
                    }
                }
            });
        } else {
            $('#supplier_id').prop('readonly', false);
            $('#po_id').val('');
            $('#po_number').val('');
        }
    });
    
    // Price/quantity/tax/discount change
    $(document).on('input', '.quantity, .unit-price, .tax-rate, .discount-rate', function() {
        var row = $(this).closest('tr');
        calculateRowTotal(row);
        calculateTotals();
    });
    
    // Other charges change
    $('#other_charges').on('input', function() {
        calculateTotals();
    });
    
    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });
    
    // Add item button
    $('#addItem').click(function() {
        $('#addItemModal').modal('show');
    });
    
    // Load items based on type
    $('#modalItemType').change(function() {
        var type = $(this).val();
        loadItems(type);
    });
    
    // Confirm add item
    $('#confirmAddItem').click(function() {
        var itemType = $('#modalItemType').val();
        var itemId = $('#modalItemId').val();
        var itemText = $('#modalItemId option:selected').text();
        
        if (itemId) {
            var item = {
                item_type: itemType,
                item_id: itemId,
                item_name: itemText,
                item_code: '',
                accepted_quantity: 1,
                unit_price: 0,
                tax_rate: 18,
                discount_rate: 0
            };
            
            addItemRow(item);
            $('#addItemModal').modal('hide');
            calculateTotals();
        }
    });
});

function addItemRow(item) {
    var html = `
        <tr>
            <td>
                <input type="hidden" name="items[${itemIndex}][grn_item_id]" value="${item.id || ''}">
                <input type="hidden" name="items[${itemIndex}][item_type]" value="${item.item_type}">
                <input type="hidden" name="items[${itemIndex}][item_id]" value="${item.item_id}">
                <input type="hidden" name="items[${itemIndex}][description]" value="${item.item_name}">
                <input type="hidden" name="items[${itemIndex}][uom_id]" value="${item.uom_id || ''}">
                ${item.item_name} ${item.item_code ? '(' + item.item_code + ')' : ''}
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" 
                       class="form-control quantity" value="${item.accepted_quantity || 1}" 
                       step="0.001" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][unit_price]" 
                       class="form-control unit-price" value="${item.unit_price || 0}" 
                       step="0.01" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][tax_rate]" 
                       class="form-control tax-rate" value="${item.tax_rate || 18}" 
                       step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control tax-amount" readonly>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][discount_rate]" 
                       class="form-control discount-rate" value="${item.discount_rate || 0}" 
                       step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control discount-amount" readonly>
            </td>
            <td>
                <input type="number" class="form-control item-total" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    `;
    
    $('#itemsBody').append(html);
    itemIndex++;
}

function calculateRowTotal(row) {
    var quantity = parseFloat(row.find('.quantity').val()) || 0;
    var unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
    var taxRate = parseFloat(row.find('.tax-rate').val()) || 0;
    var discountRate = parseFloat(row.find('.discount-rate').val()) || 0;
    
    var lineTotal = quantity * unitPrice;
    var taxAmount = (lineTotal * taxRate) / 100;
    var discountAmount = (lineTotal * discountRate) / 100;
    var total = lineTotal + taxAmount - discountAmount;
    
    row.find('.tax-amount').val(taxAmount.toFixed(2));
    row.find('.discount-amount').val(discountAmount.toFixed(2));
    row.find('.item-total').val(total.toFixed(2));
}

function calculateTotals() {
    var subtotal = 0;
    var totalTax = 0;
    var totalDiscount = 0;
    
    $('#itemsBody tr').each(function() {
        var quantity = parseFloat($(this).find('.quantity').val()) || 0;
        var unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
        var taxRate = parseFloat($(this).find('.tax-rate').val()) || 0;
        var discountRate = parseFloat($(this).find('.discount-rate').val()) || 0;
        
        var lineTotal = quantity * unitPrice;
        var taxAmount = (lineTotal * taxRate) / 100;
        var discountAmount = (lineTotal * discountRate) / 100;
        
        subtotal += lineTotal;
        totalTax += taxAmount;
        totalDiscount += discountAmount;
        
        calculateRowTotal($(this));
    });
    
    var otherCharges = parseFloat($('#other_charges').val()) || 0;
    var total = subtotal + totalTax - totalDiscount + otherCharges;
    
    $('#subtotal').text(subtotal.toFixed(2));
    $('#tax').text(totalTax.toFixed(2));
    $('#discount').text(totalDiscount.toFixed(2));
    $('#total').text(total.toFixed(2));
}

function loadItems(type) {
    // This would be an AJAX call to load products or raw materials
    // For now, using placeholder
    $('#modalItemId').html('<option value="">Select Item</option>');
}
</script>