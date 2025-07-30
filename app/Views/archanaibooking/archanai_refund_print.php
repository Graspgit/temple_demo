<?php global $lang;?>
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
    max-width: 400px;
	margin-left: calc(100% - 400px);
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
<h3 style="text-align:center;">Archanai Refund Report
<?php 
if ($grp != '0' && $grp != '' ){
	echo ' - '.$grp;
}

?>
 </h3>
<h3 style="text-align:center;"> [From : <?= date('d-m-Y', strtotime($fdate)); ?> To <?= date('d-m-Y', strtotime($tdate));?>] </h3>
    <table border="1" width="100%" align="center">
       <thead>
			<tr>
				<th style="width:10%;"><?php echo $lang->date; ?></th>
				<th style="width:20%;"><?php echo $lang->bill_no; ?></th>
				<th style="width:20%;"><?php echo $lang->ref_no; ?></th>
				<th style="width:10%;"><?php echo $lang->booked . ' ' . $lang->through; ?></th>
				<th style="width:10%;"><?php echo $lang->pay_mode; ?></th>
				<th style="width:10%;"><?php echo $lang->amount; ?></th>
			</tr>
		</thead>
        <tbody>
            <?php 
			$summary = array();
			if(!empty($data))
			{
				$total = 0; $i=1;
				foreach($data as $row) { 
					$total += $row['amount'];
					$summary[$row['payment_mode_id']]['name'] = $row['payment_mode_name'];
					if(empty($summary[$row['payment_mode_id']]['amount'])) $summary[$row['payment_mode_id']]['amount'] = 0;
					$summary[$row['payment_mode_id']]['amount'] += $row['amount'];
					?>
					<tr>
						<td align="center"><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
						<td align="center"><?php echo $row['ref_no']; ?></td>
						<td align="center"><?php echo $row['reference_id']; ?></td>
						<td align="center"><?php echo $row['paid_through']; ?></td>
						<td align="center"><?php echo $row['payment_mode_name']; ?></td>
						<td align="right"><?php echo number_format($row['amount'], 2); ?></td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td colspan="5" style="text-align:right !important;">Total</td>
					<td colspan="1" align="right"><?= number_format((float)$total, 2, '.', '');  ?></td>
				</tr>
			<?php
            }
            ?>
        </tbody>
    </table>
	<div class="summary">
		<?php
		if(count($summary) > 0){
			echo '<h3 align="center">Summary</h3>';
			echo '<table><thead><tr><th>Payment Mode</th><th>Amount</th></tr></thead><tbody>';
			$i = 0;
			$sum_payment_mode = 0;
			foreach($summary as $sv){
				$sum_payment_mode += $sv['amount'];
				echo '<tr>';
				// echo '<td align="center">' . ++$i . '</td>';
				echo '<td align="center">' . $sv['name'] . '</td>';
				echo '<td align="center">' . number_format($sv['amount'], 2) . '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '<tfoot><tr>';
			echo '<th colspan="" align="center">Total ' . $ky .'</th>';
			echo '<th>' . number_format($sum_payment_mode, 2) .'</th>';
			echo '</tr></tfoot>';
			echo '</table>';
			$over_all_total += $sum_payment_mode;
		}
		?>
	</div>
</div></div>
<script>
window.print();
</script>
</body>
</html>


 