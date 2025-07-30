<?php $db = db_connect(); ?>

<body>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mui/3.7.1/js/mui.min.js"
		integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
	<script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
		integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/imin-printer-2.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" id="archanai_ticket">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
		<style>
			body {
				font-family: 'Barlow', sans-serif;
				background: #fff;
				box-sizing: border-box;
			}

			table {
				border-collapse: collapse;
			}

			table td {
				padding: 5px;
			}

			hr {
				border: none;
				border-top: 1px dashed #000;
				color: #fff;
				background-color: #fff;
				height: 1px;
			}

			p {
				font-size: 14px;
				text-align: center;
				font-weight: 600;
				font-family: monospace;
				margin: 0px
			}

			#archanai_ticket {
				color: #000;
				background: #fff;
				padding: 5px;
				/* display: none; */
				display: block;

			}

			#archanai_loader {
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
				height: 100%;
			}

			img {
				max-width: 100%;
			}

			h2 {
				text-align: center;
				margin: 6px 0;
			}

			.box {
				border: 2px solid #000;
				padding-top: 14px;
				align-items: center;

			}

			.last_line {
				font-size: 14px;
			}

			table tr th,
			table tr td {
				padding: 4px;
				font-size: 20px;
			}

			.table tr th,
			table tr td {
				padding: 4px;
				font-size: 20px;
			}

			table.vechi tr th,
			table.vechi tr td {
				padding: 4px;
				font-size: 22px;
			}

			.capitalize{
				text-transform: capitalize;
				text-align: center;
			}
		</style>
		<!-- <p>ஓம் சக்தி</p> -->
		<p><?php if (!empty($temp_details['ar_image'])) { ?>
				<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['ar_image']; ?>"
					style="width:120px;" align="center">
			<?php } else { ?>
				<img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;"
					align="center">
			<?php } ?>
		</p>
		<!--p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;" align="center"></p-->
		<h2><?php echo $temp_details['name_tamil']; ?></h2>
		<p><?php echo $temp_details['since_tamil']; ?></p>
		<h2><?php echo $temp_details['name']; ?></h2>
		<p><?php echo $temp_details['since_eng']; ?></p>
		<p><?php echo $temp_details['address1']; ?>
			<?php echo $temp_details['postcode'] . ' ' . $temp_details['city']; ?>. <br>
			H/P : <?= $temp_details['telephone']; ?>
		</p>
		
		<?php $total = 0; ?>

		<hr style="border-top: 4px dotted #000;">
		<?php foreach($deities as $deity){ ?>
			<h3 style="text-align: center; font-size: 26px;"><?= $deity['name_tamil']; ?></h3>
			<h3 style="text-align: center; font-size: 26px;"><?= $deity['name']; ?></h3>
		<?php } ?>

		<h3 class="mt-3" style="text-align:center; font-weight: bold; font-size: 26px; border: 3px solid black; padding: 2px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background-color: #f9f9f9;">
			&nbsp;&nbsp;<?php echo $data['name_tamil']; ?><br>&nbsp;&nbsp;
			<?php echo $data['name_eng']; ?></h3>

		<?php if($data['daytype'] == 'years') { 
			$total += $data['unit_price'] * $deity_count;
		} else {
			$total += $data['unit_price'] * $data['no_of_days'] * $deity_count;
		} ?>
		
		
		<p style="text-align: center; font-weight: bold; font-size: 30px;">RM :<?= number_format($total, 2); ?></p>
		<?php $balance_amt = ($data['amount'] - $data['paid_amount']); ?>
		
		<br>
		<p style="text-align: center; font-weight: bold; font-size: 18px;">PAID AMOUNT : RM
			<?= number_format($data['paid_amount'], 2); ?>
		</p>

		<p style="text-align: center; font-weight: bold; font-size: 18px;">BALANCE AMOUNT : RM
			<?= number_format($balance_amt, 2); ?>
		</p>
		<br>
		<p style="text-align: center; font-weight: bold; font-size: 14px;">Invoice Number :
			<?= $data['ref_no']; ?>
		</p>
		<br>
		<table style="width:100%;">
			<tr>
				<td align="center"><strong>Devotee Name:</strong> <?php echo $data['name']; ?></td>
				<td align="center"><strong>Phone Number:</strong> <?php echo $data['mobile_no']; ?></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><strong>Remarks:</strong> <?php echo $data['description']; ?></td>
			</tr>
		</table>
		<hr>
		<table style="width:100%;">
			<tr>
				<th align="center">Name</th>
				<th align="center">Rasi</th>
				<th align="center">Natchathram</th>
			</tr>
			<?php 
			foreach($details as $row){ ?>
				<tr>
					<td style="text-align: center"><?php echo $row['name']; ?></td>
					<td style="text-align: center"><?php echo $row['rasi']; ?></td>
					<td style="text-align: center"><?php echo $row['natchathiram']; ?></td>
				</tr>
			<?php } ?>
		</table>
		<hr>
		<table style="width:100%;" border="1">
			<tr>
				<th align="center">Type</th>
				<th align="center">Date</th>
				<th align="center">No.of.Days</th>
				<?php if($data['daytype'] == 'weekly'){ ?>
					<th align="center">Day</th>
				<?php } ?>

			</tr>
			<tr>
				<td align="center" class="capitalize"><?php echo $data['daytype']; ?></td>
				<?php 
				if ($data['daytype'] == 'days') { 
					$output = [];
					foreach ($dates as $date) {
						$output[] = date('d-m-Y', strtotime($date['date']));
					} ?>
					<td><?php echo implode(', ', $output); ?></td>
				<?php } else { ?>
					<td align="center"><?php echo date('d-m-Y', strtotime($data['start_date'])); ?> - <?php echo date('d-m-Y', strtotime($data['end_date'])); ?></td>
				<?php } ?>

				<td align="center"><?php echo $data['no_of_days']; ?></td>
					<?php if($data['daytype'] == 'weekly'){ 
					$day = '';
					if ($data['dayofweek'] == 0) {
						$day = 'Sunday';
					} elseif ($data['dayofweek'] == 1) {
						$day = 'Monday';
					} elseif ($data['dayofweek'] == 2) {
						$day = 'Tuesday';
					} elseif ($data['dayofweek'] == 3) {
						$day = 'Wednesday';
					} elseif ($data['dayofweek'] == 4) {
						$day = 'Thursday';
					} elseif ($data['dayofweek'] == 5) {
						$day = 'Friday';
					} elseif ($data['dayofweek'] == 6) {
						$day = 'Saturday';
					}
					?>
						<td align="center"><?php echo $day; ?></td>
					<?php } ?>
			</tr>
		</table>

		
		<hr style="border-top: 4px dotted #000;">
		<?php

		$dateTime = new DateTime($data['created']);


		$formattedDate = $dateTime->format('d/m/Y');


		$formattedTime = $dateTime->format('g:i:s A');
		?>
		<br>
		<br>
		<br>
		<p style="text-align: center;"> <?php echo $formattedDate . ' ' . $formattedTime; ?></p><br>
		<p><span>---</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---</span></p>
	</div>


	</div>
	<div class="archanai_loader">
		<img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
	</div>
	<?php /* <img src="" id="test_img" /> */ ?>
	<?php /* <div>
<button class="btn btn-primary" id="web_print">Web Print</button>
<button class="btn btn-success" id="imin_print">Imin Print</button>
</div> */ ?>
	<script>
		//var vConsole = new VConsole();
		function printDiv() {

			var divToPrint = document.getElementById('archanai_ticket');

			var newWin = window.open('', 'Print-Window');

			newWin.document.open();

			newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

			newWin.document.close();

			setTimeout(function () { newWin.close(); }, 1500);

		}
		$(document).ready(function () {
			$(document).on('click', '#web_print', function () {
				printDiv();
			});
			/* var node = document.getElementById('archanai_ticket');
			domtoimage.toJpeg(node).then(function (dataUrl) {
				$('#test_img').attr('src', dataUrl);
			}); */
		});
		var IminPrintInstance = new IminPrinter();

		IminPrintInstance.connect().then(async (isConnect) => {
			if (isConnect) {
				$('.archanai_loader').hide();
				$('#archanai_ticket').show();
				console.log(await IminPrintInstance.getPrinterStatus());
				var QrCodeSize;
				//mui('body').on('tap', '#imin_print', async function (e) {
				IminPrintInstance.initPrinter();
				console.log(await IminPrintInstance.getPrinterStatus());
				var node = document.getElementById('archanai_ticket');
				domtoimage.toJpeg(node).then(function (dataUrl) {
					IminPrintInstance.printSingleBitmap(dataUrl).then(() => {
						console.log(' sucess');
						IminPrintInstance.printAndFeedPaper(100);
						IminPrintInstance.partialCut();
						IminPrintInstance.openCashBox();
						setTimeout(function () { window.close(); }, 500);
					});

				});
				//});
			} else {
				alert('error printer');
			}
		});
	</script>
</body>