<!-- hr/staff/edit.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>EDIT STAFF INFORMATION</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('staff/view/'.$staff['id']) ?>" class="btn btn-info waves-effect">
                                    <i class="material-icons">visibility</i> View Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('staff') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back to List
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form id="staff_form" method="POST" action="<?= base_url('staff/update/'.$staff['id']) ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <!-- Basic Information -->
                            <h3>Basic Information</h3>
                            <fieldset>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="staff_code" class="form-control" value="<?= $staff['staff_code'] ?>" readonly>
                                                <label class="form-label">Staff Code</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Staff Type</label>
                                            <input type="text" class="form-control" value="<?= ucfirst($staff['staff_type']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="first_name" class="form-control" value="<?= $staff['first_name'] ?>" required>
                                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="last_name" class="form-control" value="<?= $staff['last_name'] ?>" required>
                                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="email" name="email" class="form-control" value="<?= $staff['email'] ?>">
                                                <label class="form-label">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="phone" class="form-control" value="<?= $staff['phone'] ?>">
                                                <label class="form-label">Phone</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="date_of_birth" class="form-control" value="<?= $staff['date_of_birth'] ?>">
                                                <label class="form-label">Date of Birth</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control show-tick">
                                                <option value="">-- Select --</option>
                                                <option value="male" <?= $staff['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                                <option value="female" <?= $staff['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                                <option value="other" <?= $staff['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
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
                                                <option value="single" <?= $staff['marital_status'] == 'single' ? 'selected' : '' ?>>Single</option>
                                                <option value="married" <?= $staff['marital_status'] == 'married' ? 'selected' : '' ?>>Married</option>
                                                <option value="divorced" <?= $staff['marital_status'] == 'divorced' ? 'selected' : '' ?>>Divorced</option>
                                                <option value="widowed" <?= $staff['marital_status'] == 'widowed' ? 'selected' : '' ?>>Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea name="address" class="form-control" rows="2"><?= $staff['address'] ?></textarea>
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
                                                <input type="date" name="join_date" class="form-control" value="<?= $staff['join_date'] ?>" readonly>
                                                <label class="form-label">Join Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <select name="department_id" class="form-control show-tick" required>
                                                <option value="">-- Select Department --</option>
                                                <?php foreach($departments as $dept): ?>
                                                    <option value="<?= $dept['id'] ?>" <?= $staff['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= $dept['department_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Designation <span class="text-danger">*</span></label>
                                            <select name="designation_id" class="form-control show-tick" required>
                                                <option value="">-- Select Designation --</option>
                                                <?php foreach($designations as $desig): ?>
                                                    <option value="<?= $desig['id'] ?>" <?= $staff['designation_id'] == $desig['id'] ? 'selected' : '' ?>><?= $desig['designation_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" name="basic_salary" class="form-control" value="<?= $staff['basic_salary'] ?>" step="0.01" required>
                                                <label class="form-label">Basic Salary (RM) <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control show-tick">
                                                <option value="active" <?= $staff['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                <option value="inactive" <?= $staff['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                <option value="terminated" <?= $staff['status'] == 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Type Specific Details -->
                            <h3>Additional Details</h3>
                            <fieldset>
                                <?php if($staff['staff_type'] == 'foreigner'): ?>
                                <!-- Foreigner Details -->
                                <h4>Passport & Visa Information</h4>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="passport_number" class="form-control" value="<?= $staff['foreigner_details']['passport_number'] ?? '' ?>">
                                                <label class="form-label">Passport Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="passport_expiry" class="form-control" value="<?= $staff['foreigner_details']['passport_expiry'] ?? '' ?>">
                                                <label class="form-label">Passport Expiry</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="visa_number" class="form-control" value="<?= $staff['foreigner_details']['visa_number'] ?? '' ?>">
                                                <label class="form-label">Visa Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="visa_type" class="form-control" value="<?= $staff['foreigner_details']['visa_type'] ?? '' ?>">
                                                <label class="form-label">Visa Type</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="visa_category" class="form-control" value="<?= $staff['foreigner_details']['visa_category'] ?? '' ?>">
                                                <label class="form-label">Visa Category</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="visa_expiry" class="form-control" value="<?= $staff['foreigner_details']['visa_expiry'] ?? '' ?>">
                                                <label class="form-label">Visa Expiry</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" name="visa_renewal_date" class="form-control" value="<?= $staff['foreigner_details']['visa_renewal_date'] ?? '' ?>">
                                                <label class="form-label">Visa Renewal Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="country_of_origin" class="form-control" value="<?= $staff['foreigner_details']['country_of_origin'] ?? '' ?>">
                                                <label class="form-label">Country of Origin</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- Local Staff Details -->
                                <h4>Statutory Information</h4>
                                <div class="row clearfix">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="epf_number" class="form-control" value="<?= $staff['statutory_details']['epf_number'] ?? '' ?>">
                                                <label class="form-label">EPF Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="socso_number" class="form-control" value="<?= $staff['statutory_details']['socso_number'] ?? '' ?>">
                                                <label class="form-label">SOCSO Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="eis_number" class="form-control" value="<?= $staff['statutory_details']['eis_number'] ?? '' ?>">
                                                <label class="form-label">EIS Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="income_tax_number" class="form-control" value="<?= $staff['statutory_details']['income_tax_number'] ?? '' ?>">
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
                                                <option value="1" <?= ($staff['statutory_details']['pcb_code'] ?? '') == '1' ? 'selected' : '' ?>>1 - Single</option>
                                                <option value="2" <?= ($staff['statutory_details']['pcb_code'] ?? '') == '2' ? 'selected' : '' ?>>2 - Married, spouse not working</option>
                                                <option value="3" <?= ($staff['statutory_details']['pcb_code'] ?? '') == '3' ? 'selected' : '' ?>>3 - Married, spouse working</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="checkbox" name="ea_form_submitted" id="ea_form_submitted" value="1" class="filled-in" <?= ($staff['statutory_details']['ea_form_submitted'] ?? 0) == 1 ? 'checked' : '' ?>>
                                            <label for="ea_form_submitted">EA Form Submitted</label>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </fieldset>

                            <!-- Submit Button -->
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> UPDATE STAFF
                                    </button>
                                    <a href="<?= base_url('staff/view/'.$staff['id']) ?>" class="btn btn-danger waves-effect">
                                        <i class="material-icons">cancel</i> CANCEL
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function () {
    // Initialize form with focused class for filled inputs
    $('.form-control').each(function() {
        if ($(this).val() !== '') {
            $(this).parents('.form-line').addClass('focused');
        }
    });
    
    $('.show-tick').selectpicker();
});
</script>