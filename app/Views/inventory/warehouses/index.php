<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Warehouses</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory') ?>">Inventory</a></li>
                        <li class="breadcrumb-item active">Warehouses</li>
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
                            <h4 class="card-title">All Warehouses</h4>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <a href="<?= base_url('inventory/warehouses/create') ?>" class="btn btn-success btn-rounded waves-effect waves-light mb-2">
                                    <i class="mdi mdi-plus me-1"></i> Add New Warehouse
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="warehouseTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Features</th>
                                <th>Default</th>
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

    <!-- Stock Check Modal -->
    <div class="modal fade" id="stockCheckModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Current Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="stockCheckContent">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var table = $('#warehouseTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('inventory/warehouses/datatables') ?>',
            type: 'POST'
        },
        columns: [
            { 
                data: 'code',
                render: function(data) {
                    return '<span class="badge bg-dark">' + data + '</span>';
                }
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    var html = '<strong>' + data + '</strong>';
                    if (row.address) {
                        html += '<br><small class="text-muted"><i class="bx bx-map"></i> ' + row.address + '</small>';
                    }
                    return html;
                }
            },
            { 
                data: 'type',
                render: function(data) {
                    var typeColors = {
                        'main': 'primary',
                        'kitchen': 'warning',
                        'pooja': 'info',
                        'prasadam': 'success',
                        'general': 'secondary'
                    };
                    var typeLabels = {
                        'main': 'Main Store',
                        'kitchen': 'Kitchen',
                        'pooja': 'Pooja Items',
                        'prasadam': 'Prasadam',
                        'general': 'General'
                    };
                    return '<span class="badge bg-' + (typeColors[data] || 'secondary') + '">' + 
                           (typeLabels[data] || data) + '</span>';
                }
            },
            { 
                data: 'location',
                render: function(data) {
                    return data || '<span class="text-muted">-</span>';
                }
            },
            { 
                data: 'contact_person',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-muted">-</span>';
                    var html = data;
                    if (row.contact_number) {
                        html += '<br><small><i class="bx bx-phone"></i> ' + row.contact_number + '</small>';
                    }
                    return html;
                }
            },
            { 
                data: 'temperature_controlled',
                render: function(data, type, row) {
                    var features = [];
                    if (data == 1) {
                        features.push('<span class="badge bg-info" title="Temperature Controlled"><i class="bx bx-wind"></i></span>');
                    }
                    if (row.allow_negative_stock == 1) {
                        features.push('<span class="badge bg-warning" title="Allows Negative Stock"><i class="bx bx-minus"></i></span>');
                    }
                    if (row.security_level == 'high') {
                        features.push('<span class="badge bg-danger" title="High Security"><i class="bx bx-lock"></i></span>');
                    }
                    return features.length > 0 ? features.join(' ') : '<span class="text-muted">-</span>';
                }
            },
            { 
                data: 'is_default',
                render: function(data) {
                    return data == 1 ? '<span class="badge bg-primary">Default</span>' : '';
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
                    var actions = inventoryCommon.renderActions(data, '<?= base_url('inventory/warehouses') ?>');
                    
                    // Add stock check button
                    actions = '<button class="btn btn-info btn-sm me-1 check-stock" data-id="' + data + '" ' +
                              'data-name="' + row.name + '" title="Check Stock">' +
                              '<i class="bx bx-package"></i></button>' + actions;
                    
                    return actions;
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        drawCallback: function() {
            inventoryCommon.initializeActions('warehouseTable', '<?= base_url('inventory/warehouses') ?>');
            
            // Handle stock check
            $('.check-stock').on('click', function() {
                var warehouseId = $(this).data('id');
                var warehouseName = $(this).data('name');
                
                $('#stockCheckModal .modal-title').text('Current Stock - ' + warehouseName);
                $('#stockCheckContent').html('<div class="text-center"><i class="bx bx-loader bx-spin"></i> Loading...</div>');
                $('#stockCheckModal').modal('show');
                
                $.ajax({
                    url: '<?= base_url('inventory/warehouses/check-stock') ?>/' + warehouseId,
                    type: 'GET',
                    success: function(response) {
                        var html = '';
                        if (response.stock_count === 0) {
                            html = '<div class="alert alert-info">No stock in this warehouse.</div>';
                        } else {
                            html = '<div class="table-responsive">' +
                                   '<table class="table table-sm table-bordered">' +
                                   '<thead><tr>' +
                                   '<th>Product Code</th>' +
                                   '<th>Product Name</th>' +
                                   '<th>Quantity</th>' +
                                   '<th>Unit</th>' +
                                   '</tr></thead><tbody>';
                            
                            response.stock_items.forEach(function(item) {
                                html += '<tr>' +
                                        '<td>' + item.product_code + '</td>' +
                                        '<td>' + item.product_name + '</td>' +
                                        '<td class="text-end">' + parseFloat(item.quantity).toLocaleString() + '</td>' +
                                        '<td>' + item.unit + '</td>' +
                                        '</tr>';
                            });
                            
                            html += '</tbody></table></div>';
                            html += '<div class="text-muted small mt-2">Total items: ' + response.stock_count + '</div>';
                        }
                        $('#stockCheckContent').html(html);
                    },
                    error: function() {
                        $('#stockCheckContent').html('<div class="alert alert-danger">Error loading stock data.</div>');
                    }
                });
            });
        }
    });
});
</script>