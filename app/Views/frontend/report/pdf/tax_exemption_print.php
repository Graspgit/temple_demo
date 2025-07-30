<!DOCTYPE html>
<html>

<head>
    <title>Tax Exemption Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2><?php echo $pdfdata['temp_details']['name']; ?></h2>
        <h3>Tax Exemption Donations Report</h3>
        <p>Period: <?php echo date('d-m-Y', strtotime($pdfdata['fdate'])); ?> to
            <?php echo date('d-m-Y', strtotime($pdfdata['tdate'])); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Receipt No</th>
                <th>Donor Name</th>
                <th>IC Number</th>
                <th>Mobile</th>
                <th>Donation Type</th>
                <th>Amount (RM)</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fdt = date('Y-m-d', strtotime($pdfdata['fdate']));
            $tdt = date('Y-m-d', strtotime($pdfdata['tdate']));

            // Use db_connect() for CodeIgniter 4
            $db = \Config\Database::connect();
            $builder = $db->table('donation')
                ->join('donation_setting', 'donation_setting.id = donation.pay_for')
                ->join('donation_payment_gateway_datas as dpgd', 'dpgd.donation_booking_id = donation.id', 'left')
                ->select('donation_setting.name as pname, dpgd.pay_method')
                ->select('donation.*')
                ->where('donation.date >=', $fdt)
                ->where('donation.date <=', $tdt)
                ->where('donation.is_tax_redemption', 1)
                ->where('donation.payment_status', 2);

            if (!empty($pdfdata['payfor'])) {
                $builder->where('donation_setting.id', $pdfdata['payfor']);
            }
            if (!empty($pdfdata['fltername'])) {
                $builder->like('donation.name', $pdfdata['fltername']);
            }

            $donations = $builder->orderBy('donation.date', 'desc')->get()->getResultArray();

            $i = 1;
            $totalAmount = 0;
            foreach ($donations as $row):
                $totalAmount += $row['amount'];
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                    <td><?php echo $row['ref_no']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['ic_number'] ?: '-'; ?></td>
                    <td><?php echo $row['mobile'] ?: '-'; ?></td>
                    <td><?php echo $row['pname']; ?></td>
                    <td class="text-right"><?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo $row['pay_method'] ?: 'Cash'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong><?php echo number_format($totalAmount, 2); ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <p>Total Tax Exemption Donations: RM <?php echo number_format($totalAmount, 2); ?></p>
        <p>Total Number of Donations: <?php echo $i - 1; ?></p>
    </div>

    <div class="footer">
        <p>Generated on: <?php echo date('d-m-Y H:i:s'); ?></p>
        <p>This is a computer-generated report</p>
    </div>
</body>

</html>