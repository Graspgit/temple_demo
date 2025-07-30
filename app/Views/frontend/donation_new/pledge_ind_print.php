<title><?php echo $_SESSION['site_title']; ?></title>
<?php        
$db = db_connect();
?>
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
    background-color: #ffffff !important;
    color: #000000;

}
table { border-collapse:collapse; }
table td, table th { padding:5px; }
.inner_table tr:nth-child(even) { background:#F3F3F3; }
.inner_table tr:last-child { background:#e2dfdf; }
.paid_text { color:green; font-weight:600; }
.unpaid_text { color:red; font-weight:600; }
</style>

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
		<tr>
			<td colspan="2" style="width: 100%;">
				<h3 style="text-align:center;">Donation Individual Report Name : <?php echo $name; ?> </h3>
			</td>
		</tr>
	</tbody>
</table>
<div>

    <table border="1" width="100%" align="center">
        <thead>
            <tr>
                <th>SNO</th>
                <th>Date</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Pledge Init</th>
                <th>Pledge Amount</th>
                <th>Donated Amount</th>
                <th>Balance Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach($data as $row)
            {
			$bal_amt = floatval($row['current_total_amt']) - floatval($row['current_donation_amount']);
			?>
            <tr>
                    <td><?= $i++;?></td>
                    <td><?= Date("d-m-Y",strtotime($row['created_date'])); ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['phone_code'].$row['mobile']; ?></td>
                    <td><?= number_format($row['donated_pledge_amt'], '2', '.', ','); ?></td>
                    <td><?= number_format($row['current_total_amt'], '2', '.', ','); ?></td>
                    <td><?= number_format($row["current_donation_amount"], '2', '.', ','); ?></td>
                    <td><?= number_format(($bal_amt), '2', '.', ','); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</td></tr></table>
</div></div>
<script>
    
window.print();

</script>


