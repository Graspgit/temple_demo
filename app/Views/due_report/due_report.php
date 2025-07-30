<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DUE REPORT<small>Reports / <a href="#" target="_blank">Due Report</a></small></h2>
        </div>
        
        <!-- Filter Section -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"><h2>Due Report Filters</h2></div>
                            <div class="col-md-4" align="right">
                                <?php if (!empty($due_data)) { ?>
                                <a href="<?= base_url('due_report/exportExcel') ?>?<?= http_build_query($_GET) ?>" class="btn bg-green waves-effect" style="margin-right: 5px;">
                                    <i class="material-icons">file_download</i> EXCEL
                                </a>
                                <a href="<?= base_url('due_report/printReport') ?>?<?= http_build_query($_GET) ?>" target="_blank" class="btn bg-blue waves-effect">
                                    <i class="material-icons">print</i> PRINT
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <form action="<?= base_url('due_report') ?>" method="GET">
                        <div class="body">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control show-tick" name="report_type">
                                                    <option value="all" <?= ($report_type == 'all') ? 'selected' : '' ?>>All Invoices</option>
                                                    <option value="outstanding" <?= ($report_type == 'outstanding') ? 'selected' : '' ?>>Outstanding Only</option>
                                                    <option value="paid" <?= ($report_type == 'paid') ? 'selected' : '' ?>>Paid Only</option>
                                                </select>
                                                <label class="form-label">Report Type</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control show-tick" name="entity_type" id="entity_type">
                                                    <option value="all" <?= ($entity_type == 'all') ? 'selected' : '' ?>>All</option>
                                                    <option value="customer" <?= ($entity_type == 'customer') ? 'selected' : '' ?>>Customer</option>
                                                    <option value="supplier" <?= ($entity_type == 'supplier') ? 'selected' : '' ?>>Supplier</option>
                                                </select>
                                                <label class="form-label">Entity Type</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control show-tick" name="entity_id" id="entity_id">
                                                    <option value="">-- Select Entity --</option>
                                                    <optgroup label="Customers" id="customer_options" style="<?= ($entity_type == 'supplier') ? 'display:none;' : '' ?>">
                                                        <?php if (!empty($customers)) {
                                                            foreach($customers as $customer) { ?>
                                                            <option value="<?= $customer['id'] ?>" <?= ($entity_id == $customer['id'] && $entity_type == 'customer') ? 'selected' : '' ?>><?= esc($customer['customer_name']) ?></option>
                                                        <?php } } ?>
                                                    </optgroup>
                                                    <optgroup label="Suppliers" id="supplier_options" style="<?= ($entity_type == 'customer') ? 'display:none;' : '' ?>">
                                                        <?php if (!empty($suppliers)) {
                                                            foreach($suppliers as $supplier) { ?>
                                                            <option value="<?= $supplier['id'] ?>" <?= ($entity_id == $supplier['id'] && $entity_type == 'supplier') ? 'selected' : '' ?>><?= esc($supplier['supplier_name']) ?></option>
                                                        <?php } } ?>
                                                    </optgroup>
                                                </select>
                                                <label class="form-label">Specific Entity</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="date" class="form-control" name="from_date" value="<?= esc($from_date) ?>">
                                                <label class="form-label">From Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="clear:both"></div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="date" class="form-control" name="to_date" value="<?= esc($to_date) ?>">
                                                <label class="form-label">To Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-9" align="left" style="padding-top: 20px;">
                                        <button type="submit" name="generate_report" value="1" class="btn btn-success btn-lg waves-effect">
                                            <i class="material-icons">search</i> GENERATE REPORT
                                        </button>
                                        <a href="<?= base_url('due_report') ?>" class="btn btn-default btn-lg waves-effect">
                                            <i class="material-icons">clear</i> CLEAR
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <?php if (!empty($summary) && !empty($due_data)) { ?>
        <div class="row clearfix">
            <!-- Customer Summary - Show when entity_type is 'all' or 'customer' -->
            <?php if ($entity_type == 'all' || $entity_type == 'customer') { ?>
            <div class="col-lg-<?= ($entity_type == 'all') ? '6' : '12' ?> col-md-<?= ($entity_type == 'all') ? '6' : '12' ?> col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>CUSTOMER DUES SUMMARY</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <tr>
                                    <td><strong>Total Invoices:</strong></td>
                                    <td><?= number_format($summary['customer']['total_invoices'] ?? 0) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><?= number_format($summary['customer']['total_amount'] ?? 0, 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td><?= number_format($summary['customer']['total_paid'] ?? 0, 2) ?></td>
                                </tr>
                                <tr class="bg-red">
                                    <td><strong>Total Due:</strong></td>
                                    <td><strong><?= number_format($summary['customer']['total_due'] ?? 0, 2) ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Supplier Summary - Show when entity_type is 'all' or 'supplier' -->
            <?php if ($entity_type == 'all' || $entity_type == 'supplier') { ?>
            <div class="col-lg-<?= ($entity_type == 'all') ? '6' : '12' ?> col-md-<?= ($entity_type == 'all') ? '6' : '12' ?> col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SUPPLIER DUES SUMMARY</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <tr>
                                    <td><strong>Total Invoices:</strong></td>
                                    <td><?= number_format($summary['supplier']['total_invoices'] ?? 0) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><?= number_format($summary['supplier']['total_amount'] ?? 0, 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td><?= number_format($summary['supplier']['total_paid'] ?? 0, 2) ?></td>
                                </tr>
                                <tr class="bg-red">
                                    <td><strong>Total Due:</strong></td>
                                    <td><strong><?= number_format($summary['supplier']['total_due'] ?? 0, 2) ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <!-- Report Data Section -->
        <?php if (!empty($due_data)) { ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>DUE REPORT DETAILS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Grand Total</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Days Overdue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sno = 1;
                                    $total_grand_total = 0;
                                    $total_paid_amount = 0;
                                    $total_due_amount = 0;
                                    
                                    foreach($due_data as $row) { 
                                        $total_grand_total += $row['grand_total'];
                                        $total_paid_amount += $row['paid_amount'];
                                        $total_due_amount += $row['due_amount'];
                                        
                                        $status_class = '';
                                        $status_text = '';
                                        
                                        if ($row['due_amount'] <= 0) {
                                            $status_class = 'bg-green';
                                            $status_text = 'PAID';
                                        } elseif ($row['days_overdue'] <= 30) {
                                            $status_class = 'bg-yellow';
                                            $status_text = 'CURRENT';
                                        } elseif ($row['days_overdue'] <= 60) {
                                            $status_class = 'bg-orange';
                                            $status_text = 'OVERDUE';
                                        } else {
                                            $status_class = 'bg-red';
                                            $status_text = 'CRITICAL';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $sno++ ?></td>
                                        <td>
                                            <span class="label <?= ($row['entity_type'] == 'Customer') ? 'label-info' : 'label-warning' ?>">
                                                <?= esc($row['entity_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($row['entity_name']) ?> (<?= esc($row['entity_code']) ?>)</td>
                                        <td><?= esc($row['invoice_no']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                                        <td style="text-align: right;"><?= number_format($row['grand_total'], 2) ?></td>
                                        <td style="text-align: right;"><?= number_format($row['paid_amount'], 2) ?></td>
                                        <td style="text-align: right;">
                                            <strong <?= ($row['due_amount'] > 0) ? 'style="color: red;"' : 'style="color: green;"' ?>>
                                                <?= number_format($row['due_amount'], 2) ?>
                                            </strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <?= ($row['due_amount'] > 0) ? $row['days_overdue'] . ' days' : '-' ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="label <?= $status_class ?>"><?= $status_text ?></span>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-info btn-sm waves-effect" onclick="showReceiptPaymentHistory(<?= $row['invoice_id'] ?>, '<?= $row['invoice_no'] ?>')">
                                                <i class="material-icons">history</i> History
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f5f5f5; font-weight: bold;">
                                        <td colspan="5" style="text-align: right;"><strong>TOTAL:</strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_grand_total, 2) ?></strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_paid_amount, 2) ?></strong></td>
                                        <td style="text-align: right; color: red;"><strong><?= number_format($total_due_amount, 2) ?></strong></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } elseif (isset($_GET['generate_report'])) { ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <div class="alert alert-info">
                            <strong>No records found!</strong> Please adjust your filter criteria and try again.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>

<!-- CSS and JS includes -->
<link href="<?= base_url('assets/plugins/bootstrap-select/css/bootstrap-select.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?= base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-datatable/jquery.dataTables.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') ?>"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('.dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[4, 'desc']], // Sort by date column
        columnDefs: [
            { targets: [5, 6, 7], className: 'text-right' }, // Right align amount columns
            { targets: [8, 9], className: 'text-center' } // Center align status columns
        ]
    });

    // Entity type change handler
    $('#entity_type').change(function() {
        var selectedType = $(this).val();
        $('#entity_id').val('');
        
        if (selectedType === 'customer') {
            $('#customer_options').show();
            $('#supplier_options').hide();
        } else if (selectedType === 'supplier') {
            $('#customer_options').hide();
            $('#supplier_options').show();
        } else {
            $('#customer_options').show();
            $('#supplier_options').show();
        }
        
        $('#entity_id').selectpicker('refresh');
    });

    // Initialize selectpicker
    $('.show-tick').selectpicker();
});

// Function to show receipt and payment history
function showReceiptPaymentHistory(invoiceId, invoiceNo) {
    // Show loading
    $('#historyModal .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    $('#historyModal .modal-title').text('Loading...');
    $('#historyModal').modal('show');
    
    // Load history data
    $.ajax({
        url: '<?= base_url('due_report/getReceiptPaymentHistoryAjax') ?>/' + invoiceId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            
            // Set dynamic title based on invoice type
            let historyType = response.invoice_data.invoice_type == 1 ? 'Receipt History' : 'Payment History';
            $('#historyModal .modal-title').text(historyType + ' - ' + invoiceNo);
            
            if (response.receipt_payment_history && response.receipt_payment_history.length > 0) {
                // Show Print All button
                $('#printAllBtn').show().off('click').on('click', function() {
                    printReceiptPaymentHistory(invoiceId);
                });
                
                html += '<div class="invoice-info mb-3">';
                html += '<h4>Invoice Details:</h4>';
                html += '<p><strong>Invoice No:</strong> ' + response.invoice_data.invoice_no + '</p>';
                html += '<p><strong>Entity:</strong> ' + response.invoice_data.entity_name + ' (' + response.invoice_data.entity_type + ')</p>';
                html += '<p><strong>Grand Total:</strong> ' + parseFloat(response.invoice_data.grand_total).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</p>';
                html += '<p><strong>Paid Amount:</strong> ' + parseFloat(response.invoice_data.paid_amount).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</p>';
                html += '<p><strong>Due Amount:</strong> ' + parseFloat(response.invoice_data.due_amount).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</p>';
                html += '</div>';
                
                html += '<h4>' + (response.invoice_data.invoice_type == 1 ? 'Receipt History:' : 'Payment History:') + '</h4>';
                html += '<div class="table-responsive">';
                html += '<table class="table table-bordered table-striped">';
                html += '<thead>';
                html += '<tr>';
                html += '<th>Date</th>';
                html += '<th>Voucher No</th>';
                html += '<th>Amount</th>';
                html += '<th>Payment Mode</th>';
                html += '<th>Narration</th>';
                html += '<th>Action</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                response.receipt_payment_history.forEach(function(item) {
                    let amount = item.entry_type === 'Receipt' ? item.dr_total : item.cr_total;
                    
                    html += '<tr>';
                    html += '<td>' + item.entry_date + '</td>';
                    html += '<td>' + (item.voucher_number || '-') + '</td>';
                    html += '<td style="text-align: right;">' + parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
                    html += '<td>' + (item.payment_mode || '-') + '</td>';
                    html += '<td>' + (item.narration || '-') + '</td>';
                    html += '<td>';
                    html += '<button type="button" class="btn btn-xs btn-primary" onclick="printVoucher(' + item.entry_id + ', \'' + item.entry_type.toLowerCase() + '\')">';
                    html += '<i class="fa fa-print"></i> Print';
                    html += '</button>';
                    html += '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody>';
                html += '</table>';
                html += '</div>';
            } else {
                // Hide Print All button if no data
                $('#printAllBtn').hide();
                let historyType = response.invoice_data && response.invoice_data.invoice_type == 1 ? 'receipt' : 'payment';
                html = '<div class="alert alert-info">No ' + historyType + ' history found for this invoice.</div>';
            }
            
            $('#historyModal .modal-body').html(html);
        },
        error: function() {
            // Hide Print All button on error
            $('#printAllBtn').hide();
            $('#historyModal .modal-title').text('Error');
            $('#historyModal .modal-body').html('<div class="alert alert-danger">Error loading receipt and payment history.</div>');
        }
    });
}

// Function to print voucher
function printVoucher(entryId, entryType) {
    // You can customize this URL based on your print voucher route
    let printUrl = '<?= base_url('entries/print_page') ?>/' + entryId;
    window.open(printUrl, '_blank');
}

// Function to print overall receipt and payment history
function printReceiptPaymentHistory(invoiceId) {
    let printUrl = '<?= base_url('due_report/printReceiptPaymentHistory') ?>/' + invoiceId;
    window.open(printUrl, '_blank');
}
</script>

<style>
.bg-red { background-color: #f44336 !important; color: white !important; }
.bg-green { background-color: #4caf50 !important; color: white !important; }
.bg-yellow { background-color: #ff9800 !important; color: white !important; }
.bg-orange { background-color: #ff5722 !important; color: white !important; }
.label-info { background-color: #17a2b8; }
.label-warning { background-color: #ffc107; color: #212529; }
.text-right { text-align: right !important; }
.text-center { text-align: center !important; }
</style>

<!-- Receipt & Payment History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="historyModalLabel">Receipt & Payment History</h4>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="printAllBtn">
                    <i class="fa fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>