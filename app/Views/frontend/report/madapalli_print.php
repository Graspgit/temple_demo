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
		 <table style="border: none; width: 100%; text-align: center;">
        <tr>
            <td style="border: none; padding-bottom: 0;" align="center">
                <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
						style="width: 300px; max-width: 100%; display: block; margin: auto;">
				</td>
			</tr>
			<tr>
				<td style="border: none; padding-top: 0;">
					<h2 style="text-align: center; margin: 0; font-size: 24px; font-weight: bold; width: 100%;">
						<?php echo $temp_details['name_tamil']; ?>
					</h2>
					<h2 style="text-align: center; margin: 0; font-size: 28px; font-weight: bold; width: 100%;">
						<?php echo $temp_details['name']; ?>
					</h2>
					<p
						style="text-align: center; font-size: 18px; margin: 5px 0; width: 80%; margin-left: auto; margin-right: auto;">
						<?php echo $temp_details['address1'], $temp_details['address2'] ?> <br>
		
						<?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?> <br>
						Tel:<?php echo $temp_details['telephone']; ?>
					</p>
				</td>
			</tr>
		</table>

		<tr>
			<td colspan="2" style="width: 100%;">
				<h3 style="text-align:center;"> MADAPALLI REPORT - <?php echo date("d/m/Y", strtotime($from_date)); ?></h3>
			</td>
		</tr>
	</tbody>
</table>

<h4>Prasadam Details:</h4>
<table border="1" width="100%" align="center">
    <thead>
		<tr>
			<th style="width:5%;text-align: center;">S.No</th>
			<th style="width:8%;text-align: center;">Products</th>
			<th style="width:20%;text-align: center;">Indoor/Outdoor</th> 
			<th style="width:9%;text-align: center;">Quantity</th>
			<th style="width:9%;text-align: center;">Amount</th>
			<th style="width:12%;text-align: center;">Preparation Time</th>
			<th style="width:15%;text-align:center;">Serving Time</th>
    	</tr>
    </thead>
    <tbody>

	<?php
$sn = 1;
foreach ($prasadam as $session => $products) {
    foreach ($products as $row) {
        ?>
        <tr>
            <td style='text-align: center;'><?php echo $sn++; ?></td>
            <td style='text-align: center;'><?php echo $row['name']; ?></td>
            <td><?php echo $row['group_name']; ?></td>
            <td style='text-align: center;'><?php echo $row['quantity']; ?></td>
            <td><?php echo number_format($row['amount'], 2); ?></td>
            <td style='text-align: center;'><?php echo ($row['session'] == 'AM') ? 'AM' : 'PM'; ?></td>
            <td style='text-align: center;'><?php echo $row['session']; ?></td>
        </tr>
        <?php
    }
}
?>


    </tbody>
</table><br>

<h4>Annathanam Details:</h4>
<table border="1" width="100%" align="center">
    <thead>
		<tr>
			<th style="width:5%;text-align: center;">S.No</th>
			<th style="width:8%;text-align: center;">Products</th>
			<th style="width:9%;text-align: center;">Quantity</th>
			<th style="width:12%;text-align: center;">Preparation Time</th>
			<th style="width:15%;text-align:center;">Serving Time</th>
    	</tr>
    </thead>
    <tbody>

	<?php
$sn = 1;
foreach ($annathanam as $session => $products) {
    foreach ($products as $row) {
        ?>
        <tr>
            <td style='text-align: center;'><?php echo $sn++; ?></td>
            <td style='text-align: center;'><?php echo $row['name']; ?></td>
            <td style='text-align: center;'><?php echo $row['quantity']; ?></td>
            <td style='text-align: center;'>
                <?php
                if ($row['session'] == 'Breakfast' || $row['session'] == 'Lunch') echo "AM";
                else echo "PM";
                ?>
            </td>
            <td style='text-align: center;'><?php echo $row['session']; ?></td>
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


