<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
    </div>
    <div class="card-body">
        <form id="stockOutForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stock Out Date <span class="text-danger">*</span></label>
                        <input type="date" name="stock_out_date" class="form-control" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Stock Out Type <span class="text-danger">*</span></label>
                        <select name="stock_out_type" id="stock_out_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <?php foreach($stockOutTypes as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fund</label>
                        <select name="fund_id" class="form-control">
                            <option value="1">General Fund</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Customer Section (shown only for sales) -->
            <div id="customerSection" style="display: none;">
                <hr>
                <h5>Customer Details</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Mobile</label>
                            <input type="text" name="customer_mobile" id="customer_mobile" 
                                   class="form-control" placeholder="Enter mobile to search">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   class="form-control" placeholder="Guest customer name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Info</label>
                            <div id="customerInfo" class="form-control-plaintext">
                                <span class="text-muted">Enter mobile number to search customer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Items</h5>
            
            <!-- Item Selection -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Item Type</label>
                        <select id="item_type_select" class="form-control">
                            <option value="product">Product</option>
                            <option value="rawmaterial">Raw Material</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Item</label>
                        <select id="item_select" class="form-control">
                            <option value="">Select Item</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" id="item_quantity" class="form-control" 
                               step="0.001" min="0.001">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Unit Price</label>
                        <input type="number" id="item_price" class="form-control" 
                               step="0.01" min="0" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" id="addItemBtn" class="btn btn-primary btn-block">
                            Add Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Unit Price</th>
                            <th>Discount %</th>
                            <th>Tax %</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-right">Subtotal:</th>
                            <th id="subtotal">0.00</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-right">Discount:</th>
                            <th id="totalDiscount">0.00</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-right">Tax:</th>
                            <th id="totalTax">0.00</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="8" class="text-right">Total Amount:</th>
                            <th id="totalAmount">0.00</th>
                            <th></th>
                        </tr>
                        <tr class="bg-light">
                            <th colspan="8" class="text-right">Total Cost (Avg):</th>
                            <th id="totalCost">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Narration</label>
                        <textarea name="narration" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save Stock Out</button>
                <a href="<?= base_url('stock-out') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    let items = [];
    let itemsData = {};
    
    // Initialize select2
    $('#item_select').select2({
        placeholder: 'Select Item',
        allowClear: true
    });

    // Load items based on type
    loadItems('product');

    $('#item_type_select').change(function() {
        loadItems($(this).val());
    });

    // Stock out type change
    $('#stock_out_type').change(function() {
        const type = $(this).val();
        if (type === 'sale') {
            $('#customerSection').show();
            $('#item_price').prop('disabled', false);
        } else {
            $('#customerSection').hide();
            $('#item_price').prop('disabled', true).val('0');
        }
        calculateTotals();
    });

    // Customer mobile search
    $('#customer_mobile').on('blur', function() {
        const mobile = $(this).val();
        if (mobile) {
            $.get('<?= base_url('stock-out/check-customer') ?>', { mobile: mobile })
                .done(function(response) {
                    if (response.found) {
                        $('#customer_name').val(response.customer.name).prop('readonly', true);
                        $('#customerInfo').html(
                            '<strong>' + response.customer.name + '</strong><br>' +
                            'Mobile: ' + response.customer.mobile + '<br>' +
                            'Type: Registered Customer'
                        );
                    } else {
                        $('#customer_name').prop('readonly', false);
                        $('#customerInfo').html('<span class="text-warning">New customer - Enter name</span>');
                    }
                });
        }
    });

    // Item selection
    $('#item_select').change(function() {
        const itemId = $(this).val();
        const itemType = $('#item_type_select').val();
        
        if (itemId) {
            $.get('<?= base_url('stock-out/item-details') ?>', {
                item_type: itemType,
                item_id: itemId
            }).done(function(response) {
                if (response.success) {
                    itemsData[itemType + '_' + itemId] = response;
                    $('#item_quantity').attr('max', response.current_stock);
                    
                    // Set default price for products
                    if (itemType === 'product' && $('#stock_out_type').val() === 'sale') {
                        $('#item_price').val(response.item.selling_price || 0);
                    }
                }
            });
        }
    });

    // Add item
    $('#addItemBtn').click(function() {
        const itemType = $('#item_type_select').val();
        const itemId = $('#item_select').val();
        const quantity = parseFloat($('#item_quantity').val());
        const unitPrice = parseFloat($('#item_price').val()) || 0;
        
        if (!itemId || !quantity || quantity <= 0) {
            alert('Please select item and enter valid quantity');
            return;
        }

        const itemData = itemsData[itemType + '_' + itemId];
        if (!itemData) {
            alert('Please select item again');
            return;
        }

        if (quantity > itemData.current_stock) {
            alert('Insufficient stock. Available: ' + itemData.current_stock);
            return;
        }

        // Check if item already added
        const existingIndex = items.findIndex(i => i.item_type === itemType && i.item_id === itemId);
        if (existingIndex >= 0) {
            alert('Item already added');
            return;
        }

        const item = {
            item_type: itemType,
            item_id: itemId,
            item_name: itemData.item[itemType === 'product' ? 'product_name' : 'material_name'],
            quantity: quantity,
            current_stock: itemData.current_stock,
            unit_cost: itemData.average_cost,
            unit_price: unitPrice,
            discount_percent: 0,
            tax_percent: 0
        };

        items.push(item);
        renderItemsTable();
        
        // Reset form
        $('#item_select').val('').trigger('change');
        $('#item_quantity').val('');
        $('#item_price').val('0');
    });

    // Render items table
    function renderItemsTable() {
        const tbody = $('#itemsTableBody');
        tbody.empty();

        items.forEach((item, index) => {
            const row = createItemRow(item, index);
            tbody.append(row);
        });

        calculateTotals();
    }

    // Create item row
    function createItemRow(item, index) {
        const stockOutType = $('#stock_out_type').val();
        const isSale = stockOutType === 'sale';
        
        const itemTotal = item.quantity * item.unit_price;
        const discountAmount = (item.discount_percent / 100) * itemTotal;
        const afterDiscount = itemTotal - discountAmount;
        const taxAmount = (item.tax_percent / 100) * afterDiscount;
        const total = afterDiscount + taxAmount;

        return `
            <tr>
                <td>${item.item_name}</td>
                <td>${item.item_type}</td>
                <td>${item.current_stock}</td>
                <td>${item.quantity}</td>
                <td>${item.unit_cost}</td>
                <td>
                    ${isSale ? `<input type="number" class="form-control form-control-sm item-price" 
                        data-index="${index}" value="${item.unit_price}" step="0.01" min="0">` 
                        : item.unit_price}
                </td>
                <td>
                    ${isSale ? `<input type="number" class="form-control form-control-sm item-discount" 
                        data-index="${index}" value="${item.discount_percent}" step="0.01" min="0" max="100">` 
                        : '0'}
                </td>
                <td>
                    ${isSale ? `<input type="number" class="form-control form-control-sm item-tax" 
                        data-index="${index}" value="${item.tax_percent}" step="0.01" min="0" max="100">` 
                        : '0'}
                </td>
                <td>${isSale ? total.toFixed(2) : '0.00'}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Event handlers for dynamic inputs
    $(document).on('change', '.item-price, .item-discount, .item-tax', function() {
        const index = $(this).data('index');
        const field = $(this).hasClass('item-price') ? 'unit_price' : 
                     $(this).hasClass('item-discount') ? 'discount_percent' : 'tax_percent';
        items[index][field] = parseFloat($(this).val()) || 0;
        renderItemsTable();
    });

    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        items.splice(index, 1);
        renderItemsTable();
    });

    // Calculate totals
    function calculateTotals() {
        const stockOutType = $('#stock_out_type').val();
        const isSale = stockOutType === 'sale';
        
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        let totalCost = 0;

        items.forEach(item => {
            const itemCost = item.quantity * item.unit_cost;
            totalCost += itemCost;

            if (isSale) {
                const itemTotal = item.quantity * item.unit_price;
                const discountAmount = (item.discount_percent / 100) * itemTotal;
                const afterDiscount = itemTotal - discountAmount;
                const taxAmount = (item.tax_percent / 100) * afterDiscount;
                
                subtotal += itemTotal;
                totalDiscount += discountAmount;
                totalTax += taxAmount;
            }
        });

        const totalAmount = subtotal - totalDiscount + totalTax;

        $('#subtotal').text(subtotal.toFixed(2));
        $('#totalDiscount').text(totalDiscount.toFixed(2));
        $('#totalTax').text(totalTax.toFixed(2));
        $('#totalAmount').text(totalAmount.toFixed(2));
        $('#totalCost').text(totalCost.toFixed(2));
    }

    // Form submission
    $('#stockOutForm').submit(function(e) {
        e.preventDefault();

        if (items.length === 0) {
            alert('Please add at least one item');
            return;
        }

        const formData = $(this).serializeArray();
        formData.push({ name: 'items', value: JSON.stringify(items) });

        $.post('<?= base_url('stock-out/store') ?>', formData)
            .done(function(response) {
                if (response.success) {
                    alert('Stock out created successfully');
                    window.location.href = '<?= base_url('stock-out') ?>';
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function() {
                alert('Error occurred while saving');
            });
    });

    // Load items function
    function loadItems(type) {
        $('#item_select').empty().append('<option value="">Select Item</option>');
        
        const items = type === 'product' ? <?= json_encode($products) ?> : <?= json_encode($rawMaterials) ?>;
        const nameField = type === 'product' ? 'product_name' : 'material_name';
        
        items.forEach(item => {
            $('#item_select').append(`<option value="${item.id}">${item[nameField]}</option>`);
        });
    }
});
</script>