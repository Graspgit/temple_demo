<title><?php echo $_SESSION['site_title']; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
body { font-family: 'Barlow', sans-serif; }
table { border-collapse:collapse; }
table td, table th { padding:5px; text-align:center; }
.inner_table tr:nth-child(even) { background:#F3F3F3; }
.inner_table tr:last-child { background:#e2dfdf; }
/* .print_header *{
	color: #fff;
}
.print_header{
	
    margin: 0 auto;
    position: relative;
}
.since{
	position: absolute;
    font-size: 10px;
    z-index: 9999;
    right: 55px;
} */
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
<tr><td colspan="2">
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
</td></tr>
<tr><td colspan="2"><hr></td></tr>
</table>

<div>
<h3 style="text-align:center;"> Deity Report 
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
                <th>SNO</th>
                <th width="15%">Date</th>
                <th>Product Name</th>
                <th>Deity Name</th>
                <th>Quantity</th>
                
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
			$summary = array();
			$data['data'] = array();
			$total = 0; 
			$total_qty = 0; 
			$i=1;
			foreach($data as $row) { 
                if($row['status'] == 1) $status = 'Booked';
                else if($row['status'] == 2) $status = 'Completed';
                else $status = 'Canceled';
                if(!empty($row['name'])){
					$amt = number_format((float)$row['rate'], 2, '.', '');
					$summary[$row['deity_id']]['name'] = $row['name'];
					if(empty($summary[$row['deity_id']]['amount'])) $summary[$row['deity_id']]['rate'] = 0;
					$summary[$row['deity_id']]['amount'] += $row['rate'];
					if(empty($summary[$row['deity_id']]['qty'])) $summary[$row['deity_id']]['qty'] = 0;
					$summary[$row['deity_id']]['qty'] += $row['qunt'];
					?>
					<tr>
							<td><?= $i++;?></td>
							<td><?= date('d-m-Y', strtotime($row['date'])); ?></td>
							<td><?= $row['name_eng'] . '/' . $row['name_tamil']; ?></td>
							<td><?= $row['name']; ?></td>
							<td><?= $row['qunt']; ?></td>
							<td><?= $amt; ?></td>
					</tr>
					<?php 
					$total += $row['rate']; 
					$total_qty += $row['qunt']; 
				} 
			} ?>
            <tr>
                    <td colspan="4" style="text-align:right !important;">Total</td>
                    <td><?= $total_qty;  ?></td>
                    <td align="right"><?= number_format((float)$total, 2, '.', '');  ?></td>
            </tr>
        </tbody>
    </table>
	<div class="summary">
		<?php
		if(count($summary) > 0){
			echo '<h3 align="center">Summary</h3>';
			echo '<table><thead><tr><th>Deity</th><th>Qty</th><th>Amount</th></tr></thead><tbody>';
			$i = 0;
			$sum_payment_mode = 0;
			$sum_qty = 0;
			foreach($summary as $sv){
				$sum_payment_mode += $sv['amount'];
				$sum_qty += $sv['qty'];
				echo '<tr>';
				// echo '<td align="center">' . ++$i . '</td>';
				echo '<td align="center">' . $sv['name'] . '</td>';
				echo '<td align="center">' . $sv['qty'] . '</td>';
				echo '<td align="center">' . number_format($sv['amount'], 2) . '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '<tfoot><tr>';
			echo '<th colspan="" align="center">Total ' . $ky .'</th>';
			echo '<th>' . $sum_qty .'</th>';
			echo '<th>' . number_format($sum_payment_mode, 2) .'</th>';
			echo '</tr></tfoot>';
			echo '</table>';
		}
		?>
	</div>
</td></tr></table>
</div></div>
<script>
window.print();
</script>


