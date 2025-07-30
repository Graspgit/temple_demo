<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prasadam Receipt</title>
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
    </style>
</head>

<body>

    <!-- Temple Details -->
        <table style="border: none; width: 100%; text-align: center;">
        <tr>
            <td style="border: none; padding-bottom: 0;" align="center">
                <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
                        style="width: 300px; max-width: 100%; display: block; margin: auto;">
                </td>
            </tr>
            <tr>
                <td style="border: none; padding-top: 0;">
                    <h2 style="text-align: center; margin: 0; font-size: 24px; font-weight: bold; width: 100%;">
                        <?php echo $temp_details['name_tamil']; ?>
                    </h2>
                    <h2 style="text-align: center; margin: 0; font-size: 28px; font-weight: bold; width: 100%;">
                        <?php echo $temp_details['name']; ?>
                    </h2>
                    <p
                        style="text-align: center; font-size: 18px; margin: 5px 0; width: 80%; margin-left: auto; margin-right: auto;">
                        <?php echo $temp_details['address1'], $temp_details['address2'] ?> <br>
        
                        <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?> <br>
                        Tel:<?php echo $temp_details['telephone']; ?>
                    </p>
                </td>
            </tr>
        </table>
    <hr>

    <h2>Prasadam Receipt</h2>

    <table>
        <tr>
            <td><b>Entry Date:</b></td>
           <td><?php echo date('d-m-Y', strtotime($data['date'])); ?></td>

        </tr>
        <tr>
            <td><b>Invoice:</b></td>
            <td><?php echo $data['ref_no']; ?></td>
        </tr>
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo $data['customer_name']; ?></td>
        </tr>
        <tr>
            <td><b>Collection Date:</b></td>
            <td><?php echo date('d-m-Y', strtotime($data['collection_date'])); ?></td>
        </tr>
        <tr>
            <td><b>Collection Time:</b></td>
            <td><?php echo $data['collection_session']; ?></td>
        </tr>
        <tr>
            <td><b>Total Amount (RM):</b></td>
            <td><?php echo number_format($data['total_amount'], 2); ?></td>
        </tr>
    </table>

    <h4>Prasadam Details:</h4>
    <table>
        <tr>
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit Price (RM)</th>
            <th>Amount (RM)</th>
        </tr>
        <?php foreach ($booking_details as $bd) { ?>
            <tr>
                <?php if ($bd['groupname'] == 'General') { ?>
                    <td><?php echo $bd['name_eng'] . ' / ' . $bd['name_tamil']; ?></td>
                <?php } else { ?>
                    <td><?php echo $bd['name_eng'] . ' / ' . $bd['name_tamil'] . ' - ' . $bd['groupname']; ?></td>
                <?php } ?>
                <td><?php echo $bd['quantity']; ?></td>
                <td><?php echo number_format($bd['amount'], 2); ?></td>
                <td><?php echo number_format($bd['quantity'] * $bd['amount'], 2); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h4>Payment Details:</h4>
    <table>
        <tr>
            <th>Payment Date</th>
            <th>Payment Mode</th>
            <th>Paid Amount (RM)</th>
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

    <br>
    <br>
    <br>
    <div style="text-align: right;">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>
    <br>

    <script>
        window.print();
    </script>

</body>

</html>