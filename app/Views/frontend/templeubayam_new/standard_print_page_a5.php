<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubayam Voucher</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
            font-size: 9px;
            margin: 10mm auto;
            max-width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }

        table,
        th,
        td {
            border: 1px solid #CCC;
            padding: 6px;
        }

        th {
            background-color: #f9f9f9;
            text-align: center;
        }

        td,
        th {
            vertical-align: top;
        }

        h2,
        h4 {
            text-align: center;
            margin: 5px 0;
        }

        p {
            font-size: 11px;
            margin: 4px 0;
        }

        .temple-logo {
            width: 100px;
            max-width: 100%;
            display: block;
            margin: 0 auto 5px auto;
        }

        .no-border {
            border: none !important;
        }

        .signature {
            text-align: right;
            margin-top: 30px;
        }

        @media print {
            @page {
                size: A5 portrait;
                margin: 10mm;
            }

            body {
                margin: 0;
            }
        }
        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <!-- Temple Details -->
    <table class="no-border">
        <!-- <tr>
            <td class="no-border" align="center">
            
            </td>
        </tr> -->
        <tr>
            <td class="no-border" align="center">
                    <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" class="temple-logo">
                <h2 style="font-size: 10px;"><?php echo $temp_details['name_tamil']; ?></h2>
                <h2 style="font-size: 10px;"><?php echo $temp_details['name']; ?></h2>
                <p style="text-align: center;">
                    <?php echo $temp_details['address1'] . ' ' . $temp_details['address2']; ?><br>
                    <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?><br>
                    Tel: <?php echo $temp_details['telephone']; ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- <hr> -->

    <h2>Ubayam Receipt</h2>

    <table>
        <tr>
            <td><b>Entry Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['entry_date'])); ?></td>
        </tr>
        <tr>
            <td><b>Invoice:</b></td>
            <td><?php echo $data['ref_no']; ?></td>
        </tr>
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo $data['name']; ?></td>
        </tr>
        <tr>
            <td><b>Ubayam Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['booking_date'])); ?></td>
        </tr>
        <tr>
            <td><b>Ubayam Time:</b></td>
            <td><?php echo $booked_slot['slot_name']; ?></td>
        </tr>
        <?php if (!empty($data['discount_amount']) && $data['discount_amount'] > 0) { ?>
            <tr>
                <td><b>Sub Total (RM)</b></td>
                <td><?php echo number_format($data['amount'] + $data['discount_amount'], '2', '.', ','); ?> </td>
            </tr>
            <tr>
                <td><b>Discount (RM)</b></td>
                <td>- <?php echo number_format($data['discount_amount'], '2', '.', ','); ?> </td>
            </tr>
            <tr>
                <td><b>Total (RM)</b></td>
                <td><?php echo number_format($data['amount'], '2', '.', ','); ?> </td>
            </tr>
        <?php } else { ?>
            <tr>
                <td><b>Total (RM)</b></td>
                <td><?php echo number_format($data['amount'], '2', '.', ','); ?> </td>
            </tr>
        <?php } ?> 
        <tr>
            <td><b>Paid Amount (RM):</b></td>
            <td><?php echo number_format($data['paid_amount'], 2); ?></td>
        </tr>
    </table>

 <h4>Package Details:</h4>
<table>
    <tr>
        <th>Deity Name</th>
        <th>Ubayam Name</th>
        <th>Included Prasadam</th>
        <th>Quantity</th>
    </tr>
    <?php foreach ($packages as $package) { ?>
        <tr>
            <td><?php echo $package['deity_name'] ?? '-'; ?></td>
            <td><?php echo $package['name']; ?></td>
            <td>
                <?php
                // Loop through prasadam details and display each one
                if (!empty($prasadam_details)) {
                    foreach ($prasadam_details as $prasadam) {
                        echo $prasadam['name_eng'] . "<br>"; // Displaying prasadam name
                    }
                }
                ?>
            </td>
            <td>
                <?php
                // Loop through prasadam details and display the quantity
                if (!empty($prasadam_details)) {
                    foreach ($prasadam_details as $prasadam) {
                        echo $prasadam['quantity'] . "<br>"; // Displaying prasadam quantity
                    }
                }
                ?>
            </td>
        </tr>
    <?php } ?>
</table><br>

    <?php if (!empty($services)) { ?>
        <h4>Included Services</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($services as $addon) { ?>
                <tr>
                    <td><?php echo $addon['name']; ?></td>
                    <td><?php echo $addon['quantity']; ?></td>
                </tr>
            <?php } ?>
        </table><br>
    <?php } ?>

    <?php if (!empty($abishegam_details)) { ?>
        <h4>Abishegam Details</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>AMount</th>
            </tr>
            <?php foreach ($abishegam_details as $details) { ?>
                <tr>
                    <td><?php echo $details['name']; ?></td>
                    <td><?php echo $details['amount']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

     <?php if (!empty($homam_details)) { ?>
        <h4>Homam Details</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>AMount</th>
            </tr>
            <?php foreach ($homam_details as $details) { ?>
                <tr>
                    <td><?php echo $details['name']; ?></td>
                    <td><?php echo $details['amount']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>

    <?php if (!empty($booked_addon)) { ?>
        <h4>Add-on Details:</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Amount (RM)</th>
            </tr>
            <?php foreach ($booked_addon as $addon) { ?>
                <tr>
                    <td><?php echo $addon['name']; ?></td>
                    <td><?php echo $addon['quantity']; ?></td>
                    <td><?php echo number_format($addon['amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </table><br>
    <?php } ?>

    <?php if (!empty($addon_prasadam)) { ?>
        <h4>Add-on Prasadam</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Amount (RM)</th>
            </tr>
            <?php foreach ($addon_prasadam as $addon) { ?>
                <tr>
                    <td><?php echo $addon['name_eng']; ?></td>
                    <td><?php echo $addon['quantity']; ?></td>
                    <td><?php echo number_format($addon['total_amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </table><br>
    <?php } ?>

    <?php if (!empty($extra_charges)) { ?>
        <h4>Extra Charges</h4>
        <table>
            <tr>
                <th>Description</th>
                <th>Amount (RM)</th>
            </tr>
            <?php foreach ($extra_charges as $addon) { ?>
                <tr>
                    <td><?php echo $addon['description']; ?></td>
                    <td><?php echo number_format($addon['amount'], 2); ?></td>
                </tr>
            <?php } ?>
        </table><br>
    <?php } ?>

    <h4>Payment Details:</h4>
    <table>
        <tr>
            <th>Payment Date</th>
            <th>Payment Mode</th>
            <th>Amount (RM)</th>
            <th>Outstanding Amount (RM)</th>
        </tr>
        <?php
        $totalPaid = 0;
        foreach ($pay_details as $payment) {
            $payment_mode = $db->table("payment_mode")->where('id', $payment['payment_mode_id'])->get()->getRowArray();
            $totalPaid += $payment['amount'];
            $outstandingAmount = $data['amount'] - $totalPaid;
            ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($payment['paid_date'])); ?></td>
                <td><?php echo $payment_mode['name']; ?></td>
                <td><?php echo number_format($payment['amount'], 2); ?></td>
                <td><?php echo number_format($outstandingAmount, 2); ?></td>
            </tr>
        <?php } ?>
    </table>

    <div class="signature">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>

    <?php if (!empty($terms)): ?>
        <div class="page-break" > <!-- Forces new page -->
            <p><b>Terms and Conditions</b></p>
            <?php foreach ($terms as $term): ?>
                <p><?php echo $term; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script>
        window.print();
    </script>

</body>

</html>