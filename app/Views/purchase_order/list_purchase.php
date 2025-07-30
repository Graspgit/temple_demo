<?php global $lang;?>
<?php $db = db_connect();?>

<style>
/* Enhanced styles that integrate with your existing design */
.enhanced-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.enhanced-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    position: relative;
    overflow: hidden;
}

.enhanced-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.enhanced-header-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.enhanced-header-title {
    flex: 1;
}

.enhanced-header-title h2 {
    font-size: 2rem;
    font-weight: 300;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.enhanced-header-title p {
    font-size: 1rem;
    opacity: 0.9;
}

.enhanced-header-stats {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: rgba(255,255,255,0.15);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    min-width: 120px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

.enhanced-header-actions {
    display: flex;
    gap: 10px;
}

.btn-enhanced {
    padding: 12px 20px;
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

.btn-enhanced.btn-primary {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.btn-enhanced.btn-success {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
}

.btn-enhanced.btn-warning {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
}

.btn-enhanced.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.btn-enhanced.btn-secondary {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
    color: white;
}

.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    text-decoration: none;
    color: white;
}

.btn-enhanced.btn-sm {
    padding: 8px 16px;
    font-size: 12px;
}

/* Enhanced body */
.enhanced-body {
    padding: 30px;
}

/* Filters section */
.filters-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.enhanced-form-control {
    padding: 12px 15px;
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.enhanced-form-control:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

/* Enhanced table */
.enhanced-table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.enhanced-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.enhanced-table thead {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
}

.enhanced-table thead th {
    padding: 20px 15px;
    text-align: center;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
    border: none;
}

.enhanced-table tbody td {
    padding: 15px;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    border-top: none;
}

.enhanced-table tbody tr:hover {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

/* Enhanced status badges */
.enhanced-status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.enhanced-status-badge.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.enhanced-status-badge.status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.enhanced-status-badge.status-draft {
    background: #e2e3e5;
    color: #41464b;
    border: 1px solid #d6d8db;
}

/* Enhanced action buttons */
.enhanced-action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Enhanced modal */
.enhanced-modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.enhanced-modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    width: 90%;
    max-width: 500px;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.enhanced-modal-header {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    padding: 25px;
    border-radius: 15px 15px 0 0;
    position: relative;
}

.enhanced-modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.enhanced-close {
    position: absolute;
    right: 20px;
    top: 20px;
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.enhanced-close:hover {
    background: rgba(255,255,255,0.2);
    transform: rotate(90deg);
}

.enhanced-modal-body {
    padding: 30px;
}

/* Enhanced alert styles */
.enhanced-alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
    border: none;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 10px;
}

.enhanced-alert::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: currentColor;
}

.enhanced-alert.alert-success {
    background: #d4edda;
    color: #155724;
}

.enhanced-alert.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.enhanced-alert.alert-info {
    background: #cce7ff;
    color: #004085;
}

/* Responsive design */
@media (max-width: 768px) {
    .enhanced-header {
        padding: 20px;
    }

    .enhanced-header-title h2 {
        font-size: 1.5rem;
    }

    .enhanced-header-stats {
        flex-direction: column;
        gap: 15px;
    }

    .stat-card {
        min-width: auto;
    }

    .enhanced-body {
        padding: 20px;
    }

    .filters-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .enhanced-action-buttons {
        flex-direction: column;
    }

    .enhanced-modal-content {
        width: 95%;
        margin: 10% auto;
    }

    .enhanced-table {
        font-size: 12px;
    }

    .enhanced-table th,
    .enhanced-table td {
        padding: 10px 8px;
    }
}

/* PO number highlighting */
.po-number-highlight {
    background: linear-gradient(45deg, #3498db, #2980b9);
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
}

/* Amount formatting */
.amount-display {
    font-weight: 600;
    color: #27ae60;
    font-family: 'Courier New', monospace;
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
    display: inline-block;
    margin-right: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<section class="content">
    <div class="container-fluid">
        <!-- Enhanced Header -->
        <div class="enhanced-card">
            <div class="enhanced-header">
                <div class="enhanced-header-content">
                    <div class="enhanced-header-title">
                        <h2><i class="material-icons">&#xE85C;</i> Purchase Orders</h2>
                        <p>Manage and track all your purchase orders</p>
                    </div>
                    <div class="enhanced-header-actions">
                        <a href="<?php echo base_url(); ?>/Purchase_order/add?req_typ=2" class="btn-enhanced btn-success">
                            <i class="material-icons">&#xE145;</i> <?php echo $lang->add; ?>
                        </a>
                        <a href="<?php echo base_url(); ?>/purchase_order" class="btn-enhanced btn-primary">
                            <i class="material-icons">&#xE85B;</i> Sales Orders
                        </a>
                    </div>
                </div>
                
                <div class="enhanced-header-stats">
                    <div class="stat-card">
                        <div class="stat-number" id="totalOrders">
                            <?php echo !empty($suppliers) ? count($suppliers) : 0; ?>
                        </div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="pendingOrders">
                            <?php 
                            $pending = 0;
                            if(!empty($suppliers)) {
                                foreach($suppliers as $supplier) {
                                    if($supplier["is_approved"] == 0) $pending++;
                                }
                            }
                            echo $pending;
                            ?>
                        </div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="approvedOrders">
                            <?php 
                            $approved = 0;
                            if(!empty($suppliers)) {
                                foreach($suppliers as $supplier) {
                                    if($supplier["is_approved"] == 1) $approved++;
                                }
                            }
                            echo $approved;
                            ?>
                        </div>
                        <div class="stat-label">Approved</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="totalValue">
                            RM <?php 
                            $total_value = 0;
                            if(!empty($suppliers)) {
                                foreach($suppliers as $supplier) {
                                    $total_value += $supplier["grand_total"];
                                }
                            }
                            echo number_format($total_value, 0);
                            ?>
                        </div>
                        <div class="stat-label">Total Value</div>
                    </div>
                </div>
            </div>

            <div class="enhanced-body">
                <!-- Alert Messages -->
                <?php if($_SESSION['succ'] != '') { ?>
                <div class="enhanced-alert alert-success" id="content_alert">
                    <i class="material-icons">&#xE5CA;</i>
                    <span><?php echo $_SESSION['succ']; ?></span>
                    <span class="enhanced-close" onclick="this.parentElement.style.display='none';" style="position: absolute; right: 15px; top: 15px; font-size: 20px;">&times;</span>
                </div>
                <?php } ?>
                
                <?php if($_SESSION['fail'] != '') { ?>
                <div class="enhanced-alert alert-danger" id="content_alert">
                    <i class="material-icons">&#xE000;</i>
                    <span><?php echo $_SESSION['fail']; ?></span>
                    <span class="enhanced-close" onclick="this.parentElement.style.display='none';" style="position: absolute; right: 15px; top: 15px; font-size: 20px;">&times;</span>
                </div>
                <?php } ?>

                <!-- Filters Section -->
                <div class="filters-section">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" class="enhanced-form-control">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="supplierFilter">Supplier</label>
                            <select id="supplierFilter" class="enhanced-form-control">
                                <option value="">All Suppliers</option>
                                <?php if(!empty($supparr)) {
                                    foreach($supparr as $id => $name) { ?>
                                        <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFrom">Date From</label>
                            <input type="date" id="dateFrom" class="enhanced-form-control">
                        </div>
                        <div class="filter-group">
                            <label for="dateTo">Date To</label>
                            <input type="date" id="dateTo" class="enhanced-form-control">
                        </div>
                        <div class="filter-group">
                            <button type="button" class="btn-enhanced btn-primary" onclick="applyFilters()">
                                <i class="material-icons">&#xE8B6;</i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Purchase Orders Table -->
                <div class="enhanced-table-container">
                    <div class="table-responsive">
                        <table class="enhanced-table dataTable" id="datatables">
                            <thead>
                                <tr>
                                    <th><?php echo $lang->sno; ?></th>
                                    <th>PO Number</th>
                                    <th>Supplier <?php echo $lang->name; ?></th>
                                    <th><?php echo $lang->date; ?></th>
                                    <th><?php echo $lang->total; ?></th>
                                    <th>Discount</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th><?php echo $lang->action; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($suppliers))
                                {
                                $i = 1; 
                                foreach($suppliers as $supplier) {
                                
                                $nm = "";
                                // For purchase orders (invoice_type = 2), get supplier name
                                if($supplier['invoice_type'] == 2)
                                {
                                    if(isset($supparr[$supplier['customer_supplier_id']]))
                                        $nm = $supparr[$supplier['customer_supplier_id']];
                                }
                                else
                                {
                                    // This should not happen in purchase order list, but keeping for safety
                                    if(isset($custarr[$supplier['customer_supplier_id']]))
                                        $nm = $custarr[$supplier['customer_supplier_id']];
                                }
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <span class="po-number-highlight"><?php echo $supplier['po_no']; ?></span>
                                    </td>
                                    <td><?php echo $nm; ?></td>
                                    <td><?php echo date("d-m-Y",strtotime($supplier['date'])); ?></td>
                                    <td class="amount-display">RM <?php echo number_format($supplier['total'],2); ?></td>
                                    <td class="amount-display">RM <?php echo number_format($supplier['discount'],2); ?></td>
                                    <td class="amount-display">RM <?php echo number_format($supplier['grand_total'],2); ?></td>
                                    <td>
                                        <?php if($supplier["is_approved"] == 0){ ?>
                                            <span class="enhanced-status-badge status-pending">
                                                <i class="material-icons" style="font-size: 16px;">&#xE8B5;</i>
                                                Pending
                                            </span>
                                        <?php } else { ?>
                                            <span class="enhanced-status-badge status-approved">
                                                <i class="material-icons" style="font-size: 16px;">&#xE5CA;</i>
                                                Approved
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="enhanced-action-buttons">
                                            <a class="btn-enhanced btn-warning btn-sm" title="View" href="<?php echo base_url(); ?>/Purchase_order/view/<?php echo $supplier['id']; ?>">
                                                <i class="material-icons">&#xE417;</i>
                                            </a>
                                            
                                            <a class="btn-enhanced btn-secondary btn-sm" title="Print" href="<?php echo base_url(); ?>/Purchase_order/print/<?php echo $supplier['id']; ?>">
                                                <i class="material-icons">&#xE8AD;</i>
                                            </a>
                                            
                                            <?php if($supplier["is_approved"] == 0){ ?>
                                            <a class="btn-enhanced btn-primary btn-sm" title="Edit" href="<?php echo base_url(); ?>/Purchase_order/edit/<?php echo $supplier['id']; ?>">
                                                <i class="material-icons">&#xE3C9;</i>
                                            </a>
                                            <button class="btn-enhanced btn-success btn-sm" title="Convert to Invoice" onclick='approve("<?php echo $supplier['id']; ?>","<?php echo $supplier['invoice_type']; ?>")'>
                                                <i class="material-icons">&#xE876;</i>
                                            </button>
                                            <?php } else { ?>
                                            <span class="btn-enhanced btn-secondary btn-sm" title="Already Converted" style="opacity: 0.6; cursor: not-allowed;">
                                                <i class="material-icons">&#xE5CA;</i>
                                            </span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Approval Modal -->
    <div id="invoice_modal" class="enhanced-modal">
        <div class="enhanced-modal-content">
            <div class="enhanced-modal-header">
                <h3><i class="material-icons">&#xE876;</i> Convert to Invoice</h3>
                <span class="enhanced-close" onclick="closeModal()">&times;</span>
            </div>
            <div class="enhanced-modal-body">
                <div class="enhanced-alert alert-info">
                    <i class="material-icons">&#xE88E;</i>
                    This action will convert the purchase order to an invoice. Please provide the invoice details below.
                </div>
                
                <form id="approvalForm">
                    <input type="hidden" id="approval_po_id" name="id">
                    <input type="hidden" id="approval_type" name="type">
                    
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="invoice_no" name="invoice_no" class="enhanced-form-control" placeholder="Enter Invoice Number" required>
                            <label class="form-label">Invoice Number <span style="color: red;">*</span></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-line">
                            <input type="date" id="invoice_date" name="invoice_date" class="enhanced-form-control" required>
                            <label class="form-label">Invoice Date <span style="color: red;">*</span></label>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="button" class="btn-enhanced btn-secondary" onclick="closeModal()">
                            <i class="material-icons">&#xE14C;</i> Cancel
                        </button>
                        <button type="submit" class="btn-enhanced btn-success" id="approvalSubmitBtn" style="margin-left: 10px;">
                            <i class="material-icons">&#xE5CA;</i> Convert to Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
var _id = "";
var _type = "";

function approve(id, type) {
    if(!confirm('Are you sure you want to convert this order to invoice? This action cannot be undone.')) {
        return;
    }
    
    _id = id;
    _type = type;
    $("#approval_po_id").val(id);
    $("#approval_type").val(type);
    $("#invoice_modal").fadeIn();
}

function closeModal() {
    $("#invoice_modal").fadeOut();
    $("#approvalForm")[0].reset();
}

function setApprove() {
    var id = _id|0;
    var type = _type|0;
    var invoice_no = $("#invoice_no").val();
    var invoice_date = $("#invoice_date").val();
    
    if(_id > 0 && _type > 0 && invoice_no != "" && invoice_date != "") {
        // Show loading state
        $("#approvalSubmitBtn").html('<div class="spinner"></div> Processing...').prop('disabled', true);
        
        $.post("<?php echo base_url() ?>/purchase_order/approve", {
            id: id,
            type: type,
            invoice_no: invoice_no,
            invoice_date: invoice_date
        }, function(res) {
            $("#invoice_modal").fadeOut();
            try {
                res = (typeof res.status == "undefined" ? JSON.parse(res) : res);
                if(res.status) {
                    showAlert("Order successfully converted to invoice!", "success");
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(res.errMsg || "Failed to convert order", "danger");
                }
            } catch(e) {
                showAlert("Error processing request. Please try again.", "danger");
                console.error("Response parsing error:", e);
            }
        }).fail(function() {
            showAlert("Network error. Please check your connection and try again.", "danger");
        }).always(function() {
            $("#approvalSubmitBtn").html('<i class="material-icons">&#xE5CA;</i> Convert to Invoice').prop('disabled', false);
            closeModal();
        });
    } else {
        showAlert("Required fields must not be empty", "danger");
    }
}

function applyFilters() {
    var table = $('#datatables').DataTable();
    
    // Get filter values
    var status = $('#statusFilter').val();
    var supplier = $('#supplierFilter').val();
    var dateFrom = $('#dateFrom').val();
    var dateTo = $('#dateTo').val();

    // Clear all filters first
    table.columns().search('');
    
    // Apply status filter
    if (status) {
        if (status === 'pending') {
            table.column(7).search('Pending');
        } else if (status === 'approved') {
            table.column(7).search('Approved');
        }
    }
    
    // Apply supplier filter
    if (supplier) {
        // Get supplier name from the array
        <?php if(!empty($supparr)) { ?>
        var supplierNames = <?php echo json_encode($supparr); ?>;
        if (supplierNames[supplier]) {
            table.column(2).search(supplierNames[supplier]);
        }
        <?php } ?>
    }
    
    // Date filtering would need custom implementation
    // For now, just redraw the table
    table.draw();
}

function showAlert(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? '&#xE5CA;' : '&#xE000;';
    
    var alert = `
        <div class="enhanced-alert ${alertClass}" style="position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="material-icons">${icon}</i>
            <span>${message}</span>
            <span class="enhanced-close" onclick="this.parentElement.remove();" style="position: absolute; right: 15px; top: 15px; font-size: 20px; cursor: pointer;">&times;</span>
        </div>
    `;
    
    $('body').append(alert);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        $('.enhanced-alert').fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}

// Form submission handler
$('#approvalForm').on('submit', function(e) {
    e.preventDefault();
    setApprove();
});

// Close modal when clicking outside
$(window).on('click', function(e) {
    if (e.target.id === 'invoice_modal') {
        closeModal();
    }
});

// Handle escape key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

$(document).ready(function() {
    // Initialize DataTable with enhanced options
    $("#datatables").dataTable({
        "searching": true,
        "ordering": true,
        "order": [[ 0, "desc" ]], // Order by first column (ID) descending
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "responsive": true,
        "language": {
            "search": "Search orders:",
            "lengthMenu": "Show _MENU_ orders per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ orders",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            },
            "emptyTable": "No purchase orders found"
        },
        "columnDefs": [
            { "orderable": false, "targets": [8] }, // Disable sorting on action column
            { "className": "text-center", "targets": [0, 3, 7, 8] },
            { "className": "text-right", "targets": [4, 5, 6] }
        ]
    });

    // Set today's date for approval modal
    $('#invoice_date').val(new Date().toISOString().split('T')[0]);

    // Auto-hide session messages
    setTimeout(function() {
        $("#content_alert").fadeOut();
    }, 5000);
});

// Enhanced filter functionality
$('#statusFilter, #supplierFilter').on('change', function() {
    applyFilters();
});

// Date range filtering
$('#dateFrom, #dateTo').on('change', function() {
    var dateFrom = $('#dateFrom').val();
    var dateTo = $('#dateTo').val();
    
    if (dateFrom && dateTo) {
        // Custom date filtering logic would go here
        // For now, we'll just trigger the general filter
        applyFilters();
    }
});

// Export functionality (if needed)
function exportToExcel() {
    var table = $('#datatables').DataTable();
    var data = table.buttons.exportData({
        columns: ':not(:last-child)' // Exclude action column
    });
    
    // Excel export logic would go here
    console.log('Exporting to Excel...', data);
}

function exportToPDF() {
    // PDF export logic would go here
    console.log('Exporting to PDF...');
}

// Print functionality
function printTable() {
    var printContents = $('.enhanced-table-container').html();
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Purchase Orders List</title>
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h2>Purchase Orders List</h2>
            ${printContents}
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
}
</script>