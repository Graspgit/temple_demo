<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MARRIAGE REGISTRATION DETAILS</h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <?= session()->getFlashdata('success') ?>
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <!-- Booking Summary -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-cyan">
                        <h2>
                            BOOKING INFORMATION
                            <small>Booking No: <?= $booking['booking_no'] ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('rom/print/'.$booking['id']) ?>" 
                                   class="btn btn-default waves-effect" target="_blank">
                                    <i class="material-icons">print</i> PRINT
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Date:</strong><br><?= date('d F Y', strtotime($booking['booking_date'])) ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Time Slot:</strong><br><?= $booking['slot_name'] ?> 
                                (<?= date('h:i A', strtotime($booking['start_time'])) ?> - 
                                <?= date('h:i A', strtotime($booking['end_time'])) ?>)</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Venue:</strong><br><?= $booking['venue_name'] ?> (<?= $booking['venue_type'] ?>)</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Booking Status:</strong><br>
                                    <span class="label label-<?= $booking['booking_status'] == 'confirmed' ? 'success' : 'warning' ?>">
                                        <?= strtoupper($booking['booking_status']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Couple Details -->
        <div class="row clearfix">
            <?php foreach($couple as $person): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header <?= $person['person_type'] == 'bride' ? 'bg-purple' : 'bg-indigo' ?>">
                        <h2>
                            <?= strtoupper($person['person_type']) ?> DETAILS
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-condensed">
                                    <tr>
                                        <td width="40%"><strong>Name:</strong></td>
                                        <td><?= $person['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Birth:</strong></td>
                                        <td><?= date('d F Y', strtotime($person['dob'])) ?> (Age: <?= $person['age'] ?>)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nationality:</strong></td>
                                        <td><?= $person['nationality'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IC/Passport:</strong></td>
                                        <td><?= $person['ic_passport_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?= $person['phone'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?= $person['email'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Occupation:</strong></td>
                                        <td><?= $person['occupation'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Father's Name:</strong></td>
                                        <td><?= $person['father_name'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mother's Name:</strong></td>
                                        <td><?= $person['mother_name'] ?: '-' ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php if($person['photo']): ?>
                                    <img src="<?= base_url('uploads/rom/photos/'.$person['photo']) ?>" 
                                         class="img-thumbnail" style="max-width: 150px;">
                                <?php else: ?>
                                    <div style="width: 150px; height: 150px; background: #f0f0f0; 
                                                display: flex; align-items: center; justify-content: center; 
                                                margin: 0 auto;">
                                        <i class="material-icons" style="font-size: 48px; color: #ccc;">person</i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($person['address']): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Address:</strong><br><?= nl2br($person['address']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Documents -->
        <?php if($documents): ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-brown">
                        <h2>
                            UPLOADED DOCUMENTS
                            <small>Click on document name to download</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($documents as $doc): ?>
                                    <tr>
                                        <td><?= str_replace('_', ' ', $doc['document_type']) ?></td>
                                        <td><?= $doc['document_name'] ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($doc['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('uploads/rom/documents/'.$doc['file_path']) ?>" 
                                               class="btn btn-xs btn-primary waves-effect" download>
                                                <i class="material-icons">download</i> Download
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
        <?php endif; ?>

        <!-- Payment Information -->
        <div class="row clearfix">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-green">
                        <h2>
                            PAYMENT HISTORY
                        </h2>
                        <?php if($booking['payment_status'] != 'paid'): ?>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-success waves-effect" data-toggle="modal" data-target="#addPaymentModal">
                                    <i class="material-icons">add</i> ADD PAYMENT
                                </button>
                            </li>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Payment Type</th>
                                        <th>Mode</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $payment): ?>
                                    <tr>
                                        <td><?= date('d-m-Y H:i', strtotime($payment['payment_date'])) ?></td>
                                        <td><?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?></td>
                                        <td><?= $payment['payment_mode_name'] ?></td>
                                        <td>RM <?= number_format($payment['amount'], 2) ?></td>
                                        <td><?= $payment['transaction_id'] ?: '-' ?></td>
                                        <td>
                                            <span class="label label-<?= $payment['payment_status'] == 'success' ? 'success' : 'warning' ?>">
                                                <?= strtoupper($payment['payment_status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-orange">
                        <h2>PAYMENT SUMMARY</h2>
                    </div>
                    <div class="body">
                        <table class="table">
                            <tr>
                                <td>Venue Price:</td>
                                <td class="text-right">RM <?= number_format($booking['total_amount'] - $booking['extra_charges'] + $booking['discount_amount'] - $booking['tax_amount'], 2) ?></td>
                            </tr>
                            <?php if($booking['security_deposit'] > 0): ?>
                            <tr>
                                <td>Security Deposit:</td>
                                <td class="text-right">RM <?= number_format($booking['security_deposit'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($booking['extra_charges'] > 0): ?>
                            <tr>
                                <td>Extra Charges:</td>
                                <td class="text-right">RM <?= number_format($booking['extra_charges'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($booking['tax_amount'] > 0): ?>
                            <tr>
                                <td>Tax:</td>
                                <td class="text-right">RM <?= number_format($booking['tax_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($booking['discount_amount'] > 0): ?>
                            <tr>
                                <td>Discount:</td>
                                <td class="text-right text-danger">- RM <?= number_format($booking['discount_amount'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="bg-grey">
                                <td><strong>Total Amount:</strong></td>
                                <td class="text-right"><strong>RM <?= number_format($booking['total_amount'], 2) ?></strong></td>
                            </tr>
                            <tr class="bg-light-green">
                                <td><strong>Paid Amount:</strong></td>
                                <td class="text-right"><strong>RM <?= number_format($booking['paid_amount'], 2) ?></strong></td>
                            </tr>
                            <?php 
                            $dueAmount = $booking['total_amount'] - $booking['paid_amount'];
                            if($dueAmount > 0): 
                            ?>
                            <tr class="bg-deep-orange">
                                <td><strong>Due Amount:</strong></td>
                                <td class="text-right"><strong>RM <?= number_format($dueAmount, 2) ?></strong></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                        <div class="text-center">
                            <span class="label label-<?= $booking['payment_status'] == 'paid' ? 'success' : ($booking['payment_status'] == 'partial' ? 'warning' : 'danger') ?>" style="font-size: 14px;">
                                PAYMENT STATUS: <?= strtoupper($booking['payment_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($booking['remarks']): ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>REMARKS</h2>
                    </div>
                    <div class="body">
                        <p><?= nl2br($booking['remarks']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <form method="post" action="<?= base_url('rom/addPayment/'.$booking['id']) ?>">
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
                                    <option value="security_deposit">Security Deposit</option>
                                    <option value="extra_charge">Extra Charge</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" name="amount" class="form-control" step="0.01" required>
                                    <label class="form-label">Amount (RM) *</label>
                                </div>
                                <small>Due Amount: RM <?= number_format($booking['total_amount'] - $booking['paid_amount'], 2) ?></small>
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