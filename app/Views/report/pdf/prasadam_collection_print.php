<?php        
$db = db_connect();
$fdt = isset($_REQUEST['fdt']) ? $_REQUEST['fdt'] : '';
$tdt = isset($_REQUEST['tdt']) ? $_REQUEST['tdt'] : '';
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
    background-color: #444242 !important;
    color: #fff;

}
table { border-collapse:collapse; }
table td, table th { padding:5px; }
.inner_table tr:nth-child(even) { background:#F3F3F3; }
.inner_table tr:last-child { background:#e2dfdf; }
.paid_text { color:green; font-weight:600; }
.unpaid_text { color:red; font-weight:600; }
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
<?php 
 $collection_date = $pdfdata['collection_date'];
 $fltername = $pdfdata['fltername']; 
 ?>
<?php if (!empty($fdt) && !empty($tdt)) { ?>
	<h3 style="text-align:center;"> PRASADAM COLLECTION REPORT <?php echo date("d/m/Y", strtotime($fdt)) . ' - ' . date("d/m/Y", strtotime($tdt)); ?></h3>
<?php } else { ?>
	<h3 style="text-align:center;"> PRASADAM COLLECTION REPORT </h3>
<?php } ?>
</td></tr>
</table>

<table border="1" width="100%" align="center">
    <thead><tr>
    <th width="5%">S.No</th>
	<th align="left" width="9%">Customer Name</th>
    <th align="left" width="9%">Collection Date</th>
    <th align="left" width="10%">Time</th>
    <th align="left" width="10%">Pay For</th>
    <th align="left" width="10%">Amount</th>
    </tr>
    </thead>
    <tbody>
     
    <?php 
		$total = 0; 
		// $fdt= date('Y-m-d',strtotime($collection_date));
		$fdt = isset($_REQUEST['fdt']) ? $_REQUEST['fdt'] : '';
		$tdt = isset($_REQUEST['tdt']) ? $_REQUEST['tdt'] : '';
		$fltername_fil= $fltername;
		$data = [];
		$builder = $db->table('prasadam')
				->select('prasadam.id, prasadam.date, prasadam.customer_name, prasadam.collection_date, prasadam.amount, prasadam.collection_session');

			// Apply date range filter if both dates are provided
			if ($fdt && $tdt) {
				$builder->where('DATE_FORMAT(prasadam.collection_date, "%Y-%m-%d") >=', $fdt)
						->where('DATE_FORMAT(prasadam.collection_date, "%Y-%m-%d") <=', $tdt);
			}

			// Apply customer name filter if provided
			if ($fltername) {
				$builder->where('prasadam.customer_name', $fltername);
			}

			// Fetch the filtered results
			$dat = $builder->orderBy('prasadam.collection_date', 'asc')->get()->getResultArray();
              $sn=1;
              foreach($dat as $row){
				$payfors = $db->table('prasadam_booking_details')
				->join('prasadam_setting', 'prasadam_setting.id = prasadam_booking_details.prasadam_id')
				->select('prasadam_setting.name_eng, prasadam_setting.name_tamil, prasadam_booking_details.quantity')
				->where('prasadam_booking_details.prasadam_booking_id', $row['id'])
				->get()->getResultArray();
            
            $html = "";
            foreach ($payfors as $payfor) {
                $html .= "&#x2022; " . $payfor['name_eng'] . " (Quantity: " . $payfor['quantity'] . ")" . "<br>";
            }
				$collect_name =  $db->table('prasadam_master')->where('id',$row['prasadam_master_id'])->get()->getRowArray();
	?>
	<tr>
		<td><?php echo $sn++; ?></td>
		<td><?php echo $row['customer_name']; ?></td>
		<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($row['collection_date'])); ?></td>
		<td><?php echo $row['collection_session']; ?></td>
		<td style="text-align: left;"><?php echo $html; ?></td>
		<td style="text-align: center;">
			<?php echo $row['amount'] ? number_format($row['amount'], 2, '.', ',') : '-'; ?>
		</td>
	</tr>
	<?php } ?>   

    </tbody>
</table>
</div></div>
