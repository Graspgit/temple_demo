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
	}

	table td {
		padding: 5px;
	}
</style>

<!--<h2 style="text-align:center;margin-bottom: 0;"><?php echo $_SESSION['site_title']; ?></h2>
<p style="text-align:center; font-size:12px; margin:5px;"><?php echo $_SESSION['address1']; ?>, <br><?php echo $_SESSION['address2']; ?>,<br>
<?php echo $_SESSION['city']; ?> - <?php echo $_SESSION['postcode']; ?><br>
Tel : <?php echo $_SESSION['telephone']; ?></p>-->
<table align="center" width="100%">
	<tr>
		<td colspan="2">
			<table style="width:100%">
				<tr>
					<td width="15%" align="left"><img
							src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
							style="width:120px;" align="left"></td>
					<td width="85%" align="left">
						<h2 style="text-align:left;margin-bottom: 0;">
							<?php echo $temp_details['name']; ?>
						</h2>
						<p style="text-align:left; font-size:16px; margin:5px;">
							<?php echo $_SESSION['address1_frend']; ?>, <br>
							<?php echo $_SESSION['address2_frend']; ?>,<br>
							<?php echo $_SESSION['city_frend']; ?> -
							<?php echo $_SESSION['postcode_frend']; ?><br>
							Tel :
							<?php echo $_SESSION['telephone_frend']; ?>
						</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h2 style="text-align:center;"> Ubayam Voucher </h2>
		</td>
	</tr>
	<tr>
		<td align="left"><b>Entry Date :</b>
			<?php $date = new DateTime($qry1['entry_date']);
			echo $date->format('d-m-Y'); ?>
		</td>
		<td align="right">
			<p style="text-align:right; line-height:1.7em;"><b>Invoice :</b>
				<?php echo $qry1['ref_no']; ?>
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" style="border:1px solid #CCC;" width="100%" align="center">
				<tr>
					<td width="40%"><b>Name </b> </td>
					<td width="60%">
						<?php echo $qry1['name']; ?>
					</td>
				</tr>
				<tr>
					<td><b>Ubayam Date</b> </td>
					<td>
						<?php echo !empty($qry1['booking_date']) ? date('d-m-Y', strtotime($qry1['booking_date'])) : ''; ?>
					</td>
				</tr>
				<tr>
					<td><b>Booking Slot </b> </td>
					<td>
						<?php echo htmlspecialchars($booked_slot['slot_name'], ENT_QUOTES, 'UTF-8'); ?>
					</td>
				</tr>
				<tr>
					<td><b>Pay For </b> </td>
					<td>
						<?php echo htmlspecialchars($package_names, ENT_QUOTES, 'UTF-8'); ?>
					</td>
				</tr>
				<tr>
					<td><b>Amount(RM) </b> </td>
					<td>
						<?php echo number_format($qry1['amount'], '2', '.', ','); ?>
					</td>
				</tr>
				<tr>
					<td><b>Paid Amount(RM) </b> </td>
					<td>
						<?php echo number_format($qry1['paid_amount'], '2', '.', ','); ?>
					</td>
				</tr>
				<?php
					$amount = $qry1['amount'];
					$paid_amount = $qry1['paid_amount'];
					$balance_amount = $amount - $paid_amount;
				?>
				<tr>
					<td><b>Balance Amount(RM) </b> </td>
					<td>
					<?php echo number_format($balance_amount, '2', '.', ','); ?>
					</td>
				</tr>
				<tr>
					<td><b>Amount In words </b> </td>
					<td>
						<?php echo AmountInWords($qry1['paid_amount']); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php if(count($booked_services)){ ?>
	<tr>
		<td colspan="2">
			<h4 style="text-align:center;">Package Details </h4>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" style="width:100%" align="center">
				<tr>
					<th width="33%" style="text-align:left">Name</th>
					<th width="33%">Quantity</th>
				</tr>
				<?php
					foreach ($booked_services as $row) { ?>
						<tr>
							<td>
								<?php echo $row['name']; ?>
							</td>
							<td align="center">
								<?php echo $row['quantity']; ?>
							</td>
						</tr>
						<?php
					}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php 
	}
	?>
	<?php if(count($booked_addon)){ ?>
	<tr>
		<td colspan="2">
			<h4 style="text-align:center;"> Add-on Details </h4>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" style="width:100%" align="center">
				<tr>
					<th width="33%" style="text-align:left">Name</th>
					<th width="33%">Quantity</th>
					<th width="33%" style="text-align:left">Amount</th>
				</tr>
				<?php
				foreach ($booked_addon as $row) { ?>
					<tr>
						<td>
							<?php echo $row['name']; ?>
						</td>
						<td align="center">
							<?php echo $row['quantity']; ?>
						</td>
						<td>
							<?php echo $row['amount']; ?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php 
	}
	?>
	<tr>
		<td colspan="2">
			<h4 style="text-align:center;"> Payment Details </h4>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" style="width:100%" align="center">
				<tr>
					<th width="33%" style="text-align:left">Payment Date</th>
					<th width="33%">Payment Mode</th>
					<th width="33%" style="text-align:left">Amount</th>
				</tr>
				<?php
				foreach ($pay_details as $row) {
					$payment_mode = $db->table("payment_mode")->where('id', $row['payment_mode_id'])->get()->getRowArray();
					$payment_mode_name = !empty($payment_mode['name']) ? $payment_mode['name'] : "";
					?>
					<tr>
						<td>
							<?php echo date("d/m/Y", strtotime($row['paid_date'])); ?>
						</td>
						<td align="center">
							<?php echo $payment_mode_name; ?>
						</td>
						<td>
							<?php echo $row['amount']; ?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
	<!-- <tr>
		<td colspan="2"><b>Remarks :</b>
			<?php // echo $qry1['description']; ?>
		</td>
	</tr> -->
	 <tr>
        <td colspan="2">
            <p><b>Terms and Conditions</b></p>
            <?php foreach ($terms as $term): ?>
                <p>* <?php echo ($term); ?></p>
            <?php endforeach; ?>
        </td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<!-- <tr>
		<td colspan="2">
			<p><b>Declaration by the donor</b></p>
			<p>To the best of my knowledge, this ubayam donation emanated from a clean source by virtue of any law. This
				donation is done willingly without any duress purported for the Temple usefor whatsoever reason.
				Henceforth, it shall be the property of the Temple and I shall reserve no rights and locus on the said
				donations that entitle me to make any claim whatsoever in the future</p>
		</td>
	</tr> -->
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>Ubayam Agreed By :</td>
		<td>Received By :</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php if (!empty($terms['ubayam'])) { ?>
				<p>
					<?php // echo $terms['ubayam']; ?>
				</p>
			<?php } ?>
			<!-- <p class="dot_line" style="bottom:0;position:relative;margin-top: 80px;">
	  <span>---------------------------------</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---------------------------------</span>
	</p> -->
		</td>
	</tr>
</table>

<script>
	window.print();
</script>