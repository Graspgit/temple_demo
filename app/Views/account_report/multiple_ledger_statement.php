<style>
    /*body { background:#fff; }
    .content { max-width: 100%; padding: 0 .2rem; }*/
	.table1{ border:1px solid #CCCCCC; }
	.table1 tr th { background-color:#EFEFEF; padding:5px; min-width:130px; font-size:16px; }
	.table1 tr td:first-child { padding:5px; text-align:left; }
	.table1 tr td { padding:5px; text-align:right;  }
</style>
<table width="1000" border="0" align="center" style="border-collapse:collapse; font-family:Calibri ;font-size:18px;">
<tr><td width="15%" align="left"><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>" style="width:120px;" align="left"></td>
<td width="85%" align="left">
    <h3 style="text-align:center;margin-bottom: 0;"><?php echo $temp_details['name_tamil']; ?></h3>
    <p style="text-align:center; font-size:16px; margin:5px 0px;"><?php echo $temp_details['since_tamil']; ?>
    <h2 style="text-align:center;margin-bottom: 0;"><?php echo $_SESSION['site_title']; ?></h2>
    <p style="text-align:center; font-size:16px; margin:5px 0px;"><?php echo $temp_details['since_eng']; ?><br><?php echo $_SESSION['address1']; ?>, <?php echo $_SESSION['address2']; ?>,
	<?php echo $_SESSION['postcode']; ?> <?php echo $_SESSION['city']; ?>  <br>
    Tel : <?php echo $_SESSION['telephone']; ?></p></td></tr>
    </table>


	<table width="1000" border="0" align="center" style="border-collapse:collapse; font-family: Arial, Helvetica, sans-serif;">
		<tr style="font-size: 16px;">
			<td height="50" width="100%" align="center" style="text-transform:uppercase;"><strong>General Ledger Statement </strong></td>
		</tr>
	</table>

	<?php
    foreach($ledger_id as $ledgerid){
        $ledgername_code = get_ledger_name($ledgerid);
        $res_ledger = loop_general_ledger_statement($ledgerid,$fdate, $tdate, $booking_type);
    ?>
	
	<table class="table1" width="1000" border="1" align="center" style="border-collapse:collapse; font-family: Arial, Helvetica, sans-serif; font-size: 15px;">
			<tr>
				<td colspan="7"><h3 style="text-transform: uppercase;font-size: 18px;margin:15px 0px;"><?php echo $ledgername_code; ?></h3></td>
			</tr>
			<tr style="padding: 15px 0;background-color: #F2F2F2;">
				
				<td width="10%" align="left" style=""><strong>Date</strong></td>
				<td width="20%" align="left" style=""><strong>Ref.</strong></td>
				<td width="25%" align="left" style=""><strong>Description</strong></td>
				<td width="10%" align="right" style=""><strong>Debit</strong></td>
				<td width="10%" align="right" style=""><strong>Credit</strong></td>
				<td width="10%" align="right" style=""><strong>Net Activity</strong></td>
				<td width="15%" align="right" style=""><strong>Balance</strong></td>
			</tr>
                <tr >
                    <td></td><td colspan="2">Opening Balance</td><td></td><td></td><td></td>
                    <td align="right" style="padding: 15px 5px;"><?php
                        if($res_ledger['op_bal'] < 0){
                            echo "( ".number_format(abs($res_ledger['op_bal']),'2','.',',')." )";
                        }
                        else{
                            echo number_format($res_ledger['op_bal'],'2','.',',');
                        }
                        ?></td>
                </tr> 
                <?php 
                $cu_credit = 0;
                $cu_debit = 0;
                foreach($res_ledger['data'] as $row) { 
                    if(!empty($row['credit_amount'])) $cu_credit += (float) $row['credit_amount'];
                    if(!empty($row['debit_amount'])) $cu_debit += (float) $row['debit_amount'];
                ?>
                    <tr>
                        <td style="padding: 6px 0;" ><?= date('d-m-Y',strtotime($row['date'])); ?></td>
                        <td align="left" style="padding: 6px 0;" ><?= $row['entry_code']; ?></td>
                        <td style="padding: 6px 0;" ><?= $row['narration']; ?></td>
                        <td align="right" style="padding: 6px 0;" ><?= $row['debit']; ?></td>
                        <td align="right" style="padding: 6px 0;" ><?= $row['credit']; ?></td>
                        <td align="right" style="padding: 6px 0;" ></td>
                        <td align="right" style="padding: 6px 2px;" >
                            <?php
                            if($row['balance'] < 0){
                                echo "( ".number_format(abs($row['balance']),2)." )";
                            }
                            else{
                                echo number_format($row['balance'],2);
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr >
                    <td></td>
                    <td colspan="2">Closing Balance</td>
                    <td align="right"><?= number_format($cu_debit,'2','.',','); ?></td>
                    <td align="right"><?= number_format($cu_credit,'2','.',','); ?></td>
                    <td align="right">
                    <?php
                    $diffrence_amt = $cu_debit - $cu_credit;
                    echo number_format(abs($diffrence_amt),'2','.',',');
                    ?>
                    </td>
                    <td align="right" style="padding: 15px 5px;">
                        <?php
                        if($res_ledger['cl_bal'] < 0){
                            echo "( ".number_format(abs($res_ledger['cl_bal']),'2','.',',')." )";
                        }
                        else{
                            echo number_format($res_ledger['cl_bal'],'2','.',',');
                        }
                        ?>
                    </td>
                </tr>
        </table>
		<p>&nbsp;</p>
	<?php
	}
	?>



<script>
window.print();
</script>