<?php 
$readonly = isset($view) && $view == true ? 'readonly' : '';
$disable = isset($view) && $view == true ? "disabled" : '';
?>

<style>
.supplier-form-container {
    min-height: 100vh;
    padding: 20px 0;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 0 auto;
    max-width: 1200px;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    text-align: center;
    position: relative;
}

.form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="100%"><stop offset="0%" stop-color="rgba(255,255,255,.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
}

.form-header h1 {
    margin: 0;
    font-size: 2.5rem;
    font-weight: 300;
    position: relative;
    z-index: 1;
}

.form-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
    margin-top: 10px;
    position: relative;
    z-index: 1;
}

.form-body {
    padding: 40px;
}

.section-title {
    color: #4a5568;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #2d3748;
    font-weight: 500;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:read-only {
    background: #f1f5f9;
    color: #64748b;
}

.required {
    color: #e53e3e;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.form-col {
    flex: 1;
}

.opening-balance-section {
    background: #f8fafc;
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 25px;
    margin: 30px 0;
}

.balance-input-group {
    display: flex;
    gap: 15px;
    align-items: end;
}

.balance-type-selector {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.radio-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.radio-group input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #667eea;
}

.radio-group label {
    margin: 0;
    color: #4a5568;
    cursor: pointer;
}

.btn-container {
    text-align: center;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 14px 40px;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin: 0 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    font-weight: 500;
}

.alert-success {
    background: #f0fff4;
    color: #22543d;
    border: 1px solid #9ae6b4;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border: 1px solid #feb2b2;
}

.icon {
    font-size: 1.2rem;
    margin-right: 8px;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .balance-input-group {
        flex-direction: column;
        gap: 15px;
    }
    
    .form-header h1 {
        font-size: 2rem;
    }
    
    .form-body {
        padding: 25px;
    }
}
</style>

<div class="supplier-form-container">
    <div class="container-fluid">
        <div class="form-card">
            <div class="form-header">
                <h1>
                    <i class="material-icons icon">business</i>
                    <?php echo isset($supplier['id']) ? 'Edit Supplier' : 'Add New Supplier'; ?>
                </h1>
                <div class="subtitle">
                    <?php echo isset($view) && $view ? 'View supplier details' : 'Enter supplier information below'; ?>
                </div>
            </div>

            <div class="form-body">
                <?php if(session('succ')): ?>
                    <div class="alert alert-success">
                        <i class="material-icons icon">check_circle</i>
                        <?php echo session('succ'); ?>
                    </div>
                <?php endif; ?>

                <?php if(session('fail')): ?>
                    <div class="alert alert-danger">
                        <i class="material-icons icon">error</i>
                        <?php echo session('fail'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo base_url(); ?>/supplier/store" method="POST" id="form_validation">
                    <input type="hidden" value="<?php echo isset($supplier['id']) ? $supplier['id'] : ""; ?>" name="id" id="updateid">
                    
                    <!-- Basic Information Section -->
                    <div class="section-title">
                        <i class="material-icons icon">info</i>
                        Basic Information
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">
                                    Supplier Name <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       name="supplier_name" 
                                       id="supplier_name" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       required 
                                       placeholder="Enter supplier name">
                            </div>
                        </div>
                        
                        <?php if(isset($supplier['supplier_code'])): ?>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Supplier Code</label>
                                <input type="text" 
                                       name="supplier_code" 
                                       id="supplier_code" 
                                       class="form-control" 
                                       readonly 
                                       value="<?php echo isset($supplier['supplier_code']) ? $supplier['supplier_code'] : ""; ?>">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Contact Person</label>
                                <input type="text" 
                                       name="contact" 
                                       id="contact" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['contact']) ? $supplier['contact'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Contact person name">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" 
                                       name="mobile_no" 
                                       id="mobile_no" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['mobile_no']) ? $supplier['mobile_no'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="+60123456789">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" 
                                       name="phoneno" 
                                       id="phoneno" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['phone']) ? $supplier['phone'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Phone number">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['email_id']) ? $supplier['email_id'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="supplier@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="section-title">
                        <i class="material-icons icon">location_on</i>
                        Address Information
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" 
                                       name="address1" 
                                       id="address1" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['address1']) ? $supplier['address1'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Street address">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" 
                                       name="address2" 
                                       id="address2" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['address2']) ? $supplier['address2'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Apartment, suite, etc.">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['city']) ? $supplier['city'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="City">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">State</label>
                                <input type="text" 
                                       name="state" 
                                       id="state" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['state']) ? $supplier['state'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="State">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" 
                                       name="pincode" 
                                       id="pincode" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['zipcode']) ? $supplier['zipcode'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Postal code">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text" 
                                       name="country" 
                                       id="country" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['country']) ? $supplier['country'] : "Malaysia"; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Country">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Tax ID / TIN Number</label>
                                <input type="text" 
                                       name="vat_no" 
                                       id="vat_no" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['vat_no']) ? $supplier['vat_no'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Tax identification number">
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Fax Number</label>
                                <input type="text" 
                                       name="fax" 
                                       id="fax" 
                                       class="form-control" 
                                       value="<?php echo isset($supplier['fax']) ? $supplier['fax'] : ""; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="Fax number">
                            </div>
                        </div>
                    </div>

                    <!-- Opening Balance Section -->
                    <?php if(isset($ac_years) || isset($opening_balance)): ?>
                    <div class="section-title">
                        <i class="material-icons icon">account_balance</i>
                        Opening Balance
                        <?php if(isset($ac_years)): ?>
                            <span style="font-size: 0.9rem; font-weight: normal; opacity: 0.8;">
                                (For Year: <?php echo $ac_years['from_year_month'] ?? ''; ?> - <?php echo $ac_years['to_year_month'] ?? ''; ?>)
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="opening-balance-section">
                        <div class="balance-input-group">
                            <div style="flex: 2;">
                                <label class="form-label">Opening Balance Amount</label>
                                <input type="number" 
                                       name="opening_balance" 
                                       id="opening_balance" 
                                       class="form-control" 
                                       step="0.01" 
                                       min="0"
                                       value="<?php echo isset($opening_balance) ? ($opening_balance['dr_amount'] > 0 ? $opening_balance['dr_amount'] : $opening_balance['cr_amount']) : '0'; ?>" 
                                       <?php echo $readonly; ?> 
                                       placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="balance-type-selector">
                            <label class="form-label">Balance Type:</label>
                            <div class="radio-group">
                                <input type="radio" 
                                       name="balance_type" 
                                       id="balance_cr" 
                                       value="cr" 
                                       <?php echo (!isset($opening_balance) || (isset($opening_balance) && $opening_balance['cr_amount'] > 0)) ? 'checked' : ''; ?>
                                       <?php echo $disable; ?>>
                                <label for="balance_cr">Credit (We owe them)</label>
                            </div>
                            <div class="radio-group">
                                <input type="radio" 
                                       name="balance_type" 
                                       id="balance_dr" 
                                       value="dr" 
                                       <?php echo (isset($opening_balance) && $opening_balance['dr_amount'] > 0) ? 'checked' : ''; ?>
                                       <?php echo $disable; ?>>
                                <label for="balance_dr">Debit (They owe us)</label>
                            </div>
                        </div>
                        
                        <div style="margin-top: 15px; padding: 12px; background: #e6fffa; border-radius: 8px; font-size: 0.9rem; color: #234e52;">
                            <i class="material-icons" style="font-size: 1rem; vertical-align: middle; margin-right: 5px;">info</i>
                            <strong>Note:</strong> For suppliers, Credit balance means we owe them money (normal), Debit balance means they owe us money (advance payment).
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if(!isset($view) || $view !== true): ?>
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons icon">save</i>
                            <?php echo isset($supplier['id']) ? 'Update Supplier' : 'Create Supplier'; ?>
                        </button>
                        <a href="<?php echo base_url(); ?>/supplier" class="btn btn-secondary">
                            <i class="material-icons icon">cancel</i>
                            Cancel
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="btn-container">
                        <a href="<?php echo base_url(); ?>/supplier/edit/<?php echo $supplier['id']; ?>" class="btn btn-primary">
                            <i class="material-icons icon">edit</i>
                            Edit Supplier
                        </a>
                        <a href="<?php echo base_url(); ?>/supplier" class="btn btn-secondary">
                            <i class="material-icons icon">arrow_back</i>
                            Back to List
                        </a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>
<script>
$(document).ready(function() {
    $('#form_validation').validate({
        rules: {
            "supplier_name": {
                required: true,
                maxlength: 200
            },
            "mobile_no": {
                maxlength: 20
            },
            "email": {
                email: true,
                maxlength: 200
            },
            "vat_no": {
                maxlength: 100
            },
            "phone": {
                maxlength: 20
            },
            "fax": {
                maxlength: 100
            },
            "city": {
                maxlength: 100
            },
            "state": {
                maxlength: 100
            },
            "pincode": {
                maxlength: 20
            },
            "country": {
                maxlength: 100
            },
            "opening_balance": {
                number: true,
                min: 0
            }
        },
        messages: {
            "supplier_name": {
                required: "Supplier name is required",
                maxlength: "Supplier name cannot exceed 200 characters"
            },
            "email": {
                email: "Please enter a valid email address"
            },
            "opening_balance": {
                number: "Please enter a valid number",
                min: "Opening balance cannot be negative"
            }
        },
        errorClass: 'error',
        validClass: 'valid',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
            error.css({
                'color': '#e53e3e',
                'font-size': '0.85rem',
                'margin-top': '5px',
                'display': 'block'
            });
        },
        highlight: function(element) {
            $(element).css('border-color', '#e53e3e');
        },
        unhighlight: function(element) {
            $(element).css('border-color', '#e2e8f0');
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Add smooth animations
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>