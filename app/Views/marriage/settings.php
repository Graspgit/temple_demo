<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Registration Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .settings-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .settings-card:hover {
            transform: translateY(-2px);
        }

        .settings-card-header {
            background: #f8f9fa;
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
        }

        .settings-card-body {
            padding: 25px;
        }

        .tab-content {
            padding: 0;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 5px;
            padding: 12px 20px;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .nav-pills .nav-link:hover {
            background: #e9ecef;
            color: #495057;
        }

        .nav-pills .nav-link.active:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .language-tabs {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .language-tabs .nav-link {
            border-radius: 8px;
            margin: 2px;
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .slot-item, .venue-item, .category-item, .service-item {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .slot-item:hover, .venue-item:hover, .category-item:hover, .service-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .item-controls {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .item-controls .btn {
            margin-left: 5px;
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .time-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .time-input-group input {
            width: 120px;
        }

        .add-item-btn {
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            color: #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .add-item-btn:hover {
            background: #f8f9ff;
            border-color: #5a6fd8;
        }

        .settings-form .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .settings-form .form-control, .settings-form .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px;
            transition: border-color 0.3s ease;
        }

        .settings-form .form-control:focus, .settings-form .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .blocked-date-item {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: between;
            align-items: center;
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
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-0">
                        <i class="fas fa-cog"></i> Marriage Registration Settings
                    </h1>
                    <p class="mb-0 mt-2">Configure slots, venues, categories, and system settings</p>
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
        <div class="row">
            <!-- Navigation Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Settings Categories
                        </h5>
                    </div>
                    <div class="settings-card-body">
                        <ul class="nav nav-pills flex-column" id="settingsNav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#general" data-bs-toggle="pill">
                                    <i class="fas fa-cog"></i> General Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#slots" data-bs-toggle="pill">
                                    <i class="fas fa-clock"></i> Time Slots
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#venues" data-bs-toggle="pill">
                                    <i class="fas fa-building"></i> Venues
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#categories" data-bs-toggle="pill">
                                    <i class="fas fa-tags"></i> Marriage Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#services" data-bs-toggle="pill">
                                    <i class="fas fa-concierge-bell"></i> Additional Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#blocked-dates" data-bs-toggle="pill">
                                    <i class="fas fa-ban"></i> Blocked Dates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#languages" data-bs-toggle="pill">
                                    <i class="fas fa-language"></i> Language Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="tab-content">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog"></i> General Settings
                                </h5>
                            </div>
                            <div class="settings-card-body">
                                <form class="settings-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Registration Fee (RM)</label>
                                            <input type="number" class="form-control" value="50.00" step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Advance Booking Days</label>
                                            <input type="number" class="form-control" value="90">
                                            <div class="form-text">Maximum days in advance for booking</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Minimum Bride Age</label>
                                            <input type="number" class="form-control" value="18">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Minimum Groom Age</label>
                                            <input type="number" class="form-control" value="21">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Advance Payment (%)</label>
                                            <input type="number" class="form-control" value="30" min="0" max="100">
                                            <div class="form-text">Minimum advance payment percentage</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cancellation Charges (RM)</label>
                                            <input type="number" class="form-control" value="25.00" step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Late Cancellation Charges (RM)</label>
                                            <input type="number" class="form-control" value="50.00" step="0.01">
                                            <div class="form-text">Within 7 days of ceremony</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Max File Size (MB)</label>
                                            <input type="number" class="form-control" value="5">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Allowed File Types</label>
                                            <input type="text" class="form-control" value="jpg,jpeg,png,pdf,doc,docx">
                                            <div class="form-text">Comma-separated file extensions</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="allowSameDayBooking">
                                                <label class="form-check-label" for="allowSameDayBooking">
                                                    Allow Same Day Booking
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="requireWitnesses" checked>
                                                <label class="form-check-label" for="requireWitnesses">
                                                    Require Witness Details
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-custom">
                                        <i class="fas fa-save"></i> Save General Settings
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div class="tab-pane fade" id="slots">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-clock"></i> Time Slots Management
                                </h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="add-item-btn" onclick="addNewSlot()">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <h6>Add New Time Slot</h6>
                                </div>

                                <div id="slotsContainer">
                                    <!-- Existing Slots -->
                                    <div class="slot-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editSlot(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSlot(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Slot Name</label>
                                                <input type="text" class="form-control" value="Morning Slot 1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Time Range</label>
                                                <div class="time-input-group">
                                                    <input type="time" class="form-control" value="09:00">
                                                    <span>to</span>
                                                    <input type="time" class="form-control" value="09:30">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Max Bookings</label>
                                                <input type="number" class="form-control" value="3" min="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slot-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editSlot(2)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSlot(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Slot Name</label>
                                                <input type="text" class="form-control" value="Morning Slot 2">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Time Range</label>
                                                <div class="time-input-group">
                                                    <input type="time" class="form-control" value="09:30">
                                                    <span>to</span>
                                                    <input type="time" class="form-control" value="10:00">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Max Bookings</label>
                                                <input type="number" class="form-control" value="3" min="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slot-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editSlot(3)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSlot(3)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Slot Name</label>
                                                <input type="text" class="form-control" value="Evening Slot 1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Time Range</label>
                                                <div class="time-input-group">
                                                    <input type="time" class="form-control" value="17:00">
                                                    <span>to</span>
                                                    <input type="time" class="form-control" value="17:30">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Max Bookings</label>
                                                <input type="number" class="form-control" value="2" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-custom mt-3" onclick="saveSlots()">
                                    <i class="fas fa-save"></i> Save All Slots
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Venues -->
                    <div class="tab-pane fade" id="venues">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-building"></i> Venues Management
                                </h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="add-item-btn" onclick="addNewVenue()">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <h6>Add New Venue</h6>
                                </div>

                                <div id="venuesContainer">
                                    <!-- Existing Venues -->
                                    <div class="venue-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editVenue(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteVenue(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Language Tabs -->
                                        <div class="language-tabs">
                                            <ul class="nav nav-pills nav-fill">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#venue1-en" data-bs-toggle="pill">English</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#venue1-ta" data-bs-toggle="pill">Tamil</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#venue1-zh" data-bs-toggle="pill">Chinese</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#venue1-hi" data-bs-toggle="pill">Hindi</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="venue1-en">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Venue Name (English)</label>
                                                        <input type="text" class="form-control" value="Simple Hall">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Capacity</label>
                                                        <input type="number" class="form-control" value="50">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Description (English)</label>
                                                        <textarea class="form-control" rows="2">Basic wedding hall with essential facilities</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="venue1-ta">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Venue Name (Tamil)</label>
                                                        <input type="text" class="form-control" value="எளிய மண்டபம்">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Description (Tamil)</label>
                                                        <textarea class="form-control" rows="2">அடிப்படை திருமண மண்டபம்</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="venue1-zh">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Venue Name (Chinese)</label>
                                                        <input type="text" class="form-control" value="简单礼堂">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Description (Chinese)</label>
                                                        <textarea class="form-control" rows="2">基本婚礼大厅</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="venue1-hi">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Venue Name (Hindi)</label>
                                                        <input type="text" class="form-control" value="सरल हॉल">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Description (Hindi)</label>
                                                        <textarea class="form-control" rows="2">बुनियादी शादी हॉल</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Base Price (RM)</label>
                                                <input type="number" class="form-control" value="500.00" step="0.01">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Additional Charges (RM)</label>
                                                <input type="number" class="form-control" value="100.00" step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- More venues would be similar -->
                                </div>

                                <button type="button" class="btn btn-custom mt-3" onclick="saveVenues()">
                                    <i class="fas fa-save"></i> Save All Venues
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Marriage Categories -->
                    <div class="tab-pane fade" id="categories">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-tags"></i> Marriage Categories
                                </h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="add-item-btn" onclick="addNewCategory()">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <h6>Add New Category</h6>
                                </div>

                                <div id="categoriesContainer">
                                    <!-- Existing Categories -->
                                    <div class="category-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editCategory(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Language Tabs -->
                                        <div class="language-tabs">
                                            <ul class="nav nav-pills nav-fill">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#cat1-en" data-bs-toggle="pill">English</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#cat1-ta" data-bs-toggle="pill">Tamil</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#cat1-zh" data-bs-toggle="pill">Chinese</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#cat1-hi" data-bs-toggle="pill">Hindi</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="cat1-en">
                                                <div class="mb-3">
                                                    <label class="form-label">Category Name (English)</label>
                                                    <input type="text" class="form-control" value="Hindu Marriage">
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="cat1-ta">
                                                <div class="mb-3">
                                                    <label class="form-label">Category Name (Tamil)</label>
                                                    <input type="text" class="form-control" value="இந்து திருமணம்">
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="cat1-zh">
                                                <div class="mb-3">
                                                    <label class="form-label">Category Name (Chinese)</label>
                                                    <input type="text" class="form-control" value="印度教婚礼">
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="cat1-hi">
                                                <div class="mb-3">
                                                    <label class="form-label">Category Name (Hindi)</label>
                                                    <input type="text" class="form-control" value="हिंदू विवाह">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Base Fee (RM)</label>
                                                <input type="number" class="form-control" value="200.00" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-custom mt-3" onclick="saveCategories()">
                                    <i class="fas fa-save"></i> Save All Categories
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Services -->
                    <div class="tab-pane fade" id="services">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-concierge-bell"></i> Additional Services
                                </h5>
                            </div>
                            <div class="settings-card-body">
                                <div class="add-item-btn" onclick="addNewService()">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <h6>Add New Service</h6>
                                </div>

                                <div id="servicesContainer">
                                    <!-- Existing Services -->
                                    <div class="service-item">
                                        <div class="item-controls">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editService(1)">
                                                <i class="