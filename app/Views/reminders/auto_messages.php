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

<script>
    $(document).ready(function() {
        // Load birthday devotees
        function loadBirthdayDevotees() {
            $.ajax({
                url: '<?php echo base_url(); ?>/reminders/getBirthdayDevotees',
                method: 'POST',
                success: function(data) {
                    var tbody = $('#birthday_devotee_table tbody');
                    tbody.empty();
                    
                    data.forEach(function(devotee) {
                        var typeText = devotee.is_member == 1 ? 'Member' : 'Non Member';
                        var phone = devotee.phone_code + ' ' + devotee.phone_number;
                        var row = '<tr>' +
                            '<td>' + devotee.name + '</td>' +
                            '<td>' + (devotee.dob || '') + '</td>' +
                            '<td>' + phone + '</td>' +
                            '<td>' + (devotee.email || '') + '</td>' +
                            '<td>' + typeText + '</td>' +
                            '<td><input type="checkbox" class="birthday_checkbox" id="birthday_check_' + devotee.id + '" data-id="' + devotee.id + '"><label for="birthday_check_' + devotee.id + '"></label></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            });
        }

        // Load booking modules
        function loadBookingModules() {
            $.ajax({
                url: '<?php echo base_url(); ?>/reminders/getBookingModules',
                method: 'POST',
                success: function(data) {
                    var tbody = $('#booking_module_table tbody');
                    tbody.empty();
                    
                    data.forEach(function(module) {
                        var row = '<tr>' +
                            '<td>' + module.name + '</td>' +
                            '<td><input type="checkbox" class="module_checkbox" id="module_check_' + module.id + '" data-id="' + module.id + '"><label for="module_check_' + module.id + '"></label></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            });
        }

        $('#check_all_birthday').on('change', function() {
            $('.birthday_checkbox').prop('checked', this.checked);
        });

        $('#check_all_booking_modules').on('change', function() {
            $('.module_checkbox').prop('checked', this.checked);
        });

        // Initial loads
        loadBirthdayDevotees();
        loadBookingModules();
    });
</script>