<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year'] ?? date('Y')); ?>
<style>
    /* Balance Sheet Styles - Matching your template */
    .balance-sheet-container {
        background: #fff;
        margin-top: -15px;
    }
    
    /* Card Styling to match your template */
    .card {
        background: #fff;
        margin-bottom: 30px;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        -webkit-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .card .header {
        color: #555;
        padding: 20px;
        position: relative;
        border-bottom: 1px solid rgba(204, 204, 204, 0.35);
    }
    
    .card .header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: normal;
        color: #111;
    }
    
    .card .body {
        padding: 20px;
    }
    
    /* Balance Sheet Table Styles */
    .balance-sheet-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #CCCCCC;
    }
    
    .balance-sheet-table thead th {
        background-color: #F44336;
        color: #FFFFFF;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
        padding: 12px;
        border: 1px solid #F44336;
    }
    
    /* Group Headers */
    .group-header {
        background-color: #e8f4f8;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .group-header:hover {
        background-color: #d1e7dd;
    }
    
    .group-header td {
        padding: 10px;
        font-size: 16px;
        color: #2c3e50;
        font-weight: bold;
        text-transform: uppercase;
        border: 1px solid #CCCCCC;
    }
    
    /* Subgroup Headers */
    .subgroup-header {
        background-color: #f0f7ff;
        cursor: pointer;
    }
    
    .subgroup-header:hover {
        background-color: #e0efff;
    }
    
    .subgroup-header td {
        padding: 8px 10px;
        font-weight: 600;
        color: #3498db;
        text-transform: uppercase;
        border: 1px solid #CCCCCC;
    }
    
    /* Ledger Rows */
    .ledger-row {
        transition: all 0.2s ease;
    }
    
    .ledger-row:hover {
        background-color: #f8f8f8;
    }
    
    .ledger-row td {
        padding: 6px 10px;
        border: 1px solid #ddd;
        font-size: 13px;
    }
    
    .ledger-row a {
        color: #333;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .ledger-row a:hover {
        color: #F44336;
        text-decoration: none;
    }
    
    /* Total Rows */
    .group-total, .subgroup-total {
        background-color: #f5f5f5;
        font-weight: bold;
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
    }
    
    .group-total td, .subgroup-total td {
        padding: 10px;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        border: 2px solid #333;
    }
    
    /* Amount Formatting */
    .amount {
        font-family: 'Courier New', monospace;
        text-align: right;
        white-space: nowrap;
    }
    
    .negative {
        color: #e74c3c;
    }
    
    /* Collapse Indicators */
    .collapse-indicator {
        margin-right: 8px;
        transition: transform 0.3s ease;
        color: #F44336;
        font-size: 12px;
    }
    
    .collapsed .collapse-indicator {
        transform: rotate(-90deg);
    }
    
    /* Filter Section */
    .filter-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    /* Export Buttons - Matching your template style */
    .export-section {
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #ddd;
        text-align: center;
    }
    
    .export-btn {
        padding: 8px 20px;
        margin: 0 5px;
        border-radius: 3px;
        border: none;
        color: #fff;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 12px;
    }
    
    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-print {
        background-color: #00BCD4;
    }
    
    .btn-print:hover {
        background-color: #00ACC1;
    }
    
    .btn-pdf {
        background-color: #F44336;
    }
    
    .btn-pdf:hover {
        background-color: #E53935;
    }
    
    .btn-excel {
        background-color: #4CAF50;
    }
    
    .btn-excel:hover {
        background-color: #45A049;
    }
    
    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 15px;
    }
    
    .search-box input {
        padding-left: 35px;
        border-radius: 3px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        border-color: #F44336;
        box-shadow: 0 0 5px rgba(244, 67, 54, 0.3);
    }
    
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }
    
    /* Financial Year Info */
    .financial-year-info {
        background-color: #FFF3E0;
        padding: 10px 20px;
        border-radius: 3px;
        border-left: 4px solid #FF9800;
        margin-bottom: 20px;
    }
    
    .financial-year-info p {
        margin: 0;
        color: #E65100;
        font-weight: 600;
        font-size: 14px;
    }
    
    /* Loading Spinner */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #F44336;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .balance-sheet-table {
            font-size: 12px;
        }
        
        .export-btn {
            margin: 3px;
            padding: 6px 12px;
            font-size: 11px;
        }
        
        .card .header h2 {
            font-size: 16px;
        }
    }
    
    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .balance-sheet-table {
            font-size: 10pt;
        }
        
        .group-header, .subgroup-header {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
        }
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #333;
        color: white;
        padding: 15px 20px;
        border-radius: 3px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.3);
        display: none;
        z-index: 10000;
    }
    
    .toast-notification.success {
        background: #4CAF50;
    }
    
    .toast-notification.error {
        background: #F44336;
    }
</style>

<section class="content balance-sheet-container">
    <div class="container-fluid">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
        </div>
        
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>
                                    <i class="material-icons" style="vertical-align: middle;">assessment</i>
                                    STATEMENT OF FINANCIAL POSITION
                                </h2>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if(!empty($check_financial_year)): ?>
                                <div class="financial-year-info">
                                    <p>
                                        <i class="material-icons" style="font-size: 16px; vertical-align: middle;">date_range</i>
                                        Financial Year: <?= date("d-m-Y", strtotime($check_financial_year['from_year_month']."-01")) ?> to 
                                        <?= date("d-m-Y", strtotime($check_financial_year['to_year_month']."-31")) ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="body">
                        <?php if(!empty($_SESSION['succ'])): ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="suc-alert">
                                    <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                    <p><?php echo $_SESSION['succ']; unset($_SESSION['succ']); ?></p> 
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($_SESSION['fail'])): ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="alert">
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                    <p><?php echo $_SESSION['fail']; unset($_SESSION['fail']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Filter Section -->
                        <form action="<?= base_url() ?>/balance_sheet/balancesheet_full" method="post" id="filterForm">
                            <div class="filter-section">
                                <div class="row clearfix">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="form-label">As of Date</label>
                                                <input type="date" name="tdate" id="tdate" class="form-control" 
                                                       value="<?= $tdate ?>" max="<?= $booking_calendar_range_year ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect">
                                            <i class="material-icons">refresh</i> GENERATE REPORT
                                        </button>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="search-box pull-right">
                                            <i class="material-icons">search</i>
                                            <input type="text" class="form-control" id="searchInput" 
                                                   placeholder="Search accounts..." style="width: 300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Balance Sheet Table -->
                        <div class="table-responsive">
                            <table class="balance-sheet-table table table-bordered" id="balanceSheetTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60%;">Account Name</th>
                                        <th style="width: 20%; text-align: right;">Current Year</th>
                                        <th style="width: 20%; text-align: right;">Previous Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($list as $row): ?>
                                        <?= $row ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Export Section -->
                        <div class="export-section no-print">
                            <h5 style="margin-bottom: 20px;">Export Options</h5>
                            <button class="btn export-btn btn-print waves-effect" onclick="printReport()">
                                <i class="material-icons" style="vertical-align: middle;">print</i> PRINT
                            </button>
                            <button class="btn export-btn btn-pdf waves-effect" onclick="exportPDF()">
                                <i class="material-icons" style="vertical-align: middle;">picture_as_pdf</i> PDF
                            </button>
                            <button class="btn export-btn btn-excel waves-effect" onclick="exportExcel()">
                                <i class="material-icons" style="vertical-align: middle;">grid_on</i> EXCEL
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toast Notification -->
<div class="toast-notification" id="toastNotification"></div>

<!-- Hidden Forms for Export -->
<form style="display: none;" target="_blank" id="printForm" 
      action="<?= base_url() ?>/balance_sheet/print_balancesheet_full" method="post">
    <input type="hidden" name="tpdate" value="<?= $tdate ?>">
</form>

<form style="display: none;" target="_blank" id="excelForm" 
      action="<?= base_url() ?>/balance_sheet/excel_balancesheet_full" method="post">
    <input type="hidden" name="tpdate" value="<?= $tdate ?>">
</form>

<script>
$(document).ready(function() {
    initializeBalanceSheet();
});

function initializeBalanceSheet() {
    // Add collapse functionality to groups
    addCollapseFeature();
    
    // Initialize search
    initializeSearch();
    
    // Format amounts
    formatAmounts();
}

// Add collapse/expand feature to groups
function addCollapseFeature() {
    $('.group-header, .subgroup-header').each(function() {
        var $header = $(this);
        var $icon = $('<i class="material-icons collapse-indicator">keyboard_arrow_down</i>');
        $header.find('td:first').prepend($icon);
        
        $header.click(function() {
            var $this = $(this);
            $this.toggleClass('collapsed');
            
            var $next = $this.next('tr');
            var level = $this.hasClass('group-header') ? 'group' : 'subgroup';
            
            while ($next.length && !$next.hasClass('group-header') && !$next.hasClass('group-total')) {
                if (level === 'group' || !$next.hasClass('subgroup-header')) {
                    $next.toggle();
                }
                $next = $next.next('tr');
            }
        });
    });
}

// Initialize search functionality
function initializeSearch() {
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        
        $('.balance-sheet-table tbody tr').each(function() {
            var $row = $(this);
            var text = $row.text().toLowerCase();
            
            if (text.indexOf(value) > -1) {
                $row.show();
                // Show parent groups if child matches
                var $parent = $row.prevAll('.group-header, .subgroup-header').first();
                while ($parent.length) {
                    $parent.show();
                    $parent = $parent.prevAll('.group-header').first();
                }
            } else if (!$row.hasClass('group-header') && !$row.hasClass('subgroup-header')) {
                $row.hide();
            }
        });
        
        // Hide empty groups
        $('.group-header, .subgroup-header').each(function() {
            var $header = $(this);
            var hasVisibleChildren = false;
            var $next = $header.next('tr');
            
            while ($next.length && !$next.hasClass('group-header') && !$next.hasClass('group-total')) {
                if ($next.is(':visible') && !$next.hasClass('subgroup-header')) {
                    hasVisibleChildren = true;
                    break;
                }
                $next = $next.next('tr');
            }
            
            if (!hasVisibleChildren && value !== '') {
                $header.hide();
            }
        });
    });
}

// Format amount cells
function formatAmounts() {
    $('td:contains("("), td:contains(")")').each(function() {
        var $cell = $(this);
        var text = $cell.text().trim();
        
        if (text.includes('(') && text.includes(')')) {
            $cell.addClass('negative');
        }
        
        // Ensure proper alignment for amount columns
        if ($cell.index() > 0 && !$cell.parent().hasClass('group-header') && 
            !$cell.parent().hasClass('subgroup-header')) {
            $cell.addClass('amount');
        }
    });
}

// Export functions
function showLoading() {
    $('#loadingOverlay').css('display', 'flex');
}

function hideLoading() {
    $('#loadingOverlay').hide();
}

function printReport() {
    var tdate = $('#tdate').val();
    window.open('<?= base_url() ?>/balance_sheet/print_view?tdate=' + tdate, '_blank');
}

function exportPDF() {
    showLoading();
    var tdate = $('#tdate').val();
    window.location.href = '<?= base_url() ?>/balance_sheet/pdf_balancesheet_full?tpdate=' + tdate;
    setTimeout(hideLoading, 2000);
}

function exportExcel() {
    showLoading();
    $('#excelForm').submit();
    setTimeout(hideLoading, 2000);
}

function showToast(message, type) {
    var $toast = $('#toastNotification');
    $toast.text(message)
        .removeClass('success error')
        .addClass(type)
        .fadeIn(300);
    
    setTimeout(function() {
        $toast.fadeOut(300);
    }, 3000);
}

// Form submission with loading
$('#filterForm').on('submit', function(e) {
    showLoading();
});

// Add waves effect to buttons
Waves.attach('.btn', ['waves-float']);
Waves.init();
</script>