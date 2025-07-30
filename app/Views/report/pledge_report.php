<style>
    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .summary-item {
        text-align: center;
        padding: 15px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        margin: 5px;
    }
    
    .summary-number {
        font-size: 24px;
        font-weight: bold;
        display: block;
    }
    
    .summary-label {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 5px;
    }
    
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
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
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>PLEDGE REPORT<small>Reports / <b>Pledge Report</b></small></h2>
        </div>
        
        <!-- Summary Cards -->
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="summary-card">
                    <h4 style="margin-bottom: 20px; text-align: center;">Pledge Summary</h4>
                    <div class="row" id="summary-section">
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="total-pledges">0</span>
                                <div class="summary-label">Total Pledges</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="active-pledges">0</span>
                                <div class="summary-label">Active Pledges</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="completed-pledges">0</span>
                                <div class="summary-label">Completed Pledges</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="total-amount">RM 0</span>
                                <div class="summary-label">Total Pledge Amount</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="collected-amount">RM 0</span>
                                <div class="summary-label">Total Collected</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="summary-item">
                                <span class="summary-number" id="balance-amount">RM 0</span>
                                <div class="summary-label">Total Balance</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Filter Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Filter Options</h2>
                    </div>
                    <div class="body">
                        <div class="filter-section">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" id="from_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" id="to_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select id="status_filter" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="name_filter" class="form-control" placeholder="Enter name to search">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button id="apply_filters" class="btn btn-primary waves-effect">
                                        <i class="material-icons">search</i> Apply Filters
                                    </button>
                                    <button id="reset_filters" class="btn btn-secondary waves-effect">
                                        <i class="material-icons">refresh</i> Reset
                                    </button>
                                    <button id="export_report" class="btn btn-success waves-effect">
                                        <i class="material-icons">get_app</i> Export CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Pledge Records</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="pledge_table">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Pledge Amount</th>
                                        <th>Collected</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Entries</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via DataTable -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Load summary data
    loadSummary();
    
    // Initialize DataTable
    var table = $('#pledge_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url(); ?>/report/get_pledge_report_data",
            "type": "POST",
            "data": function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.status_filter = $('#status_filter').val();
                d.name_filter = $('#name_filter').val();
            }
        },
        "columns": [
            {
                "data": null,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { "data": "name" },
            { "data": "mobile" },
            { "data": "email_id" },
            {
                "data": "pledge_amount",
                "render": function(data, type, row) {
                    return "RM " + parseFloat(data || 0).toFixed(2);
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    var collected = parseFloat(row.pledge_amount || 0) - parseFloat(row.balance_amt || 0);
                    return "RM " + collected.toFixed(2);
                }
            },
            {
                "data": "balance_amt",
                "render": function(data, type, row) {
                    return "RM " + parseFloat(data || 0).toFixed(2);
                }
            },
            {
                "data": "status",
                "render": function(data, type, row) {
                    var badgeClass = "";
                    if (data === "Completed") {
                        badgeClass = "status-completed";
                    } else if (data === "Pending") {
                        badgeClass = "status-pending";
                    } else {
                        badgeClass = "status-active";
                    }
                    return '<span class="status-badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            { "data": "total_entries" },
            {
                "data": "created_date",
                "render": function(data, type, row) {
                    return new Date(data).toLocaleDateString('en-GB');
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<a href="<?php echo base_url(); ?>/report/pledge_detail/' + row.id + '" class="btn btn-sm btn-info waves-effect">View Details</a>';
                }
            }
        ],
        "order": [[9, "desc"]],
        "pageLength": 25,
        "responsive": true,
        "language": {
            "emptyTable": "No pledge records found"
        }
    });

    // Apply filters
    $('#apply_filters').click(function() {
        table.ajax.reload();
        loadSummary();
    });

    // Reset filters
    $('#reset_filters').click(function() {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#status_filter').val('');
        $('#name_filter').val('');
        table.ajax.reload();
        loadSummary();
    });

    // Export report
    $('#export_report').click(function() {
        var params = new URLSearchParams({
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
            status_filter: $('#status_filter').val(),
            name_filter: $('#name_filter').val()
        });
        
        window.location.href = '<?php echo base_url(); ?>/report/export_pledge_report?' + params.toString();
    });

    // Load summary data
    function loadSummary() {
        $.ajax({
            url: "<?php echo base_url(); ?>/report/get_pledge_summary",
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#total-pledges').text(data.total_pledges);
                $('#active-pledges').text(data.active_pledges);
                $('#completed-pledges').text(data.completed_pledges);
                $('#total-amount').text('RM ' + parseFloat(data.total_pledge_amount || 0).toFixed(2));
                $('#collected-amount').text('RM ' + parseFloat(data.total_collected || 0).toFixed(2));
                $('#balance-amount').text('RM ' + parseFloat(data.total_balance || 0).toFixed(2));
            },
            error: function() {
                console.log('Error loading summary data');
            }
        });
    }
});
</script>