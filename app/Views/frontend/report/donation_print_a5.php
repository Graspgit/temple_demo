<?php $db = db_connect(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
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

    table, th, td {
        border: 1px solid #CCC;
        padding: 6px;
    }

    th {
        background-color: #f9f9f9;
        text-align: left;
    }

    td:first-child {
        width: 50%;
    }

    td, th {
        text-align: left;
        vertical-align: top;
    }

    h2 {
        text-align: center;
        font-size: 18px;
        margin: 4px 0;
    }

    h4 {
        font-size: 14px;
        margin: 6px 0 4px 0;
    }

    .temple-logo {
        width: 180px;
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
                <h2 style="font-size: 10px; font-weight: bold;"><?php echo $temp_details['name_tamil']; ?></h2>
                <h2 style="font-size: 10px; font-weight: bold;"><?php echo $temp_details['name']; ?></h2>
              <p style="text-align: center;">
    <?php echo $temp_details['address1'], ' ', $temp_details['address2']; ?><br>
    <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?><br>
    Tel: <?php echo $temp_details['telephone']; ?>
</p>

            </td>
        </tr>
    </table>

    <hr>

    <h2 style="text-align: center;">Donation Receipt</h2>

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
            <td><b>Devotee Name:</b></td>
            <td><?php echo $data['name']; ?></td>
        </tr>
        <tr>
            <td><b>Mobile No:</b></td>
            <td><?php echo $data['mobile']; ?></td>
        </tr>
        <tr>
            <td><b>Total Donation Amount (RM):</b></td>
            <td><?php echo number_format($data['amount'], 2); ?></td>
        </tr>
    </table>
<br>
    <h4 style="text-align: center;">Donation Details</h4>
    <table>
        <tr>
            <th>Description</th>
            <th>Amount (RM)</th>
        </tr>
        <tr>
            <td><?php echo $data['pname']; ?></td>
            <td><?php echo number_format($data['amount'], 2); ?></td>
        </tr>
    </table>
<br>
    <h4 style="text-align: center;">Payment Details</h4>
    <table>
        <tr>
            <th>Payment Date</th>
            <th>Amount (RM)</th>
        </tr>
        <tr>
            <td><?php echo date("d/m/Y"); ?></td>
            <td><?php echo number_format($data['amount'], 2); ?></td>
        </tr>
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
