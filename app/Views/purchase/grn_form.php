<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <form action="<?= base_url('goods-receipts/store') ?>" method="post" id="grnForm">
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
                                    <label>GRN Number <span class="text-danger">*</span></label>
                                    <input type="text" name="grn_number" class="form-control" 
                                           value="<?= $grn_number ?>" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GRN Date <span class="text-danger">*</span></label>
                                    <input type="date" name="grn_date" class="form-control" 
                                           value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Purchase Order</label>
                                    <select name="po_id" id="po_id" class="form-control">
                                        <option value="">Select PO (Optional)</option>
                                        <?php foreach ($purchase_orders as $po): ?>
                                            <option value="<?= $po['id'] ?>" 
                                                <?= isset($selected_po) && $selected_po['id'] == $po['id'] ? 'selected' : '' ?>>
                                                <?= $po['po_number'] ?> - <?= date('d-m-Y', strtotime($po['po_date'])) ?>
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
                                                <?= isset($selected_po) && $selected_po['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>>
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
                                    <label>Supplier Invoice Number</label>
                                    <input type="text" name="invoice_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice Date</label>
                                    <input type="date" name="invoice_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Note Number</label>
                                    <input type="text" name="delivery_note_number" class="form-control">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th width="30%">Item</th>
                                        <th>Ordered Qty</th>
                                        <th>Received Qty</th>
                                        <th>Accepted Qty</th>
                                        <th>Rejected Qty</th>
                                        <th>Unit Price</th>
                                        <th>Tax %</th>
                                        <th>Discount %</th>
                                        <th>Total</th>
                                        <th>Batch No</th>
                                        <th>Expiry Date</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <?php if (isset($po_items) && !empty($po_items)): ?>
                                        <?php foreach ($po_items as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[<?= $index ?>][po_item_id]" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][item_type]" value="<?= $item['item_type'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][item_id]" value="<?= $item['item_id'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][description]" value="<?= $item['description'] ?>">
                                                <input type="hidden" name="items[<?= $index ?>][uom_id]" value="<?= $item['uom_id'] ?>">
                                                <?= $item['item_name'] ?> (<?= $item['item_code'] ?>)
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][ordered_quantity]" 
                                                       class="form-control ordered-qty" value="<?= $item['pending_quantity'] ?>" 
                                                       readonly step="0.001">
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][received_quantity]" 
                                                       class="form-control received-qty" value="<?= $item['pending_quantity'] ?>" 
                                                       step="0.001" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][accepted_quantity]" 
                                                       class="form-control accepted-qty" value="<?= $item['pending_quantity'] ?>" 
                                                       step="0.001" required>
                                            </td>
                                            <td>
                                                <input type="number" name="items[<?= $index ?>][rejected_quantity]" 
                                                       class="form-control rejected-qty" value="0" 
                                                       step="0.001" readonly>
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
                                                <input type="number" name="items[<?= $index ?>][discount_rate]" 
                                                       class="form-control discount-rate" value="<?= $item['discount_rate'] ?>" 
                                                       step="0.01" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-total" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="items[<?= $index ?>][batch_number]" 
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="date" name="items[<?= $index ?>][expiry_date]" 
                                                       class="form-control">
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
                            <div class="col-md-6 text-right">
                                <h5>Subtotal: ₹ <span id="subtotal">0.00</span></h5>
                                <h5>Tax: ₹ <span id="tax">0.00</span></h5>
                                <h5>Discount: ₹ <span id="discount">0.00</span></h5>
                                <h4>Total: ₹ <span id="total">0.00</span></h4>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save GRN
                        </button>
                        <a href="<?= base_url('goods-receipts') ?>" class="btn btn-default">
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
var itemIndex = <?= isset($po_items) ? count($po_items) : 0 ?>;

$(document).ready(function() {
    // Calculate totals on page load
    calculateTotals();
    
    // PO selection change
    $('#po_id').change(function() {
        var po_id = $(this).val();
        if (po_id) {
            $.ajax({
                url: '<?= base_url('goods-receipts/get-po-items') ?>',
                type: 'GET',
                data: { po_id: po_id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#itemsBody').empty();
                        itemIndex = 0;
                        
                        response.items.forEach(function(item) {
                            addItemRow(item);
                        });
                        
                        calculateTotals();
                    }
                }
            });
        }
    });
    
    // Quantity change calculations
    $(document).on('input', '.received-qty, .accepted-qty', function() {
        var row = $(this).closest('tr');
        var receivedQty = parseFloat(row.find('.received-qty').val()) || 0;
        var acceptedQty = parseFloat(row.find('.accepted-qty').val()) || 0;
        
        if (acceptedQty > receivedQty) {
            acceptedQty = receivedQty;
            row.find('.accepted-qty').val(acceptedQty);
        }
        
        var rejectedQty = receivedQty - acceptedQty;
        row.find('.rejected-qty').val(rejectedQty.toFixed(3));
        
        calculateRowTotal(row);
        calculateTotals();
    });
    
    // Price/tax/discount change
    $(document).on('input', '.unit-price, .tax-rate, .discount-rate', function() {
        var row = $(this).closest('tr');
        calculateRowTotal(row);
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
                pending_quantity: 0,
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
                <input type="hidden" name="items[${itemIndex}][item_type]" value="${item.item_type}">
                <input type="hidden" name="items[${itemIndex}][item_id]" value="${item.item_id}">
                <input type="hidden" name="items[${itemIndex}][description]" value="${item.item_name}">
                <input type="hidden" name="items[${itemIndex}][uom_id]" value="${item.uom_id || ''}">
                ${item.item_name}
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][ordered_quantity]" 
                       class="form-control ordered-qty" value="${item.pending_quantity || 0}" 
                       readonly step="0.001">
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][received_quantity]" 
                       class="form-control received-qty" value="${item.pending_quantity || 0}" 
                       step="0.001" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][accepted_quantity]" 
                       class="form-control accepted-qty" value="${item.pending_quantity || 0}" 
                       step="0.001" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][rejected_quantity]" 
                       class="form-control rejected-qty" value="0" 
                       step="0.001" readonly>
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
                <input type="number" name="items[${itemIndex}][discount_rate]" 
                       class="form-control discount-rate" value="${item.discount_rate || 0}" 
                       step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control item-total" readonly>
            </td>
            <td>
                <input type="text" name="items[${itemIndex}][batch_number]" 
                       class="form-control">
            </td>
            <td>
                <input type="date" name="items[${itemIndex}][expiry_date]" 
                       class="form-control">
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
    var acceptedQty = parseFloat(row.find('.accepted-qty').val()) || 0;
    var unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
    var taxRate = parseFloat(row.find('.tax-rate').val()) || 0;
    var discountRate = parseFloat(row.find('.discount-rate').val()) || 0;
    
    var lineTotal = acceptedQty * unitPrice;
    var taxAmount = (lineTotal * taxRate) / 100;
    var discountAmount = (lineTotal * discountRate) / 100;
    var total = lineTotal + taxAmount - discountAmount;
    
    row.find('.item-total').val(total.toFixed(2));
}

function calculateTotals() {
    var subtotal = 0;
    var totalTax = 0;
    var totalDiscount = 0;
    
    $('#itemsBody tr').each(function() {
        var acceptedQty = parseFloat($(this).find('.accepted-qty').val()) || 0;
        var unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
        var taxRate = parseFloat($(this).find('.tax-rate').val()) || 0;
        var discountRate = parseFloat($(this).find('.discount-rate').val()) || 0;
        
        var lineTotal = acceptedQty * unitPrice;
        var taxAmount = (lineTotal * taxRate) / 100;
        var discountAmount = (lineTotal * discountRate) / 100;
        
        subtotal += lineTotal;
        totalTax += taxAmount;
        totalDiscount += discountAmount;
    });
    
    var total = subtotal + totalTax - totalDiscount;
    
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