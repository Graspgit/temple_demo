<?php global $lang; ?>
<style>
    .kazhanji_option_text,
    .kazhanji_option_image {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <h2> Devotee Management <small><b>Devotee List</b></small></h2>
        </div>

        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="header">
                        <div class="row clearfix" style="margin-top: 15px;">
                            <div class="col-sm-2">
                                <div class="form-group form-float">
									<div class="form-line">
                                        <label for="devotee_type" class="form-label">Devotee Type</label>
                                        <select id="devotee_type" class="form-control">
                                            <option value="">-- Select Devotee Type --</option>
                                            <option value="1">Member</option>
                                            <option value="0">Non Member</option>
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-float">
									<div class="form-line">
                                        <label for="module" class="form-label">Module</label>
                                        <select id="module" class="form-control">
                                            <option value="">-- Select Module --</option>
                                            <?php foreach ($modules as $module) { ?>
                                                <option value="<?php echo $module['id']; ?>"><?php echo $module['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group form-float" id = "package_div" style="display: none;">
									<div class="form-line">
                                        <label for="package" class="form-label">Package</label>
                                        <select id="package" class="form-control">
                                            <option value="">-- Select Package --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" align="right">
                                <a href="<?php echo base_url(); ?>/devotee_management/add"><button type="button" class="btn bg-deep-purple waves-effect">Add Devotee</button></a>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <div class="row clearfix" style="margin-top: 15px;">
                            <div class="col-sm-9"></div>
                            <div class="col-sm-3">
                                <div class="form-group form-float">
									<div class="form-line">
                                        <input type="text" id="search-input" class="form-control" placeholder="Search for Devotees">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable" id="devotee_table">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">S.No</th>
                                                <th style="text-align: center;">Devotee Name</th>
                                                <th style="text-align: center;">DOB</th>
                                                <th style="text-align: center;">Mobile Number</th>
                                                <th style="text-align: center;">Email</th>
                                                <th style="text-align: center;">Type</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        function loadDevotees() {
            var devoteeType = $('#devotee_type').val() || null;
            var moduleId = $('#module').val() || null;
            var packageId = $('#package').val() || null;
            $.ajax({
                url: '<?php echo base_url(); ?>/devotee_management/getDevotees',
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
                            '<td style="text-align: center;">' + $i++ + '</td>' +
                            '<td style="text-align: center;">' + devotee.name + '</td>' +
                            '<td style="text-align: center;">' + (devotee.dob || '') + '</td>' +
                            '<td style="text-align: center;">' + phone + '</td>' +
                            '<td style="text-align: center;">' + (devotee.email || '') + '</td>' +
                            '<td style="text-align: center;">' + typeText + '</td>' +
                            '<td style="text-align: center;"><a class="btn btn-success btn-rad" href="<?php echo base_url(); ?>/devotee_management/view/' + devotee.id + '"><i class="fa fa-eye"></i> </a><a class="btn btn-primary btn-rad" href="<?php echo base_url(); ?>/devotee_management/edit/' + devotee.id + '"><i class="fa fa-edit"></i> </a></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                }
            });
        }

        $('#check_all_devotees').on('change', function() {
            $('.devotee_checkbox').prop('checked', this.checked);
        });

        $('#devotee_type, #module, #package').on('change', function() {
            loadDevotees();
        });

        $('#module').on('change', function() {
            var moduleValue = $('#module').val();
            if (moduleValue == 6) {
                loadPackages()
            } else {
                $('#package_div').hide();
            }
        });

        function loadPackages() {
            var moduleId = $('#module').val();
            $.ajax({
                url: '<?php echo base_url(); ?>/devotee_management/getPackages',
                method: 'POST',
                data: { module_id: moduleId },
                success: function(data) {
                    response = JSON.parse(data);
                    var filter = $('#package');
                    filter.empty();
                    var row = '<option value="">Select Package</option>';
                    filter.append(row);
                    $.each(response, function(index, package) {
                        var row = '<option value="' + package.id + '" data-id="' + package.id + '">' + package.name + '</option>';
                        filter.append(row);
                    });

                    $("#package").selectpicker("refresh");
                    $('#package_div').show();
                }
            });
        }

        loadDevotees();
    });
</script>

<script>
    document.getElementById('search-input').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase();
        var table = document.getElementById('devotee_table');
        var rows = table.getElementsByTagName('tr');
        
        for (var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName('td');
            var matchFound = false;

            for (var j = 0; j < cells.length; j++) {
                var cell = cells[j];
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    matchFound = true;
                    break;
                }
            }

            if (matchFound) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
</script>
