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
            font-size: 11px;
            margin: 10mm auto;
            max-width: 100%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
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

        h4 {
            font-size: 11px;
            text-align: left;
            margin-top: 10px;
        }

        p {
            font-size: 11px;
            margin: 4px 0;
        }

        .temple-logo {
            width: 140px;
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
            <td><b>Diety Name:</b></td>
            <td><?php echo $data['diety_name']; ?></td>

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
                <td><?php echo $bd['name_eng'] . ' / ' . $bd['name_tamil']; ?></td>
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

    <div class="signature">
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>

    <script>
        window.print();
    </script>

</body>

</html>