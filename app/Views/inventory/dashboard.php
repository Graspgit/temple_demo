<section class="content">
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Inventory Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Products</p>
                            <h4 class="mb-2" id="totalProducts">0</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i><span id="activeProducts">0</span>
                                </span>
                                Active Items
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded-3">
                                <i class="bx bx-package font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Low Stock Items</p>
                            <h4 class="mb-2 text-warning" id="lowStockItems">0</h4>
                            <p class="text-muted mb-0">
                                <span class="text-danger fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-down-line me-1 align-middle"></i><span id="outOfStock">0</span>
                                </span>
                                Out of Stock
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded-3">
                                <i class="bx bx-error font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Expiring Soon</p>
                            <h4 class="mb-2 text-danger" id="expiringSoon">0</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info fw-bold font-size-12 me-2">
                                    <i class="ri-time-line me-1 align-middle"></i>Next 7 days
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-danger rounded-3">
                                <i class="bx bx-time font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Active Warehouses</p>
                            <h4 class="mb-2" id="activeWarehouses">0</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-store-line me-1 align-middle"></i>Storage Locations
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded-3">
                                <i class="bx bx-store font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Data Section -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">Master Data Management</h5>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-primary bg-soft text-primary rounded-circle font-size-18">
                                <i class="bx bx-category"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Product Categories</h5>
                    </div>
                    <p class="text-muted mb-3">Organize products into categories like Pooja Items, Prasadam, etc.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/categories') ?>" class="btn btn-primary btn-sm">
                            Manage Categories <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-success bg-soft text-success rounded-circle font-size-18">
                                <i class="bx bx-ruler"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Units of Measure</h5>
                    </div>
                    <p class="text-muted mb-3">Define units like kg, liters, pieces for accurate inventory tracking.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/uom') ?>" class="btn btn-success btn-sm">
                            Manage Units <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-warning bg-soft text-warning rounded-circle font-size-18">
                                <i class="bx bx-store"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Warehouses</h5>
                    </div>
                    <p class="text-muted mb-3">Manage storage locations like Kitchen, Pooja Store, Main Store.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/warehouses') ?>" class="btn btn-warning btn-sm">
                            Manage Warehouses <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-info bg-soft text-info rounded-circle font-size-18">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Products</h5>
                    </div>
                    <p class="text-muted mb-3">Manage all temple inventory items, prasadam ingredients, etc.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/products') ?>" class="btn btn-info btn-sm">
                            Manage Products <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Management Section -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Stock Management</h5>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-success bg-soft text-success rounded-circle font-size-18">
                                <i class="bx bx-down-arrow-circle"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Stock In</h5>
                    </div>
                    <p class="text-muted mb-3">Record purchases, donations, and other stock receipts.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/stock-in') ?>" class="btn btn-outline-success btn-sm">
                            New Stock In <i class="mdi mdi-plus ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-danger bg-soft text-danger rounded-circle font-size-18">
                                <i class="bx bx-up-arrow-circle"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Stock Out</h5>
                    </div>
                    <p class="text-muted mb-3">Issue stock for pooja, prasadam preparation, or other uses.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/stock-out') ?>" class="btn btn-outline-danger btn-sm">
                            New Stock Out <i class="mdi mdi-minus ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-info bg-soft text-info rounded-circle font-size-18">
                                <i class="bx bx-transfer"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Stock Transfer</h5>
                    </div>
                    <p class="text-muted mb-3">Move stock between warehouses or storage locations.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/stock-transfer') ?>" class="btn btn-outline-info btn-sm">
                            New Transfer <i class="mdi mdi-swap-horizontal ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-warning bg-soft text-warning rounded-circle font-size-18">
                                <i class="bx bx-edit"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0">Stock Adjustment</h5>
                    </div>
                    <p class="text-muted mb-3">Adjust stock for damages, corrections, or physical count.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('inventory/stock-adjustment') ?>" class="btn btn-outline-warning btn-sm">
                            New Adjustment <i class="mdi mdi-pencil ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Alerts -->
    <div class="row mt-4">
        <!-- Low Stock Alert -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="bx bx-error text-warning"></i> Low Stock Alert
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="lowStockTable">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiring Items -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="bx bx-time text-danger"></i> Expiring Soon
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Batch</th>
                                    <th>Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                </tr>
                            </thead>
                            <tbody id="expiringTable">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Recent Transactions</h4>
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Transaction</th>
                                    <th>Type</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Warehouse</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody id="recentTransactions">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Reports & Analytics</h5>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                <i class="bx bx-list-check"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Stock Report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-16">
                                <i class="bx bx-line-chart"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Movement Report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-16">
                                <i class="bx bx-calendar"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Expiry Report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-16">
                                <i class="bx bx-pie-chart-alt"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Valuation Report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-danger bg-soft text-danger font-size-16">
                                <i class="bx bx-error-circle"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Reorder Report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="avatar-sm mb-2">
                            <span class="avatar-title rounded-circle bg-dark bg-soft text-dark font-size-16">
                                <i class="bx bx-receipt"></i>
                            </span>
                        </div>
                        <p class="text-muted mb-0 text-center">Usage Report</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script>
$(document).ready(function() {
    // Load dashboard statistics
    loadDashboardStats();
    loadLowStockItems();
    loadExpiringItems();
    loadRecentTransactions();

    function loadDashboardStats() {
        $.ajax({
            url: '<?= base_url('api/inventory/dashboard-stats') ?>',
            type: 'GET',
            success: function(response) {
                $('#totalProducts').text(response.total_products || 0);
                $('#activeProducts').text(response.active_products || 0);
                $('#lowStockItems').text(response.low_stock_items || 0);
                $('#outOfStock').text(response.out_of_stock || 0);
                $('#expiringSoon').text(response.expiring_soon || 0);
                $('#activeWarehouses').text(response.active_warehouses || 0);
            }
        });
    }

    function loadLowStockItems() {
        $.ajax({
            url: '<?= base_url('api/inventory/low-stock') ?>',
            type: 'GET',
            success: function(response) {
                var html = '';
                if (response.items && response.items.length > 0) {
                    response.items.slice(0, 5).forEach(function(item) {
                        html += '<tr>' +
                                '<td><strong>' + item.product_name + '</strong></td>' +
                                '<td><span class="badge bg-secondary">' + item.category + '</span></td>' +
                                '<td class="text-danger">' + item.current_stock + ' ' + item.unit + '</td>' +
                                '<td>' + item.reorder_level + ' ' + item.unit + '</td>' +
                                '<td><a href="<?= base_url('inventory/stock-in') ?>?product=' + item.product_id + 
                                '" class="btn btn-sm btn-warning">Reorder</a></td>' +
                                '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-muted">No low stock items</td></tr>';
                }
                $('#lowStockTable').html(html);
            }
        });
    }

    function loadExpiringItems() {
        $.ajax({
            url: '<?= base_url('api/inventory/expiring-items') ?>',
            type: 'GET',
            success: function(response) {
                var html = '';
                if (response.items && response.items.length > 0) {
                    response.items.slice(0, 5).forEach(function(item) {
                        var daysLeft = item.days_left;
                        var badgeClass = daysLeft <= 3 ? 'danger' : (daysLeft <= 7 ? 'warning' : 'info');
                        
                        html += '<tr>' +
                                '<td><strong>' + item.product_name + '</strong></td>' +
                                '<td>' + item.batch_number + '</td>' +
                                '<td>' + item.quantity + ' ' + item.unit + '</td>' +
                                '<td>' + new Date(item.expiry_date).toLocaleDateString() + '</td>' +
                                '<td><span class="badge bg-' + badgeClass + '">' + daysLeft + ' days</span></td>' +
                                '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-muted">No expiring items</td></tr>';
                }
                $('#expiringTable').html(html);
            }
        });
    }

    function loadRecentTransactions() {
        $.ajax({
            url: '<?= base_url('api/inventory/recent-transactions') ?>',
            type: 'GET',
            success: function(response) {
                var html = '';
                if (response.transactions && response.transactions.length > 0) {
                    response.transactions.slice(0, 10).forEach(function(trans) {
                        var typeClass = {
                            'IN': 'success',
                            'OUT': 'danger',
                            'TRANSFER': 'info',
                            'ADJUSTMENT': 'warning'
                        };
                        
                        html += '<tr>' +
                                '<td>' + new Date(trans.date).toLocaleDateString() + '</td>' +
                                '<td><strong>' + trans.transaction_number + '</strong></td>' +
                                '<td><span class="badge bg-' + (typeClass[trans.type] || 'secondary') + '">' + 
                                trans.type + '</span></td>' +
                                '<td>' + trans.product_name + '</td>' +
                                '<td class="text-end">' + trans.quantity + ' ' + trans.unit + '</td>' +
                                '<td>' + trans.warehouse + '</td>' +
                                '<td>' + trans.created_by + '</td>' +
                                '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="7" class="text-center text-muted">No recent transactions</td></tr>';
                }
                $('#recentTransactions').html(html);
            }
        });
    }

    // Refresh data every 5 minutes
    setInterval(function() {
        loadDashboardStats();
        loadLowStockItems();
        loadExpiringItems();
        loadRecentTransactions();
    }, 300000);
});
</script>