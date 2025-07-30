<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<style>
    :root {
        --primary-color: #2563eb;
        --primary-hover: #1d4ed8;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --bg-light: #f8fafc;
        --border-color: #e5e7eb;
        --text-muted: #6b7280;
    }
    
    body {
        background-color: var(--bg-light);
    }
    
    .card {
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border: none;
        background: white;
    }
    
    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
    }
    
    .header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.75rem;
    }
    
    .financial-year-info {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        backdrop-filter: blur(10px);
        font-weight: 500;
        display: inline-block;
    }
    
    .filter-section {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        align-items: end;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .form-control, .selectpicker {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 1rem;
        transition: all 0.2s;
        background-color: white;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    
    .date-input-wrapper {
        position: relative;
    }
    
    .date-input-wrapper::before {
        content: '📅';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        pointer-events: none;
    }
    
    .date-input-wrapper input[type="date"] {
        padding-left: 2.5rem;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.625rem 1.5rem;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-success {
        background: var(--success-color);
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-primary {
        background: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .btn-danger {
        background: var(--danger-color);
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .export-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
        margin-top: 1rem;
    }
    
    .table1 {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .table1 thead {
        background: linear-gradient(to right, #f3f4f6, #e5e7eb);
    }
    
    .table1 th {
        padding: 1rem;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.05em;
        border: none;
    }
    
    .table1 td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    
    .table1 tr:hover {
        background-color: #f9fafb;
    }
    
    .table1 tr:last-child td {
        border-bottom: none;
    }
    
    .ledger-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin: 2rem 0 1rem 0;
        font-size: 1.125rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .balance-highlight {
        font-weight: 600;
        color: #374151;
    }
    
    .opening-balance-row, .closing-balance-row {
        background-color: #f3f4f6 !important;
        font-weight: 600;
    }
    
    .debit-amount {
        color: #059669;
    }
    
    .credit-amount {
        color: #dc2626;
    }
    
    .negative-balance {
        color: #dc2626;
    }
    
    /* Bootstrap Select Custom Styling */
    .bootstrap-select .dropdown-toggle {
        border: 2px solid var(--border-color) !important;
        border-radius: 8px !important;
        padding: 0.625rem 0.875rem !important;
        background-color: white !important;
    }
    
    .bootstrap-select .dropdown-toggle:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
    }
    
    .dropdown-menu {
        border-radius: 8px !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .export-buttons {
            justify-content: stretch;
        }
        
        .export-buttons .btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2>📊 General Ledger Report</h2>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php
                                if(count($check_financial_year) > 0) {
                                    $from_date = $check_financial_year[0]['from_year_month']."-01";
                                    $from_date_re = date("d-m-Y", strtotime($from_date));
                                    $to_date = $check_financial_year[0]['to_year_month']."-31";
                                    $to_date_re = date("d-m-Y", strtotime($to_date));
                                ?>
                                <div class="financial-year-info">
                                    📅 Financial Year: <?php echo $from_date_re; ?> to <?php echo $to_date_re; ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="body">
                        <form action="<?php echo base_url(); ?>/accountreport/new_ledger_report" method="post">
                            <div class="filter-section">
                                <div class="filter-grid">
                                    <div class="form-group">
                                        <label class="form-label">Select Ledger(s)</label>
                                        <select class="form-control search_box" data-live-search="true" name="ledger[]" id="ledger" multiple data-actions-box="true" data-selected-text-format="count">
                                            <?php foreach($ledger as $row){ ?>
                                               <?php echo $row; ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Booking Type</label>
                                        <select class="form-control" name="booking_type" id="booking_type">
                                            <option value="all" <?php echo ($booking_type == 'all') ? 'selected' : ''; ?>>All Types</option>
                                            <option value="manual" <?php echo ($booking_type == 'manual') ? 'selected' : ''; ?>>Manual Entries</option>
                                            <option value="1" <?php echo ($booking_type == '1') ? 'selected' : ''; ?>>Ubayam</option>
                                            <option value="2" <?php echo ($booking_type == '2') ? 'selected' : ''; ?>>Donation</option>
                                            <option value="3" <?php echo ($booking_type == '3') ? 'selected' : ''; ?>>Archanai</option>
                                            <option value="8" <?php echo ($booking_type == '8') ? 'selected' : ''; ?>>Hall Booking</option>
                                            <option value="10" <?php echo ($booking_type == '10') ? 'selected' : ''; ?>>Prasadam</option>
                                            <option value="11" <?php echo ($booking_type == '11') ? 'selected' : ''; ?>>Member Registration</option>
                                            <option value="12" <?php echo ($booking_type == '12') ? 'selected' : ''; ?>>Annadhanam</option>
                                            <option value="13" <?php echo ($booking_type == '13') ? 'selected' : ''; ?>>Advance Salary</option>
                                            <option value="14" <?php echo ($booking_type == '14') ? 'selected' : ''; ?>>Payslip</option>
                                            <option value="18" <?php echo ($booking_type == '18') ? 'selected' : ''; ?>>Sales Invoice</option>
                                            <option value="19" <?php echo ($booking_type == '19') ? 'selected' : ''; ?>>Purchase Invoice</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">From Date</label>
                                        <div class="date-input-wrapper">
                                            <input type="date" name="fdate" id="fdate" class="form-control" value="<?php echo $fdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">To Date</label>
                                        <div class="date-input-wrapper">
                                            <input type="date" name="tdate" id="tdate" class="form-control" value="<?php echo $tdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                        </div>
                                    </div>
                                    
                                    <?php if($view != true) { ?>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <span>🔍</span> Generate Report
                                        </button>
                                    </div>
                                    <?php } ?>
                                </div>
                                
                                <?php if($view != true && !empty($ledger_id)) { ?>
                                <div class="export-buttons">
                                    <button type="button" class="btn btn-primary" id="print">
                                        <span>🖨️</span> Print
                                    </button>
                                    <button type="submit" name="pdf_archanaireport" class="btn btn-danger" id="pdf">
                                        <span>📄</span> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-success" id="excel">
                                        <span>📊</span> Export Excel
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </form>
                        
                        <!-- Hidden forms for export functionality -->
                        <form style="display: none;" target="_blank" action="<?php echo base_url(); ?>/accountreport/print_multiple_ledger_statement" method="POST" id="print_ledger">
                            <?php foreach($ledger_id as $ledgerid_new){ ?>
                            <input type="hidden" name="ledger[]" value="<?php echo $ledgerid_new; ?>">
                            <?php } ?>
                            <input type="hidden" name="fdate" value="<?php echo $fdate; ?>">
                            <input type="hidden" name="tdate" value="<?php echo $tdate; ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $booking_type; ?>">
                            <button type="submit" id="print_sub">Submit</button>
                        </form>
                        
                        <form style="display: none;" target="_blank" action="<?php echo base_url(); ?>/accountreport/pdf_multiple_ledger_statement" method="POST" id="pdf_ledger">
                            <?php foreach($ledger_id as $ledgerid_new){ ?>
                            <input type="hidden" name="ledger[]" value="<?php echo $ledgerid_new; ?>">
                            <?php } ?>
                            <input type="hidden" name="fdate" value="<?php echo $fdate; ?>">
                            <input type="hidden" name="tdate" value="<?php echo $tdate; ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $booking_type; ?>">
                        </form>
                        
                        <form style="display: none;" target="_blank" action="<?php echo base_url(); ?>/accountreport/excel_multiple_ledger_statement1" method="POST" id="excel_ledger">
                            <?php foreach($ledger_id as $ledgerid_new){ ?>
                            <input type="hidden" name="ledger[]" value="<?php echo $ledgerid_new; ?>">
                            <?php } ?>
                            <input type="hidden" name="fdate" value="<?php echo $fdate; ?>">
                            <input type="hidden" name="tdate" value="<?php echo $tdate; ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $booking_type; ?>">
                        </form>
                        
                        <div id="divTableData_general_ledger">
                            <?php
                            foreach($ledger_id as $ledgerid){
                                $ledgername_code = get_ledger_name($ledgerid);
                                $res_ledger = loop_general_ledger_statement($ledgerid, $fdate, $tdate, $booking_type);
                            ?>
                            
                            <h3 class="ledger-title"><?php echo $ledgername_code; ?></h3>
                            <div class="table-responsive">
                                <table class="table1 tableexcel" id="my_table_<?= $ledgerid; ?>">
                                    <thead>
                                        <tr>
                                            <th width="10%">Date</th>
                                            <th width="10%">Ref No</th>
                                            <th width="25%">Particulars</th>
                                            <th width="10%" class="text-right">Debit Amount</th>
                                            <th width="10%" class="text-right">Credit Amount</th>
                                            <th width="10%" class="text-right">Net Activity</th>
                                            <th width="20%" class="text-right">Balance Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="opening-balance-row">
                                            <td></td>
                                            <td colspan="2">Opening Balance</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right balance-highlight">
                                                <?php
                                                if($res_ledger['op_bal'] < 0){
                                                    echo '<span class="negative-balance">( '.number_format(abs($res_ledger['op_bal']),'2','.',',').' )</span>';
                                                } else {
                                                    echo number_format($res_ledger['op_bal'],'2','.',',');
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        
                                        <?php 
                                        $cu_credit = 0;
                                        $cu_debit = 0;
                                        foreach($res_ledger['data'] as $row) { 
                                            if(!empty($row['credit_amount'])) $cu_credit += (float) $row['credit_amount'];
                                            if(!empty($row['debit_amount'])) $cu_debit += (float) $row['debit_amount'];
                                        ?>
                                        <tr>
                                            <td><?= date('d-m-Y',strtotime($row['date'])); ?></td>
                                            <td><?php echo '<a href="' . base_url() . '/entries/view_page/' . $row['entry_id'] . '">' . $row['entry_code'] . '</a>'; ?></td>
                                            <td><?php echo $row['narration']; ?></td>
                                            <td class="text-right debit-amount"><?= $row['debit']; ?></td>
                                            <td class="text-right credit-amount"><?= $row['credit']; ?></td>
                                            <td class="text-right"></td>
                                            <td class="text-right balance-highlight">
                                                <?php
                                                if($row['balance'] < 0){
                                                    echo '<span class="negative-balance">( '.number_format(abs($row['balance']),2).' )</span>';
                                                } else {
                                                    echo number_format($row['balance'],2);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <tr class="closing-balance-row">
                                            <td></td>
                                            <td colspan="2">Closing Balance</td>
                                            <td class="text-right debit-amount"><?= number_format($cu_debit,'2','.',','); ?></td>
                                            <td class="text-right credit-amount"><?= number_format($cu_credit,'2','.',','); ?></td>
                                            <td class="text-right">
                                                <?php
                                                $diffrence_amt = $cu_debit - $cu_credit;
                                                echo number_format(abs($diffrence_amt),'2','.',',');
                                                ?>
                                            </td>
                                            <td class="text-right balance-highlight">
                                                <?php
                                                if($res_ledger['cl_bal'] < 0){
                                                    echo '<span class="negative-balance">( '.number_format(abs($res_ledger['cl_bal']),'2','.',',').' )</span>';
                                                } else {
                                                    echo number_format($res_ledger['cl_bal'],'2','.',',');
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
$(document).ready(function(){
    // Initialize bootstrap select with enhanced styling
    $('.search_box').selectpicker({
        style: 'btn-default',
        size: 10,
        liveSearchPlaceholder: 'Search ledgers...'
    });
    
    // Export button handlers
    $("#print").click(function(){
        $("#print_sub").trigger('click');
    });
    
    $("#pdf").click(function(){
        $("#pdf_ledger").submit();
    });
    
    $("#excel").click(function(){
        $("#excel_ledger").submit();
    });
    
    // Add loading state to submit button
    $('form').on('submit', function(){
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span> Generating...');
    });
});

function generatePDF() {
    const element = document.getElementById("divTableData_general_ledger");
    const opt = {
        margin: 10,
        filename: 'general_ledger_report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>