<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
body { font-family: 'Barlow', sans-serif; }
.tbor, th{
    border: 1px solid;
}
table th
{
    background-color: #fff !important;
    color: #444242;

}
table { border-collapse:collapse; }
table td, table th { padding:5px; }
.inner_table tr:nth-child(even) { background:#F3F3F3; }
.inner_table tr:last-child { background:#e2dfdf; }
.paid_text { color:green; font-weight:600; }
.unpaid_text { color:blue; font-weight:600; }
.cancel_text { color:red; font-weight:600; }
</style>

<table align="center"style="width: 100%;max-width: 800px;">
<tr><td colspan="2">
	<table border="1" align="center" style="border-collapse: collapse; width: 100%;">
	<tbody>
		<tr>
			<td width="15%" style="border: 1px solid #000; vertical-align: top;">
				<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;" align="left">
			</td>
			<td width="85%" style="padding: 0px; border: 1px solid #000;">
				<table style="width: 100%; border-collapse: collapse;">
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
								<?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?>
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="text-align:center; font-size:16px; margin:0;">
								<?php echo $temp_details['city'].'-'.$temp_details['postcode']; ?>
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<p style="text-align:center; font-size:16px; margin:0;">
								Tel : <?= $temp_details['telephone']; ?>
							</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
			$fdate = $fdate;
			$tdate = $tdate;
		?>
		<tr>
			<td colspan="2" style="width: 100%;">
				<h3 style="text-align:center;"> <?php echo 'FLOATING CASH REPORT '. date("d-m-Y", strtotime($fdate)).' - '.date("d-m-Y", strtotime($tdate)); ?></h3>
			</td>
		</tr>
	</tbody>
</table>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
</table>

<table border="1" width="100%" align="center">
    <thead>
		<tr>
			<th style="width:5%;">S.No</th>
			<th style="width:15%;">Date</th>
			<th style="width:15%;">Opening Cash(RM)</th>
			<th style="width:15%;">Income(RM)</th>
			<th style="width:15%;">Expense(RM)</th>
			<th style="width:15%;">Closing Cash(RM)</th>
			<th style="width:20%;">Checked By</th>
		</tr>
    </thead>
    <tbody>
	<?php 
	if(count($list) > 0){
		$sn = 0;
		foreach($list as $row){
			$sn++
			?>
			<tr>
				<td><?php echo $sn; ?></td>
				<td><?php echo !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : ''; ?></td>
				<td><?php echo !empty($row['opening']) ? number_format($row['opening'], 2) : 0.00; ?></td>
				<td><?php echo !empty($row['income']) ? number_format($row['income'], 2) : 0.00; ?></td>
				<td><?php echo !empty($row['expense']) ? number_format($row['expense'], 2) : 0.00; ?></td>
				<td><?php echo !empty($row['closing']) ? number_format($row['closing'], 2) : 0.00; ?></td>
				<td><?php echo !empty($row['checked_op']) ? $row['checked_op'] : ''; ?></td>
			</tr>
			<?php
		}
	}
	?>

    </tbody>
</table>
<script>
window.print();
</script>