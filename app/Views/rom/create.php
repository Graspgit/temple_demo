<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>NEW MARRIAGE REGISTRATION</h2>
        </div>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <?= $error ?><br>
                <?php endforeach; ?>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('rom/store') ?>" enctype="multipart/form-data" id="romForm">
            <?= csrf_field() ?>
            
            <!-- Step 1: Date & Slot Selection -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-pink">
                            <h2>
                                STEP 1: DATE & TIME SLOT SELECTION
                                <small>Select your preferred date and time slot for the marriage registration</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="booking_date" id="booking_date" 
                                                   class="form-control" required min="<?= date('Y-m-d') ?>">
                                            <label class="form-label">Select Date *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="slot_id" id="slot_id" class="form-control show-tick" required disabled>
                                            <option value="">-- Select Time Slot --</option>
                                            <?php foreach($slots as $slot): ?>
                                            <option value="<?= $slot['id'] ?>" data-max="<?= $slot['max_bookings'] ?>">
                                                <?= $slot['slot_name'] ?> 
                                                (<?= date('h:i A', strtotime($slot['start_time'])) ?> - 
                                                <?= date('h:i A', strtotime($slot['end_time'])) ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="availability-status" class="alert" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Venue Selection -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                            <h2>
                                STEP 2: VENUE SELECTION
                                <small>Choose your preferred venue for the ceremony</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <?php foreach($venues as $venue): ?>
                                <div class="col-md-3">
                                    <div class="venue-card" data-venue-id="<?= $venue['id'] ?>" data-price="<?= $venue['price'] ?>">
                                        <input type="radio" name="venue_id" id="venue_<?= $venue['id'] ?>" 
                                               value="<?= $venue['id'] ?>" required>
                                        <label for="venue_<?= $venue['id'] ?>">
                                            <div class="card">
                                                <div class="header <?= $venue['venue_type'] == 'Premium' ? 'bg-amber' : ($venue['venue_type'] == 'Classic' ? 'bg-blue' : 'bg-green') ?>">
                                                    <h2 style="color: white;"><?= $venue['venue_name'] ?></h2>
                                                </div>
                                                <div class="body">
                                                    <p><strong>Type:</strong> <?= $venue['venue_type'] ?></p>
                                                    <p><strong>Capacity:</strong> <?= $venue['capacity'] ?> persons</p>
                                                    <p><strong>Price:</strong> RM <?= number_format($venue['price'], 2) ?></p>
                                                    <p><small><?= $venue['description'] ?></small></p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Bride Details -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-purple">
                            <h2>
                                STEP 3: BRIDE DETAILS
                                <small>Enter bride's personal information</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_name" class="form-control" required>
                                            <label class="form-label">Full Name *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="bride_dob" id="bride_dob" class="form-control" 
                                                   required max="<?= date('Y-m-d', strtotime('-18 years')) ?>">
                                            <label class="form-label">Date of Birth *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="bride_nationality" class="form-control show-tick" required>
                                            <option value="">-- Select Nationality --</option>
                                            <option value="Malaysian" selected>Malaysian</option>
                                            <option value="Singaporean">Singaporean</option>
                                            <option value="Indonesian">Indonesian</option>
                                            <option value="Thai">Thai</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_ic" class="form-control" required>
                                            <label class="form-label">IC/Passport Number *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_phone" class="form-control">
                                            <label class="form-label">Phone Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" name="bride_email" class="form-control">
                                            <label class="form-label">Email Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="bride_address" class="form-control" rows="2"></textarea>
                                            <label class="form-label">Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_occupation" class="form-control">
                                            <label class="form-label">Occupation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_father" class="form-control">
                                            <label class="form-label">Father's Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="bride_mother" class="form-control">
                                            <label class="form-label">Mother's Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bride Photo</label>
                                        <input type="file" name="bride_photo" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Groom Details -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-indigo">
                            <h2>
                                STEP 4: GROOM DETAILS
                                <small>Enter groom's personal information</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_name" class="form-control" required>
                                            <label class="form-label">Full Name *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="groom_dob" id="groom_dob" class="form-control" 
                                                   required max="<?= date('Y-m-d', strtotime('-18 years')) ?>">
                                            <label class="form-label">Date of Birth *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="groom_nationality" class="form-control show-tick" required>
                                            <option value="">-- Select Nationality --</option>
                                            <option value="Malaysian" selected>Malaysian</option>
                                            <option value="Singaporean">Singaporean</option>
                                            <option value="Indonesian">Indonesian</option>
                                            <option value="Thai">Thai</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_ic" class="form-control" required>
                                            <label class="form-label">IC/Passport Number *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_phone" class="form-control">
                                            <label class="form-label">Phone Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" name="groom_email" class="form-control">
                                            <label class="form-label">Email Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="groom_address" class="form-control" rows="2"></textarea>
                                            <label class="form-label">Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_occupation" class="form-control">
                                            <label class="form-label">Occupation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_father" class="form-control">
                                            <label class="form-label">Father's Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="groom_mother" class="form-control">
                                            <label class="form-label">Mother's Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Groom Photo</label>
                                        <input type="file" name="groom_photo" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Documents Upload -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-brown">
                            <h2>
                                STEP 5: DOCUMENT UPLOADS
                                <small>Upload required documents</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>JPN Form</label>
                                        <input type="file" name="documents[JPN_Form]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bride IC Copy</label>
                                        <input type="file" name="documents[Bride_IC]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Groom IC Copy</label>
                                        <input type="file" name="documents[Groom_IC]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Other Document 1</label>
                                        <input type="file" name="documents[Other_1]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Other Document 2</label>
                                        <input type="file" name="documents[Other_2]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Other Document 3</label>
                                        <input type="file" name="documents[Other_3]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 6: Payment Details -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-green">
                            <h2>
                                STEP 6: PAYMENT DETAILS
                                <small>Enter payment information</small>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="payment-summary">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="70%">Venue Price</td>
                                                <td class="text-right">RM <span id="venue_price">0.00</span></td>
                                            </tr>
                                            <tr>
                                                <td>Security Deposit</td>
                                                <td class="text-right">
                                                    <input type="number" name="security_deposit" id="security_deposit" 
                                                           class="form-control text-right" value="0" step="0.01" 
                                                           onchange="calculateTotal()">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Extra Charges</td>
                                                <td class="text-right">
                                                    <input type="number" name="extra_charges" id="extra_charges" 
                                                           class="form-control text-right" value="0" step="0.01" 
                                                           onchange="calculateTotal()">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tax Amount</td>
                                                <td class="text-right">
                                                    <input type="number" name="tax_amount" id="tax_amount" 
                                                           class="form-control text-right" value="0" step="0.01" 
                                                           onchange="calculateTotal()">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Discount</td>
                                                <td class="text-right">
                                                    <input type="number" name="discount_amount" id="discount_amount" 
                                                           class="form-control text-right" value="0" step="0.01" 
                                                           onchange="calculateTotal()">
                                                </td>
                                            </tr>
                                            <tr class="bg-light-blue">
                                                <td><strong>TOTAL AMOUNT</strong></td>
                                                <td class="text-right"><strong>RM <span id="total_amount">0.00</span></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Mode *</label>
                                        <select name="payment_mode_id" class="form-control show-tick" required>
                                            <option value="">-- Select Payment Mode --</option>
                                            <?php foreach($payment_modes as $mode): ?>
                                            <option value="<?= $mode['id'] ?>" 
                                                    data-gateway="<?= $mode['is_payment_gateway'] ?>">
                                                <?= $mode['name'] ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment Type *</label>
                                        <select name="payment_type" class="form-control show-tick" required>
                                            <option value="">-- Select Payment Type --</option>
                                            <option value="partial">Partial Payment</option>
                                            <option value="full">Full Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="payment_amount" class="form-control" 
                                                   step="0.01" required>
                                            <label class="form-label">Payment Amount (RM) *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="transaction_id" class="form-control">
                                            <label class="form-label">Transaction ID</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="payment_remarks" class="form-control" rows="1"></textarea>
                                            <label class="form-label">Payment Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="remarks" class="form-control" rows="3"></textarea>
                                            <label class="form-label">General Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body text-center">
                            <button type="submit" class="btn btn-primary btn-lg waves-effect">
                                <i class="material-icons">save</i> SUBMIT REGISTRATION
                            </button>
                            <a href="<?= base_url('rom') ?>" class="btn btn-danger btn-lg waves-effect">
                                <i class="material-icons">cancel</i> CANCEL
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
$(document).ready(function() {
    // Initialize form
    $('#booking_date').change(function() {
        checkDateAvailability();
    });

    $('#slot_id').change(function() {
        checkSlotAvailability();
    });

    $('input[name="venue_id"]').change(function() {
        updatePriceDisplay();
    });

    // Calculate age on DOB change
    $('#bride_dob, #groom_dob').change(function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        
        if (age < 18) {
            alert('Person must be at least 18 years old!');
            $(this).val('');
        }
    });
});

function checkDateAvailability() {
    var date = $('#booking_date').val();
    if (!date) return;

    $.ajax({
        url: '<?= base_url('rom/checkAvailability') ?>',
        method: 'POST',
        data: {
            date: date,
            slot_id: null,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            console.log(response);
            if (response.success) {
                $('#slot_id').prop('disabled', false);
                $('#availability-status').hide();
                $('#slot_id').selectpicker('refresh');
            } else {
                $('#slot_id').prop('disabled', true);
                $('#availability-status')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(response.message)
                    .show();
            }
        }
    });
}

function checkSlotAvailability() {
    var date = $('#booking_date').val();
    var slotId = $('#slot_id').val();
    
    if (!date || !slotId) return;

    $.ajax({
        url: '<?= base_url('rom/checkAvailability') ?>',
        method: 'POST',
        data: {
            date: date,
            slot_id: slotId,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success && response.available) {
                $('#availability-status')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('✓ Slot available! ' + response.remaining + ' slots remaining.')
                    .show();
            } else {
                $('#availability-status')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('✗ Slot is full! Please select another slot.')
                    .show();
                $('#slot_id').val('').selectpicker('refresh');
            }
        }
    });
}

function updatePriceDisplay() {
    var selectedVenue = $('input[name="venue_id"]:checked');
    var price = selectedVenue.closest('.venue-card').data('price') || 0;
    $('#venue_price').text(parseFloat(price).toFixed(2));
    calculateTotal();
}

function calculateTotal() {
    var venuePrice = parseFloat($('#venue_price').text()) || 0;
    var securityDeposit = parseFloat($('#security_deposit').val()) || 0;
    var extraCharges = parseFloat($('#extra_charges').val()) || 0;
    var taxAmount = parseFloat($('#tax_amount').val()) || 0;
    var discount = parseFloat($('#discount_amount').val()) || 0;
    
    var total = venuePrice + securityDeposit + extraCharges + taxAmount - discount;
    $('#total_amount').text(total.toFixed(2));
}

// Form validation
$('#romForm').on('submit', function(e) {
    var paymentAmount = parseFloat($('input[name="payment_amount"]').val()) || 0;
    var totalAmount = parseFloat($('#total_amount').text()) || 0;
    var paymentType = $('select[name="payment_type"]').val();
    
    if (paymentType === 'full' && paymentAmount < totalAmount) {
        e.preventDefault();
        alert('Payment amount must be equal to total amount for full payment!');
        return false;
    }
    
    if (paymentAmount > totalAmount) {
        e.preventDefault();
        alert('Payment amount cannot exceed total amount!');
        return false;
    }
    
    // Check if payment gateway is selected
    var selectedPaymentMode = $('select[name="payment_mode_id"] option:selected');
    if (selectedPaymentMode.data('gateway') == '1') {
        // TODO: Integrate payment gateway
        console.log('Payment gateway integration pending');
    }
    
    return true;
});
</script>

<style>
.venue-card {
    cursor: pointer;
    transition: all 0.3s ease;
}
.venue-card input[type="radio"] {
    display: none;
}
.venue-card .card {
    border: 2px solid #ddd;
    transition: all 0.3s ease;
}
.venue-card input[type="radio"]:checked + label .card {
    border-color: #2196F3;
    box-shadow: 0 0 15px rgba(33, 150, 243, 0.3);
}
.payment-summary table td {
    padding: 8px;
}
.payment-summary input {
    max-width: 150px;
    float: right;
}
#availability-status {
    padding: 10px;
    margin: 0;
    font-weight: bold;
}
</style>