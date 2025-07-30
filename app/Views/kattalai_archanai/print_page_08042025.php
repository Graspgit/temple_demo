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
        .capitalize{
            text-transform: capitalize;
            text-align: center;
        }
    </style>

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
                        Kattalai Archanai Receipt
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
                        Date: <?php echo date("d-m-Y", strtotime($data['date'])); ?> 
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        Phone Number: <?php echo $data['mobile_no']; ?>
                    </td>
                    
                    <td style="text-align: center;">
                    <?php if(!empty($data['description'])){ ?>
                        Description: <?php echo $data['description']; ?>
                    <?php } ?>
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
                        <td style="text-align: right; font-weight: bold; padding: 10px; border: 1px solid #000;">Unit Price RM</td> 
                        <td style="text-align: right; font-weight: bold; padding: 10px; border: 1px solid #000;">Amount RM</td> 
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td class="right-align"><?php echo $data['name_eng'] . ' / ' . $data['name_tamil']; ?><br>
                            <?php foreach($deities as $deity){ ?>
                            <?php echo '  - ' . $deity['deity_name'] . '<br>'; }?>
                        </td>

                        <?php if($data['daytype'] == 'years'){ ?>
                            <td>1 x <?php echo $deity_count; ?> </td>
                        <?php } else { ?>
                            <td><?php echo $data['no_of_days']; ?> x (<?php echo $deity_count; ?>) </td>
                        <?php } ?>

                        <!-- <?php //if($data['daytype'] == 'years'){ ?>
                            <td>-</td>
                        <?php //} else { ?> -->
                            <td style="text-align: right;">RM<?php echo $data['unit_price']; ?></td>
                        <!-- <?php //} ?> -->

                        <?php if($data['daytype'] == 'years') { 
                            $amount += $data['unit_price'] * $deity_count;
                        } else {
                            $amount += $data['unit_price'] * $data['no_of_days'] * $deity_count;
                        } ?>

                        <td style="text-align: right;">RM<?php echo number_format($amount, 2);?></td>
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
        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align: center; font-weight: bold; padding: 10px;">
                            Devotee Name List and Details
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000; width: 10%">S.No</td>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000; width: 40%">Name</td> 
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000; width: 25%">Rasi</td> 
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000; width: 25%">Natchathiram</td> 
                    </tr>
                    <?php 
                    $sno = 1;
                    foreach($details as $row){ ?>
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

        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td colspan="5" style="text-align: center; font-weight: bold; padding: 10px;">
                            Details of Kattalai Archanai
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Type</td>
                        <?php if ($data['daytype'] == 'days') { ?>
                            <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Dates</td> 
                        <?php } else {?>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">From Date</td> 
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">To Date</td> 
                        <?php } ?>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">No.of Days</td>
                        <?php if($data['daytype'] == 'weekly'){ ?>
                            <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Day</td> 
                        <?php } ?>
                    </tr>
            
                    <tr>
                        <td style="text-align: center" class="capitalize"><?php echo $data['daytype']; ?></td>
                        <?php if ($data['daytype'] == 'days') { 
                            $output = [];
                            foreach ($dates as $date) {
                                $output[] = date('d-m-Y', strtotime($date['date']));
                            } ?>
                            <td><?php echo implode(', ', $output); ?></td>
                        <?php } else { ?>
                        <td style="text-align: center"><?php echo date('d-m-Y', strtotime($data['start_date'])); ?></td>
                        <td style="text-align: center"><?php echo date('d-m-Y', strtotime($data['end_date'])); ?></td>
                        <?php } ?>
                        <td style="text-align: center"><?php echo $data['no_of_days']; ?></td>
                        <?php if($data['daytype'] == 'weekly'){ 
                            $day = '';
                            if ($data['dayofweek'] == 0) {
                                $day = 'Sunday';
                            } elseif ($data['dayofweek'] == 1) {
                                $day = 'Monday';
                            } elseif ($data['dayofweek'] == 2) {
                                $day = 'Tuesday';
                            } elseif ($data['dayofweek'] == 3) {
                                $day = 'Wednesday';
                            } elseif ($data['dayofweek'] == 4) {
                                $day = 'Thursday';
                            } elseif ($data['dayofweek'] == 5) {
                                $day = 'Friday';
                            } elseif ($data['dayofweek'] == 6) {
                                $day = 'Saturday';
                            }
                            ?>
                        <td style="text-align: center"><?php echo $day; ?></td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive col-md-12" style="background:#FFF; float:none;margin-bottom:0px;">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
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
                        <td style="text-align: right;">RM<?php echo number_format($data['paid_amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td class="right-align" width="30%"><strong>Total Invoice value</strong></td>
                        <td colspan="2" style="text-align: center"><?php echo $data['ref_no'] . '/' . date('d-m-Y', strtotime($data['date'])); ?></td>
                        <td style="text-align: right;">RM<?php echo number_format($data['amount'], 2); ?></td>
                    </tr>
                    <!-- <tr>
                        <td class="right-align" width="30%">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr> -->
                    <tr>
                        <td class="right-align" width="30%"><strong>Balance Payable</strong></td>
                        <td colspan="2">&nbsp;</td>
                        <td style="text-align: right;">RM <?php echo number_format(($data['amount'] - $data['paid_amount']), 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div><br>

        <!-- <h3>Devotee Details for Kattalai Archanai: </h3>
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">S.No</td>
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Name</td> 
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Rasi</td> 
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Natchathiram</td> 
                </tr>
                <?php 
                $sno = 1;
                foreach($details as $row){ ?>
                    <tr>
                        <td style="text-align: center"><?php echo $sno++ ?></td>
                        <td style="text-align: center"><?php echo $row['name']; ?></td>
                        <td style="text-align: center"><?php echo $row['rasi']; ?></td>
                        <td style="text-align: center"><?php echo $row['natchathiram']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table> -->

        <!-- <h3>Kattalai Archanai Date Details: </h3>
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Type</td>
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">From Date</td> 
                    <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">To Date</td> 
                    <?php if($data['daytype'] == 'weekly'){ ?>
                        <td style="text-align: center; font-weight: bold; padding: 10px; border: 1px solid #000;">Day</td> 
                    <?php } ?>
                </tr>
        
                <tr>
                    <td style="text-align: center"><?php echo $data['daytype']; ?></td>
                    <td style="text-align: center"><?php echo $data['start_date']; ?></td>
                    <td style="text-align: center"><?php echo $data['end_date']; ?></td>
                    <?php if($data['daytype'] == 'weekly'){ 
                        $day = '';
                        if ($data['dayofweek'] == 1) {
                            $day = 'Sunday';
                        } elseif ($data['dayofweek'] == 2) {
                            $day = 'Monday';
                        } elseif ($data['dayofweek'] == 3) {
                            $day = 'Tuesday';
                        } elseif ($data['dayofweek'] == 4) {
                            $day = 'Wednesday';
                        } elseif ($data['dayofweek'] == 5) {
                            $day = 'Thursday';
                        } elseif ($data['dayofweek'] == 6) {
                            $day = 'Friday';
                        } elseif ($data['dayofweek'] == 7) {
                            $day = 'Saturday';
                        }
                        ?>
                    <td style="text-align: center"><?php echo $day; ?></td>
                    <?php } ?>
                </tr>
            </tbody>
        </table> -->

	
<!-- <p class="dot_line" style="bottom:0;position:relative;margin-top: 100px;">
	  <span>---------------------------------</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---------------------------------</span>
	</p> -->
<script>
	window.print();
</script>