<!-- hr/staff/create.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STAFF REGISTRATION FORM</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('staff') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back to List
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form id="staff_form" method="POST" action="<?= base_url('staff/store') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <!-- Basic Information -->
                            <h3>Basic Information</h3>
                            <fieldset>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="staff_code" class="form-control" value="<?= $staff_code ?>" readonly>
                                                <label class="form-label">Staff Code</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Staff Type <span class="text-danger">*</span></label>
                                            <select name="staff_type" id="staff_type" class="form-control show-tick" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="local">Local</option>
                                                <option value="foreigner">Foreigner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="first_name" class="form-control" required>
                                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="last_name" class="form-control" required>
                                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="email" name="email" class="form-control">
                                                <label class="form-label">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="phone" class="form-control">
                                                <label class="form-label">Phone</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="date_of_birth" class="form-control">
                                                <label class="form-label">Date of Birth</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control show-tick">
                                                <option value="">-- Select --</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Marital Status</label>
                                            <select name="marital_status" class="form-control show-tick">
                                                <option value="">-- Select --</option>
                                                <option value="single">Single</option>
                                                <option value="married">Married</option>
                                                <option value="divorced">Divorced</option>
                                                <option value="widowed">Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea name="address" class="form-control" rows="2"></textarea>
                                                <label class="form-label">Address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Employment Details -->
                            <h3>Employment Details</h3>
                            <fieldset>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="join_date" class="form-control" required>
                                                <label class="form-label">Join Date <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <select name="department_id" id="department_id" class="form-control show-tick" required>
                                                <option value="">-- Select Department --</option>
                                                <?php foreach($departments as $dept): ?>
                                                    <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Designation <span class="text-danger">*</span></label>
                                            <select name="designation_id" id="designation_id" class="form-control show-tick" required>
                                                <option value="">-- Select Designation --</option>
                                                <?php foreach($designations as $desig): ?>
                                                    <option value="<?= $desig['id'] ?>" data-dept="<?= $desig['department_id'] ?>"><?= $desig['designation_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" name="basic_salary" class="form-control" step="0.01" required>
                                                <label class="form-label">Basic Salary (RM) <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Type Specific Details -->
                            <h3>Additional Details</h3>
                            <fieldset>
                                <!-- Foreigner Details -->
                                <div id="foreigner_details" style="display:none;">
                                    <h4>Passport & Visa Information</h4>
                                    <div class="row clearfix">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="passport_number" class="form-control">
                                                    <label class="form-label">Passport Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="date" name="passport_expiry" class="form-control">
                                                    <label class="form-label">Passport Expiry</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="visa_number" class="form-control">
                                                    <label class="form-label">Visa Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="visa_type" class="form-control">
                                                    <label class="form-label">Visa Type</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="visa_category" class="form-control">
                                                    <label class="form-label">Visa Category</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="date" name="visa_expiry" class="form-control">
                                                    <label class="form-label">Visa Expiry</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="date" name="visa_renewal_date" class="form-control">
                                                    <label class="form-label">Visa Renewal Date</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="country_of_origin" class="form-control">
                                                    <label class="form-label">Country of Origin</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Local Staff Details -->
                                <div id="local_details" style="display:none;">
                                    <h4>Statutory Information</h4>
                                    <div class="row clearfix">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="epf_number" class="form-control">
                                                    <label class="form-label">EPF Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="socso_number" class="form-control">
                                                    <label class="form-label">SOCSO Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="eis_number" class="form-control">
                                                    <label class="form-label">EIS Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="income_tax_number" class="form-control">
                                                    <label class="form-label">Income Tax Number</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>PCB Code</label>
                                                <select name="pcb_code" class="form-control show-tick">
                                                    <option value="">-- Select --</option>
                                                    <option value="1">1 - Single</option>
                                                    <option value="2">2 - Married, spouse not working</option>
                                                    <option value="3">3 - Married, spouse working</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="checkbox" name="ea_form_submitted" id="ea_form_submitted" value="1" class="filled-in">
                                                <label for="ea_form_submitted">EA Form Submitted</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Next of Kin -->
                            <h3>Next of Kin</h3>
                            <fieldset>
                                <div id="nok_container">
                                    <div class="nok_row">
                                        <div class="row clearfix">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="nok_name[]" class="form-control">
                                                        <label class="form-label">Name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="nok_relationship[]" class="form-control">
                                                        <label class="form-label">Relationship</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="nok_phone[]" class="form-control">
                                                        <label class="form-label">Phone</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="email" name="nok_email[]" class="form-control">
                                                        <label class="form-label">Email</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="nok_address[]" class="form-control">
                                                        <label class="form-label">Address</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" id="add_nok">
                                    <i class="material-icons">add</i> Add Another
                                </button>
                            </fieldset>

                            <!-- Documents -->
                            <h3>Documents</h3>
                            <fieldset>
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Upload Documents (Multiple files allowed)</label>
                                            <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG</small>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link href="<?= base_url('assets/plugins/jquery-steps/jquery.steps.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/plugins/jquery-steps/jquery.steps.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.js') ?>"></script>

<script>
$(function () {
    // Form wizard
    var form = $('#staff_form');
    form.steps({
        headerTag: 'h3',
        bodyTag: 'fieldset',
        transitionEffect: 'slideLeft',
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) return true;
            if (currentIndex < newIndex) {
                form.find('.body:eq(' + newIndex + ') label.error').remove();
                form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
            }
            form.validate().settings.ignore = ':disabled,:hidden';
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ':disabled';
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            form.submit();
        }
    });

    // Form validation
    form.validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

    // Show/hide type specific fields
    $('#staff_type').on('change', function() {
        var type = $(this).val();
        if (type == 'foreigner') {
            $('#foreigner_details').show();
            $('#local_details').hide();
        } else if (type == 'local') {
            $('#local_details').show();
            $('#foreigner_details').hide();
        } else {
            $('#foreigner_details').hide();
            $('#local_details').hide();
        }
    });

    // Filter designations by department
    $('#department_id').on('change', function() {
        var deptId = $(this).val();
        $('#designation_id option').hide();
        $('#designation_id option[value=""]').show();
        if (deptId) {
            $('#designation_id option[data-dept="' + deptId + '"]').show();
        }
        $('#designation_id').selectpicker('refresh');
    });

    // Add next of kin
    $('#add_nok').on('click', function() {
        var nokRow = $('.nok_row:first').clone();
        nokRow.find('input').val('');
        nokRow.append('<div class="col-md-12"><button type="button" class="btn btn-sm btn-danger remove_nok"><i class="material-icons">delete</i> Remove</button></div>');
        $('#nok_container').append(nokRow);
    });

    // Remove next of kin
    $(document).on('click', '.remove_nok', function() {
        $(this).closest('.nok_row').remove();
    });

    // Initialize select picker
    $('.show-tick').selectpicker();
});
</script>