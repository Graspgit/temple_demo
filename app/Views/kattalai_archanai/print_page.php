<?php $db = db_connect(); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Barlow', sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table,
    th,
    td {
        border: 1px solid #CCC;
        padding: 8px;
        text-align: left;
    }

    h2,
    h4 {
        text-align: center;
    }

    .no-border {
        border: none;
    }

    .capitalize {
        text-transform: capitalize;
    }

    .table td {
        padding: 10px;
    }
    
</style>

<!-- Temple Details -->
<table style="border: none; width: 100%; text-align: center;">
    <tr>
        <td style="border: none; padding-bottom: 0;" align="center">
            <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
                style="width: 200px; max-width: 100%; display: block; margin: auto;">
        </td>
    </tr>
    <tr>
        <td style="border: none; padding-top: 0;">
            <h2 style="margin: 0; font-size: 15px; font-weight: bold;">
                <?php echo $temp_details['name_tamil']; ?>
            </h2>
            <h2 style="margin: 0; font-size: 18px; font-weight: bold;">
                <?php echo $temp_details['name']; ?>
            </h2>
            <p style="text-align: center; font-size: 18px; margin: 5px 0;">
                <?php echo $temp_details['address1'], $temp_details['address2']; ?>
                <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?><br>
                Tel: <?php echo $temp_details['telephone']; ?>
            </p>
        </td>
    </tr>
</table>

<!-- Kattalai Archanai Details -->
<div class="table-responsive col-md-12" style="background:#FFF; margin-bottom:0;">
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <td><b>Devotee Name:</b></td>
            <td><?php echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
            <td><b>Invoice No:</b></td>
            <td><?php echo $data['ref_no']; ?></td>
        </tr>
        <tr>
            <td><b>Date:</b></td>
            <td><?php echo date("d-m-Y", strtotime($data['date'])); ?></td>
        </tr>
        <tr>
            <td><b>Phone Number:</b></td>
            <td><?php echo $data['mobile_no']; ?></td>
        </tr>
        <tr>
            <td><b>Description:</b></td>
            <td><?php echo !empty($data['description']) ? $data['description'] : 'N/A'; ?></td>
        </tr>
    </table>
</div>
<br>

<!-- Itemized Details -->
<div class="table-responsive col-md-12" style="background:#FFF; margin-bottom:0;">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style="text-align: center; width: 50%;">Description</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Unit Price RM</th>
                <th style="text-align: right;">Amount RM</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="right-align"><?php echo $data['name_eng'] . ' / ' . $data['name_tamil']; ?><br>
                    <?php foreach ($deities as $deity) { ?>
                        <?php echo '  - ' . $deity['deity_name'] . '<br>';
                    } ?>
                </td>

                <?php if ($data['daytype'] == 'years') { ?>
                    <td>1 x <?php echo $deity_count; ?> </td>
                <?php } else { ?>
                    <td><?php echo $data['no_of_days']; ?> x (<?php echo $deity_count; ?>)</td>
                <?php } ?>

                <td style="text-align: right;">RM<?php echo $data['unit_price']; ?></td>

                <?php
                if ($data['daytype'] == 'years') {
                    $amount += $data['unit_price'] * $deity_count;
                } else {
                    $amount += $data['unit_price'] * $data['no_of_days'] * $deity_count;
                }
                ?>

                <td style="text-align: right;">RM<?php echo number_format($amount, 2); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align: right;"><strong>Total</strong></td>
                <td style="text-align: right;">RM<?php echo number_format($data['amount'], 2); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<!-- Devotee Name List -->
<div class="table-responsive col-md-12" style="background:#FFF; margin-bottom:0;">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style="text-align: center; width: 10%">S.No</th>
                <th style="text-align: center; width: 40%">Name</th>
                <th style="text-align: center; width: 25%">Rasi</th>
                <th style="text-align: center; width: 25%">Natchathiram</th>
            </tr>
        </thead>
        <tbody>
            <?php $sno = 1;
            foreach ($details as $row) { ?>
                <tr>
                    <td style="text-align: center"><?php echo $sno++ ?></td>
                    <td style="text-align: center"><?php echo $row['name']; ?></td>
                    <td style="text-align: center"><?php echo $row['rasi']; ?></td>
                    <td style="text-align: center"><?php echo $row['natchathiram']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<br>
<!-- Details of Kattalai Archanai -->
<div class="table-responsive col-md-12" style="background:#FFF; margin-bottom:0;">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style="text-align: center; width: 36%;">Type</th>
                <th style="text-align: center;">From Date</th>
                <th style="text-align: center;">To Date</th>
                <th style="text-align: center;">No.of Days</th>
                <?php if ($data['daytype'] == 'weekly') { ?>
                    <th style="text-align: center;">Day</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="capitalize" style="text-align: center;"><?php echo $data['daytype']; ?></td>
                <?php if ($data['daytype'] == 'days') {
                    $output = [];
                    foreach ($dates as $date) {
                        $output[] = date('d-m-Y', strtotime($date['date']));
                    } ?>
                    <td style="text-align: center;"><?php echo implode(', ', $output); ?></td>
                <?php } else { ?>
                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($data['start_date'])); ?></td>
                    <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($data['end_date'])); ?></td>
                <?php } ?>
                <td style="text-align: center;"><?php echo $data['no_of_days']; ?></td>

                <?php if ($data['daytype'] == 'weekly') {
                    $day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    ?>
                    <td style="text-align: center;"><?php echo $day[$data['dayofweek']]; ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
</div>

<br>

<!-- Payment Details -->
<div class="table-responsive col-md-12" style="background:#FFF; margin-bottom:0;">
    <table class="table table-bordered table-striped table-hover">
        <tbody>
            <tr>
                <td class="right-align" width="30%"><strong>Payment Details</strong></td>
                <td colspan="2">&nbsp;</td>
                <td style="text-align: right;">RM<?php echo number_format($data['paid_amount'], 2); ?></td>
            </tr>
            <tr>
                <td class="right-align" width="30%"><strong>Total Invoice value</strong></td>
                <td colspan="2" style="text-align: center">
                    <?php echo $data['ref_no'] . '/' . date('d-m-Y', strtotime($data['date'])); ?></td>
                <td style="text-align: right;">RM<?php echo number_format($data['amount'], 2); ?></td>
            </tr>
            <tr>
                <td class="right-align" width="30%"><strong>Balance Payable</strong></td>
                <td colspan="2">&nbsp;</td>
                <td style="text-align: right;">
                    RM<?php echo number_format(($data['amount'] - $data['paid_amount']), 2); ?></td>
            </tr>
        </tbody>
    </table>
</div>

    <br>

       <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
    <div style="text-align: right;">
        
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>
</div>
<script>
    window.print();
</script>