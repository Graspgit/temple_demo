<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Registration Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .report-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
        }

        .report-card-header {
            background: #f8f9fa;
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
        }

        .report-card-body {
            padding: 25px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
            transition: transform 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-3px);
        }

        .stat-box.primary {
            border-color: #007bff;
        }

        .stat-box.success {
            border-color: #28a745;
        }

        .stat-box.warning {
            border-color: #ffc107;
        }

        .stat-box.danger {
            border-color: #dc3545;
        }

        .stat-box.info {
            border-color: #17a2b8;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .stat-change {
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .stat-change.positive {
            background: #d4edda;
            color: #155724;
        }

        .stat-change.negative {
            background: #f8d7da;
            color: #721c24;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
        }

        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .report-tabs .nav-link {
            border-radius: 10px;
            margin: 5px;
            padding: 12px 20px;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .report-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .export-buttons .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .export-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Marriage Registration Reports
                    </h1>
                    <p class="mb-0 mt-2">Comprehensive analytics and reporting for marriage registrations</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/marriage" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to Registrations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Report Summary -->
        <div class="summary-card">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="summary-item">
                        <div class="summary-value">0</div>
                        <div class="summary-label">Total Registrations</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="summary-item">
                        <div class="summary-value">0</div>
                        <div class="summary-label">Total Revenue</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="summary-item">
                        <div class="summary-value">0</div>
                        <div class="summary-label">Collected Amount</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="summary-item">
                        <div class="summary-value">0%</div>
                        <div class="summary-label">Collection Rate</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Report Type</label>
                    <select class="form-select" id="reportType">
                        <option value="summary">Summary Report</option>
                        <option value="detailed">Detailed Report</option>
                        <option value="financial">Financial Report</option>
                        <option value="venue">Venue Analysis</option>
                        <option value="monthly">Monthly Report</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" id="fromDate" value="2024-03-01">
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" id="toDate" value="2024-03-31">
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3 d-flex align-items-end">
                    <button class="btn btn-custom w-100" onclick="generateReport()">
                        <i class="fas fa-sync"></i> Generate Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-box primary">
                <div class="stat-number text-primary"><?php echo (isset($tot_cnts["cur_mon"]["month"])?number_format($tot_cnts["cur_mon"]["month"]):0); ?></div>
                <div class="stat-label">This Month</div>
                <div class="stat-change positive">% from last month</div>
            </div>
            <div class="stat-box success">
                <div class="stat-number text-success">38</div>
                <div class="stat-label">Completed</div>
                <div class="stat-change positive">+0% completion rate</div>
            </div>
            <div class="stat-box warning">
                <div class="stat-number text-warning">5</div>
                <div class="stat-label">Pending</div>
                <div class="stat-change negative">0% from last month</div>
            </div>
            <div class="stat-box danger">
                <div class="stat-number text-danger">2</div>
                <div class="stat-label">Cancelled</div>
                <div class="stat-change negative">0% cancellation rate</div>
            </div>
            <div class="stat-box info">
                <div class="stat-number text-info">RM </div>
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-change positive">0% from last month</div>
            </div>
        </div>

        <!-- Report Tabs -->
        <div class="report-card">
            <div class="report-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detailed Analytics</h5>
                    <div class="export-buttons">
                        <button class="btn btn-outline-success btn-sm" onclick="exportExcel()">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="exportPDF()">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="printReport()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="emailReport()">
                            <i class="fas fa-envelope"></i> Email
                        </button>
                    </div>
                </div>
            </div>
            <div class="report-card-body">
                <ul class="nav nav-pills report-tabs" id="reportTabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#overview" data-bs-toggle="pill">
                            <i class="fas fa-chart-pie"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#registrations" data-bs-toggle="pill">
                            <i class="fas fa-list"></i> Registrations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#financial" data-bs-toggle="pill">
                            <i class="fas fa-money-bill"></i> Financial
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#venues" data-bs-toggle="pill">
                            <i class="fas fa-building"></i> Venues
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trends" data-bs-toggle="pill">
                            <i class="fas fa-chart-line"></i> Trends
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-4">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Registration Status Distribution</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="statusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Monthly Registration Trends</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="trendChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Marriage Categories</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="categoryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Payment Status</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="paymentChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registrations Tab -->
                    <div class="tab-pane fade" id="registrations">
                        <div class="table-responsive">
                            <table class="table table-hover" id="registrationsTable">
                                <thead>
                                    <tr>
                                        <th>Reg. No.</th>
                                        <th>Couple Names</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                   <?php foreach($registrations as $iter){ ?>
                                   <tr>
                                        <th>
                                            <?php echo $iter["registration_number"]; ?></th>
                                        <th><?php echo $iter["bride_name"]; ?></th>
                                        <th><?php echo $iter["registration_date"]; ?></th>
                                        <th><?php echo $iter["venue_name"]; ?></th>
                                        <th><?php echo $iter["category_name"]; ?></th>
                                        <th><?php echo number_format($iter["total_amount"]); ?></th>
                                        <th><?php echo ($iter["registration_status"]==1?"Active":"Inactive"); ?></th>
                                        <th><?php echo number_format($iter["paid_amount"],2); ?></th>
                                    </tr>
                                   <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Financial Tab -->
                    <div class="tab-pane fade" id="financial">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="chart-container">
                                    <h6>Revenue Analysis</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="chart-container">
                                    <h6>Payment Methods</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="paymentMethodChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Total Revenue</th>
                                                <th>Collected</th>
                                                <th>Pending</th>
                                                <th>Collection Rate</th>
                                                <th>Growth</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>March 2024</td>
                                                <td>RM 36,250.00</td>
                                                <td>RM 34,100.00</td>
                                                <td>RM 2,150.00</td>
                                                <td>94.1%</td>
                                                <td class="text-success">+15.3%</td>
                                            </tr>
                                            <tr>
                                                <td>February 2024</td>
                                                <td>RM 31,450.00</td>
                                                <td>RM 31,450.00</td>
                                                <td>RM 0.00</td>
                                                <td>100%</td>
                                                <td class="text-success">+8.7%</td>
                                            </tr>
                                            <tr>
                                                <td>January 2024</td>
                                                <td>RM 28,950.00</td>
                                                <td>RM 28,950.00</td>
                                                <td>RM 0.00</td>
                                                <td>100%</td>
                                                <td class="text-danger">-2.1%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Venues Tab -->
                    <div class="tab-pane fade" id="venues">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Venue Utilization</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="venueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Venue Revenue</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="venueRevenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Venue</th>
                                                <th>Bookings</th>
                                                <th>Utilization</th>
                                                <th>Revenue</th>
                                                <th>Avg. Booking Value</th>
                                                <th>Popular Slots</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Premium Hall</td>
                                                <td>18</td>
                                                <td>75%</td>
                                                <td>RM 32,400.00</td>
                                                <td>RM 1,800.00</td>
                                                <td>Morning Slot 2, Evening Slot 1</td>
                                            </tr>
                                            <tr>
                                                <td>Classic Hall</td>
                                                <td>20</td>
                                                <td>83%</td>
                                                <td>RM 29,000.00</td>
                                                <td>RM 1,450.00</td>
                                                <td>Morning Slot 1, Morning Slot 3</td>
                                            </tr>
                                            <tr>
                                                <td>Simple Hall</td>
                                                <td>12</td>
                                                <td>50%</td>
                                                <td>RM 11,400.00</td>
                                                <td>RM 950.00</td>
                                                <td>Evening Slot 2</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trends Tab -->
                    <div class="tab-pane fade" id="trends">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-container">
                                    <h6>Marriage Registration Trends (Last 12 Months)</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="yearlyTrendChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Peak Booking Days</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="dayChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h6>Seasonal Trends</h6>
                                    <div class="chart-wrapper">
                                        <canvas id="seasonChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#registrationsTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                responsive: true
            });

            // Initialize Charts
            initializeCharts();
        });

        // Chart initialization
        function initializeCharts() {
            // Status Distribution Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Confirmed', 'Pending', 'Cancelled'],
                    datasets: [{
                        data: [38, 5, 3, 2],
                        backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        label: 'Registrations',
                        data: [35, 38, 45],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: ['Hindu', 'Buddhist', 'Sikh', 'Inter-Religious', 'Christian'],
                    datasets: [{
                        label: 'Registrations',
                        data: [20, 12, 8, 6, 2],
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Payment Chart
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'pie',
                data: {
                    labels: ['Paid', 'Partial', 'Pending'],
                    datasets: [{
                        data: [38, 7, 3],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        label: 'Total Revenue',
                        data: [28950, 31450, 36250],
                        backgroundColor: '#667eea'
                    }, {
                        label: 'Collected',
                        data: [28950, 31450, 34100],
                        backgroundColor: '#28a745'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'RM ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Payment Method Chart
            const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
            new Chart(paymentMethodCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Card', 'Online', 'Bank Transfer'],
                    datasets: [{
                        data: [35, 25, 30, 10],
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Venue Chart
            const venueCtx = document.getElementById('venueChart').getContext('2d');
            new Chart(venueCtx, {
                type: 'bar',
                data: {
                    labels: ['Premium Hall', 'Classic Hall', 'Simple Hall'],
                    datasets: [{
                        label: 'Bookings',
                        data: [18, 20, 12],
                        backgroundColor: ['#667eea', '#764ba2', '#36a2eb']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Venue Revenue Chart
            const venueRevenueCtx = document.getElementById('venueRevenueChart').getContext('2d');
            new Chart(venueRevenueCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Premium Hall', 'Classic Hall', 'Simple Hall'],
                    datasets: [{
                        data: [32400, 29000, 11400],
                        backgroundColor: ['#667eea', '#764ba2', '#36a2eb']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': RM ' + context.parsed.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Yearly Trend Chart
            const yearlyTrendCtx = document.getElementById('yearlyTrendChart').getContext('2d');
            new Chart(yearlyTren