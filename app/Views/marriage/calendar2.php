<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Registration Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .calendar-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-nav button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .calendar-nav button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e0e0e0;
            padding: 1px;
        }
        
        .calendar-day-header {
            background: #f8f9fa;
            padding: 15px 5px;
            text-align: center;
            font-weight: bold;
            color: #666;
        }
        
        .calendar-day {
            background: white;
            min-height: 120px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .calendar-day:hover {
            background: #f8f9fa;
        }
        
        .calendar-day.other-month {
            background: #f8f9fa;
            color: #ccc;
        }
        
        .calendar-day.blocked {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .calendar-day.today {
            background: #e3f2fd;
            border: 2px solid #2196f3;
        }
        
        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .slot-info {
            font-size: 10px;
            padding: 2px 4px;
            margin: 1px 0;
            border-radius: 3px;
            text-align: center;
        }
        
        .slot-available {
            background: #e8f5e8;
            color: #2e7d2e;
        }
        
        .slot-partial {
            background: #fff3cd;
            color: #856404;
        }
        
        .slot-full {
            background: #f8d7da;
            color: #721c24;
        }
        
        .slot-blocked {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .booking-form {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-top: 20px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }
        
        .step-indicator .progress-line {
            position: absolute;
            top: 20px;
            left: 0;
            height: 2px;
            background: #4caf50;
            z-index: 2;
            transition: width 0.3s ease;
        }
        
        .step {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 3;
            position: relative;
        }
        
        .step.active {
            border-color: #2196f3;
            background: #2196f3;
            color: white;
        }
        
        .step.completed {
            border-color: #4caf50;
            background: #4caf50;
            color: white;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .venue-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .venue-card:hover {
            border-color: #2196f3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .venue-card.selected {
            border-color: #4caf50;
            background: #f8fff8;
        }
        
        .slot-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 8px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .slot-card:hover {
            border-color: #2196f3;
        }
        
        .slot-card.selected {
            border-color: #4caf50;
            background: #f8fff8;
        }
        
        .slot-card.full {
            border-color: #f44336;
            background: #ffebee;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .price-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
        }
        
        .price-row.total {
            border-top: 2px solid #dee2e6;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 12px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
        }
        
        .availability-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        @media (max-width: 768px) {
            .calendar-grid {
                font-size: 12px;
            }
            
            .calendar-day {
                min-height: 80px;
                padding: 4px;
            }
            
            .slot-info {
                font-size: 8px;
                padding: 1px 2px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Calendar Section -->
        <div class="row">
            <div class="col-12">
                <div class="calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-nav">
                            <button id="prevMonth" type="button">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <h3 id="currentMonthYear">March 2024</h3>
                            <button id="nextMonth" type="button">
                                <i class="fas fa-chevron-right"></i> Next
                            </button>
                        </div>
                        <p class="mb-0">Select a date to book marriage registration</p>
                    </div>
                    
                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Calendar days will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form Section -->
        <div class="row mt-4" id="bookingSection" style="display: none;">
            <div class="col-12">
                <div class="booking-form">
                    <div class="step-indicator">
                        <div class="progress-line" id="progressLine"></div>
                        <div class="step active" data-step="1">1</div>
                        <div class="step" data-step="2">2</div>
                        <div class="step" data-step="3">3</div>
                        <div class="step" data-step="4">4</div>
                    </div>

                    <form id="marriageRegistrationForm">
                        <!-- Step 1: Date & Slot Selection -->
                        <div class="form-section active" id="step1">
                            <h4 class="mb-4">Step 1: Select Date & Time Slot</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Selected Date</label>
                                        <input type="date" class="form-control" id="selectedDate" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Available Time Slots</label>
                                        <div id="availableSlots">
                                            <!-- Slots will be loaded dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Venue Selection -->
                        <div class="form-section" id="step2">
                            <h4 class="mb-4">Step 2: Select Venue</h4>
                            <div class="row" id="venueSelection">
                                <!-- Venues will be loaded dynamically -->
                            </div>
                        </div>

                        <!-- Step 3: Marriage Category -->
                        <div class="form-section" id="step3">
                            <h4 class="mb-4">Step 3: Marriage Category & Additional Services</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Marriage Category</label>
                                        <select class="form-select" id="marriageCategory" required>
                                            <option value="">Select Category</option>
                                            <option value="1">Hindu Marriage - RM 200.00</option>
                                            <option value="2">Buddhist Marriage - RM 200.00</option>
                                            <option value="3">Sikh Marriage - RM 200.00</option>
                                            <option value="4">Inter-Religious Marriage - RM 250.00</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Guest Count (Optional)</label>
                                        <input type="number" class="form-control" id="guestCount" min="1" max="500">
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Additional Services</h5>
                            <div class="row" id="additionalServices">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="photography" value="300">
                                        <label class="form-check-label" for="photography">
                                            Photography - RM 300.00
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="videography" value="500">
                                        <label class="form-check-label" for="videography">
                                            Videography - RM 500.00
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="decoration" value="200">
                                        <label class="form-check-label" for="decoration">
                                            Decoration - RM 200.00
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sound" value="150">
                                        <label class="form-check-label" for="sound">
                                            Sound System - RM 150.00
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="price-summary mt-4">
                                <h5>Price Summary</h5>
                                <div class="price-row">
                                    <span>Registration Fee:</span>
                                    <span>RM 50.00</span>
                                </div>
                                <div class="price-row">
                                    <span>Category Fee:</span>
                                    <span id="categoryFee">RM 0.00</span>
                                </div>
                                <div class="price-row">
                                    <span>Venue Cost:</span>
                                    <span id="venueCost">RM 0.00</span>
                                </div>
                                <div class="price-row">
                                    <span>Additional Services:</span>
                                    <span id="servicesCost">RM 0.00</span>
                                </div>
                                <div class="price-row total">
                                    <span>Total Amount:</span>
                                    <span id="totalAmount">RM 50.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Couple Details -->
                        <div class="form-section" id="step4">
                            <h4 class="mb-4">Step 4: Couple & Witness Details</h4>
                            
                            <!-- Bride Details -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-female text-pink"></i> Bride Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="brideName" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth *</label>
                                            <input type="date" class="form-control" id="brideDob" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nationality *</label>
                                            <select class="form-select" id="brideNationality" required>
                                                <option value="">Select Nationality</option>
                                                <option value="Malaysian">Malaysian</option>
                                                <option value="Singaporean">Singaporean</option>
                                                <option value="Indian">Indian</option>
                                                <option value="Chinese">Chinese</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">IC/Passport Number *</label>
                                            <input type="text" class="form-control" id="brideIc" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Father's Name *</label>
                                            <input type="text" class="form-control" id="brideFather" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mother's Name *</label>
                                            <input type="text" class="form-control" id="brideMother" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="bridePhone" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" id="brideEmail">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Religion *</label>
                                            <select class="form-select" id="brideReligion" required>
                                                <option value="">Select Religion</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddhist">Buddhist</option>
                                                <option value="Sikh">Sikh</option>
                                                <option value="Christian">Christian</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Occupation</label>
                                            <input type="text" class="form-control" id="brideOccupation">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Address *</label>
                                            <textarea class="form-control" id="brideAddress" rows="3" required></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Photo</label>
                                            <input type="file" class="form-control" id="bridePhoto" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Groom Details -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-male text-blue"></i> Groom Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="groomName" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth *</label>
                                            <input type="date" class="form-control" id="groomDob" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nationality *</label>
                                            <select class="form-select" id="groomNationality" required>
                                                <option value="">Select Nationality</option>
                                                <option value="Malaysian">Malaysian</option>
                                                <option value="Singaporean">Singaporean</option>
                                                <option value="Indian">Indian</option>
                                                <option value="Chinese">Chinese</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">IC/Passport Number *</label>
                                            <input type="text" class="form-control" id="groomIc" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Father's Name *</label>
                                            <input type="text" class="form-control" id="groomFather" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mother's Name *</label>
                                            <input type="text" class="form-control" id="groomMother" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="groomPhone" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" id="groomEmail">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Religion *</label>
                                            <select class="form-select" id="groomReligion" required>
                                                <option value="">Select Religion</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddhist">Buddhist</option>
                                                <option value="Sikh">Sikh</option>
                                                <option value="Christian">Christian</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Occupation</label>
                                            <input type="text" class="form-control" id="groomOccupation">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Address *</label>
                                            <textarea class="form-control" id="groomAddress" rows="3" required></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Photo</label>
                                            <input type="file" class="form-control" id="groomPhoto" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Witness Details -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-users text-green"></i> Witness Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Witness 1</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" class="form-control" id="witness1Name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">IC Number *</label>
                                                <input type="text" class="form-control" id="witness1Ic" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="witness1Phone">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Witness 2</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" class="form-control" id="witness2Name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">IC Number *</label>
                                                <input type="text" class="form-control" id="witness2Ic" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="witness2Phone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Additional Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Special Requirements</label>
                                        <textarea class="form-control" id="specialRequirements" rows="3" placeholder="Any special requirements or notes..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Document Upload</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small">Bride IC/Passport</label>
                                                <input type="file" class="form-control" id="brideIcFile" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small">Groom IC/Passport</label>
                                                <input type="file" class="form-control" id="groomIcFile" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small">JPN Form</label>
                                                <input type="file" class="form-control" id="jpnForm" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small">Other Documents</label>
                                                <input type="file" class="form-control" id="otherDocs" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-custom" id="nextBtn">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="fas fa-check"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Day Details Modal -->
    <div class="modal fade" id="dayDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-day"></i> Bookings for <span id="modalDate"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="dayDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-custom" id="bookThisDate">
                        <i class="fas fa-plus"></i> Book This Date
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calendar Variables
        let currentDate = new Date();
        let currentStep = 1;
        let selectedDate = null;
        let selectedSlot = null;
        let selectedVenue = null;
        let selectedCategory = null;

        // Sample data (in real application, this would come from API)
        const sampleSlots = [
            {id: 1, name: 'Morning Slot 1', start_time: '09:00', end_time: '09:30', max_bookings: 3},
            {id: 2, name: 'Morning Slot 2', start_time: '09:30', end_time: '10:00', max_bookings: 3},
            {id: 3, name: 'Morning Slot 3', start_time: '10:00', end_time: '10:30', max_bookings: 3},
            {id: 4, name: 'Evening Slot 1', start_time: '17:00', end_time: '17:30', max_bookings: 2},
            {id: 5, name: 'Evening Slot 2', start_time: '17:30', end_time: '18:00', max_bookings: 2}
        ];

        const sampleVenues = [
            {id: 1, name: 'Simple Hall', description: 'Basic wedding hall with essential facilities', capacity: 50, base_price: 500, additional_charges: 100},
            {id: 2, name: 'Classic Hall', description: 'Traditional wedding hall with enhanced facilities', capacity: 100, base_price: 1000, additional_charges: 200},
            {id: 3, name: 'Premium Hall', description: 'Luxury wedding hall with premium facilities', capacity: 150, base_price: 1500, additional_charges: 300}
        ];

        const sampleBookings = {
            '2024-03-15': {1: 2, 2: 1, 4: 2}, // slot_id: booking_count
            '2024-03-16': {1: 3, 3: 1},
            '2024-03-20': {2: 2, 4: 1}
        };

        const blockedDates = ['2024-03-25', '2024-03-31']; // Sample blocked dates

        // Initialize Calendar
        function initCalendar() {
            generateCalendar();
            updateMonthYearDisplay();
        }

        function generateCalendar() {
            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            // Add day headers
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                grid.appendChild(dayHeader);
            });

            // Get first day of month and number of days
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            // Generate calendar days
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const dayElement = createDayElement(date);
                grid.appendChild(dayElement);
            }
        }

        function createDayElement(date) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day';
            
            const dateStr = date.toISOString().split('T')[0];
            const isCurrentMonth = date.getMonth() === currentDate.getMonth();
            const isToday = dateStr === new Date().toISOString().split('T')[0];
            const isBlocked = blockedDates.includes(dateStr);
            const isPast = date < new Date().setHours(0,0,0,0);

            // Add classes
            if (!isCurrentMonth) dayDiv.classList.add('other-month');
            if (isToday) dayDiv.classList.add('today');
            if (isBlocked) dayDiv.classList.add('blocked');

            // Day number
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = date.getDate();
            dayDiv.appendChild(dayNumber);

            // Add slot information if current month and not blocked
            if (isCurrentMonth && !isBlocked && !isPast) {
                const bookings = sampleBookings[dateStr] || {};
                sampleSlots.forEach(slot => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'slot-info';
                    
                    const booked = bookings[slot.id] || 0;
                    const available = slot.max_bookings - booked;
                    
                    if (available === 0) {
                        slotDiv.classList.add('slot-full');
                        slotDiv.textContent = `${slot.start_time} - Full`;
                    } else if (available < slot.max_bookings) {
                        slotDiv.classList.add('slot-partial');
                        slotDiv.textContent = `${slot.start_time} - ${available} left`;
                    } else {
                        slotDiv.classList.add('slot-available');
                        slotDiv.textContent = `${slot.start_time} - Available`;
                    }
                    
                    dayDiv.appendChild(slotDiv);
                });
            }

            // Click handler
            if (isCurrentMonth && !isBlocked && !isPast) {
                dayDiv.style.cursor = 'pointer';
                dayDiv.addEventListener('click', () => selectDate(dateStr));
            } else {
                dayDiv.style.cursor = 'not-allowed';
                dayDiv.style.opacity = '0.5';
            }

            return dayDiv;
        }

        function selectDate(dateStr) {
            selectedDate = dateStr;
            document.getElementById('selectedDate').value = dateStr;
            
            // Show booking section
            document.getElementById('bookingSection').style.display = 'block';
            
            // Load available slots
            loadAvailableSlots(dateStr);
            
            // Scroll to booking section
            document.getElementById('bookingSection').scrollIntoView({behavior: 'smooth'});
        }

        function loadAvailableSlots(dateStr) {
            const slotsContainer = document.getElementById('availableSlots');
            slotsContainer.innerHTML = '';

            const bookings = sampleBookings[dateStr] || {};
            
            sampleSlots.forEach(slot => {
                const booked = bookings[slot.id] || 0;
                const available = slot.max_bookings - booked;
                
                const slotCard = document.createElement('div');
                slotCard.className = 'slot-card';
                if (available === 0) slotCard.classList.add('full');
                
                slotCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${slot.name}</strong><br>
                            <small>${slot.start_time} - ${slot.end_time}</small>
                        </div>
                        <div class="text-end">
                            <span class="availability-badge ${available === 0 ? 'bg-danger' : (available < slot.max_bookings ? 'bg-warning' : 'bg-success')} text-white">
                                ${available === 0 ? 'Full' : `${available} available`}
                            </span>
                        </div>
                    </div>
                `;

                if (available > 0) {
                    slotCard.addEventListener('click', () => selectSlot(slot));
                }

                slotsContainer.appendChild(slotCard);
            });
        }

        function selectSlot(slot) {
            // Remove previous selection
            document.querySelectorAll('.slot-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Select current slot
            event.target.closest('.slot-card').classList.add('selected');
            selectedSlot = slot;
        }

        function loadVenues() {
            const venueContainer = document.getElementById('venueSelection');
            venueContainer.innerHTML = '';

            sampleVenues.forEach(venue => {
                const venueCard = document.createElement('div');
                venueCard.className = 'col-md-6 col-lg-4';
                venueCard.innerHTML = `
                    <div class="venue-card" data-venue-id="${venue.id}">
                        <h6>${venue.name}</h6>
                        <p class="text-muted small">${venue.description}</p>
                        <div class="d-flex justify-content-between">
                            <small>Capacity: ${venue.capacity}</small>
                            <strong>RM ${(venue.base_price + venue.additional_charges).toFixed(2)}</strong>
                        </div>
                    </div>
                `;

                venueCard.addEventListener('click', () => selectVenue(venue, venueCard.querySelector('.venue-card')));
                venueContainer.appendChild(venueCard);
            });
        }

        function selectVenue(venue, element) {
            // Remove previous selection
            document.querySelectorAll('.venue-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Select current venue
            element.classList.add('selected');
            selectedVenue = venue;
            
            // Update price summary
            updatePriceSummary();
        }

        function updatePriceSummary() {
            let venueCost = 0;
            let categoryFee = 0;
            let servicesCost = 0;

            // Venue cost
            if (selectedVenue) {
                venueCost = selectedVenue.base_price + selectedVenue.additional_charges;
                document.getElementById('venueCost').textContent = `RM ${venueCost.toFixed(2)}`;
            }

            // Category fee
            const categorySelect = document.getElementById('marriageCategory');
            if (categorySelect.value) {
                switch (categorySelect.value) {
                    case '1':
                    case '2':
                    case '3':
                        categoryFee = 200;
                        break;
                    case '4':
                        categoryFee = 250;
                        break;
                }
                document.getElementById('categoryFee').textContent = `RM ${categoryFee.toFixed(2)}`;
            }

            // Additional services
            const serviceCheckboxes = document.querySelectorAll('#additionalServices input[type="checkbox"]:checked');
            serviceCheckboxes.forEach(checkbox => {
                servicesCost += parseFloat(checkbox.value);
            });
            document.getElementById('servicesCost').textContent = `RM ${servicesCost.toFixed(2)}`;

            // Total
            const total = 50 + venueCost + categoryFee + servicesCost; // 50 is registration fee
            document.getElementById('totalAmount').textContent = `RM ${total.toFixed(2)}`;
        }

        function updateMonthYearDisplay() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonthYear').textContent = 
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < 4) {
                    document.getElementById(`step${currentStep}`).classList.remove('active');
                    currentStep++;
                    document.getElementById(`step${currentStep}`).classList.add('active');
                    
                    updateStepIndicator();
                    updateNavigationButtons();
                    
                    // Load data for specific steps
                    if (currentStep === 2) {
                        loadVenues();
                    }
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                updateStepIndicator();
                updateNavigationButtons();
            }
        }

        function validateCurrentStep() {
            switch (currentStep) {
                case 1:
                    if (!selectedDate || !selectedSlot) {
                        alert('Please select a date and time slot');
                        return false;
                    }
                    break;
                case 2:
                    if (!selectedVenue) {
                        alert('Please select a venue');
                        return false;
                    }
                    break;
                case 3:
                    if (!document.getElementById('marriageCategory').value) {
                        alert('Please select a marriage category');
                        return false;
                    }
                    break;
                case 4:
                    // Validate required fields
                    const requiredFields = ['brideName', 'brideDob', 'brideNationality', 'brideIc', 
                                          'brideFather', 'brideMother', 'bridePhone', 'brideReligion', 'brideAddress',
                                          'groomName', 'groomDob', 'groomNationality', 'groomIc', 
                                          'groomFather', 'groomMother', 'groomPhone', 'groomReligion', 'groomAddress',
                                          'witness1Name', 'witness1Ic', 'witness2Name', 'witness2Ic'];
                    
                    for (let field of requiredFields) {
                        if (!document.getElementById(field).value.trim()) {
                            alert(`Please fill in all required fields`);
                            document.getElementById(field).focus();
                            return false;
                        }
                    }
                    break;
            }
            return true;
        }

        function updateStepIndicator() {
            // Update step circles
            for (let i = 1; i <= 4; i++) {
                const step = document.querySelector(`[data-step="${i}"]`);
                step.classList.remove('active', 'completed');
                
                if (i < currentStep) {
                    step.classList.add('completed');
                } else if (i === currentStep) {
                    step.classList.add('active');
                }
            }
            
            // Update progress line
            const progressPercentage = ((currentStep - 1) / 3) * 100;
            document.getElementById('progressLine').style.width = `${progressPercentage}%`;
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
            nextBtn.style.display = currentStep < 4 ? 'block' : 'none';
            submitBtn.style.display = currentStep === 4 ? 'block' : 'none';
        }

        // Event Listeners
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
            updateMonthYearDisplay();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
            updateMonthYearDisplay();
        });

        document.getElementById('nextBtn').addEventListener('click', nextStep);
        document.getElementById('prevBtn').addEventListener('click', prevStep);

        // Add event listeners for price updates
        document.getElementById('marriageCategory').addEventListener('change', updatePriceSummary);
        document.querySelectorAll('#additionalServices input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updatePriceSummary);
        });

        // Form submission
        document.getElementById('marriageRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateCurrentStep()) {
                // Collect all form data
                const formData = {
                    registration_date: selectedDate,
                    slot_id: selectedSlot.id,
                    venue_id: selectedVenue.id,
                    category_id: document.getElementById('marriageCategory').value,
                    guest_count: document.getElementById('guestCount').value,
                    
                    // Bride details
                    bride_name: document.getElementById('brideName').value,
                    bride_dob: document.getElementById('brideDob').value,
                    bride_nationality: document.getElementById('brideNationality').value,
                    bride_ic_passport: document.getElementById('brideIc').value,
                    bride_father_name: document.getElementById('brideFather').value,
                    bride_mother_name: document.getElementById('brideMother').value,
                    bride_phone: document.getElementById('bridePhone').value,
                    bride_email: document.getElementById('brideEmail').value,
                    bride_religion: document.getElementById('brideReligion').value,
                    bride_occupation: document.getElementById('brideOccupation').value,
                    bride_address: document.getElementById('brideAddress').value,
                    
                    // Groom details
                    groom_name: document.getElementById('groomName').value,
                    groom_dob: document.getElementById('groomDob').value,
                    groom_nationality: document.getElementById('groomNationality').value,
                    groom_ic_passport: document.getElementById('groomIc').value,
                    groom_father_name: document.getElementById('groomFather').value,
                    groom_mother_name: document.getElementById('groomMother').value,
                    groom_phone: document.getElementById('groomPhone').value,
                    groom_email: document.getElementById('groomEmail').value,
                    groom_religion: document.getElementById('groomReligion').value,
                    groom_occupation: document.getElementById('groomOccupation').value,
                    groom_address: document.getElementById('groomAddress').value,
                    
                    // Witness details
                    witness1_name: document.getElementById('witness1Name').value,
                    witness1_ic: document.getElementById('witness1Ic').value,
                    witness1_phone: document.getElementById('witness1Phone').value,
                    witness2_name: document.getElementById('witness2Name').value,
                    witness2_ic: document.getElementById('witness2Ic').value,
                    witness2_phone: document.getElementById('witness2Phone').value,
                    
                    // Additional info
                    special_requirements: document.getElementById('specialRequirements').value
                };
                
                console.log('Form Data:', formData);
                
                $.post("store",formData,(res)=>{
                    res = ("undefined" == typeof res.status?JSON.parse(res):res);
                    if(res.status)
                    {
                        alert('Marriage registration submitted successfully! You will be redirected to payment.');
                    }
                    else
                    {
                        alert(res.errMsg);
                    }
                })
                // Show success message (in real app, this would submit to server)
               // alert('Marriage registration submitted successfully! You will be redirected to payment.');
                
                // Reset form (optional)
                // document.getElementById('marriageRegistrationForm').reset();
                // currentStep = 1;
                // updateStepIndicator();
                // updateNavigationButtons();
            }
        });

        // Initialize calendar on page load
        document.addEventListener('DOMContentLoaded', initCalendar);
    </script>
</body>
</html>