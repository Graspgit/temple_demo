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
        text-align: center;
	}

	table td {
		padding: 5px 10px!important;
	}
    table th {
		padding: 5px 10px!important;
        /* text-align: center; */
        font-weight: bold;
	}
    .table tr th {
        font-size: 14px;
        background: #f7ebbb;
        color: #333232;
    }
    table tr td, table tr th{
        border-collapse: collapse;
        border: 1px solid;
    }

    .table1{
        border-collapse: collapse;
        border: 0px !important;
    }

.body_head table tr td, .body_head table tr th{
	border: 0;
}
table{
	width: 100%;
	border-collapse: collapse;
}
</style>
<style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1, .header p {
            margin: 0;
        }
        .details, .items, .payment-details, .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details th, .details td, .items th, .items td, .payment-details th, .payment-details td, .footer-table th, .footer-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .details th {
            background-color: #f4f4f4;
        }
        .items th {
            background-color: #f0f0f0;
        }
        .right-align {
            text-align: left !important;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer-table th, .footer-table td {
            border: none;
        }
    </style>


<!-- <table align="center" width="100%" border="1" style="border-collapse: collapse;">
    <tr>
        <td width="15%" align="left" style="border:1px solid black; vertical-align:top;">
            <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;" align="left">
        </td>
        <td style="vertical-align:top;">
            <table width="100%" border="1" style="border-collapse: collapse;">
                <tr>
                    <td style="text-align:center;">
                        <h2 style="text-align:center;margin-bottom: 0; margin-top:8px;"><?php echo $temp_details['name']; ?></h2>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; font-size:16px;">
                        <?php echo $temp_details['address1']; ?>, <?php echo $temp_details['city']; ?> <?php echo $temp_details['postcode']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; font-size:16px;">
                        GST Reg.No: <?php echo $temp_details['gstno']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center; font-size:16px;">
                        Tel: <?php echo $temp_details['telephone']; ?>  Fax: <?php echo $temp_details['fax_no']; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;"><strong> Annathanam Voucher </strong></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="right-align"> Date: <?php echo date('Y-m-d'); ?> </td>
    </tr>

</table> -->

        <table border="1" align="center" style="border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td width="15%" style="border: 1px solid #000; vertical-align: top;">
                        <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;" align="left">
                    </td>
                    <td width="85%" style="padding: 0px; border: 1px solid #000;">
                        <table class="table1" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td>
                                    <h2 style="text-align:center; margin-bottom: 0; font-size: 18px;">
                                        <?php echo $temp_details['name']; ?>
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align:center; font-size:16px; margin:0;">
                                        <?php echo $temp_details['address1']; ?>, <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align:center; font-size:16px; margin:0;">
                                        GST Reg.No: <?php echo $temp_details['gstno']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align:center; font-size:16px; margin:0;">
                                        Tel: <?php echo $temp_details['telephone']; ?>  Fax: <?php echo $temp_details['fax_no']; ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">
                        Annathanam Voucher
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">
                        Devotee Name and Details
                    </td>
                    <td style="text-align: center;">
                        Tax Invoice No: <?php echo $data['ref_no']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <?php echo $data['name']; ?> 
                    </td>
                    <td style="text-align: center;">
                        Date: <?php echo date('d-m-Y'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        Phone Number: <?php echo $data['phone_no']; ?>
                    </td>
                    <td style="text-align: center;">
                        Event Date: <?php echo date('d-m-Y', strtotime($data['event_date'])); ?> | Time: <?php echo $data['serve_time']; ?>
                    </td>
                </tr>
            </table>
        </div>

            

        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Description</td>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Quantity</td> 
                        <td style="text-align: right; font-weight: bold; padding: 10px; border: 1px solid #000;">Unit Price</td> 
                        <td style="text-align: right; font-weight: bold; padding: 10px; border: 1px solid #000;">Amount</td> 
                    </tr>
                </tbody>
                <tbody>
                    <?php if($data['is_special'] == 0) { ?>
                        <tr>
                            <td class="right-align"><?php echo $data['name_eng'] . ' / ' . $data['name_tamil']; ?></td>
                            <td><?php echo $data['no_of_pax']; ?></td>
                            <td style="text-align: right;">$<?php echo $data['amount']; ?></td>
                            <?php $amount1 = $data['no_of_pax'] * $data['amount']; ?>
                            <td style="text-align: right;">$<?php echo number_format($amount1, 2); ?></td>
                        </tr>

                        <?php foreach($items as $item){ ?>
                            <tr>
                                <td class="right-align"><?php echo $item['name_eng'] . ' / ' . $item['name_tamil']; ?></td>
                                <td><?php echo $data['no_of_pax']; ?></td>
                                <td style="text-align: right;">-</td>
                                <td style="text-align: right;">-</td>
                            </tr>
                        <?php } ?> 
                    <?php } else { ?>
                        <?php foreach($special_items as $item){ ?>
                            <tr>
                                <td class="right-align">Special - <?php echo $item['name_eng'] . ' / ' . $item['name_tamil']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td style="text-align: right;">$<?php echo $item['amount']; ?></td>
                                <?php $amount2 = $item['quantity'] * $item['amount']; ?>
                                <td style="text-align: right;">$<?php echo number_format($amount2, 2); ?></td>
                            </tr>
                        <?php } ?> 
                    <?php } ?>

                    <?php foreach($addon_items as $addon){ ?>
                        <tr>
                            <td class="right-align"><?php echo $addon['name_eng'] . ' / ' . $addon['name_tamil']; ?></td>
                            <td><?php echo $addon['quantity']; ?></td>
                            <td style="text-align: right;">$<?php echo $addon['amount']; ?></td>
                            <?php $amount3 = $addon['quantity'] * $addon['amount']; ?>
                            <td style="text-align: right;">$<?php echo number_format($amount3, 2); ?></td>
                        </tr>
				    <?php } ?> 
                    <?php foreach($additional as $addi){ ?>
                        <tr>
                            <td class="right-align"><?php echo $addi['name']; ?></td>
                            <td><?php echo $addi['quantity']; ?></td>
                            <td style="text-align: right;">$<?php echo $addi['amount']; ?></td>
                            <?php $amount4 = $addi['quantity'] * $addi['amount']; ?>
                            <td style="text-align: right;">$<?php echo number_format($amount4, 2); ?></td>
                        </tr>
				    <?php } ?> 
                    <tr>
                        <td style="text-align: right;"colspan="3"><strong>Sub total ($)</strong></td>
                        <td style="text-align: right;">$<?php echo number_format($data['sub_total'], 2); ?></td>
				    </tr>
                    <tr>
                        <td style="text-align: right;" colspan="3"><strong>Discount ($)</strong></td>
                        <td style="text-align: right;">-<?php echo $data['discount_amount']; ?></td>
				    </tr>
                    <tr>
                        <td style="text-align: right;" colspan="3"><strong>Total ($)</strong></td>
                        <?php $amount3 = $data['total_amount']; ?>
                        <td style="text-align: right;"><strong><?php echo number_format($amount3, 2); ?></strong></td>
				    </tr>
                    <tr>
                        <td class="right-align" colspan="4">(GST has been absorbed by the Temple)</td>
                        <!-- <td>&nbsp;</td> -->
                    </tr>
                    <!-- <tr>
                        <td colspan="3">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr> -->
                    <tr>
                        <td class="right-align" colspan="4">Note: All cheques should be crossed and made payable to "Muneeswaran Temple Society"</td>
                    </tr>
                    <tr>
                        <td class="right-align" colspan="4">PAYNOW - S67SS0012K (Please Quote Tax Invoice Number As Reference)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td class="right-align" width="30%"><strong>Payment Details</strong></td>
                        <td colspan="2">&nbsp;</td>
                        <td style="text-align: right;">$<?php echo number_format($data['paid_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td class="right-align" width="30%"><strong>Total Invoice Value</strong></td>
                        <td colspan="2" style="text-align: center"><?php echo $data['ref_no'] . '/' . date('d-m-Y', strtotime($data['date'])); ?></td>
                        <td style="text-align: right;">$<?php echo number_format($data['total_amount'], 2); ?></td>
                    </tr>
                    <!-- <tr>
                        <td class="right-align" width="30%">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr> -->
                    <tr>
                        <td class="right-align" width="30%"><strong>Balance Payable</strong></td>
                        <td colspan="2">&nbsp;</td>
                        <td style="text-align: right;">$ <?php echo number_format(($data['total_amount'] - $data['paid_amount']), 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if (!empty($terms)): ?>
            <h4><b>Terms and Conditions</b></h4>
            <?php foreach ($terms as $term): ?>
                <p><?php echo $term; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

	
<!-- <p class="dot_line" style="bottom:0;position:relative;margin-top: 100px;">
	  <span>---------------------------------</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---------------------------------</span>
	</p> -->
<script>
	window.print();
</script>