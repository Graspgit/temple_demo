<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}
if ($edit == true) {
    $readonly_edit = 'readonly';
    $disable_edit = "disabled";
}
?>
<style>
    <?php if ($view == true) { ?>
        label.form-label span {
            display: none !important;
            color: transporant;
        }
    <?php } ?>
    
    .pledge_amt_div {
        display: none;
    }
    
    .pledge_section {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin: 10px 0;
    }
    
    .pledge_header {
        font-weight: bold;
        color: #495057;
        margin-bottom: 10px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <?php if ($_SESSION['succ'] != '') { ?>
            <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                <div class="suc-alert">
                    <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <p>
                        <?php echo $_SESSION['succ']; ?>
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if ($_SESSION['fail'] != '') { ?>
            <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <p>
                        <?php echo $_SESSION['fail']; ?>
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if ($edit !== true) {
            $data = session()->getFlashdata('data'); 
        } ?>
        <div class="block-header">
            <h2>Devotee Management <small>Devotee / <b>Add Devotee</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <h2 style="text-align: center; margin-top: 10px;">ADD DEVOTEE</h2>
                            </div>
                            <div class="col-md-1" align="right">
                                <a href="<?php echo base_url(); ?>/devotee_management"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a>
                            </div>
                        </div>
                    </div>
                    <form action="<?php echo base_url(); ?>/devotee_management/save" method="POST" enctype='multipart/form-data'>
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            <input type="hidden" name="member_id" id="member_id" value="<?php echo $data['member_id']; ?>">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-md-1">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control" name="phone_code" id="phone_code" <?php echo $readonly; ?>>
                                                    <option value="">Dialing code</option>
                                                    <?php
                                                    if (!empty ($phone_codes)) {
                                                        foreach ($phone_codes as $phone_code) { ?>
                                                            <option value="<?php echo $phone_code['dailing_code']; ?>" <?php if ($phone_code['dailing_code'] == "+60" || $data['phone_code'] == $phone_code['dailing_code']) { echo "selected"; } ?> <?php echo $readonly; ?>> 
                                                                <?php echo $phone_code['dailing_code']; ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    } ?>    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <label for="phone_number" class="form-label focused">Phone Number <span style="color: red;">*</span></label>
                                                <input class="form-control" type="number" id="phone_number" name="phone_number" min="0" value="<?php echo !empty($data['phone_number']) ? $data['phone_number'] : ''; ?>" autocomplete="off" placeholder="" <?php echo $readonly; ?> required>
                                            </div>
                                            <small id="error-message" style="color: red; display: none;">Invalid phone number format.</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <label class="form-label">Memeber Type <span style="color: red;">*</span></label>  
                                                <select class="form-control" name="is_member" id="is_member" >
                                                    <option value="">-- Select Member Type --</option>
                                                    <option value="1" <?php if (!empty($data['is_member']) && $data['is_member'] == 1) echo "selected"; ?>>Member</option>
                                                    <option value="0" <?php if (!empty($data['is_member']) && $data['is_member'] == 0) echo "selected"; ?>>Non Member</option>
                                                </select>
                                            </div>
                                            <small id="member-message" style="color: green; display: none;">Member Verified Successfully!</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-1" style="display: none;">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" id="member_no" class="form-control" value="" readonly >
                                                <label class="form-label">Member No</label>        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" name="name" id="name" class="form-control" required value="<?php echo !empty($data['name']) ? $data['name'] : ''; ?>" <?php echo $readonly; ?> autocomplete="off">
                                                <label class="form-label">Name <span style="color: red;">*</span></label>        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="email" id="email" name="email" class="form-control" value="<?php echo !empty($data['email']) ? $data['email'] : ''; ?>" <?php echo $readonly; ?> autocomplete="off">
                                                <label class="form-label">Email </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="date" id="dob" name="dob" class="form-control" value="<?php echo !empty($data['dob']) ? $data['dob'] : ''; ?>" min="<?php echo date("Y-m-d", strtotime($data['dob'])); ?>" max="<?php echo date("Y-m-d"); ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Date of Birth <span style="color: red;">*</span></label>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" id="ic_no" name="ic_no" class="form-control" value="<?php echo !empty($data['ic_no']) ? $data['ic_no'] : ''; ?>" <?php echo $readonly; ?> autocomplete="off">
                                                <label class="form-label">Ic Number </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <label class="form-label">Rasi</label>
                                                <select class="form-control" name="rasi_id" id="rasi_id">
                                                    <option value="">--Select Rasi--</option>
                                                    <?php foreach ($rasi as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($data['rasi_id']) && $data['rasi_id'] == $row['id']) echo "selected"; ?> data-name="<?php echo $row['name_eng']; ?>"><?php echo $row['name_eng']; ?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <label class="form-label">Natchathiram</label>
                                                <select class="form-control" name="natchathra_id" id="natchathra_id">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($natchathram as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($data['natchathra_id']) && $data['natchathra_id'] == $row['id']) echo "selected"; ?> data-name="<?php echo $row['name_eng']; ?>"><?php echo $row['name_eng']; ?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" name="address" class="form-control"
                                                    value="<?php echo !empty($data['address']) ? $data['address'] : ''; ?>" <?php echo $readonly; ?> autocomplete="off">
                                                <label class="form-label">Address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <input type="checkbox" id="reminder_consent" name="reminder_consent" value="1" <?php if($data['consent_for_reminders'] == 1) echo 'checked' ?> class="form-control" <?php echo $disable; ?>>
                                            <label for="reminder_consent" class="form-label">Consent for Reminders</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <input type="checkbox" id="birthday_consent" name="birthday_consent" value="1" <?php if($data['consent_for_birthday_wishes	'] == 1) echo 'checked' ?> class="form-control" <?php echo $disable; ?>>
                                            <label for="birthday_consent" class="form-label">Consent for Birthday Wishes</label>
                                        </div>
                                    </div>
                                    
                                    <?php if ($view != true) { ?>
                                        <div class="col-sm-12" align="center">
                                            <button type="submit" class="btn btn-success btn-lg waves-effect">SAVE</button>
                                            <label id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <table>
                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button"
                                    class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                        </table>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
</section>

<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
    updatePhoneInputInstructions();
    document.getElementById('phone_number').addEventListener('input', function() {
        validatePhoneNumber();
    });

    document.getElementById('phone_code').addEventListener('change', function() {
        updatePhoneInputInstructions();
        validatePhoneNumber();
    });

    function updatePhoneInputInstructions() {
        var phoneCode = document.getElementById('phone_code').value;
        var phoneInput = document.getElementById('phone_number');

        switch (phoneCode) {
            case "+60":
                phoneInput.placeholder = "Enter a valid 8-9 digit without leading 0";
                break;
            case "+65":
                phoneInput.placeholder = "Enter a valid 8-digit number starting with 8 or 9";
                break;
            case "+91":
                phoneInput.placeholder = "Enter a 10-digit number starting with 6, 7, 8, or 9";
                break;
            default:
                phoneInput.placeholder = "Enter a valid phone number";
                break;
        }
    }

    function validatePhoneNumber() {
        var phoneCode = document.getElementById('phone_code').value;
        var phoneNumber = document.getElementById('phone_number').value;
        var errorMessage = document.getElementById('error-message');
        let isValid = false;
        
        errorMessage.style.display = 'none';

        var phoneValidationPatterns = {
            "+60": /^1[1-9]\d{7,8}$/, // Malaysia: Starts with 1, followed by 8 or 9 digits
            "+65": /^[89]\d{7}$/,     // Singapore: Starts with 8 or 9, followed by 7 digits
            "+91": /^[6789]\d{9}$/,    // India: Starts with 7, 8, or 9, followed by 9 digits
            "default": /^[0-9]{7,15}$/ // General validation for any number (7 to 15 digits)
        };

        if (phoneCode === "+60" && phoneNumber.startsWith('0')) {
            errorMessage.textContent = "Invalid phone number format: Cannot start with 0 for Malaysia (+60).";
            errorMessage.style.display = 'block';
            return;
        }

        var pattern = phoneValidationPatterns[phoneCode] || phoneValidationPatterns['default'];

        if (pattern && pattern.test(phoneNumber)) {
            checkPhoneNumberExistence();
        } else {
            errorMessage.textContent = "Invalid phone number format.";
            errorMessage.style.display = 'block';
        }
    }

    function checkPhoneNumberExistence() {
        var phoneCode = document.getElementById('phone_code').value;
        var phoneNumber = document.getElementById('phone_number').value;
        $.ajax({
            url: "<?php echo base_url(); ?>/devotee_management/check_phone_number", // Update this with the correct endpoint
            type: "POST",
            data: { phone_code: phoneCode, phone_number: phoneNumber },
            success: function(response) {
                response = JSON.parse(response);
                var errorMessage = document.getElementById('error-message');
                if (response.exists) {
                    console.log("Phone number exists.");
                    errorMessage.textContent = "Phone number exists.";
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none'; // Hide error message if phone number is valid
                }
            },
            error: function() {
                alert("Error checking phone number existence.");
            }
        });
    }

    $('#phone_number').on('blur', function() {
        checkPhoneNumberExistence();
    });

    $(document).ready(function () {
        if ($edit != true) {
            var phoneCode = $('#phone_code').val();
            var phoneNumber = $('#phone_number').val();
            if (phoneCode && phoneNumber) {
                checkPhoneNumberExistence();
            }

            var is_member = $('#is_member').val();
            if (is_member == 1) {
                checkMemberExists();
            }
        }
    });

    $('#is_member').on('change', function() {
        checkMemberExists();
    });

    function checkMemberExists() {
        var is_member = $('#is_member').val();
        if (is_member == 1) {
            var phoneCode = $('#phone_code').val();
            var phoneNumber = $('#phone_number').val();
            $.ajax({
                url: "<?php echo base_url(); ?>/devotee_management/check_member_exists", // Update this with the correct endpoint
                type: "POST",
                data: { phone_code: phoneCode, phone_number: phoneNumber },
                success: function(response) {
                    response = JSON.parse(response);
                    console.log('response:', response);
                    var memberMessage = document.getElementById('member-message');
                    if (response.member_no) {
                        $('#id').val(response.id);
                        $('#name').val(response.name);
                        $('#ic_no').val(response.ic_no);
                        $('#email').val(response.email);
                        $('#dob').val(response.dob);
                        $('#address').val(response.address);
                        $('#member_no').val(response.member_no);

                        $('#member_no').closest('.col-sm-1').show();
                        memberMessage.style.display = 'block';
                    } else {
                        $('#member_no').val('');
                        $('#member_no').closest('.col-sm-1').hide();
                    }
                },
                error: function () {
                    alert('Error checking phone number.');
                }
            });
        }
    };
</script>


<script>
    $("#rasi_id").change(function () {
        var rasi = $("#rasi_id").val();

        if (rasi !== "") {
            $.ajax({
                url: '<?php echo base_url(); ?>/devotee_management/get_natchathram',
                type: 'POST',
                data: { rasi_id: rasi },
                dataType: 'json',
                success: function (response) {
                    var natchathramDropdown = $("#natchathra_id");
                    natchathramDropdown.empty();
                    natchathramDropdown.append('<option value="">Select Natchathiram</option>');

                    if (response.natchathrams && response.natchathrams.length > 0) {
                        response.natchathrams.forEach(function (item) {
                            natchathramDropdown.append(
                                '<option value="' + item.id + '" data-name="' + item.name_eng + '">' + item.name_eng + '</option>'
                            );
                        });
                    }
                    $("#natchathra_id").selectpicker('refresh');
                }
            });
        }
    });

</script>

<script>
    $("#clear").click(function () {
        $("input").val("");
        $("#is_pledge").prop('checked', false);
        togglePledgeAmount();
    });

    // $("#submit").click(function () {
    //     var formData = $("form").serialize();
    //     console.log("Form data being sent:", formData);

    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url(); ?>/devotee_management/save",
    //         data: formData,
    //         beforeSend: function () {
    //             $('input[type=submit]').prop('disabled', true);
    //             $("#loader").show();
    //             $("#submit").attr("disabled", true);
    //         },
    //         success: function (data) {
    //             console.log("Response received:", data);
    //             obj = jQuery.parseJSON(data);
    //             if (obj.err != '') {
    //                 $('#alert-modal').modal('show', { backdrop: 'static' });
    //                 $("#spndeddelid").text(obj.err);
    //             } else {
    //                 if ($("#print").prop('checked') == true) {
    //                     printData(obj.id);
    //                 }
    //                 else
    //                     window.location.reload(true);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.log("AJAX Error:", error);
    //             console.log("Response:", xhr.responseText);
    //             alert("Error occurred: " + error);
    //         },
    //         complete: function (data) {
    //             // Hide image container
    //             $('input[type=submit]').prop('disabled', false);
    //             $("#loader").hide();
    //             $("#submit").attr("disabled", false);
    //         }
    //     });
    // });
</script>