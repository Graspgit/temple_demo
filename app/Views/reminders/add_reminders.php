<?php global $lang; ?>
<style>
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-size: 18px;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Success Modal */
    #successModal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
    }

    #closeModal {
        margin-top: 10px;
        padding: 5px 10px;
        background: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    #closeModal:hover {
        background: #218838;
    }
    .leftnav {
        flex-direction: column; width: 200px; background: #8BC34A; position:absolute;
    }
    .nav-tabs + .tab-content {
        padding: 0;
    }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        background-color: rgb(0 0 0 / 32%);
    }
    .nav-tabs > li > a:before { border-bottom: 0; }
    .nav-tabs > li {
        position: relative;
        top: 0px;
        left: 0px;
    }
    .nav-tabs > li > a { color: #393737 !important; margin-right:0; }
    .nav-tabs li.active a { color: #000 !important; }
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
        <div class="block-header">
            <h2>
                Reminders and Messages
            </h2>
        </div>

        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="body">
                        <ul class="nav nav-tabs tab-nav-right leftnav" role="tablist" style="">
                            <li role="presentation" class="active"><a href="#custom_messages" data-toggle="tab"
                                    aria-expanded="true">Custom Messages</a></li>
                            <li role="presentation" class=""><a href="#auto_messages" data-toggle="tab"
                                    aria-expanded="false">Auto Messages</a></li>
                        </ul>
                    
                        <div class="tab-content" style="margin-left: 210px;">
                            <div role="tabpanel" class="tab-pane fade in active" id="custom_messages">
                                <div class="">
                                    <div class="container-fluid">
                                        <h3 style="text-align: center;">Custom Messages</h3>
                                        <hr>
                                        <!-- Add this inside your form -->
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <input type="radio" name="message_type" value="text" id="text_message" checked>
                                                <label for="text_message"> Text Message</label>
                                                <input type="radio" name="message_type" value="image" id="image_message">
                                                <label for="image_message" style="margin-left: 20px;"> Image</label>
                                                <input type="radio" name="message_type" value="pdf" id="pdf_message">
                                                <label for="pdf_message" style="margin-left: 20px;"> PDF</label>
                                            </div>
                                        </div>

                                        <!-- Text Editor -->
                                        <div class="row clearfix message-section">
                                            <div class="col-sm-12">
                                                <label>Message</label>
                                                <div id="editor-message" style="height: 150px; border: 1px solid #ccc;"></div>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="row clearfix image-section" style="display: none;">
                                            <div class="col-sm-4">
                                                <p><strong>Upload Image</strong> (Max: 2MB)</p>
                                                <input type="file" id="image_upload" accept="image/*" class="form-control">
                                                <img id="image_preview" src="#" style="margin-top:10px; display:none; max-width:50%; height:auto;" />
                                            </div>
                                            <div class="col-sm-8">
                                                <label>Image Caption</label>
                                                <div id="editor-image-caption" style="height: 100px; border: 1px solid #ccc;"></div>
                                            </div>
                                        </div>

                                        <!-- PDF Upload -->
                                        <div class="row clearfix pdf-section" style="display: none;">
                                            <div class="col-sm-4">
                                                <p><strong>Upload PDF</strong> (Max: 5MB)</p>
                                                <input type="file" id="pdf_upload" accept="application/pdf" class="form-control">
                                            </div>
                                            <div class="col-sm-8">
                                                <label>PDF Caption</label>
                                                <div id="editor-pdf-caption" style="height: 100px; border: 1px solid #ccc;"></div>
                                            </div>
                                        </div>


                                        <div class="row clearfix" style="margin-top: 15px;">
                                            <div class="col-sm-4">
                                                <label for="filter_devotee_type">Devotee Type</label>
                                                <select id="filter_devotee_type" class="form-control">
                                                    <option value="">-- Select Devotee Type --</option>
                                                    <option value="1">Member</option>
                                                    <option value="0">Non Member</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="filter_module">Module</label>
                                                <select id="filter_module" class="form-control">
                                                    <option value="">-- Select Module --</option>
                                                    <?php foreach ($modules as $module) { ?>
                                                        <option value="<?php echo $module['id']; ?>"><?php echo $module['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4" id = "package_div" style="display: none;">
                                                <label for="filter_package">Package</label>
                                                <select id="filter_package" class="form-control">
                                                    <option value="">-- Select Package --</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row clearfix" style="margin-top: 15px;">
                                            <div class="col-sm-12">
                                                <div class="table-responsive" style="max-height: 500px; overflow: auto !important;">
                                                    <table class="table table-bordered table-striped table-hover dataTable" id="devotee_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;">#</th>
                                                                <th style="text-align: center;">Name</th>
                                                                <th style="text-align: center;">DOB</th>
                                                                <th style="text-align: center;">Mobile Number</th>
                                                                <th style="text-align: center;">Email</th>
                                                                <th style="text-align: center;">Type</th>
                                                                <th style="text-align: center;"><input type="checkbox" id="check_all_devotees">
                                                                    <label for="check_all_devotees">Select</label></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix" style="margin-top: 15px;">
                                            <div class="col-sm-12" style="text-align: right;">
                                                <button id="send_custom_message" class="btn btn-primary">Send Message</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="auto_messages">
                                <div class="">
                                    <div class="container-fluid">
                                        <h4>Auto Messages</h4>
                                        <hr>
                                        <ul class="nav nav-tabs tab-nav-right" role="tablist" style="flex-direction: row;">
                                            <li role="presentation" class="active"><a href="#birthday" data-toggle="tab" aria-expanded="true">Birthday</a></li>
                                            <li role="presentation" class=""><a href="#booking_messages" data-toggle="tab" aria-expanded="false">Booking Messages</a></li>
                                            <li role="presentation" class=""><a href="#repayment_messages" data-toggle="tab" aria-expanded="false">Repayment Messages</a></li>
                                            <li role="presentation" class=""><a href="#order_messages" data-toggle="tab" aria-expanded="false">Order Messages</a></li>
                                        </ul>
                                        <div class="tab-content" style="margin-top: 15px;">
                                            <div role="tabpanel" class="tab-pane fade in active" id="birthday">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12" style="max-height: 500px; overflow: auto !important;">
                                                        <table class="table table-bordered table-striped" id="birthday_devotee_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>DOB</th>
                                                                    <th>Mobile Number</th>
                                                                    <th>Email</th>
                                                                    <th>Type</th>
                                                                    <th><input type="checkbox" id="check_all_birthday"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row clearfix" style="margin-top: 15px;">
                                                    <div class="col-sm-12" style="text-align: right;">
                                                        <label><input type="checkbox" id="toggle_auto_trigger" checked> Auto Trigger</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="booking_messages">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <table class="table table-bordered table-striped" id="booking_module_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Module Name</th>
                                                                    <th><input type="checkbox" id="check_all_booking_modules"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row clearfix" style="margin-top: 15px;">
                                                    <div class="col-sm-12">
                                                        <label for="booking_message_template">Message Template:</label>
                                                        <textarea id="booking_message_template" class="form-control" rows="4" placeholder="Enter message template here..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="repayment_messages">
                                                <p>Repayment messages content will be added in next phase.</p>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="order_messages">
                                                <p>Order messages content will be added in next phase.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div id="loader" style="display: none;">
        <div class="spinner"></div>
        <p>Sending Messages...</p>
    </div>

    <div id="alert-successModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style=" max-width: 500px;">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;">
                        <br><i class="mdi mdi-checkbox-marked-circle-outline" style="font-size:42px; color:green;"></i>
                    </p>
                    <h4 style="text-align:center;" id="spndeddelid1"></h4>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-info" id="okay" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quill.js Editor CDN -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Material Design Icons -->
<link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

<script>
    $(document).ready(function() {
        function loadDevotees() {
            var devoteeType = $('#filter_devotee_type').val() || null;
            var moduleId = $('#filter_module').val() || null;
            var packageId = $('#filter_package').val() || null;
            $.ajax({
                url: '<?php echo base_url(); ?>/reminders/getDevotees',
                method: 'POST',
                data: { is_member: devoteeType, user_module_tag: moduleId, package_id: packageId },
                success: function(data) {
                    console.log('devotees:',data);
                    var tbody = $('#devotee_table tbody');
                    tbody.empty();
                    $i = 1;
                    data.forEach(function(devotee) {
                        var typeText = devotee.is_member == 1 ? 'Member' : 'Non Member';
                        var phone = devotee.phone_code + ' ' + devotee.phone_number;
                        var row = '<tr>' +
                            '<td>' + $i++ + '</td>' +
                            '<td>' + devotee.name + '</td>' +
                            '<td>' + (devotee.dob || '') + '</td>' +
                            '<td>' + phone + '</td>' +
                            '<td>' + (devotee.email || '') + '</td>' +
                            '<td>' + typeText + '</td>' +
                            '<td><input type="checkbox" class="devotee_checkbox" id="check_' + devotee.id + '" data-id="' + devotee.id + '"><label for="check_' + devotee.id + '"></label></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            });
        }

        $('#check_all_devotees').on('change', function() {
            $('.devotee_checkbox').prop('checked', this.checked);
        });

        $('#filter_devotee_type, #filter_module, #filter_package').on('change', function() {
            loadDevotees();
        });

        $('#filter_module').on('change', function() {
            var moduleValue = $('#filter_module').val();
            console.log(moduleValue);
            if (moduleValue == 6) {
                loadPackages()
            } else {
                $('#package_div').hide();
            }
        });

        function loadPackages() {
            var moduleId = $('#filter_module').val();
            $.ajax({
                url: '<?php echo base_url(); ?>/reminders/getPackages',
                method: 'POST',
                data: { module_id: moduleId },
                success: function(data) {
                    response = JSON.parse(data);
                    var filter = $('#filter_package');
                    filter.empty();
                    var row = '<option value="">Select Package</option>';
                    filter.append(row);
                    $.each(response, function(index, package) {
                        var row = '<option value="' + package.id + '" data-id="' + package.id + '">' + package.name + '</option>';
                        filter.append(row);
                    });

                    $("#filter_package").selectpicker("refresh");
                    $('#package_div').show();
                }
            });
        }

        $('#closeModal').on('click', function() {
            $('#successModal').hide();
            $('#custom_message_text').val('');
            $('#check_all_devotees').prop('checked', false);
            loadDevotees();
        });

        loadDevotees();
    });

    var messageEditor = new Quill('#editor-message', { theme: 'snow' });
    var imageCaptionEditor = new Quill('#editor-image-caption', { theme: 'snow' });
    var pdfCaptionEditor = new Quill('#editor-pdf-caption', { theme: 'snow' });

    $('input[name="message_type"]').on('change', function () {
        let type = $(this).val();
        $('.message-section, .image-section, .pdf-section').hide();
        if (type === 'text') {
            $('.message-section').show();
        } else if (type === 'image') {
            $('.image-section').show();
        } else if (type === 'pdf') {
            $('.pdf-section').show();
        }
    });

    // Preview image
    $("#image_upload").on("change", function () {
        const file = this.files[0];
        if (file && file.size <= 2 * 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#image_preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            alert("Image size must be less than 2MB.");
            $(this).val('');
            $('#image_preview').hide();
        }
    });

    $('#send_custom_message').on('click', function (e) {
        e.preventDefault();

        const selectedType = $('input[name="message_type"]:checked').val();
        const selectedDevotees = $('.devotee_checkbox:checked').map(function () {
            return $(this).data('id');
        }).get();

        if (selectedDevotees.length === 0) {
            alert("Please select at least one devotee.");
            return;
        }

        const formData = new FormData();
        formData.append("message_type", selectedType);
        formData.append("devotee_ids", JSON.stringify(selectedDevotees));

        if (selectedType === "text") {
            const message = messageEditor.root.innerHTML.trim();
            if (!message) return alert("Please enter a message.");
            formData.append("message", message);

        } else if (selectedType === "image") {
            const imageFile = $('#image_upload')[0].files[0];
            if (!imageFile) return alert("Please upload an image.");
            if (imageFile.size > 2 * 1024 * 1024) return alert("Image size must be < 2MB.");
            formData.append("image", imageFile);
            formData.append("caption", imageCaptionEditor.root.innerHTML.trim());

        } else if (selectedType === "pdf") {
            const pdfFile = $('#pdf_upload')[0].files[0];
            if (!pdfFile) return alert("Please upload a PDF.");
            if (pdfFile.size > 5 * 1024 * 1024) return alert("PDF size must be < 5MB.");
            formData.append("document", pdfFile);
            formData.append("caption", pdfCaptionEditor.root.innerHTML.trim());
        }

        $('#loader').show();

        $.ajax({
            url: '<?php echo base_url(); ?>/reminders/send_Ultra_Message',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#loader').hide();
                if (response.success && response.sent_count > 0) {
                    $('#alert-successModal').modal('show', { backdrop: 'static', keyboard: false });
                    $("#spndeddelid1").text(response.sent_count + " Messages sent Successfully!");
                    $("#spndeddelid1").css("color", "green");

                    $(".mdi").removeClass("mdi-alert-circle-outline").addClass("mdi-checkbox-marked-circle-outline");
                    $(".mdi").css("color", "green");

                } else {
                    $('#alert-successModal').modal('show', { backdrop: 'static', keyboard: false });
                    $("#spndeddelid1").text("No messages were sent. Please try again!");
                    $("#spndeddelid1").css("color", "red");

                    $(".mdi").removeClass("mdi-checkbox-marked-circle-outline").addClass("mdi-alert-circle-outline");
                    $(".mdi").css("color", "red");
                }
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            },
            error: function (xhr) {
                $('#loader').hide();
                const msg = xhr.responseJSON?.error || 'Failed to send messages.';
                alert(msg);
            }
        });
    });

</script>

