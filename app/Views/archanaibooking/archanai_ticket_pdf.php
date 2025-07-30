<?php global $lang; ?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
@font-face {
font-family: "Baamini";           
src: url('<?php echo base_url(); ?>/assets/font/Baamini.ttf');
font-weight: normal;
font-style: normal;
} 
</style>
</head>
<body>
<style>
body { font-family: 'Barlow', sans-serif; }
table { border-collapse:collapse; }
table th { padding:5px; text-align:center; }
.inner_table tr:nth-child(even) { background:#F3F3F3; }
.inner_table tr:last-child { background:#e2dfdf; }
.summary table{
	width: 100%;
    min-width: 350px;
}
.summary{
	max-width: 350px;
    margin-left: calc(100% - 350px);
}
</style>
<div style="width: 100%;max-width: 210mm;margin:0 auto;">
<table align="center">
<tr>
	<td colspan="2">
		<table style="width:100%">
			<tr>
				<td width="15%" align="center">
					<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>" style="width:120px;" align="center">
				</td>
				<td width="85%" align="left">
					<h2 style="text-align:left;margin-bottom: 0;font-family: "Baamini" !important;"><?php echo $_SESSION['site_title']; ?></h2>
					<p style="text-align:left; font-size:16px; margin:5px 0px;"><?php echo $_SESSION['address1']; ?>,
					<br><?php echo $_SESSION['address2']; ?>,
					<br><?php echo $_SESSION['city']; ?> - <?php echo $_SESSION['postcode']; ?>
					<br>Tel : <?php echo $_SESSION['telephone']; ?></p>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2"><hr></td></tr>
</table>
<div>
<h3 style="text-align:center;">Archanai Ticket Report
<?php 
if ($grp != '0' && $grp != '' ){
	echo ' - '.$grp;
}

?>
 </h3>
<h3 style="text-align:center;"> [From : <?= date('d-m-Y', strtotime($pdfdata['fdate'])); ?> To <?= date('d-m-Y', strtotime($pdfdata['tdate']));?>] </h3>
    <table border="1" width="100%" align="center">
       <thead>
			<tr>
				<th style="width:10%;">Date</th>
				<th style="width:10%;">Bill No</th>
				<th style="width:10%;">Ref No</th>
				<th style="width:10%;">Booked Through</th>
				<th style="width:10%;">Payment Mode</th>
				<th style="width:10%;">Amount</th>
			</tr>
		</thead>
        <tbody>
            <?php 
			$summary = array();
			if(!empty($pdfdata['data']))
			{
				$total = 0; $i=1;
				foreach($pdfdata['data'] as $row) { 
					$amt = (float) $row['amount'];
					$summary[$row['paid_through']][$row['payment_mode_id']]['name'] = $row['payment_mode_name'];
					if(empty($summary[$row['paid_through']][$row['payment_mode_id']]['amount'])) $summary[$row['paid_through']][$row['payment_mode_id']]['amount'] = 0;
					$summary[$row['paid_through']][$row['payment_mode_id']]['amount'] += $row['amount'];
					?>
					<tr>
						<td align="center"><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
						<td align="center"><?php echo $row['ref_no']; ?></td>
						<td align="center"><?php echo $row['reference_id']; ?></td>
						<td align="center"><?php echo $row['paid_through']; ?></td>
						<td align="center"><?php echo $row['payment_mode_name']; ?></td>
						<td align="right"><?php echo number_format($row['amount'], 2); ?></td>
					</tr>
					<?php $total += $amt; 
				} ?>
				<tr>
						<td colspan="5" style="text-align:right !important;">Total</td>
						<td align="right"><?= number_format((float)$total, 2, '.', '');  ?></td>
				</tr>
				
				<?php
            }
            ?>
        </tbody>
    </table>
	<div class="summary">
		<h3 align="center">Summary</h3>
		<?php
		if(count($summary) > 0){
			$over_all_total = 0;
			foreach($summary as $ky => $sy_values){
				if(count($sy_values) > 0){
					echo '<h3>' . $ky . '</h3>';
					echo '<table><thead><tr><th>#</th><th>Payment Mode</th><th>Amount</th></tr></thead><tbody>';
					$i = 0;
					$sum_payment_mode = 0;
					foreach($sy_values as $sv){
						$sum_payment_mode += $sv['amount'];
						echo '<tr>';
						echo '<td align="center">' . ++$i . '</td>';
						echo '<td align="center">' . $sv['name'] . '</td>';
						echo '<td align="right">' . number_format($sv['amount'], 2) . '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '<tfoot><tr>';
					echo '<th colspan="2" align="center">Total ' . $ky .'</th>';
					echo '<th align="right">' . number_format($sum_payment_mode, 2) .'</th>';
					echo '</tr></tfoot>';
					echo '</table>';
					$over_all_total += $sum_payment_mode;
				}
			}
			echo '<h3 align="center">Overall Total : ' . number_format($over_all_total, 2) . '</h3>';
		}
		?>
	</div>
</div></div>
</body>
</html>


 