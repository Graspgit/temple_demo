<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>CALENDAR AVAILABILITY SETTINGS</h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <?= session()->getFlashdata('success') ?>
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <!-- Calendar View -->
        <div class="row clearfix">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            AVAILABILITY CALENDAR
                            <small>Click on dates to mark as unavailable. Red dates are blocked.</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="header bg-cyan">
                        <h2>QUICK ACTIONS</h2>
                    </div>
                    <div class="body">
                        <button class="btn btn-block btn-primary waves-effect" onclick="blockWeekends()">
                            <i class="material-icons">weekend</i> Block All Weekends
                        </button>
                        <button class="btn btn-block btn-warning waves-effect" onclick="blockPublicHolidays()">
                            <i class="material-icons">event_busy</i> Block Public Holidays
                        </button>
                        <button class="btn btn-block btn-info waves-effect" onclick="blockDateRange()">
                            <i class="material-icons">date_range</i> Block Date Range
                        </button>
                        <button class="btn btn-block btn-success waves-effect" onclick="unblockAll()">
                            <i class="material-icons">event_available</i> Unblock All Dates
                        </button>
                    </div>
                </div>

                <!-- Blocked Dates List -->
                <div class="card">
                    <div class="header bg-red">
                        <h2>BLOCKED DATES</h2>
                    </div>
                    <div class="body" style="max-height: 400px; overflow-y: auto;">
                        <ul class="list-unstyled" id="blockedDatesList">
                            <?php foreach($unavailable_dates as $date): ?>
                            <li class="blocked-date-item">
                                <div class="row">
                                    <div class="col-xs-8">
                                        <strong><?= date('d M Y', strtotime($date['date'])) ?></strong><br>
                                        <small><?= $date['reason'] ?: 'No reason specified' ?></small>
                                    </div>
                                    <div class="col-xs-4 text-right">
                                        <button class="btn btn-xs btn-danger waves-effect" 
                                                onclick="unblockDate('<?= $date['date'] ?>')">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </div>
                                </div>
                                <hr style="margin: 10px 0;">
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #4CAF50;"></span>
                                    Available Dates
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #F44336;"></span>
                                    Blocked Dates
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #2196F3;"></span>
                                    Today
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #9E9E9E;"></span>
                                    Past Dates
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Date Availability Modal -->
<div class="modal fade" id="dateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="dateModalTitle">Date Availability</h4>
            </div>
            <form method="post" action="<?= base_url('rom/saveCalendarAvailability') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="date" id="modal_date">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <p><strong>Date:</strong> <span id="modal_date_display"></span></p>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="radio" name="is_available" id="available" value="1" 
                                       class="with-gap radio-col-green" checked>
                                <label for="available">Available</label>
                                
                                <input type="radio" name="is_available" id="unavailable" value="0" 
                                       class="with-gap radio-col-red">
                                <label for="unavailable">Unavailable</label>
                            </div>
                        </div>
                        <div class="col-sm-12" id="reasonDiv" style="display: none;">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="reason" id="reason" class="form-control">
                                    <label class="form-label">Reason for blocking</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Date Range Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Block Date Range</h4>
            </div>
            <form method="post" action="<?= base_url('rom/blockDateRange') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" name="start_date" class="form-control" required>
                                    <label class="form-label">Start Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="date" name="end_date" class="form-control" required>
                                    <label class="form-label">End Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="reason" class="form-control" required>
                                    <label class="form-label">Reason</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">BLOCK DATES</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var calendar;
var blockedDates = <?= json_encode(array_map(function($d) { return $d['date']; }, $unavailable_dates)) ?>;

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        dateClick: function(info) {
            if (info.date < new Date().setHours(0,0,0,0)) {
                alert('Cannot modify past dates!');
                return;
            }
            
            $('#modal_date').val(info.dateStr);
            $('#modal_date_display').text(info.date.toLocaleDateString('en-GB', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }));
            
            // Check if date is already blocked
            if (blockedDates.includes(info.dateStr)) {
                $('#unavailable').prop('checked', true);
                $('#reasonDiv').show();
            } else {
                $('#available').prop('checked', true);
                $('#reasonDiv').hide();
            }
            
            $('#dateModal').modal('show');
        },
        dayCellDidMount: function(arg) {
            // Color blocked dates
            if (blockedDates.includes(arg.date.toISOString().split('T')[0])) {
                arg.el.style.backgroundColor = '#ffebee';
                arg.el.style.color = '#c62828';
            }
            
            // Color past dates
            if (arg.date < new Date().setHours(0,0,0,0)) {
                arg.el.style.backgroundColor = '#f5f5f5';
                arg.el.style.color = '#9e9e9e';
            }
            
            // Highlight today
            if (arg.date.toDateString() === new Date().toDateString()) {
                arg.el.style.backgroundColor = '#e3f2fd';
                arg.el.style.border = '2px solid #2196f3';
            }
        },
        events: blockedDates.map(function(date) {
            return {
                title: 'BLOCKED',
                start: date,
                color: '#f44336'
            };
        })
    });
    
    calendar.render();
});

// Toggle reason field
$('input[name="is_available"]').change(function() {
    if ($(this).val() == '0') {
        $('#reasonDiv').show();
    } else {
        $('#reasonDiv').hide();
        $('#reason').val('');
    }
});

function unblockDate(date) {
    if (confirm('Unblock this date?')) {
        $.post('<?= base_url('rom/saveCalendarAvailability') ?>', {
            date: date,
            is_available: 1,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function() {
            location.reload();
        });
    }
}

function blockWeekends() {
    if (confirm('This will block all weekends for the next 6 months. Continue?')) {
        // TODO: Implement weekend blocking
        alert('Weekend blocking functionality to be implemented');
    }
}

function blockPublicHolidays() {
    if (confirm('This will block all Malaysian public holidays for the year. Continue?')) {
        // TODO: Implement public holiday blocking
        alert('Public holiday blocking functionality to be implemented');
    }
}

function blockDateRange() {
    $('#dateRangeModal').modal('show');
}

function unblockAll() {
    if (confirm('This will unblock ALL dates. Are you sure?')) {
        // TODO: Implement unblock all functionality
        alert('Unblock all functionality to be implemented');
    }
}
</script>

<style>
.legend-item {
    display: inline-block;
    margin: 10px;
}
.legend-color {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    vertical-align: middle;
    border: 1px solid #ddd;
}
.blocked-date-item {
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
#calendar {
    font-size: 14px;
}
.fc-day-today {
    background-color: #e3f2fd !important;
}
.fc-daygrid-day-number {
    font-weight: bold;
}
</style>