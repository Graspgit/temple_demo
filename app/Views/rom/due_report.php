<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MARRIAGE REGISTRATION DUE REPORT</h2>
        </div>

        <!-- Summary Cards -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-red hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL DUE BOOKINGS</div>
                        <div class="number"><?= count($bookings) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL DUE AMOUNT</div>
                        <div class="number">RM <?= number_format(array_sum(array_column($bookings, 'due_amount')), 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="info-box bg-deep-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">access_time</i>
                    </div>
                    <div class="content">
                        <div class="text">OVERDUE BOOKINGS</div>
                        <div class="number">
                            <?php 
                            $overdueCount = 0;
                            foreach($bookings as $booking) {
                                if(strtotime($booking['booking_date']) < strtotime('today')) {
                                    $overdueCount++;
                                }
                            }
                            echo $overdueCount;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Report Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            BOOKINGS WITH PENDING PAYMENTS
                            <small>All bookings with outstanding balance</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-success waves-effect" onclick="sendReminders()">
                                    <i class="material-icons">email</i> SEND REMINDERS
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-exportable dataTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check_all" class="filled-in chk-col-red">
                                            <label for="check_all"></label>
                                        </th>
                                        <th>Booking No</th>
                                        <th>Booking Date</th>
                                        <th>Days Due</th>
                                        <th>Couple Names</th>
                                        <th>Contact</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($bookings as $key => $booking): 
                                        $daysDue = floor((strtotime('today') - strtotime($booking['booking_date'])) / (60 * 60 * 24));
                                        $isOverdue = $daysDue > 0;
                                        
                                        // Get couple details
                                        $coupleModel = new \App\Models\RomCoupleModel();
                                        $couple = $coupleModel->getCoupleByBooking($booking['id']);
                                        $brideName = '';
                                        $groomName = '';
                                        $contact = '';
                                        foreach($couple as $person) {
                                            if($person['person_type'] == 'bride') {
                                                $brideName = $person['name'];
                                                if($person['phone']) $contact = $person['phone'];
                                            }
                                            if($person['person_type'] == 'groom') {
                                                $groomName = $person['name'];
                                                if(!$contact && $person['phone']) $contact = $person['phone'];
                                            }
                                        }
                                    ?>
                                    <tr class="<?= $isOverdue ? 'bg-light-red' : '' ?>">
                                        <td>
                                            <input type="checkbox" class="filled-in chk-col-red booking-check" 
                                                   id="check_<?= $booking['id'] ?>" value="<?= $booking['id'] ?>">
                                            <label for="check_<?= $booking['id'] ?>"></label>
                                        </td>
                                        <td><?= $booking['booking_no'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($booking['booking_date'])) ?></td>
                                        <td>
                                            <?php if($isOverdue): ?>
                                                <span class="label label-danger"><?= $daysDue ?> days overdue</span>
                                            <?php else: ?>
                                                <span class="label label-warning"><?= abs($daysDue) ?> days to go</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong>B:</strong> <?= $brideName ?><br>
                                            <strong>G:</strong> <?= $groomName ?>
                                        </td>
                                        <td><?= $contact ?: '-' ?></td>
                                        <td class="text-right">RM <?= number_format($booking['total_amount'], 2) ?></td>
                                        <td class="text-right">RM <?= number_format($booking['paid_amount'], 2) ?></td>
                                        <td class="text-right text-danger">
                                            <strong>RM <?= number_format($booking['due_amount'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <span class="label label-<?= $booking['payment_status'] == 'partial' ? 'warning' : 'danger' ?>">
                                                <?= strtoupper($booking['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('rom/view/'.$booking['id']) ?>" 
                                               class="btn btn-xs btn-info waves-effect" title="View">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-success waves-effect" 
                                                    onclick="showPaymentModal(<?= $booking['id'] ?>)" title="Add Payment">
                                                <i class="material-icons">payment</i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-warning waves-effect" 
                                                    onclick="sendSingleReminder(<?= $booking['id'] ?>)" title="Send Reminder">
                                                <i class="material-icons">email</i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">TOTALS:</th>
                                        <th class="text-right">RM <?= number_format(array_sum(array_column($bookings, 'total_amount')), 2) ?></th>
                                        <th class="text-right">RM <?= number_format(array_sum(array_column($bookings, 'paid_amount')), 2) ?></th>
                                        <th class="text-right text-danger">
                                            <strong>RM <?= number_format(array_sum(array_column($bookings, 'due_amount')), 2) ?></strong>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Analysis -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>PAYMENT STATUS BREAKDOWN</h2>
                    </div>
                    <div class="body">
                        <canvas id="paymentStatusChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>DUE AMOUNT BY MONTH</h2>
                    </div>
                    <div class="body">
                        <canvas id="dueByMonthChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <form method="post" id="paymentForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Payment Mode *</label>
                                <select name="payment_mode_id" class="form-control show-tick" required>
                                    <option value="">-- Select Payment Mode --</option>
                                    <?php 
                                    $paymentModes = (new \App\Models\PaymentModeModel())
                                        ->where('status', 1)
                                        ->where('rom', 1)
                                        ->findAll();
                                    foreach($paymentModes as $mode): 
                                    ?>
                                    <option value="<?= $mode['id'] ?>"><?= $mode['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Payment Type *</label>
                                <select name="payment_type" class="form-control show-tick" required>
                                    <option value="">-- Select Payment Type --</option>
                                    <option value="partial">Partial Payment</option>
                                    <option value="full">Full Payment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" name="amount" class="form-control" step="0.01" required>
                                    <label class="form-label">Amount (RM) *</label>
                                </div>
                                <small id="dueAmountInfo"></small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="transaction_id" class="form-control">
                                    <label class="form-label">Transaction ID (Optional)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea name="remarks" class="form-control" rows="2"></textarea>
                                    <label class="form-label">Remarks (Optional)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE PAYMENT</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Check all functionality
$('#check_all').change(function() {
    $('.booking-check').prop('checked', $(this).prop('checked'));
});

// Payment modal
function showPaymentModal(bookingId) {
    var booking = <?= json_encode($bookings) ?>.find(b => b.id == bookingId);
    $('#dueAmountInfo').text('Due Amount: RM ' + parseFloat(booking.due_amount).toFixed(2));
    $('#paymentForm').attr('action', '<?= base_url('rom/addPayment/') ?>' + bookingId);
    $('#paymentModal').modal('show');
}

// Send reminders
function sendReminders() {
    var selected = [];
    $('.booking-check:checked').each(function() {
        selected.push($(this).val());
    });
    
    if (selected.length === 0) {
        alert('Please select at least one booking to send reminder');
        return;
    }
    
    if (confirm('Send payment reminders to ' + selected.length + ' bookings?')) {
        // TODO: Implement reminder sending functionality
        alert('Reminder functionality to be implemented');
    }
}

function sendSingleReminder(bookingId) {
    if (confirm('Send payment reminder for this booking?')) {
        // TODO: Implement reminder sending functionality
        alert('Reminder functionality to be implemented');
    }
}

// Charts
$(document).ready(function() {
    // Payment Status Chart
    var statusData = {
        pending: 0,
        partial: 0
    };
    
    <?php foreach($bookings as $booking): ?>
        statusData['<?= $booking['payment_status'] ?>']++;
    <?php endforeach; ?>
    
    var ctx1 = document.getElementById('paymentStatusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Partial'],
            datasets: [{
                data: [statusData.pending, statusData.partial],
                backgroundColor: ['#F44336', '#FF9800']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Due by Month Chart
    var monthData = {};
    <?php foreach($bookings as $booking): ?>
        var month = '<?= date('M Y', strtotime($booking['booking_date'])) ?>';
        if (!monthData[month]) monthData[month] = 0;
        monthData[month] += <?= $booking['due_amount'] ?>;
    <?php endforeach; ?>
    
    var ctx2 = document.getElementById('dueByMonthChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: Object.keys(monthData),
            datasets: [{
                label: 'Due Amount (RM)',
                data: Object.values(monthData),
                backgroundColor: '#FF5722'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<style>
.bg-light-red {
    background-color: #ffebee !important;
}
</style>