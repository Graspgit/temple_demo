<?php global $lang;?>
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

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.summary-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid;
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
}

.summary-card.receivables {
    border-left-color: #28a745;
}

.summary-card.payables {
    border-left-color: #dc3545;
}

.summary-card.total-customers {
    border-left-color: #007bff;
}

.summary-card.zero-balance {
    border-left-color: #6c757d;
}

.card-value {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.card-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
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
    padding: 3px 8px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.8rem;
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

.btn-modern {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-export {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    margin-right: 10px;
}

.btn-export:hover {
    transform: translateY(-1px);
    color: white;
}

.btn-print {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #8b4513;
}

.btn-print:hover {
    transform: translateY(-1px);
    color: #8b4513;
}

.filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.aging-indicator {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.aging-current {
    background: #d4edda;
    color: #155724;
}

.aging-30 {
    background: #fff3cd;
    color: #856404;
}

.aging-60 {
    background: #f8d7da;
    color: #721c24;
}

.aging-90 {
    background: #f5c6cb;
    color: #495057;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .modern-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .table-modern {
        box-shadow: none;
    }
}
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Account <small>Customer / <b>Balance Report</b></small></h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="modern-card">
                    <div class="modern-header no-print">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Customer Balance Report
                                </h3>
                                <p class="mb-0 opacity-75">Overview of customer receivables and payables</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?php echo base_url(); ?>/customer/exportCSV" class="btn btn-export btn-modern">
                                    <i class="fas fa-download me-2"></i>Export CSV
                                </a>
                                <button onclick="window.print()" class="btn btn-print btn-modern">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Summary Cards -->
                        <div class="summary-cards">
                            <?php
                            $total_customers = count($customers);
                            $total_receivables = 0;
                            $total_payables = 0;
                            $zero_balance_count = 0;
                            
                            foreach($customers as $customer) {
                                if($customer['net_balance'] > 0) {
                                    $total_receivables += $customer['net_balance'];
                                } elseif($customer['net_balance'] < 0) {
                                    $total_payables += abs($customer['net_balance']);
                                } else {
                                    $zero_balance_count++;
                                }
                            }
                            ?>
                            
                            <div class="summary-card total-customers">
                                <div class="card-value"><?php echo $total_customers; ?></div>
                                <div class="card-label">Total Customers</div>
                            </div>
                            
                            <div class="summary-card receivables">
                                <div class="card-value">₹<?php echo number_format($total_receivables, 2); ?></div>
                                <div class="card-label">Total Receivables</div>
                            </div>
                            
                            <div class="summary-card payables">
                                <div class="card-value">₹<?php echo number_format($total_payables, 2); ?></div>
                                <div class="card-label">Total Payables</div>
                            </div>
                            
                            <div class="summary-card zero-balance">
                                <div class="card-value"><?php echo $zero_balance_count; ?></div>
                                <div class="card-label">Zero Balance</div>
                            </div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="filter-section no-print">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="balanceSearch" class="form-control" placeholder="Search customers...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select id="balanceFilter" class="form-select">
                                        <option value="">All Balances</option>
                                        <option value="positive">Receivables Only</option>
                                        <option value="negative">Payables Only</option>
                                        <option value="zero">Zero Balance Only</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="amountFilter" class="form-select">
                                        <option value="">All Amounts</option>
                                        <option value="0-1000">₹0 - ₹1,000</option>
                                        <option value="1000-5000">₹1,000 - ₹5,000</option>
                                        <option value="5000-10000">₹5,000 - ₹10,000</option>
                                        <option value="10000-25000">₹10,000 - ₹25,000</option>
                                        <option value="25000+">Above ₹25,000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Balance Report Table -->
                        <div class="table-responsive">
                            <table class="table table-modern" id="balanceTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Customer Details</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th class="text-end">Debit Amount</th>
                                        <th class="text-end">Credit Amount</th>
                                        <th class="text-end">Net Balance</th>
                                        <th class="text-center">Aging</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(!empty($customers)) {
                                        $i = 1;
                                        foreach($customers as $customer) { 
                                            $aging_class = 'aging-current';
                                            $aging_text = 'Current';
                                            
                                            // Simple aging calculation based on creation date
                                            $created_date = new DateTime($customer['created_at']);
                                            $current_date = new DateTime();
                                            $days_old = $created_date->diff($current_date)->days;
                                            
                                            if($days_old > 90) {
                                                $aging_class = 'aging-90';
                                                $aging_text = '90+ Days';
                                            } elseif($days_old > 60) {
                                                $aging_class = 'aging-60';
                                                $aging_text = '60-90 Days';
                                            } elseif($days_old > 30) {
                                                $aging_class = 'aging-30';
                                                $aging_text = '30-60 Days';
                                            }
                                    ?>
                                    <tr data-balance="<?php echo $customer['net_balance']; ?>" data-amount="<?php echo abs($customer['net_balance']); ?>">
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <div class="customer-details">
                                                <div class="fw-bold mb-1"><?php echo $customer['customer_name']; ?></div>
                                                <span class="customer-code"><?php echo $customer['customer_code']; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <?php if(!empty($customer['mobile_no'])) { ?>
                                                <div style="font-size: 0.85rem;">
                                                    <i class="fas fa-mobile-alt me-1"></i><?php echo $customer['mobile_no']; ?>
                                                </div>
                                                <?php } ?>
                                                <?php if(!empty($customer['email_id'])) { ?>
                                                <div style="font-size: 0.85rem; color: #6c757d;">
                                                    <i class="fas fa-envelope me-1"></i><?php echo $customer['email_id']; ?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem;">
                                                <?php if(!empty($customer['city'])) { ?>
                                                <div><?php echo $customer['city']; ?></div>
                                                <?php } ?>
                                                <?php if(!empty($customer['state'])) { ?>
                                                <div class="text-muted"><?php echo $customer['state']; ?></div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <?php if($customer['dr_amount'] > 0) { ?>
                                                <span class="balance-positive">₹<?php echo number_format($customer['dr_amount'], 2); ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if($customer['cr_amount'] > 0) { ?>
                                                <span class="balance-negative">₹<?php echo number_format($customer['cr_amount'], 2); ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if($customer['net_balance'] > 0) { ?>
                                                <span class="balance-positive">
                                                    <i class="fas fa-arrow-up me-1"></i>₹<?php echo number_format($customer['net_balance'], 2); ?>
                                                </span>
                                            <?php } elseif($customer['net_balance'] < 0) { ?>
                                                <span class="balance-negative">
                                                    <i class="fas fa-arrow-down me-1"></i>₹<?php echo number_format(abs($customer['net_balance']), 2); ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="balance-zero">₹0.00</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="aging-indicator <?php echo $aging_class; ?>">
                                                <?php echo $aging_text; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                                <h5>No Customer Data Available</h5>
                                                <p>Add customers to see balance reports</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f8f9fa; font-weight: bold;">
                                        <td colspan="4" class="text-end">TOTALS:</td>
                                        <td class="text-end balance-positive">₹<?php echo number_format($total_receivables, 2); ?></td>
                                        <td class="text-end balance-negative">₹<?php echo number_format($total_payables, 2); ?></td>
                                        <td class="text-end">
                                            <?php 
                                            $net_total = $total_receivables - $total_payables;
                                            if($net_total > 0) {
                                                echo '<span class="balance-positive">₹' . number_format($net_total, 2) . '</span>';
                                            } elseif($net_total < 0) {
                                                echo '<span class="balance-negative">₹' . number_format(abs($net_total), 2) . '</span>';
                                            } else {
                                                echo '<span class="balance-zero">₹0.00</span>';
                                            }
                                            ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Report Footer -->
                        <div class="row mt-4 no-print">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Report generated on <?php echo date('d M Y, h:i A'); ?>
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    Total <?php echo $total_customers; ?> customers | 
                                    Net Outstanding: ₹<?php echo number_format($total_receivables - $total_payables, 2); ?>
                                </small>
                            </div>
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
    var table = $("#balanceTable").DataTable({
        "searching": true,
        "ordering": true,
        "paging": true,
        "pageLength": 50,
        "responsive": true,
        "footerCallback": function() {
            // Keep footer totals visible
        },
        "language": {
            "search": "",
            "searchPlaceholder": "Search in table...",
            "lengthMenu": "Show _MENU_ entries per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ customers",
            "infoEmpty": "No customers found",
            "infoFiltered": "(filtered from _MAX_ total customers)"
        },
        "columnDefs": [
            { "orderable": false, "targets": [7] }, // Aging column not sortable
            { "searchable": false, "targets": [0, 7] } // S.No and Aging columns not searchable
        ],
        "order": [[6, "desc"]] // Sort by net balance descending
    });
    
    // Global search
    $('#balanceSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Balance type filter
    $('#balanceFilter').on('change', function() {
        var filterValue = this.value;
        
        if(filterValue === 'positive') {
            table.column(6).search('arrow-up', true, false).draw();
        } else if(filterValue === 'negative') {
            table.column(6).search('arrow-down', true, false).draw();
        } else if(filterValue === 'zero') {
            table.column(6).search('₹0.00', true, false).draw();
        } else {
            table.column(6).search('').draw();
        }
    });
    
    // Amount range filter
    $('#amountFilter').on('change', function() {
        var range = this.value;
        
        // Reset search
        table.draw();
        
        if(range) {
            var rows = table.rows().nodes();
            $(rows).each(function() {
                var amount = parseFloat($(this).data('amount')) || 0;
                var show = false;
                
                switch(range) {
                    case '0-1000':
                        show = amount >= 0 && amount <= 1000;
                        break;
                    case '1000-5000':
                        show = amount > 1000 && amount <= 5000;
                        break;
                    case '5000-10000':
                        show = amount > 5000 && amount <= 10000;
                        break;
                    case '10000-25000':
                        show = amount > 10000 && amount <= 25000;
                        break;
                    case '25000+':
                        show = amount > 25000;
                        break;
                    default:
                        show = true;
                }
                
                $(this).toggle(show);
            });
        }
    });
    
    // Print styling
    window.addEventListener('beforeprint', function() {
        $('body').addClass('printing');
    });
    
    window.addEventListener('afterprint', function() {
        $('body').removeClass('printing');
    });
});
</script>