<style>
    .pledge-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .info-label {
        font-weight: bold;
        opacity: 0.9;
    }

    .info-value {
        text-align: right;
    }

    .progress-section {
        margin-top: 20px;
    }

    .progress {
        height: 20px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .progress-bar {
        background-color: #28a745;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .entry-card {
        border-left: 4px solid #007bff;
        margin-bottom: 15px;
    }

    .entry-header {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
    }

    .entry-body {
        padding: 15px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-info {
        background-color: #17a2b8;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>PLEDGE DETAILS<small>Reports / Pledge Report / <b>Pledge Details</b></small></h2>
        </div>

        <div class="row">
            <!-- Back Button -->
            <div class="col-lg-12">
                <div style="margin-bottom: 15px;">
                    <a href="<?php echo base_url(); ?>/report/pledge_report" class="btn btn-primary waves-effect">
                        <i class="material-icons">arrow_back</i> Back to Pledge Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Pledge Information Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="pledge-info-card">
                    <h4 style="margin-bottom: 20px;">
                        <i class="material-icons" style="vertical-align: middle;">person</i>
                        Pledge Information
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Name:</span>
                                <span class="info-value"><?php echo $pledge['name']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Mobile:</span>
                                <span class="info-value"><?php echo $pledge['phone_code'] . $pledge['mobile']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value"><?php echo $pledge['email_id']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">IC/Passport:</span>
                                <span class="info-value"><?php echo $pledge['ic_or_passport']; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Address:</span>
                                <span class="info-value"><?php echo $pledge['address']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Pledge Amount:</span>
                                <span class="info-value">RM
                                    <?php echo number_format($pledge['pledge_amount'], 2); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Balance Amount:</span>
                                <span class="info-value">RM
                                    <?php echo number_format($pledge['balance_amt'], 2); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Collected Amount:</span>
                                <span class="info-value">RM
                                    <?php echo number_format($pledge['pledge_amount'] - $pledge['balance_amt'], 2); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    <?php
                                    if ($pledge['balance_amt'] <= 0) {
                                        echo '<span class="badge badge-success">Completed</span>';
                                    } else {
                                        echo '<span class="badge badge-warning">Pending</span>';
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Created Date:</span>
                                <span
                                    class="info-value"><?php echo date('d-m-Y', strtotime($pledge['created_date'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Section -->
                    <div class="progress-section">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>Progress</span>
                            <span>
                                <?php
                                $percentage = ($pledge['pledge_amount'] > 0) ?
                                    (($pledge['pledge_amount'] - $pledge['balance_amt']) / $pledge['pledge_amount']) * 100 : 0;
                                echo number_format($percentage, 1) . '%';
                                ?>
                            </span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="header bg-blue">
                        <h2 style="color: white;">
                            <i class="material-icons">assessment</i>
                            Summary Statistics
                        </h2>
                    </div>
                    <div class="body">
                        <div class="info-item" style="border-bottom: 1px solid #eee; color: #333;">
                            <span class="info-label">Total Entries:</span>
                            <span class="info-value"><strong><?php echo count($pledge_entries); ?></strong></span>
                        </div>
                        <div class="info-item" style="border-bottom: 1px solid #eee; color: #333;">
                            <span class="info-label">Total Donated:</span>
                            <span class="info-value">
                                <strong>RM <?php
                                $total_donated = array_sum(array_column($pledge_entries, 'donated_pledge_amt'));
                                echo number_format($total_donated, 2);
                                ?></strong>
                            </span>
                        </div>
                        <div class="info-item" style="border-bottom: 1px solid #eee; color: #333;">
                            <span class="info-label">Actual Donations:</span>
                            <span class="info-value">
                                <strong>RM <?php
                                $total_actual = array_sum(array_column($pledge_entries, 'current_donation_amount'));
                                echo number_format($total_actual, 2);
                                ?></strong>
                            </span>
                        </div>
                        <div class="info-item" style="color: #333;">
                            <span class="info-label">Last Entry:</span>
                            <span class="info-value">
                                <strong>
                                    <?php
                                    if (!empty($pledge_entries)) {
                                        echo date('d-m-Y', strtotime($pledge_entries[0]['created_at']));
                                    } else {
                                        echo 'No entries';
                                    }
                                    ?>
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pledge Entries -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <i class="material-icons">list</i>
                            Pledge Entries (<?php echo count($pledge_entries); ?>)
                        </h2>
                    </div>
                    <div class="body">
                        <?php if (!empty($pledge_entries)): ?>
                            <?php foreach ($pledge_entries as $index => $entry): ?>
                                <div class="entry-card card">
                                    <div class="entry-header">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 style="margin: 0;">
                                                    <span class="badge badge-info">Entry #<?php echo $index + 1; ?></span>
                                                    <?php if (!empty($entry['ref_no'])): ?>
                                                        <small>Donation Ref: <?php echo $entry['ref_no']; ?></small>
                                                    <?php endif; ?>
                                                </h5>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <small class="text-muted">
                                                    Created: <?php echo date('d-m-Y H:i', strtotime($entry['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="entry-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Pledge Amount:</strong><br>
                                                <span class="text-primary">RM
                                                    <?php echo number_format($entry['donated_pledge_amt'], 2); ?></span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Donation Amount:</strong><br>
                                                <span class="text-success">RM
                                                    <?php echo number_format($entry['current_donation_amount'], 2); ?></span>
                                            </div>
                                            <div class="col-md-3">
                                                <?php if (!empty($entry['donation_date'])): ?>
                                                    <strong>Donation Date:</strong><br>
                                                    <span><?php echo date('d-m-Y', strtotime($entry['donation_date'])); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php if (!empty($entry['payment_mode_name'])): ?>
                                                    <strong>Payment Mode:</strong><br>
                                                    <span><?php echo $entry['payment_mode_name']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if ($entry['status'] == 1): ?>
                                            <div style="margin-top: 10px;">
                                                <span class="badge badge-success">Active</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="material-icons">info</i>
                                No pledge entries found for this pledge record.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="<?php echo base_url(); ?>/report/pledge_report" class="btn btn-primary btn-lg waves-effect">
                    <i class="material-icons">arrow_back</i> Back to Report
                </a>
                <button onclick="window.print();" class="btn btn-secondary btn-lg waves-effect">
                    <i class="material-icons">print</i> Print Details
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        // Add some interactive features
        $('.entry-card').hover(
            function () {
                $(this).css('box-shadow', '0 4px 8px rgba(0,0,0,0.1)');
            },
            function () {
                $(this).css('box-shadow', '');
            }
        );
    });

    // Print styles
    @media print {
    .btn, .header, .block - header {
            display: none!important;
        }
    
    .pledge - info - card {
            background: white!important;
            color: black!important;
            border: 1px solid #ddd!important;
        }
    
    .info - item {
            border - bottom: 1px solid #ddd!important;
        }
    
    .entry - card {
            border: 1px solid #ddd!important;
            page -break-inside: avoid;
        }
    }
</script>