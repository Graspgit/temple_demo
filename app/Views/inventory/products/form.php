<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Products</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory') ?>">Inventory</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <h4 class="card-title">All Products</h4>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <a href="<?= base_url('inventory/products/import-template') ?>" class="btn btn-info btn-rounded waves-effect waves-light mb-2">
                                    <i class="mdi mdi-download me-1"></i> Import Template
                                </a>
                                <a href="<?= base_url('inventory/products/create') ?>" class="btn btn-success btn-rounded waves-effect waves-light mb-2">
                                    <i class="mdi mdi-plus me-1"></i> Add New Product
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">
                            All Products
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info filter-btn" data-filter="pooja">
                            <i class="bx bx-sun"></i> Pooja Items
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success filter-btn" data-filter="prasadam">
                            <i class="bx bx-bowl-hot"></i> Prasadam Items
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning filter-btn" data-filter="donation">
                            <i class="bx bx-gift"></i> Donation Items
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger filter-btn" data-filter="perishable">
                            <i class="bx bx-time"></i> Perishable
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary filter-btn" data-filter="low-stock">
                            <i class="bx bx-error"></i> Low Stock
                        </button>
                    </div>

                    <table id="productTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Stock Info</th>
                                <th>Price</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Details Modal -->
    <div class="modal fade" id="stockDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="stockDetailsContent">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var currentFilter = 'all';
    
    var table = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('inventory/products/datatables') ?>',
            type: 'POST',
            data: function(d) {
                d.filter = currentFilter;
            }
        },
        columns: [
            { 
                data: 'code',
                render: function(data, type, row) {
                    var html = '<span class="badge bg-dark">' + data + '</span>';
                    if (row.barcode) {
                        html += '<br><small class="text-muted">' + row.barcode + '</small>';
                    }
                    return html;
                }
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    var html = '<strong>' + data + '</strong>';
                    if (row.description) {
                        html += '<br><small class="text-muted">' + 
                                row.description.substring(0, 50) + 
                                (row.description.length > 50 ? '...' : '') + '</small>';
                    }
                    return html;
                }
            },
            { 
                data: 'category_name',
                render: function(data) {
                    return '<span class="badge bg-secondary">' + data + '</span>';
                }
            },
            { 
                data: 'is_pooja_item',
                render: function(data, type, row) {
                    var badges = [];
                    
                    if (row.is_pooja_item == 1) {
                        badges.push('<span class="badge bg-info">Pooja</span>');
                    }
                    if (row.is_prasadam_item == 1) {
                        badges.push('<span class="badge bg-success">Prasadam</span>');
                    }
                    if (row.is_donation_item == 1) {
                        badges.push('<span class="badge bg-warning">Donation</span>');
                    }
                    if (row.is_perishable == 1) {
                        badges.push('<span class="badge bg-danger">Perishable</span>');
                    }
                    
                    return badges.length > 0 ? badges.join(' ') : '<span class="text-muted">-</span>';
                }
            },
            { 
                data: 'minimum_stock',
                render: function(data, type, row) {
                    var html = '<div class="text-center">';
                    
                    // Show stockable status
                    if (row.is_stockable == 0) {
                        html += '<span class="text-muted">Non-stockable</span>';
                    } else {
                        html += '<button class="btn btn-sm btn-link check-stock" data-id="' + row.id + '" ' +
                                'data-name="' + row.name + '">' +
                                '<i class="bx bx-package"></i> Check Stock</button>';
                        
                        // Show reorder info if available
                        if (row.reorder_level) {
                            html += '<br><small class="text-muted">Reorder: ' + 
                                    parseFloat(row.reorder_level).toLocaleString() + ' ' + row.unit_abbr + '</small>';
                        }
                    }
                    
                    html += '</div>';
                    return html;
                }
            },
            { 
                data: 'purchase_price',
                render: function(data, type, row) {
                    var html = '';
                    if (row.purchase_price) {
                        html += 'Buy: ₹' + parseFloat(row.purchase_price).toFixed(2);
                    }
                    if (row.selling_price) {
                        if (html) html += '<br>';
                        html += 'Sell: ₹' + parseFloat(row.selling_price).toFixed(2);
                    }
                    return html || '<span class="text-muted">-</span>';
                }
            },
            { 
                data: 'supplier_name',
                render: function(data) {
                    return data || '<span class="text-muted">-</span>';
                }
            },
            { 
                data: 'is_active',
                render: function(data) {
                    return inventoryCommon.renderStatus(data);
                }
            },
            { 
                data: 'id',
                render: function(data, type, row) {
                    return inventoryCommon.renderActions(data, '<?= base_url('inventory/products') ?>');
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        drawCallback: function() {
            inventoryCommon.initializeActions('productTable', '<?= base_url('inventory/products') ?>');
            
            // Handle stock check
            $('.check-stock').off('click').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var productName = $(this).data('name');
                
                $('#stockDetailsModal .modal-title').text('Stock Details - ' + productName);
                $('#stockDetailsContent').html('<div class="text-center"><i class="bx bx-loader bx-spin"></i> Loading...</div>');
                $('#stockDetailsModal').modal('show');
                
                $.ajax({
                    url: '<?= base_url('inventory/products/check-stock') ?>/' + productId,
                    type: 'GET',
                    success: function(response) {
                        var html = '<div class="mb-3">' +
                                   '<h5>Total Stock: <span class="text-primary">' + 
                                   parseFloat(response.total_stock).toLocaleString() + '</span></h5>' +
                                   '</div>';
                        
                        if (response.warehouses.length === 0) {
                            html += '<div class="alert alert-info">No stock available in any warehouse.</div>';
                        } else {
                            html += '<div class="table-responsive">' +
                                    '<table class="table table-sm table-bordered">' +
                                    '<thead><tr>' +
                                    '<th>Warehouse</th>' +
                                    '<th>Type</th>' +
                                    '<th>Quantity</th>' +
                                    '<th>Last Updated</th>' +
                                    '</tr></thead><tbody>';
                            
                            response.warehouses.forEach(function(item) {
                                var typeColor = {
                                    'main': 'primary',
                                    'kitchen': 'warning',
                                    'pooja': 'info',
                                    'prasadam': 'success',
                                    'general': 'secondary'
                                };
                                
                                html += '<tr>' +
                                        '<td>' + item.warehouse_name + '</td>' +
                                        '<td><span class="badge bg-' + (typeColor[item.warehouse_type] || 'secondary') + '">' + 
                                        item.warehouse_type + '</span></td>' +
                                        '<td class="text-end">' + parseFloat(item.quantity).toLocaleString() + '</td>' +
                                        '<td>' + (item.updated_at ? new Date(item.updated_at).toLocaleDateString() : '-') + '</td>' +
                                        '</tr>';
                            });
                            
                            html += '</tbody></table></div>';
                        }
                        
                        $('#stockDetailsContent').html(html);
                    },
                    error: function() {
                        $('#stockDetailsContent').html('<div class="alert alert-danger">Error loading stock data.</div>');
                    }
                });
            });
        }
    });
    
    // Filter buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        table.ajax.reload();
    });
});
</script>