<?php global $lang; ?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>

<style>
    /* Reset and base styles */
    .trial-balance-container * {
        box-sizing: border-box;
    }
    
    .trial-balance-container {
        background: #f5f5f5;
        min-height: calc(100vh - 200px);
    }
    
    .tb-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .tb-filters {
        background: white;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .tb-main {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }
    
    .tb-table-wrapper {
        overflow-x: auto;
        overflow-y: auto;
    }
    
    .tb-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        background: white;
    }
    
    .tb-table thead {
        background: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    
    .tb-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    
    .tb-table th:first-child,
    .tb-table td:first-child {
        width: 15%;
    }
    
    .tb-table th:nth-child(2),
    .tb-table td:nth-child(2) {
        width: 55%;
    }
    
    .tb-table th:nth-child(3),
    .tb-table th:nth-child(4),
    .tb-table td:nth-child(3),
    .tb-table td:nth-child(4) {
        width: 15%;
        text-align: right;
    }
    
    .tb-table tbody tr {
        display: table-row;
        width: 100%;
    }
    
    .tb-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .tb-table tr {
        width: 100%;
    }
    
    .tb-group {
        background: #f8f9fa;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .tb-group:hover {
        background: #e9ecef;
    }
    
    .tb-group .group-description-cell {
        cursor: pointer;
        position: relative;
    }
    
    .tb-group .group-description-cell:hover {
        background: rgba(0, 0, 0, 0.02);
    }
    
    .collapse-icon {
        font-size: 20px;
        transition: transform 0.3s ease;
        color: #666;
    }
    
    .collapse-icon:hover {
        color: #333;
    }
    
    .tb-group-level-1 {
        font-size: 14px;
    }
    
    .tb-group-level-2 {
        font-size: 13px;
    }
    
    .tb-ledger a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .tb-ledger a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    
    .tb-ledger {
        cursor: default;
        transition: background 0.3s;
        font-size: 13px;
    }
    
    .tb-ledger:hover {
        background: #f8f9fa;
    }
    
    .tb-amount {
        text-align: right;
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }
    
    .tb-total {
        background: #343a40;
        color: white;
        font-weight: 700;
    }
    
    .tb-total td {
        padding: 15px;
        border: none;
    }
    
    .tb-search {
        position: relative;
    }
    
    .tb-search input {
        padding-left: 40px;
        border-radius: 20px;
        border: 1px solid #ddd;
    }
    
    .tb-search i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }
    
    .tb-loader {
        display: none;
        text-align: center;
        padding: 50px;
    }
    
    .tb-loader.active {
        display: block;
    }
    
    .tb-empty {
        text-align: center;
        padding: 50px;
        color: #666;
    }
    
    .collapse-icon {
        margin-right: 10px;
        transition: transform 0.3s;
    }
    
    .collapsed .collapse-icon {
        transform: rotate(-90deg);
    }
    
    .tb-stats {
        display: flex;
        justify-content: space-around;
        margin-bottom: 20px;
    }
    
    .tb-stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex: 1;
        margin: 0 10px;
        text-align: center;
    }
    
    .tb-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    
    .tb-stat-label {
        color: #666;
        margin-top: 5px;
    }
    
    .btn-action {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    @media (max-width: 768px) {
        .tb-stats {
            flex-direction: column;
        }
        
        .tb-stat-card {
            margin: 10px 0;
        }
        
        .tb-table {
            font-size: 12px;
        }
        
        .tb-table th,
        .tb-table td {
            padding: 8px 5px;
        }
        
        .tb-ledger {
            font-size: 12px;
        }
        
        .tb-filters .col-md-3 {
            margin-bottom: 15px;
        }
        
        .btn-action {
            margin-bottom: 5px;
        }
        
        .tb-table-wrapper {
            max-height: calc(100vh - 500px);
        }
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loading-overlay.active {
        display: flex;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<section class="content">
    <div class="container-fluid trial-balance-container">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="tb-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 style="margin: 0; font-size: 28px;">
                                <i class="material-icons" style="vertical-align: middle;">account_balance</i>
                                Trial Balance
                            </h2>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if(count($check_financial_year) > 0): ?>
                                <?php
                                $from_date = date("d-m-Y", strtotime($check_financial_year[0]['from_year_month']."-01"));
                                $to_date = date("d-m-Y", strtotime($check_financial_year[0]['to_year_month']."-31"));
                                ?>
                                <p style="margin: 0; font-size: 14px;">
                                    Financial Year: <?php echo $from_date; ?> to <?php echo $to_date; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="tb-stats" id="statsContainer" style="display: none;">
                    <div class="tb-stat-card">
                        <div class="tb-stat-value" id="totalDebit">0.00</div>
                        <div class="tb-stat-label">Total Debit</div>
                    </div>
                    <div class="tb-stat-card">
                        <div class="tb-stat-value" id="totalCredit">0.00</div>
                        <div class="tb-stat-label">Total Credit</div>
                    </div>
                    <div class="tb-stat-card">
                        <div class="tb-stat-value" id="difference">0.00</div>
                        <div class="tb-stat-label">Difference</div>
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="tb-filters">
                    <form id="trialBalanceForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" name="fdate" id="fdate" class="form-control" 
                                           value="<?= $sdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" name="tdate" id="tdate" class="form-control" 
                                           value="<?= $tdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="material-icons">refresh</i> Load Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-success btn-action" onclick="exportExcel()">
                                    <i class="material-icons">file_download</i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-action" onclick="exportPDF()">
                                    <i class="material-icons">picture_as_pdf</i> PDF
                                </button>
                                <button type="button" class="btn btn-primary btn-action" onclick="printReport()">
                                    <i class="material-icons">print</i> Print
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Main Table -->
                <div class="tb-main">
                    <div class="tb-loader" id="loader">
                        <div class="spinner"></div>
                        <p style="margin-top: 20px;">Loading trial balance data...</p>
                    </div>
                    
                    <div id="tableContainer" style="display: none;">
                        <div class="tb-table-wrapper">
                            <table class="tb-table" id="trialBalanceTable">
                                <colgroup>
                                    <col style="width: 15%;">
                                    <col style="width: 55%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>A/C No</th>
                                        <th>Description</th>
                                        <th style="text-align: right;">Debit</th>
                                        <th style="text-align: right;">Credit</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Dynamic content -->
                                </tbody>
                                <tfoot>
                                    <tr class="tb-total">
                                        <td colspan="2" style="text-align: right;">Total</td>
                                        <td class="tb-amount" id="footerDebit">0.00</td>
                                        <td class="tb-amount" id="footerCredit">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tb-empty" id="emptyState" style="display: none;">
                        <i class="material-icons" style="font-size: 48px; color: #ddd;">inbox</i>
                        <p>No data found for the selected period</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hidden forms for export -->
<form id="exportForm" method="POST" target="_blank" style="display: none;">
    <input type="hidden" name="fdate" id="export_fdate">
    <input type="hidden" name="tdate" id="export_tdate">
    <input type="hidden" name="fund_id" id="export_fund_id">
</form>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<script>
// Global state management - using in-memory storage only
var trialBalanceData = [];
var expandedGroups = [];
var searchTerm = '';
var searchTimeout;

// Initialize on page load
$(document).ready(function() {
    loadTrialBalance();
    
    // Search functionality with debounce
    $('#searchInput').on('input', function() {
        var searchValue = $(this).val();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchTerm = searchValue;
            if (trialBalanceData.length > 0) {
                if (searchTerm) {
                    filterTableBySearch(searchTerm);
                } else {
                    renderTable(trialBalanceData);
                }
            }
        }, 300);
    });
    
    // Form submission
    $('#trialBalanceForm').on('submit', function(e) {
        e.preventDefault();
        loadTrialBalance();
    });
});

function loadTrialBalance() {
    var $loader = $('#loader');
    var $tableContainer = $('#tableContainer');
    var $emptyState = $('#emptyState');
    var $statsContainer = $('#statsContainer');
    
    // Clear state
    searchTerm = '';
    $('#searchInput').val('');
    expandedGroups = [];
    
    $loader.addClass('active');
    $tableContainer.hide();
    $emptyState.hide();
    $statsContainer.hide();
    
    var formData = {
        fdate: $('#fdate').val(),
        tdate: $('#tdate').val()
    };
    
    $.ajax({
        url: '<?php echo base_url(); ?>/accountreport/get_trial_balance_data',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(data) {
            $loader.removeClass('active');
            
            if (data.error) {
                alert(data.error);
                return;
            }
            
            if (data.data && data.data.length > 0) {
                trialBalanceData = data.data;
                
                // Debug: Check if groups have proper IDs
                console.log('Sample group data:', data.data[0]);
                
                renderTable(data.data);
                updateStats(data.totals);
                $tableContainer.show();
                $statsContainer.show();
            } else {
                $emptyState.show();
            }
        },
        error: function(xhr, status, error) {
            $loader.removeClass('active');
            alert('Error loading data: ' + error);
        }
    });
}

function renderTable(data) {
    var $tbody = $('#tableBody');
    // Clear existing content
    $tbody.empty();
    
    // Clear search term when re-rendering
    searchTerm = '';
    $('#searchInput').val('');
    
    // Render each top-level group
    $.each(data, function(index, group) {
        renderGroup($tbody, group, 0, true);
    });
}

function renderGroup($tbody, group, level) {
    var hasData = group.ledgers.length > 0 || group.children.length > 0;
    if (!hasData && group.total_debit === 0 && group.total_credit === 0) return;
    
    // Check if group matches search
    var groupMatches = !searchTerm || 
        group.name.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1 ||
        group.code.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
    
    // Check if any ledger in this group matches
    var hasMatchingLedger = false;
    $.each(group.ledgers, function(index, l) {
        if (l.name.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1 ||
            l.code.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1) {
            hasMatchingLedger = true;
            return false; // break
        }
    });
    
    // Check if any child group has matches
    var hasMatchingChild = searchTerm && checkChildrenForMatch(group.children, searchTerm);
    
    // Show group if it matches or has matching children
    if (searchTerm && !groupMatches && !hasMatchingLedger && !hasMatchingChild) {
        return;
    }
    
    // Ensure group has an identifier
    var groupIdentifier = group.id || group.code || 'group-' + level + '-' + group.name.replace(/\s+/g, '-');
    var groupId = 'group-' + groupIdentifier;
    
    // Group is expanded by default, unless explicitly collapsed
    // When searching, always show expanded to display matching results
    var isExpanded = searchTerm !== '' || $.inArray(groupId, expandedGroups) === -1;
    
    // Main group row
    var $groupRow = $('<tr></tr>');
    $groupRow.addClass('tb-group tb-group-level-' + level);
    $groupRow.attr('id', groupId);
    
    var expandIcon = isExpanded ? 'expand_more' : 'chevron_right';
    var paddingLeft = 20 + (level * 20);
    
    $groupRow.html(
        '<td>' + group.code + '</td>' +
        '<td class="group-description-cell">' +
            '<div style="padding-left: ' + paddingLeft + 'px; display: flex; align-items: center;">' +
                '<i class="material-icons collapse-icon" style="cursor: pointer; user-select: none;">' + expandIcon + '</i>' +
                '<strong style="margin-left: 5px; flex: 1;">' + group.name + '</strong>' +
            '</div>' +
        '</td>' +
        '<td class="tb-amount">' + formatAmount(group.total_debit) + '</td>' +
        '<td class="tb-amount">' + formatAmount(group.total_credit) + '</td>'
    );
    
    // Add click event specifically to the description cell
    $groupRow.find('.group-description-cell').on('click', function(e) {
        e.stopPropagation();
        toggleGroup(groupId);
    });
    
    $tbody.append($groupRow);
    
    // Render child groups and ledgers
    if (isExpanded) {
        // Render child groups first
        $.each(group.children, function(index, child) {
            renderGroup($tbody, child, level + 1);
        });
        
        // Then render ledgers
        $.each(group.ledgers, function(index, ledger) {
            if (searchTerm && 
                ledger.name.toLowerCase().indexOf(searchTerm.toLowerCase()) === -1 &&
                ledger.code.toLowerCase().indexOf(searchTerm.toLowerCase()) === -1) {
                return true; // continue
            }
            
            var ledgerPaddingLeft = 40 + (level * 20);
            var $ledgerRow = $('<tr></tr>');
            $ledgerRow.addClass('tb-ledger');
            $ledgerRow.attr('data-parent', groupId);
            $ledgerRow.html(
                '<td>' + ledger.code + '</td>' +
                '<td>' +
                    '<div style="padding-left: ' + ledgerPaddingLeft + 'px;">' +
                        '<a href="#" onclick="openLedgerReport(' + ledger.id + ', event)">' + ledger.name + '</a>' +
                    '</div>' +
                '</td>' +
                '<td class="tb-amount">' + formatAmount(ledger.debit) + '</td>' +
                '<td class="tb-amount">' + formatAmount(ledger.credit) + '</td>'
            );
            $tbody.append($ledgerRow);
        });
    }
}

function checkChildrenForMatch(children, term) {
    var hasMatch = false;
    $.each(children, function(index, child) {
        var matches = child.name.toLowerCase().indexOf(term.toLowerCase()) !== -1 ||
                     child.code.toLowerCase().indexOf(term.toLowerCase()) !== -1;
        
        var ledgerMatches = false;
        $.each(child.ledgers, function(i, l) {
            if (l.name.toLowerCase().indexOf(term.toLowerCase()) !== -1 ||
                l.code.toLowerCase().indexOf(term.toLowerCase()) !== -1) {
                ledgerMatches = true;
                return false; // break
            }
        });
        
        var childMatches = checkChildrenForMatch(child.children, term);
        
        if (matches || ledgerMatches || childMatches) {
            hasMatch = true;
            return false; // break
        }
    });
    
    return hasMatch;
}

function toggleGroup(groupId) {
    console.log('Toggle group called for:', groupId);
    
    // Toggle the expanded state - groups are expanded by default
    // If groupId is NOT in expandedGroups, it means it's currently expanded
    var isCurrentlyExpanded = $.inArray(groupId, expandedGroups) === -1;
    
    if (isCurrentlyExpanded) {
        // Currently expanded, so collapse it (add to expandedGroups)
        expandedGroups.push(groupId);
    } else {
        // Currently collapsed, so expand it (remove from expandedGroups)
        expandedGroups = $.grep(expandedGroups, function(value) {
            return value !== groupId;
        });
    }
    
    // Find the group row
    var $groupRow = $('#' + groupId);
    if ($groupRow.length === 0) {
        console.error('Group row not found:', groupId);
        return;
    }
    
    // Update the chevron icon
    var $icon = $groupRow.find('.collapse-icon');
    if ($icon.length > 0) {
        $icon.text(isCurrentlyExpanded ? 'chevron_right' : 'expand_more');
    }
    
    // Count how many children we're toggling
    var childCount = 0;
    
    // Toggle visibility of child elements
    var $nextRow = $groupRow.next();
    var groupLevel = parseInt($groupRow.attr('class').match(/tb-group-level-(\d+)/)[1]) || 0;
    
    while ($nextRow.length > 0) {
        // Check if this row belongs to the current group
        if ($nextRow.hasClass('tb-group')) {
            var nextLevelMatch = $nextRow.attr('class').match(/tb-group-level-(\d+)/);
            var nextLevel = nextLevelMatch ? parseInt(nextLevelMatch[1]) : 0;
            // If we've reached a group at the same or higher level, stop
            if (nextLevel <= groupLevel) {
                break;
            }
        }
        
        // Toggle visibility
        if (isCurrentlyExpanded) {
            // Hiding (collapsing)
            $nextRow.hide();
            childCount++;
            // Also collapse child groups if they're groups
            if ($nextRow.hasClass('tb-group') && $nextRow.attr('id')) {
                var childGroupId = $nextRow.attr('id');
                if ($.inArray(childGroupId, expandedGroups) === -1) {
                    expandedGroups.push(childGroupId);
                }
                var $childIcon = $nextRow.find('.collapse-icon');
                if ($childIcon.length > 0) {
                    $childIcon.text('chevron_right');
                }
            }
        } else {
            // Showing (expanding) - but only immediate children
            if ($nextRow.hasClass('tb-ledger') || 
                ($nextRow.hasClass('tb-group') && 
                 (parseInt($nextRow.attr('class').match(/tb-group-level-(\d+)/)[1]) || 0) === groupLevel + 1)) {
                $nextRow.show();
                childCount++;
            }
        }
        
        $nextRow = $nextRow.next();
    }
    
    console.log((isCurrentlyExpanded ? 'Hidden' : 'Shown') + ' ' + childCount + ' child elements');
    console.log('Expanded groups (collapsed list):', expandedGroups);
}

function filterTableBySearch(searchTerm) {
    // Re-render the table with search applied
    renderTable(trialBalanceData);
}

function updateStats(totals) {
    $('#totalDebit').text(formatAmount(totals.debit));
    $('#totalCredit').text(formatAmount(totals.credit));
    $('#difference').text(formatAmount(Math.abs(totals.debit - totals.credit)));
    
    $('#footerDebit').text(formatAmount(totals.debit));
    $('#footerCredit').text(formatAmount(totals.credit));
}

function formatAmount(amount) {
    var num = parseFloat(amount || 0);
    return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function openLedgerReport(ledgerId, event) {
    event.preventDefault();
    event.stopPropagation();
    
    var $form = $('<form></form>');
    $form.attr({
        method: 'POST',
        action: '<?php echo base_url(); ?>/accountreport/print_ledger_statement',
        target: '_blank'
    });
    
    var fields = {
        ledger: ledgerId,
        fdate: $('#fdate').val(),
        tdate: $('#tdate').val()
    };
    
    $.each(fields, function(key, value) {
        var $input = $('<input>');
        $input.attr({
            type: 'hidden',
            name: key,
            value: value
        });
        $form.append($input);
    });
    
    $('body').append($form);
    $form.submit();
    $form.remove();
}

function exportExcel() {
    showLoading();
    var $form = $('#exportForm');
    $form.attr('action', '<?php echo base_url(); ?>/accountreport/excel_trial_balance_new');
    populateExportForm();
    $form.submit();
    hideLoading();
}

function exportPDF() {
    showLoading();
    var $form = $('#exportForm');
    $form.attr('action', '<?php echo base_url(); ?>/accountreport/pdf_trial_balance_new');
    populateExportForm();
    $form.submit();
    hideLoading();
}

function printReport() {
    showLoading();
    var $form = $('#exportForm');
    $form.attr('action', '<?php echo base_url(); ?>/accountreport/print_trial_balance_new');
    populateExportForm();
    $form.submit();
    hideLoading();
}

function populateExportForm() {
    $('#export_fdate').val($('#fdate').val());
    $('#export_tdate').val($('#tdate').val());
}

function showLoading() {
    $('#loadingOverlay').addClass('active');
}

function hideLoading() {
    setTimeout(function() {
        $('#loadingOverlay').removeClass('active');
    }, 1000);
}
</script>