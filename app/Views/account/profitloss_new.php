<?php 
$booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); 
global $lang;
?>
<style>
    .income-expenditure-report {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .report-filters {
        background: #ecf0f1;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #bdc3c7;
    }
    
    .report-filters label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .report-filters .form-control {
        border-color: #bdc3c7;
    }
    
    .report-filters .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .btn-success {
        background-color: #27ae60;
        border-color: #27ae60;
    }
    
    .btn-success:hover {
        background-color: #229954;
        border-color: #229954;
    }
    
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border: 2px solid #bdc3c7;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .report-table thead th {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .report-table thead th:last-child,
    .report-table tbody td:last-child {
        text-align: right;
    }
    
    .report-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .report-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .report-table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    .group-header {
        font-weight: 700;
        background-color: #f8f9fa;
        cursor: pointer;
        user-select: none;
    }
    
    .group-header:hover {
        background-color: #e9ecef;
    }
    
    .level-0 { 
        font-size: 18px; 
        background: linear-gradient(to right, #f8f9fa, #fff);
        border-left: 4px solid #2c3e50;
    }
    
    .level-1 { 
        padding-left: 30px; 
        font-size: 16px;
        background: linear-gradient(to right, #fafbfc, #fff);
        border-left: 3px solid #34495e;
    }
    
    .level-2 { 
        padding-left: 60px; 
        font-size: 14px;
        border-left: 2px solid #95a5a6;
    }
    
    .ledger-row {
        padding-left: 90px;
        font-size: 13px;
        color: #2c3e50;
    }
    
    .ledger-link {
        color: #3498db;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .ledger-link:hover {
        color: #2980b9;
        text-decoration: underline;
    }
    
    .total-row {
        font-weight: 700;
        background-color: #ecf0f1;
        border-top: 2px solid #bdc3c7;
    }
    
    .grand-total-row {
        font-weight: 700;
        font-size: 16px;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
    }
    
    .grand-total-row .amount-positive,
    .grand-total-row .amount-negative {
        color: #fff !important;
        font-size: 18px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        text-decoration: underline;
        text-decoration-color: #e74c3c;
        text-underline-offset: 2px;
    }
    
    .amount-positive {
        color: #27ae60;
        font-weight: 700;
        font-size: 14px;
    }
    
    .amount-negative {
        color: #c0392b;
        font-weight: 700;
        font-size: 14px;
    }
    
    .amount-zero {
        color: #7f8c8d;
        font-weight: 400;
    }
    
    .collapsible-icon {
        margin-right: 8px;
        display: inline-block;
        transition: transform 0.3s ease;
        font-weight: bold;
        color: #2c3e50;
        font-size: 16px;
    }
    
    .collapsed .collapsible-icon {
        transform: rotate(-90deg);
    }
    
    .export-buttons {
        margin-top: 20px;
        text-align: center;
    }
    
    .export-buttons .btn {
        margin: 0 5px;
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .export-buttons .btn-primary {
        background-color: #3498db;
        color: #fff;
    }
    
    .export-buttons .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }
    
    .export-buttons .btn-danger {
        background-color: #e74c3c;
        color: #fff;
    }
    
    .export-buttons .btn-danger:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }
    
    .export-buttons .btn-success {
        background-color: #27ae60;
        color: #fff;
    }
    
    .export-buttons .btn-success:hover {
        background-color: #229954;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
    }
    
    .summary-box {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: #fff;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .summary-box h2 {
        margin: 0;
        font-size: 48px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .summary-label {
        font-size: 20px;
        opacity: 0.95;
        margin-top: 10px;
        font-weight: 500;
    }
    
    @media print {
        .report-filters, .export-buttons, .sidebar, .navbar {
            display: none !important;
        }
        .report-table {
            border: 1px solid #000;
        }
        .group-header {
            page-break-inside: avoid;
        }
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .grand-total-row td {
        padding: 15px;
        font-size: 16px;
    }
    
    .month-column {
        min-width: 120px;
        text-align: right !important;
        font-size: 14px;
        font-weight: 600;
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #bdc3c7;
        border-radius: 8px;
        background: #fff;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>ACCOUNTS<small>Accounts / Income and Expenditure Statement</small></h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Income and Expenditure Statement</h2>
                    </div>
                    <div class="body">
                        <!-- Filter Form -->
                        <div class="report-filters">
                            <form id="dateform" method="post">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Breakdown</label>
                                            <select class="form-control" name="breakdown" id="breakdown">
                                                <option value="daily" <?= $breakdown == 'daily' ? 'selected' : '' ?>>Daily</option>
                                                <option value="monthly" <?= $breakdown == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" id="daily_start" style="display:<?= $breakdown == 'daily' ? 'block' : 'none' ?>">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" name="sdate" class="form-control" value="<?= $sdate ?>" max="<?= $booking_calendar_range_year ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" id="daily_end" style="display:<?= $breakdown == 'daily' ? 'block' : 'none' ?>">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" name="edate" class="form-control" value="<?= $edate ?>" max="<?= $booking_calendar_range_year ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" id="monthly_start" style="display:<?= $breakdown == 'monthly' ? 'block' : 'none' ?>">
                                        <div class="form-group">
                                            <label>From Month</label>
                                            <input type="month" name="smonthdate" class="form-control" value="<?= $smonthdate ?>" max="<?= date('Y-m', strtotime($booking_calendar_range_year)) ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" id="monthly_end" style="display:<?= $breakdown == 'monthly' ? 'block' : 'none' ?>">
                                        <div class="form-group">
                                            <label>To Month</label>
                                            <input type="month" name="emonthdate" class="form-control" value="<?= $emonthdate ?>" max="<?= date('Y-m', strtotime($booking_calendar_range_year)) ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Job</label>
                                            <select class="form-control search_box" data-live-search="true" name="fund_id">
                                                <option value="">All Jobs</option>
                                                <?php foreach ($funds as $fund): ?>
                                                    <option value="<?= $fund['id'] ?>" <?= $fund_id == $fund['id'] ? 'selected' : '' ?>>
                                                        <?= $fund['name'] . ' (' . $fund['code'] . ')' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="material-icons">search</i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Report Table -->
                        <div class="income-expenditure-report">
                            <div class="table-responsive">
                                <table class="report-table" id="reportTable">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 300px;">Account Name</th>
                                            <?php if ($breakdown == 'monthly'): ?>
                                                <?php foreach ($getMonthsInRange as $month): ?>
                                                    <th class="month-column"><?= date('M Y', strtotime($month['date'])) ?></th>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <th class="month-column">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Revenue Section
                                        if ($reportData['revenue']): 
                                            echo renderGroupSection($reportData['revenue'], 'Revenue', $breakdown, $getMonthsInRange);
                                        endif;
                                        
                                        // Direct Cost Section
                                        if ($reportData['direct_cost']): 
                                            echo renderGroupSection($reportData['direct_cost'], 'Direct Cost', $breakdown, $getMonthsInRange);
                                        endif;
                                        
                                        // Gross Profit Row
                                        echo renderCalculationRow('Gross Surplus/Deficit', $calculations, 'gross_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
                                        
                                        // Incomes Section
                                        if ($reportData['incomes']): 
                                            echo renderGroupSection($reportData['incomes'], 'Incomes', $breakdown, $getMonthsInRange);
                                        endif;
                                        
                                        // Expenses Section
                                        if ($reportData['expenses']): 
                                            echo renderGroupSection($reportData['expenses'], 'Expenses', $breakdown, $getMonthsInRange);
                                        endif;
                                        
                                        // Net Profit Row
                                        echo renderCalculationRow('Surplus/(Deficit) Before Taxation', $calculations, 'net_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
                                        
                                        // Taxation Section
                                        if ($reportData['taxation']): 
                                            echo renderGroupSection($reportData['taxation'], 'Taxation', $breakdown, $getMonthsInRange);
                                        endif;
                                        
                                        // Final Profit Row
                                        echo renderCalculationRow('Surplus/(Deficit) After Taxation', $calculations, 'final_profit', $breakdown, $getMonthsInRange, 'grand-total-row');
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Summary Box -->
                            <div class="summary-box">
                                <h2><?= formatAmount($calculations['total']['final_profit']) ?></h2>
                                <div class="summary-label">
                                    <?= $calculations['total']['final_profit'] >= 0 ? 'Total Surplus' : 'Total Deficit' ?>
                                </div>
                            </div>
                            
                            <!-- Export Buttons -->
                            <div class="export-buttons">
                                <button class="btn btn-primary" onclick="printReport()">
                                    <i class="material-icons">print</i> Print
                                </button>
                                <button class="btn btn-danger" onclick="exportPDF()">
                                    <i class="material-icons">picture_as_pdf</i> PDF
                                </button>
                                <button class="btn btn-success" onclick="exportExcel()">
                                    <i class="material-icons">grid_on</i> Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Helper functions
function renderGroupSection($groupData, $title, $breakdown, $monthsRange, $level = 0) {
    $html = '';
    
    // Group header
    $html .= '<tr class="group-header level-' . $level . '" data-group="' . $groupData['group']['id'] . '">';
    $html .= '<td><span class="collapsible-icon">▼</span>' . $groupData['group']['name'] . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $html .= '<td></td>';
        }
    }
    $html .= '<td></td>';
    $html .= '</tr>';
    
    // Render ledgers
    foreach ($groupData['ledgers'] as $ledgerData) {
        $html .= renderLedgerRow($ledgerData, $breakdown, $monthsRange, $groupData['group']['id']);
    }
    
    // Render subgroups
    foreach ($groupData['subgroups'] as $subgroup) {
        $html .= renderSubgroup($subgroup, $breakdown, $monthsRange, $groupData['group']['id']);
    }
    
    // Group total
    $html .= renderTotalRow('Total ' . $groupData['group']['name'], $groupData['totals'], $breakdown, $monthsRange, $groupData['group']['id']);
    
    return $html;
}

function renderSubgroup($subgroupData, $breakdown, $monthsRange, $parentGroupId, $level = 1) {
    $html = '';
    
    // Subgroup header
    $html .= '<tr class="group-header level-' . $level . ' group-' . $parentGroupId . '" data-group="sub-' . $subgroupData['group']['id'] . '">';
    $html .= '<td><span class="collapsible-icon">▼</span>' . $subgroupData['group']['name'] . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $html .= '<td></td>';
        }
    }
    $html .= '<td></td>';
    $html .= '</tr>';
    
    // Render ledgers
    foreach ($subgroupData['ledgers'] as $ledgerData) {
        $html .= renderLedgerRow($ledgerData, $breakdown, $monthsRange, 'sub-' . $subgroupData['group']['id']);
    }
    
    // Render nested subgroups
    foreach ($subgroupData['subgroups'] as $nestedSubgroup) {
        $html .= renderSubgroup($nestedSubgroup, $breakdown, $monthsRange, 'sub-' . $subgroupData['group']['id'], $level + 1);
    }
    
    // Subgroup total
    if (count($subgroupData['ledgers']) > 0 || count($subgroupData['subgroups']) > 0) {
        $html .= renderTotalRow('Total ' . $subgroupData['group']['name'], $subgroupData['totals'], $breakdown, $monthsRange, 'sub-' . $subgroupData['group']['id'], 'level-' . $level);
    }
    
    return $html;
}

function renderLedgerRow($ledgerData, $breakdown, $monthsRange, $groupId) {
    $ledger = $ledgerData['ledger'];
    $code = '(' . $ledger['left_code'] . '/' . $ledger['right_code'] . ')';
    $ledgerUrl = base_url() . '/accountreport/ledger_statement/' . $ledger['id'];
    
    $html = '<tr class="ledger-row group-' . $groupId . '">';
    $html .= '<td>' . $code . ' <a href="' . $ledgerUrl . '" target="_blank" class="ledger-link">' . $ledger['name'] . '</a></td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $ledgerData['amounts'][$month['date']] ?? 0;
            $html .= '<td class="month-column">' . formatAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td class="month-column">' . formatAmount($ledgerData['total']) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function renderTotalRow($label, $totals, $breakdown, $monthsRange, $groupId, $extraClass = '') {
    $html = '<tr class="total-row group-' . $groupId . ' ' . $extraClass . '">';
    $html .= '<td>' . $label . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $totals[$month['date']] ?? 0;
            $html .= '<td class="month-column">' . formatAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td class="month-column">' . formatAmount($totals['total'] ?? 0) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function renderCalculationRow($label, $calculations, $key, $breakdown, $monthsRange, $class = '') {
    $html = '<tr class="' . $class . '">';
    $html .= '<td>' . $label . '</td>';
    
    if ($breakdown == 'monthly') {
        foreach ($monthsRange as $month) {
            $amount = $calculations[$month['date']][$key] ?? 0;
            $html .= '<td class="month-column">' . formatAmount($amount) . '</td>';
        }
    }
    
    $html .= '<td class="month-column">' . formatAmount($calculations['total'][$key] ?? 0) . '</td>';
    $html .= '</tr>';
    
    return $html;
}

function formatAmount($amount) {
    if ($amount == 0) {
        return '<span class="amount-zero">0.00</span>';
    } elseif ($amount < 0) {
        return '<span class="amount-negative">(' . number_format(abs($amount), 2) . ')</span>';
    } else {
        return '<span class="amount-positive">' . number_format($amount, 2) . '</span>';
    }
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function() {
    // Breakdown type change handler
    $('#breakdown').change(function() {
        if ($(this).val() == 'monthly') {
            $('#monthly_start, #monthly_end').show();
            $('#daily_start, #daily_end').hide();
        } else {
            $('#daily_start, #daily_end').show();
            $('#monthly_start, #monthly_end').hide();
        }
    });
    
    // Collapsible groups
    $('.group-header').click(function() {
        var groupId = $(this).data('group');
        var $icon = $(this).find('.collapsible-icon');
        
        $(this).toggleClass('collapsed');
        $('.group-' + groupId).toggle();
        
        // Update nested groups visibility
        if ($(this).hasClass('collapsed')) {
            $('.group-' + groupId + ' .group-header').addClass('collapsed');
            $('.group-' + groupId + ' .collapsible-icon').text('▶');
        } else {
            $('.group-' + groupId + ' .group-header').removeClass('collapsed');
            $('.group-' + groupId + ' .collapsible-icon').text('▼');
        }
    });
});

// Print function
function printReport() {
    window.print();
}

// Export to PDF
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');
    
    doc.setFontSize(18);
    doc.text('Income and Expenditure Statement', 14, 20);
    
    doc.setFontSize(12);
    doc.text('Period: <?= $breakdown == "monthly" ? date("M Y", strtotime($smonthdate)) . " to " . date("M Y", strtotime($emonthdate)) : date("d-M-Y", strtotime($sdate)) . " to " . date("d-M-Y", strtotime($edate)) ?>', 14, 30);
    
    // Get table data
    var tableData = [];
    $('#reportTable tbody tr').each(function() {
        var rowData = [];
        $(this).find('td').each(function() {
            rowData.push($(this).text().trim());
        });
        tableData.push(rowData);
    });
    
    // Table headers
    var headers = [];
    $('#reportTable thead th').each(function() {
        headers.push($(this).text());
    });
    
    // Generate table
    doc.autoTable({
        head: [headers],
        body: tableData,
        startY: 40,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [102, 126, 234] }
    });
    
    // Add summary
    doc.setFontSize(14);
    var finalY = doc.lastAutoTable.finalY + 10;
    doc.text('<?= $calculations["total"]["final_profit"] >= 0 ? "Total Surplus: " : "Total Deficit: " ?><?= number_format(abs($calculations["total"]["final_profit"]), 2) ?>', 14, finalY);
    
    doc.save('income_expenditure_statement_<?= date("Ymd") ?>.pdf');
}

// Export to Excel
function exportExcel() {
    var wb = XLSX.utils.table_to_book(document.getElementById('reportTable'), {sheet: "Report"});
    
    // Add summary sheet
    var summaryData = [
        ['Income and Expenditure Statement'],
        ['Period: <?= $breakdown == "monthly" ? date("M Y", strtotime($smonthdate)) . " to " . date("M Y", strtotime($emonthdate)) : date("d-M-Y", strtotime($sdate)) . " to " . date("d-M-Y", strtotime($edate)) ?>'],
        [''],
        ['<?= $calculations["total"]["final_profit"] >= 0 ? "Total Surplus" : "Total Deficit" ?>', '<?= number_format(abs($calculations["total"]["final_profit"]), 2) ?>']
    ];
    
    var ws = XLSX.utils.aoa_to_sheet(summaryData);
    XLSX.utils.book_append_sheet(wb, ws, "Summary");
    
    XLSX.writeFile(wb, 'income_expenditure_statement_<?= date("Ymd") ?>.xlsx');
}
</script>