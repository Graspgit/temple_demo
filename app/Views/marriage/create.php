<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Registration Management</title>
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
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card.primary {
            border-color: #007bff;
        }
        
        .stats-card.success {
            border-color: #28a745;
        }
        
        .stats-card.warning {
            border-color: #ffc107;
        }
        
        .stats-card.danger {
            border-color: #dc3545;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stats-icon {
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        
        .data-table-wrapper {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .payment-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .payment-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .payment-partial {
            background-color: #ffeaa7;
            color: #2d3436;
        }
        
        .payment-paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .payment-refunded {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons .btn {
            margin: 2px;
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .quick-actions {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .couple-names {
            font-weight: 600;
            color: #495057;
        }
        
        .registration-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #007bff;
        }
        
        .date-slot {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .amount-display {
            font-weight: 600;
        }
        
        .amount-paid {
            color: #28a745;
        }
        
        .amount-pending {
            color: #dc3545;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .action-buttons .btn {
                padding: 3px 8px;
                font-size: 0.7rem;
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
                        <i class="fas fa-heart"></i> Marriage Registration Management
                    </h1>
                    <p class="mb-0 mt-2">Manage wedding registrations, schedules, and ceremonies</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/marriage/create" class="btn btn-light btn-lg">
                        <i class="fas fa-plus"></i> New Registration
                    </a>
                    <a href="/marriage/calendar" class="btn btn-outline-light btn-lg ms-2">
                        <i class="fas fa-calendar"></i> Calendar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card primary position-relative">
                    <div class="stats-number text-primary">45</div>
                    <div class="stats-label">Total Registrations</div>
                    <i class="fas fa-heart stats-icon text-primary"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success position-relative">
                    <div class="stats-number text-success">32</div>
                    <div class="stats-label">Completed</div>
                    <i class="fas fa-check-circle stats-icon text-success"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card warning position-relative">
                    <div class="stats-number text-warning">8</div>
                    <div class="stats-label">Pending</div>
                    <i class="fas fa-clock stats-icon text-warning"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card danger position-relative">
                    <div class="stats-number text-danger">5</div>
                    <div class="stats-label">This Week</div>
                    <i class="fas fa-calendar-week stats-icon text-danger"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h5 class="mb-0">Quick Actions</h5>
                    <p class="text-muted mb-0">Frequently used actions for marriage registration management</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="printReport()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="showSettings()">
                            <i class="fas fa-cog"></i> Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateRange">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Payment Status</label>
                    <select class="form-select" id="paymentFilter">
                        <option value="">All Payments</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Venue</label>
                    <select class="form-select" id="venueFilter">
                        <option value="">All Venues</option>
                        <option value="1">Simple Hall</option>
                        <option value="2">Classic Hall</option>
                        <option value="3">Premium Hall</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchBox" placeholder="Search by registration number, couple names, phone...">
                </div>
                <div class="col-lg-6 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <button class="btn btn-outline-secondary" onclick="clearFilters()">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Marriage Registrations</h5>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Show:</span>
                    <select class="form-select form-select-sm" style="width: auto;" id="recordsPerPage">
                        <option value="10">10</option>
                        <option value="25"