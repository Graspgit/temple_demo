<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>REGISTRAR OF MARRIAGE</h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <?= session()->getFlashdata('success') ?>
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert">
                <?= session()->getFlashdata('error') ?>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            MARRIAGE REGISTRATIONS
                            <small>Manage all marriage registration bookings</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('rom/create') ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">add</i>
                                    <span>NEW REGISTRATION</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Booking No</th>
                                        <th>Date</th>
                                        <th>Time Slot</th>
                                        <th>Venue</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Payment Status</th>
                                        <th>Booking Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($bookings as $key => $booking): ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $booking['booking_no'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($booking['booking_date'])) ?></td>
                                        <td><?= $booking['slot_name'] ?><br>
                                            <small><?= date('h:i A', strtotime($booking['start_time'])) ?> - 
                                            <?= date('h:i A', strtotime($booking['end_time'])) ?></small>
                                        </td>
                                        <td><?= $booking['venue_name'] ?></td>
                                        <td>RM <?= number_format($booking['total_amount'], 2) ?></td>
                                        <td>RM <?= number_format($booking['paid_amount'], 2) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch($booking['payment_status']) {
                                                case 'paid':
                                                    $statusClass = 'label-success';
                                                    break;
                                                case 'partial':
                                                    $statusClass = 'label-warning';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'label-danger';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'label-default';
                                                    break;
                                            }
                                            ?>
                                            <span class="label <?= $statusClass ?>"><?= strtoupper($booking['payment_status']) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            switch($booking['booking_status']) {
                                                case 'confirmed':
                                                    $statusClass = 'label-success';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'label-warning';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'label-info';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'label-danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="label <?= $statusClass ?>"><?= strtoupper($booking['booking_status']) ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('rom/view/'.$booking['id']) ?>" 
                                               class="btn btn-xs btn-info waves-effect" title="View">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <?php if($booking['payment_status'] != 'paid'): ?>
                                            <a href="#" onclick="showPaymentModal(<?= $booking['id'] ?>)" 
                                               class="btn btn-xs btn-success waves-effect" title="Add Payment">
                                                <i class="material-icons">payment</i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('rom/print/'.$booking['id']) ?>" 
                                               class="btn btn-xs btn-default waves-effect" title="Print" target="_blank">
                                                <i class="material-icons">print</i>
                                            </a>
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

        <!-- Quick Links -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">access_time</i>
                    </div>
                    <div class="content">
                        <div class="text">TIME SLOTS</div>
                        <div class="number">
                            <a href="<?= base_url('rom/slots') ?>" style="color: white;">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">location_city</i>
                    </div>
                    <div class="content">
                        <div class="text">VENUES</div>
                        <div class="number">
                            <a href="<?= base_url('rom/venues') ?>" style="color: white;">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">date_range</i>
                    </div>
                    <div class="content">
                        <div class="text">CALENDAR</div>
                        <div class="number">
                            <a href="<?= base_url('rom/calendar') ?>" style="color: white;">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">assessment</i>
                    </div>
                    <div class="content">
                        <div class="text">REPORTS</div>
                        <div class="number">
                            <a href="<?= base_url('rom/reports') ?>" style="color: white;">View</a>
                        </div>
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
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
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
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select name="payment_type" class="form-control show-tick" required>
                                        <option value="">-- Select Payment Type --</option>
                                        <option value="partial">Partial Payment</option>
                                        <option value="full">Full Payment</option>
                                        <option value="security_deposit">Security Deposit</option>
                                        <option value="extra_charge">Extra Charge</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" name="amount" class="form-control" step="0.01" required>
                                    <label class="form-label">Amount (RM)</label>
                                </div>
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

<script>
function showPaymentModal(bookingId) {
    $('#paymentForm').attr('action', '<?= base_url('rom/addPayment/') ?>' + bookingId);
    $('#paymentModal').modal('show');
}
</script>

<style>
.info-box {
    cursor: pointer;
    transition: all 0.3s ease;
}
.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.label {
    font-size: 12px;
    padding: 3px 8px;
}
body .info-box.hover-expand-effect:hover:after {
    width: 0%;
}

</style>