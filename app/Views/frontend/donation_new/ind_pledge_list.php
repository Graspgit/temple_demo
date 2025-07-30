<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
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
		width: 80%;
		max-height: 60vh;
		overflow-y: auto;
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
.modal-backdrop.show {
    opacity: 0;
    z-index: -1;
}
@media (min-width: 576px) {
    .modal-dialog {
        max-width: 80%;
        margin: 1.75rem auto;
    }
}
</style>

<body class="sidebar-icon-only">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    <div class="container-fluid page-body-wrapper">

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
                             
          <div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<!-- Popup Modal -->
					

					
						<div class="col-md-12">
							<h3 style="text-align: center" >Pledge Individual Report</h3>
						</div><br>

						<div class="header1">
                            <form action="<?php echo base_url(); ?>/donation_online/print_individual_report" method="POST">
                                <div class="container-fluid">
                                    <div class="row clearfix">
                                    
                                        <!-- <div class="col-md-2 col-sm-4">
                                            <div class="form-group form-float">
												<div class="form-line">
                                                <label class="form-label">Payment Type Filter</label>
													<select class="form-control" name="group_filter" id="group_filter">
                                                        <option value="0">Select Type</option>
														<option value="full">Fully Paid</option>
														<option value="partial">Partially paid</option>
														<option value="only_booking">Only Booking</option>
                                                        
                                                    </select>
													<label class="form-label"></label>
												</div>
											</div>                                            
                                        </div> -->

                                        <div class="col-md-11 col-sm-12" align="right">                                
                                            <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit_print" name="submit_print">Print</button>
                                            <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit_print_imim" name="submit_print_imim">Print Imim</button>
                                            <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit_print_pdf" name="submit_print_pdf">Pdf</button>
                                            <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit_print_excel" name="submit_print_excel">Excel</button>
                                            <input type="hidden" id="pledge_id" name="pledge_id" value="<?php echo $pledge_id; ?>" />
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        
						<div class="body">		
						   <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                                <table style="width:100%;" align="center" class="table table-striped dataTable" id="datatables">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">S.No</th>
                                            <th style="width:5%;">Date</th>
                                            <th style="width:10%;">Name</th>
                                            <th style="width:15%;">Phone</th>
                                            <th style="width:10%;">Pledge Init</th>
                                            <th style="width:10%;">Pledge Amount</th>
                                            <th style="width:5%;">Donated Amount</th>
                                            <th style="width:5%;">Balance Amount</th>
										</tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

						
                            <div id="alert-modal1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content" style="width: 30%;">
                                        <div class="modal-body p-4">
                                            <div class="text-center">
                                                <i class="dripicons-information h1 text-info"></i>
                                                <table>
                                                    <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
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
    <!-- page-body-wrapper ends -->
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
  
<link href="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">  
  
<!-- Jquery DataTable Plugin Js -->
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<!-- Custom Js -->
<script src="https://panel.srimuneeswaran.org/dev/assets/js/pages/tables/jquery-datatable.js"></script>
<script>
	$(document).ready(function(){
        $(document).on('click', '.btn-payment', function(e) {
        e.preventDefault();
        // var bookingId = $(this).attr('href').split('/').pop();
        var bookingId = $(this).data('id');

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/annathanam_counter/gtpaymentdata",
            data: {
                id: bookingId
            },
            success: function(response){
                console.log(response);
                var obj = JSON.parse(response);
                var totalAmount = formatAmount(obj.amt);
                var paidAmount = formatAmount(obj.paid_amount);
                var balAmount = formatAmount(obj.amt - obj.paid_amount);

                $("#bookingId").val(bookingId);
                $("#totalAmount").text(totalAmount);
                $("#paidAmount").text(paidAmount);
                $("#balAmount").text(balAmount);
                $("#alert-modal_payment").modal('show');
            },
            error: function(){
                $("#spndeddelid").css("color", "red").text('Error while fetching repayment data.');
            }
        });
        
    });

    function formatAmount(amount){
        amount = parseFloat(amount);
        return isNaN(amount) ? '0.00' : amount.toFixed(2);
    }

    $("#saveRepayment").click(function(){
        var date = $("#repaymentDate").val();
        var payAmount = parseFloat($("#payAmount").val());
        var paymentMode = $("#paymentMode").val();
        var bookingId = $("#bookingId").val();
        var balAmount = parseFloat($("#balAmount").text());

        if (payAmount > balAmount) {
            $("#alert-modal_payment").modal('hide');
            $('#alert-modal1').modal('show', { backdrop: 'static' });
            $("#spndeddelid").css("color", "red").text('Pay amount cannot be greater than balance amount.');
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/annathanam_counter/save_repayment", 
            data: {
                date: date,
                pay_amount: payAmount,
                payment_mode: paymentMode,
                booking_id: bookingId
            },
            success: function(response){
                var obj = JSON.parse(response);
                if(obj.status){
                    $("#payAmount").val("");
                    $("#alert-modal_payment").modal('hide');
                    $("#spndeddelid").css("color", "green").text(obj.message);
                    $('#alert-modal1').modal('show', { backdrop: 'static' });
					$("#spndeddelid").css("color", "green");
                    setTimeout(function() {
						$('#alert-modal1').modal('hide');  // Optionally hide the modal before reloading
						window.location.reload();  // Reload the current page
					}, 2000);
                    
                } else {
                    $("#spndeddelid").css("color", "red").text(obj.message);
                }
            },
            error: function(){
                $("#spndeddelid").css("color", "red").text('Error while saving repayment.');
            }
        });
    });
});

    function confirm_modal(id) {
        $('#alert-modal').modal('show', {backdrop: 'static'});
        document.getElementById('del').setAttribute('onclick' , 'Del('+id+')');
        $("#spndelid").text("Are you sure to Delete "+$("#pay"+id).attr("data-id") + " prasadam?" );    
    }    
    function Del(id)
    {
        var act = "<?php echo base_url(); ?>/prasadam/delete/"+id;
        $( "#delete-form" ).append( "<form action='"+act+"'><button type='submit' id='delete"+id+"'>submit</button></form>");
        $( "#delete"+id).trigger( "click" );
    }

    $(document).on('click', '.btn-cancel', function(e) {
        e.preventDefault(); 
        //var bookingId = $(this).attr('href').split('/').pop();
        var bookingId = $(this).data('id');

        if (confirm('Are you sure you want to cancel this booking?')) {
            $.ajax({
                url: "<?php echo base_url(); ?>/annathanam_counter/update_booking_status", 
                type: 'POST',
                data: {
                    id: bookingId,
                    status: 3
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if(res.success) {
                        $("#spndeddelid").css("color", "green").text(res.message);
                        $('#alert-modal1').modal('show', { backdrop: 'static' });
                    } else {
                        $('#alert-modal1').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").css("color", "red").text(res.message);
                    }
                    setTimeout(function() {
                        window.location.replace("<?php echo base_url(); ?>/report_online/annathanam_report");
                    }, 2000); 
                },

                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
    
    
    $(document).ready(function(){        
        report = $('#datatables').DataTable({
            searching: false,
            dom: 'Bfrtip',
            buttons: [],
            paging: false,
            "ajax":{
                url: "<?php echo base_url(); ?>/donation_online/getIndPledgeListRep",
                dataType: "json",
                type: "POST",
                data: function ( data ) {
                    data.pledge_id = "<?php echo $pledge_id; ?>";
                    
                    }
            },
        });

        $('#submit').click(function() {
            report.ajax.reload();
        });

    });
</script>

</body>
