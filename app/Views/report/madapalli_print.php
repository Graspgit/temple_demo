<?php
// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Establish database connection
$db = db_connect();

// Retrieve filter values (sanitize input)
$fdt = isset($_REQUEST['fdt']) ? $_REQUEST['fdt'] : '';

// Fetch site details from session
$site_title = $_SESSION['site_title'];
$logo_img = $_SESSION['logo_img'];
$name_tamil = $_SESSION['name_tamil'];
$city_tamil = $_SESSION['city_tamil'];
$since_tamil = $_SESSION['since_tamil'];
$city = $_SESSION['city'];
$since_eng = $_SESSION['since_eng'];
$address1 = $_SESSION['address1'];
$address2 = $_SESSION['address2'];
$postcode = $_SESSION['postcode'];
$telephone = $_SESSION['telephone'];
$details = $db->table('madapalli_preparation_details')
                    ->where('date', $fdt)
                    ->orderBy('session', 'asc')
                    ->get()
                    ->getResultArray();

		$prasadam = [];
		$annathanam = [];

		foreach ($details as $detail) {
			if ($detail['type'] == 1) {
				$prasadam[$detail['product_id']] = [
					'product_id' => $detail['product_id'],
					'name' => $detail['pro_name_eng'],
					'quantity' => $detail['quantity'],
					// 'quantity' => $detail['total_quantity'],
					'session' => $detail['session']
				];
			} elseif ($detail['type'] == 2) {
				$annathanam[$detail['product_id']] = [
					'product_id' => $detail['product_id'],
					'name' => $detail['pro_name_eng'],
					'quantity' => $detail['quantity'],
					// 'quantity' => $detail['total_quantity'],
					'session' => $detail['session']
				];
			}
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
        }
        .tbor,
        th {
            border: 1px solid;
        }
        table th {
            /* background-color: #444242 !important; */
            color: black;
        }
        table {
            border-collapse: collapse;
        }
        table td,
        table th {
            padding: 5px;
        }
        .inner_table tr:nth-child(even) {
            background: #F3F3F3;
        }
        .inner_table tr:last-child {
            background: #e2dfdf;
        }
        .paid_text {
            color: green;
            font-weight: 600;
        }
        .unpaid_text {
            color: red;
            font-weight: 600;
        }
    </style>
</head>
<body>

<table align="center" style="width: 100%;max-width: 800px;">
    <tr>
        <td colspan="2">
            <table style="width:100%">
                <tr>
                    <td width="15%" align="left"><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $logo_img; ?>" style="width:120px;" align="left"></td>
                    <td width="85%" align="left">
                        <h3 style="text-align:center;margin-bottom: 0;"><?php echo $name_tamil; ?></h3>
                        <p style="text-align:center; font-size:16px; margin:0;"><?php echo $city_tamil; ?> <?php echo $since_tamil; ?></p>
                        <h2 style="text-align:center;margin-bottom: 0; margin-top:8px;"><?php echo $site_title; ?></h2>
                        <p style="text-align:center; font-size:16px; margin:0px;"><?php echo $city; ?> <?php echo $since_eng; ?><br><?php echo $address1; ?>, <?php echo $address2; ?>, <?php echo $postcode; ?> <?php echo $city; ?> <br>Tel : <?php echo $telephone; ?></p>
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
        <td colspan="2" style="width: 100%;">
            
            <h3 style="text-align:center;"> MADAPALLI REPORT - <?php echo date("d/m/Y", strtotime($fdt)); ?></h3>
            
            
        </td>
    </tr>
</table>

<h4>Prasadam Details:</h4>
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
    <?php $sn = 1; foreach($prasadam as $row) { ?>
		<tr>
			<td style='text-align: center;'><?php echo $sn++; ?></td>
			<td style='text-align: center;'><?php echo $row['name']; ?></td>
			<td style='text-align: center;'> <?php echo $row ['quantity']; ?></td>
			<?php if ($row['session'] == 'AM') $prep = "AM";
			else $prep = "PM"; ?>
			<td style='text-align: center;'><?php echo $prep; ?></td>
			<td style='text-align: center;'><?php echo $row['session']; ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
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

    <?php $sn = 1; foreach($annathanam as $row) { ?>
		<tr>
			<td style='text-align: center;'><?php echo $sn++; ?></td>
			<td style='text-align: center;'><?php echo $row['name']; ?></td>
			<td style='text-align: center;'> <?php echo $row ['quantity']; ?></td>
			<?php if ($row['session'] == 'Breakfast') $prep = "AM";
			elseif ($row['session'] == 'Lunch') $prep = "AM";
			else $prep = "PM" ?>
			<td style='text-align: center;'><?php echo $prep; ?></td>
			<td style='text-align: center;'><?php echo $row['session']; ?></td>
		</tr>
	<?php } ?>

    </tbody>
</table>
<script>
    window.print();
</script>

</body>
</html>
