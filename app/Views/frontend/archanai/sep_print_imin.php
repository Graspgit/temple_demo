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
	
	<div id="archanai_ticket">
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
		#archanai_ticket{
			color: #000;
			background: #fff;
			padding: 5px;
			font-weight: 600;
			font-family: monospace;
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
		</style>
		<?php 
		//print_r($rasi);
		$i=1; foreach($booking as $row) 
			{
			$qty = $row['quantity']; 
			for($j=0; $j<$qty; $j++) 
				{ ?>

		<div style="width: 150mm;font-weight: 600;font-family: monospace;"  class="arc">
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
				.arc{
					color: #000;
					background: #fff;
					padding: 5px;
					font-weight: 600;
					font-family: monospace;
				}
				img{
					max-width: 100%;
				}
			</style>
			<p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:280px;" align="center"></p>
			<h2 style="text-align:center; margin:0; font-size:26px;"><?php echo $temp_details['name']; ?></b></h2>
			<p style="text-align:center; margin:0; font-size:26px;"><?php echo $temp_details['address1']; ?> <?php echo $temp_details['address2']; ?></br>
			<?php echo $temp_details['city'].'-'.$temp_details['postcode']; ?>. 
			Tel: <?= $temp_details['telephone']; ?></p>
			<hr>
			<?php
            if(!empty($row['tharpanam_start']) && !empty($row['tharpanam_end'])){
            ?>
			<br>
             <p ><span style="font-size: 26px;">TOKEN NO:</span>  <span style="border: 1px solid #000;padding: 7px 18px;border-radius: 100%;color: #000;font-size: 30px;font-weight: bold;"><?php echo $row['tharpanam_start'] + $j; ?></span></p>
            </br>
            <?php
            }
            ?>

			<p style="text-align: center;font-size:26px;">Date: <?php echo date('d-m-Y', strtotime($qry1['created'])); ?></p>
			<p style="text-align: center;font-size:26px;">Bill NO: <?php echo $qry1['ref_no']; ?></p>
			<hr>

			<!-- <p style="text-align: left;">SNO&nbsp;&nbsp;PARTICULARS</p> -->
			<!-- <hr> -->
			<?php
				$archanai_book_id = $qry1['id'];
				$payment_name = $db->table('archanai_payment_gateway_datas')->where('archanai_booking_id', $archanai_book_id)->get()->getRowArray();
				?>
				
			<?php if ($row['show_deity'] == 1) { ?>
				<!-- <div
					style="text-align: center;font-weight:bold; font-size: 35px; border: 2px solid #000; padding: 10px; margin: 20px auto; width: 75%; border-radius: 10px; background-color: #f9f9f9;">
					<?= $row['diety_name']; ?>
					<p style="font-size: 25px;"><?= $row['diety_name_tamil']; ?></p>
				</div> -->
				<div
					style="text-align: center;font-weight:bold; font-size: 35px; padding: 10px; margin: 20px auto; width: 75%; ">
					<?= $row['diety_name']; ?>
					<p style="font-size: 25px;"><?= $row['diety_name_tamil']; ?></p>
				</div>
			<?php } ?>
			<?php if(!empty($row['watermark_image'])) { ?>
				<p style="text-align: center;"><img src="<?php echo base_url(); ?>/uploads/archanai/watermark/<?php echo $row['watermark_image']; ?>" style="width:160px;" align="center" alt="image" style="display:block;margin:0 auto;"></p>
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
				<!-- <div style="display: flex; justify-content: center; align-items: center;
					<?= ($row['groupname'] == 'POOJA LAMP') ? 'border: 3px solid black;  padding: 10px;' : ''; ?>">
					
					<div>
						<p style="font-size: 30px; text-align: left;"><?= $i++; ?></p>
					</div>
					<div style="padding: 10px; margin: 20px auto; width: 75%; text-align: center;">
						<p style="margin: 0; font-size: 40px;"><?= $row['name_eng']; ?></p>
						<p style="margin: 0; font-size: 40px;"><?= $row['name_tamil']; ?></p>
					</div>
				</div> -->
				<?php
				if ($row['archanai_category'] == 7) {
					$descriptions = json_decode($row['description'], true);
					if (is_array($descriptions) && !empty($descriptions)) {
						foreach ($descriptions as $description) {
							// Adjust margin-bottom to control the space between the descriptions
							echo '<h3 style="text-align:center; font-weight: bold; font-size: 24px; margin-top: 5px; margin-bottom: 5px;">' . htmlspecialchars($description) . '</h3>';
						}
					}
				}
					?>
				<p> <span style="font-size: 30px;">[RM<?= $row['amount']; ?> x 1 = RM<?= number_format($row['amount'],2); ?>]</span></p>
			<?php $row['amount']; ?>
			<p style="text-align: center; font-size: 40px;">PAID METHOD: <?php echo $payment_name['pay_method']; ?></p>
		
			<hr>
			<p style="text-align: center; font-size: 34px;">Total:  RM <?= number_format($row['amount'],2); ?></p>
			<!-- <br> -->
			<?php 
			if($row['archanai_category'] == 2){
				if(!empty($vehicles)) {  ?>
				<hr>
				<br>
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
				<br>	
				<?php 
				}
			}
			 ?>
					 
			<?php 
			foreach($booking as $row1) {
				if($row1['groupname'] == 'NAVAGRAHAM PEYERCHI') { 
				?>
				<p style="text-align:center;font-size:30px;text-transform:uppercase;">1 free small vilaku</p>
				<?php 
				} 
			} 
			?>			 
			<?php if(!empty($rasi) && $row['archanai_category'] == 1){ ?>
				<hr>
				<table style="width:100%; font-size:20px">
					<tr><th align="left">Name</th><th align="left">Rasi</th><th align="left">Natchathram</th></tr>
					<?php foreach($rasi as $res) { ?>
					<tr><td><?= $res['name']; ?></td>
					<td><?= $res['rasi_name_tamil']; ?><br><?= $res['rasi_name_eng']; ?></td>
					<td><?= $res['nat_name_tamil']; ?><br><?= $res['nat_name_eng']; ?></td></tr>
					<?php } ?>
				</table>
			<?php }
				echo ($setting["show_date"]?"<div style='text-align:center'>Date ".Date("d-m-Y H:i:s")."</div>":"");
			?>
			
			<?php 
				if(!empty($settings['archanai_slogan'])) echo '<br><p style="text-align:center;">' . $settings['archanai_slogan'] . '</p>';
			?>
			<hr>
			<!-- <br> -->
			<?php /* <img src="<?php echo $qrcdoee; ?>" style="display:block;margin:0 auto;" width="250" height="250">
			<p style="text-align:center;font-size:20px;font-weight:bold;">["PLEASE SCAN HERE"]</p> */ ?>
			<!-- <br><br> -->
			<?php /* <p  class="dot_line"><span>---</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---</span></p> */ ?>
			
			<!-- <br> -->
			<?php
			if(!empty($trans_details)){
				echo '<br><hr>';
				foreach($trans_details as $ky => $td){
					echo '<p>' . $ky . ' ' . $td . '</p>';
				}
			}
		?>
		</div>
		<?php
	
		if($row['archanai_category'] == 3){
			echo '<div style="width: 150mm;font-weight: 600;font-family: monospace;"  class="arc">
			<style>
				body { font-family: \'Barlow\', sans-serif; background: #fff; box-sizing: border-box;}
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
				.arc{
					color: #000;
					background: #fff;
					padding: 5px;
					font-weight: 600;
					font-family: monospace;
				}
				img{
					max-width: 100%;
				}
			</style>
			<br>';
			
			if(!empty($row['kazhanji_option'])){
				
				if($row['kazhanji_option'] == 'text'){
					echo '<p style="text-align:center;font-size:30px;">' . (!empty($row['kazhanji_option_text']) ? $row['kazhanji_option_text']: '') . '</p>';
				}else{
					
					echo '<p><img src="' . (!empty($row['kazhanji_option_image']) ? base_url() . '/uploads/kazhanji/' . $row['kazhanji_option_image'] : '') . '" width="200" height="160" alt="image" style="display:block;margin:0 auto;"></p>';
				}
			}
			echo '<br></div>';
		}
		?>
		 
		<?php 
				}
			} 
		?>
		

	</div>
	<div class="archanai_loader">
		<img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
	</div>
	<div class="test_div">
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
		var tot_count = $('#archanai_ticket .arc').length;
		/* $('#archanai_ticket .arc').each(function(i){
			var node = this;
			domtoimage.toJpeg(node).then(function (dataUrl) {
				$('.test_div').append('<img src="' + dataUrl + '" />');
				IminPrintInstance.printSingleBitmap(dataUrl);
				IminPrintInstance.printAndFeedPaper(100);
				if(i >= (tot_count - 1)){setTimeout(function(){window.close();}, 1500);}
			});
		}); */
		/* var node = document.getElementById('archanai_ticket');
		domtoimage.toJpeg(node).then(function (dataUrl) {
			$('#test_img').attr('src', dataUrl);
		}); */
	});
	/* domtoimage.toJpeg(node).then(function (dataUrl) {
		$('#test_img').attr('src', dataUrl);
	}); */
	var IminPrintInstance = new IminPrinter();
	console.log('IminPrintInstance');
	console.log(IminPrintInstance);
	let isConnect = false;
	IminPrintInstance.connect().then(async (connect) => {
		if (connect) {
			isConnect = true;
			$('.archanai_loader').hide();
			$('#archanai_ticket').show();
			initiate_load();
		}else{
			alert('error printer');
		}
	});
	function initiate_load(){
		if(isConnect){
			var tot_count = $('#archanai_ticket .arc').length;
			var ticket = [];
			console.log( IminPrintInstance.getPrinterStatus());
			IminPrintInstance.initPrinter();
			//IminPrintInstance.setPageFormat(0);
			var i = 0;
			$('#archanai_ticket .arc').each(function(){
				var node = this;
				domtoimage.toJpeg(node).then(function (dataUrl) {
					console.log('i=' + i);
					ticket[i] = dataUrl;
					if(i >= (tot_count - 1)){
						print_queue(IminPrintInstance, ticket, 0);
					}
					i++;
				});
			});
		}
	}
	async function print_queue(IminPrintInstance, ticket, i){
		if(i < ticket.length){
			console.log(IminPrintInstance.getPrinterStatus());
			console.log('test');
			//IminPrintInstance.initPrinter();
			$('.test_div').append('<img src="' + ticket[i] + '" />');
			await IminPrintInstance.printSingleBitmap(ticket[i]);
			await IminPrintInstance.printAndFeedPaper(100);
			await IminPrintInstance.partialCut();
			print_queue(IminPrintInstance, ticket, i + 1);
		}else{
			IminPrintInstance.openCashBox();
			setTimeout(function(){window.close();},500);
		}
	}
	</script>
</body>