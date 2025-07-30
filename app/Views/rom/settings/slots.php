<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>TIME SLOT SETTINGS</h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <?= session()->getFlashdata('success') ?>
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Slot -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            ADD/EDIT TIME SLOT
                            <small>Configure available time slots for marriage registrations</small>
                        </h2>
                    </div>
                    <div class="body">
                        <form method="post" action="<?= base_url('rom/saveSlot') ?>" id="slotForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="slot_id">
                            <div class="row clearfix">
                                <div class="col-md-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="slot_name" id="slot_name" class="form-control" required>
                                            <label class="form-label">Slot Name *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                                            <label class="form-label">Start Time *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                                            <label class="form-label">End Time *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="max_bookings" id="max_bookings" 
                                                   class="form-control" min="1" value="3" required>
                                            <label class="form-label">Max Bookings *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                                               class="filled-in chk-col-green" checked>
                                        <label for="is_active">Active</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> SAVE
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slots List -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            TIME SLOTS LIST
                            <small>All configured time slots</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Slot Name</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Duration</th>
                                        <th>Max Bookings</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($slots as $key => $slot): ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $slot['slot_name'] ?></td>
                                        <td><?= date('h:i A', strtotime($slot['start_time'])) ?></td>
                                        <td><?= date('h:i A', strtotime($slot['end_time'])) ?></td>
                                        <td>
                                            <?php
                                            $start = new DateTime($slot['start_time']);
                                            $end = new DateTime($slot['end_time']);
                                            $diff = $start->diff($end);
                                            echo $diff->format('%h hr %i min');
                                            ?>
                                        </td>
                                        <td><?= $slot['max_bookings'] ?></td>
                                        <td>
                                            <span class="label label-<?= $slot['is_active'] ? 'success' : 'danger' ?>">
                                                <?= $slot['is_active'] ? 'ACTIVE' : 'INACTIVE' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-info waves-effect" 
                                                    onclick="editSlot(<?= htmlspecialchars(json_encode($slot)) ?>)">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger waves-effect" 
                                                    onclick="deleteSlot(<?= $slot['id'] ?>)">
                                                <i class="material-icons">delete</i>
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

        <!-- Slot Templates -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            QUICK SLOT TEMPLATES
                            <small>Click to quickly add standard slot configurations</small>
                        </h2>
                    </div>
                    <div class="body">
                        <button type="button" class="btn btn-primary waves-effect" onclick="addTemplate('morning')">
                            <i class="material-icons">wb_sunny</i> Add Morning Slots (9AM-12PM)
                        </button>
                        <button type="button" class="btn btn-info waves-effect" onclick="addTemplate('afternoon')">
                            <i class="material-icons">wb_cloudy</i> Add Afternoon Slots (2PM-5PM)
                        </button>
                        <button type="button" class="btn btn-warning waves-effect" onclick="addTemplate('evening')">
                            <i class="material-icons">brightness_3</i> Add Evening Slots (5PM-7PM)
                        </button>
                        <button type="button" class="btn btn-success waves-effect" onclick="addTemplate('30min')">
                            <i class="material-icons">access_time</i> Add 30-Min Slots (Full Day)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function editSlot(slot) {
    $('#slot_id').val(slot.id);
    $('#slot_name').val(slot.slot_name);
    $('#start_time').val(slot.start_time);
    $('#end_time').val(slot.end_time);
    $('#max_bookings').val(slot.max_bookings);
    $('#is_active').prop('checked', slot.is_active == 1);
    
    // Update labels
    $('#slot_name').parent().addClass('focused');
    $('#start_time').parent().addClass('focused');
    $('#end_time').parent().addClass('focused');
    $('#max_bookings').parent().addClass('focused');
    
    // Scroll to form
    $('html, body').animate({
        scrollTop: $("#slotForm").offset().top - 100
    }, 500);
}

function deleteSlot(id) {
    if (confirm('Are you sure you want to delete this slot?')) {
        window.location.href = '<?= base_url('rom/deleteSlot/') ?>' + id;
    }
}

function addTemplate(type) {
    if (!confirm('This will add multiple slots. Continue?')) return;
    
    var templates = {
        morning: [
            {name: 'Morning Slot 1', start: '09:00', end: '09:30'},
            {name: 'Morning Slot 2', start: '09:30', end: '10:00'},
            {name: 'Morning Slot 3', start: '10:00', end: '10:30'},
            {name: 'Morning Slot 4', start: '10:30', end: '11:00'},
            {name: 'Morning Slot 5', start: '11:00', end: '11:30'},
            {name: 'Morning Slot 6', start: '11:30', end: '12:00'}
        ],
        afternoon: [
            {name: 'Afternoon Slot 1', start: '14:00', end: '14:30'},
            {name: 'Afternoon Slot 2', start: '14:30', end: '15:00'},
            {name: 'Afternoon Slot 3', start: '15:00', end: '15:30'},
            {name: 'Afternoon Slot 4', start: '15:30', end: '16:00'},
            {name: 'Afternoon Slot 5', start: '16:00', end: '16:30'},
            {name: 'Afternoon Slot 6', start: '16:30', end: '17:00'}
        ],
        evening: [
            {name: 'Evening Slot 1', start: '17:00', end: '17:30'},
            {name: 'Evening Slot 2', start: '17:30', end: '18:00'},
            {name: 'Evening Slot 3', start: '18:00', end: '18:30'},
            {name: 'Evening Slot 4', start: '18:30', end: '19:00'}
        ],
        '30min': []
    };
    
    // Generate 30-min slots for full day
    if (type === '30min') {
        var hour = 9;
        var min = 0;
        var slotNum = 1;
        
        while (hour < 19) {
            var startHour = hour.toString().padStart(2, '0');
            var startMin = min.toString().padStart(2, '0');
            var endHour = min === 30 ? (hour + 1).toString().padStart(2, '0') : hour.toString().padStart(2, '0');
            var endMin = min === 30 ? '00' : '30';
            
            templates['30min'].push({
                name: 'Slot ' + slotNum,
                start: startHour + ':' + startMin,
                end: endHour + ':' + endMin
            });
            
            min = min === 30 ? 0 : 30;
            if (min === 0) hour++;
            slotNum++;
        }
    }
    
    // TODO: Implement batch insert
    alert('Template functionality to be implemented with batch insert');
}

// Time validation
$('#end_time').change(function() {
    var start = $('#start_time').val();
    var end = $(this).val();
    
    if (start && end && start >= end) {
        alert('End time must be after start time!');
        $(this).val('');
    }
});
</script>