<?php $db = db_connect(); ?>
<body>
<?php /*
<script src="https://cdnjs.cloudflare.com/ajax/libs/mui/3.7.1/js/mui.min.js"
   integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
   crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.bootcdn.net/ajax/libs/vConsole/3.9.1/vconsole.min.js"></script>
*/ ?>
<script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
	integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/imin-printer-2.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>
	
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" id="archanai_ticket">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
		<style>
		body { font-family: 'Barlow', sans-serif; background: #fff; box-sizing: border-box;}
		table { border-collapse:collapse; }
		table td { padding:5px; }
		hr {
		  border:none;
		  border-top:1px dashed #000;
		  color:#fff;
		  background-color:#fff;
		  height:1px;
		}
		p{font-size: 20px;text-align: center;font-weight: 600;font-family: monospace;margin: 0px}
		tr td, tr th{font-size: 20px;}
		#archanai_ticket{
			color: #000;
			background: #fff;
			padding: 5px;
			display: none;
		}
		#archanai_loader{
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			height: 100%;
		}
		img{
			max-width: 100%;
		}
		p{font-size: 20px;text-align: center;font-weight: 600;font-family: monospace;margin: 0px}
		</style>
		<p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:120px;" align="center"></p>
		<h2 style="text-align:center; margin:0"><?php echo $temp_details['name']; ?></h2>
		<p><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
		<?php echo $temp_details['city'].'-'.$temp_details['postcode']; ?>.
		<br>
		Tel: <?= $temp_details['telephone']; ?></p>
		<hr>
	
		<p style="text-align: center;">Date: <?php echo date('d-m-Y', strtotime($qry1['created'])); ?></p>

		<p style="text-align: center;">Bill NO: <?php echo $qry1['ref_no']; ?></p>
		<hr>

		<?php /*<p style="text-align: left;">SNO&nbsp;&nbsp;PARTICULARS</p> */?>
		<hr>
		<?php $sub_total = 0; $i=1; foreach($booking as $row) { ?>
<?php
	$archanai_book_id = $qry1['id'];
	?>
			<?php if ($row['show_deity'] == 1) { ?>
				<div
					style="text-align: center;font-weight:bold; font-size: 35px; padding: 10px; margin: 20px auto; width: 75%; ">
					<?= $row['diety_name']; ?>
					<p style="font-size: 25px;">
						<?= $row['diety_name_tamil']; ?>
					</p>
				</div>
			<?php } ?>
<?php if (!empty($row['watermark_image'])) { ?>
	<p style="text-align: center;"><img
			src="<?php echo base_url(); ?>/uploads/archanai/watermark/<?php echo $row['watermark_image']; ?>"
			style="width:160px;" align="center" alt="image" style="display:block;margin:0 auto;"></p>
<?php } ?>

				<div style="display:flex; justify-content:center;align-items:center;border: 3px solid black;  padding: 10px;">
					<div>
						<p style="font-size: 30px;text-align:left;" ><?  $i++; ?></p>
					</div>
					<div style="  width: 75%;  text-align: center;">
						<p style="margin: 0; font-size: 40px;"><?= $row['name_eng']; ?></p>
						<p style="margin: 0; font-size: 40px;"><?= $row['name_tamil']; ?></p>
					</div>
				</div>
				<?php
			if($row['archanai_category'] == 7){
				$descriptions = json_decode($row['description'], true);
				if (is_array($descriptions) && !empty($descriptions)) {
					foreach ($descriptions as $description) {
						// Adjust margin-bottom to control the space between the descriptions
						echo '<h3 style="text-align:center; font-weight: bold; font-size: 24px; margin-top: 5px; margin-bottom: 5px;">' . htmlspecialchars($description) . '</h3>';
					}
				}
			}
		?>
				<br>
			  <p> <span style="font-size: 32px;">[RM <?= $row['amount']; ?> x <?= $row['quantity']; ?> = RM <?= number_format($row['quantity'] * $row['amount'],2); ?>]</span></p>

		<?php $sub_total += $row['quantity'] * $row['amount']; } ?>
	

		<br>
			<p style="text-align: center; font-size: 40px;">PAID METHOD: <?php echo $payment_name['pay_method']; ?></p>
		<?php
		$archanai_book_id = $qry1['id'];
		$check_amt = $db->table('archanai_booking')->where('id', $archanai_book_id)->get()->getResultArray();
		if (count($check_amt) > 0) {
			$paid_amt = !empty($check_amt[0]['paid_amount']) ? $check_amt[0]['paid_amount'] : 0;
		} else {
			$paid_amt = 0;
		}
		$final_total = $sub_total;
			?>
			<br>
			<p style="text-align: center; font-weight: bold; font-size: 18px;">SUB TOTAL : RM
				<?= number_format($sub_total, 2); ?>
			</p>
			<?php 
			if (!empty($qry1['discount_amount'])) {
				$discount = (float) $qry1['discount_amount'];
				if (!empty($discount)) {
					$final_total -= $discount;
					?>
					<p style="text-align: center; font-weight: bold; font-size: 18px;">DISCOUNT AMOUNT : RM
						<?= number_format($discount, 2); ?>
					</p>
					<?php
				}
			}
			?>
			<br>
			<p style="text-align: center; font-weight: bold; font-size: 18px;">PAID AMOUNT : RM
				<?= number_format($paid_amt, 2); ?>
			</p>
			<?php 
			$balance_amt = $paid_amt - $final_total;
			if (!empty($balance_amt)) {
			?>
			<p style="text-align: center; font-weight: bold; font-size: 18px;">BALANCE AMOUNT : RM
				<?= number_format($balance_amt, 2); ?>
			</p>
			<?php
			}
			?>

		<br>
		<?php if(count($rasi) > 0) {  ?>
			<hr>
			<table style="width:100%;">
			<tr><th align="left">Name</th><th align="left">Rasi</th><th align="left">Natchathram</th></tr>
			<?php foreach($rasi as $res) { ?>
			<tr><td><?= $res['name']; ?></td>
			<td><?= $res['rasi_name_tamil']; ?><br><?= $res['rasi_name_eng']; ?></td>
			<td><?= $res['nat_name_tamil']; ?><br><?= $res['nat_name_eng']; ?></td></tr>
			<?php } ?>
		<?php } ?>
		</table>
		<?php if(count($vehicles) > 0) {  ?>
		<hr>
		<table style="width:100%;">
			<tr>
				<th align="left">Name</th>
				<th align="left">Vehicle No</th>
			</tr>
			<?php foreach($vehicles as $vehicle) { ?>
				<tr>
					<td style="font-size:24px;"><?= $vehicle['name']; ?></td>
					<td style="font-size:24px;"><?= $vehicle['vehicle_no']; ?></td>
				</tr>
			<?php } ?>
		</table>
		<?php } ?>
		<?php
		$archanai_book_id = $qry1['id'];
		$payment_name = $db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $archanai_book_id)->get()->getRowArray();
		?>
		<p style="text-align: center; font-size: 40px;">Total:  RM <?= number_format($final_total,2); ?></p>
		
		<br>
		<br>
	
		
		<?php 
		foreach($booking as $row) {
			if($row['groupname'] == 'NAVAGRAHAM PEYERCHI') { 
			?>
			<p style="text-align:center;font-size:30px;text-transform:uppercase;">1 free small vilaku</p>
			<?php 
			} 
		} 
		?>


		<?php 
		$j=0; 
		foreach($booking as $row) {
		if($row['archanai_category'] == 3)
		{
		$j = $j+1;
		}
		}
		?>
		<br>
		<?php
		echo ($setting["show_date"]?"<div style='text-align:center'>Date ".Date("d-m-Y H:i:s")."</div>":"");
		?>
		<br>
		<?php
		if(!empty($settings['archanai_slogan'])) echo '<p style="text-align:center;">' . $settings['archanai_slogan'] . '</p>';
		/*
		<p><span>---</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---</span></p> */
		?>
		<hr>
		<br>
		<?php
		if(!empty($trans_details)){
			echo '<br><hr>';
			foreach($trans_details as $ky => $td){
				echo '<p>' . $ky . ' ' . $td . '</p>';
			}
		}
		
		if($j > 0)
		{
			echo '<br>';
			if(!empty($settings['kazhanji_option'])){
				if($settings['kazhanji_option'] == 'text'){
					echo '<p style="text-align:center;font-size:30px;">' . (!empty($arc_settings['kazhanji_option_text']) ? $arc_settings['kazhanji_option_text']: '') . '</p>';
				}else{
					echo '<p><img src="' . (!empty($arc_settings['kazhanji_option_image']) ? base_url() . '/uploads/kazhanji/' . $arc_settings['kazhanji_option_image'] : '') . '" width="200" height="160" alt="image" style="display:block;margin:0 auto;"></p>';
				}
			}
			echo '<br>';
		}
		?>
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
		var vConsole = new VConsole();
		function printDiv(){

		  var divToPrint=document.getElementById('archanai_ticket');

		  var newWin=window.open('','Print-Window');

		  newWin.document.open();

		  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

		  newWin.document.close();

		  setTimeout(function(){newWin.close();},1500);

		}
		$(document).ready(function(){
			$(document).on('click', '#web_print', function(){
				printDiv();
			});
			/* var node = document.getElementById('archanai_ticket');
			domtoimage.toJpeg(node).then(function (dataUrl) {
				$('#test_img').attr('src', dataUrl);
			}); */
		});
		var IminPrintInstance = new IminPrinter();
		console.log('IminPrintInstance');
		console.log(IminPrintInstance);
		IminPrintInstance.connect().then(async (isConnect) => {	
			if (isConnect) {
				$('.archanai_loader').hide();
				$('#archanai_ticket').show();
				console.log( await IminPrintInstance.getPrinterStatus());
				var QrCodeSize;
				<?php /*
				mui('body').on('tap', '#imin_print', async function (e) {*/?>
				IminPrintInstance.initPrinter();
				console.log( await IminPrintInstance.getPrinterStatus());
				var node = document.getElementById('archanai_ticket');
				domtoimage.toJpeg(node).then(function (dataUrl) {
					IminPrintInstance.printSingleBitmap(dataUrl).then(()=> {
						console.log('sucess');
						IminPrintInstance.printAndFeedPaper(100);
						IminPrintInstance.partialCut();
						IminPrintInstance.openCashBox();
						setTimeout(function(){window.close();}, 500);
						<?php /*setTimeout(function(){print_queue(IminPrintInstance, ticket, i + 1);},1000);*/ ?>
					});
					<?php /* setTimeout(function(){window.close();}, 1500); */ ?>
				});
				<?php /*});*/ ?>
				   }else{
					   alert('error printer');
				   }
			   });
		   </script>
	   </body>