<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<style>
body { height:100vh; width:100%; }
.navbar-light .navbar-nav .nav-link {
    font-weight: 500;
}
/*.row { width:100%; }*/ 
.btn { padding: 0.25rem 0.395rem; height: 2rem; }
.product { /*height:500px;*/ max-height:67vh; overflow:auto; }
.cart { /*height:330px;*/ height:32vh; max-height:32vh; overflow:auto; width:100%; margin-bottom:10px; margin-top:10px; }
.nav-link { font-weight:bold; }
.nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
    color: #fff;
    background-color: #f1b330;
    border-color: #ebedf2 #ebedf2 #ffffff;
}
.nav-item a { color:#f1b330; }
.prasadam .nav-item { width:50%; }
.annathanam .nav-item { width:33.33%; }
.tab-content { text-align:left; }
.crd {
    background: #f1b33087;
    padding: 5px;
    display: flex
;
    align-items: center;
    margin: 10px 0;
    flex-wrap: nowrap;
    justify-content: space-between;
    border-radius: 5px;
}
.crd p { margin:0; }
.crd p:first-child { width:60%; }
.crd select { border:none !important; font-size:14px; }
p.qty {
    background: #df9c0e;
    padding: 8px 3px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    text-align: center;
    color: #fff;
}
</style>

<body class="sidebar-icon-only">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    
    
    <div class="container-fluid page-body-wrapper">
      
      <div class="main-panel">
        <div class="content-wrapper">
          <?php if($_SESSION['succ'] != '') { ?>
              <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
                  <div class="suc-alert" style="width: 100%;">
                      <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span> 
                      <p><?php echo $_SESSION['succ']; ?></p> 
                  </div>
              </div>
          <?php } ?>
            <?php if($_SESSION['fail'] != '') { ?>
              <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
                  <div class="alert" style="width: 100%;">
                      <span class="closebtn" onClick="this.parentElement.style.display='none';">&times;</span> 
                      <p><?php echo $_SESSION['fail']; ?></p>
                  </div>
              </div>
          <?php } ?>
        <div class="row">
        	<div class="col-md-6"><div class="card prasadam" style="padding:20px;">
                <div class="card-header" align="center">
                  <h5>Prasadam</h5> 
                </div>
                <div class="card-body" align="center">
                    <ul class="nav nav-tabs" role="tablist">
                		<li class="nav-item">
                			<a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">AM</a>
                		</li>
                		<li class="nav-item">
                			<a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">PM</a>
                		</li>
                	</ul><!-- Tab panes -->
                	<div class="tab-content">
                		<div class="tab-pane active" id="tabs-1" role="tabpanel">
                			<div class="crd"><p>Sakkara Ponggal<br>சக்கரைப் பொங்கல்</p>
                			<p class="qty">10</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		    <div class="crd"><p>Puli Satham<br> புளி சாதம் </p>
                			<p class="qty">5</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		</div>
                		<div class="tab-pane" id="tabs-2" role="tabpanel">
                			<div class="crd"><p>Sakkara Ponggal<br>சக்கரைப் பொங்கல்</p>
                			<p class="qty">10</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		</div>
                	</div>
                </div>
            </div></div>
            
            <div class="col-md-6"><div class="card annathanam" style="padding:20px;">
                <div class="card-header" align="center">
                  <h5>Annathanam</h5> 
                </div>
                <div class="card-body" align="center">
                    <ul class="nav nav-tabs" role="tablist">
                		<li class="nav-item">
                			<a class="nav-link active" data-toggle="tab" href="#tabs-3" role="tab">Breakfast</a>
                		</li>
                		<li class="nav-item">
                			<a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">Lunch</a>
                		</li>
                		<li class="nav-item">
                			<a class="nav-link" data-toggle="tab" href="#tabs-5" role="tab">Dinner</a>
                		</li>
                	</ul><!-- Tab panes -->
                	<div class="tab-content">
                		<div class="tab-pane active" id="tabs-3" role="tabpanel">
                			<div class="crd"><p>Sakkara Ponggal<br>சக்கரைப் பொங்கல்</p>
                			<p class="qty">10</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		    <div class="crd"><p>Puli Satham<br> புளி சாதம் </p>
                			<p class="qty">5</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		</div>
                		<div class="tab-pane" id="tabs-4" role="tabpanel">
                			<div class="crd"><p>Sakkara Ponggal<br>சக்கரைப் பொங்கல்</p>
                			<p class="qty">6</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		    <div class="crd"><p>Puli Satham<br> புளி சாதம் </p>
                			<p class="qty">9</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		</div>
                	    <div class="tab-pane" id="tabs-5" role="tabpanel">
                			<div class="crd"><p>Sakkara Ponggal<br>சக்கரைப் பொங்கல்</p>
                			<p class="qty">8</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		    <div class="crd"><p>Puli Satham<br> புளி சாதம் </p>
                			<p class="qty">15</p>
                			<select>
                			    <option value="1">Process</option>
                			    <option value="2">Completed</option>
                			</select>
                			</div>
                		</div>
                	</div>
                </div>
            </div></div>
        </div>
        
        
        
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
<!-- REPRINT SECTION END -->
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
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
  
  
  
  <!--script src='https://code.jquery.com/jquery-2.2.4.min.js'></script-->
  <script  src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>
  <script>
    $(document).ready(function() {
        $(".paid_ro").hide();

        $("input[name='pay_method']").change(function() {
            var selectedPaymentId = $("input[name='pay_method']:checked").val();
            var selectedPaymentName = $("input[name='pay_method']:checked").data('name');

            if (selectedPaymentName.toUpperCase() === "CASH") {
                $(".paid_ro").show();
            } else {
                $(".paid_ro").hide();
            }
        });
        $("input[name='pay_method']:checked").trigger('change');
    });
</script>
