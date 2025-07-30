<?php 
global $lang;
$db = db_connect();

// Get current active accounting year for opening balances
$current_ac_year = $db->table('ac_year')->where('status', 1)->get()->getRowArray();
?>

<style>
.supplier-list-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.list-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 0 auto;
    max-width: 1400px;
}

.list-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.list-title {
    margin: 0;
    font-size: 2.2rem;
    font-weight: 300;
}

.list-subtitle {
    opacity: 0.9;
    font-size: 1rem;
    margin-top: 5px;
}

.header-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-add {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
}

.btn-add:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.btn-action {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    margin: 0 2px;
}

.btn-view {
    background: #4299e1;
    color: white;
}

.btn-edit {
    background: #48bb78;
    color: white;
}

.btn-view:hover {
    background: #3182ce;
    transform: translateY(-1px);
}

.btn-edit:hover {
    background: #38a169;
    transform: translateY(-1px);
}

.list-body {
    padding: 30px;
}

.filters-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
}

.filters-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
}

.search-box {
    max-width: 400px;
}

.search-box input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.table {
    width: 100%;
    margin: 0;
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 18px 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border: none;
}

.table tbody td {
    padding: 15px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.001);
    transition: all 0.2s ease;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.supplier-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
}

.supplier-code {
    background: #e2e8f0;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #4a5568;
}

.contact-info {
    font-size: 0.9rem;
    color: #64748b;
}

.balance-info {
    text-align: right;
    font-weight: 600;
}

.balance-credit {
    color: #38a169;
}

.balance-debit {
    color: #e53e3e;
}

.balance-zero {
    color: #64748b;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #c6f6d5;
    color: #22543d;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #f0fff4;
    color: #22543d;
    border: 1px solid #9ae6b4;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border: 1px solid #feb2b2;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.empty-state-icon {
    font-size: 4rem;
    opacity: 0.3;
    margin-bottom: 20px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-label {
    color: #64748b;
    font-size: 0.9rem;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .list-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .table {
        min-width: 800px;
    }
    
    .stats-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="supplier-list-container">
    <div class="container-fluid">
        <div class="list-card">
            <div class="list-header">
                <div>
                    <h1 class="list-title">
                        <i class="material-icons" style="font-size: 2.2rem; vertical-align: middle; margin-right: 15px;">business</i>
                        Suppliers Management
                    </h1>
                    <div class="list-subtitle">Manage your supplier accounts and opening balances</div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo base_url(); ?>/supplier/add" class="btn btn-add">
                        <i class="material-icons">add</i>
                        Add New Supplier
                    </a>
                </div>
            </div>

            <div class="list-body">
                <?php if(session('succ')): ?>
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        <span><?php echo session('succ'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if(session('fail')): ?>
                    <div class="alert alert-danger">
                        <i class="material-icons">error</i>
                        <span><?php echo session('fail'); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <?php 
                $total_suppliers = count($suppliers);
                $suppliers_with_balance = 0;
                $total_credit_balance = 0;
                $total_debit_balance = 0;

                foreach($suppliers as $supplier) {
                    if($current_ac_year && $supplier['ledger_id']) {
                        $balance = $db->table('ac_year_ledger_balance')
                            ->where('ledger_id', $supplier['ledger_id'])
                            ->where('ac_year_id', $current_ac_year['id'])
                            ->get()->getRowArray();
                        
                        if($balance) {
                            if($balance['cr_amount'] > 0 || $balance['dr_amount'] > 0) {
                                $suppliers_with_balance++;
                            }
                            $total_credit_balance += $balance['cr_amount'];
                            $total_debit_balance += $balance['dr_amount'];
                        }
                    }
                }
                ?>

                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_suppliers; ?></div>
                        <div class="stat-label">Total Suppliers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $suppliers_with_balance; ?></div>
                        <div class="stat-label">With Opening Balance</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" style="color: #38a169;">RM <?php echo number_format($total_credit_balance, 2); ?></div>
                        <div class="stat-label">Total Credit Balance</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" style="color: #e53e3e;">RM <?php echo number_format($total_debit_balance, 2); ?></div>
                        <div class="stat-label">Total Debit Balance</div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <div class="filters-title">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">search</i>
                        Search & Filter
                    </div>
                    <div class="search-box">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search suppliers by name, code, email, or contact..." 
                               autocomplete="off">
                    </div>
                </div>

                <!-- Suppliers Table -->
                <div class="table-container">
                    <?php if(!empty($suppliers)): ?>
                        <table class="table" id="suppliersTable">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Supplier Details</th>
                                    <th>Code</th>
                                    <th>Contact Info</th>
                                    <th>Opening Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                foreach($suppliers as $supplier): 
                                    // Get opening balance for current year
                                    $opening_balance = null;
                                    if($current_ac_year && $supplier['ledger_id']) {
                                        $opening_balance = $db->table('ac_year_ledger_balance')
                                            ->where('ledger_id', $supplier['ledger_id'])
                                            ->where('ac_year_id', $current_ac_year['id'])
                                            ->get()->getRowArray();
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <div class="supplier-name"><?php echo $supplier['supplier_name']; ?></div>
                                        <div class="contact-info">
                                            <?php if($supplier['contact']): ?>
                                                <i class="material-icons" style="font-size: 1rem; vertical-align: middle;">person</i>
                                                <?php echo $supplier['contact']; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($supplier['supplier_code']): ?>
                                            <span class="supplier-code"><?php echo $supplier['supplier_code']; ?></span>
                                        <?php else: ?>
                                            <span style="color: #cbd5e0;">--</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <?php if($supplier['mobile_no']): ?>
                                                <div>
                                                    <i class="material-icons" style="font-size: 1rem; vertical-align: middle;">phone</i>
                                                    <?php echo $supplier['mobile_no']; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($supplier['email_id']): ?>
                                                <div>
                                                    <i class="material-icons" style="font-size: 1rem; vertical-align: middle;">email</i>
                                                    <?php echo $supplier['email_id']; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="balance-info">
                                        <?php if($opening_balance): ?>
                                            <?php if($opening_balance['cr_amount'] > 0): ?>
                                                <span class="balance-credit">
                                                    RM <?php echo number_format($opening_balance['cr_amount'], 2); ?> CR
                                                </span>
                                            <?php elseif($opening_balance['dr_amount'] > 0): ?>
                                                <span class="balance-debit">
                                                    RM <?php echo number_format($opening_balance['dr_amount'], 2); ?> DR
                                                </span>
                                            <?php else: ?>
                                                <span class="balance-zero">RM 0.00</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="balance-zero">RM 0.00</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active">Active</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-action btn-view" 
                                           title="View Details" 
                                           href="<?php echo base_url(); ?>/supplier/view/<?php echo $supplier['id']; ?>">
                                            <i class="material-icons" style="font-size: 1rem;">visibility</i>
                                        </a>
                                        <a class="btn btn-action btn-edit" 
                                           title="Edit Supplier" 
                                           href="<?php echo base_url(); ?>/supplier/edit/<?php echo $supplier['id']; ?>">
                                            <i class="material-icons" style="font-size: 1rem;">edit</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="material-icons" style="font-size: 4rem;">business</i>
                            </div>
                            <h3 style="margin: 0 0 10px 0; color: #4a5568;">No Suppliers Found</h3>
                            <p style="margin: 0 0 20px 0;">Start by adding your first supplier to the system.</p>
                            <a href="<?php echo base_url(); ?>/supplier/add" class="btn btn-primary">
                                <i class="material-icons">add</i>
                                Add First Supplier
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom styling
    if ($.fn.DataTable && $('#suppliersTable').length) {
        $('#suppliersTable').DataTable({
            "searching": false, // We'll use custom search
            "lengthChange": true,
            "pageLength": 25,
            "info": true,
            "ordering": true,
            "order": [[0, "asc"]],
            "language": {
                "lengthMenu": "Show _MENU_ suppliers per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ suppliers",
                "infoEmpty": "No suppliers to display",
                "infoFiltered": "(filtered from _MAX_ total suppliers)",
                "emptyTable": "No suppliers found",
                "zeroRecords": "No matching suppliers found"
            },
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Actions column not sortable
            ]
        });
    }

    // Custom search functionality
    $('#searchInput').on('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = $('#suppliersTable');
        const rows = table.find('tbody tr');
        
        rows.each(function() {
            const row = $(this);
            const text = row.text().toLowerCase();
            
            if (text.includes(searchTerm)) {
                row.show();
            } else {
                row.hide();
            }
        });
        
        // Update "no results" message
        const visibleRows = rows.filter(':visible').length;
        if (visibleRows === 0 && searchTerm !== '') {
            if (!table.find('.no-results').length) {
                table.find('tbody').append(
                    '<tr class="no-results"><td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">' +
                    '<i class="material-icons" style="font-size: 2rem; opacity: 0.5;">search_off</i><br>' +
                    'No suppliers match your search criteria</td></tr>'
                );
            }
        } else {
            table.find('.no-results').remove();
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Add hover effects
    $('.btn-action').hover(
        function() {
            $(this).css('transform', 'scale(1.1)');
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
});
</script>