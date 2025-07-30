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
            <div class="col-sm-4" id="package_div" style="display: none;">
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

<script>
    $(document).ready(function() {
        // Initialize editors
        var messageEditor = new Quill('#editor-message', { theme: 'snow' });
        var imageCaptionEditor = new Quill('#editor-image-caption', { theme: 'snow' });
        var pdfCaptionEditor = new Quill('#editor-pdf-caption', { theme: 'snow' });

        function loadDevotees() {
            var devoteeType = $('#filter_devotee_type').val() || null;
            var moduleId = $('#filter_module').val() || null;
            var packageId = $('#filter_package').val() || null;
            
            $.ajax({
                url: '<?php echo base_url(); ?>/reminders/getDevotees',
                method: 'POST',
                data: { is_member: devoteeType, user_module_tag: moduleId, package_id: packageId },
                success: function(data) {
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

        // Initial load
        loadDevotees();
    });
</script>