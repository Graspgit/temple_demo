<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STAFF COMMISSION SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#commissionModal">
                                    <i class="material-icons">add</i> Add Commission Setting
                                </button>
                            </li>
                            <li>
                                <a href="<?= base_url('commissionhr') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Commission Type</th>
                                        <th>Percentage</th>
                                        <th>Fixed Amount</th>
                                        <th>Effective Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($commission_settings as $setting): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $setting['staff_code'] ?></td>
                                        <td><?= $setting['first_name'] . ' ' . $setting['last_name'] ?></td>
                                        <td><?= $setting['commission_type'] ?></td>
                                        <td><?= $setting['commission_percentage'] ? $setting['commission_percentage'] . '%' : '-' ?></td>
                                        <td><?= $setting['commission_amount'] ? 'RM ' . number_format($setting['commission_amount'], 2) : '-' ?></td>
                                        <td><?= date('d-M-Y', strtotime($setting['effective_date'])) ?></td>
                                        <td><?= $setting['end_date'] ? date('d-M-Y', strtotime($setting['end_date'])) : 'Ongoing' ?></td>
                                        <td>
                                            <span class="label <?= $setting['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $setting['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-commission" 
                                                data-id="<?= $setting['id'] ?>"
                                                data-staff-id="<?= $setting['staff_id'] ?>"
                                                data-type="<?= $setting['commission_type'] ?>"
                                                data-percentage="<?= $setting['commission_percentage'] ?>"
                                                data-amount="<?= $setting['commission_amount'] ?>"
                                                data-effective="<?= $setting['effective_date'] ?>"
                                                data-end="<?= $setting['end_date'] ?>"
                                                data-status="<?= $setting['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Commission Setting Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('commissionhr/saveSetting') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="commission_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Commission Setting</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff <span class="text-danger">*</span></label>
                                <select name="staff_id" id="commission_staff_id" class="form-control show-tick" required data-live-search="true">
                                    <option value="">-- Select Staff --</option>
                                    <?php foreach($staff as $emp): ?>
                                        <option value="<?= $emp['id'] ?>">
                                            <?= $emp['staff_code'] ?> - <?= $emp['first_name'] . ' ' . $emp['last_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="commission_type" id="commission_type" class="form-control" required>
                                    <label class="form-label">Commission Type <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="commission_percentage" id="commission_percentage" class="form-control" step="0.01">
                                    <label class="form-label">Commission Percentage (%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="commission_amount" id="commission_amount" class="form-control" step="0.01">
                                    <label class="form-label">Fixed Commission Amount (RM)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" name="effective_date" id="effective_date" class="form-control" required>
                                    <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" name="end_date" id="end_date" class="form-control">
                                    <label class="form-label">End Date (Optional)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="commission_status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<script>
$(function() {
    $('.js-basic-example').DataTable({
        responsive: true
    });

    $('.show-tick').selectpicker();

    // Leave Application Scripts
    $('#from_date, #to_date').on('change', function() {
        var from = $('#from_date').val();
        var to = $('#to_date').val();
        
        if (from && to) {
            var fromDate = new Date(from);
            var toDate = new Date(to);
            var days = Math.ceil((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;
            $('#num_days').val(days);
        }
    });

    $('#staff_id, #leave_type_id').on('change', function() {
        var staffId = $('#staff_id').val();
        var leaveTypeId = $('#leave_type_id').val();
        
        if (staffId && leaveTypeId) {
            $.get('<?= base_url("leave/balance/") ?>' + staffId + '?type=' + leaveTypeId + '&year=' + new Date().getFullYear(), function(data) {
                $('#leave_balance').val(data.balance + ' days');
            });
        }
    });

    // Calendar initialization
    if ($('#calendar').length) {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '<?= base_url("leave/calendar-events") ?>',
            eventColor: '#2196F3'
        });
        calendar.render();
    }

    // Commission Settings
    $('.edit-commission').on('click', function() {
        var data = $(this).data();
        $('#commission_id').val(data.id);
        $('#commission_staff_id').val(data.staffId).selectpicker('refresh');
        $('#commission_type').val(data.type);
        $('#commission_percentage').val(data.percentage);
        $('#commission_amount').val(data.amount);
        $('#effective_date').val(data.effective);
        $('#end_date').val(data.end);
        $('#commission_status').val(data.status);
        
        $('#commissionModal').modal('show');
    });
});
</script>