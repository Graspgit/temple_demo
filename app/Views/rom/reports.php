<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MARRIAGE REGISTRATION REPORTS</h2>
        </div>

        <!-- Report Filters -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            REPORT FILTERS
                            <small>Filter bookings by various criteria</small>
                        </h2>
                    </div>
                    <div class="body">
                        <form method="get" action="<?= current_url() ?>">
                            <div class="row clearfix">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="date_from" class="form-control" 
                                               value="<?= $filters['date_from'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="date_to" class="form-control" 
                                               value="<?= $filters['date_to'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Payment Status</label>
                                        <select name="payment_status" class="form-control show-tick">
                                            <option value="">-- All Status --</option>
                                            <option value="pending" <?= $filters['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="partial" <?= $filters['payment_status'] == 'partial' ? 'selected' : '' ?>>Partial</option>
                                            <option value="paid" <?= $filters['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                            <option value="cancelled" <?= $filters['payment_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Payment Mode</label>
                                        <select name="payment_mode" class="form-control show-tick">
                                            <option value="">-- All Modes --</option>
                                            <?php foreach($payment_modes as $mode): ?>
                                            <option value="<?= $mode['id'] ?>" <?= $filters['payment_mode'] == $mode['id'] ? 'selected' : '' ?>>
                                                <?= $mode['name'] ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">search</i> SEARCH
                                    </button>
                                    <a href="<?= current_url() ?>" class="btn btn-default waves-effect">
                                        <i class="material-icons">refresh</i> RESET
                                    </a>
                                    <button type="button" class="btn btn-success waves-effect" onclick="exportReport()">
                                        <i class="material-icons">file_download</i> EXPORT
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect" onclick="printReport()">
                                        <i class="material-icons">print</i> PRINT
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL BOOKINGS</div>
                        <div class="number count-to" data-from="0" data-to="<?= count($bookings) ?>" 
                             data-speed="1000" data-fresh-interval="20"><?= count($bookings) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL AMOUNT</div>
                        <div class="number">RM <?= number_format(array_sum(array_column($bookings, 'total_amount')), 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">done</i>
                    </div>
                    <div class="content">
                        <div class="text">PAID AMOUNT</div>
                        <div class="number">RM <?= number_format(array_sum(array_column($bookings, 'paid_amount')), 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">warning</i>
                    </div>
                    <div class="content">
                        <div class="text">DUE AMOUNT</div>
                        <div class="number">RM <?= number_format(
                            array_sum(array_column($bookings, 'total_amount')) - 
                            array_sum(array_column($bookings, 'paid_amount')), 2) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" id="reportContent">
                    <div class="header">
                        <h2>
                            BOOKING REPORT
                            <small><?= count($bookings) ?> records found</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-exportable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Booking No</th>
                                        <th>Date</th>
                                        <th>Slot</th>
                                        <th>Venue</th>
                                        <th>Bride Name</th>
                                        <th>Groom Name</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalAmount = 0;
                                    $totalPaid = 0;
                                    $totalDue = 0;
                                    foreach($bookings as $key => $booking): 
                                        $dueAmount = $booking['total_amount'] - $booking['paid_amount'];
                                        $totalAmount += $booking['total_amount'];
                                        $totalPaid += $booking['paid_amount'];
                                        $totalDue += $dueAmount;
                                        
                                        // Get couple names
                                        $coupleModel = new \App\Models\RomCoupleModel();
                                        $couple = $coupleModel->getCoupleByBooking($booking['id']);
                                        $brideName = '';
                                        $groomName = '';
                                        foreach($couple as $person) {
                                            if($person['person_type'] == 'bride') $brideName = $person['name'];
                                            if($person['person_type'] == 'groom') $groomName = $person['name'];
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $booking['booking_no'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($booking['booking_date'])) ?></td>
                                        <td><?= $booking['slot_name'] ?></td>
                                        <td><?= $booking['venue_name'] ?></td>
                                        <td><?= $brideName ?></td>
                                        <td><?= $groomName ?></td>
                                        <td class="text-right">RM <?= number_format($booking['total_amount'], 2) ?></td>
                                        <td class="text-right">RM <?= number_format($booking['paid_amount'], 2) ?></td>
                                        <td class="text-right <?= $dueAmount > 0 ? 'text-danger' : '' ?>">
                                            RM <?= number_format($dueAmount, 2) ?>
                                        </td>
                                        <td>
                                            <span class="label label-<?= 
                                                $booking['payment_status'] == 'paid' ? 'success' : 
                                                ($booking['payment_status'] == 'partial' ? 'warning' : 'danger') ?>">
                                                <?= strtoupper($booking['payment_status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">TOTALS:</th>
                                        <th class="text-right">RM <?= number_format($totalAmount, 2) ?></th>
                                        <th class="text-right">RM <?= number_format($totalPaid, 2) ?></th>
                                        <th class="text-right">RM <?= number_format($totalDue, 2) ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>OTHER REPORTS</h2>
                    </div>
                    <div class="body">
                        <a href="<?= base_url('rom/dueReport') ?>" class="btn btn-primary waves-effect">
                            <i class="material-icons">warning</i> DUE REPORT
                        </a>
                        <a href="<?= base_url('rom/monthlyReport') ?>" class="btn btn-info waves-effect">
                            <i class="material-icons">date_range</i> MONTHLY REPORT
                        </a>
                        <a href="<?= base_url('rom/venueReport') ?>" class="btn btn-success waves-effect">
                            <i class="material-icons">location_city</i> VENUE WISE REPORT
                        </a>
                        <a href="<?= base_url('rom/paymentModeReport') ?>" class="btn btn-warning waves-effect">
                            <i class="material-icons">payment</i> PAYMENT MODE REPORT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function exportReport() {
    // Trigger export functionality of DataTable
    $('.js-exportable').DataTable().button('.buttons-excel').trigger();
}

function printReport() {
    var printContent = document.getElementById('reportContent').innerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

/* $(document).ready(function() {
    // Initialize DataTable with export buttons
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
                extend: 'copy',
                className: 'btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn-sm'
            },
            {
                extend: 'excel',
                className: 'btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn-sm'
            },
            {
                extend: 'print',
                className: 'btn-sm'
            }
        ]
    });
}); */
</script>