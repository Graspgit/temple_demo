<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">

            Tax Exempt Donations Report
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tax Report</li>
            </ul>
        </nav>
    </div>

    <!-- Filter Section -->
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3" style="text-align: right; margin: 10px;">
                    
                        <div class="btn-group" role="group">
                            <!-- <button onclick="printReport()" class="btn btn-outline-info btn-sm">
                                <i class="mdi mdi-printer me-1"></i>Print
                            </button> -->
<button onclick="exportToExcel()" class="btn btn-success btn-lg" style="color: white; padding: 10px 20px;" >
    <i class="mdi mdi-file-excel me-1"></i> Excel
</button>

                        </div>
                    </div>

                    <form method="GET" class="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label fw-bold">From Date</label>
                                <input type="date" class="form-control form-control-lg" id="from_date" name="from_date"
                                    value="<?php echo $from_date; ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label fw-bold">To Date</label>
                                <input type="date" class="form-control form-control-lg" id="to_date" name="to_date"
                                    value="<?php echo $to_date; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label fw-bold">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-magnify"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-lg" id="search" name="search"
                                        value="<?php echo $search; ?>" placeholder="Name, Reference, IC, Mobile...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="mdi mdi-magnify me-1"></i>Filter
                                    </button>
                                    <a href="<?php echo base_url(); ?>/report/tax_report"
                                        class="btn btn-outline-secondary">
                                        <i class="mdi mdi-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card card-tale">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            <i class="mdi mdi-receipt icon-xl text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-dark mb-1 fw-bold">Total Tax Exempt Donations</p>
                            <h2 class="mb-0 text-primary fw-bold"><?php echo number_format($total_count); ?></h2>
                            <p class="text-muted mb-0 fs-6">Confirmed donations only</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            <i class="mdi mdi-currency-usd icon-xl text-success"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-dark mb-1 fw-bold">Total Amount</p>
                            <h2 class="mb-0 text-success fw-bold">RM <?php echo number_format($total_amount, 2); ?></h2>
                            <p class="text-muted mb-0 fs-6">Tax-deductible amount</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Data Table Section -->
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="mdi mdi-table text-primary me-2"></i>Tax Exempt Donations List
                            </h4>
                            <p class="card-description text-muted mb-0">
                                Detailed list of all donations with tax exemption receipts
                            </p>
                        </div>
                        <?php if (!empty($tax_donations)): ?>
                            <div class="badge badge-primary badge-pill fs-6">
                                <?php echo count($tax_donations); ?> Records Found
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover modern-table" id="taxReportTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th><i class="mdi mdi-calendar me-1"></i>Date</th>
                                    <th><i class="mdi mdi-barcode me-1"></i>Reference</th>
                                    <th><i class="mdi mdi-account me-1"></i>Donor Name</th>
                                    <th><i class="mdi mdi-card-account-details me-1"></i>IC Number</th>
                                    <th><i class="mdi mdi-phone me-1"></i>Mobile</th>
                                    <th><i class="mdi mdi-email me-1"></i>Email</th>
                                    <th><i class="mdi mdi-gift me-1"></i>Type</th>
                                    <th><i class="mdi mdi-currency-usd me-1"></i>Amount</th>
                                    <th><i class="mdi mdi-credit-card me-1"></i>Payment</th>
                                    <th class="text-center"><i class="mdi mdi-cog me-1"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tax_donations)): ?>
                                    <?php foreach ($tax_donations as $index => $donation): ?>
                                        <tr class="table-row-hover">
                                            <td class="text-center fw-bold"><?php echo $index + 1; ?></td>
                                            <td>
                                                <span
                                                    class="badge badge-info"><?php echo date('d-M-Y', strtotime($donation['date'])); ?></span>
                                            </td>
                                            <td>
                                                <code class="text-primary fw-bold"><?php echo $donation['ref_no']; ?></code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="mdi mdi-account text-primary"></i>
                                                    </div>
                                                    <strong class="text-dark"><?php echo $donation['name']; ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo $donation['ic_number'] ?: '-'; ?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo $donation['mobile'] ?: '-'; ?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted small"><?php echo $donation['email'] ?: '-'; ?></span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-light-secondary"><?php echo $donation['donation_type'] ?? 'General'; ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success fs-6">RM
                                                    <?php echo number_format($donation['amount'], 2); ?></span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-light-info"><?php echo $donation['payment_method']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url(); ?>/donation_online/print_tax_exempt/<?php echo $donation['id']; ?>"
                                                    
                                                    target="_blank" class="btn btn-sm btn-outline-primary rounded-pill"
                                                    title="Print Tax Receipt">
                                                    <i class="mdi mdi-printer"></i><span style="font-size: 15px;">Print</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                                                <h5 class="text-muted mb-2">No Tax Exempt Donations Found</h5>
                                                <p class="text-muted mb-3">No donations with tax exemption receipts were
                                                    found for the selected criteria.</p>
                                                <a href="<?php echo base_url(); ?>/report/tax_report"
                                                    class="btn btn-outline-primary">
                                                    <i class="mdi mdi-refresh me-1"></i>Reset Filters
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($tax_donations)): ?>
                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="mdi mdi-information me-1"></i>
                                        Showing <?php echo count($tax_donations); ?> tax exempt donations
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="mdi mdi-calendar me-1"></i>
                                        Report generated on <?php echo date('d M Y, h:i A'); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Styles -->
<!-- Additional CSS to make tax report table look like pledge report -->
<style>
    /* Override existing styles to match pledge report */
    .modern-table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        border-radius: 0 !important;
        overflow: visible !important;
    }

    .modern-table thead th {
        background: #f8f9fa !important;
        color: #495057 !important;
        font-weight: 600 !important;
        text-transform: none !important;
        font-size: 14px !important;
        letter-spacing: normal !important;
        padding: 12px 8px !important;
        border: 1px solid #dee2e6 !important;
        position: static !important;
        background-image: none !important;
    }

    .modern-table tbody tr {
        transition: background-color 0.15s ease-in-out !important;
        transform: none !important;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: none !important;
    }

    .modern-table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .modern-table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .modern-table td {
        padding: 12px 8px !important;
        vertical-align: middle !important;
        border: 1px solid #dee2e6 !important;
        font-size: 14px !important;
    }

    /* Simplified badge styling */
    .badge {
        font-size: 11px !important;
        font-weight: bold !important;
        padding: 4px 8px !important;
        border-radius: 4px !important;
    }

    .badge-info {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
        border: none !important;
    }

    .badge-light-secondary {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        border: 1px solid #dee2e6 !important;
    }

    .badge-light-info {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
        border: 1px solid #bee5eb !important;
    }

    /* Remove complex avatar styling */
    .avatar-sm {
        display: none !important;
    }

    /* Simplify amount display */
    .text-success {
        color: #28a745 !important;
        font-weight: bold !important;
    }

    /* Simplify action buttons */
    .btn-outline-primary {
        color: #007bff !important;
        border-color: #007bff !important;
        background-color: transparent !important;
    }

    .btn-outline-primary:hover {
        background-color: #007bff !important;
        color: white !important;
    }

    /* Table container styling like pledge report */
    .table-responsive {
        border-radius: 0 !important;
        overflow-x: auto !important;
    }

    .card {
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        transform: none !important;
    }

    .card:hover {
        transform: none !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    /* Card header styling */
    .card-title {
        font-size: 1.25rem !important;
        font-weight: 500 !important;
        color: #495057 !important;
    }

    /* Remove gradient backgrounds */
    .page-title-icon,
    .card-tale,
    .card-dark-blue {
        background: #f8f9fa !important;
        color: #495057 !important;
    }

    /* Status badge styling similar to pledge report */
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-active {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    /* Remove animations */
    .fadeInUp {
        animation: none !important;
    }

    /* Filter section styling to match pledge report */
    .filter-form {
        padding: 20px !important;
        background: #f8f9fa !important;
        border-radius: 8px !important;
        margin-top: 0 !important;
        border: 1px solid #dee2e6 !important;
    }

    /* Button styling to match pledge report */
    .btn-primary {
        background-color: #007bff !important;
        border-color: #007bff !important;
        font-weight: normal !important;
    }

    .btn-primary:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .btn-outline-secondary {
        color: #6c757d !important;
        border-color: #6c757d !important;
    }

    .btn-outline-info {
        color: #17a2b8 !important;
        border-color: #17a2b8 !important;
    }

    .btn-outline-success {
        color: #28a745 !important;
        border-color: #28a745 !important;
    }

    /* Remove complex hover effects */
    .card .card-body {
        transition: none !important;
    }

    /* Simplify empty state */
    .empty-state {
        padding: 40px 20px !important;
    }

    .empty-state i {
        font-size: 48px !important;
        margin-bottom: 20px !important;
    }

    /* Remove print button styling complexity */
    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.875rem !important;
        border-radius: 0.2rem !important;
    }

    /* DataTables integration styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin-left: 2px;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        color: white !important;
        border-color: #007bff;
    }

    /* Override any remaining complex styling */
    * {
        box-shadow: none !important;
    }

    .card,
    .modern-table {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>

<!-- Fix for DataTable reinitialization error -->
<script>
$(document).ready(function() {
    // Check if DataTable is already initialized and destroy it first
    if ($.fn.DataTable.isDataTable('#taxReportTable')) {
        $('#taxReportTable').DataTable().destroy();
    }
    
    // Now initialize DataTable safely
    if ($('#taxReportTable tbody tr').length > 1) {
        $('#taxReportTable').DataTable({
            "order": [[1, "desc"]], // Sort by date descending
            "pageLength": 25,
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "emptyTable": "No tax exempt donations found"
            },
            "columnDefs": [
                { "orderable": false, "targets": [10] }, // Disable sorting for Action column
                { "className": "text-center", "targets": [0, 10] }
            ],
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        });
    }
});

// Alternative solution - More robust approach
$(document).ready(function() {
    function initializeDataTable() {
        // Check if table exists and has data
        if ($('#taxReportTable').length && $('#taxReportTable tbody tr').length > 1) {
            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#taxReportTable')) {
                // If already initialized, just return the existing instance
                return $('#taxReportTable').DataTable();
            } else {
                // Initialize new DataTable
                return $('#taxReportTable').DataTable({
                    "order": [[1, "desc"]],
                    "pageLength": 25,
                    "responsive": true,
                    "language": {
                        "search": "Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "emptyTable": "No tax exempt donations found"
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": [10] },
                        { "className": "text-center", "targets": [0, 10] }
                    ]
                });
            }
        }
        return null;
    }
    
    // Initialize the DataTable
    var table = initializeDataTable();
});

// If you need to reinitialize DataTable (for example, after AJAX updates)
function reinitializeDataTable() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#taxReportTable')) {
        $('#taxReportTable').DataTable().destroy();
    }
    
    // Clear the table HTML to reset it
    $('#taxReportTable').empty();
    
    // Add your table headers back
    $('#taxReportTable').html(`
        <thead class="table-primary">
            <tr>
                <th class="text-center">#</th>
                <th><i class="mdi mdi-calendar me-1"></i>Date</th>
                <th><i class="mdi mdi-barcode me-1"></i>Reference</th>
                <th><i class="mdi mdi-account me-1"></i>Donor Name</th>
                <th><i class="mdi mdi-card-account-details me-1"></i>IC Number</th>
                <th><i class="mdi mdi-phone me-1"></i>Mobile</th>
                <th><i class="mdi mdi-email me-1"></i>Email</th>
                <th><i class="mdi mdi-gift me-1"></i>Type</th>
                <th><i class="mdi mdi-currency-usd me-1"></i>Amount</th>
                <th><i class="mdi mdi-credit-card me-1"></i>Payment</th>
                <th class="text-center"><i class="mdi mdi-cog me-1"></i>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Your data rows will go here -->
        </tbody>
    `);
    
    // Reinitialize DataTable
    $('#taxReportTable').DataTable({
        "order": [[1, "desc"]],
        "pageLength": 25,
        "responsive": true,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries", 
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "emptyTable": "No tax exempt donations found"
        },
        "columnDefs": [
            { "orderable": false, "targets": [10] },
            { "className": "text-center", "targets": [0, 10] }
        ]
    });
}

// For AJAX reloading without reinitializing
function reloadDataTableData() {
    if ($.fn.DataTable.isDataTable('#taxReportTable')) {
        $('#taxReportTable').DataTable().ajax.reload();
    }
}
</script>

<!-- Alternative: Simple solution for existing code -->
<script>
// Replace your existing DataTable initialization with this:
$(document).ready(function() {
    // Simple check and destroy approach
    try {
        if ($('#taxReportTable').hasClass('dataTable')) {
            $('#taxReportTable').DataTable().destroy();
        }
    } catch (e) {
        // Table wasn't initialized, continue
    }
    
    // Initialize DataTable
    if ($('#taxReportTable tbody tr').length > 1) {
        $('#taxReportTable').DataTable({
            "order": [[1, "desc"]],
            "pageLength": 25,
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "emptyTable": "No tax exempt donations found"
            },
            "columnDefs": [
                { "orderable": false, "targets": [10] },
                { "className": "text-center", "targets": [0, 10] }
            ]
        });
    }
});
// // Simplified print function
// function printReport() {
//     window.print();
// }

// Simplified export function
function exportToExcel() {
    const table = document.getElementById('taxReportTable');
    if (typeof XLSX !== 'undefined') {
        const wb = XLSX.utils.table_to_book(table, {
            sheet: "Tax Exempt Report"
        });
        const filename = 'Tax_Exempt_Report_' + new Date().toISOString().slice(0, 10) + '.xlsx';
        XLSX.writeFile(wb, filename);
        
        // Simple alert instead of toastr
        alert('Excel file downloaded successfully!');
    }
}

</script>

<!-- Include XLSX library for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>