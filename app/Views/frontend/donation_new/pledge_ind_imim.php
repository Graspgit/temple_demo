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
	
	<div style="width: 150mm;font-weight: 600;font-family: monospace;" id="booking_tickets">
		<div style="width: 150mm;font-weight: 600;font-family: monospace;" class="booking_ticket">
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
			p{font-size: 26px;text-align: center;font-weight: 600;font-family: monospace;margin: 0px}
			
			h3{ font-size:32px;text-align: center;font-weight: 600;font-family: monospace; text-transform:uppercase; }
			tr td, tr th{font-size: 20px;}
			.booking_ticket{
				color: #000;
				background: #fff;
				padding: 5px;
				display: block;
			}
			#ticket_loader{
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

<h3 style="text-align:center">Pledge Individual Report </h3>
<div     style="font-size: 26px;text-align: center;margin-bottom: 10px;"><b>Name : <?php echo $name; ?></b></div>
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




			
			
			
		</div>
	</div>
	<div class="ticket_loader">
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
	let isConnect = false;
	IminPrintInstance.connect().then(async (connect) => {
		if (connect) {
			isConnect = true;
			$('.ticket_loader').hide();
			$('.booking_ticket').show();
			initiate_load();
		}else{
			alert('error printer');
		}
	});
	function initiate_load(){
		if(isConnect){
			var tot_count = $('#booking_tickets .booking_ticket').length;
			var ticket = [];
			console.log( IminPrintInstance.getPrinterStatus());
			IminPrintInstance.initPrinter();
			<?php /*
			IminPrintInstance.setPageFormat(0);
			*/?>
			var i = 0;
			$('#booking_tickets .booking_ticket').each(function(){
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
			<?php /*
			IminPrintInstance.initPrinter();
			*/?>
			await IminPrintInstance.printSingleBitmap(ticket[i]);
			await IminPrintInstance.printAndFeedPaper(100);
			await IminPrintInstance.partialCut();
			print_queue(IminPrintInstance, ticket, i + 1);
		}else{
			setTimeout(function(){window.close();},500);
		}
	}
	</script>
</body>