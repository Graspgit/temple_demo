<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<style>
	body {
		height: 100vh;
		width: 100%;
	}

	.prod::-webkit-scrollbar {
		width: 3px;
	}

	.prod::-webkit-scrollbar-track {
		background: #f1f1f1;
	}

	.prod::-webkit-scrollbar-thumb {
		background: #d4aa00;
	}

	.prod::-webkit-scrollbar-thumb:hover {
		background: #e91e63;
	}

	a {
		text-decoration: none !important;
	}

	.table tr th {
		border: 1px solid #f7e086;
		font-size: 14px;
		background: #f7ebbb;
		color: #333232;
	}

	.pack,
	.pay {
		margin-bottom: 15px;
	}

	.form-label {
		text-transform: uppercase;
		font-size: 13px;
		letter-spacing: 1px;
		color: #333333;
		text-align: left;
		width: 100%;
	}

	.input {
		width: 100%;
		text-align: left;
	}

	select.input {
		color: #000;
	}

	.sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title {
		display: block !important;
		font-size: 11px;
		color: #FFFFFF;
	}

	.sidebar .nav .nav-item.active>.nav-link i.menu-icon {
		background: #edc10f;
		padding: 1px;
		list-style: outside;
		border-radius: 5px;
		box-shadow: 2px 5px 15px #00000017;
	}

	.sidebar-icon-only .sidebar .nav .nav-item .nav-link {
		display: block;
		padding-left: 0.25rem;
		padding-right: 0.25rem;
		text-align: center;
		position: static;
	}

	.sidebar-icon-only .sidebar .nav .nav-item .nav-link[aria-expanded] .menu-title {
		padding-top: 7px;
	}

	.sidebar-icon-only .main-panel {
		width: calc(100% - 0px);
	}

	.back {
		background: #00000087;
		padding: 15px;
		color: white;
		min-height: 120px;
	}

	.back h5 {
		min-height: 80px;
		font-size: 15px;
		font-weight: bold;
		color: #FFFFFF;
	}

	.greensubmit {
		background: #ab8a04 !important;
		font-weight: bold !important;
		color: #ffffff !important;
		box-shadow: -1px 10px 20px #ab8a04;
		background: #ab8a04 !important;
		background: -moz-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
		background: -webkit-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
		background: linear-gradient(to right, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
	}

	.ar_btn {
		background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
		border-radius: 15px;
		font-weight: bold;
		height: 2.75em;
		margin: 10px;
	}

	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;

		background-color: rgb(0, 0, 0);
		background-color: rgba(0, 0, 0, 0.4);
	}

	.modal-content {
		background-color: #fefefe;
		margin: 155px auto 20px auto;
		padding: 20px;
		border: 1px solid #888;
		width: 100%;
		max-height: 60vh;
		max-width: 155vh;
		overflow-y: auto;
	}

	.modal-dialog {
        max-width: 549px;
        margin: 5.75rem auto;
    }

	.modal-backdrop {
		position: fixed;
		top: 0;
		left: 0;
		z-index: -1;
		width: 100vw;
		height: 100vh;
		background-color: #000;
	}

	.body-no-scroll {
		overflow: hidden;
		height: 100%;
	}


	.close {
		color: #aaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
	}
	.capitalize{
		text-transform: capitalize;
		text-align: center;
	}
    .card .header1 {
    color: #555;
    padding: 10px 20px;
    position: relative;
    border-bottom: 1px solid rgba(204, 204, 204, 0.35);
}

.card .body {
    font-size: 14px;
    color: #222222;
    padding: 20px;
}
.nav-tabs li:has(.active) {
  background:#d4aa00; color:#fff;
}
.nav-tabs li { margin:4px 6px; width:49%; padding:10px 20px; background:#f7ebbb; text-align:center; cursor:pointer; }
.nav-tabs li a { font-weight:600; font-size:18px; width:100%; color:#FFF; color:#000; }
.nav-tabs li a.active { color:#fff; }
</style>

<body class="sidebar-icon-only">
  <div class="container-scroller">

    <div class="container-fluid page-body-wrapper">
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
                             
          <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<!-- Popup Modal -->			
					<div class="col-md-12">
						<h3 style="text-align: center" >Madapalli Booking details</h3>
					</div><br>

					<div class="header1">
						<form action="<?php echo base_url(); ?>/report_online/madapalli" method="POST">
							<div class="container-fluid">
								<div class="row clearfix">
									<div class="col-md-2 col-sm-4">
										<div class="form-group form-float">
											<div class="form-line" id="bs_datepicker_container" >
												<input type="date" name="fdt" id="fdt" class="form-control" value="<?php echo $from_date; ?>"  >
												<label class="form-label"><?php echo $lang->from; ?></label>
											</div>                                                        
										</div>                                            
									</div>
									<!-- <div class="col-md-2 col-sm-4">
										<div class="form-group form-float">
											<div class="form-line" id="bs_datepicker_container" >
												<input type="date" name="tdt" id="tdt" class="form-control" value="<?php echo $to_date; ?>"  >
												<label class="form-label"><?php echo $lang->to; ?></label>
											</div>                                                        
										</div>                                            
									</div> -->
									<div class="col-md-2 col-sm-4">
										<button type="submit" class="btn btn-success">Filter</button>
										<!-- <label type="submit" class="btn btn-success btn-lg waves-effect" id="submit">FILTER</label> -->
									</div>
									<div class="col-md-6" align="right">
										<a href="<?php echo base_url('report_online/madapalli_print_a4') . '?from_date='. $from_date; ?>"       <?php //. '&to_date=' . $from_date; ?>
										class="btn btn-primary">Print</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				
					<div class="body">	
					<ul class="nav nav-tabs tab-nav-right" role="tablist" style="flex-direction: row;">
                            <li role="presentation"><a href="#prasadam" data-toggle="tab" aria-expanded="false" class="active">PRASADAM</a></li>
                            <li role="presentation"><a href="#annathanam" data-toggle="tab" aria-expanded="false">ANNATHANAM</a></li> 
                        </ul>
						<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade show active" id="prasadam" aria-labelledby="prasadam-tab">
    <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
      <!-- Your Prasadam tables here -->
      <?php foreach ($prasadam as $session => $products): ?>
        <h4 style="text-align: center;"><?php echo $session; ?></h4><br>
        <table class="table table-striped">
          <thead>
            <tr>
              <th style="width:10%;">S.No</th>
              <th style="width:20%;">Products</th>
              <th style="width:20%;">Indoor/Outdoor</th>
              <th style="width:30%;">Quantity</th>
              <th style="width:30%;">Amount</th>
              <th style="width:20%;">Session</th>
              <th style="width:20%;">Details</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($products as $row): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['session']; ?></td>
                <td>
                  <a class="btn btn-warning btn-reprint btn-rad"
                     data-id="<?php echo $row['product_id']; ?>"
                     data-type="1"
                     data-date="<?php echo $from_date; ?>"
                     data-slot="<?php echo $row['session']; ?>"
                     title="Payment Mode"
                     target="_blank">Details</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endforeach; ?>
    </div>
  </div>

  <div role="tabpanel" class="tab-pane fade" id="annathanam" aria-labelledby="annathanam-tab">
    <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
      <!-- Your Annathanam tables here -->
      <?php foreach ($annathanam as $session => $products): ?>
        <h4 style="text-align: center;"><?php echo $session; ?></h4><br>
        <table class="table table-striped">
          <thead>
            <tr>
              <th style="width:10%;">S.No</th>
              <th style="width:20%;">Products</th>
              <th style="width:30%;">Quantity</th>
              <th style="width:20%;">Session</th>
              <th style="width:20%;">Details</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($products as $row): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['session']; ?></td>
                <td>
                  <a class="btn btn-warning btn-reprint btn-rad"
                     data-id="<?php echo $row['product_id']; ?>"
                     data-type="2"
                     data-date="<?php echo $from_date; ?>"
                     data-slot="<?php echo $row['session']; ?>"
                     title="Payment Mode"
                     target="_blank">Details</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endforeach; ?>
    </div>
  </div>
</div>

				</div>
				</div>
						  
			</div>
		
          </div>
        </div>
        
    </div>
      <!-- main-panel ends -->
  </div>
</div>

  <!-- Modal Structure -->
<div class="modal fade" id="reprintModal" tabindex="-1" aria-labelledby="reprintModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reprintModalLabel">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>Name</th>
							<th>Mobile</th>
							<th>Quantity</th>
							<th>Amount</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                    <tbody id="detailsTableBody">
                        <!-- Data will be appended here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

  
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
  <!-- base:js -->
  <script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="<?php echo base_url(); ?>/assets/archanai/vendors/chart.js/Chart.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
  <script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
  <script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>
  <script src="<?php echo base_url(); ?>/assets/archanai/js/settings.js"></script>
  <script src="<?php echo base_url(); ?>/assets/archanai/js/todolist.js"></script>
  <!-- Custom js for this page-->
  <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
  
  
  
<!--script src='https://code.jquery.com/jquery-2.2.4.min.js'></script-->
<script  src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>

<script>
$(document).ready(function() {
  $('.nav-tabs li').click(function(e) {
    // Prevent default link behavior
    e.preventDefault();

    // Trigger the anchor click event to switch the tab
    $(this).find('a').tab('show');
  });

  // Prevent the default behavior when clicking the anchor directly
  $('.nav-tabs li a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  });
});


	$(document).on("click", ".btn-reprint", function (e) {
		e.preventDefault();
		var productId = $(this).data("id");
		var date = $(this).data("date");
		var slot = $(this).data("slot");
		var type = $(this).data("type");

        var fAjax = ()=>{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>/report_online/get_madapalli_user_details",
			data: { id: productId, date: date, session: slot, type: type},
			success: function (response) {
				var obj = JSON.parse(response);
				if(typeof obj.session_expired != "undefined")
                {
                    show_login_pg((par)=>{
                        fAjax();
                    });
                    return;
                }
				console.log('result:', obj);
				$("#detailsTableBody").empty();
				var data = obj.list;
				if (data.length === 0) {
					$("#detailsTableBody").append("<tr><td colspan='4' class='text-center'>No data available</td></tr>");
				} else {
					var statusLabel = '';
					$.each(data, function (index, item) {

						$("#detailsTableBody").append(
							"<tr>" +
								"<td>" + (index + 1) + "</td>" +
								"<td>" + item.name + "</td>" +
								"<td>" + item.mobile + "</td>" +
								"<td>" + item.quantity + "</td>" +
								"<td>" + item.amount + "</td>" +
								"<td>" + item.session + "</td>" +
							"</tr>"
						);
					});
				}

				$("#reprintModal").modal("show");
			},
			error: function () {
				alert("Error fetching data.");
			}
		});
        }
        fAjax();
	});
</script>
</body>
