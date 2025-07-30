<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?= isset($po) ? 'EDIT' : 'CREATE' ?> PURCHASE ORDER
                        </h2>
                    </div>
                    <div class="body">
                        <form id="po_form" method="POST" action="<?= isset($po) ? base_url('purchase-orders/update/' . $po['id']) : base_url('purchase-orders/store') ?>">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>PO Number *</label>
                                            <input type="text" name="po_number" class="form-control" 
                                                   value="<?= isset($po) ? $po['po_number'] : $po_number ?>" 
                                                   readonly required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>PO Date *</label>
                                            <input type="date" name="po_date" class="form-control" 
                                                   value="<?= isset($po) ? $po['po_date'] : date('Y-m-d') ?>" 
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Supplier *</label>
                                            <select name="supplier_id" class="form-control show-tick" required>
                                                <option value="">-- Select Supplier --</option>
                                                <?php foreach ($suppliers as $supplier): ?>
                                                    <option value="<?= $supplier['id'] ?>" 
                                                            <?= (isset($po) && $po['supplier_id'] == $supplier['id']) ? 'selected' : '' ?>>
                                                        <?= $supplier['supplier_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Expected Delivery Date</label>
                                            <input type="date" name="delivery_date" class="form-control" 
                                                   value="<?= isset($po) ? $po['delivery_date'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Reference Number</label>
                                            <input type="text" name="reference_number" class="form-control" 
                                                   value="<?= isset($po) ? $po['reference_number'] : '' ?>"
                                                   placeholder="Quotation/Inquiry Reference">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Terms & Conditions</label>
                                            <textarea name="terms_conditions" class="form-control" rows="2"><?= isset($po) ? $po['terms_conditions'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4>Purchase Order Items</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                                        <i class="material-icons">add</i> Add Item
                                    </button>
                                </div>
                            </div>
                            <br>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="items_table">
                                    <thead>
                                        <tr>
                                            <th width="15%">Type</th>
                                            <th width="20%">Item</th>
                                            <th width="15%">Description</th>
                                            <th width="8%">Qty</th>
                                            <th width="10%">Unit Price</th>
                                            <th width="8%">Tax %</th>
                                            <th width="8%">Disc %</th>
                                            <th width="12%">Total</th>
                                            <th width="4%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items_body">
                                        <?php if (isset($po_items) && count($po_items) > 0): ?>
                                            <?php foreach ($po_items as $index => $item): ?>
                                                <script>
                                                    addItem(<?= json_encode($item) ?>);
                                                </script>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <script>addItem();</script>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-right">Subtotal:</th>
                                            <th class="text-right" id="subtotal">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-right">Tax:</th>
                                            <th class="text-right" id="total_tax">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-right">Discount:</th>
                                            <th class="text-right" id="total_discount">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-right">Total:</th>
                                            <th class="text-right" id="grand_total">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="3"><?= isset($po) ? $po['notes'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">save</i> Save Purchase Order
                                    </button>
                                    <a href="<?= base_url('purchase-orders') ?>" class="btn btn-default">
                                        <i class="material-icons">cancel</i> Cancel
                                    </a>
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
var itemIndex = 0;
var products = <?= json_encode($products) ?>;
var rawMaterials = <?= json_encode($raw_materials) ?>;

function addItem(data = null) {
    var html = '<tr id="item_row_' + itemIndex + '">';
    
    // Type
    html += '<td>';
    html += '<select name="items[' + itemIndex + '][item_type]" class="form-control" onchange="loadItems(' + itemIndex + ')" required>';
    html += '<option value="">-- Select --</option>';
    html += '<option value="raw_material"' + (data && data.item_type == 'raw_material' ? ' selected' : '') + '>Raw Material</option>';
    html += '<option value="product"' + (data && data.item_type == 'product' ? ' selected' : '') + '>Product</option>';
    html += '</select>';
    html += '</td>';
    
    // Item
    html += '<td>';
    html += '<select name="items[' + itemIndex + '][item_id]" id="item_id_' + itemIndex + '" class="form-control" onchange="getItemDetails(' + itemIndex + ')" required>';
    html += '<option value="">-- Select Item --</option>';
    html += '</select>';
    html += '</td>';
    
    // Description
    html += '<td>';
    html += '<input type="text" name="items[' + itemIndex + '][description]" class="form-control" value="' + (data ? data.description : '') + '">';
    html += '<input type="hidden" name="items[' + itemIndex + '][uom_id]" id="uom_id_' + itemIndex + '" value="' + (data ? data.uom_id : '') + '">';
    html += '<input type="hidden" name="items[' + itemIndex + '][po_item_id]" value="' + (data ? data.id : '') + '">';
    html += '</td>';
    
    // Quantity
    html += '<td>';
    html += '<input type="number" name="items[' + itemIndex + '][quantity]" id="quantity_' + itemIndex + '" class="form-control" value="' + (data ? data.quantity : '1') + '" min="0.001" step="0.001" onchange="calculateRow(' + itemIndex + ')" required>';
    html += '</td>';
    
    // Unit Price
    html += '<td>';
    html += '<input type="number" name="items[' + itemIndex + '][unit_price]" id="unit_price_' + itemIndex + '" class="form-control" value="' + (data ? data.unit_price : '0') + '" min="0" step="0.01" onchange="calculateRow(' + itemIndex + ')" required>';
    html += '</td>';
    
    // Tax Rate
    html += '<td>';
    html += '<input type="number" name="items[' + itemIndex + '][tax_rate]" id="tax_rate_' + itemIndex + '" class="form-control" value="' + (data ? data.tax_rate : '0') + '" min="0" max="100" step="0.01" onchange="calculateRow(' + itemIndex + ')">';
    html += '</td>';
    
    // Discount Rate
    html += '<td>';
    html += '<input type="number" name="items[' + itemIndex + '][discount_rate]" id="discount_rate_' + itemIndex + '" class="form-control" value="' + (data ? data.discount_rate : '0') + '" min="0" max="100" step="0.01" onchange="calculateRow(' + itemIndex + ')">';
    html += '</td>';
    
    // Total
    html += '<td class="text-right">';
    html += '<span id="total_' + itemIndex + '">0.00</span>';
    html += '</td>';
    
    // Action
    html += '<td>';
    html += '<button type="button" class="btn btn-danger btn-sm" onclick="removeItem(' + itemIndex + ')">';
    html += '<i class="material-icons">delete</i>';
    html += '</button>';
    html += '</td>';
    
    html += '</tr>';
    
    $('#items_body').append(html);
    
    // If editing, load items and set selected
    if (data) {
        loadItems(itemIndex, function() {
            $('#item_id_' + (itemIndex - 1)).val(data.item_id);
            calculateRow(itemIndex - 1);
        });
    }
    
    itemIndex++;
}

function loadItems(index, callback) {
    var itemType = $('select[name="items[' + index + '][item_type]"]').val();
    var selectElement = $('#item_id_' + index);
    
    selectElement.empty();
    selectElement.append('<option value="">-- Select Item --</option>');
    
    if (itemType == 'product') {
        $.each(products, function(i, product) {
            selectElement.append('<option value="' + product.id + '">' + product.product_name + ' (' + product.product_code + ')</option>');
        });
    } else if (itemType == 'raw_material') {
        $.each(rawMaterials, function(i, material) {
            selectElement.append('<option value="' + material.id + '">' + material.material_name + ' (' + material.material_code + ')</option>');
        });
    }
    
    if (callback) callback();
}

function getItemDetails(index) {
    var itemType = $('select[name="items[' + index + '][item_type]"]').val();
    var itemId = $('#item_id_' + index).val();
    
    if (itemType && itemId) {
        $.get('<?= base_url('purchase-orders/get-item-details') ?>', {
            item_type: itemType,
            item_id: itemId
        }, function(response) {
            if (response.success) {
                $('input[name="items[' + index + '][description]"]').val(response.item.description);
                $('#uom_id_' + index).val(response.item.uom_id);
                $('#unit_price_' + index).val(response.item.price);
                calculateRow(index);
            }
        });
    }
}

function calculateRow(index) {
    var quantity = parseFloat($('#quantity_' + index).val()) || 0;
    var unitPrice = parseFloat($('#unit_price_' + index).val()) || 0;
    var taxRate = parseFloat($('#tax_rate_' + index).val()) || 0;
    var discountRate = parseFloat($('#discount_rate_' + index).val()) || 0;
    
    var lineTotal = quantity * unitPrice;
    var taxAmount = (lineTotal * taxRate) / 100;
    var discountAmount = (lineTotal * discountRate) / 100;
    var total = lineTotal + taxAmount - discountAmount;
    
    $('#total_' + index).text(total.toFixed(2));
    
    calculateTotal();
}

function calculateTotal() {
    var subtotal = 0;
    var totalTax = 0;
    var totalDiscount = 0;
    
    $('#items_body tr').each(function() {
        var rowId = $(this).attr('id');
        if (rowId) {
            var index = rowId.replace('item_row_', '');
            
            var quantity = parseFloat($('#quantity_' + index).val()) || 0;
            var unitPrice = parseFloat($('#unit_price_' + index).val()) || 0;
            var taxRate = parseFloat($('#tax_rate_' + index).val()) || 0;
            var discountRate = parseFloat($('#discount_rate_' + index).val()) || 0;
            
            var lineTotal = quantity * unitPrice;
            var taxAmount = (lineTotal * taxRate) / 100;
            var discountAmount = (lineTotal * discountRate) / 100;
            
            subtotal += lineTotal;
            totalTax += taxAmount;
            totalDiscount += discountAmount;
        }
    });
    
    var grandTotal = subtotal + totalTax - totalDiscount;
    
    $('#subtotal').text(subtotal.toFixed(2));
    $('#total_tax').text(totalTax.toFixed(2));
    $('#total_discount').text(totalDiscount.toFixed(2));
    $('#grand_total').text(grandTotal.toFixed(2));
}

function removeItem(index) {
    if (confirm('Are you sure you want to remove this item?')) {
        $('#item_row_' + index).remove();
        calculateTotal();
    }
}

// Form validation
$('#po_form').on('submit', function(e) {
    var itemCount = $('#items_body tr').length;
    if (itemCount == 0) {
        alert('Please add at least one item');
        e.preventDefault();
        return false;
    }
});
</script>