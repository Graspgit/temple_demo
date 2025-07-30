<?php global $lang;?>
<?php $db = db_connect();?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
.modern-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
}

.modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 30px;
    border-radius: 12px 12px 0 0;
}

.btn-modern {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-add-modern {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    padding: 12px 24px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
    color: white;
}

.btn-view {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #8b4513;
}

.btn-edit {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #6c5ce7;
}

.btn-view:hover, .btn-edit:hover {
    transform: translateY(-1px);
    color: inherit;
}

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stats-label {
    opacity: 0.8;
    font-size: 0.9rem;
}

.balance-positive {
    color: #28a745;
    font-weight: 600;
}

.balance-negative {
    color: #dc3545;
    font-weight: 600;
}

.balance-zero {
    color: #6c757d;
    font-style: italic;
}

.customer-code {
    background: #e9ecef;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.85rem;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.contact-item {
    font-size: 0.85rem;
    color: #6c757d;
}

.alert-modern {
    border: none;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    position: relative;
}

.alert-success-modern {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.alert-error-modern {
    background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
    color: white;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0.8;
}

.close-btn:hover {
    opacity: 1;
}

.table-modern {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table-modern thead th {
    background: #f8f9fa;
    border: none;
    padding: 15px 12px;
    font-weight: 600;
    color: #495057;
}

.table-modern tbody td {
    padding: 12px;
    vertical-align: middle;
    border-color: #f8f9fa;
}

.table-modern tbody tr:hover {
    background-color: #f8f9ff;
}

.search-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?php echo $lang->account; ?> <small><?php echo $lang->customer; ?> / <b><?php echo $lang->list; ?></b></small></h2>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo count($suppliers); ?></div>
                    <div class="stats-label">Total Customers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="stats-number">
                        <?php 
                        $active_count = 0;
                        foreach($suppliers as $supplier) {
                            if(!empty($supplier['mobile_no']) || !empty($supplier['email_id'])) {
                                $active_count++;
                            }
                        }
                        echo $active_count;
                        ?>
                    </div>
                    <div class="stats-label">Active Customers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #8b4513;">
                    <div class="stats-number">
                        <?php 
                        // Get total debit balance
                        $total_dr = $db->query("
                            SELECT SUM(dr_amount) as total_dr 
                            FROM ac_year_ledger_balance alb
                            JOIN customer c ON c.ledger_id = alb.ledger_id
                            JOIN ac_year ay ON ay.id = alb.ac_year_id 
                            WHERE ay.status = 1
                        ")->getRow();
                        echo number_format($total_dr->total_dr ?? 0, 2);
                        ?>
                    </div>
                    <div class="stats-label">Total Receivables</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #6c5ce7;">
                    <div class="stats-number">
                        <?php 
                        // Get total credit balance
                        $total_cr = $db->query("
                            SELECT SUM(cr_amount) as total_cr 
                            FROM ac_year_ledger_balance alb
                            JOIN customer c ON c.ledger_id = alb.ledger_id
                            JOIN ac_year ay ON ay.id = alb.ac_year_id 
                            WHERE ay.status = 1
                        ")->getRow();
                        echo number_format($total_cr->total_cr ?? 0, 2);
                        ?>
                    </div>
                    <div class="stats-label">Total Advances</div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="modern-card">
                    <div class="modern-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-0">
                                    <i class="fas fa-users me-2"></i>Customer Management
                                </h3>
                                <p class="mb-0 opacity-75">Manage your customer database and opening balances</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?php echo base_url(); ?>/customer/add" class="btn btn-add-modern">
                                    <i class="fas fa-plus me-2"></i><?php echo $lang->add; ?> Customer
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Alert Messages -->
                        <?php if($_SESSION['succ'] != '') { ?>
                        <div class="alert-success-modern alert-modern">
                            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['succ']; ?>
                        </div>
                        <?php } ?>
                        
                        <?php if($_SESSION['fail'] != '') { ?>
                        <div class="alert-error-modern alert-modern">
                            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['fail']; ?>
                        </div>
                        <?php } ?>
                        
                        <!-- Search Section -->
                        <div class="search-section">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="globalSearch" class="form-control" placeholder="Search customers...">
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Search by name, code, phone, email, or CR number
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-modern" id="datatables">
                                <thead>
                                    <tr>
                                        <th><?php echo $lang->sno; ?></th>
                                        <th>Customer Details</th>
                                        <th>Contact Information</th>
                                        <th>CR Number</th>
                                        <th>Opening Balance</th>
                                        <th class="text-center"><?php echo $lang->action; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(!empty($suppliers)) {
                                        $i = 1; 
                                        foreach($suppliers as $supplier) { 
                                            // Get opening balance for this customer
                                            $balance_query = $db->query("
                                                SELECT dr_amount, cr_amount 
                                                FROM ac_year_ledger_balance alb
                                                JOIN ac_year ay ON ay.id = alb.ac_year_id 
                                                WHERE alb.ledger_id = ? AND ay.status = 1
                                            ", [$supplier['ledger_id']]);
                                            $balance = $balance_query->getRow();
                                            
                                            $dr_amount = $balance->dr_amount ?? 0;
                                            $cr_amount = $balance->cr_amount ?? 0;
                                            $net_balance = $dr_amount - $cr_amount;
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <div class="customer-details">
                                                <div class="fw-bold mb-1"><?php echo $supplier['customer_name']; ?></div>
                                                <?php if(!empty($supplier['customer_code'])) { ?>
                                                <span class="customer-code"><?php echo $supplier['customer_code']; ?></span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <?php if(!empty($supplier['mobile_no'])) { ?>
                                                <div class="contact-item">
                                                    <i class="fas fa-mobile-alt me-1"></i><?php echo $supplier['mobile_no']; ?>
                                                </div>
                                                <?php } ?>
                                                <?php if(!empty($supplier['email_id'])) { ?>
                                                <div class="contact-item">
                                                    <i class="fas fa-envelope me-1"></i><?php echo $supplier['email_id']; ?>
                                                </div>
                                                <?php } ?>
                                                <?php if(empty($supplier['mobile_no']) && empty($supplier['email_id'])) { ?>
                                                <span class="text-muted">No contact info</span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if(!empty($supplier['cr_no'])) { ?>
                                                <span class="customer-code"><?php echo $supplier['cr_no']; ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($net_balance > 0) { ?>
                                                <span class="balance-positive">
                                                    <i class="fas fa-arrow-up me-1"></i>₹<?php echo number_format($net_balance, 2); ?> Dr
                                                </span>
                                            <?php } elseif($net_balance < 0) { ?>
                                                <span class="balance-negative">
                                                    <i class="fas fa-arrow-down me-1"></i>₹<?php echo number_format(abs($net_balance), 2); ?> Cr
                                                </span>
                                            <?php } else { ?>
                                                <span class="balance-zero">₹0.00</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-modern btn-view btn-sm" 
                                                   title="View Customer" 
                                                   href="<?php echo base_url(); ?>/customer/view/<?php echo $supplier['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-modern btn-edit btn-sm" 
                                                   title="Edit Customer" 
                                                   href="<?php echo base_url(); ?>/customer/edit/<?php echo $supplier['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <h5>No Customers Found</h5>
                                                <p>Start by adding your first customer</p>
                                                <a href="<?php echo base_url(); ?>/customer/add" class="btn btn-add-modern">
                                                    <i class="fas fa-plus me-2"></i>Add Customer
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $("#datatables").DataTable({
        "searching": true,
        "ordering": true,
        "paging": true,
        "pageLength": 25,
        "responsive": true,
        "language": {
            "search": "",
            "searchPlaceholder": "Search in table...",
            "lengthMenu": "Show _MENU_ customers per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ customers",
            "infoEmpty": "No customers found",
            "infoFiltered": "(filtered from _MAX_ total customers)",
            "emptyTable": "No customers available",
            "zeroRecords": "No matching customers found"
        },
        "columnDefs": [
            { "orderable": false, "targets": [5] }, // Action column not sortable
            { "searchable": false, "targets": [0, 5] } // S.No and Action columns not searchable
        ],
        "order": [[1, "asc"]] // Sort by customer name by default
    });
    
    // Global search functionality
    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-modern').fadeOut('slow');
    }, 5000);
    
    // Tooltip initialization
    $('[title]').tooltip();
});
</script>